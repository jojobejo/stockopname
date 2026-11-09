-- Development migration - verify live target and back up before execution.
CREATE TABLE stockopname_zahir_batch (
 id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 snapshot_id CHAR(36) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
 checksum CHAR(64) CHARACTER SET ascii NOT NULL,
 source_instance VARCHAR(128) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
 scope VARCHAR(128) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
 exported_at VARCHAR(40) DEFAULT NULL,
 exported_epoch BIGINT DEFAULT NULL,
 imported_at DATETIME NOT NULL,
 actor VARCHAR(128) NOT NULL,
 input_format VARCHAR(20) NOT NULL DEFAULT 'csv-v1',
 source_file VARCHAR(255) DEFAULT NULL,
 row_count INT UNSIGNED NOT NULL,
 master_changes LONGTEXT NOT NULL,
 previous_batch_id BIGINT UNSIGNED DEFAULT NULL,
 UNIQUE KEY snapshot_identity(snapshot_id), UNIQUE KEY file_identity(checksum)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE stockopname_zahir_active (
 source_instance VARCHAR(128) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
 scope VARCHAR(128) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
 batch_id BIGINT UNSIGNED NOT NULL,
 PRIMARY KEY(source_instance,scope)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE stockopname_zahir_qrlotexp (
 id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 batch_id BIGINT UNSIGNED NOT NULL,
 source_row INT UNSIGNED NOT NULL,
 kode_barang VARCHAR(25) NOT NULL,
 master_id INT NOT NULL,
 nama_sumber TEXT NOT NULL,
 nama_normalized TEXT NOT NULL,
 expired_date DATE NOT NULL,
 qty_lot DECIMAL(24,6) NOT NULL,
 p INT DEFAULT NULL, l INT DEFAULT NULL, t INT DEFAULT NULL, dimensi INT DEFAULT NULL,
 UNIQUE KEY batch_grain(batch_id,kode_barang,expired_date),
 KEY local_master(master_id), KEY batch_rows(batch_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE stockopname_zahir_audit (
 id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 occurred_at DATETIME NOT NULL,
 actor VARCHAR(128) NOT NULL,
 checksum CHAR(64) NOT NULL,
 snapshot_id VARCHAR(36) NOT NULL,
 source_instance VARCHAR(128) NOT NULL,
 scope VARCHAR(128) NOT NULL,
 result VARCHAR(32) NOT NULL,
 detail LONGTEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
