# Impor CSV setelah migration lokal

Skema impor sudah terpasang pada database development lokal kiucoid_karismaerp. Belum ada file CSV yang diimpor oleh proses migration.

1. Muat ulang katalog Stockopname.
2. Buka card Impor CSV.
3. Pilih ekspor CSV lengkap, lalu klik Impor sekarang.
4. Tunggu hasil dan pembaruan katalog otomatis.

Alur tanpa preview tetap berlaku. P/L/T CSV diabaikan; validasi tanggal/kode/qty/duplikat dan konflik tetap berjalan sebelum penyimpanan. Hasil opname dan QR existing tidak diubah oleh impor. Migration tidak berarti file sudah diimpor; data baru aktif setelah upload berhasil.

Backup dan laporan verifikasi tersedia di C:/xampp/backups/stockopname/20261109_183254_0b48fe/. Jangan memulihkan backup penuh di atas data yang telah berubah tanpa rekonsiliasi. Ikuti panduan pemulihan impor untuk kembali ke referensi sebelumnya. Environment production belum dimigrasi oleh pekerjaan ini.
