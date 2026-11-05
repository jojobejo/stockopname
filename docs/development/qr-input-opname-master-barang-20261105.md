# QR dan input opname berdasarkan Data Master Barang — 5 November 2026

## Perubahan aplikasi

- QR yang dibuat dari tab **Data Master Barang** sekarang berisi `MB|<id master lokal>|<expired date YYYY-MM-DD>`. File bernama `MB_<id>_<YYYYMMDD>.png`, sehingga satu kode barang dengan beberapa tanggal mempunyai QR yang berbeda. Pengelompokan daftar tetap berdasarkan pasangan nama barang dan expired date; bila satu kelompok memiliki beberapa kode, setiap kode mempunyai QR sendiri.
- Pembuatan QR mencocokkan kode dan nama master lokal dengan pasangan kode, nama, dan expired date pada Data Master Barang LOT Zahir yang berqty positif. Kartu cetak menampilkan tanggal yang terkandung dalam QR. Status QR pada tab Data Master Barang dihitung dari file QR per tanggal; file lama `BR_*.png` tidak dianggap siap.
- **User Input Opname > Scan** hanya menerima QR baru. Lookup menampilkan nama dan expired date langsung dari Data Master Barang tanpa pilihan Master Opname. Saat simpan, server membaca ulang QR dan pasangan master agar perubahan field browser atau data master yang berubah tidak menghasilkan opname dengan identitas salah.
- **Input Manual** memilih expired date dari Data Master Barang untuk kode dan nama yang sama. Jumlah pcs/box dan tim/wilayah tetap mengikuti validasi lama. **Opname Request** tetap menjadi alur permintaan untuk barang/tanggal yang belum tersedia.
- Nilai `stockopname_opname.kode_barang`, `nama_barang`, `expired_date`, dan dimensi hitung scan berasal dari Data Master Barang serta master lokal yang cocok. `scan_code` menyimpan `MB|...`; karena QR tidak menunjuk baris Master Opname atau lot tertentu, `source_id` bernilai `0` dan `no_lot` bernilai `-`. Input manual mempertahankan jejak di tabel manual dengan identitas kode dan tanggal yang sama. Edit jumlah scan mengambil dimensi dari master lokal melalui ID dalam `scan_code`.
- Preview dan cetak kartu dari Master Opname hanya menampilkan QR `MB` yang cocok dengan nama dan tanggal. Cetak dari Master Opname tidak membuat QR secara otomatis; gunakan tab Data Master Barang untuk generate. Reset QR Master Opname tidak menghapus file `MB_*.png`.
- Status dan filter QR pada daftar Master Opname kini menghitung kesiapan QR `MB` per tanggal; angka QR lama `BR` tidak lagi dianggap selesai.

## Cara penggunaan

1. Admin membuka **Master Opname > Data Master Barang** dan menyelaraskan nama barang lokal dengan Data Master Barang bila berbeda.
2. Untuk setiap kode dan expired date yang dipakai, klik **Generate**. Jika kelompok memiliki beberapa kode, generate setiap kode. Cetak kartu melalui preview atau pilihan kartu pada tab tersebut. Kartu lama dengan `BR|id` harus diganti.
3. User membuka **User Input Opname**, scan QR baru. Periksa nama dan expired date yang muncul otomatis, isi qty pcs/box, lalu tekan **Input Opname**.
4. Untuk input tanpa scan, gunakan **Input Manual**, pilih nama dan expired date dari daftar master, lalu isi qty. Jika pasangan tidak ada pada Data Master Barang, gunakan **Opname Request** atau minta admin memeriksa master.

## Verifikasi dan batasan

- PHP lint controller, model, view, dan test lulus. Uji kontrak `tests/stockopname_input_test.php` meliputi lookup, simpan scan, input manual, perubahan nama master, QR lama, tanggal salah, qty, tim/wilayah, dan request. Uji PNG QR lokal lulus. `git diff --check` lulus.
- Belum dilakukan scan kamera, cetak fisik, atau penyimpanan terautentikasi pada database produksi. Perlu UAT dengan beberapa expired date pada satu kode, beberapa kode pada nama/tanggal yang sama, dan pemeriksaan laporan per lot. QR `MB` memuat tanggal tetapi tidak memuat nomor lot; hasil scan dicatat pada kelompok kode/nama/tanggal dengan lot `-`.

