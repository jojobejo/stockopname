> Alur diperbarui 10 November 2026: [pilih CSV dan langsung impor](impor-csv-langsung.md), tanpa preview/konfirmasi tambahan. Catatan di bawah adalah alur sebelumnya.

# Modal Impor CSV Zahir

1. Buka katalog Master Barang, termasuk tab katalog pada Master Opname.
2. Klik card **Impor CSV** atau tombol **Impor CSV** pada header card data.
3. Modal menampilkan status impor saat ini dan formulir upload. Pilih CSV UTF-8 maksimal5MiB/10000rows. Nama dan ukuran file tampil setelah dipilih. P/L/T file tetap diabaikan.
4. Klik **Validasi dan preview**. Tunggu progres upload dan validasi; tombol tidak dapat diklik berulang saat proses berjalan.
5. Periksa laporan dalam modal. Jika ada error/konflik, perbaiki file dan upload ulang. Belum ada data referensi yang diaktifkan pada tahap preview.
6. Pastikan ekspor lengkap untuk cakupan yang tertera, centang konfirmasi lalu klik **Konfirmasi impor CSV**. Jangan menutup modal saat request berjalan.
7. Setelah berhasil, katalog dan banner status halaman dimuat ulang tanpa pindah halaman. Laporan hasil tetap dapat diperiksa dalam modal sebelum ditutup.
8. Jika permintaan terputus atau sesi berakhir, gunakan **Muat ulang formulir / status**. Jika konfirmasi sudah dikirim, periksa status dahulu sebelum mengulang; timeout tidak membuktikan transaksi gagal. Jika sesi berakhir, masuk kembali melalui halaman login.

Halaman impor standalone admin/stockopname/zahir-csv tetap tersedia. Pemulihan pada modal memakai aksi dan checkbox konfirmasi terpisah sebagaimana halaman tersebut. Ketentuan full replacement, checksum, preservasi master/QR/business data dan Resign tetap mengikuti docs/usage/zahir-csv-dan-resign.md.

Banner tidak lagi menampilkan Undefined property CI_Loader::$zahir_snapshot. Bila muncul pesan migration belum dipasang, minta pengelola memasang skema sesuai prosedur backup/migration; pekerjaan perbaikan modal tidak menjalankan migration secara otomatis.
