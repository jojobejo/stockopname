# Tab Master Opname dan Master Barang

Tanggal: 4 November 2026.

## Perubahan aplikasi

- Route `admin/stockopname/master_opname` kini mempunyai tab **Data Master Opname** dan **Data Master Barang**. Tab kedua menampilkan katalog yang sama dengan route `admin/stockopname/master_barang`, termasuk filter, generate QR, preview, dan cetak kartu. Route lama tetap dapat dibuka langsung.
- Daftar Master Opname menampilkan kolom QR Barang. Data QR diambil dari `tb_master_barang_all` melalui `kode_barang`, bukan dari QR lama per baris opname. Kartu preview dan cetak satu item menggunakan file QR serta nama master barang tersebut; expired date dan nomor lot tetap mengikuti baris `stockopname_master_item` yang dipilih.
- Ringkasan dan filter QR pada Master Opname membaca ketersediaan path QR di Master Barang. Kolom QR dan tombol cetak satu item juga memeriksa keberadaan file PNG. QR barang berisi identitas `BR|id_master_barang`; saat dipindai, operator memilih lot dan expired date yang sesuai.
- Tab Data Master Barang menggunakan jarak atas dan padding horizontal konten yang sama dengan Data Master Opname. Tampilan route Master Barang yang dibuka langsung tetap memakai tata letak sebelumnya.
- Gambar QR tidak ditampilkan di sel tabel Data Master Barang. Kolom **Aksi QR** menampilkan tombol generate untuk barang yang belum memiliki QR atau petunjuk membuka preview untuk barang yang sudah siap. Gambar QR tetap tersedia pada panel preview kartu dan hasil cetak.

## Cara penggunaan

1. Buka `admin/stockopname/master_opname` sebagai admin. Pilih **Data Master Opname** untuk melihat atau menambah data per lot dan expired date.
2. Pilih **Data Master Barang** untuk mencari kode barang dan expired date dari LOT Zahir, lalu generate QR bila belum tersedia.
3. Kembali ke **Data Master Opname** dan pilih baris yang sesuai. Preview menampilkan QR barang, kode, expired date, dan nomor lot dari baris terpilih. **Print Kartu Stock** aktif bila file QR tersedia.
4. Saat scan QR barang untuk opname, pilih lot dan expired date yang benar pada form input.

## Verifikasi

PHP lint controller, model, dan kedua view berhasil. `git diff --check` berhasil. `tests/stockopname_read_smoke.php` lulus terhadap database terkonfigurasi untuk lookup katalog/QR, lot/expired, query monitoring, dan status pending. UAT dengan sesi admin, data Zahir aktif, pencetakan fisik, dan scan kamera belum dijalankan.
