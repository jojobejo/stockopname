# Dokumentasi Perubahan Skema Database

Tanggal: 10 November 2026  
Modul: Stock Opname & Antrian Cetak Label QR Code

---

## 1. Ringkasan Perubahan Skema

Pada pembaruan ini, ditambahkan satu tabel baru untuk mendukung penyimpanan antrian cetak label QR Code per pengguna, sedangkan tabel-tabel data master opname lainnya tetap menggunakan skema yang ada.

### Status Skema Tabel
- **Tabel Baru Ditambahkan**: `stockopname_print_queue`
- **Tabel Eksisting Tetap**:
  - `stockopname_master_item` (tetap sama)
  - `tb_master_barang_all` (tetap sama)
  - `stockopname_catalog_qrcode` (tetap sama)
  - `stockopname_catalog_qr_issue` (tetap sama)
  - `tb_karyawan` (tetap sama)

---

## 2. Definisi Tabel `stockopname_print_queue`

Tabel ini digunakan untuk menyimpan antrian cetak label kartu QR Code dari Data Master Barang untuk masing-masing pengguna (Superadmin, Admin, dan Admin1) secara terisolasi.

```sql
CREATE TABLE IF NOT EXISTS `stockopname_print_queue` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL COMMENT 'Username pemilik antrian (superadmin/admin/admin1)',
    `kode_barang` VARCHAR(50) NOT NULL COMMENT 'Kode barang',
    `nama_barang` VARCHAR(255) NOT NULL COMMENT 'Nama barang',
    `expired_date` VARCHAR(20) NOT NULL COMMENT 'Tanggal kadaluarsa barang',
    `copies` INT NOT NULL DEFAULT 1 COMMENT 'Jumlah salinan cetak',
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL,
    INDEX `idx_user` (`username`),
    UNIQUE KEY `uq_user_item` (`username`, `kode_barang`, `expired_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Rincian Kolom:
- `id` (INT, PK): Identifier unik baris antrian.
- `username` (VARCHAR 100): Username pengguna aktif (memisahkan antrian antar pengguna).
- `kode_barang` (VARCHAR 50): Kode barang katalog Zahir.
- `nama_barang` (VARCHAR 255): Nama barang katalog.
- `expired_date` (VARCHAR 20): Tanggal kadaluarsa barang (format YYYY-MM-DD).
- `copies` (INT): Jumlah salinan label yang akan dicetak (minimal 1).
- `created_at` (DATETIME): Waktu barang pertama kali dimasukkan ke antrian.
- `updated_at` (DATETIME): Waktu pembaruan jumlah salinan atau data terakhir.

### Kunci dan Indeks:
- `PRIMARY KEY (`id`)`
- `INDEX `idx_user` (`username`)` untuk mempercepat query load antrian per pengguna.
- `UNIQUE KEY `uq_user_item` (`username`, `kode_barang`, `expired_date`)` untuk mencegah duplikasi item yang sama dalam antrian satu pengguna.

---

## 3. File Migrasi SQL
File migrasi mandiri tersimpan pada:
- `database/sql/20261110_create_stockopname_print_queue.sql`
