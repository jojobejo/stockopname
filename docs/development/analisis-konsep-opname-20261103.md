# Analisis perubahan konsep stock opname

Catatan: dokumen ini merekam tahap analisis awal. Hasil implementasi terbaru ada di [implementasi-konsep-opname-20261103.md](implementasi-konsep-opname-20261103.md).

Tanggal: 3 November 2026. Status: audit kode dan rancangan; belum implementasi alur baru.

## Ruang lingkup dan bukti

Ditinjau route CodeIgniter, controller `application/controllers/admin/C_Stockopname.php`, model `application/models/admin/M_Stockopname.php`, dan view `application/views/content/admin/stockopname_input_mobile.php`. Struktur tabel dibandingkan dengan backup lokal tanggal 3 November 2026; database aktif dan UAT login belum diverifikasi. File backup dan dokumentasi pembersihan yang sudah ada tidak diubah.

## Perhitungan saat ini

- `save_mobile_opname()` dan `opname_payload_from_master()` menghitung `qty = qty_box * dimensi + qty_pcs`. Qty adalah total pcs; qty_pcs adalah pcs lepas di luar box.
- Contoh isi box 24: input 3 box dan 5 pcs menghasilkan 77 pcs.
- `master_dimensi_value_sql()` mengutamakan kolom `tb_master_barang_all.dimensi` berdasarkan kode barang. Ada fallback ke master opname; saat menyimpan, dimensi tidak positif dapat dihitung dari `floor((qty master - qty_pcs master) / qty_box master)` jika qty_box master positif.
- Katalog menampilkan `p * l * t` sebagai dimensi. `create_master_barang_catalog()` dan `update_master_barang_catalog()` menyimpan p/l/t dan kubikasi, tetapi tidak menyimpan kolom dimensi. Ini berpotensi membuat tampilan katalog dan nilai konversi input berbeda. Belum dibuktikan pada data aktif.
- Input menyimpan box dan pcs sebagaimana dimasukkan; pcs yang melebihi isi box tidak otomatis dipindahkan menjadi box. Bentuk normal matematis untuk total Q dan isi D > 0 adalah `box = floor(Q / D)` dan `pcs = Q % D`, tetapi bukan normalisasi otomatis jalur simpan saat ini.
- Jalur scan belum menolak box positif ketika dimensi dan fallback sama-sama nol; kontribusi box dapat menjadi nol. Rancangan baru harus menolak kondisi tersebut.
- Setiap simpan scan menambah baris. Agregasi hasil menjumlahkan input per tim; tim 1 dan tim 2 merupakan hasil pembanding, bukan stok yang dijumlahkan bersama.

## Kondisi alur saat ini

Master barang menggunakan `tb_master_barang_all`; master opname sudah terpisah pada `stockopname_master_item`. QR aktif dibuat dari master opname dengan payload `OP|kode_barang|id_master_opname`. Scan mengambil lot dan expired dari baris tersebut. Pencarian kode barang mentah mengambil satu baris sehingga tidak aman dijadikan pemilihan lot pada konsep baru.

Scan tersimpan pada `stockopname_opname` dengan sumber `scan_qrcode`. Manual melalui `save_manual_input_to_opname()` langsung masuk hasil dan salinan manual, dengan sumber `manual_input`. Request dari input mobile masuk antrean `Request Master Item`, memakai lot `-`, dan memerlukan alur afirmasi. Wilayah dan tim diambil dari session akun, bukan isian bebas operator.

## Rancangan yang mengikuti permintaan

1. Master barang menjadi identitas kode dan nama barang, sumber QR, serta sumber konversi isi per box setelah aturannya diputuskan.
2. Master opname tetap tabel tersendiri dengan referensi kode barang, lot, expired, qty, qty_box, qty_pcs, pending_qty, pending_qty_box, pending_qty_pcs.
3. Generate dan cetak QR dipindahkan ke master barang. Satu identitas QR barang tidak mengunci lot, expired, tim, wilayah, atau jumlah stok.
4. Scan menampilkan nama barang. Lot/expired ditentukan melalui pemilihan atau penginputan sesuai keputusan pengguna, baru operator mengisi box dan pcs.
5. Hasil menyimpan barang, lot, expired, qty, box, pcs, inputer, waktu, tim, wilayah, dan jenis input scan/manual/request. Nilai konteks tim/wilayah harus valid sebelum simpan.
6. Laporan perlu konsisten memisahkan tim, wilayah, dan sumber input; laporan ringkas yang menggabungkan wilayah tetap diberi konteks yang jelas.

## Keputusan yang masih diperlukan

- Isi per box: kolom langsung atau tetap p * l * t? Jika p/l/t adalah ukuran fisik, hasil kalinya tidak boleh diasumsikan sebagai jumlah pcs tanpa aturan bisnis.
- Lot/expired setelah scan: pilih data tersedia dan request bila belum ada, atau input bebas?
- Pending: menambah, mengurangi, atau terpisah dari stok buku; pencocokan disarankan memakai kode barang + lot + expired.
- Request: tetap perlu persetujuan supervisor?
- Input ulang pada kombinasi barang/lot/expired/tim/wilayah: tambahan hitungan atau penggantian dengan audit?

## Panduan penggunaan yang direncanakan

Alur ini belum tersedia: admin menyiapkan master barang dan isi box, menyiapkan master opname per lot/expired, lalu mencetak QR dari master barang. Inputer login dengan tim/wilayah, scan atau memilih barang manual, melengkapi lot/expired, mengisi box dan pcs, memeriksa total pcs lalu menyimpan. Barang/lot yang belum terdaftar mengikuti keputusan request. Supervisor meninjau hasil per tim, wilayah, sumber input, dan selisih.

## Validasi implementasi berikutnya

Uji konversi 3 box + 5 pcs dengan isi 24 = 77; pcs saja; isi nol dengan box positif ditolak; barang dengan beberapa lot tidak memilih lot pertama otomatis; konteks tim/wilayah kosong ditolak; input ulang sesuai keputusan; request belum disetujui tidak terhitung bila persetujuan diwajibkan; QR lama ditangani eksplisit; pending tidak terhitung dua kali. UAT scan kamera dan akun setiap peran diperlukan setelah implementasi.
