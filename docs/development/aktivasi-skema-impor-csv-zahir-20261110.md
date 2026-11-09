# Aktivasi skema development impor CSV Zahir - 10 November 2026

Skema fitur kini telah dipasang pada database lokal kiucoid_karismaerp melalui fresh migration. Tidak ada perubahan controller/view/aturan bisnis untuk eksekusi ini: alur tetap pilih CSV lalu Impor sekarang, validasi server dan aktivasi atomik. Tidak otomatis mengimpor CSV lampiran.

Berkas tambahan: database/tools/migrate_zahir_csv_local.php (CLI lokal, backup dan verifikasi restore sebelum DDL), tests/stockopname_zahir_migration_audit.php (audit read-only), tests/stockopname_zahir_local_health.php (readiness kode aktual dan reader imported), serta dokumentasi database/usage tanggal ini. SQL fitur existing dipakai tanpa perubahan.

Backup penuh di luar web root berhasil direstore ke database uji dan dibandingkan dengan sumber. Setelah migration, data/checksum/skema12tabel existing tetap sama,5tabel baru kosong/InnoDB. Model ready dan library imported refresh pada target lokal lulus. Kode QR, master ID, ukuran dan hasil opname tidak dimutasi. Detail artefak dan rollback: docs/database/eksekusi-migration-csv-zahir-20261110.md.

User kini dapat memuat ulang katalog dan mengimpor CSV melalui modal. Status belum ada referensi aktif merupakan empty state yang benar sampai impor pertama berhasil. Tidak ada koneksi/fallback ke jaringan Zahir untuk pemeriksaan imported. Belum UAT browser, upload nyata pada target operasional, kamera atau cetak fisik. Tidak ada migration/deploy production.
