# Database: konsep opname dan pemetaan pending

Tanggal: 3 November 2026 (Asia/Bangkok).

## Schema

**No schema change.** Tidak ada migrasi DDL baru. Master barang, master opname, hasil input, antrean request, dan pending memakai tabel yang sudah ada. Helper legacy yang dapat menyiapkan kolom saat aplikasi digunakan tetap berada pada project; pengujian baca-saja tidak menjalankannya.

QR baru menggunakan ID master barang dan path pada `tb_master_barang_all.qrcode`. `stockopname_opname.source_id` tetap mengacu master opname. Request barang tanpa master lot memakai source_id 0 di antrean; saat admin memproses, source_id hasil diarahkan ke master lot yang dibuat/ditemukan.

Pending berasal dari `stockopname_pending`, dijumlahkan berdasarkan kode_barang + no_lot + expired_date. Query mengurangi marker pending legacy dari buku sebelum menambahkan pending aktif, sehingga marker tidak dihitung dua kali. Saat pending diedit/dihapus, marker legacy terkait dibersihkan dalam transaksi yang sama. Data aktif pada audit tidak memiliki marker legacy.

## Audit sebelum update

| Pemeriksaan | Hasil |
| --- | ---: |
| Master barang | 5.916 |
| Kode barang duplikat | 0 |
| Dimensi berbeda dari P × L × T | 0 |
| Isi box nol/tidak positif | 0 |
| Master opname | 3.430 |
| Master dengan marker pending legacy | 0 |
| Hasil opname | 0 |
| Pending | 209 |
| Pending tidak cocok kode + lot + expired | 209 |

Semua pending memiliki pasangan nama + expired. Sebanyak 44 belum cocok kode; sisanya tetap belum cocok lot. Pengguna menyetujui pemetaan otomatis hanya untuk pasangan tunggal dan menyerahkan pasangan ambigu ke admin.

## Eksekusi

Script: `database/scripts/map_pending_20261103.php`. Default dry-run; `--apply` menjalankan update dalam transaksi, mengunci baris pending, membuat backup lebih dahulu, dan memeriksa total sebelum commit. Kandidat dihitung sebagai kombinasi kode + lot + expired yang berbeda, bukan hanya jumlah baris master.

- 198 baris diperbarui pada kolom kode_barang/no_lot mengikuti pasangan master tunggal.
- 11 baris ambigu tidak diubah dan belum termasuk stok pembanding. Identitasnya tercatat pada bagian `unresolved` di `mapping_plan.json`; badge Barang Pending menandai pasangan belum terpetakan.
- Nomor lot pending lama yang berbeda dipertahankan pada backup. Pemetaan ini menyelaraskan dengan identitas master yang tersedia, bukan verifikasi lot fisik di gudang.
- Timestamp otomatis `input_at` dapat diperbarui oleh database akibat UPDATE. Backup/rollback mencakup nilai timestamp awal.
- Pemeriksaan ulang dry-run: **will_change = 0**, unresolved = 11.
- Audit sesudah update: semua 209 kode pending cocok master; 11 kombinasi lengkap masih belum terpetakan.

| Ukuran | Sebelum | Sesudah |
| --- | ---: | ---: |
| Jumlah baris pending | 209 | 209 |
| SUM(qty), total pcs tersimpan | 67.682 | 67.682 |
| SUM(qty_box) | 3.025 | 3.025 |
| SUM(qty_pcs), pcs lepas tersimpan | 1.441 | 1.441 |

Qty historis dipertahankan; pemetaan tidak melakukan konversi ulang seluruh pending.

## Backup dan rollback

Folder: `database/backups/pending_mapping_20261103_113503/`.

- `pending_before.json`: snapshot seluruh 209 baris.
- `mapping_plan.json`: perubahan 198 baris dan 11 ID ambigu.
- `rollback.sql`: pemulihan seluruh kolom 198 baris yang dipetakan, dalam transaksi.
- `verification.json`: jumlah baris, total qty sebelum/sesudah, dan hasil penerapan.

Rollback belum dijalankan. Sebelum memakai rollback, hentikan sementara edit pending dan tinjau apakah baris sudah berubah setelah pemetaan, karena rollback mengembalikan nilai pada waktu backup. Jalankan terhadap database yang sama dengan konfigurasi project. Jangan menjalankan rollback bila tujuan hanya memperbaiki 11 pasangan ambigu; gunakan form admin.

## Verifikasi lanjutan

`tests/stockopname_data_audit.php` membaca hitungan dan total tanpa mencetak kredensial atau detail transaksi. Test SQL memakai fixture CTE dan tidak mengubah data operasional. Test smoke menjalankan query baca terhadap database aktif. UAT transaksi aplikasi dan pengecekan lot fisik belum dilakukan.
