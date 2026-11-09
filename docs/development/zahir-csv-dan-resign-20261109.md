# Impor CSV Zahir dan Resign QR - development 9 November 2026

Dokumen ini menggantikan alur upload JSON/snapshot sebelumnya. Tujuan tetap menyediakan referensi Zahir pada Stockopname ketika production tidak dapat mengakses jaringan lokal Zahir. Tidak ada deploy atau migration ke production/database aplikasi terkonfigurasi dalam pekerjaan ini; mutasi pengujian menggunakan database uji acak yang dibuang setelah pengujian.

## Alur dan batas bisnis

Menu **Impor Data Zahir CSV** (`admin/stockopname/zahir-csv`, alias route lama tetap tersedia) memakai C_Stockopname::zahir_snapshot -> Zahir_snapshot_validator -> M_Zahir_snapshot -> stockopname_zahir_import. Upload CSV, validasi seluruh rows, preview, konfirmasi lengkap, transaksi master/referensi, laporan. JSON/SQL upload ditolak. Admin tidak mengisi UUID, waktu ekspor, row_count atau metadata teknis. Aplikasi membuat identitas batch internal, checksum, nama file, pelaku dan waktu impor UTC. Waktu sumber disimpan NULL, bukan diisi waktu upload.

Header lampiran tujuh kolom diterima; versi empat kolom pertama juga diterima. P/L/T dibuang sejak parsing, termasuk jika kosong/teks/negatif/pecahan. Tidak divalidasi, dihitung, disimpan dari CSV, atau disinkronkan. Kontrak: docs/contracts/zahir-csv-v1.md. Qty nol/negatif tetap disimpan, 18 digit bulat + 6 pecahan, DECIMAL(24,6); grouping memakai BCMath agar tidak kehilangan pecahan. Null/invalid expired, kode terlalu panjang, nama invalid, grain ganda dan nama konflik antar expired satu kode menahan seluruh impor.

Satu file yang dikonfirmasi mengganti seluruh referensi aktif pada source_instance/scope konfigurasi. File kosong/gagal/error satu row tidak mengganti pointer. Missing hanya menonaktifkan referensi katalog, tidak menghapus master/QR/histori. CSV tanpa metadata waktu tidak bisa membuktikan kelengkapan ataupun lebih baru dari sumber; admin harus memastikan ekspor lengkap sebelum konfirmasi. Checksum mendeteksi bytes identik, bukan autentikasi exporter atau kebaruan data. File identik yang aktif no-op; perbedaan master lokal hanya dilaporkan pada no-op, tidak diam-diam disinkronkan ulang. File historis yang pernah diterima tidak diaktifkan melalui upload biasa; gunakan pemulihan terpisah.

Master dicocokkan dengan TRIM + equality utf8mb4_unicode_ci, bukan nama. Lebih dari satu ID cocok = konflik. Existing hanya kandidat perubahan nama dengan before/after dan persetujuan; ID/kode/QR/ukuran/supplier/metadata lain tetap. Baru menggunakan KIUBR dan default mekanisme katalog aktual: p/l/t/dimensi=0, supplier/bahan/satuan='-', berat/qty_min=0, kubikasi='0', hpp default0. Ukuran nol berarti belum dilengkapi, bukan isi box1; input box ditolak sampai ukuran lokal sah. Input pcs sesuai validasi aplikasi tetap tersedia. Importer tidak pernah membuat stockopname_master_item atau mengubah hasil opname/pending/approval/saldo stok/Zahir.

## Lokal dan production

Konfigurasi berdasarkan ENVIRONMENT/CI_ENV, bukan Host header atau tebakan IP browser. Local/testing default imported + perbandingan kode/nama ke tb_master_barang_all. Preview memuat matched, missing_local, name_changed, dimensi lokal yang dipertahankan, identitas konflik, kelompok aktif/nonaktif. Perbedaan nama lokal saat generate/scan memblokir operasi sampai direkonsiliasi. Memilih direct via ZAHIR_CATALOG_MODE masih tersedia khusus non-production untuk perilaku integrasi lama.

CI_ENV=production memaksa imported, bahkan bila ZAHIR_CATALOG_MODE=direct. CSV aktif menjadi sumber kode/nama/expired/qty referensi QR; tidak mensyaratkan nama lokal sama saat membaca/generate/scan/preview/cetak. ID master lokal tetap dipakai sebagai identitas QR dan dimensi tetap dibaca dari master lokal. Konflik kode/ID tetap menahan impor di semua lingkungan. Set CI_ENV=production pada server secara eksplisit karena index.php proyek default development. Sumber dan scope diatur ZAHIR_SOURCE_INSTANCE/ZAHIR_SCOPE, bukan input bebas operator.

Zahir_lot_catalog imported hanya membaca tabel lokal, termasuk refresh, gagal/missing migration dan empty state. Tidak fallback ke IP internal/cache HTML. Consumer yang diaudit: scan_master_barang_item, manual expired, generate single/batch, daftar/grouping/status QR, Master Opname daftar/summary, preview aset, cetak per item/batch, kartu stock. Preview/cetak aset kini memeriksa pasangan referensi aktif. QR summary controller menghitung terhadap map katalog aktif. Sumber langsung LPB C_Ics di luar modul tetap tidak diubah.

## Generate dan Resign QR

Generate biasa adalah penerbitan pertama untuk pasangan ID master + expired. Jika sudah ada record stockopname_catalog_qrcode, server mengembalikan penolakan/skipped tanpa memanggil generator, termasuk jika file aset hilang. Batch melewati yang sudah pernah dibuat. GET halaman QR hanya membaca, tidak lagi membuat QR. POST single dan batch wajib token sesi; CSRF framework turut dikirim/dirotasi bila diaktifkan. Guard admin dashboard melindungi impor, generate, resign dan riwayat.

Resign adalah penerbitan ulang aset QR, bukan tanda tangan digital dan bukan pencabutan label lama. POST resign-qrcode mewajibkan alasan 5-500 karakter, pasangan aktif dan expected_file dari status yang dilihat admin. Aset baru bernama acak dan tidak menimpa file lama; payload tetap MB|id|YYYY-MM-DD. Label lama tetap bisa discan selama referensi masih aktif. Tidak ada aturan scan sekali pakai. Jika perlu pencabutan label/QR versioned, itu merupakan perubahan kontrak bisnis terpisah.

M_Catalog_qr_issue memegang lock stockopname_master_barang_kiubr, mengunci row master/QR, memvalidasi ulang referensi di dalam lock, membuat PNG lokal, mengganti pointer aset, mencatat stockopname_catalog_qr_issue, memperbarui path QR master lalu commit. Gagal generator/DB: QR lama tetap aktif, file baru gagal dipublikasikan dibuang. Double-click status lama ditolak lewat expected_file. Generasi pertama sukses dan resign dicatat dengan actor, UTC, alasan, aset lama/baru dan payload. Menu Riwayat menampilkan sampai 50 penerbitan terbaru; QR legacy belum memiliki event awal, tetapi resign berikutnya tercatat.

Ditemukan dua nama lock KIUBR pada kode awal; jalur create_master_barang_catalog disamakan dengan M_Keuangan/importer menjadi stockopname_master_barang_kiubr. Writer di luar aplikasi yang tidak memakai lock masih merupakan batas konsistensi; tidak menambahkan UNIQUE pada master legacy.

Semua consumer yang tadinya mengasumsikan nama file deterministik MB_id_tanggal.png sekarang mengambil qrcode_file yang aktif: katalog, summary/list Master Opname, preview dan cetak. File QR lama tidak dihapus oleh impor atau resign.

## Keamanan dan konsistensi

Staging acak di TEMP OS, ditolak jika direktori berada dalam document root. Token preview acak membedakan file antar tab; konfirmasi lama tidak dapat mengimpor file preview tab lain. Konfirmasi wajib memastikan CSV full. Decode ulang, hash ulang dan fingerprint master/active/config diulang saat commit di dalam lock/transaksi. Sesi admin/token POST, escaping sumber, batas5MiB/10000rows dan laporan per baris tetap berlaku. TEMP privat perlu ACL dan housekeeping untuk preview yang ditinggalkan setelah satu jam. Generator QR baru memerlukan GD/library QR lokal; tidak fallback layanan QR eksternal.

## Bukti otomatis

- tests/stockopname_zahir_snapshot_test.php dijalankan local/testing dan --production pada database uji terpisah. Lulus parsing CSV tanpa metadata, pengabaian ukuran, penolakan JSON/error/duplikat/kosong, master compare, ID/system/ukuran preservation, no-op checksum, konflik lokal, rollback trigger database, perubahan master setelah preview, zero/missing/representative/expired, pemulihan batch/nama, scan controller aktual.
- QR tests lulus penerbitan pertama, duplicate generate ditolak, resign alasan wajib, aset lama tetap ada, satu QR aktif dengan dua event audit, expected_file lama ditolak, kegagalan audit DB membatalkan resign, lock dua koneksi, preview/cetak/list/summary mengikuti aset baru dan referensi aktif.
- --production --csv <lampiran> berhasil mengimpor seluruh 2065 rows lampiran tanpa edit dan tanpa metadata sumber. File125906byte, valid2065/error0/duplikat0. Validator sekitar0,024detik, peak4MiB.
- Kapasitas10000rows CSV nama panjang sekitar3,8MB: preview9,78detik, commit11,14detik, peak162MiB setelah fixture lampiran. Batas tidak dinaikkan. PHP development512M; rekomendasi worker512MiB, timeout120detik, post>=8MiB, upload>=5MiB. Query TRIM dan penguncian seluruh master bisa menahan edit lain; ukur ulang hosting sebelum rilis.
- tests/stockopname_zahir_csv_upgrade_test.php lulus migrasi kompatibilitas dengan row legacy/batch/dimensi tetap. Fixture hasil opname dan pending pada DB uji tetap identik. Tidak ada Master Opname otomatis.
- Regression katalog/QR/konsep/input dan read-only smoke lokal lulus. PHP lint dan git diff --check diperiksa.

Belum UAT browser autentikasi/role, CSRF enabled, upload terputus/dua tab, modal Riwayat/Resign, semua consumer dengan firewall memblokir IP Zahir, kamera dan cetak fisik. Mode production diuji sebagai konfigurasi dalam database uji lokal, bukan di server production. Saldo transaksi/approval lain dibatasi lewat audit tabel penulisan; belum UAT seluruh proses bisnis.

## Manifest perubahan

Config zahir_stock_lot; tambahan route CSV/resign/history (alias lama dipertahankan); C_Stockopname; Zahir_lot_catalog dan validator; M_Zahir_snapshot; M_Catalog_qr_issue baru; M_Stockopname (lock, lookup aset, status); view import/status/katalog/link master lokal; SQL fresh/upgrade/QR audit; tests snapshot/capacity/upgrade; docs development/database/usage/contracts. Perubahan login/config database/default_controller yang sudah ada sebelum pekerjaan tetap dipertahankan, bukan bagian fitur ini.
