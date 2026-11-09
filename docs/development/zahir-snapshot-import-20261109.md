> Dokumen historis. Alur ini telah digantikan oleh [Impor CSV Zahir dan Resign QR](zahir-csv-dan-resign-20261109.md). Upload JSON dan metadata manual tidak lagi berlaku.

# Development impor snapshot Zahir - 9 November 2026

## Arsitektur dan batas bisnis

Route baru `admin/stockopname/zahir-snapshot` -> `C_Stockopname::zahir_snapshot` -> validator `Zahir_snapshot_validator` -> model `admin/M_Zahir_snapshot` -> view `stockopname_zahir_import`. Guard admin dashboard yang sama dengan katalog berlaku; role inputer/supervisor tidak mendapat method ini. Mutasi POST dengan master_action_token sesi; hidden CSRF framework juga diberikan jika proteksi global diaktifkan. Proteksi global aktual nonaktif, sehingga token sesi tetap wajib.

Upload JSON v1 atau CSV dengan metadata wajib -> validasi semua rows -> preview -> konfirmasi -> decode/validasi ulang -> lock KIUBR -> transaksi -> kunci master -> hitung ulang preview/fingerprint -> sinkron kandidat master -> simpan batch/semua kode sumber -> ganti pointer aktif -> audit -> commit. Dry-run hanya menulis audit dan staging privat, bukan data operasional. Fingerprint mengikat keadaan master yang cocok, pointer batch aktif, checksum file. Konfirmasi juga memakai metadata staging yang tersimpan di sesi; file asli dibaca ulang dan hash diperiksa. Preview baru membatalkan preview sebelumnya. Batas konfirmasi satu jam.

File disimpan dengan nama acak dalam direktori TEMP OS di luar document root, bukan uploads publik; tidak pernah dieksekusi. Periksa ACL TEMP saat instalasi Windows/Linux. File dibuang setelah commit atau preview baru. Preview yang ditinggal dapat menyisakan file TEMP; operator harus membersihkan berkas prefix so_zahir_ yang usianya melebihi masa sesi/preview melalui kebijakan housekeeping OS. Jangan menghapus staging yang masih aktif. Tidak ada koneksi exporter baru, sehingga tidak ada TLS baru yang dinonaktifkan.

Impor hanya menulis tb_master_barang_all (kolom kandidat atau identitas baru), stockopname_zahir_* dan audit. Tidak membuat/mengubah stockopname_master_item, hasil opname, approval, pending, transaksi saldo, atau database Zahir. Qty snapshot tidak menjadi hasil hitung fisik.

## Mapping dan identitas

Kode dicocokkan dengan TRIM dan equality database utf8mb4_unicode_ci. Tidak mencocokkan nama untuk identitas. Kode input dibatasi subset ASCII yang dinyatakan kontrak untuk menghindari normalisasi identitas Unicode yang belum diaudit. Dua ID lokal cocok menjadi konflik; tidak memakai MIN(id). Nama/ukuran berbeda antar LOT kode yang sama juga error.

Existing: update hanya nama_barang,p,l,t,dimensi sesuai before/after yang disetujui. id,kd_barang,kode_barang_system,barcode,qrcode,supplier,bahan aktif,satuan,berat,kubikasi,qty_min,gudang,wilayah,hpp dipertahankan. Baru: alokasi KIUBR memakai named lock stockopname_master_barang_kiubr dan generator urutan yang sama dengan M_Keuangan::master_barang_insert_many. Logika alokasi dilakukan dalam transaksi importer, karena memanggil fungsi existing secara nested akan melepaskan lock sebelum aktivasi selesai. Default wajib: supplier/bahan/satuan string kosong; berat/qty_min nol; kubikasi '0'; hpp '0.00'; gudang/wilayah/barcode/qrcode NULL. P/L/T positif 1-999; produk muat signed INT. Tidak mengisi nol/null sebagai pengganti ukuran yang hilang.

## Pemisahan sumber dan audit consumer

Konfigurasi application/config/zahir_stock_lot.php: ZAHIR_CATALOG_MODE=direct/imported, ZAHIR_SOURCE_INSTANCE=zahir-local, ZAHIR_SCOPE=all. Default direct mempertahankan perilaku lama; perubahan mode tidak mengimpor atau memutasi data. Unknown mode gagal tertutup. Imported hanya membaca pointer batch lokal dan rows immutable; refresh tidak menghubungi IP internal atau cache HTML, termasuk saat error/tidak ada snapshot. BCMath wajib.

Seluruh pembacaan katalog Stockopname yang ditemukan memakai Zahir_lot_catalog::rows: scan_master_barang_item, ajax_manual_expired, generate single/all, ajax_master_barang_catalog_list, print_catalog_stock_cards, master_barang_list_response. Daftar/summary/status QR memakai hasil yang sama; model M_Stockopname tetap membaca master lokal dan tabel QR. M_Keuangan tetap mengelola master lokal dan impor lamanya. Tidak mengubah endpoint LPB di C_Ics yang di luar cakupan snapshot Stockopname.

Hasil library mempertahankan status/data/stale/cached dan field barang/expired/ukuran. Imported menambahkan source metadata dan qty_lot agregat string. stale=false berarti pembacaan batch aktif berhasil, bukan sumber baru; source.age_seconds dan exported_at harus digunakan menilai usia. UI banner menampilkan identitas, waktu sumber, waktu impor UTC, usia. Refresh katalog tidak lagi mengklaim data sumber baru pada imported. Empty state mengarahkan upload. Jangan menafsirkan cached=false sebagai bukti data Zahir terkini.

Grouping nama tanpa * dan spasi rapi + tanggal mengikuti kode lama, penjumlahan qty imported eksak BCMath 6 desimal, total positif saja tampil. Kode positif prioritas Q lalu alfabetis. P/L/T mengikuti kode terpilih. Semua kode asli tetap disimpan sehingga tidak menjumlahkan hasil grouping dua kali. QR MB|id|tanggal dan format lama pada model tidak diubah. Importer tidak menulis stockopname_catalog_qrcode atau file aset. QR lama tetap ada; ketika kode representatif/tanggal berubah, scan lama gagal sesuai validasi existing, QR aktif baru menggunakan ID lokal yang sesuai.

## Validasi otomatis dan keterbatasan

Skema database development dibaca langsung melalui SHOW CREATE TABLE/SHOW INDEX, bukan menganggap dump sebagai skema target. Audit tidak menjangkau production. Lihat dokumen database untuk detail.

`php tests/stockopname_zahir_snapshot_test.php` membuat database uji bernama acak stockopname_snapshot_test_*, menyalin struktur target, menjalankan migration hanya di database itu, lalu membuang database uji pada finally. Uji mencakup valid/ulang, preservasi ID/system/QR, konflik kode lokal, input parsial/kosong/lebih lama/UUID berbeda isi, tanggal null/ukuran pecahan, error satu baris, kegagalan insert via trigger dan rollback, master berubah setelah preview, dua koneksi memperebutkan lock KIUBR, qty nol/negatif, missing grain, representative/tanggal berubah, rollback kandidat, dan scan MB melalui method controller aktual.

Opsi `--csv <path>` mengimpor CSV lampiran tanpa edit dalam database uji menggunakan metadata sintetis yang dinyatakan test, lalu menguji pengiriman ulang. Bukan bukti metadata full/waktu sumber exporter. Fixture stockopname_opname dan stockopname_pending disalin terbatas untuk membuktikan isinya tidak berubah. Tidak ada Master Opname baru oleh import.

Batas kapasitas terukur lokal: validator 10000 rows ~0,062 detik / peak 30 MiB. Transaksi 10000 rows, nama panjang, setelah fixture CSV 2065 rows: preview ~9,62 detik, commit ~11,27 detik, peak 166 MiB. Batas file 5 MiB/10000 rows dipilih konservatif terhadap PHP development memory_limit 512M. Siapkan worker 512 MiB, timeout minimal 120 detik, post_max_size >=8M dan upload_max_filesize >=5M; ukur ulang di hosting sebelum produksi. Query TRIM melakukan pemindaian master dan transaksi mengunci seluruh master untuk konsistensi; volume master besar menambah durasi dan menahan edit lain. Jangan menaikkan batas sebelum benchmark target.

CSV lampiran: 2065 source/valid, 0 error, 0 duplikat grain, validasi baris ~0,034 detik. Metadata sumber tetap wajib diperoleh dari exporter; tidak diambil dari nama file atau waktu filesystem.

Lint PHP seluruh berkas yang diubah dan regression tests katalog, QR, konsep, input dijalankan terpisah. UAT browser belum dijalankan: sesi admin/role, token/CSRF, upload terputus, pesan preview, cetak/preview layout, kamera scan, UI batch QR, dan semua consumer dengan firewall memblokir IP Zahir. Uji imported otomatis berjalan tanpa server Zahir, namun bukan bukti firewall/browser end-to-end. Saldo transaksi/approval lain diverifikasi melalui pembatasan tabel penulisan dalam audit kode; belum UAT seluruh proses bisnis.

## Berkas perubahan

Config zahir_stock_lot.php; tambahan route (perubahan default_controller pengguna dipertahankan); controller C_Stockopname.php; library Zahir_lot_catalog.php dan validator baru; model M_Zahir_snapshot.php; view import/status baru serta banner/link di master lokal, katalog, Master Opname, input dan toolbar cetak kartu; migration SQL; tests schema/capacity/snapshot; docs contracts/development/database/usage. Config login/database dan view login yang sudah dirty bukan perubahan fitur ini dan dipertahankan.
