# Perbaikan perbandingan `kode_barang` lintas charset

## Latar belakang

Query pencocokan kode barang pada modul Stock Opname memberi collation `utf8mb4_general_ci` langsung pada kolom. Koneksi aplikasi dikonfigurasi dengan charset `utf8` (`utf8_general_ci`), sehingga database dapat menolak query dengan pesan `COLLATION 'utf8mb4_general_ci' is not valid for CHARACTER SET 'utf8'`.

## Perubahan

Helper `kode_barang_match_sql()` pada model Stock Opname sekarang mengonversi kedua operand ke `utf8mb4` sebelum menerapkan collation dan membandingkannya. Ini menjaga aturan perbandingan case-insensitive yang sudah dipakai sambil menghindari collation yang tidak sesuai dengan charset operand.

## Cara kerja

Perbaikan ini otomatis berlaku pada pencarian nilai dimensi barang dan agregasi dimensi yang mencocokkan `mba.kd_barang` dengan `kode_barang`. Tidak ada langkah penggunaan baru bagi operator.

## Verifikasi

Perubahan telah ditinjau terhadap konfigurasi koneksi CodeIgniter (`utf8` / `utf8_general_ci`) dan seluruh pemanggilan helper pencocokan kode barang di model. Validasi query pada database aplikasi belum dilakukan.
