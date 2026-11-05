# Cetak Kartu Stock Master Barang A4 (4 November 2026)

## Perubahan aplikasi

View pada route `admin/stockopname/master_barang/print-kartu-stock` memakai kertas A4 portrait dengan margin 5 mm. Setiap halaman berisi kisi 3 kolom dan 5 baris, maksimal 15 kartu QR. Pilihan lebih dari 15 kartu otomatis dibagi ke halaman berikutnya. Isi kartu tetap nama barang, QR barang, dan expired date. Halaman pratinjau menampilkan jumlah kartu dan lembar serta menyediakan tombol **Cetak**. Pengaturan ini menggantikan tata letak empat kartu per A4 yang tercatat pada dokumentasi sebelumnya.

## Cara penggunaan

1. Masuk sebagai admin dan buka `admin/stockopname/master_barang`.
2. Pilih barang dengan QR siap cetak, lalu tekan **Cetak**, **Cetak Tercentang**, atau **Cetak Semua QR Siap**.
3. Pada dialog browser, pilih kertas A4 portrait dan skala 100%. Nonaktifkan header/footer browser bila aktif. Periksa pratinjau: satu halaman penuh berisi 15 kartu; halaman terakhir berisi sisanya.

## Verifikasi

PHP lint dan pemeriksaan whitespace Git berhasil. Render PDF melalui Edge headless dengan 16 data contoh menghasilkan dua halaman A4: 15 kartu pada halaman pertama dan satu kartu pada halaman kedua. Kedua halaman diperiksa secara visual; QR tampil pada kartu halaman kedua. UAT melalui sesi admin dan pencetakan fisik tetap diperlukan untuk memastikan hasil sesuai printer serta ukuran media label.
