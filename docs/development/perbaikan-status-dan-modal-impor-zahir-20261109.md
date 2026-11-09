# Perbaikan status Zahir dan modal impor CSV - 9 November 2026

## Masalah dan hasil

View stockopname_zahir_status merujuk $this->zahir_snapshot setelah memuat model di dalam view. Konteks $this pada view CodeIgniter adalah CI_Loader; model yang baru dimuat disimpan pada instance controller sehingga properti itu belum tersedia pada Loader. Akibatnya muncul Notice Undefined property dan ready() pada null.

Partial kini mengambil controller melalui get_instance(), kemudian memuat dan mengakses config/model dari controller tersebut. Semua pemakai partial (katalog, Master Opname, input, master lokal dan toolbar cetak) mendapat perbaikan yang sama. Jika migration belum dipasang, status menampilkan pesan model yang jelas tanpa Notice properti. Perbaikan ini tidak memasang migration yang belum tersedia.

## Antarmuka dan alur

Katalog memiliki card **Impor CSV** dan tombol **Impor CSV** pada header card data. Keduanya membuka satu Bootstrap4 modal, termasuk ketika katalog ditanam di tab Master Opname. Partial modal dimuat bersama katalog dan assets/js/stockopname-zahir-import.js diinisialisasi setelah window load, karena jQuery/Bootstrap proyek dimuat pada footer.

Modal mengambil route impor existing admin/stockopname/zahir-csv?embedded=1. Controller dan validator/transaksi existing tetap satu jalur; tidak ada API impor alternatif yang melewati admin guard atau validasi. Upload, preview, konfirmasi dan pemulihan mengirim form melalui FormData/AJAX pada endpoint yang sama. View embedded adalah fragment tanpa html/body bersarang; halaman standalone tetap tersedia.

UI menampilkan nama/ukuran file, pemeriksaan ekstensi/5MiB di client (server tetap otoritas), progres transfer, status validasi, preview/error, dan konfirmasi full yang wajib. Selama request tombol/input dan penutupan modal ditahan untuk menghindari double-submit. Jika koneksi/sesi terputus, UI menawarkan muat ulang status/formulir dan mengingatkan agar memeriksa hasil konfirmasi sebelum mengulang. Tidak mengklaim request timeout berarti transaksi pasti gagal.

Token sesi, token preview, checksum, fingerprint, upload privat dan CSRF framework tetap dipakai. Token CSRF hasil form ditransfer ke aksi QR parent bila diaktifkan. HTML respon diparse tanpa scripts, sumber ditampilkan dengan escaping yang sudah ada. Penanda import_completed hanya diberikan controller setelah commit/recovery berhasil; setelah itu katalog dimuat ulang, pilihan cetak dibersihkan, banner status pada halaman diperbarui. Preview saja tidak merefresh seolah data sudah aktif. GET status_only=1 hanya membaca partial status dan tetap melalui guard controller.

## Berkas

- application/views/content/admin/stockopname_zahir_status.php
- application/views/content/admin/stockopname_zahir_import.php
- application/views/content/admin/stockopname_zahir_import_modal.php (baru)
- application/views/content/admin/stockopname_master_barang_catalog.php
- application/controllers/admin/C_Stockopname.php
- assets/js/stockopname-zahir-import.js (baru)
- tests/stockopname_zahir_views_test.php (baru)

Perubahan pengguna yang sudah ada dipertahankan. Tidak mengubah model, aturan grain/grouping, format CSV, penerbitan/Resign QR, ukuran, atau business data untuk perbaikan ini.

## Verifikasi

PHP lint pada controller dan view yang berubah lulus. Test view menjalankan error handler ketat dan konteks Loader yang tidak memiliki model: membuktikan model baru diakses lewat controller, nama file di-escape, migration hilang menghasilkan pesan tanpa Notice, fragment tanpa dokumen bersarang, tiga form mempertahankan endpoint embedded serta token preview/CSRF, source preview di-escape, penanda selesai dan halaman standalone tetap tersedia. Suite integrasi CSV/QR di database uji juga lulus.

Modul JavaScript berhasil di-import/parse melalui runtime Node, termasuk pemuatan sebelum jQuery dan resolusi jQuery saat init. Ini bukan simulasi browser lengkap. UAT browser autentikasi, Bootstrap modal, upload/progres nyata, CSRF enabled, dua tab, request terputus, preview/konfirmasi serta refresh banner/katalog masih perlu dilakukan. Tidak ada klaim UAT browser/camera/cetak fisik pada pekerjaan ini.
