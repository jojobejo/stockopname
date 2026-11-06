# Penggabungan Master Barang Zahir (6 November 2026)

## Perubahan aplikasi

- Sumber LOT Zahir pada `admin/stockopname/master_barang` menghapus karakter `*` dari nama dan merapikan spasi sebelum menggabungkan data.
- Satu baris ditampilkan untuk setiap nama barang dan expired date yang sama. Qty LOT dijumlahkan dari semua kode pada kelompok tersebut; kelompok dengan total Qty tidak positif tidak ditampilkan.
- Bila beberapa kode memiliki Qty positif, satu kode dipilih untuk dimensi, QR, dan kartu stock. Kode berawalan `Q` diutamakan; jika lebih dari satu kode setara, dipilih menurut urutan kode alfabetis agar hasil tetap stabil. Kode dengan Qty nol atau negatif tidak dipilih.
- Snapshot LOT memakai nama cache baru `zahir_master_barang_lot_qty_v2.json` agar aturan baru tidak membaca hasil penggabungan lama. Tombol **Refresh** mengambil ulang sumber Zahir.
- Nama master lokal yang masih memuat `*` tetap dapat dicocokkan dengan nama Zahir yang sudah dibersihkan bila kode dan expired date sesuai.
- Kartu Stock pada layar input scan menampilkan **Nama Barang** dan **Expired Date**. Kode barang tetap dipakai sistem saat menyimpan hasil opname.

## Cara menggunakan

1. Buka **Master Opname → Data Master Barang** atau `admin/stockopname/master_barang`, lalu tekan **Refresh**.
2. Periksa satu baris per nama dan expired date serta kode terpilih dan dimensinya. Generate QR untuk baris yang belum siap.
3. Cetak kartu stock dari baris terpilih. Saat QR dipindai pada input opname, periksa nama barang dan expired date yang tampil sebelum mengisi jumlah.
4. QR lama dengan kode yang tidak lagi terpilih perlu digenerate ulang dari baris katalog yang sekarang; validasi scan mengikuti kode terpilih pada data Zahir terbaru.

## Validasi

- `tests/stockopname_zahir_catalog_test.php` lulus untuk penghapusan `*`, penggabungan berdasarkan nama dan tanggal, prioritas kode `Q`, serta filter Qty.
- PHP lint pada library, controller, dan dua view terkait lulus.
- UAT dengan sesi admin, sumber Zahir aktif, pemindaian kamera, dan cetak fisik belum dilakukan.
