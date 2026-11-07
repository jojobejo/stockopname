# Database Master Opname dan QR, 7 November 2026

Ada perubahan skema database: tabel `stockopname_catalog_qrcode` menyimpan satu QR untuk setiap `(catalog_id, expired_date)` dengan primary key gabungan. SQL tersedia di `database/sql/stockopname_catalog_qrcode_variants.sql`; aplikasi juga membuat tabel bila belum tersedia saat generate QR pertama. Cadangkan database sebelum deploy.

Impor menambah baris `stockopname_master_item` dengan `input_source` `opname_master` atau `opname_it`. `id` mengikuti auto-increment. Penghapusan seluruh isi memakai TRUNCATE; penghapusan per sumber memakai DELETE dengan WHERE input_source. Aksi ini tidak menghapus catatan QR pada tabel varian.
