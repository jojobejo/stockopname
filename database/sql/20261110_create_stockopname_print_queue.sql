-- Migrasi tabel antrian cetak QR Code per akun pengguna
-- Dibuat: 2026-11-10

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
