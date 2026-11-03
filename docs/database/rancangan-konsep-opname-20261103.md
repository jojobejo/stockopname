# Rancangan database konsep stock opname

Catatan: dokumen ini merekam tahap analisis awal. Hasil implementasi terbaru ada di [pemetaan-pending-dan-konsep-opname-20261103.md](pemetaan-pending-dan-konsep-opname-20261103.md).

Tanggal: 3 November 2026. Status: rancangan, bukan migrasi yang telah dijalankan.

## Status perubahan

**No schema change.** Tidak ada perubahan schema maupun data database pada tahap analisis ini. Struktur berikut berasal dari kode dan backup lokal `database/backups/kiucoid_karismaerp_before_stockopname_cleanup_20261103.sql`, bukan pemeriksaan database aktif.

## Struktur yang sudah tersedia

| Tabel | Peran |
| --- | --- |
| tb_master_barang_all | Master identitas barang: kd_barang, nama_barang, p/l/t, dimensi; kolom qrcode dan barcode tersedia pada backup |
| stockopname_master_item | Master opname terpisah: kode_barang, nama_barang, no_lot, expired_date, qty, qty_box, qty_pcs, pending_qty, pending_qty_box, pending_qty_pcs; saat ini juga menyimpan status dan aset QR |
| stockopname_opname | Hasil input: source_id, barang, lot, expired, qty, box, pcs, input_by, input_at, tim_opname, wilayah, input_source |
| stockopname_pending | Detail pending; sinkronisasi model saat ini memakai nama barang dan expired |
| stockopname_master_manual_item / stockopname_manual_input | Antrean/master manual-request serta pencatatan input manual |

## Penyesuaian yang direncanakan

- Pertahankan pemisahan master barang, master opname, dan transaksi hasil; tidak perlu membuat tabel master opname duplikat hanya untuk mengganti konsep QR.
- Gunakan kode barang untuk relasi identitas. Audit duplikasi kode sebelum menambah constraint; backup menunjukkan index kode master barang belum merupakan unique constraint.
- QR barang harus dibedakan dari payload lama `OP|kode|id_master_opname`. Jangan memaknai ulang ID master opname sebagai ID master barang. Putuskan kompatibilitas scan QR lama dan lakukan uji sebelum mengganti label fisik.
- Status generate/batch QR sekarang berada di master opname; pemindahan ke master barang harus mencakup generator, lookup, daftar, retry, reset, preview dan cetak, bukan hanya kolom qrcode.
- `source_id` hasil saat ini mengacu ke master opname. Pertahankan arti tersebut atau migrasikan eksplisit; identitas barang untuk QR tidak otomatis mengganti referensi ini.
- Simpan snapshot isi per box pada hasil bila konversi boleh berubah, supaya audit historis tidak bergantung pada nilai master terbaru. Ini usulan dan belum dibuat.
- Penanganan pending harus diputuskan dahulu. `sync_pending_to_master()` saat ini mengubah qty master dan marker pending sesuai mode tambah/kurang, lalu menaruh total pending pada baris pertama yang memiliki nama dan expired sama. Lot berbeda dapat tercampur.
- Hindari memperlakukan qty, box, dan pcs sebagai tiga angka bebas: setelah aturan isi box disepakati, total harus dihitung di server. Terapkan aturan yang sama pada pending.

## Persiapan migrasi dan rollback

Sebelum migrasi, verifikasi schema aktif, duplikasi kode, referensi source_id, kesesuaian dimensi dengan p/l/t, nilai konversi nol, serta pasangan barang/lot/expired dan marker pending. Buat backup baru beserta jumlah baris dan total qty per tim/wilayah. Migrasi harus mempertahankan ID historis dan tidak menghapus QR lama sebelum strategi kompatibilitas ditetapkan. Siapkan rollback schema/data dan aset QR sebagai satu paket. Belum ada SQL migrasi atau eksekusi yang diklaim pada tahap ini.
