# STOCK DATABASE AUDIT - KARISMA ERP

Tanggal audit: 2026-05-22  
Database aktif yang dianalisis: `kiucoid_karismaerp`  
Catatan keamanan: credential database tidak disimpan di dokumen ini.

## Ringkasan Eksekutif

Audit dilakukan dengan query read-only: `SHOW TABLES`, metadata `INFORMATION_SCHEMA`, `SHOW CREATE VIEW`, `SHOW INDEX`, `SELECT COUNT`, dan sample terbatas. Database lokal hanya menampilkan `kiucoid_karismaerp`; database `kiucoid_karismaerp_new` tidak ditemukan pada server lokal saat audit.

Kesimpulan tegas:

1. `tberp_stock_ledger` dan `tberp_stock_batch` sudah ada dan secara desain merupakan kandidat pusat stock modern.
2. Kondisi data saat audit menunjukkan keduanya belum layak langsung dijadikan single source of truth production tanpa audit dan rekonsiliasi.
3. `tberp_stock_batch` sudah menyimpan snapshot saldo cepat per barang, gudang, lot, expired date, dan reserved qty.
4. `tberp_stock_ledger` baru berisi tipe `SALDO_AWAL`, `IN`, `RESERVE`, dan `RELEASE`; belum terlihat posting `OUT`, `RBELI`, `RJUAL`, `MUTASI`, `ADJIN`, `ADJOUT`.
5. Rekonsiliasi batch vs ledger menunjukkan 1.769 kombinasi berbeda dengan total absolut selisih 5.293.879 qty. Ini adalah risiko terbesar.
6. View legacy `v_stock_per_gudang` masih menghitung stock dari `tb_saldo_awal`, `tb_ics_po`, `tb_detail_do`, dan `tb_detail_mutasi`, bukan dari ledger modern.
7. Sebagian besar tabel stock legacy memakai `text` untuk tanggal, lot, nama barang, user, dan status. Ini membuat join lambat, validasi lemah, dan rawan beda format tanggal.
8. Foreign key eksplisit untuk domain stock hampir tidak ada. FK eksplisit yang ditemukan di area stock hanya `tb_gudang_wilayah.id_gudang -> tb_gudang.id_gudang`.
9. Tabel SO/reservation sudah terhubung ke batch modern, tetapi status SO saat sample masih ada yang kosong dan `draft` sudah memiliki reservation aktif. Perlu aturan status yang ketat.
10. Mutasi, retur, dan opname saat ini belum mem-posting ledger modern secara konsisten.

## Tabel Penting Yang Ditemukan

| Tabel/View | Row Count | Tipe | Fungsi bisnis utama | Status rekomendasi |
|---|---:|---|---|---|
| `tberp_stock_batch` | 1.798 | table | Snapshot saldo stock per batch/lot/gudang | Target snapshot cepat, belum source of truth final |
| `tberp_stock_ledger` | 2.296 | table | Jurnal movement stock | Target source of truth setelah rekonsiliasi |
| `tb_master_barang_all` | 5.777 | table | Master barang aktif lintas modul | Master referensi utama |
| `tb_master_barang` | 5.739 | table | Master barang lama/keuangan | Legacy/reference |
| `tb_barangv2` | 5.738 | table | Master barang versi lain, lebih sederhana | Legacy/reference |
| `tb_gudang` | 4 | table | Master gudang | Master referensi |
| `tb_gudang_wilayah` | 19 | table | Wilayah/rak/lokasi dalam gudang | Master referensi |
| `tb_suplier` | 258 | table | Master supplier | Master referensi |
| `tb_satuan` | 23 | table | Master satuan | Master referensi |
| `tb_lpb` | 21 | table | Header penerimaan barang/LPB baru | Transaction source PO receive |
| `tb_lpb_detail` | 26 | table | Detail item LPB baru | Transaction source PO receive |
| `tb_lpb_batch` | 26 | table | Batch/lot LPB baru | Transaction detail batch |
| `tb_po_received` | 4 | table | Penerimaan PO versi lama/sementara | Legacy/staging |
| `tb_tmp_po_received` | 0 | table | Staging penerimaan PO sebelum LPB | Staging |
| `tb_pre_po` | 6.506 | table | Import/pre PO | Transaction reference/staging |
| `tb_po_pending` | 0 | table | Pending PO lama | Legacy/staging |
| `tbso_sales_order` | 6 | table | Header SO modern | Transaction source reservation |
| `tbso_sales_order_detail` | 7 | table | Detail SO modern | Transaction source reservation |
| `tbso_stock_reservation` | 8 | table | Reservation stock kompatibilitas SO | Reservation detail, perlu sinkron batch |
| `tb_pre_do` | 20.148 | table | Import/pre DO dari CSV | Staging utama DO legacy |
| `tb_do` | 304 | table | Header DO | Transaction source keluar |
| `tb_detail_do` | 5.233 | table | Detail DO, stock keluar legacy | Transaction source keluar legacy |
| `tb_tmp_do` | 1 | table | Staging header DO | Staging |
| `tb_tmp_detaildo` | 1 | table | Staging detail DO | Staging |
| `tb_mutasi` | 0 | table | Header mutasi gudang | Transaction source mutasi |
| `tb_detail_mutasi` | 0 | table | Detail mutasi gudang | Transaction source mutasi |
| `tb_tmp_mutasi` | 0 | table | Staging mutasi per user | Staging |
| `tb_log_mutasi` | 0 | table | Log aksi mutasi | Audit log |
| `tb_stock_hold` | 0 | table | Hold stock untuk mutasi/SO/order | Hold/reservation supplement |
| `tb_retur_barang` | 8 | table | Header retur pembelian/penjualan | Transaction source retur |
| `tb_detail_retur_barang` | 21 | table | Detail retur | Transaction detail retur |
| `tb_saldo_awal` | 2.258 | table | Saldo awal legacy per barang/expired/gudang | Legacy migration source |
| `tb_ics` | 2.257 | table | Saldo ICS/buku legacy | Legacy/reporting |
| `tb_ics_po` | 65 | table | Barang masuk ICS/PO legacy | Legacy/reporting/inbound history |
| `tb_ics_do` | 5.703 | table | Barang keluar ICS/DO legacy | Legacy/reporting/outbound history |
| `tb_ics_opname` | 0 | table | Input stock opname ICS legacy | Opname staging |
| `tb_req_opname` | 0 | table | Request opname item/expired date | Opname request staging |
| `stockopname_master` | 670 | table | Snapshot master opname legacy | Legacy/reporting |
| `stockopname_master_box` | 670 | table | Snapshot opname berbasis box | Legacy/reporting |
| `stockopname_opname` | 747 | table | Input opname legacy lain | Opname staging legacy |
| `stockopname_pending` | 31 | table | Pending opname dari DO | Opname support legacy |
| `tb_qty_lot` | 0 | table | Daily stock per lot lama | Legacy/import |
| `tb_dailystock` | 0 | table | Daily stock per gudang lama | Legacy/import |
| `tb_dailystock_global` | 0 | table | Daily stock global lama | Legacy/import |
| `v_stock_per_gudang` | view | view | Saldo per gudang legacy | Reporting legacy, jangan jadi pusat |
| `v_stock_in` | view | view | Inbound legacy dari `tb_ics_po` | Reporting legacy |
| `v_stock_out` | view | view | Outbound legacy dari `tb_detail_do` status 4 | Reporting legacy |
| `v_stock_mutasi_in` | view | view | Mutasi masuk legacy | Reporting legacy |
| `v_stock_mutasi_out` | view | view | Mutasi keluar legacy | Reporting legacy |
| `v_stock_saldo_awal` | view | view | Saldo awal legacy | Reporting legacy |
| `v_show_diff_ics` | view | view | Selisih ICS/opname legacy | Reporting/reconciliation legacy |

## Inventory Schema Per Tabel

### Core Modern Stock

| Tabel | PK | FK eksplisit | Relasi implisit | Kolom penting | Tipe bermasalah | Index | Potensi duplicate/orphan | Status |
|---|---|---|---|---|---|---|---|---|
| `tberp_stock_ledger` | `id` | tidak ada | `kd_barang -> tb_master_barang_all.kd_barang`, `gudang_id -> tb_gudang.id_gudang`, `ref_no/ref_type -> dokumen asal` | `kd_barang`, `gudang_id`, `no_lot`, `expired_date`, `qty`, `tipe`, `ref_no`, `ref_type`, `created_at` | `gudang_id` varchar padahal master int; belum ada `movement_at`, `created_by`, `idempotency_key`, `source_detail_id`, `qty_sign` | hanya PK | 1 orphan barang; tidak ada unique idempotency; ledger belum lengkap untuk OUT/mutasi/retur/opname | Target source of truth setelah dibenahi |
| `tberp_stock_batch` | `id` | tidak ada | `kd_barang -> master`, `gudang_id -> gudang`, `no_lot/expired_date -> batch` | `kd_barang`, `gudang_id`, `no_lot`, `expired_date`, `qty_on_hand`, `qty_reserved`, `created_at`, `update_at` | `gudang_id` varchar; nama kolom `update_at` tidak standar `updated_at`; tidak ada `qty_hold` | PK; unique composite `uniq_batch(kd_barang,gudang_id,no_lot,expired_date)` | duplicate key 0; orphan barang 0; orphan gudang 0; mismatch besar dengan ledger | Snapshot cepat, bukan source of truth final |

Catatan data core:

- Ledger by tipe: `SALDO_AWAL` 2.258 row total 4.021.209; `IN` 26 row total 238.361; `RESERVE` 10 row total 1.300; `RELEASE` 2 row total 150.
- Batch total: `qty_on_hand` 2.185.177; `qty_reserved` 1.150; `qty_available` 2.184.027.
- Batch kosong lot: 0; batch expired null: 0; batch minus: 0; reserved melebihi on hand: 0.
- Rekonsiliasi batch vs ledger on hand: 1.769 key mismatch; total absolut selisih 5.293.879.
- Rekonsiliasi batch reserved vs reservation aktif: 1 key mismatch, `QAGUS01` batch reserved 200 vs active reservation 100.

### Master Data

| Tabel | PK | FK eksplisit | Relasi implisit | Kolom penting | Tipe bermasalah | Index | Potensi duplicate/orphan | Status |
|---|---|---|---|---|---|---|---|---|
| `tb_master_barang_all` | `id` | tidak ada | supplier, gudang, wilayah | `kd_barang`, `kode_barang_system`, `barcode`, `qrcode`, `nama_barang`, `kd_supplier`, `satuan`, `p/l/t`, `berat`, `kubikasi`, `qty_min`, `id_gudang`, `id_wilayah`, `hpp` | `nama_barang` text; `satuan` text; `kubikasi` text; `id_gudang/id_wilayah` banyak 0; supplier code `-` | banyak index duplikatif di `kd_barang`, `barcode`, `qrcode`, `id_gudang,id_wilayah` | duplicate `kd_barang` 0; beberapa lokasi default 0 perlu validasi | Master barang utama |
| `tb_master_barang` | `id` | tidak ada | `kd_suplier -> tb_suplier` | `kd_system`, `kode_barang`, `kd_suplier`, `nm_barang`, dimensi, `qty_min`, status | `nm_barang` varchar, `satuan` text, `kordinat` text | index barang/supplier/nama | duplicate `kode_barang` 27 | Legacy/reference |
| `tb_barangv2` | `id_barang` | tidak ada | kode barang ke master lain | `kode_barang`, `nama_barang`, `nama_suplier`, `produk_fokus` | `nama_barang`, `nama_suplier`, `kelompok`, `bahan_aktif` text | index kode/nama/supplier/fokus | perlu validasi sinkron dengan master utama | Legacy/reference |
| `tb_gudang` | `id_gudang` | tidak ada | dipakai batch, saldo, lpb, mutasi, so | `nama_gudang`, `tipe`, `is_active`, `created_at` | `tipe` enum terbatas `INDUK/ECERAN/EXPIRED`; belum ada kode gudang unik | PK | tidak ada orphan dari batch/ledger/saldo | Master gudang |
| `tb_gudang_wilayah` | `id_wilayah` | `id_gudang -> tb_gudang.id_gudang` | `id_wilayah -> master barang/saldo/opname` | `id_gudang`, `nama_wilayah`, `is_active` | belum ada kode lokasi/rak | PK, index `id_gudang` | semua `tb_saldo_awal.koordinat_id=0`, sehingga 2.258 orphan terhadap wilayah | Master wilayah, perlu mapping ulang |
| `tb_suplier` | `id` | tidak ada | `kd_suplier/kd_supplier` dipakai PO dan master barang | `kd_suplier`, `nama_suplier`, alamat, kontak | nama dan kontak text | index `kd_suplier` non-unique | duplicate `kd_suplier` 1 | Master supplier, perlu unique |
| `tb_satuan` | `id_satuan` | tidak ada | satuan text pada barang/transaksi | `nm_satuan` | `nm_satuan` text | PK | tidak ada FK dari transaksi | Master satuan, perlu kode satuan |

### Purchase, LPB, Barang Masuk

| Tabel | PK | FK eksplisit | Relasi implisit | Kolom penting | Tipe bermasalah | Index | Potensi duplicate/orphan | Status |
|---|---|---|---|---|---|---|---|---|
| `tb_pre_po` | `id_pre_po` | tidak ada | `kd_suplier`, `kd_barang` | `no_po`, `kd_po`, `tgl_transaksi`, `qty`, `hrg_satuan`, `status` | `no_po` text; `tgl_transaksi` text; harga int | PK saja | tidak ada unique dokumen; status int tidak standar | Staging/reference PO |
| `tb_pre_po_invoice_adjustment` | `id` | tidak ada | `kd_po`, `kd_barang` | qty, harga, diskon, tax, source_payload | banyak double; `tgl_transaksi` varchar | unique composite `uniq_kd_po_barang(kd_po,kd_barang)` | perlu validasi jika PO punya lot berbeda | Staging finance/PO |
| `tb_pre_po_diskon_history` | `id` | tidak ada | `kd_po` | diskon history | source payload text | index `kd_po`, source | reporting/audit PO | Audit/support |
| `tb_pre_po_adjustment_log` | `id_log` | tidak ada | `kd_po`, `kd_barang` | harga lama/baru, alasan, user | - | PK | tidak ada FK | Audit/support |
| `tb_tmp_po_received` | `id_tmp_recieved` | tidak ada | `kd_po`, `kd_barang`, `kd_suplier` | qty, satuan, no_lot, expired_date | typo `crete_at`; PK tidak didefinisikan unik di metadata | tidak terlihat index | row 0 | Staging |
| `tb_po_received` | `id_detail_lpb` | tidak ada | `no_po`, `kd_po`, `kd_barang` | qty_diterima, no_lot, exp_date | qty int; belum gudang | index no_po/kd_barang | row 4; legacy penerimaan | Legacy/staging |
| `tb_lpb` | `id_lpb` | tidak ada | `gudang_id -> tb_gudang`, `kd_po/no_po` | `kd_po`, `nosj`, `tgl_sj`, `no_po`, `no_invoice`, `gudang_id`, `input_at` | `nosj` text; tidak ada status posted | PK | detail orphan 0 | Transaction source LPB baru |
| `tb_lpb_detail` | `id_detail_lpb` | tidak ada | `id_lpb -> tb_lpb`, `kd_barang -> master` | `id_lpb`, `kd_barang`, `qty_diterima`, `no_lot`, `expired_date` | tidak ada harga/cost per line; no status | PK | header orphan 0; barang orphan 0 | Source inbound detail |
| `tb_lpb_batch` | `id_batch` | tidak ada | `id_detail_lpb -> tb_lpb_detail` | no_lot, expired_date, qty | redundant dengan `tb_lpb_detail` jika satu detail hanya satu batch | PK | detail orphan 0 | Batch inbound detail |

Catatan flow LPB saat ini:

- `create_lpb_from_tmp()` menulis `tb_lpb`, `tb_lpb_detail`, `tb_lpb_batch`, lalu menaikkan `tberp_stock_batch.qty_on_hand` dan insert ledger `IN`.
- Kode belum menunjukkan row locking pada batch.
- Kode belum memiliki idempotency key agar LPB yang sama tidak double posting.
- Kode menghapus `tb_tmp_po_received` setelah LPB, ini destructive di aplikasi, tapi tidak dijalankan dalam audit.

### Sales Order, DO, Barang Keluar

| Tabel | PK | FK eksplisit | Relasi implisit | Kolom penting | Tipe bermasalah | Index | Potensi duplicate/orphan | Status |
|---|---|---|---|---|---|---|---|---|
| `tbso_sales_order` | `id_so` | tidak ada | customer, gudang | `no_so`, `no_faktur`, `tanggal_transaksi`, `gudang_id`, totals, `status` | `status` varchar bebas; sample ada status kosong; `gudang_id` varchar | index no_so/status/tanggal | no unique `no_so/no_faktur` | Source reservation modern |
| `tbso_sales_order_detail` | `id` | tidak ada | `no_so -> header`, `kd_barang -> master` | qty, qty_box, qty_satuan, expired_date, no_lot, harga, tonase/kubikasi | tidak ada `id_so` padahal beberapa kode membaca `id_so`; relasi header pakai `no_so` | index no_so/kd_barang | header orphan 0 | Detail reservation modern |
| `tbso_stock_reservation` | `id` | tidak ada | `id_so_detail`, batch key, SO | `no_so`, `no_faktur`, `kd_barang`, `exp_date`, `no_lot`, `gudang_id`, `qty_reserved`, `status` | `exp_date` varchar format `dd/mm/YYYY`, status enum hanya active/released | index no_so dan composite barang/exp/gudang | 1 reservation detail orphan; batch orphan 0 | Reservation compatibility |
| `tb_pre_do` | `id` | tidak ada | faktur/customer/barang | import DO, `data_sts`, `barang_sts`, delivery | banyak text tanggal; delivery default `0000-00-00` | banyak index faktur/barang/rute/status | duplicate faktur-item-lot-exp 661 | Staging DO utama |
| `tb_do` | `id` | tidak ada | `kd_do -> tb_detail_do` | `kd_do`, driver, tanggal kirim, status, sales confirm | status int tidak standar; text driver/regional | index `kd_do` non-unique | header orphan dari detail 0 | Header DO |
| `tb_detail_do` | `id` | tidak ada | `kd_do -> tb_do`, `id_pre_do`, `kd_barang -> master` | faktur, customer, barang, qty, no_lot, `tgl_exp`, `status` | `tgl_transaksi`, `tgl_exp`, input_at text; qty int; status int | index faktur/do/customer/barang | duplicate do-faktur-item-lot-exp 223 | Legacy source outbound |
| `tb_tmp_do` | `id` | tidak ada | faktur/do | staging header | - | index faktur | row 1 | Staging |
| `tb_tmp_detaildo` | `id` | tidak ada | do/faktur/barang | staging detail | tanggal text | indexes | row 1 | Staging |
| `tb_pnd_do` | `id` | tidak ada | do/faktur/barang | pending DO lama | tanggal text | indexes | row 0 | Legacy/staging |
| `tb_log_do` | `id_log` | tidak ada | `kd_do` | log do | tanggal/inputer text | indexes | - | Audit log legacy |
| `trashbin_do` | `id` | tidak ada | faktur/barang/customer | arsip delete DO | tanggal text | indexes | row 0 | Trash/legacy |

Catatan flow keluar saat ini:

- DO legacy memakai `tb_detail_do.status = 4` sebagai filter barang keluar pada `v_stock_out`.
- Model `M_Logistik` memiliki method ledger DO draft/finalize yang insert/update ledger `RESERVE` dan `RELEASE`, tetapi belum terlihat posting `OUT` yang mengurangi `qty_on_hand`.
- `DO posted baru mengurangi on hand` belum terjamin di schema/flow saat ini.
- `v_stock_per_gudang` masih menghitung keluar dari `tb_detail_do`, bukan dari ledger modern.

### Mutasi, Hold, Retur

| Tabel | PK | FK eksplisit | Relasi implisit | Kolom penting | Tipe bermasalah | Index | Potensi duplicate/orphan | Status |
|---|---|---|---|---|---|---|---|---|
| `tb_mutasi` | `id` | tidak ada | gudang asal/tujuan | `noreff`, `tgl_transaksi`, `gudang_asal`, `gudang_mutasi`, `status` | `tgl_transaksi`, input_at text; status enum belum sejalan dengan enterprise | PK saja | row 0 | Source mutasi |
| `tb_detail_mutasi` | `id` | tidak ada | `noreff -> tb_mutasi`, gudang, barang | asal, tujuan, kode barang, exp_date, qty, satuan | exp_date text; kode barang dobel system/zahir | index mutasi in/out | row 0 | Source detail mutasi |
| `tb_tmp_mutasi` | `id` | tidak ada | user, barang by nama | nama_barang, exp_date, qty, satuan_id | barang dipilih via nama text | index nama/exp/user | row 0 | Staging mutasi |
| `tb_stock_hold` | `id` | tidak ada | `noref -> tb_mutasi/no_so/order`, gudang, barang | source, status, qty, exp_date, released_at | exp_date text; status enum `HOLD/RELEASE/CANCEL` tetapi kode update `RELEASED` | PK | row 0 | Hold stock, perlu disatukan dengan reservation |
| `tb_log_mutasi` | `id` | tidak ada | `noreff` | aksi, keterangan, user, created_at | user varchar bebas | PK | row 0 | Audit log |
| `tb_retur_barang` | `id` | tidak ada | `kd_retur` ke detail | type_retur, status, input_by, input_at | type/status int tanpa lookup | PK | row 8 | Header retur |
| `tb_detail_retur_barang` | `id` | tidak ada | `kd_retur`, faktur, barang | retur_type, kd_faktur, kd_barang, no_lot, tgl_expired, qty, status_data | no_lot/text, tgl_expired text, status int | PK | header orphan 0 | Detail retur |

Catatan flow mutasi/retur:

- Mutasi non-hold saat ini hanya insert header/detail dan log. Belum mengurangi batch asal, belum menambah batch tujuan, dan belum insert ledger `MUTASI_OUT/MUTASI_IN` atau tipe setara.
- Mutasi ke gudang 10 dianggap HOLD dan masuk `tb_stock_hold`, tetapi tetap insert `tb_detail_mutasi`. Harus ditegaskan bahwa HOLD tidak mengubah stock final.
- Retur hanya menyimpan header/detail dan update status detail. Belum ada posting ledger/batch untuk `RBELI` atau `RJUAL`.

### Saldo Awal, ICS, Opname, Reporting Legacy

| Tabel/View | PK | FK eksplisit | Relasi implisit | Kolom penting | Tipe bermasalah | Index | Potensi duplicate/orphan | Status |
|---|---|---|---|---|---|---|---|---|
| `tb_saldo_awal` | `id` | tidak ada | barang, gudang/wilayah | `kode_barang_zahir`, `wilayah_id`, `koordinat_id`, `qty`, `nolot`, `exp_date`, `noreff` | exp_date text; `koordinat_id` semua 0; qty decimal(10,0) | composite wilayah/barang/exp | 1 barang orphan; 2.258 wilayah orphan; duplicate natural key 54 | Migration source, bukan source of truth final |
| `tb_ics` | `id` | tidak ada | barang by nama/kd_system | saldo buku ICS | exp_date/input_at text | index nama/kd_system | duplicate key 53 | Legacy/reporting |
| `tb_ics_po` | `id` | tidak ada | LPB/faktur/barang | barang masuk legacy | tanggal/exp text | index faktur/nama/exp | row 65 | Legacy inbound reporting |
| `tb_ics_do` | `id` | tidak ada | DO/faktur/barang | barang keluar legacy | tanggal/exp/input text | index faktur/nama/exp | row 5.703 | Legacy outbound reporting |
| `tb_ics_opname` | `id` | tidak ada | barang/exp/user/tim/wilayah | hasil input opname | semua dimensi non-key banyak text | index kd/nama/exp | row 0 | Opname staging legacy |
| `tb_req_opname` | `id` | tidak ada | request item/exp/wilayah | request input expired tidak ada | text date/user/status int | PK | row 0 | Opname request staging |
| `tb_log_ics` | `id` | tidak ada | barang/user/opname | log aksi ICS/opname | banyak text | index nama | row 1.337 | Audit legacy |
| `tb_ics_supp` | `id` | tidak ada | barang/exp | qty support legacy | exp_date text | PK | row 0 | Legacy support |
| `stockopname_master` | `id` | tidak ada | barang/lot/exp | snapshot master opname | expired/no_lot/nama text | index text columns | row 670 | Legacy snapshot |
| `stockopname_master_box` | `id` | tidak ada | barang/lot/exp | snapshot box opname | expired/no_lot/nama text | index text columns | row 670 | Legacy snapshot |
| `stockopname_opname` | `id` | tidak ada | barang/lot/exp/user/wilayah | input fisik opname legacy | expired/nolot/user/input_at text | index text columns | row 747 | Opname staging legacy |
| `stockopname_pending` | `id` | tidak ada | DO/faktur/barang | pending opname dari DO | tanggal/exp/nama text | index faktur/nama/exp | row 31 | Opname support |
| `tb_qty_lot` | `id` | tidak ada | barang/supplier/lot | import daily lot | exp_date/gudang/unit text | indexes | row 0 | Legacy/import |
| `tb_dailystock` | `id` | tidak ada | barang/supplier/gudang | import daily stock | gudang text | PK | row 0 | Legacy/import |
| `tb_dailystock_global` | `id` | tidak ada | barang/supplier | import global stock | gudang text | supplier/barang index | row 0 | Legacy/import |
| `v_stock_per_gudang` | view | - | saldo awal + in - out + mutasi | kode_barang, nama_barang, exp_date, gudang, qty | exp_date text dari legacy | - | rawan double counting | Reporting legacy |
| `v_show_diff_ics` | view | - | saldo awal, PO, DO, opname | qty sistem/fisik/selisih | parsing tanggal campuran | - | rekonsiliasi legacy | Reporting/reconciliation legacy |

## Relasi Antar Tabel

Relasi eksplisit:

- `tb_gudang_wilayah.id_gudang -> tb_gudang.id_gudang`

Relasi implisit yang wajib distandarkan:

| Dari | Ke | Kolom | Catatan |
|---|---|---|---|
| `tberp_stock_batch` | `tb_master_barang_all` | `kd_barang` | aman saat audit, orphan 0 |
| `tberp_stock_batch` | `tb_gudang` | `gudang_id` | tipe varchar vs int |
| `tberp_stock_ledger` | `tb_master_barang_all` | `kd_barang` | 1 orphan |
| `tberp_stock_ledger` | `tb_gudang` | `gudang_id` | orphan 0 |
| `tb_lpb_detail` | `tb_lpb` | `id_lpb` | orphan 0 |
| `tb_lpb_batch` | `tb_lpb_detail` | `id_detail_lpb` | orphan 0 |
| `tbso_sales_order_detail` | `tbso_sales_order` | `no_so` | orphan 0 |
| `tbso_stock_reservation` | `tbso_sales_order_detail` | `id_so_detail` | 1 orphan |
| `tbso_stock_reservation` | `tberp_stock_batch` | barang, gudang, lot, exp | orphan 0 jika exp diparse `dd/mm/YYYY` |
| `tb_detail_do` | `tb_do` | `kd_do` | orphan 0 |
| `tb_detail_do` | `tb_master_barang_all` | `kd_barang` | orphan 0 |
| `tb_detail_retur_barang` | `tb_retur_barang` | `kd_retur` | orphan 0 |
| `tb_saldo_awal` | `tb_master_barang_all` | `kode_barang_zahir -> kd_barang` | 1 orphan |
| `tb_saldo_awal` | `tb_gudang` | `wilayah_id -> id_gudang` | orphan 0, nama kolom menyesatkan |
| `tb_saldo_awal` | `tb_gudang_wilayah` | `koordinat_id -> id_wilayah` | 2.258 orphan karena semua 0 |

## Temuan Duplicate, Orphan, Dan Kualitas Data

| Metric | Nilai | Risiko |
|---|---:|---|
| Batch duplicate natural key | 0 | baik |
| Master `tb_master_barang_all.kd_barang` duplicate | 0 | baik |
| Master `tb_master_barang.kode_barang` duplicate | 27 | rawan join ganda pada legacy |
| Supplier duplicate `kd_suplier` | 1 | rawan salah supplier |
| Saldo awal duplicate natural key | 54 | rawan double counting |
| ICS duplicate key | 53 | rawan double counting opname/ICS |
| Pre DO duplicate faktur-barang-lot-exp | 661 | perlu validasi, bisa sah jika split line, bisa double import |
| Detail DO duplicate DO-faktur-barang-lot-exp | 223 | perlu validasi, bisa split line, bisa double out |
| Ledger barang orphan | 1 | ledger tidak sepenuhnya valid |
| Saldo awal barang orphan | 1 | migrasi saldo perlu cleanup |
| Saldo awal wilayah orphan | 2.258 | `koordinat_id` belum dimapping |
| Reservation detail orphan | 1 | reservation tidak konsisten dengan detail SO |
| Batch vs ledger mismatch rows | 1.769 | critical |
| Batch vs ledger total absolute diff | 5.293.879 | critical |
| Reserved mismatch batch vs reservation aktif | 1 | medium |

## Kelemahan Schema Utama

1. Tidak ada foreign key eksplisit pada mayoritas transaksi stock.
2. Banyak tanggal disimpan sebagai `text`: `tb_saldo_awal.exp_date`, `tb_ics.exp_date`, `tb_ics_do.exp_date`, `tb_detail_do.tgl_exp`, `tb_pre_do.tgl_exp`, dan field input tanggal lain.
3. Format tanggal campuran: modern memakai `date` (`YYYY-MM-DD`), legacy sample memakai `d/m/YYYY`, reservation menyimpan `dd/mm/YYYY`.
4. Status transaksi tidak standar: SO varchar bebas, DO int, retur int, pre_po int, mutasi enum berbeda.
5. Ledger belum punya idempotency key dan belum punya constraint unik per source document/detail/movement.
6. Batch belum punya `qty_hold`, `last_ledger_id`, `updated_by`, dan tidak ada mekanisme row lock tersirat di schema.
7. View legacy masih melakukan join berbasis nama barang/expired date text pada beberapa bagian.
8. `v_stock_per_gudang` rawan double counting karena menggabungkan saldo awal, PO legacy, DO legacy, dan mutasi tanpa dokumen status standar.
9. Tabel master barang terpecah menjadi beberapa versi, berpotensi beda kode/nama/dimensi.
10. Tidak ada standar lokasi: `gudang_id`, `wilayah_id`, dan `koordinat_id` dipakai tidak konsisten.

## Rekomendasi Perbaikan Schema

Prioritas tinggi:

1. Tetapkan `tberp_stock_ledger` sebagai source of truth target setelah rekonsiliasi.
2. Tambahkan kolom ledger: `movement_at`, `source_table`, `source_id`, `source_detail_id`, `direction`, `qty_in`, `qty_out`, `created_by`, `posted_by`, `idempotency_key`, `reversal_of_ledger_id`, `remark`.
3. Tambahkan unique key ledger untuk idempotency, misalnya `(source_table, source_detail_id, tipe, kd_barang, gudang_id, no_lot, expired_date)`.
4. Tambahkan index ledger: `(kd_barang,gudang_id,no_lot,expired_date,created_at)`, `(ref_type,ref_no)`, `(tipe,created_at)`.
5. Tambahkan batch columns: `qty_hold`, `last_ledger_id`, `updated_by`, `version`.
6. Standarkan `gudang_id` menjadi int pada batch/ledger/reservation, atau gunakan varchar kode gudang terpisah jika memang butuh kode.
7. Normalisasi tanggal legacy ke kolom date baru: `expired_date_norm`, `transaction_date_norm`, lalu migrasi bertahap.
8. Tambahkan FK rekomendasi setelah cleanup data, bukan sebelum cleanup.
9. Tambahkan status dokumen standar: `DRAFT`, `SUBMITTED`, `APPROVED`, `POSTED`, `CANCELLED`, `REVERSED`.
10. Pecah movement mutasi menjadi tipe eksplisit `MUTASI_OUT` dan `MUTASI_IN`, atau gunakan `tipe='MUTASI'` dengan `direction`.

Foreign key yang disarankan setelah cleanup:

| Tabel | Kolom | Referensi |
|---|---|---|
| `tberp_stock_batch` | `kd_barang` | `tb_master_barang_all.kd_barang` |
| `tberp_stock_batch` | `gudang_id` | `tb_gudang.id_gudang` |
| `tberp_stock_ledger` | `kd_barang` | `tb_master_barang_all.kd_barang` |
| `tberp_stock_ledger` | `gudang_id` | `tb_gudang.id_gudang` |
| `tb_lpb` | `gudang_id` | `tb_gudang.id_gudang` |
| `tb_lpb_detail` | `id_lpb` | `tb_lpb.id_lpb` |
| `tb_lpb_detail` | `kd_barang` | `tb_master_barang_all.kd_barang` |
| `tb_lpb_batch` | `id_detail_lpb` | `tb_lpb_detail.id_detail_lpb` |
| `tbso_sales_order` | `gudang_id` | `tb_gudang.id_gudang` |
| `tbso_sales_order_detail` | `kd_barang` | `tb_master_barang_all.kd_barang` |
| `tbso_stock_reservation` | `id_so_detail` | `tbso_sales_order_detail.id` |
| `tb_detail_do` | `kd_do` | `tb_do.kd_do` setelah `kd_do` dibuat unique |
| `tb_detail_do` | `kd_barang` | `tb_master_barang_all.kd_barang` |
| `tb_gudang_wilayah` | `id_gudang` | sudah ada |

## Status Source Of Truth

Keputusan audit:

- Source of truth target: `tberp_stock_ledger`.
- Snapshot cepat target: `tberp_stock_batch`.
- Source migrasi awal: `tb_saldo_awal`, `tb_lpb*`, `tb_detail_do`, `tbso_*`, `tb_mutasi*`, `tb_retur*`, `tb_ics*`, `stockopname_*`.
- Reporting legacy: `v_stock_per_gudang`, `v_stock_in`, `v_stock_out`, `v_stock_mutasi_in`, `v_stock_mutasi_out`, `v_stock_saldo_awal`, `v_show_diff_ics`.

`tberp_stock_ledger` belum layak menjadi pusat stock saat ini karena:

1. Belum mencakup semua tipe movement aktual.
2. Belum ada OUT dari DO posted.
3. Belum ada mutasi masuk/keluar.
4. Belum ada retur beli/jual yang mempengaruhi stock.
5. Belum ada adjustment opname.
6. Belum ada idempotency.
7. Hasil batch vs ledger tidak reconcile.

`tberp_stock_batch` belum layak sebagai pusat stock karena:

1. Secara prinsip batch adalah snapshot, bukan jurnal audit.
2. Snapshot bisa salah jika update parsial gagal.
3. Ada mismatch besar terhadap ledger.
4. Belum punya `qty_hold`.
5. Belum punya audit trail untuk perubahan langsung.

Risiko jika view legacy tetap dipakai sebagai sumber utama:

1. Double counting dari saldo awal + PO + DO jika data sudah masuk ledger.
2. Join berbasis text date dan nama barang dapat gagal diam-diam.
3. Mutasi HOLD bisa terlihat sebagai stock movement final.
4. Reservation modern tidak masuk hitungan available legacy.
5. Tanggal `d/m/YYYY` vs `YYYY-MM-DD` membuat expired dan FEFO salah.
6. Data lama tanpa status posting bisa ikut terhitung.
7. Tidak audit-ready karena tidak ada satu jurnal immutable.

## Query Audit Contoh

Contoh query yang aman untuk audit lanjutan:

```sql
SELECT tipe, COUNT(*) AS cnt, SUM(qty) AS total_qty
FROM tberp_stock_ledger
GROUP BY tipe;
```

```sql
SELECT
    COUNT(*) AS total_batch,
    SUM(qty_on_hand) AS qty_on_hand,
    SUM(qty_reserved) AS qty_reserved,
    SUM(qty_on_hand - COALESCE(qty_reserved, 0)) AS qty_available
FROM tberp_stock_batch;
```

```sql
SELECT
    b.kd_barang,
    b.gudang_id,
    b.no_lot,
    b.expired_date,
    b.qty_on_hand,
    b.qty_reserved
FROM tberp_stock_batch b
WHERE b.qty_on_hand < 0
   OR COALESCE(b.qty_reserved, 0) > COALESCE(b.qty_on_hand, 0)
LIMIT 20;
```

```sql
SELECT r.*
FROM tbso_stock_reservation r
LEFT JOIN tberp_stock_batch b
    ON b.kd_barang = r.kd_barang
   AND b.gudang_id = r.gudang_id
   AND COALESCE(b.no_lot, '') = COALESCE(r.no_lot, '')
   AND b.expired_date = STR_TO_DATE(r.exp_date, '%d/%m/%Y')
WHERE r.status = 'active'
  AND b.id IS NULL;
```

