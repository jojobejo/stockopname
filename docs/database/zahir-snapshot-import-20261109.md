> Dokumen historis. Alur ini telah digantikan oleh [Impor CSV Zahir dan Resign QR](zahir-csv-dan-resign-20261109.md). Upload JSON dan metadata manual tidak lagi berlaku.

# Database snapshot Zahir - 9 November 2026

## Bukti skema target development

Audit read-only tests/snapshot_schema_audit.php memakai konfigurasi aktual tanpa mencetak credential. tb_master_barang_all: InnoDB, utf8mb4_unicode_ci, id signed INT AUTO_INCREMENT PK; kd_barang VARCHAR(25) NOT NULL dengan beberapa indeks nonunik; kode_barang_system VARCHAR(50) NOT NULL tanpa UNIQUE. nama_barang TEXT NOT NULL, P/L/T signed INT(3), dimensi signed INT(12). Angka display-width bukan batas 999; batas 999 mengikuti domain aplikasi lokal yang didokumentasikan. Metadata wajib supplier/bahan/satuan/berat/kubikasi/qty_min; hpp DECIMAL(15,2) default 0. Nullability barcode/qrcode/gudang/wilayah tetap nullable. Duplikasi UPPER(TRIM(kd_barang)): 0 pada pemeriksaan ini. Jangan menganggap hasil ini berlaku untuk production.

stockopname_catalog_qrcode InnoDB utf8mb4_general_ci, PK(catalog_id,expired_date), keduanya NOT NULL. Tidak ada FK pada SHOW CREATE. stockopname_master_item InnoDB utf8mb4_unicode_ci, kode VARCHAR(50), PK ID, UNIQUE qrcode nullable, indeks kode/expired_lot/status; ID dan business data tidak disentuh. Relasi QR ke master lokal adalah relasi aplikasi berdasarkan ID; item opname terkait kode/expired/no_lot dan tidak boleh dibangun dari qty snapshot. Dump repo hanya referensi.

## Migration dan grain

Migration: database/sql/stockopname_zahir_snapshot.sql. Empat tabel InnoDB baru; tidak ALTER/UNIQUE/FK tabel lama.

- stockopname_zahir_batch: PK BIGINT lokal, UUID unik binary ASCII, SHA256 bytes unik, source_instance/scope stabil, exported_at asli + epoch, imported_at UTC, actor, row_count, before/after master_changes, previous_batch_id pointer batch yang benar-benar aktif sebelum commit.
- stockopname_zahir_qrlotexp: PK BIGINT lokal; batch_id/source_row untuk provenance; kode sumber asli VARCHAR(25) utf8mb4_unicode_ci kompatibel master; master_id signed INT; nama sumber dan normalized TEXT; tanggal NOT NULL; qty DECIMAL(24,6) signed; P/L/T/dimensi signed INT. UNIQUE(batch_id,kode_barang,expired_date), indeks master_id dan batch_id. Grain kode sumber + tanggal di dalam satu full batch. Tanggal null ditahan sebelum staging sehingga UNIQUE tidak mengandalkan perilaku NULL. source_instance/scope/snapshot/timestamps dinormalisasi di batch, tersedia melalui JOIN batch_id; tidak diduplikasi tiap row.
- stockopname_zahir_active: PK(source_instance,scope), batch_id pointer tunggal; semua reader JOIN atau mengambil batch yang ditunjuk. Pointer diganti hanya setelah seluruh rows dan master sukses dalam transaksi. Batch lama dan rows tidak dihapus.
- stockopname_zahir_audit: pelaku,waktu UTC,checksum,snapshot,sumber,cakupan,result,detail error/preview/commit/restore. Kegagalan commit di-rollback dahulu lalu rejection dicatat controller. Hash bukan tanda tangan keaslian.

FK baru tidak ditambahkan: preservasi master legacy dan kebutuhan audit kompatibilitas lintas consumer lebih dahulu. Integritas batch/row/active dikelola satu transaksi dan akses terbatas aplikasi; akun DB/petugas tidak boleh mengubah rows immutable secara manual. ready() menolak engine non-InnoDB dan perubahan domain/collation master yang belum diaudit. Histori referensi memerlukan kebijakan retensi terpisah; tidak ada purge otomatis.

## Backup dan pemasangan

Tidak ada migrasi dijalankan pada database aplikasi terkonfigurasi atau production dalam development ini; hanya database uji acak dimigrasi. Jalankan audit ulang di target read-only: SHOW CREATE/INDEX untuk master/QR/item, information_schema engine/collation, duplikasi TRIM/case kode dan relasi opname/pending/transaksi. Cocokkan panjang kode dan domain wajib sebelum memakai SQL. Siapkan backup konsisten seluruh database dengan mysqldump --single-transaction --routines --triggers --events dan backup aset QR; gunakan option-file privat untuk credential (jangan menaruh password di command line/log). Simpan checksum, waktu UTC, jumlah row, posisi backup, serta verifikasi restore di database uji sebelum maintenance.

Set mode direct selama persiapan. Setelah backup dan audit disetujui proses rilis, jalankan SQL hanya pada target yang dipilih secara eksplisit. Migration sengaja tidak IF NOT EXISTS agar skema tak cocok tidak tersamarkan. Jika SQL DDL terputus, audit tabel yang sudah tercipta sebelum melanjutkan; DDL MySQL tidak transactional. Jangan menghapus tabel yang sudah berisi batch bisnis. Development ini tidak melakukan deploy.

## Rollback aman

Pemulihan data memakai aksi admin POST terpisah di halaman impor: target previous_batch_id dari batch aktif, bukan sekadar ID batch terbesar. Transaksi memakai lock KIUBR sama; identitas snapshot aktif harus sama dengan halaman. Kandidat existing hanya dikembalikan bila nilainya masih identik dengan after dari batch tersebut; jika ada edit sesudahnya seluruh rollback ditahan. Master baru tetap disimpan beserta ID/system/QR; jangan DELETE master baru karena mungkin sudah dipakai opname. Pointer aktif kembali ke batch sebelumnya dan audit from/to disimpan. Timestamp watermark impor tidak mundur karena recovery. Tidak ada snapshot sebelumnya berarti recovery otomatis ditolak; gunakan backup yang diuji untuk pemulihan awal melalui proses terpisah.

Rollback aplikasi dapat mengembalikan mode direct dan revisi kode tanpa menghapus empat tabel baru. Mode direct memerlukan jaringan sumber dan memiliki perilaku cache/TLS lama; pilih sesuai kondisi operasional. Jangan DROP tabel/audit atau restore seluruh master di atas business data yang sudah berubah. Pemulihan backup penuh hanya dalam maintenance terencana dengan rekonsiliasi data setelah backup, bukan rollback otomatis fitur. Tidak ada script rollback destruktif yang menghapus master/QR/histori.
