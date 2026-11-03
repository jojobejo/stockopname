# STOCK MODULE BLUEPRINT - KARISMA ERP

Tanggal blueprint: 2026-05-22  
Target: modul stock terintegrasi, akurat, audit-ready, dan scalable.

## Prinsip Modul Stock

Modul stock harus berdiri di atas satu aturan utama: semua perubahan stock wajib tercatat sebagai ledger yang immutable. Snapshot batch boleh diperbarui untuk performa, tetapi kebenaran historis tetap berasal dari ledger.

Keputusan desain:

1. `tberp_stock_ledger` menjadi single source of truth target setelah rekonsiliasi.
2. `tberp_stock_batch` menjadi snapshot cepat untuk query available, FEFO, dan validasi transaksi.
3. Semua dokumen stock harus punya lifecycle status yang jelas.
4. Draft tidak boleh mengubah `qty_on_hand`.
5. Approved SO hanya boleh reserve.
6. Posted DO baru mengurangi `qty_on_hand`.
7. Opname tidak overwrite stock langsung; selisih harus menjadi ledger `ADJIN` atau `ADJOUT`.
8. Mutasi posted harus menghasilkan dua sisi: keluar gudang asal dan masuk gudang tujuan.
9. Hold/reservation harus mengurangi available, bukan mengurangi on hand.
10. Setiap posting harus idempotent.

## Single Source Of Truth

### Status Saat Ini

`tberp_stock_ledger` dan `tberp_stock_batch` sudah tersedia, tetapi belum boleh langsung menggantikan view legacy. Alasannya:

- Ledger belum mencakup DO posted sebagai `OUT`.
- Ledger belum mencakup mutasi posted.
- Ledger belum mencakup retur dan opname adjustment.
- Batch vs ledger mismatch besar.
- Belum ada idempotency key.
- Belum ada row locking formal di semua flow stock.

### Status Target

| Layer | Tabel | Peran |
|---|---|---|
| Source of truth | `tberp_stock_ledger` | Jurnal semua movement stock |
| Snapshot | `tberp_stock_batch` | Saldo cepat per barang/gudang/lot/expired |
| Reservation | `tbso_stock_reservation`, `tberp_stock_ledger` tipe `RESERVE/RELEASE` | Reservasi SO/DO |
| Hold | `tb_stock_hold`, batch `qty_hold` target | Hold non-final |
| Master | `tb_master_barang_all`, `tb_gudang`, `tb_gudang_wilayah`, `tb_suplier`, `tb_satuan` | Referensi |
| Legacy/migration | `tb_saldo_awal`, `tb_ics*`, `tb_detail_do`, `tb_lpb*`, `stockopname_*` | Audit awal dan migrasi |

## Data Layer

### Master Data

Master barang utama:

- `tb_master_barang_all.kd_barang`
- `nama_barang`
- `satuan`
- dimensi `p/l/t`
- `berat`
- `kubikasi`
- `qty_min`
- `hpp`
- mapping lokasi default `id_gudang`, `id_wilayah`

Master gudang:

- `tb_gudang.id_gudang`
- `nama_gudang`
- `tipe`: `INDUK`, `ECERAN`, `EXPIRED`, perlu dipertimbangkan `HOLD`, `RUSAK`, `TITIPAN`
- `is_active`

Master wilayah gudang:

- `tb_gudang_wilayah.id_wilayah`
- `id_gudang`
- `nama_wilayah`
- `is_active`

### Ledger Data

Kolom minimal target ledger:

- `id`
- `movement_at`
- `kd_barang`
- `gudang_id`
- `wilayah_id` jika movement sampai level wilayah
- `no_lot`
- `expired_date`
- `qty`
- `tipe`
- `direction`
- `ref_type`
- `ref_no`
- `source_table`
- `source_id`
- `source_detail_id`
- `idempotency_key`
- `created_by`
- `created_at`
- `reversal_of_ledger_id`

Movement type standar:

| Tipe | Direction | Efek on hand | Contoh sumber |
|---|---|---:|---|
| `SALDO_AWAL` | IN | + | migrasi saldo awal |
| `IN` | IN | + | LPB/PO received |
| `OUT` | OUT | - | DO posted |
| `RESERVE` | HOLD | 0 | SO approved |
| `RELEASE` | RELEASE | 0 | SO cancel, SO to DO, reservation release |
| `MUTASI_OUT` | OUT | - | mutasi posted gudang asal |
| `MUTASI_IN` | IN | + | mutasi posted gudang tujuan |
| `RBELI` | OUT | - | retur pembelian ke supplier |
| `RJUAL` | IN | + | retur penjualan dari customer |
| `ADJIN` | IN | + | opname lebih |
| `ADJOUT` | OUT | - | opname kurang |
| `HOLD` | HOLD | 0 | hold internal |
| `UNHOLD` | RELEASE | 0 | release hold |

Catatan: enum saat ini belum punya `MUTASI_IN`, `MUTASI_OUT`, `HOLD`, `UNHOLD`. Jangan alter sebelum approval.

### Batch Data

Batch target:

- `kd_barang`
- `gudang_id`
- `wilayah_id` optional jika stock disajikan sampai wilayah/rak
- `no_lot`
- `expired_date`
- `qty_on_hand`
- `qty_reserved`
- `qty_hold`
- `qty_available` dihitung, tidak wajib disimpan
- `last_ledger_id`
- `updated_at`
- `updated_by`
- `version`

Unique key target:

```sql
(kd_barang, gudang_id, wilayah_id, no_lot, expired_date)
```

Jika wilayah belum stabil, gunakan sementara:

```sql
(kd_barang, gudang_id, no_lot, expired_date)
```

## Transaction Layer

Semua posting stock harus mengikuti pola:

1. Validasi dokumen.
2. Validasi status dokumen boleh posting.
3. Mulai database transaction.
4. Lock batch row yang akan dikurangi dengan `SELECT ... FOR UPDATE`.
5. Validasi available cukup.
6. Insert ledger dengan idempotency key.
7. Update batch snapshot.
8. Update status dokumen menjadi posted/released.
9. Commit.
10. Jika gagal, rollback dan catat error log.

Contoh pola pengurangan stock:

```sql
START TRANSACTION;

SELECT *
FROM tberp_stock_batch
WHERE kd_barang = ?
  AND gudang_id = ?
  AND no_lot = ?
  AND expired_date = ?
FOR UPDATE;

-- aplikasi validasi:
-- qty_on_hand - qty_reserved - qty_hold >= qty_keluar

INSERT INTO tberp_stock_ledger (...);

UPDATE tberp_stock_batch
SET qty_on_hand = qty_on_hand - ?,
    update_at = NOW()
WHERE id = ?;

COMMIT;
```

## Service Layer

Service target:

| Service | Tanggung jawab |
|---|---|
| `StockService` | Query summary, available, batch, card, expired, low stock |
| `StockLedgerService` | Posting movement, reversal, idempotency, rebuild batch |
| `StockReservationService` | Reserve/release SO/DO, sinkron reservation vs batch |
| `StockOpnameService` | Session opname, input fisik, approval selisih, generate adjustment |
| `StockReconciliationService` | Audit ledger vs batch vs legacy, anomaly report |

## Presentation Layer

Sajian stock yang disarankan:

1. Stock Summary
2. Stock Per Barang
3. Stock Per Batch/Lot
4. Stock Per Gudang
5. Stock Per Wilayah Gudang
6. Stock Available
7. Stock Reserved
8. Stock Hold
9. Stock Expired
10. Stock Near Expired
11. Stock Low/Minimum
12. Kartu Stock / Stock Card
13. Stock Movement Ledger
14. Stock Reconciliation
15. Stock Opname Difference
16. FEFO Picking Recommendation

## Definisi Formula

### qty_on_hand

Target dari batch:

```text
qty_on_hand = saldo fisik buku yang sudah posted pada batch
```

Target dari ledger:

```text
qty_on_hand =
  SUM(SALDO_AWAL + IN + RJUAL + ADJIN + MUTASI_IN)
  - SUM(OUT + RBELI + ADJOUT + MUTASI_OUT)
```

### qty_reserved

```text
qty_reserved = SUM(reservation aktif)
```

Dari batch:

```text
qty_reserved = tberp_stock_batch.qty_reserved
```

Dari reservation detail:

```text
qty_reserved = SUM(tbso_stock_reservation.qty_reserved WHERE status = 'active')
```

### qty_hold

```text
qty_hold = SUM(stock hold aktif yang belum release/cancel)
```

Saat ini berasal dari:

```text
tb_stock_hold.status = 'HOLD'
```

Target:

```text
tberp_stock_batch.qty_hold
```

### qty_available

```text
qty_available = qty_on_hand - qty_reserved - qty_hold
```

Aturan:

- `qty_available` tidak boleh minus untuk transaksi normal.
- Expired stock boleh on hand, tetapi tidak boleh available untuk picking penjualan normal.

### qty_expired

```text
qty_expired = SUM(qty_on_hand WHERE expired_date < CURRENT_DATE)
```

### qty_near_expired

```text
qty_near_expired = SUM(qty_on_hand WHERE expired_date BETWEEN CURRENT_DATE AND CURRENT_DATE + interval N day)
```

Default `N = 90` hari, bisa dibuat konfigurasi.

### qty_low_stock

```text
qty_low_stock = item dengan SUM(qty_available) <= tb_master_barang_all.qty_min
```

Jika `qty_min = 0`, item tidak dianggap low stock kecuali user mengaktifkan flag minimum.

### stock_value

```text
stock_value = qty_on_hand * hpp
```

Sumber HPP target:

- `tb_master_barang_all.hpp`, atau
- cost dari LPB terakhir per batch jika nanti tersedia.

Untuk audit-ready costing, gunakan cost layer per batch, bukan hanya master HPP.

### selisih_opname

```text
selisih_opname = qty_fisik - qty_buku
```

Jika positif: generate `ADJIN`.  
Jika negatif: generate `ADJOUT`.  
Jika nol: tidak ada movement.

### saldo_berjalan

```text
saldo_berjalan =
  SUM(qty_signed) OVER (
    PARTITION BY kd_barang, gudang_id, no_lot, expired_date
    ORDER BY movement_at, id
  )
```

`qty_signed`:

```text
IN type  = +qty
OUT type = -qty
HOLD/RESERVE/RELEASE = 0 untuk on hand, tetapi mempengaruhi reserved/hold balance
```

## Workflow Stock

### 1. Saldo Awal

Current:

- `tb_saldo_awal` berisi saldo awal legacy.
- Ledger sudah memiliki `SALDO_AWAL` 2.258 row.
- Batch tidak reconcile dengan ledger.

Target:

1. Freeze cut-off date.
2. Validasi duplicate saldo awal.
3. Validasi barang/gudang/lot/expired.
4. Insert ledger `SALDO_AWAL` satu kali dengan idempotency key.
5. Rebuild batch dari ledger.
6. Lock `tb_saldo_awal` sebagai migration reference, bukan source utama.

### 2. Barang Masuk Dari PO/LPB

Current:

- `create_lpb_from_tmp()` sudah insert LPB, detail, batch LPB.
- Kode update `tberp_stock_batch.qty_on_hand += qty`.
- Kode insert ledger `IN`.

Target flow:

1. LPB dibuat sebagai draft dari `tb_tmp_po_received`.
2. Checker validasi barang, lot, expired date, gudang.
3. LPB di-post.
4. Service insert ledger `IN`.
5. Service update batch dengan row lock.
6. PO remaining qty dihitung dari PO qty - LPB posted.

Skenario:

- PO 100 pcs barang A.
- LPB terima 60 pcs lot L1 exp 2027-01-01.
- Ledger `IN +60`.
- Batch A/L1/gudang naik 60.
- PO pending menjadi 40.

### 3. Barang Keluar Dari SO/DO

Current:

- SO membuat reservation dan update batch `qty_reserved`.
- DO legacy mengurangi stock dalam view `v_stock_out` berdasarkan `tb_detail_do.status = 4`.
- Belum terlihat posting ledger `OUT` dan pengurangan batch on hand pada DO posted.

Target flow:

1. SO draft tidak mengubah on hand/reserved.
2. SO approved membuat `RESERVE` dan menaikkan `qty_reserved`.
3. DO draft tidak mengubah on hand.
4. DO posted:
   - release reservation SO/DO terkait,
   - insert ledger `OUT`,
   - turunkan batch `qty_on_hand`,
   - turunkan batch `qty_reserved` jika sebelumnya reserved.
5. DO cancel/reversal insert ledger reversal, bukan delete.

### 4. Reservation Stock

Current:

- `tbso_stock_reservation` status `active/released`.
- Ledger `RESERVE/RELEASE` ada.
- Batch `qty_reserved` ada.
- Ada mismatch reserved satu item.

Target:

1. Reservation hanya dari dokumen approved.
2. Reservation harus per batch FEFO: barang, gudang, lot, expired date.
3. Release harus menunjuk reservation yang dilepas.
4. Reservation balance harus bisa direkonsiliasi dari ledger atau reservation table.
5. User double-click tidak boleh membuat reservation dobel.

### 5. Release Stock

Release terjadi saat:

- SO cancelled.
- SO diubah.
- SO diproses menjadi DO.
- DO posted mengonsumsi reservation.
- Reservation expired karena SLA.

Aturan:

```text
release_qty <= active_reserved_qty
```

Release tidak mengubah on hand. Release hanya mengurangi reserved.

### 6. Mutasi Antar Gudang

Current:

- Mutasi insert `tb_mutasi` dan `tb_detail_mutasi`.
- HOLD ke gudang 10 insert `tb_stock_hold`.
- Tidak ada ledger/batch update modern.

Target:

Mutasi normal:

1. Validasi available gudang asal.
2. Lock batch asal.
3. Insert ledger `MUTASI_OUT` untuk gudang asal.
4. Turunkan batch asal.
5. Insert ledger `MUTASI_IN` untuk gudang tujuan.
6. Naikkan batch tujuan.
7. Status mutasi `POSTED`.

Mutasi HOLD:

1. Validasi available.
2. Insert hold `HOLD`.
3. Naikkan `qty_hold`.
4. Tidak mengubah on hand final.
5. Saat release/post final, baru movement final dibuat.

### 7. Retur Beli

Retur beli berarti barang keluar ke supplier.

Target:

- Header/detail retur status `APPROVED/POSTED`.
- Insert ledger `RBELI`.
- Turunkan batch on hand.
- Wajib pilih lot dan expired date.
- Tidak boleh retur melebihi available.

### 8. Retur Jual

Retur jual berarti barang masuk dari customer.

Target:

- Validasi faktur DO asal jika tersedia.
- Insert ledger `RJUAL`.
- Naikkan batch on hand.
- Jika barang rusak/expired, masuk gudang sesuai kondisi, bukan otomatis gudang induk.

### 9. Stock Opname

Current:

- Opname legacy menulis `tb_ics_opname` atau `stockopname_opname`.
- Selisih dihitung oleh view/query legacy.
- Belum ada adjustment ledger.

Target:

1. Buat session opname.
2. Freeze snapshot buku dari batch/ledger.
3. Input fisik per barang/gudang/lot/expired.
4. Supervisor approve selisih.
5. Generate `ADJIN` atau `ADJOUT`.
6. Rebuild/update batch.
7. Simpan audit reason.

### 10. Adjustment

Adjustment hanya boleh lewat dokumen adjustment yang approved.

Jenis:

- `ADJIN`: koreksi tambah.
- `ADJOUT`: koreksi kurang.

Tidak boleh:

- update langsung `qty_on_hand`,
- overwrite saldo batch tanpa ledger,
- delete ledger lama.

### 11. Hold Stock

Hold mengurangi available, bukan on hand.

```text
qty_available = qty_on_hand - qty_reserved - qty_hold
```

Hold dipakai untuk:

- karantina,
- mutasi hold,
- order internal,
- quality check,
- expired/rusak sebelum diputuskan.

### 12. Expired Stock

Expired stock adalah batch dengan `expired_date < CURRENT_DATE`.

Aturan:

- Tidak boleh muncul di FEFO picking penjualan normal.
- Boleh muncul di dashboard expired.
- Boleh dimutasi ke gudang expired/rusak.
- Retur/adjustment harus tetap bisa dilakukan dengan approval.

### 13. Stock Per Lot

Stock per lot wajib memakai key:

```text
kd_barang + gudang_id + no_lot + expired_date
```

Jika lot kosong:

- Jangan pakai empty string.
- Gunakan nilai standar `NOLOT` hanya jika bisnis menyetujui.
- Lebih baik kolom nullable dengan validasi kategori barang yang tidak butuh lot.

### 14. Stock Per Gudang

Stock per gudang dihitung dari batch:

```sql
SELECT kd_barang, gudang_id, SUM(qty_on_hand) AS qty_on_hand
FROM tberp_stock_batch
GROUP BY kd_barang, gudang_id;
```

### 15. Stock Per Wilayah Gudang

Perlu stabilisasi schema karena batch belum menyimpan `wilayah_id`. Opsi:

1. Tambah `wilayah_id` pada batch dan ledger.
2. Gunakan mapping default barang ke `tb_master_barang_all.id_wilayah`, tetapi ini kurang akurat jika barang berpindah lokasi.
3. Buat tabel lokasi batch terpisah: `tberp_stock_location_batch`.

Rekomendasi tegas: tambahkan `wilayah_id` pada ledger/batch setelah desain lokasi disetujui.

## Dashboard Stock

Kartu dashboard minimum:

1. Total SKU aktif.
2. Total batch aktif.
3. Total qty on hand.
4. Total stock value.
5. Qty reserved.
6. Qty hold.
7. Qty available.
8. Expired batch count.
9. Near expired batch count.
10. Low stock SKU count.
11. Negative stock anomaly.
12. Ledger vs batch mismatch count.

Drilldown:

- Barang -> batch -> movement card.
- Gudang -> wilayah -> barang -> batch.
- Expired alert -> batch -> source LPB/ledger.
- Reservation -> SO -> detail reservation -> release history.
- Opname diff -> adjustment document -> ledger.

## Aturan Transaksi Stock

Aturan wajib:

1. Semua perubahan stock masuk ledger.
2. Batch table hanya snapshot saldo cepat.
3. Update stock wajib dalam database transaction.
4. Pengurangan stock wajib row locking.
5. Posting wajib idempotent.
6. Hanya dokumen `POSTED/APPROVED` yang boleh mengubah stock.
7. DO/SO draft tidak boleh mengurangi on hand.
8. SO approved hanya reserve.
9. DO posted baru mengurangi on hand.
10. Mutasi HOLD tidak mengubah stock final.
11. Mutasi POSTED mengurangi asal dan menambah tujuan.
12. Opname tidak boleh overwrite stock langsung, harus lewat `ADJIN/ADJOUT`.
13. Delete dokumen posted dilarang; gunakan reversal.
14. Tanggal expired wajib valid date.
15. Lot wajib untuk barang yang butuh batch tracking.

## Risiko Utama Dan Mitigasi

| Risiko | Dampak | Mitigasi |
|---|---|---|
| Batch dan ledger tidak reconcile | saldo salah | Phase rekonsiliasi sebelum go-live |
| Double posting LPB/DO | stock dobel | idempotency key |
| Stock minus | gagal fulfilment/audit | row lock dan validasi available |
| Expired date beda format | FEFO salah | normalisasi date |
| Lot kosong/tidak konsisten | kartu stock salah | standardisasi lot |
| Reservation tidak sinkron | available salah | reserve service dan reconciliation job |
| View legacy beda batch | user melihat saldo berbeda | label legacy, migrasi dashboard ke view baru |
| Rollback transaksi parsial | batch/ledger beda | DB transaction atomic |
| User double-click | double insert | unique idempotency dan UI disabled |
| Data lama tanpa status posting | double counting | cut-off dan migration map |

