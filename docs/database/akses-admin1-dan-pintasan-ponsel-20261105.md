# Catatan database — 5 November 2026

Tidak ada perubahan skema atau data database untuk akses `admin1` dan pintasan ponsel.

Pemeriksaan baca saja pada database lokal `kiucoid_karismaerp` menemukan `admin1` aktif (`tb_karyawan.id = 116`, `jobdesk = ADMIN`, `akses_lv = 1`). Tabel `tb_menu` dan `tb_akses_menu` tidak tersedia, sehingga tidak ada migrasi atau pembaruan izin menu yang dijalankan. Tidak diperlukan rollback database untuk pekerjaan ini.
