# Database: QR dan input opname berdasarkan Data Master Barang — 5 November 2026

**Tidak ada perubahan skema database, migrasi, atau pembaruan data yang dijalankan dalam development ini.** Tidak ada SQL yang perlu dieksekusi untuk memasang perubahan kode.

Sumber pasangan barang dan tanggal adalah Data Master Barang LOT Zahir berqty positif. `tb_master_barang_all` menyediakan ID, kode, nama, dan dimensi lokal. Karena tabel lokal itu tidak memiliki kolom expired date dan satu kode dapat memiliki beberapa tanggal, QR per tanggal disimpan sebagai file `assets/qrcode/stockopname/MB_<id>_<YYYYMMDD>.png`, bukan sebagai satu path dalam kolom `tb_master_barang_all.qrcode`.

Saat user menyimpan scan, aplikasi menambah baris ke `stockopname_opname` dengan kode, nama, dan expired date yang divalidasi ulang dari master. `scan_code` berisi `MB|<id>|<tanggal>`, `input_source` berisi `scan_qrcode`, `source_id` berisi `0`, dan `no_lot` berisi `-`. Ini mencegah `source_id` secara keliru menunjuk ID dari tabel master yang berbeda. Input manual memakai pasangan master yang sama dan tetap menulis jejak pada tabel manual yang sudah ada. Baris lama dan file QR lama tidak diubah atau dihapus secara otomatis.

Konsekuensi operasional: laporan yang memecah hasil per lot akan melihat input QR pada lot `-`. Jika bisnis memerlukan hitungan per lot, perlu keputusan dan rancangan relasi lot sebelum data scan digabung ke lot tertentu. Validasi data produksi dan UAT laporan belum dilakukan.
