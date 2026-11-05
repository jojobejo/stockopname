# Database: tab Master Opname dan Master Barang

Tanggal: 4 November 2026.

**No schema change.** Tidak ada DDL, migrasi, atau perubahan data massal.

Penyesuaian jarak tampilan tab Data Master Barang hanya mengubah view; tidak mengubah query maupun data.

Penghilangan gambar QR dari sel tabel juga hanya mengubah view. Status dan path QR tetap dibaca dari data yang sama; tidak ada perubahan skema atau data.

Master Opname tetap membaca `stockopname_master_item` per kode barang, nomor lot, dan expired date. QR diambil dari `tb_master_barang_all.qrcode` dengan pasangan `stockopname_master_item.kode_barang = tb_master_barang_all.kd_barang`. Bila satu kode mempunyai beberapa baris master barang, baris dengan ID terkecil dipakai seperti pencocokan katalog saat ini. File PNG harus ada agar preview dan cetak satu kartu aktif. Generate QR pada tab Master Barang tetap menggunakan mekanisme yang sudah ada untuk menyimpan path ke kolom tersebut.
