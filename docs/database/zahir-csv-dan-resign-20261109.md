# Database impor CSV Zahir dan penerbitan QR - 9 November 2026

Menggantikan instruksi skema impor sebelumnya. Tidak ada migration ke database aplikasi terkonfigurasi atau production dalam pekerjaan ini. Skema target development dibaca read-only menggunakan tests/snapshot_schema_audit.php. Semua mutasi pengujian berada di database uji acak.

## Hasil audit

Master tb_master_barang_all tetap InnoDB/utf8mb4_unicode_ci, id INT PK AUTO_INCREMENT, kd_barang VARCHAR25 NOT NULL berindeks nonunik, kode_barang_system VARCHAR50 NOT NULL. P/L/T INT(3), dimensi INT(12), semuanya NOT NULL tanpa default DB; mekanisme katalog aplikasi memberikan nilai0 bila tidak disuplai. Supplier/bahan/satuan wajib dan diberi '-' oleh jalur katalog aktual. Barcode/qrcode/gudang/wilayah nullable; hpp DECIMAL15,2 default0. Duplikasi UPPER(TRIM(kd_barang))=0 saat audit lokal. stockopname_catalog_qrcode InnoDB utf8mb4_general_ci PK(catalog_id,expired_date) tetap tanpa perubahan skema. stockopname_master_item InnoDB dengan ID/qty/opname/QR legacy dipertahankan. Bukti lokal bukan verifikasi production; ulangi SHOW CREATE/INDEX/engine/collation/nullability/duplikasi/relasi di target sebelum instalasi.

## Pilih satu jalur migration

Instalasi belum memiliki stockopname_zahir_*: jalankan database/sql/stockopname_zahir_snapshot.sql lalu database/sql/stockopname_catalog_qr_issue.sql setelah backup/audit. Nama script snapshot dipertahankan untuk kompatibilitas distribusi, isinya kini skema CSV. Jangan menjalankan upgrade setelah fresh schema.

Instalasi yang sudah memakai migration snapshot lama: jalankan database/sql/stockopname_zahir_csv_upgrade.sql lalu database/sql/stockopname_catalog_qr_issue.sql. Upgrade mengubah exported_at/exported_epoch menjadi nullable, menambah input_format default legacy-json dan source_file nullable pada batch, menjadikan kolom ukuran row referensi nullable. Data lama tidak dihapus/diubah; default legacy-json membedakan provenance historis. DDL MySQL tidak transactional: jika terputus, periksa SHOW CREATE dan status tiap statement sebelum melanjutkan. Script sengaja bukan IF NOT EXISTS yang dapat menutupi ketidakcocokan.

## Skema dan relasi

stockopname_zahir_batch: PK lokal BIGINT; snapshot_id internal unik; checksum SHA256 bytes unik; source_instance/scope konfigurasi; exported_at/exported_epoch NULL pada CSV baru; input_format csv-v1; source_file; imported_at UTC; actor; row_count; master_changes before/after nama/identitas barang baru; previous_batch_id pointer batch yang benar-benar aktif sebelum commit.

stockopname_zahir_qrlotexp: PK lokal BIGINT; batch_id/source_row; kode_barang sumber asli VARCHAR25 dengan collation master; master_id INT; nama_sumber/nama_normalized; expired_date DATE NOT NULL; qty_lot DECIMAL24,6 signed; p/l/t/dimensi nullable hanya untuk kompatibilitas histori. Impor CSV baru tidak menulis ukuran tersebut. Ukuran runtime dibaca JOIN master_id -> tb_master_barang_all.id. UNIQUE(batch_id,kode_barang,expired_date), indeks master_id dan batch_id. Grain kode sumber+expired per batch. Null expired ditolak validasi sehingga uniqueness tidak mengandalkan NULL. Semua kode sumber disimpan sebelum grouping.

stockopname_zahir_active: PK(source_instance,scope) -> batch_id. Pointer diaktifkan dalam transaksi yang sama dengan master dan rows, batch lama disimpan. source_instance/scope/timestamp/provenance dinormalisasi di batch, bukan diduplikasi per row. stockopname_zahir_audit mencatat pelaku,UTC,checksum,identitas,cakupan,hasil,detail/error per baris.

stockopname_catalog_qr_issue baru: PK BIGINT; catalog_id INT; expired_date DATE; operation generate/resign; actor; reason VARCHAR500; previous_file nullable; qrcode_file/qrcode_value; issued_at UTC; indeks(catalog_id,expired_date,id). Satu stockopname_catalog_qrcode tetap pointer aset aktif pasangan barang/expired. Event immutable dipertahankan terpisah agar tidak mengganti kontrak/tabel QR lama. Tidak ada FK/UNIQUE baru pada master legacy. Integritas baru dijaga transaksi, lock dan akses penulis aplikasi; jangan mengedit pointer/row/audit manual.

## Backup dan rollback

Sebelum migration: backup konsisten seluruh database (mysqldump --single-transaction --routines --triggers --events), aset assets/qrcode/stockopname, konfigurasi dan revisi aplikasi. Credential gunakan option-file privat, tidak command line/log. Simpan checksum,waktu,counts,dan uji restore ke DB uji. Audit engine semua tabel terlibat wajib InnoDB. Tidak mengasumsikan dump repo adalah skema production.

Rollback data impor melalui POST pemulihan terpisah dengan snapshot aktif yang diharapkan dan konfirmasi admin. Kembalikan pointer previous_batch_id dan hanya nama existing yang belum berubah setelah impor. Master baru/ID/system/ukuran/QR/business data tetap disimpan. Jika nama sudah diedit lagi atau tidak ada predecessor, rollback otomatis ditahan; rekonsiliasi dari audit/backup. Reimport CSV historis ditahan; tidak ada klaim deteksi file lama yang belum pernah diterima karena tidak ada timestamp sumber.

Rollback resign: transaksi gagal otomatis mempertahankan QR/pointer lama dan membersihkan file baru yang gagal dipublikasikan. Setelah resign sukses, jangan hapus audit/aset lama. Untuk memperbaiki label lagi gunakan resign berikutnya dengan alasan; payload tidak berubah. Pemulihan historis pointer secara manual memerlukan audit dan rekonsiliasi, bukan restore seluruh master yang dapat menimpa business data setelah backup.

Rollback aplikasi: pertahankan tabel/data/indeks baru dan backup; jangan DROP tabel referensi/audit atau DELETE master. Production tetap membutuhkan reader CSV atau revisi reader kompatibel, karena direct tidak dapat menjangkau jaringan lokal. Hindari mengembalikan kode lama yang menganggap path QR deterministik setelah resign; pulihkan versi yang memahami pointer qrcode_file atau lakukan rekonsiliasi aset terdokumentasi terlebih dahulu.

Pengujian fresh/upgrade/rollback dilakukan hanya pada DB uji. Perubahan lama login/config/database pengguna tetap dipertahankan. Tidak mengklaim migrasi target terpasang atau UAT produksi selesai.
