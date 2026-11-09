# Penggunaan Impor Data Zahir CSV dan Resign QR

## Persiapan administrator

Pasang migration sesuai kondisi fresh/upgrade pada docs/database/zahir-csv-dan-resign-20261109.md, setelah backup dan audit skema. Pekerjaan development belum memasang migration pada database aplikasi/production. PHP BCMath dan GD/library QR lokal wajib; siapkan worker512MiB, timeout120detik, post_max_size>=8M, upload_max_filesize>=5M dan TEMP privat di luar document root. Bersihkan staging so_zahir_ yang ditinggalkan sesuai masa sesi/preview tanpa menghapus file yang masih digunakan.

Default lokal/testing menggunakan imported dan perbandingan master lokal. Direct masih bisa dipilih non-production melalui ZAHIR_CATALOG_MODE=direct. Pada hosting set CI_ENV=production secara eksplisit: aplikasi memaksa imported dan menggunakan CSV sebagai referensi master QR. Host/IP browser bukan penentu lingkungan. Konfigurasi ZAHIR_SOURCE_INSTANCE dan ZAHIR_SCOPE harus stabil. Refresh halaman tidak menerima ekspor baru.

## Impor CSV

1. Ekspor CSV lengkap dari Zahir, UTF-8. Gunakan lampiran apa adanya dengan header kode_barang,nama_barang,expired_date,qty_lot,p,l,t. P/L/T diabaikan; boleh kosong atau tidak valid. Empat kolom pertama saja juga diterima. Contoh sintetis dan kontrak ada di docs/contracts/zahir-csv-v1.md dan zahir-csv-v1.example.csv. Jangan upload JSON/SQL.
2. Masuk admin dashboard. Buka **Impor Data Zahir CSV** dari Master Barang lokal/katalog atau admin/stockopname/zahir-csv. Menu ini berbeda dari impor master lokal dan impor Master Opname. Tidak perlu mengisi UUID/waktu ekspor/metadata.
3. Upload (maksimum5MiB/10000rows) dan tekan **Validasi dan preview**. Periksa jumlah valid/error/duplikat, barang baru, perubahan nama before/after, konflik, referensi missing dan kelompok aktif/nonaktif. Lokal juga menampilkan hasil compare kode/nama/ukuran master. Nomor row tidak menghitung header. Tanggal wajib YYYY-MM-DD, qty desimal bertitik. Error satu row/duplikat/kosong menahan seluruh impor.
4. Pastikan file benar-benar ekspor lengkap untuk cakupan yang tertera; file ini akan mengganti seluruh referensi aktif. Centang konfirmasi dan klik **Konfirmasi impor CSV**. Tanpa waktu sumber, sistem tidak dapat mengetahui apakah file yang belum pernah diterima lebih lama/parsial. Waktu upload bukan waktu ekspor. Periksa kelengkapan melalui proses exporter.
5. Preview berlaku satu jam. Tab/file preview lain menggantikan preview sebelumnya; konfirmasi lama ditolak. Master berubah atau impor lain berjalan: periksa pesan dan ulangi preview setelah keadaan stabil. Snapshot aktif lama tidak diganti jika transaksi gagal.
6. File identik yang masih aktif menghasilkan no-op, termasuk tidak memperbarui ulang nama master yang sudah diedit. Perbedaan lokal pada file identik hanya laporan; rekonsiliasi master melalui alur edit yang sah. File historis yang pernah diterima memerlukan pemulihan terpisah. File berbeda tanpa waktu sumber tidak otomatis dianggap lebih baru; admin bertanggung jawab memastikan versinya.
7. Katalog hanya menampilkan kelompok nama+expired dengan qty total positif. Missing/qty nol-negatif tidak menghapus master/QR/histori. Nama existing dapat berubah sesuai preview yang dikonfirmasi; ukuran existing dan metadata lain tetap. Barang baru berdimensi0 sampai ukuran lokal dilengkapi; QR/pcs dapat digunakan sesuai validasi, input box belum diizinkan.
8. Periksa banner: nama file, waktu impor UTC, pelaku, cakupan dan usia sejak impor. Waktu ekspor sumber tidak tersedia. Mode imported bekerja tanpa akses IP Zahir; tidak ada fallback ke jaringan/cache HTML. Empty state meminta upload, error migration meminta pemasangan skema.

## Generate, Resign dan Riwayat QR

Generate pada katalog adalah penerbitan pertama pasangan ID master+expired. Sesudah tercatat, tombol Generate nonaktif dan server menolak generate ulang, termasuk jika aset hilang. Generate All melewati QR yang sudah tercatat, tidak menerbitkan ulang semuanya.

Untuk label/aset yang perlu dibuat ulang: klik **Resign QR**, isi alasan5-500karakter, periksa konfirmasi, lalu lanjutkan. Admin saja yang dapat memakai fitur ini. Aplikasi membuat aset baru, mengganti pointer aktif dan mencatat pelaku/alasan/waktu/old-new asset. Label/aset lama tetap disimpan. Jika status telah berubah sejak halaman dibuka, muat ulang dahulu; jangan spam klik. Gagal pembuatan/DB tidak membatalkan QR sebelumnya.

Resign di sini adalah penerbitan ulang aset, bukan tanda tangan digital, pencabutan label atau scan sekali pakai. Payload tetap MB|id_master_lokal|YYYY-MM-DD. Label lama tetap sah selama barang/expired masih menjadi referensi aktif. Jika kode representatif atau expired berubah akibat CSV baru, QR lama tidak lagi tersedia untuk scan berdasarkan aturan katalog, walau file/historinya tetap ada.

Klik **Riwayat** untuk melihat sampai50event generate/resign terbaru dalam UTC, pelaku dan alasan. QR legacy dapat belum punya event penerbitan awal; resign melalui fitur baru tetap dicatat. Cetak/preview/summary menggunakan aset aktif setelah resign, bukan nama file lama.

Di lokal, nama/kode sumber dibandingkan dengan master lokal dan mismatch menahan QR/scan. Production memakai nama/expired CSV aktif tanpa menuntut nama lokal sama; ID dan ukuran tetap berasal dari master lokal. Impor/Resign tidak mengubah hasil opname, pending, approval, transaksi saldo atau menulis balik ke Zahir.

## Pemulihan dan UAT

Pemulihan impor memakai tombol terpisah **Pulihkan impor sebelumnya** setelah backup dan konfirmasi cakupan. Nama existing hanya dikembalikan jika belum diedit lagi; ID/ukuran/master baru/QR/histori tetap disimpan. Jika ditahan, rekonsiliasi berdasarkan audit, jangan menghapus master atau menimpa seluruh database tanpa memperhitungkan data bisnis setelah backup.

Sebelum produksi, UAT akun admin/nonadmin, upload valid/gagal/terputus, dua tab preview, CSRF, local compare dan production CSV, generate single/batch, repeat/missing asset, Resign/Riwayat, error/concurrent, semua consumer saat IP Zahir diblokir, kamera dan cetak fisik. Pengujian otomatis menggunakan database uji lokal; bukan UAT browser atau deployment production.
