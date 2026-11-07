# Master Opname dan Data Master Barang Zahir

## Perubahan aplikasi

- Kedua halaman merender kerangka lebih dahulu. Tabel memakai request DataTables, sedangkan ringkasan Master Opname memakai endpoint summary QR. Perhitungan Qty 0 di controller saat render awal dihilangkan.
- Refresh Master Opname memuat ulang tabel pada halaman yang sama, mempertahankan halaman pagination, dan memuat ulang ringkasan serta daftar kegagalan QR. Tombol dinonaktifkan selama request tabel berlangsung. Filter dan pencarian tetap berada di form.
- Refresh Zahir memaksa pembacaan sumber, lalu memuat ulang tabel pada posisi pagination yang sama. Tombol dinonaktifkan selama proses. Respons cache diberi label cache; kegagalan atau cache kedaluwarsa menampilkan pesan dan tidak diperlakukan sebagai verifikasi baru.
- Sidebar pada kedua halaman dimulai tertutup. Item menu dinamis yang cocok dengan route diberi kelas aktif tanpa membuka parent submenu otomatis.
- Card `Total Barang Qty 0` di Data Master Opname dihapus dari tampilan. Route Qty 0, data, dan endpoint terkait tetap tersedia.
- Aturan status QR, validasi, hak akses, generate, preview, dan cetak tidak diubah oleh pekerjaan ini. Perubahan lain yang sudah ada di worktree tidak dibatalkan.

## Cara penggunaan

1. Buka `admin/stockopname/master_opname` atau `admin/stockopname/master_barang`. Kerangka tampil sebelum tabel selesai dimuat.
2. Gunakan filter, pencarian, dan pagination. Tekan **Refresh** untuk memperbarui data tanpa navigasi ulang.
3. Pada halaman Zahir, label cache berarti data belum diverifikasi pada request saat ini. Tekan **Refresh**; bila sumber gagal, pesan kesalahan ditampilkan dan data cache tidak diklaim terbaru.
4. Sidebar dimulai tertutup; buka secara manual bila ingin berpindah menu. Item halaman aktif tetap ditandai.

## Verifikasi

- PHP lint pada controller, kedua view, sidebar, dan library: lulus.
- JavaScript inline kedua view diperiksa dengan `node --check` setelah ekspresi PHP diganti placeholder: lulus.
- UAT browser terautentikasi untuk pemuatan awal, Refresh, kegagalan Zahir, filter, pencarian, pagination, serta sidebar pada kedua route belum dilakukan. Perlu diuji dengan akun berhak akses dan sumber Zahir yang bisa dibuat gagal untuk skenario negatif.
