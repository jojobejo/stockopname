# Memulihkan semua file QR yang hilang

1. Buka katalog Master Barang.
2. Klik **Reassign All QR Hilang**, di sebelah **Generate All QR Code**.
3. Konfirmasikan pemulihan, lalu tunggu progres selesai.
4. Periksa jumlah dipulihkan, dilewati dan gagal. Katalog dimuat ulang otomatis.

Hanya QR yang sudah tercatat pada katalog aktif dan file-nya hilang yang dibuatkan aset baru. QR yang masih tersedia, belum pernah digenerate atau hanya menjadi histori tidak diproses. Untuk penerbitan pertama tetap gunakan Generate All QR Code.

Payload QR tetap sama dan pemulihan tercatat pada Riwayat. Hasil opname tidak berubah. Jika koneksi terputus, jalankan kembali; file yang sudah tersedia dilewati. Jika katalog berubah selama batch, muat ulang dan jalankan kembali. Jika ada gagal, periksa laporan; QR yang berhasil tetap dapat dipakai.

Fitur ini untuk admin dashboard. UAT browser dan cetak fisik belum dilakukan pada development ini.
