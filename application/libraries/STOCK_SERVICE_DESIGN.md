# STOCK SERVICE DESIGN - KARISMA ERP

Tanggal desain: 2026-05-22  
Framework target: CodeIgniter 3 / PHP  
Status: desain teknis, belum implementasi final.

## Prinsip Implementasi Service

1. Controller tidak boleh update stock langsung.
2. Semua posting stock lewat service.
3. Semua service posting memakai database transaction.
4. Pengurangan stock memakai row lock.
5. Semua posting punya idempotency key.
6. Ledger immutable: jangan update/delete ledger posted, gunakan reversal.
7. Batch adalah snapshot, selalu bisa di-rebuild dari ledger.
8. Error harus jelas dan bisa ditampilkan ke user/log.

## Struktur Library Yang Disarankan

Lokasi target:

```text
application/libraries/StockService.php
application/libraries/StockLedgerService.php
application/libraries/StockReservationService.php
application/libraries/StockOpnameService.php
application/libraries/StockReconciliationService.php
```

Pola dependency:

```php
$this->load->library('StockLedgerService');
$this->load->library('StockReservationService');
$this->load->library('StockService');
```

Setiap service dapat mengakses CI instance:

```php
$this->CI =& get_instance();
$this->db = $this->CI->db;
```

## 1. StockService

Tanggung jawab:

- Query stock untuk UI/API.
- Stock summary.
- Stock per barang/gudang/wilayah/batch.
- Expired/near expired/low stock.
- Kartu stock read-only.
- FEFO recommendation read-only.

### Method: getSummary

```php
public function getSummary(array $filters = []): array
```

Parameter:

| Nama | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `gudang_id` | int/string | tidak | filter gudang |
| `kd_barang` | string | tidak | filter barang |
| `supplier_id` | string | tidak | filter supplier |
| `expired_status` | string | tidak | `OK`, `NEAR`, `EXPIRED` |

Return:

```php
[
    'total_sku' => 0,
    'total_batch' => 0,
    'qty_on_hand' => 0.0,
    'qty_reserved' => 0.0,
    'qty_hold' => 0.0,
    'qty_available' => 0.0,
    'stock_value' => 0.0,
    'expired_batch' => 0,
    'near_expired_batch' => 0,
    'low_stock_sku' => 0,
]
```

Validasi:

- `gudang_id` harus ada di `tb_gudang` jika dikirim.
- `kd_barang` harus ada di `tb_master_barang_all` jika dikirim.

### Method: getAvailable

```php
public function getAvailable(array $filters = [], int $limit = 100, int $offset = 0): array
```

Output per row:

```php
[
    'kd_barang' => 'QABAC01',
    'nama_barang' => '...',
    'gudang_id' => '2',
    'nama_gudang' => 'Gdg. Induk',
    'no_lot' => '0',
    'expired_date' => '2028-06-01',
    'qty_on_hand' => 1500.0,
    'qty_reserved' => 100.0,
    'qty_hold' => 0.0,
    'qty_available' => 1400.0,
]
```

Aturan:

- Default hanya tampil `qty_available > 0`.
- Default exclude expired untuk picking, tetapi dashboard boleh include expired.

### Method: getBatchCard

```php
public function getBatchCard(string $kd_barang, string $gudang_id, string $no_lot, string $expired_date): array
```

Return:

- daftar movement dari ledger,
- saldo berjalan,
- ref dokumen,
- movement group.

Validasi:

- `expired_date` dinormalisasi ke `Y-m-d`.
- Jika batch tidak ada, return array kosong, bukan error.

### Method: getFefoRecommendation

```php
public function getFefoRecommendation(string $kd_barang, string $gudang_id, float $qtyNeeded, array $options = []): array
```

Return:

```php
[
    'is_fulfillable' => true,
    'qty_needed' => 500.0,
    'qty_allocated' => 500.0,
    'lines' => [
        [
            'kd_barang' => 'QABAC01',
            'gudang_id' => '2',
            'no_lot' => '0',
            'expired_date' => '2028-06-01',
            'qty_pick' => 500.0,
            'qty_available_before' => 1400.0,
        ],
    ],
]
```

Aturan:

- Urut `expired_date ASC`, lalu `no_lot ASC`.
- Exclude expired.
- Jika `allow_near_expired = false`, exclude near expired sesuai threshold.

## 2. StockLedgerService

Tanggung jawab:

- Posting semua movement.
- Insert ledger.
- Update batch snapshot.
- Idempotency.
- Reversal.
- Rebuild batch dari ledger.

### Method: postMovement

```php
public function postMovement(array $movement): array
```

Parameter minimal:

| Nama | Tipe | Wajib |
|---|---|---|
| `tipe` | string | ya |
| `kd_barang` | string | ya |
| `gudang_id` | string/int | ya |
| `no_lot` | string | ya untuk batch item |
| `expired_date` | string/date | ya untuk batch item |
| `qty` | decimal | ya |
| `ref_type` | string | ya |
| `ref_no` | string | ya |
| `source_table` | string | disarankan |
| `source_id` | int/string | disarankan |
| `source_detail_id` | int/string | disarankan |
| `idempotency_key` | string | ya |
| `created_by` | string | ya |

Return:

```php
[
    'status' => true,
    'ledger_id' => 123,
    'idempotent' => false,
    'batch_after' => [
        'qty_on_hand' => 100.0,
        'qty_reserved' => 0.0,
        'qty_hold' => 0.0,
    ],
]
```

Validasi:

- `qty > 0`.
- `tipe` harus movement type valid.
- Barang aktif.
- Gudang aktif.
- Expired date valid.
- Lot tidak kosong untuk barang batch-tracked.
- `idempotency_key` unik.
- Untuk tipe OUT/RBELI/ADJOUT/MUTASI_OUT, available harus cukup kecuali role override enterprise.

Transaction:

1. `trans_begin`.
2. Cek idempotency.
3. Lock batch row untuk movement yang mempengaruhi batch.
4. Insert ledger.
5. Update/insert batch.
6. Commit.

Error handling:

```php
[
    'status' => false,
    'error_code' => 'INSUFFICIENT_STOCK',
    'message' => 'Stock available tidak cukup',
    'context' => [...],
]
```

### Method: postBulkMovements

```php
public function postBulkMovements(array $movements, array $options = []): array
```

Gunakan untuk LPB multi-line, DO multi-line, mutasi, opname adjustment.

Aturan:

- Semua movement dalam satu transaction.
- Jika satu line gagal, semua rollback.
- Urutan lock harus konsisten untuk mencegah deadlock: sort by `kd_barang`, `gudang_id`, `no_lot`, `expired_date`.

### Method: reverseMovement

```php
public function reverseMovement(int $ledgerId, string $reason, string $createdBy): array
```

Aturan:

- Tidak update/delete ledger lama.
- Insert movement kebalikan.
- Isi `reversal_of_ledger_id`.
- Hanya ledger yang belum pernah direverse boleh direverse.

### Method: rebuildBatchFromLedger

```php
public function rebuildBatchFromLedger(array $filters = [], bool $dryRun = true): array
```

Mode:

- `dryRun = true`: hanya hitung hasil dan diff.
- `dryRun = false`: update batch setelah approval eksplisit.

Return dry-run:

```php
[
    'status' => true,
    'total_keys' => 2000,
    'mismatch_count' => 1769,
    'total_abs_diff' => 5293879.0,
    'samples' => [...],
]
```

## 3. StockReservationService

Tanggung jawab:

- Reserve stock untuk SO approved.
- Release reservation.
- Consume reservation saat DO posted.
- Sinkronisasi `tbso_stock_reservation`, ledger `RESERVE/RELEASE`, dan batch `qty_reserved`.

### Method: reserveSalesOrder

```php
public function reserveSalesOrder(string $noSo, array $details, string $gudangId, string $createdBy): array
```

Input detail:

```php
[
    [
        'id_so_detail' => 10,
        'kd_barang' => 'QABAC01',
        'qty' => 100,
        'no_lot' => '0',
        'expired_date' => '2028-06-01',
        'no_faktur' => 'INV...',
    ],
]
```

Aturan:

- SO harus `APPROVED`.
- Draft tidak boleh reserve.
- Available harus cukup.
- Gunakan row lock.
- Buat reservation row `active`.
- Insert ledger `RESERVE`.
- Naikkan batch `qty_reserved`.
- Idempotency key per `SO_DETAIL_RESERVE:{id_so_detail}`.

### Method: releaseSalesOrder

```php
public function releaseSalesOrder(string $noSo, string $reason, string $releasedBy): array
```

Alasan:

- `CANCELLED`
- `UPDATED`
- `CONVERTED_TO_DO`
- `EXPIRED`

Aturan:

- Ambil reservation active.
- Lock batch.
- Insert ledger `RELEASE`.
- Turunkan batch `qty_reserved`.
- Update reservation menjadi `released`.
- Tidak boleh membuat `qty_reserved` minus.

### Method: consumeReservationForDo

```php
public function consumeReservationForDo(string $kdDo, string $noSo, array $doDetails, string $postedBy): array
```

Aturan:

- Release reservation terkait.
- Insert ledger `OUT` untuk DO posted.
- Turunkan `qty_on_hand`.
- Turunkan `qty_reserved`.
- Update `qty_delivered` pada SO detail jika perlu.

### Method: reconcileReservation

```php
public function reconcileReservation(array $filters = []): array
```

Membandingkan:

- batch `qty_reserved`,
- `tbso_stock_reservation` active,
- ledger `RESERVE - RELEASE`.

Return:

```php
[
    'mismatch_count' => 1,
    'rows' => [...],
]
```

## 4. StockOpnameService

Tanggung jawab:

- Session opname.
- Snapshot buku.
- Input fisik.
- Approval selisih.
- Posting adjustment.

### Method: createSession

```php
public function createSession(array $payload): array
```

Payload:

| Nama | Keterangan |
|---|---|
| `session_no` | nomor opname |
| `gudang_id` | gudang opname |
| `wilayah_id` | optional |
| `scope` | `ALL`, `BY_SUPPLIER`, `BY_ITEM`, `BY_LOCATION` |
| `cutoff_at` | waktu freeze |
| `created_by` | user |

Return:

```php
[
    'status' => true,
    'session_id' => 1,
    'session_no' => 'OPN202605220001',
]
```

### Method: captureBookSnapshot

```php
public function captureBookSnapshot(int $sessionId): array
```

Aturan:

- Ambil saldo dari batch pada cutoff.
- Simpan snapshot, jangan hitung ulang terus saat user input.
- Jika belum ada tabel session opname baru, gunakan staging sementara setelah desain schema disetujui.

### Method: submitPhysicalCount

```php
public function submitPhysicalCount(int $sessionId, array $line, string $inputBy): array
```

Line:

```php
[
    'kd_barang' => 'QABAC01',
    'gudang_id' => '2',
    'wilayah_id' => null,
    'no_lot' => '0',
    'expired_date' => '2028-06-01',
    'qty_fisik' => 1200,
]
```

Validasi:

- Session open.
- Barang/gudang valid.
- Expired valid.
- Qty fisik tidak negatif.
- User hanya boleh input wilayah/tim sesuai hak akses.

### Method: calculateDifference

```php
public function calculateDifference(int $sessionId): array
```

Return:

```php
[
    'lines' => [
        [
            'kd_barang' => 'QABAC01',
            'qty_buku' => 1000,
            'qty_fisik' => 980,
            'selisih' => -20,
            'recommended_tipe' => 'ADJOUT',
        ],
    ],
]
```

### Method: postAdjustment

```php
public function postAdjustment(int $sessionId, string $approvedBy): array
```

Aturan:

- Session harus approved.
- Setiap selisih menghasilkan ledger `ADJIN` atau `ADJOUT`.
- Gunakan `StockLedgerService::postBulkMovements`.
- Idempotency key per session line.
- Setelah posting, session status `POSTED`.

## 5. StockReconciliationService

Tanggung jawab:

- Audit ledger vs batch.
- Audit batch vs reservation.
- Audit modern stock vs legacy view.
- Orphan checker.
- Duplicate checker.
- Laporan risiko sebelum migrasi.

### Method: auditSchema

```php
public function auditSchema(): array
```

Return:

- daftar tabel,
- kolom,
- index,
- FK eksplisit,
- tipe bermasalah.

### Method: compareLedgerBatch

```php
public function compareLedgerBatch(array $filters = []): array
```

Formula ledger:

```text
SALDO_AWAL + IN + RJUAL + ADJIN + MUTASI_IN
- OUT - RBELI - ADJOUT - MUTASI_OUT
```

Return:

```php
[
    'mismatch_count' => 1769,
    'total_abs_diff' => 5293879.0,
    'top_diff' => [...],
]
```

### Method: compareReservationBatch

```php
public function compareReservationBatch(array $filters = []): array
```

Membandingkan:

```text
tberp_stock_batch.qty_reserved
vs
SUM(tbso_stock_reservation.qty_reserved WHERE status='active')
```

### Method: compareLegacyViewBatch

```php
public function compareLegacyViewBatch(array $filters = []): array
```

Membandingkan:

- `v_stock_per_gudang.qty`
- `tberp_stock_batch.qty_on_hand`

Catatan:

- Ini hanya audit transisi karena view legacy tidak memasukkan reservation.

### Method: findOrphans

```php
public function findOrphans(): array
```

Check:

- ledger barang/gudang orphan,
- batch barang/gudang orphan,
- LPB detail tanpa header,
- reservation tanpa SO detail,
- DO detail tanpa header,
- saldo awal tanpa master/wilayah.

### Method: findDuplicates

```php
public function findDuplicates(): array
```

Check:

- duplicate master barang,
- duplicate supplier,
- duplicate saldo awal natural key,
- duplicate ICS,
- duplicate DO/pre-DO.

## Error Handling Standar

Gunakan format error konsisten:

```php
[
    'status' => false,
    'error_code' => 'ERROR_CODE',
    'message' => 'Pesan yang bisa dibaca user',
    'technical_message' => 'Detail teknis untuk log',
    'context' => [],
]
```

Kode error disarankan:

| Kode | Arti |
|---|---|
| `VALIDATION_FAILED` | input tidak valid |
| `ITEM_NOT_FOUND` | barang tidak ditemukan |
| `WAREHOUSE_NOT_FOUND` | gudang tidak ditemukan |
| `BATCH_NOT_FOUND` | batch tidak ditemukan |
| `INSUFFICIENT_STOCK` | available tidak cukup |
| `DOCUMENT_NOT_APPROVED` | dokumen belum boleh posting |
| `DOCUMENT_ALREADY_POSTED` | idempotency/document sudah posted |
| `LEDGER_INSERT_FAILED` | gagal insert ledger |
| `BATCH_UPDATE_FAILED` | gagal update batch |
| `RECONCILIATION_DIFF` | hasil tidak sinkron |
| `DEADLOCK_RETRY_EXCEEDED` | retry deadlock habis |

## Logging

Setiap posting harus log:

- user,
- IP/user agent jika dari web,
- dokumen sumber,
- before/after qty batch,
- ledger id,
- idempotency key,
- error jika rollback.

Tabel log bisa:

- `tberp_stock_posting_log` target baru, atau
- log aplikasi CodeIgniter sementara.

Jangan log password atau credential.

## Validasi Bisnis Wajib

| Area | Validasi |
|---|---|
| Barang | `kd_barang` ada dan aktif |
| Gudang | `gudang_id` ada dan aktif |
| Lot | wajib jika item batch-tracked |
| Expired | valid date; tidak boleh kosong untuk barang batch-tracked |
| Qty | > 0; decimal sesuai satuan |
| Available | cukup untuk OUT/RBELI/ADJOUT/MUTASI_OUT |
| Status dokumen | hanya APPROVED/POSTED boleh posting |
| Idempotency | key belum pernah sukses |
| Reservation | release tidak boleh melebihi active reserved |
| Opname | adjustment hanya dari selisih approved |

## Contoh Integrasi Flow

### LPB Posted

Controller:

```php
$result = $this->stockledgerservice->postBulkMovements($movements);
if (!$result['status']) {
    show_error($result['message']);
}
```

Movement line:

```php
[
    'tipe' => 'IN',
    'kd_barang' => $row['kd_barang'],
    'gudang_id' => $header['gudang_id'],
    'no_lot' => $row['no_lot'],
    'expired_date' => $row['expired_date'],
    'qty' => $row['qty_diterima'],
    'ref_type' => 'LPB',
    'ref_no' => $header['kd_po'],
    'source_table' => 'tb_lpb_detail',
    'source_detail_id' => $idDetailLpb,
    'idempotency_key' => 'LPB:' . $idDetailLpb . ':IN',
    'created_by' => $user,
]
```

### DO Posted

Steps:

1. Validate DO status.
2. `consumeReservationForDo`.
3. Insert `OUT`.
4. Release reservation.
5. Update DO status.

### Stock Opname Adjustment

Steps:

1. Calculate diff.
2. Supervisor approve.
3. For each diff:
   - positive: `ADJIN`,
   - negative: `ADJOUT`.
4. Post bulk movement.
5. Mark session posted.

