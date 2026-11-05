# Database: Pengelompokan Data Master Barang — 5 November 2026

Tidak ada perubahan skema atau isi database. Data LOT Zahir yang sudah dikumpulkan per kode barang, nama barang, dan expired date tetap menjadi sumber. Pengelompokan tambahan berdasarkan nama barang dan expired date hanya dilakukan pada respons daftar untuk tampilan.

`tb_master_barang_all` tetap menyimpan satu identitas dan path QR per kode barang. Tidak ada penggabungan atau penghapusan kode, sehingga QR `BR|id` dan hasil scan tetap menunjuk ke barang yang benar. Proses cetak memvalidasi ulang masing-masing kode, nama, dan expired date terhadap sumber LOT.
