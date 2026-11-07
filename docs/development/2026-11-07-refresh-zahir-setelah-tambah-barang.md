# Refresh Zahir setelah Tambah Barang

Tombol **Refresh** pada tabel Data Master Barang Zahir sekarang memaksa pengambilan Zahir melalui request DataTables yang juga menggambar tabel. Ini menghindari request kedua yang membaca cache dan mengganti status menjadi “Sumber Zahir belum terverifikasi” sesaat setelah refresh berhasil. Filter, pencarian, dan halaman pagination dipertahankan.

Pada halaman Master Opname, setelah **Tambah Master Opname** berhasil, tombol Refresh dipicu. Request tabel Master Opname memeriksa Zahir; setelah selesai, tabel Data Master Barang Zahir yang tertanam juga menjalankan refresh Zahir. Tombol Refresh Zahir dinonaktifkan selama proses dan kesalahan sumber ditampilkan.

Cara pakai: pada Master Opname, tambah data melalui tombol **Tambah Barang** lalu simpan dan tunggu indikator Refresh selesai. Untuk sinkronisasi manual, tekan **Refresh** pada tabel yang relevan. Jika Zahir gagal, data cache tidak ditandai sebagai hasil verifikasi baru.

Verifikasi: PHP lint dan `node --check` untuk dua view lulus. UAT browser terautentikasi untuk tambah barang, refresh, kegagalan Zahir, filter, pencarian, dan pagination belum dilakukan.
