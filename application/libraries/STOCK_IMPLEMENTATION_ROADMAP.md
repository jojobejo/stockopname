# STOCK IMPLEMENTATION ROADMAP - KARISMA ERP

Tanggal roadmap: 2026-05-22  
Tujuan: mengubah stock dari campuran legacy/reporting menjadi modul ERP yang ledger-first, audit-ready, dan scalable.

## Keputusan Awal

Keputusan source of truth target:

- `tberp_stock_ledger` adalah single source of truth target.
- `tberp_stock_batch` adalah snapshot cepat.
- Tabel legacy tetap dipakai sebagai referensi audit/migrasi sampai rekonsiliasi selesai.
- View legacy tidak boleh dijadikan sumber utama untuk fitur baru setelah ledger stabil.

Keputusan operasional:

- Jangan alter schema tanpa backup dan approval.
- Jangan migrate data sebelum mapping selesai.
- Jangan ubah flow produksi sekaligus. Gunakan fase paralel dan rekonsiliasi.

## Phase 1 - Database Audit Dan Reconciliation

Tujuan:

- Mengukur kondisi data aktual.
- Membuat daftar mismatch.
- Menentukan cut-off migrasi.

Pekerjaan:

1. Audit row count semua tabel stock.
2. Audit duplicate master, saldo awal, ICS, pre-DO, detail DO.
3. Audit orphan ledger, batch, SO reservation, LPB, DO, retur.
4. Bandingkan `tberp_stock_batch` vs `tberp_stock_ledger`.
5. Bandingkan `tberp_stock_batch` vs `v_stock_per_gudang`.
6. Bandingkan `qty_reserved` batch vs reservation aktif.
7. Identifikasi dokumen lama yang sudah/ belum masuk ledger.
8. Tentukan tanggal cut-off.
9. Buat laporan top mismatch per barang/gudang/lot/expired.
10. Freeze rule: data sebelum cut-off dimigrasi sekali, data sesudah cut-off wajib service baru.

Output:

- Laporan rekonsiliasi.
- Data cleanup list.
- Keputusan cut-off.
- Approval migrasi.

Kompleksitas: Tinggi.  
Risiko: Salah mapping tanggal/lot membuat saldo awal salah.

## Phase 2 - Stock Ledger Sebagai Source Of Truth

Tujuan:

- Semua movement baru masuk ledger.
- Ledger punya idempotency.
- Ledger bisa di-audit per dokumen.

Pekerjaan:

1. Finalisasi movement type.
2. Tambahkan desain idempotency key.
3. Buat `StockLedgerService`.
4. Integrasi LPB posted ke service.
5. Integrasi DO posted ke service.
6. Integrasi retur posted ke service.
7. Integrasi mutasi posted ke service.
8. Integrasi opname adjustment ke service.
9. Buat reversal flow.
10. Buat log posting.

Output:

- Semua transaksi baru masuk ledger.
- Tidak ada update stock langsung dari controller/model.
- Query stock card dari ledger.

Kompleksitas: Tinggi.  
Risiko: Flow lama masih menulis batch/view secara paralel dan menyebabkan double posting.

## Phase 3 - Batch Snapshot Stabilization

Tujuan:

- Batch menjadi cache cepat yang selalu sesuai ledger.

Pekerjaan:

1. Tambahkan konsep `qty_hold`.
2. Tambahkan rebuild batch dry-run.
3. Tambahkan job reconciliation ledger vs batch.
4. Tambahkan row locking pada pengurangan stock.
5. Terapkan update batch hanya dari `StockLedgerService`.
6. Buat alert jika batch dan ledger berbeda.
7. Buat mekanisme rebuild batch setelah approval.
8. Tambahkan index batch yang dibutuhkan FEFO.

Output:

- `qty_on_hand`, `qty_reserved`, `qty_hold`, `qty_available` stabil.
- Dashboard anomaly batch vs ledger.

Kompleksitas: Sedang sampai tinggi.  
Risiko: Deadlock jika lock order tidak konsisten.

## Phase 4 - Reservation SO/DO

Tujuan:

- Reservation akurat dan tidak double.
- DO posted mengonsumsi reservation dan mengurangi on hand.

Pekerjaan:

1. Standarkan status SO: `DRAFT`, `APPROVED`, `CANCELLED`, `COMPLETED`.
2. SO draft tidak reserve.
3. SO approved reserve via `StockReservationService`.
4. SO update/cancel release reservation lama.
5. DO draft tidak mengubah stock.
6. DO posted release reserved dan insert `OUT`.
7. Partial delivery update `qty_delivered`.
8. Buat reconciliation reservation harian.
9. Blok user double-click dengan idempotency dan UI state.
10. Tambahkan audit log approval dan posting.

Output:

- Available stock = on hand - reserved - hold.
- SO/DO tidak menyebabkan reserved menggantung.

Kompleksitas: Tinggi.  
Risiko: Status SO lama kosong/draft sudah punya reservation aktif.

## Phase 5 - FEFO Picking

Tujuan:

- Picking otomatis berdasarkan expired date paling dekat.

Pekerjaan:

1. Gunakan `v_stock_fefo_picking` atau query service setara.
2. Exclude expired.
3. Exclude hold.
4. Exclude reserved.
5. Buat rekomendasi multi-batch jika qty satu batch tidak cukup.
6. Tambahkan warning near expired.
7. Simpan pilihan FEFO ke detail SO/DO.
8. Audit override FEFO oleh user.

Output:

- Picking recommendation per SO/DO.
- Trace lot dan expired lebih kuat.

Kompleksitas: Sedang.  
Risiko: Data expired date legacy yang belum normal membuat urutan FEFO salah.

## Phase 6 - Opname Adjustment

Tujuan:

- Opname tidak overwrite stock, semua selisih menjadi adjustment ledger.

Pekerjaan:

1. Buat session opname.
2. Freeze snapshot buku.
3. Input fisik per barang/gudang/lot/expired.
4. Approval supervisor.
5. Generate `ADJIN/ADJOUT`.
6. Posting adjustment via `StockLedgerService`.
7. Dashboard selisih opname.
8. Lock session setelah posted.
9. Buat audit report per user/tim/wilayah.
10. Migrasi flow `tb_ics_opname` ke session baru.

Output:

- Selisih opname audit-ready.
- Tidak ada update langsung ke saldo.

Kompleksitas: Tinggi.  
Risiko: Opname legacy berbasis nama barang, bukan kode/lot lengkap.

## Phase 7 - Dashboard Dan Alert

Tujuan:

- User melihat satu versi saldo stock.
- Alert membantu tindakan operasional.

Pekerjaan:

1. Dashboard stock summary.
2. Alert expired.
3. Alert near expired.
4. Alert low stock.
5. Alert negative stock.
6. Alert reservation mismatch.
7. Alert ledger vs batch mismatch.
8. Stock card per batch.
9. Reconciliation screen.
10. Export audit.

Output:

- Dashboard stock terpadu.
- Operasional tidak perlu membuka view legacy berbeda-beda.

Kompleksitas: Sedang.  
Risiko: User membandingkan dashboard baru dengan laporan legacy yang belum reconcile.

## Phase 8 - Hardening Enterprise

Tujuan:

- Modul stock siap skala ERP.

Pekerjaan:

1. Foreign key setelah cleanup.
2. Unique key idempotency.
3. Standard status lookup table.
4. Movement type lookup table.
5. Audit columns semua dokumen.
6. Role/permission posting, reversal, override FEFO.
7. Deadlock retry.
8. Background reconciliation job.
9. Backup/restore drill.
10. Monitoring performance query.

Output:

- Stock module stabil untuk transaksi tinggi.
- Audit trail kuat.
- Risiko manipulasi manual turun.

Kompleksitas: Tinggi.  
Risiko: Constraint baru gagal jika data lama belum bersih.

## 10 Prioritas Implementasi Berikutnya

1. Buat laporan detail mismatch `tberp_stock_batch` vs `tberp_stock_ledger`.
2. Tentukan cut-off migrasi dan aturan freeze data lama.
3. Tambahkan desain idempotency tanpa mengubah schema dulu: definisikan key per flow.
4. Refactor LPB posting agar semua update stock lewat service.
5. Refactor SO reservation agar draft tidak reserve dan approved yang reserve.
6. Implement DO posted sebagai `OUT` ledger dan batch decrement.
7. Implement reconciliation reservation batch vs `tbso_stock_reservation`.
8. Standarkan parsing expired date di satu helper/service.
9. Buat query dashboard berbasis batch modern dengan label "beta/reconciled".
10. Susun approval perubahan schema untuk index, unique key, audit column, dan FK.

## 10 Risiko Teknis Terbesar

1. Batch vs ledger mismatch sangat besar.
2. View legacy dan batch modern bisa menampilkan saldo berbeda.
3. Status dokumen lama tidak standar.
4. SO draft sudah memiliki reservation aktif pada sample data.
5. DO posted belum jelas mengurangi batch modern.
6. Mutasi belum masuk ledger/batch modern.
7. Retur belum masuk ledger/batch modern.
8. Expired date legacy disimpan text.
9. Lot dan wilayah gudang belum konsisten.
10. Tidak ada idempotency sehingga double-click bisa double posting.

## Checklist Sebelum Coding

- [ ] Owner bisnis menyetujui source of truth target.
- [ ] Cut-off data disetujui.
- [ ] Movement type final disetujui.
- [ ] Status dokumen final disetujui.
- [ ] Mapping gudang dan wilayah gudang disetujui.
- [ ] Mapping tanggal legacy disetujui.
- [ ] Daftar dokumen lama yang harus dimigrasi disetujui.
- [ ] Daftar dokumen yang tidak boleh dimigrasi disetujui.
- [ ] Strategi idempotency disetujui.
- [ ] Strategi rollback/reversal disetujui.
- [ ] Backup database tersedia.
- [ ] Test database tersedia.

## Checklist Sebelum Production

- [ ] Tidak ada batch negative.
- [ ] Tidak ada reserved > on hand.
- [ ] Ledger vs batch diff = 0 untuk scope go-live.
- [ ] Reservation active vs batch reserved diff = 0.
- [ ] Semua DO posted punya ledger `OUT`.
- [ ] Semua LPB posted punya ledger `IN`.
- [ ] Semua mutasi posted punya ledger dua sisi.
- [ ] Semua retur posted punya ledger.
- [ ] Semua opname adjustment punya ledger `ADJIN/ADJOUT`.
- [ ] Idempotency key aktif.
- [ ] Row locking aktif pada pengurangan stock.
- [ ] Dashboard memakai view/query modern.
- [ ] View legacy diberi label legacy atau disembunyikan dari menu utama.
- [ ] Backup restore sudah diuji.
- [ ] User acceptance test selesai.

## Estimasi Kompleksitas Relatif

| Area | Kompleksitas | Catatan |
|---|---|---|
| Audit schema/data | Sedang | Query read-only dan laporan |
| Rekonsiliasi batch-ledger | Tinggi | Mismatch besar |
| Service ledger | Tinggi | Harus atomic dan idempotent |
| LPB integration | Sedang | Sudah ada batch/ledger awal |
| SO reservation | Tinggi | Status lama perlu dibersihkan |
| DO posted OUT | Tinggi | Harus sinkron dengan legacy DO |
| Mutasi | Sedang-tinggi | Perlu dua sisi dan hold |
| Retur | Sedang | Perlu definisi retur beli/jual |
| Opname adjustment | Tinggi | Perlu session dan approval |
| Dashboard | Sedang | Bergantung rekonsiliasi |
| FK/schema hardening | Tinggi | Baru aman setelah cleanup |

## Strategi Migrasi Aman

1. Jangan matikan legacy sebelum laporan modern match.
2. Jalankan ledger/batch modern paralel pada transaksi baru.
3. Buat reconciliation report harian.
4. Tampilkan status data: `LEGACY`, `MIGRATED`, `RECONCILED`.
5. Setelah diff 0 pada scope go-live, pindahkan UI utama ke query modern.
6. Kunci perubahan manual ke tabel legacy yang sudah dimigrasi.
7. Archive view legacy sebagai laporan pembanding saja.

