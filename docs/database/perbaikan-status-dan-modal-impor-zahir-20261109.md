# Database: perbaikan status dan modal impor CSV - 9 November 2026

Tidak ada perubahan skema database (no schema change). Tidak ada migration, ALTER, penghapusan data, atau impor ke database aplikasi/production yang dijalankan untuk perbaikan ini.

Perubahan memperbaiki akses model melalui instance controller di partial status dan menambahkan tampilan modal pada backend impor yang telah tersedia. GET formulir/status tidak memutasi data operasional; konfirmasi tetap memakai transaksi, lock, checksum, fingerprint dan audit pada M_Zahir_snapshot. Proses dry-run tetap hanya staging/audit sesuai alur sebelumnya, bukan hasil opname.

Jika skema impor CSV belum terpasang, pesan status yang benar tetap menginformasikan migration belum siap. Ikuti docs/database/zahir-csv-dan-resign-20261109.md untuk jalur fresh/upgrade dan backup. Perbaikan Notice bukan bukti skema sudah terpasang.

Suite pengujian mutasi berjalan pada database uji terpisah yang dibuang setelah selesai. Tidak mengubah ID master, ukuran, QR, histori hasil opname, approval, pending atau transaksi saldo melalui pekerjaan UI ini. Rollback UI dapat mengembalikan berkas perbaikan tanpa mengubah tabel/data; akses model controller sebaiknya tetap dipertahankan agar Notice tidak muncul lagi.
