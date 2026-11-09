-- QR issuance audit. Existing QR rows and assets remain intact.
CREATE TABLE stockopname_catalog_qr_issue (
 id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 catalog_id INT NOT NULL,
 expired_date DATE NOT NULL,
 operation VARCHAR(16) NOT NULL,
 actor VARCHAR(128) NOT NULL,
 reason VARCHAR(500) NOT NULL,
 previous_file VARCHAR(255) DEFAULT NULL,
 qrcode_file VARCHAR(255) NOT NULL,
 qrcode_value VARCHAR(255) NOT NULL,
 issued_at DATETIME NOT NULL,
 KEY item_history(catalog_id,expired_date,id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
