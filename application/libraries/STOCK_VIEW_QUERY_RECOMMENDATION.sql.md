# STOCK VIEW QUERY RECOMMENDATION - KARISMA ERP

Tanggal rekomendasi: 2026-05-22  
Status: SQL di dokumen ini adalah rekomendasi. Jangan dieksekusi otomatis. Jalankan hanya setelah review, backup, dan approval eksplisit.

## Catatan Umum

View di bawah diarahkan ke model target:

- `tberp_stock_ledger` sebagai source of truth.
- `tberp_stock_batch` sebagai snapshot cepat.
- `tbso_stock_reservation` sebagai kompatibilitas reservation SO.
- `tb_stock_hold` sebagai hold aktif.
- `tb_master_barang_all`, `tb_gudang`, dan `tb_gudang_wilayah` sebagai referensi.

Dependensi penting:

- `tberp_stock_ledger.tipe` saat ini belum punya semua tipe yang direkomendasikan.
- Jika enum belum diubah, query yang menyebut `MUTASI_IN`, `MUTASI_OUT`, `HOLD`, `UNHOLD` adalah target desain dan perlu disesuaikan.
- `tbso_stock_reservation.exp_date` saat ini varchar dengan format sample `dd/mm/YYYY`.
- `tberp_stock_batch` belum punya `qty_hold`, sehingga view memakai agregasi dari `tb_stock_hold`.
- `wilayah_id` belum ada di batch/ledger; view wilayah memakai fallback mapping dari master barang dan perlu validasi bisnis.

## 1. v_stock_available

Fungsi bisnis: menyajikan stock yang bisa dipakai untuk picking dan validasi SO/DO.

```sql
-- REKOMENDASI SAJA, JANGAN DIEKSEKUSI TANPA APPROVAL
CREATE OR REPLACE VIEW v_stock_available AS
SELECT
    sb.kd_barang,
    mb.nama_barang,
    mb.satuan,
    mb.qty_min,
    mb.hpp,
    sb.gudang_id,
    g.nama_gudang,
    sb.no_lot,
    sb.expired_date,
    COALESCE(sb.qty_on_hand, 0) AS qty_on_hand,
    COALESCE(sb.qty_reserved, 0) AS qty_reserved,
    COALESCE(h.qty_hold, 0) AS qty_hold,
    (
        COALESCE(sb.qty_on_hand, 0)
        - COALESCE(sb.qty_reserved, 0)
        - COALESCE(h.qty_hold, 0)
    ) AS qty_available,
    CASE
        WHEN sb.expired_date < CURRENT_DATE THEN 1
        ELSE 0
    END AS is_expired,
    CASE
        WHEN sb.expired_date BETWEEN CURRENT_DATE AND DATE_ADD(CURRENT_DATE, INTERVAL 90 DAY) THEN 1
        ELSE 0
    END AS is_near_expired,
    sb.created_at,
    sb.update_at
FROM tberp_stock_batch sb
LEFT JOIN tb_master_barang_all mb
    ON mb.kd_barang = sb.kd_barang
LEFT JOIN tb_gudang g
    ON CAST(g.id_gudang AS CHAR) = sb.gudang_id
LEFT JOIN (
    SELECT
        kode_barang AS kd_barang,
        CAST(gudang_asal AS CHAR) AS gudang_id,
        COALESCE(no_lot, '') AS no_lot,
        STR_TO_DATE(exp_date, '%d/%m/%Y') AS expired_date,
        SUM(qty) AS qty_hold
    FROM tb_stock_hold
    WHERE status = 'HOLD'
    GROUP BY
        kode_barang,
        CAST(gudang_asal AS CHAR),
        COALESCE(no_lot, ''),
        STR_TO_DATE(exp_date, '%d/%m/%Y')
) h
    ON h.kd_barang = sb.kd_barang
   AND h.gudang_id = sb.gudang_id
   AND h.no_lot = COALESCE(sb.no_lot, '')
   AND h.expired_date = sb.expired_date;
```

Catatan:

- Untuk picking penjualan, filter `qty_available > 0` dan `is_expired = 0`.
- Jika `tb_stock_hold.exp_date` sudah dinormalisasi ke date, ganti `STR_TO_DATE`.

## 2. v_stock_batch_card

Fungsi bisnis: kartu stock per batch dari ledger dengan saldo berjalan.

```sql
-- REKOMENDASI SAJA, JANGAN DIEKSEKUSI TANPA APPROVAL
CREATE OR REPLACE VIEW v_stock_batch_card AS
SELECT
    l.id,
    l.created_at AS movement_at,
    l.kd_barang,
    mb.nama_barang,
    l.gudang_id,
    g.nama_gudang,
    l.no_lot,
    l.expired_date,
    l.tipe,
    l.ref_type,
    l.ref_no,
    l.qty,
    CASE
        WHEN l.tipe IN ('SALDO_AWAL', 'IN', 'RJUAL', 'ADJIN', 'MUTASI_IN') THEN l.qty
        WHEN l.tipe IN ('OUT', 'RBELI', 'ADJOUT', 'MUTASI_OUT') THEN -l.qty
        ELSE 0
    END AS qty_signed,
    SUM(
        CASE
            WHEN l.tipe IN ('SALDO_AWAL', 'IN', 'RJUAL', 'ADJIN', 'MUTASI_IN') THEN l.qty
            WHEN l.tipe IN ('OUT', 'RBELI', 'ADJOUT', 'MUTASI_OUT') THEN -l.qty
            ELSE 0
        END
    ) OVER (
        PARTITION BY l.kd_barang, l.gudang_id, COALESCE(l.no_lot, ''), l.expired_date
        ORDER BY l.created_at, l.id
    ) AS saldo_berjalan
FROM tberp_stock_ledger l
LEFT JOIN tb_master_barang_all mb
    ON mb.kd_barang = l.kd_barang
LEFT JOIN tb_gudang g
    ON CAST(g.id_gudang AS CHAR) = l.gudang_id;
```

Catatan:

- MariaDB 10.4 mendukung window function.
- Untuk performa, tambahkan index ledger `(kd_barang,gudang_id,no_lot,expired_date,created_at,id)`.

## 3. v_stock_movement

Fungsi bisnis: daftar movement stock siap audit, termasuk klasifikasi inbound/outbound/reservation.

```sql
-- REKOMENDASI SAJA, JANGAN DIEKSEKUSI TANPA APPROVAL
CREATE OR REPLACE VIEW v_stock_movement AS
SELECT
    l.id,
    l.created_at AS movement_at,
    l.kd_barang,
    mb.nama_barang,
    l.gudang_id,
    g.nama_gudang,
    l.no_lot,
    l.expired_date,
    l.tipe,
    CASE
        WHEN l.tipe IN ('SALDO_AWAL', 'IN', 'RJUAL', 'ADJIN', 'MUTASI_IN') THEN 'IN'
        WHEN l.tipe IN ('OUT', 'RBELI', 'ADJOUT', 'MUTASI_OUT') THEN 'OUT'
        WHEN l.tipe = 'RESERVE' THEN 'RESERVE'
        WHEN l.tipe = 'RELEASE' THEN 'RELEASE'
        ELSE 'OTHER'
    END AS movement_group,
    l.qty,
    CASE
        WHEN l.tipe IN ('SALDO_AWAL', 'IN', 'RJUAL', 'ADJIN', 'MUTASI_IN') THEN l.qty
        WHEN l.tipe IN ('OUT', 'RBELI', 'ADJOUT', 'MUTASI_OUT') THEN -l.qty
        ELSE 0
    END AS qty_on_hand_effect,
    CASE
        WHEN l.tipe = 'RESERVE' THEN l.qty
        WHEN l.tipe = 'RELEASE' THEN -l.qty
        ELSE 0
    END AS qty_reserved_effect,
    l.ref_type,
    l.ref_no
FROM tberp_stock_ledger l
LEFT JOIN tb_master_barang_all mb
    ON mb.kd_barang = l.kd_barang
LEFT JOIN tb_gudang g
    ON CAST(g.id_gudang AS CHAR) = l.gudang_id;
```

## 4. v_stock_expired_alert

Fungsi bisnis: daftar expired dan near expired stock.

```sql
-- REKOMENDASI SAJA, JANGAN DIEKSEKUSI TANPA APPROVAL
CREATE OR REPLACE VIEW v_stock_expired_alert AS
SELECT
    a.kd_barang,
    a.nama_barang,
    a.satuan,
    a.gudang_id,
    a.nama_gudang,
    a.no_lot,
    a.expired_date,
    a.qty_on_hand,
    a.qty_reserved,
    a.qty_hold,
    a.qty_available,
    DATEDIFF(a.expired_date, CURRENT_DATE) AS days_to_expired,
    CASE
        WHEN a.expired_date < CURRENT_DATE THEN 'EXPIRED'
        WHEN a.expired_date BETWEEN CURRENT_DATE AND DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY) THEN 'NEAR_30'
        WHEN a.expired_date BETWEEN DATE_ADD(CURRENT_DATE, INTERVAL 31 DAY) AND DATE_ADD(CURRENT_DATE, INTERVAL 90 DAY) THEN 'NEAR_90'
        ELSE 'OK'
    END AS alert_status
FROM v_stock_available a
WHERE a.qty_on_hand > 0
  AND a.expired_date <= DATE_ADD(CURRENT_DATE, INTERVAL 90 DAY);
```

## 5. v_stock_reorder

Fungsi bisnis: daftar barang yang perlu reorder berdasarkan minimum stock.

```sql
-- REKOMENDASI SAJA, JANGAN DIEKSEKUSI TANPA APPROVAL
CREATE OR REPLACE VIEW v_stock_reorder AS
SELECT
    mb.kd_barang,
    mb.nama_barang,
    mb.satuan,
    mb.kd_supplier,
    s.nama_suplier,
    mb.qty_min,
    COALESCE(SUM(a.qty_on_hand), 0) AS qty_on_hand,
    COALESCE(SUM(a.qty_reserved), 0) AS qty_reserved,
    COALESCE(SUM(a.qty_hold), 0) AS qty_hold,
    COALESCE(SUM(a.qty_available), 0) AS qty_available,
    CASE
        WHEN mb.qty_min > 0 AND COALESCE(SUM(a.qty_available), 0) <= mb.qty_min THEN 1
        ELSE 0
    END AS is_low_stock,
    GREATEST(mb.qty_min - COALESCE(SUM(a.qty_available), 0), 0) AS suggested_reorder_qty
FROM tb_master_barang_all mb
LEFT JOIN v_stock_available a
    ON a.kd_barang = mb.kd_barang
LEFT JOIN tb_suplier s
    ON s.kd_suplier = mb.kd_supplier
GROUP BY
    mb.kd_barang,
    mb.nama_barang,
    mb.satuan,
    mb.kd_supplier,
    s.nama_suplier,
    mb.qty_min;
```

## 6. v_stock_opname_diff

Fungsi bisnis: menghitung selisih opname dari input fisik legacy terhadap saldo batch. Ini view transisi sebelum modul opname baru.

```sql
-- REKOMENDASI SAJA, JANGAN DIEKSEKUSI TANPA APPROVAL
CREATE OR REPLACE VIEW v_stock_opname_diff AS
SELECT
    COALESCE(b.kd_barang, o.kd_barang) AS kd_barang,
    mb.nama_barang,
    COALESCE(b.gudang_id, o.gudang_id) AS gudang_id,
    g.nama_gudang,
    COALESCE(b.no_lot, o.no_lot) AS no_lot,
    COALESCE(b.expired_date, o.expired_date) AS expired_date,
    COALESCE(b.qty_buku, 0) AS qty_buku,
    COALESCE(o.qty_fisik, 0) AS qty_fisik,
    COALESCE(o.qty_fisik, 0) - COALESCE(b.qty_buku, 0) AS selisih_opname,
    CASE
        WHEN COALESCE(o.qty_fisik, 0) - COALESCE(b.qty_buku, 0) > 0 THEN 'ADJIN'
        WHEN COALESCE(o.qty_fisik, 0) - COALESCE(b.qty_buku, 0) < 0 THEN 'ADJOUT'
        ELSE 'MATCH'
    END AS recommended_adjustment_type
FROM (
    SELECT
        kd_barang,
        gudang_id,
        COALESCE(no_lot, '') AS no_lot,
        expired_date,
        SUM(qty_on_hand) AS qty_buku
    FROM tberp_stock_batch
    GROUP BY kd_barang, gudang_id, COALESCE(no_lot, ''), expired_date
) b
LEFT JOIN (
    SELECT
        kd_system AS kd_barang,
        wilayah AS gudang_id,
        '' AS no_lot,
        CASE
            WHEN STR_TO_DATE(exp_date, '%Y-%m-%d') IS NOT NULL THEN STR_TO_DATE(exp_date, '%Y-%m-%d')
            ELSE STR_TO_DATE(exp_date, '%d/%m/%Y')
        END AS expired_date,
        SUM(qty) AS qty_fisik
    FROM tb_ics_opname
    GROUP BY
        kd_system,
        wilayah,
        CASE
            WHEN STR_TO_DATE(exp_date, '%Y-%m-%d') IS NOT NULL THEN STR_TO_DATE(exp_date, '%Y-%m-%d')
            ELSE STR_TO_DATE(exp_date, '%d/%m/%Y')
        END
) o
    ON o.kd_barang = b.kd_barang
   AND o.gudang_id = b.gudang_id
   AND o.no_lot = b.no_lot
   AND o.expired_date = b.expired_date
LEFT JOIN tb_master_barang_all mb
    ON mb.kd_barang = COALESCE(b.kd_barang, o.kd_barang)
LEFT JOIN tb_gudang g
    ON CAST(g.id_gudang AS CHAR) = COALESCE(b.gudang_id, o.gudang_id);
```

Catatan:

- View ini belum full outer join. Untuk opname final, gunakan tabel session opname baru agar lot dan gudang konsisten.
- `tb_ics_opname` saat audit kosong, jadi view ini akan berguna setelah ada input.

## 7. v_stock_reconciliation

Fungsi bisnis: membandingkan batch snapshot dengan saldo dari ledger.

```sql
-- REKOMENDASI SAJA, JANGAN DIEKSEKUSI TANPA APPROVAL
CREATE OR REPLACE VIEW v_stock_reconciliation AS
SELECT
    k.kd_barang,
    mb.nama_barang,
    k.gudang_id,
    g.nama_gudang,
    k.no_lot,
    k.expired_date,
    COALESCE(b.qty_on_hand, 0) AS batch_qty_on_hand,
    COALESCE(l.ledger_qty_on_hand, 0) AS ledger_qty_on_hand,
    COALESCE(b.qty_on_hand, 0) - COALESCE(l.ledger_qty_on_hand, 0) AS diff_on_hand,
    COALESCE(b.qty_reserved, 0) AS batch_qty_reserved,
    COALESCE(r.reservation_qty_active, 0) AS reservation_qty_active,
    COALESCE(b.qty_reserved, 0) - COALESCE(r.reservation_qty_active, 0) AS diff_reserved,
    CASE
        WHEN ABS(COALESCE(b.qty_on_hand, 0) - COALESCE(l.ledger_qty_on_hand, 0)) > 0.0001
          OR ABS(COALESCE(b.qty_reserved, 0) - COALESCE(r.reservation_qty_active, 0)) > 0.0001
        THEN 'DIFF'
        ELSE 'OK'
    END AS reconciliation_status
FROM (
    SELECT kd_barang, gudang_id, COALESCE(no_lot, '') AS no_lot, expired_date
    FROM tberp_stock_batch
    UNION
    SELECT kd_barang, gudang_id, COALESCE(no_lot, '') AS no_lot, expired_date
    FROM tberp_stock_ledger
    UNION
    SELECT
        kd_barang,
        gudang_id,
        COALESCE(no_lot, '') AS no_lot,
        STR_TO_DATE(exp_date, '%d/%m/%Y') AS expired_date
    FROM tbso_stock_reservation
    WHERE status = 'active'
) k
LEFT JOIN (
    SELECT
        kd_barang,
        gudang_id,
        COALESCE(no_lot, '') AS no_lot,
        expired_date,
        SUM(qty_on_hand) AS qty_on_hand,
        SUM(COALESCE(qty_reserved, 0)) AS qty_reserved
    FROM tberp_stock_batch
    GROUP BY kd_barang, gudang_id, COALESCE(no_lot, ''), expired_date
) b
    ON b.kd_barang = k.kd_barang
   AND b.gudang_id = k.gudang_id
   AND b.no_lot = k.no_lot
   AND b.expired_date = k.expired_date
LEFT JOIN (
    SELECT
        kd_barang,
        gudang_id,
        COALESCE(no_lot, '') AS no_lot,
        expired_date,
        SUM(
            CASE
                WHEN tipe IN ('SALDO_AWAL', 'IN', 'RJUAL', 'ADJIN', 'MUTASI_IN') THEN qty
                WHEN tipe IN ('OUT', 'RBELI', 'ADJOUT', 'MUTASI_OUT') THEN -qty
                ELSE 0
            END
        ) AS ledger_qty_on_hand
    FROM tberp_stock_ledger
    GROUP BY kd_barang, gudang_id, COALESCE(no_lot, ''), expired_date
) l
    ON l.kd_barang = k.kd_barang
   AND l.gudang_id = k.gudang_id
   AND l.no_lot = k.no_lot
   AND l.expired_date = k.expired_date
LEFT JOIN (
    SELECT
        kd_barang,
        gudang_id,
        COALESCE(no_lot, '') AS no_lot,
        STR_TO_DATE(exp_date, '%d/%m/%Y') AS expired_date,
        SUM(qty_reserved) AS reservation_qty_active
    FROM tbso_stock_reservation
    WHERE status = 'active'
    GROUP BY kd_barang, gudang_id, COALESCE(no_lot, ''), STR_TO_DATE(exp_date, '%d/%m/%Y')
) r
    ON r.kd_barang = k.kd_barang
   AND r.gudang_id = k.gudang_id
   AND r.no_lot = k.no_lot
   AND r.expired_date = k.expired_date
LEFT JOIN tb_master_barang_all mb
    ON mb.kd_barang = k.kd_barang
LEFT JOIN tb_gudang g
    ON CAST(g.id_gudang AS CHAR) = k.gudang_id;
```

Catatan:

- Query ini akan menampilkan mismatch besar dengan data saat audit.
- Gunakan sebagai laporan audit, bukan sebagai dasar update otomatis.

## 8. v_stock_fefo_picking

Fungsi bisnis: rekomendasi batch picking berdasarkan FEFO.

```sql
-- REKOMENDASI SAJA, JANGAN DIEKSEKUSI TANPA APPROVAL
CREATE OR REPLACE VIEW v_stock_fefo_picking AS
SELECT
    a.kd_barang,
    a.nama_barang,
    a.gudang_id,
    a.nama_gudang,
    a.no_lot,
    a.expired_date,
    a.qty_on_hand,
    a.qty_reserved,
    a.qty_hold,
    a.qty_available,
    ROW_NUMBER() OVER (
        PARTITION BY a.kd_barang, a.gudang_id
        ORDER BY a.expired_date ASC, a.no_lot ASC
    ) AS fefo_rank,
    DATEDIFF(a.expired_date, CURRENT_DATE) AS days_to_expired
FROM v_stock_available a
WHERE a.qty_available > 0
  AND a.expired_date >= CURRENT_DATE;
```

Contoh pemakaian:

```sql
SELECT *
FROM v_stock_fefo_picking
WHERE kd_barang = 'QABAC01'
  AND gudang_id = '2'
ORDER BY fefo_rank
LIMIT 10;
```

## Query Pendukung Audit

### Rebuild batch preview dari ledger

```sql
-- SELECT preview saja, bukan update
SELECT
    kd_barang,
    gudang_id,
    COALESCE(no_lot, '') AS no_lot,
    expired_date,
    SUM(
        CASE
            WHEN tipe IN ('SALDO_AWAL', 'IN', 'RJUAL', 'ADJIN', 'MUTASI_IN') THEN qty
            WHEN tipe IN ('OUT', 'RBELI', 'ADJOUT', 'MUTASI_OUT') THEN -qty
            ELSE 0
        END
    ) AS qty_on_hand_from_ledger
FROM tberp_stock_ledger
GROUP BY kd_barang, gudang_id, COALESCE(no_lot, ''), expired_date;
```

### Cek batch minus dan reserved berlebih

```sql
SELECT *
FROM tberp_stock_batch
WHERE qty_on_hand < 0
   OR COALESCE(qty_reserved, 0) > COALESCE(qty_on_hand, 0);
```

### Cek orphan ledger

```sql
SELECT l.*
FROM tberp_stock_ledger l
LEFT JOIN tb_master_barang_all mb
    ON mb.kd_barang = l.kd_barang
WHERE mb.id IS NULL
LIMIT 50;
```

