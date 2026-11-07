CREATE TABLE IF NOT EXISTS stockopname_catalog_qrcode (
    catalog_id INT NOT NULL,
    expired_date DATE NOT NULL,
    qrcode_value VARCHAR(255) NOT NULL,
    qrcode_file VARCHAR(255) NOT NULL,
    generated_at DATETIME NOT NULL,
    PRIMARY KEY (catalog_id, expired_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
