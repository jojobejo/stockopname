# Master Barang lokal, 7 November 2026

Tidak ada perubahan skema atau migrasi database. `tb_master_barang_all.kd_supplier` dipakai sesuai nama kolom aktual. Field baru diisi `-` untuk barcode, qrcode, kd_supplier, bhn_aktif, satuan; `0` untuk berat, kubikasi, qty_min, id_gudang, id_wilayah, hpp; dan `dimensi = p × l × t`.

Kode sistem mengikuti angka maksimum `KIUBR` yang sudah tersimpan, termasuk bentuk lima digit seperti `KIUBR00001`. Alokasi dari modul ini dilindungi transaksi dan named lock. Indeks unik pada `kd_barang` dan `kode_barang_system` belum ditambahkan karena data lama serta penulis lain perlu diaudit terlebih dahulu. Karena itu perlindungan duplikasi tingkat database lintas semua aplikasi belum terjamin.
