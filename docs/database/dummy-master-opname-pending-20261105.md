# Data dummy Master Opname dan pending — 5 November 2026

## Eksekusi database

**Tidak ada perubahan skema.** Pada database lokal `kiucoid_karismaerp`, script `database/scripts/seed_dummy_opname_20261105.php --apply` menambah dua baris `stockopname_master_item` dan satu baris `stockopname_pending`. Master Barang `tb_master_barang_all` hanya dibaca, tidak diubah.

| Barang pada gambar | Kode Master Barang | Expired Master Opname | Lot | Dimensi | Master box + pcs = qty | Pending box + pcs = qty | Qty sistem (mode tambah) |
| --- | --- | --- | --- | ---: | ---: | ---: | ---: |
| Alufos Agro 56 TB 42 X 90 gr* | AALUF01 | 2031-04-01 | - | 42 | 5 + 0 = 210 | 1 + 7 = 49 | 259 |
| Amolin 300 EC 100 X 100 ml | AAMOL01 | 2028-01-01 | - | 100 | 3 + 15 = 315 | 0 | 315 |

Gambar menampilkan tanda `*` pada nama Amolin, sedangkan Master Barang resmi menyimpan nama tanpa tanda tersebut. Seed memakai nama resmi agar relasi dan pencarian konsisten; tanggal expired tetap mengikuti gambar. `stockopname_master_item.id` yang dibuat: 1 dan 2. `stockopname_pending.id`: 210; `kd_do` dan `kd_faktur`: `DUMMY-OPNAME-20261105`.

## Pemeriksaan

- Sebelum insert: kedua kode ada tepat sekali di `tb_master_barang_all`, belum ada baris Master Opname atau pending untuk kode tersebut.
- Sesudah insert: dua baris Master Opname dan satu pending dummy. Pending cocok berdasarkan kode, lot `-`, dan tanggal expired; `unmatched_dummy_pending = 0`.
- Query baca aplikasi `tests/stockopname_read_smoke.php` lulus. Sebanyak 209 pending historis lain masih belum terpetakan dan tidak diubah.
- Script bersifat dry-run tanpa argumen dan menolak insert ulang pada kode atau marker yang sudah digunakan.

## Audit dan rollback

Snapshot sebelum, hasil verifikasi, dan SQL rollback tersimpan di `database/backups/dummy_opname_20261105_20261105_123140/`. SQL rollback menghapus ID dummy spesifik dan menahan penghapusan Master Opname jika sudah dipakai hasil opname atau masih punya pending. Tinjau pemakaian data setelah pengujian sebelum menjalankan rollback; rollback belum dijalankan.
