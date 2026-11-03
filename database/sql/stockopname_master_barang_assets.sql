-- Conditional migration untuk asset Stockopname Master Barang.
-- MySQL/MariaDB lama tidak selalu mendukung ADD COLUMN IF NOT EXISTS.

SET @has_qrcode := (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'tb_master_barang_all'
      AND COLUMN_NAME = 'qrcode'
);

SET @sql_qrcode := IF(
    @has_qrcode = 0,
    'ALTER TABLE `tb_master_barang_all` ADD COLUMN `qrcode` varchar(255) NULL AFTER `kubikasi`',
    'SELECT ''Column qrcode already exists'' AS info'
);
PREPARE stmt_qrcode FROM @sql_qrcode;
EXECUTE stmt_qrcode;
DEALLOCATE PREPARE stmt_qrcode;

SET @has_barcode := (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'tb_master_barang_all'
      AND COLUMN_NAME = 'barcode'
);

SET @sql_barcode := IF(
    @has_barcode = 0,
    'ALTER TABLE `tb_master_barang_all` ADD COLUMN `barcode` varchar(100) NULL AFTER `qrcode`',
    'SELECT ''Column barcode already exists'' AS info'
);
PREPARE stmt_barcode FROM @sql_barcode;
EXECUTE stmt_barcode;
DEALLOCATE PREPARE stmt_barcode;
