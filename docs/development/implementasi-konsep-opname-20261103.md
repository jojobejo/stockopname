# Implementasi konsep opname berbasis master barang

Tanggal: 3 November 2026. Menggantikan rancangan awal pada `analisis-konsep-opname-20261103.md` setelah keputusan pengguna.

## Keputusan dan perilaku

- Identitas barang berasal dari `tb_master_barang_all`. Isi box tetap P × L × T; penyimpanan katalog sekarang menyimpan nilai dimensi tersebut. Lookup konversi langsung memakai perkalian P/L/T, bukan kolom dimensi yang mungkin usang.
- Master opname tetap terpisah di `stockopname_master_item`, dengan kode/nama barang, lot, expired, qty total pcs, box, pcs lepas. Daftar master menampilkan tiga kolom pending dari tabel pending terpisah.
- QR barang baru berisi `BR|id_master_barang`, dibuat/cetak melalui Master Barang. File dan path qrcode dicatat pada master barang. QR lama `OP|kode|id_master_opname` tetap dapat mengenali barang tetapi operator harus memilih lot/expired kembali. Generator QR per lot lama dihentikan; label lama tidak dihapus.
- Library QR lokal ditambahkan dengan namespace terpisah. Sumber, hash upstream, dan lisensi ada di `application/libraries/stockopnameqr/SOURCE.md` dan `LICENSE`. PHP membutuhkan GD. Jalur lokal telah diuji menghasilkan PNG; uji kamera belum dilakukan.
- Scan menampilkan nama barang lalu pilihan gabungan nomor lot / expired. Manual mencari kode/nama dari katalog yang sama. Lot tidak disatukan hanya karena expired sama.
- Qty total dihitung server: `box * (P*L*T) + pcs lepas`. Input box ditolak jika konversi tidak valid. Angka nol yang sengaja diisi diterima sebagai hasil pemeriksaan; kedua kolom kosong ditolak. Box/pcs disimpan sesuai input tanpa normalisasi otomatis.
- Tim 1/2 dan wilayah wajib valid dari session. Jenis input tetap `scan_qrcode`, `manual_input`, dan label request historis `master data request opname` setelah admin memproses request.
- Request boleh memilih barang katalog yang belum memiliki lot pada master opname. Nomor lot dan expired wajib diisi. Request hanya masuk antrean, tidak masuk hasil. Supervisor tidak dapat mengafirmasi; admin memproses melalui halaman Request Opname. Proses memakai lock per barang dan status antrean untuk mencegah posting ulang. Master lot yang sudah ada digunakan kembali.
- Pending selalu menambah stok pembanding: buku + pending. Pending baru tidak ditanamkan ke qty dasar master; query membaca tabel pending. Marker legacy tetap diperhitungkan agar tidak terjadi hitung ganda. Pencocokan memakai kode + lot + expired.
- Detail input, pemilihan stok, pending per lot, serta penghapusan master/hasil terkait menggunakan kode + lot + expired. Penghapusan tidak melebar ke lot lain dengan expired sama.
- Hasil tim tidak dijumlahkan lintas tim. Status memilih kedua tim match, tim 1 match, tim 2 match, atau perlu cek ulang. Dashboard memilih qty fisik tim yang match. Status kelengkapan dan sama/beda qty ditampilkan terpisah. Ringkasan barang menghitung cakupan seluruh lot per tim; detail membandingkan per lot. Tracking supervisor mempertahankan batas wilayah dan memisahkan lot.
- Input berulang tim yang sama tetap bersifat tambahan sesuai perilaku sebelumnya, sebagaimana disampaikan kepada pengguna. Koreksi menggunakan edit/hapus riwayat, bukan menimpa otomatis. Laporan utama agregat seluruh wilayah; tracking wilayah untuk membandingkan hasil di wilayah tertentu. Master buku belum memiliki alokasi per wilayah, sehingga stok buku tidak diasumsikan tersedia terpisah untuk setiap wilayah.

## Cara penggunaan

1. Admin membuka `admin/stockopname/master_barang`, memastikan kode, nama, P/L/T benar, lalu menggunakan **QR / Cetak** pada barang. Cetak dari halaman label yang terbuka.
2. Admin menyiapkan master opname per barang, lot, expired dan qty buku melalui `admin/stockopname/master_opname`. Pending dikelola terpisah di `admin/stockopname/barang-pending`; pilihan lot/expired wajib sesuai master.
3. Inputer login dengan tim dan wilayah yang ditetapkan, membuka `stockopname/input`, scan QR, memilih lot/expired, mengisi box dan pcs lepas, memeriksa total, lalu menyimpan. Pilihan **Input Manual** menggunakan data barang/lot yang sama dengan sumber input berbeda.
4. Bila lot belum ada, pilih **Opname Request**, isi barang, nomor lot, expired, box/pcs. Data menunggu admin dan belum memengaruhi hasil opname.
5. Admin membuka `admin/stockopname/monitoring/request-opname`, meninjau barang/lot/expired serta qty dan memproses request. Sesudah diproses, data masuk hasil tim/wilayah asal.
6. Monitoring menampilkan match terhadap buku + pending dan kelengkapan tim. Tanda belum mengisi berbeda dengan angka 0 yang sudah diinput.
7. Pada Barang Pending, badge **Perlu pemetaan admin** berarti data belum masuk pembanding. Edit baris tersebut, pilih kode dan pasangan lot/expired yang benar, periksa qty lalu simpan. Sesudah benar, badge menjadi **Masuk pembanding**.

## Perubahan data lama

Pengguna mengizinkan pemetaan hanya pasangan tunggal nama + expired. Sebanyak 198 dari 209 baris berhasil dipetakan; 11 ambigu tetap untuk admin. Tidak ada perubahan qty. Detail, backup dan rollback ada pada dokumentasi database terpisah `../database/pemetaan-pending-dan-konsep-opname-20261103.md`.

## Verifikasi dan batasan

- PHP lint file aplikasi yang berubah dan library QR.
- Pemeriksaan sintaks JavaScript inline dengan Node setelah placeholder PHP diganti; ini bukan pengujian interaksi DOM/browser.
- `tests/stockopname_concept_test.php`: fixture SQL read-only untuk konversi 77 pcs, pemisahan lot/kode, penambahan pending, marker legacy, tim 2 match, kedua tim match, tidak ada match, kelengkapan semua lot, dan input nol.
- `tests/stockopname_input_test.php`: pengujian controller dengan double in-memory untuk asal tim/wilayah, validasi qty dan pasangan barang/lot, nol eksplisit, antrean request, penolakan akses supervisor dan akses admin; tanpa transaksi produksi.
- `tests/stockopname_read_smoke.php`: lookup katalog/QR, pilihan lot, detail master/pending, query dashboard/compare pada database aktif, dan konsistensi badge pending; tanpa DDL/DML.
- `tests/stockopname_qr_test.php`: generate PNG QR lokal, file sementara dibersihkan sesudah pengujian. Belum membuktikan pembacaan kamera.
- UAT login inputer/admin/supervisor, scan kamera, cetak fisik, serta transaksi simpan/edit/request melalui browser masih diperlukan. Hasil opname aktif masih kosong saat audit. Status siap diuji pengguna, belum dinyatakan lulus UAT operasional.

## Risiko yang masih perlu ditangani operasional

Sebelas pending ambigu belum termasuk pembanding sampai admin memetakan. Perubahan P/L/T tidak menghitung ulang transaksi historis secara massal. Pastikan konversi benar sebelum periode opname berjalan. Backup data dan rollback aplikasi harus diperlakukan sebagai satu paket bila mengembalikan konsep lama.
