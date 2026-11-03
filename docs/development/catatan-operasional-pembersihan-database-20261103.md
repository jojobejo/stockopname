# Catatan development: pembersihan data database

Pada 2026-11-03 dilakukan operasi pemeliharaan database `kiucoid_karismaerp` atas permintaan pengguna. Tidak ada perubahan pada kode sumber, konfigurasi aplikasi, atau modul baru.

Modul Stock Opname menggunakan tabel `stockopname_...`, `tb_karyawan`, `tb_master_barang_all`, dan `tb_wilayah`. Karena itu, tabel master barang dan wilayah ikut dipertahankan agar fitur Stock Opname tetap mempunyai data referensi. Prosedur, daftar tabel yang dihapus, backup, dan pemulihan dicatat terpisah dalam [dokumentasi database](../database/pembersihan-database-kiucoid-karismaerp-20261103.md).

Verifikasi operasional memastikan hanya 11 tabel yang dipertahankan. Pengujian alur aplikasi setelah penghapusan belum dilakukan.
