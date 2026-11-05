# Data master barang ZahirDigital LOT (3 November 2026)

Tidak ada perubahan skema database. Data ZahirDigital hanya dibaca.

Data LOT dibaca dari halaman `LOGISTIK/lotbarang_v2.php` yang sudah ada di ZahirDigital. Halaman tersebut membaca Firebird: `LOTLEDGR` terhubung ke `LOT`, `INVENTOR`, `GUDANG`, dan `UNIT`. Stockopname menjumlahkan `QTYLOT` per kode barang, nama barang, dan tanggal expired, lalu hanya menampilkan total di atas 0 sebelum pagination lokal. Tidak ada perubahan kode maupun database ZahirDigital.

P/L/T pada tampilan dibaca dari `LOGISTIK/Daftar Barang new.php` di ZahirDigital, lalu dimensi dihitung sebagai hasil perkalian. QR barang dicocokkan ke `tb_master_barang_all` melalui `kd_barang`. Status QR berasal dari kolom `qrcode` pada tabel lokal tersebut dan keberadaan file PNG terkait; filter diterapkan sebelum pagination. Saat admin menekan **Generate QR**, aplikasi menulis path ke kolom `qrcode` yang sudah ada dan membuat file PNG lokal. Tidak ada penambahan kolom atau indeks.

Jika kode belum ada di `tb_master_barang_all`, tindakan **Generate QR** membuat satu baris master lokal dengan kode, nama, P/L/T, dan dimensi dari snapshot Zahir sebelum menyimpan path QR. Kolom `kode_barang_system` diisi dengan generator kode yang sudah ada; kolom legacy wajib menggunakan nilai standar mekanisme create master yang sudah ada. Tidak ada impor massal dan tidak ada penulisan ke database ZahirDigital. Kartu **Total Barang Qty 0** mengambil hitungan dari `stockopname_master_item` secara terpisah dari daftar LOT positif.

Pemilihan cetak berdasarkan centang atau semua hanya membaca snapshot LOT dan status file QR lokal. Pilihan tercentang diverifikasi ulang terhadap kombinasi kode barang, nama barang, dan expired date yang masih ada serta QR yang siap. Proses cetak tidak menulis database dan tidak mengubah skema.
