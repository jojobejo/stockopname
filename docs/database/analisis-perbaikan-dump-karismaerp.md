# Analisis perbaikan dump SQL KarismaERP

## Hasil pemeriksaan

File sumber `u676129830_karismaerp (2).sql` memakai `utf8mb4` bersama `utf8mb4_unicode_ci`; tidak ditemukan deklarasi kolom/tabel dengan `CHARACTER SET utf8` yang dipasangkan dengan `utf8mb4_unicode_ci`. Karena itu, pasangan charset/collation dalam sumber tidak menunjukkan penyebab langsung pesan `#1253` tersebut.

Ditemukan tiga tabel memakai collation `utf8mb4_0900_ai_ci`, collation MySQL 8 yang tidak tersedia pada MariaDB. Pada salinan hasil perbaikan, tiga pemakaian itu diganti menjadi `utf8mb4_unicode_ci` agar dump lebih kompatibel dengan MariaDB. Charset dan data tidak diubah.

## Artefak

- Sumber: `C:/Users/bram/Downloads/u676129830_karismaerp (2).sql`
- Salinan kompatibilitas: `C:/Users/bram/Downloads/u676129830_karismaerp_import-fixed.sql`
- Penggantian: 3 `utf8mb4_0900_ai_ci` menjadi `utf8mb4_unicode_ci`.

## Batas verifikasi

Isi file diperiksa untuk pasangan deklarasi charset/collation dan jumlah collation MySQL 8 yang diganti. Import ke server database belum dijalankan. Jika `#1253` tetap muncul pada file hasil perbaikan, periksa pesan lengkap, engine/versi server, serta konfigurasi charset database/koneksi pada proses import; penyebabnya tidak dapat dikonfirmasi dari dump sumber saja. Tidak ada perubahan aplikasi atau skema database yang dijalankan.
