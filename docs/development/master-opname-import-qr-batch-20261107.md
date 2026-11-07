# Master Opname dan Master Barang, 7 November 2026

## Penggunaan

- Data Master Barang: klik **Generate All QR Code**. Progres menunjukkan jumlah berhasil, dilewati, dan gagal. Jalankan lagi bila terputus; QR yang telah tersimpan dilewati. QR dicatat per barang dan expired date.
- Master Opname: siapkan CSV UTF-8 dengan header persis `kode_barang,nama_barang,qty,qty_box,qty_pcs,expired_date`. Tanggal `YYYY-MM-DD`; qty bilangan bulat tidak negatif. Klik **Upload Master Opname** atau **Upload Master Opname IT** sesuai sumber. Hasil dan baris salah tampil di bawah tombol. Bila ada baris salah, seluruh impor dibatalkan.
- Tiga tombol hapus menampilkan jumlah dan cakupan sebelum konfirmasi. **Hapus Semua Data** mencakup seluruh tabel; dua tombol lain hanya menghapus sumber yang tertera.

## Pengembangan

- Route dan controller menegakkan sesi admin yang sudah ada. Aksi impor, batch, dan hapus memakai POST dan token sesi untuk CSRF karena proteksi CSRF global CodeIgniter proyek masih nonaktif.
- Impor dibatasi 5 MB/10.000 baris, diverifikasi penuh sebelum transaksi insert. `id` tidak ditulis. Kolom angka di luar format diisi 0, teks wajib seperti `no_lot` diisi `0`, sedangkan status QR memakai enum `PENDING` dan timestamp memakai default database.
- Batch QR memproses paling banyak 10 varian per request. Kunci unik database melindungi pasangan barang dan expired date. File yang telah ada tidak ditimpa, dan status berhasil baru diberikan setelah pencatatan database sukses.
- Preview aset Master Opname dan tombol cetak khusus preview dihapus dari halaman. Status Master Opname mengambil keadaan QR tersimpan dan hasil pencocokan barang.

## Verifikasi lanjutan

Pemeriksaan lokal: lint PHP pada route, controller, model, dan kedua view lulus; sintaks JavaScript kedua view lulus. Uji `stockopname_zahir_catalog_test.php` dan `stockopname_qr_test.php` lulus. Uji tabel sementara di database lokal lulus untuk insert dua sumber dan auto-increment, batas batch 10, preservasi QR dengan kunci unik, empat kondisi status, serta cakupan ketiga aksi hapus. Uji ini tidak mengubah baris operasional.

Lakukan UAT browser dengan akun admin: impor kedua sumber, baris salah, progres/resume QR, status untuk barang cocok dan tidak cocok, serta ketiga cakupan hapus pada database uji yang memiliki cadangan. Periksa juga dependensi data opname sebelum menghapus seluruh master.
