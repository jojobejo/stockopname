CREATE TABLE `tb_tmp_po_received` (
  `id_tmp_recieved` INT NOT NULL AUTO_INCREMENT,
  `kd_po` VARCHAR(100) NOT NULL,
  `kd_suplier` VARCHAR(100) NOT NULL,
  `kd_barang` VARCHAR(100) NOT NULL,
  `qty_diterima` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
  `satuan` VARCHAR(100) NOT NULL,
  `no_lot` VARCHAR(100) DEFAULT NULL,
  `expired_date` DATE DEFAULT NULL,
  PRIMARY KEY (`id_tmp_recieved`),
  KEY `idx_tmp_po_received_kdpo_barang` (`kd_po`, `kd_barang`),
  KEY `idx_tmp_po_received_supplier` (`kd_suplier`),
  KEY `idx_tmp_po_received_expired` (`expired_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
