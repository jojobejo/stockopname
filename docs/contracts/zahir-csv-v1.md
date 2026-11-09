# Kontrak referensi CSV Zahir v1

Kontrak aktif menggantikan kontrak upload JSON/snapshot sebelumnya berdasarkan persetujuan pengguna. Exporter tidak perlu menambah metadata teknis untuk importer ini.

CSV UTF-8 dengan atau tanpa BOM, delimiter koma, quoting CSV standar. Header lampiran tepat:

```csv
kode_barang,nama_barang,expired_date,qty_lot,p,l,t
```

Header kode_barang,nama_barang,expired_date,qty_lot saja juga diterima. Jika memakai tujuh kolom, setiap row harus tetap memiliki tujuh kolom; isi P/L/T bebas dan dibuang sepenuhnya. Tidak mengambil ukuran/dimensi dari CSV. Setiap row menyimpan kode asli sebelum grouping; qty nol/negatif tetap disertakan. Seluruh file dianggap pengganti lengkap untuk pasangan source_instance/scope konfigurasi setelah admin menyetujuinya.

- kode_barang: teks ASCII nonkosong maksimal25karakter sesuai master VARCHAR25; huruf/angka/spasi/_/./:/slash/- diperbolehkan. Matching TRIM+collation master; kode sumber asli tetap disimpan. Tidak memotong kode atau mencocokkan hanya nama.
- nama_barang: UTF-8 nonkosong maksimal65535byte, tanpa karakter kontrol; nama sumber dipertahankan, nama normalized membersihkan * dan spasi.
- expired_date: YYYY-MM-DD yang sah, minimal1000-01-01. Kosong/null/invalid menahan seluruh impor.
- qty_lot: teks desimal bertitik, bertanda minus opsional, tanpa eksponen/pemisah ribuan; maksimal18digit bulat dan6pecahan. DECIMAL24,6 signed menampung domain tersebut tanpa pembulatan. BCMath menjaga agregat eksak lintas kode.

Grain: kode sumber setelah trim/case + expired per batch. Duplikat grain ambigu ditolak, tidak dijumlahkan importer. Nama berbeda antar expired satu kode ditolak. Exporter harus menyelesaikan grain internal LOT terlebih dahulu. Kelompok katalog nama normalized+expired menjumlahkan lintas kode, total positif saja, kode positif prioritas Q lalu alfabetis. Ukuran runtime berasal dari master lokal, bukan ukuran tersimpan dari impor lama.

Maksimum5MiB/10000rows, full kosong ditolak. File row error membatalkan aktivasi. JSON dan SQL dump tidak diterima. UUID batch/checksum/nama file/waktu upload/actor dibuat aplikasi. Tidak ada waktu ekspor sumber di CSV; imported_at adalah waktu impor, exported_at/epoch NULL. Checksum adalah kesamaan bytes, bukan autentikasi/kebaruan/kelengkapan. Pengiriman identik aktif no-op; replay historis yang sudah dikenal ditahan. Kelengkapan dan umur file yang belum pernah diterima memerlukan verifikasi operator.

Contoh sintetis di zahir-csv-v1.example.csv sengaja mengandung P/L/T kosong/teks sebagai bukti kolom diabaikan. Bukan data bisnis atau hasil hitung fisik.
