# Catatan database: perbandingan collation `kode_barang`

## Dampak database

Tidak ada perubahan skema, data, charset tabel, atau collation database. Perbaikan hanya mengubah ekspresi perbandingan di query aplikasi agar kedua nilai dikonversi ke `utf8mb4` sebelum menggunakan `utf8mb4_general_ci`.

## Catatan operasional

Jika error yang sama masih muncul setelah perubahan diterapkan, periksa query aktual dan kolom lain yang menerapkan `COLLATE utf8mb4_general_ci` secara langsung. Koneksi proyek saat ini menggunakan charset `utf8`.
