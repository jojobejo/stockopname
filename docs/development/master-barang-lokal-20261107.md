# Master Barang lokal, 7 November 2026

Halaman `master_barang`, alias `purchase/listBarang`, dan halaman baru `admin/stockopname/master_barang` membaca `tb_master_barang_all`. Daftar, detail, tambah, ubah, hapus, dan impor Excel tersedia pada halaman tersebut. Katalog Zahir berada pada route terpisah.

Koreksi navigasi: tombol **Master Barang** pada Master Opname kini membuka `admin/stockopname/master_barang` dengan view baru `stockopname_master_barang_local.php` yang membaca tabel lokal. Katalog Zahir lama tersedia di `admin/stockopname/master_barang/catalog` untuk kebutuhan QR. Route data CRUD/impor admin memakai awalan `admin/stockopname/master_barang/data` dan memerlukan sesi admin dashboard.

## Penggunaan

Masuk sebagai ADMINKEU, ADMINKEUTC, atau ADMINPURCHASING. Buka **Master Barang** dari halaman Keuangan atau Logistik. Tambah mengisi kode, nama, serta P/L/T. Field lain mengikuti nilai awal yang ditentukan. Detail menampilkan identitas dan dimensi. Edit hanya mengubah kode, nama, dan P/L/T; kode sistem dan metadata lain tetap tersimpan. Hapus ditolak bila barang ditemukan pada tabel transaksi/opname yang diperiksa.

Untuk impor, pilih XLSX/XLS maksimal 5 MB dan klik **Impor Excel**. Baris pertama harus tepat `kd_barang,nama_barang,p,l,t` dalam lima kolom. P/L/T wajib bilangan bulat 0–999; baris kosong, kode ganda dalam file, kode yang sudah ada, serta nilai tidak valid membatalkan seluruh file. Hasil dan alasan per baris tampil di halaman. Impor tidak memperbarui barang yang telah ada.

Kode sistem dialokasikan sebagai `KIUBR` + urutan angka terbesar yang tersimpan ditambah satu, lebar angka minimal empat digit. Penambahan manual dan impor memakai transaksi dan MySQL named lock yang sama sehingga dua permintaan modul ini tidak mengalokasikan kode yang sama.

## Verifikasi dan batas

Perbaikan DataTables: query daftar sebelumnya memakai `COALESCE(kode_barang_system)` dengan satu argumen sehingga MySQL menolak respons AJAX. Query daftar dan pencarian sekarang memakai kolom langsung. Query daftar diverifikasi pada database lokal (6.112 baris; satu baris contoh terbaca), dan lint PHP controller/model lolos.

Perbaikan lanjutan: `M_Logistik.php` mengeluarkan komentar HTML sebelum tag PHP, yang mencemari respons JSON dari controller Keuangan. Komentar tersebut dihapus. Setelah muat ulang dengan sesi admin di browser, DataTables menampilkan 10 baris pertama dan ringkasan 6.112 entri tanpa peringatan JSON.

Lint PHP pada controller, model, view, dan routes lolos. Uji interaktif dengan akun tiap peran, impor XLS/XLSX valid dan gagal, serta pengujian dua impor serentak pada database target masih perlu dijalankan melalui browser. Named lock hanya menyelaraskan penulis yang memakai modul ini; penulis lama di luar modul perlu diaudit sebelum menjanjikan keunikan lintas aplikasi.
