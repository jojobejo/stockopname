# Pembersihan database `kiucoid_karismaerp` — 2026-11-03

## Lingkup dan hasil

Database `kiucoid_karismaerp` pada host `EDP2:3306` dibersihkan sesuai permintaan. Sebelum perubahan terdapat 181 tabel. Sebanyak 170 tabel di luar modul stock opname dihapus; 11 tabel berikut tersisa:

- Delapan tabel dengan awalan `stockopname_`.
- `tb_karyawan`, untuk data karyawan dan autentikasi aplikasi.
- `tb_master_barang_all` dan `tb_wilayah`, karena model Stock Opname menggunakannya sebagai sumber master barang dan wilayah.

Data pada tabel yang dihapus ikut terhapus. Tidak ada perubahan kode aplikasi atau definisi struktur pada tabel yang dipertahankan.

## Backup dan pemulihan

Dump penuh sebelum pembersihan: `database/backups/kiucoid_karismaerp_before_stockopname_cleanup_20261103.sql` (44.278.537 byte).

SHA-256: `B8D8EAC5D6F68C004E275B6288FBFF1E86629B3245A16B991EF35FA6175AECE0`

Skrip berisi daftar tabel yang dihapus: `database/backups/kiucoid_karismaerp_drop_non_stockopname_20261103.sql`.

Untuk memulihkan seluruh database ke kondisi sebelum pembersihan, impor dump backup ke target yang sesuai. Pemulihan penuh akan mengganti keadaan database saat ini, termasuk perubahan sesudah backup; tinjau target dan waktu pemulihan sebelum menjalankannya.

Contoh pemulihan lokal dengan MySQL XAMPP:

```powershell
Get-Content .\database\backups\kiucoid_karismaerp_before_stockopname_cleanup_20261103.sql | C:\xampp\mysql\bin\mysql.exe --protocol=TCP -h localhost -u root
```

## Verifikasi

Setelah pembersihan, inventaris `information_schema.TABLES` menunjukkan tepat 11 tabel yang tercantum di atas, tanpa tabel lain. Backup `mysqldump` berhasil dengan kode keluar 0 sebelum operasi.
