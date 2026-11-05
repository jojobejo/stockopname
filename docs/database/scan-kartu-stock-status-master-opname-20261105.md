# Database: scan kartu stock dan status Master Opname — 5 November 2026

**Tidak ada perubahan skema maupun data database.** Tidak ada DDL, migrasi, atau koreksi otomatis nama/expired.

Status dibaca dari `stockopname_master_item` (kode, nama, expired), `tb_master_barang_all` (kode, nama, ID dan path QR), serta Data Master Barang LOT Zahir yang sudah menjadi sumber tab Master Barang. Tanggal expired tidak tersedia sebagai kolom pada `tb_master_barang_all`; karena itu validasi tanggal memakai data LOT Zahir. Perbandingan nama dan tanggal bersifat tepat setelah trim. Path QR harus sesuai dengan ID Master Barang lokal dan file PNG harus ada.

Lot `stockopname_master_item.no_lot` tetap dipakai sebagai identitas baris dan relasi pending/hasil opname; hanya kolom tampilan Master Opname yang disembunyikan. Data dummy `AAMOL01` tetap tidak diubah agar perbedaan nama terhadap LOT Zahir terlihat sebagai **Data tidak sinkron**. Tidak ada rollback database yang diperlukan untuk perubahan ini.
