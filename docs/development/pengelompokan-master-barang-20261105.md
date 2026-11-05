# Pengelompokan Data Master Barang — 5 November 2026

## Perubahan aplikasi

- Tab **Data Master Barang** dan route `admin/stockopname/master_barang` menampilkan satu baris untuk setiap pasangan nama barang dan expired date dari LOT Zahir yang berqty positif. Bila pasangan tersebut mempunyai beberapa kode barang, semua kode tampil pada baris yang sama.
- QR tetap mempunyai identitas per kode barang (`BR|id` dari master lokal). Tombol generate tersedia untuk setiap kode yang belum punya QR. Status baris menjadi **Semua QR siap**, **Sebagian QR siap**, atau **Belum digenerate**.
- Total Data menghitung kelompok nama dan expired date; angka QR Selesai/Pending menghitung kode barang. Filter QR selesai menunjukkan kelompok yang seluruh kodenya siap; filter pending juga mencakup kelompok yang baru sebagian siap.
- Preview menyediakan pilihan kode bila kelompok memiliki lebih dari satu kode. **Cetak** pada preview memakai kode terpilih. Centang kelompok memilih seluruh kartu berkode yang QR-nya siap; **Cetak Semua QR Siap** tetap mencetak kartu per kode, karena isi QR dipakai untuk scan dan harus mempertahankan identitas kode.
- Pengelompokan hanya terjadi pada respons daftar Master Barang. Pencarian kode, validasi QR, cetak, dan scan tetap memakai pasangan kode barang, nama barang, dan expired date yang tepat.

## Penggunaan

1. Buka **Master Opname > Data Master Barang**. Cari nama atau expired date; satu baris menampung semua kode yang cocok.
2. Jika kolom Aksi QR menampilkan kode yang belum siap, generate QR untuk kode tersebut.
3. Klik baris untuk preview. Bila ada beberapa kode, pilih kode yang ingin dicetak pada panel preview. Centang baris untuk mencetak seluruh kartu siap dalam kelompok itu.

## Verifikasi

- Lint PHP controller dan view lulus; `git diff --check` lulus.
- Pada cache LOT lokal awal saat pengujian, 2.101 kombinasi kode/nama/tanggal menjadi 2.077 kelompok nama/tanggal. Setelah Refresh Zahir di browser, katalog terbaru menampilkan 2.076 kelompok. Jumlah dapat berubah mengikuti data sumber.
- Browser terautentikasi menunjukkan Amolin 300 EC 100 X 100 ml* tanggal 2028-01-01 sebagai satu baris dengan kode `AAMOL01, QAMOL01`, status **Sebagian QR siap**, dan tombol generate khusus `QAMOL01`. Preview menampilkan pilihan kedua kode. Mencentang kelompok memilih satu kartu yang QR-nya siap. Eksekusi cetak fisik belum dilakukan.
