# Scan kartu stock dan status Master Opname — 5 November 2026

## Perubahan aplikasi

- Kartu Stock pada halaman user menampilkan kode barang dari `tb_master_barang_all` setelah QR barang (`BR|id`) dibaca. Expired date ditampilkan setelah memilih baris Master Opname yang tanggalnya juga tersedia pada Data Master Barang (LOT Zahir). Bila hanya satu tanggal yang cocok, pilihan dan tanggal langsung terisi. Bila lebih dari satu, user memilih tanggal; lot tetap ditunjukkan pada opsi bila perlu membedakan tanggal yang sama.
- Lookup dan penyimpanan hasil scan memeriksa pasangan kode, nama, dan expired date terhadap Data Master Barang. Baris yang tidak cocok tidak dapat dipakai untuk menyimpan hasil scan. Jika Master Opname belum memiliki tanggal yang cocok, user melihat pesan dan dapat memakai alur Opname Request yang sudah ada.
- Tabel **Data Master Opname** menyembunyikan kolom Lot dan QR Barang. Kolom `# Status Master Barang` menampilkan **Sudah digenerate**, **Belum digenerate**, **Data tidak sinkron**, **Tidak ada di Master Barang**, atau **Belum terverifikasi**, beserta alasan singkat. Lot tetap disimpan untuk identitas dan perhitungan stok; QR tetap berada di Master Barang dan preview kartu.
- Status membandingkan kode dan nama dengan Master Barang lokal, lalu pasangan kode, nama, expired date dengan Data Master Barang LOT Zahir. Status QR berhasil memerlukan path `BR_<id Master Barang>.png` yang tepat dan file yang ada. Jika sumber Zahir gagal, status menjadi **Belum terverifikasi**, bukan salah satu status sinkronisasi.
- Tombol **Refresh** pada Master Opname meminta pembaruan sumber LOT Zahir. Jika daftar memakai cache lebih dari lima menit, halaman menampilkan penanda cache lama.

## Cara penggunaan

1. Buka **Data Master Opname** dan lihat status di kolom terakhir sebelum pilihan cetak. **Data tidak sinkron** menunjukkan nama atau expired date yang perlu dicocokkan dengan tab **Data Master Barang**.
2. Pada halaman user, scan kartu stock. Kode barang terlihat pada kartu; expired date otomatis tampil bila ada satu pasangan yang cocok, atau pilih dari daftar bila lebih dari satu.
3. Jika tidak ada tanggal yang cocok, hasil scan tidak dapat disimpan. Gunakan **Opname Request** untuk barang/tanggal yang belum menjadi Master Opname, atau minta admin menyelaraskan data.

## Verifikasi dan batasan

PHP lint controller, model, dan view lulus. Read-only smoke test aplikasi lulus. Pada browser desktop dengan sesi aktif, tabel menampilkan dua baris tanpa kolom Lot/QR dan status sesuai data: `AALUF01` **Sudah digenerate**, `AAMOL01` **Data tidak sinkron** karena nama LOT Zahir memuat `*` sedangkan Master Opname tidak. Refresh sumber Zahir selesai dan status tetap konsisten. Uji scan kamera serta penyimpanan pada ponsel user belum dilakukan.
