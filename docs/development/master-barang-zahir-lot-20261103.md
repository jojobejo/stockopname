# Master Barang dari ZahirDigital LOT (3 November 2026)

## Perubahan aplikasi

- Route `admin/stockopname/master_barang` kini menampilkan kode barang, nama barang, dan expired date dari sumber LOT ZahirDigital. Form tambah barang dan modal edit pada halaman ini dihilangkan.
- Endpoint referensi `SALES/LOT/lot_data.php` mengembalikan HTTP 401 karena direktori LOT dilindungi HTTP Basic Auth. Stockopname membaca halaman ZahirDigital yang sudah ada dan dapat diakses, `LOGISTIK/lotbarang_v2.php`, yang memakai `LOTLEDGR`, `LOT`, `INVENTOR`, `GUDANG`, dan `UNIT`. Relasi `UNIT` dapat mengecualikan barang tanpa master unit. Tidak ada file ZahirDigital yang diubah.
- Seluruh hasil LOT diambil dalam satu permintaan. Stockopname menjumlahkan `QTYLOT` untuk kombinasi kode barang, nama barang, dan tanggal expired, lalu hanya menampilkan kombinasi dengan total Qty Lot lebih dari 0. Nomor lot atau gudang yang berbeda tidak membuat baris tambahan jika ketiga nilai itu sama.
- Pencarian dan pagination berjalan melalui AJAX jQuery setelah filter Qty Lot dan penghapusan duplikat, dengan 25 baris per halaman. Snapshot LOT dan dimensi disimpan di `application/cache/zahir_master_barang_lot_qty.json` dan `application/cache/zahir_master_barang_dimensions.json`. Snapshot yang berumur lebih dari lima menit tetap ditampilkan segera, lalu diperbarui di belakang layar. Tombol **Refresh** menjalankan pembaruan tanpa mengosongkan tabel. Jika cache tidak tersedia, permintaan pertama harus membaca sumber ZahirDigital dan dapat memerlukan beberapa detik.
- URL sumber berada di `application/config/zahir_stock_lot.php`.
- P, L, dan T diambil dari master barang ZahirDigital `LOGISTIK/Daftar Barang new.php` (`PANJANG`, `LEBAR`, `TINGGI`) menurut kode barang. Dimensi dihitung di stockopname sebagai `P × L × T`; nilai tidak diambil dari master lokal. Sumber tersebut memuat barang aktif, sehingga kode yang tidak ditemukan menampilkan `-`.
- Kolom QR menampilkan gambar QR barang jika path `qrcode` di master lokal menunjuk ke file PNG yang ada. Tombol **Generate QR** muncul juga untuk kode yang belum ada di master lokal. POST AJAX memvalidasi kode/nama/P/L/T dari snapshot Zahir berqty positif, membuat master lokal melalui mekanisme kode sistem yang sudah ada, kemudian membuat QR. Alert menampilkan hasil tanpa membuka tab. Jika master lokal berhasil dibuat tetapi pembuatan QR gagal, master tetap tersimpan dan tombol dapat dicoba lagi.
- Kartu ringkasan mengikuti tampilan master opname: **Total Data** dari kombinasi LOT Zahir berqty positif, **Total Barang Qty 0** dari master opname lokal (tautan ke halaman qty 0), **QR Code Selesai**, **QR Code Pending**, dan **Print Kartu Stock** untuk baris terpilih. Kartu QR gagal dan Print Preview Asset tidak ditampilkan. Kartu selesai/pending dapat memfilter tabel.
- Tabel menampilkan 10 data per halaman; pilihan 10, 25, dan 50, dengan batas server 50 data per permintaan. Tidak ada pilihan menampilkan semua data.
- Memilih baris menampilkan preview kartu stock di sisi tabel yang hanya berisi nama barang, QR barang, dan expired date. Location, nama gudang, kode barang, dimensi, inventory date, serta paraf dihilangkan dari preview cetak. Tombol **Cetak** aktif hanya saat file QR tersedia. QR barang berisi identitas barang; operator memilih lot/expired date setelah scan.
- Jarak QR ke expired date pada preview ditambah dan QR diperbesar dari 160 px menjadi 320 px. Hasil cetak memakai QR 80 mm dan empat kartu per lembar A4. Kolom centang tersedia untuk QR yang sudah siap. Centang header memilih seluruh QR siap pada halaman yang sedang tampil; pilihan tetap tersimpan saat pindah halaman. **Cetak Tercentang** hanya mencetak pilihan, sedangkan **Cetak Semua QR Siap** mengambil seluruh QR siap dari daftar LOT berqty positif, melampaui halaman yang sedang tampil. Server memvalidasi kembali pilihan dan file QR sebelum menyajikan halaman cetak.

## Cara penggunaan

1. Buka `admin/stockopname/master_barang` sebagai admin.
2. Cari kode atau nama barang melalui kolom Cari. Pilih jumlah baris dan halaman sesuai kebutuhan.
3. Tekan **Refresh** untuk mengambil perubahan terbaru dari ZahirDigital.
4. Gunakan **Status QR** atau kartu ringkasan untuk melihat barang yang sudah atau belum memiliki QR siap cetak. Klik baris untuk preview kartu, lalu **Cetak**. Klik **Generate QR** jika belum ada; master lokal dibuat otomatis bila perlu. Tunggu alert, kemudian baris akan diperbarui otomatis.
5. Untuk cetak banyak kartu, centang baris yang diinginkan atau gunakan centang header untuk memilih QR siap pada halaman tersebut. Klik **Cetak Tercentang**. Untuk seluruh kartu dengan QR siap dari semua halaman, klik **Cetak Semua QR Siap**. Halaman cetak menyediakan tombol Cetak browser.

## Konfigurasi operasional

Endpoint LOGISTIK tidak memerlukan kredensial HTTP Basic dari mesin stockopname. URL internal memakai sertifikat HTTPS yang tidak dipercaya mesin kerja, sehingga proxy untuk URL tetap tersebut saat ini menonaktifkan verifikasi sertifikat; pasang sertifikat internal tepercaya sebelum mengaktifkan verifikasi.

## Verifikasi dan batasan

PHP lint pada controller, model, view, konfigurasi, dan library berhasil; JavaScript view lolos pemeriksaan sintaks Node. Uji baca sumber HTML ZahirDigital terbaru dari PHP stockopname menghasilkan 2.105 kombinasi LOT dengan Qty Lot positif, semuanya memiliki P/L/T pada snapshot master barang aktif tersebut. Waktu baca sumber LOT pada uji ini sekitar 11 detik, dibanding sekitar 29 detik untuk endpoint JSON sebelumnya; cache yang sudah ada dibaca tanpa menunggu sumber. Pada pemeriksaan data lokal sebelum perubahan ini, belum ada path QR barang tersimpan; filter **Sudah digenerate** akan kosong sampai QR barang dibuat. UAT halaman dan POST generate QR dengan sesi admin belum dilakukan. Endpoint lama `master_barang/create` dan `master_barang/update` tetap ada untuk kompatibilitas pemanggil lain.
Template cetak diuji secara read-only dengan lima kartu contoh dan menghasilkan dua lembar (empat kartu lalu satu kartu). UAT cetak browser dengan QR nyata dan sesi admin belum dilakukan.
