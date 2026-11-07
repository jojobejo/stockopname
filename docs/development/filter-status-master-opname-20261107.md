# Filter status Master Opname dan rincian status Master Barang (2026-11-07)

Route `admin/stockopname/master_opname` dan AJAX `/ajax-list` tetap memakai `C_Stockopname` dan `M_Stockopname`. Guard controller membatasi halaman, AJAX, generate QR, preview, dan cetak kartu pada sesi admin dashboard. Route cetak sebagian tetap ada untuk kompatibilitas, tetapi tombol dan pilihan barisnya tidak ditampilkan lagi pada Data Master Opname.

## Penggunaan

1. Buka Master Opname > Data Master Opname. Pilih Filter Status: Semua, Sudah Digenerate, Belum Digenerate, atau Tidak Ada Kesesuaian Data. Pencarian dapat dipakai bersama filter; jumlah hasil dan halaman berasal dari hasil filter model sebelum pagination.
2. Semua menampilkan seluruh baris sesuai pencarian. Belum Digenerate berarti data lokal dan LOT Zahir cocok tetapi belum ada catatan QR untuk pasangan barang dan expired date. Tidak Ada Kesesuaian Data mencakup kode tidak ada pada master lokal, nama lokal berbeda, kode tidak ada di LOT Zahir berqty positif, atau pasangan nama dan expired date tidak tersedia. QR siap mensyaratkan data cocok, catatan QR sesuai ID/expired date, dan file ada.
3. Pada Data Master Barang, baca label dan keterangan per kode. File QR hilang berbeda dari QR belum dibuat. Bila sumber Zahir gagal, status Master Opname menjadi belum terverifikasi untuk baris yang tidak memiliki masalah lokal yang dapat dipastikan. Gunakan Refresh setelah sumber pulih.
4. Generate QR hanya dilakukan pada baris yang lolos validasi lokal, nama, dan pasangan expired date Zahir. Preview serta cetak hanya mengaktifkan QR yang valid dan file-nya ada.

Perubahan lain yang telah ada di worktree dipertahankan. UAT browser admin masih diperlukan untuk filter bersama pencarian dan pagination, seluruh label status, kegagalan Zahir, generate, preview, dan cetak kartu.

## Verifikasi teknis

- `tests/master_opname_status_test.php`: lulus delapan kategori status, termasuk sumber Zahir tidak tersedia; lulus kombinasi filter, pencarian, hitungan, dan dua halaman DataTables pada database terkonfigurasi.
- `tests/stockopname_read_smoke.php`: lulus uji baca katalog, master, QR, dan query terkait.
- `php -l` untuk controller, model, dan kedua view: lulus. `node --check` untuk skrip kedua view setelah ekspresi PHP diganti literal: lulus.
- Uji browser dengan sesi admin dan simulasi gangguan Zahir belum dijalankan. Generate QR dan cetak kartu belum dieksekusi dalam pengujian ini agar data dan aset produksi tidak berubah.
