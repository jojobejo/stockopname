# Eksekusi migration lokal CSV Zahir - 10 November 2026

Migration berhasil dijalankan pada koneksi development lokal database kiucoid_karismaerp. Target diperiksa lewat konfigurasi aktif tanpa menampilkan kredensial. Sebelum eksekusi terdapat12tabel, sekitar5MB menurut information_schema; kelima tabel fitur belum ada. Karena itu dipakai jalur fresh, bukan ALTER upgrade snapshot lama.

## Skema yang dipasang

- stockopname_zahir_batch
- stockopname_zahir_active
- stockopname_zahir_qrlotexp
- stockopname_zahir_audit
- stockopname_catalog_qr_issue

Sumber SQL: database/sql/stockopname_zahir_snapshot.sql dan database/sql/stockopname_catalog_qr_issue.sql. Seluruh tabel baru InnoDB. Batch CSV memakai input_format=csv-v1, source_file dan waktu impor; exported_at/epoch nullable karena CSV tidak membawa waktu sumber. Kolom ukuran row referensi nullable untuk kompatibilitas, tetapi importer tidak mengisi P/L/T CSV. Grain UNIQUE(batch_id,kode_barang,expired_date); expired wajib terisi. Relasi master memakai master_id lokal, bukan membuat ulang ID/QR. Tidak ALTER/UNIQUE/FK tabel lama.

Alur yang berlaku: pilih file -> Impor sekarang -> validasi penuh di server -> rencana internal/fingerprint -> lock/transaksi -> aktivasi dan audit -> hasil ringkas. Tidak membutuhkan preview/konfirmasi UI atau metadata manual. Migration hanya menyiapkan skema; tidak otomatis mengimpor lampiran, tidak membuat Master Opname, dan tidak menghasilkan QR.

## Backup dan bukti

Backup di luar document root:
C:/xampp/backups/stockopname/20261109_183254_0b48fe/before.sql

Ukuran1848349byte. SHA256:
5ec2905ed04320e773e954c740f80fbc4d463cb8fa71c6b02ed0a62740bb471f

Folder yang sama memuat before.json, after.json dan migration-result.json serta log tool backup/restore. Nama folder adalah artefak waktu host saat eksekusi; tanggal dokumen mengikuti konteks pengguna10November2026. File kredensial sementara tidak disimpan dalam artefak dan telah dibuang.

Backup penuh menggunakan single-transaction/routines/triggers/events/hex-blob. Restore diuji ke database acak terpisah stockopname_migration_verify_* lalu dibandingkan per12tabel: jumlah row, CHECKSUM TABLE EXTENDED, SHA256 SHOW CREATE TABLE. Seluruhnya identik. Database restore uji dibuang setelah verifikasi.

Sebelum dan sesudah DDL,12tabel operasional dibandingkan dengan cara sama. Tidak ada perubahan row/checksum/skema, termasuk master/QR/opname/pending dan tabel bisnis lain. Lima tabel baru terverifikasi kosong. Tidak ada impor data bisnis atau perubahan ID/AUTO_INCREMENT tabel lama.

## Perintah dan verifikasi

Runner CLI khusus local fresh: database/tools/migrate_zahir_csv_local.php --apply. Runner menolak host non-local, target yang sudah memiliki tabel fitur, dan lock impor/master/QR yang sedang berjalan. Jangan menjalankan runner ini lagi untuk upgrade; baca audit skema dan gunakan jalur upgrade terdokumentasi jika diperlukan. DDL MySQL tidak transactional; jika runner terhenti, periksa migration-error.json/created_tables sebelum melanjutkan, jangan DROP tabel yang sudah dipakai.

Read-only health: tests/stockopname_zahir_local_health.php. Lulus pemanggilan model aktual M_Zahir_snapshot::ready, pembacaan library imported dengan refresh tanpa jaringan Zahir, dan verifikasi5tabel baru kosong/InnoDB. tests/stockopname_zahir_csv_upgrade_test.php dan tests/stockopname_zahir_views_test.php juga lulus. Syntax PHP runner/audit/health diperiksa.

## Rollback

Perubahan bersifat tambahan. Pilihan aman rollback aplikasi adalah mempertahankan kelima tabel beserta audit/historinya. Jangan restore backup penuh di atas data operasional yang telah berubah setelah backup. Jika hendak membatalkan skema sebelum penggunaan pertama, pastikan semua tabel fitur masih kosong, tidak ada importer berjalan, backup tetap valid, dan tidak ada dependensi baru. Jangan DROP setelah ada batch/QR issue yang dipakai bisnis. Pemulihan data setelah impor memakai aksi pemulihan existing yang mempertahankan ID/master/QR/histori; rujuk zahir-csv-dan-resign-20261109.md.

Tidak ada deployment atau migration production. UAT browser upload langsung, kamera dan cetak fisik belum dilakukan. Schema terpasang tidak berarti file CSV telah diterima; upload melalui modal masih perlu dilakukan pengguna.
