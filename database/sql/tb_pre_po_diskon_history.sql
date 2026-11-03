CREATE TABLE IF NOT EXISTS `tb_pre_po_diskon_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kd_po` varchar(255) NOT NULL,
  `id_diskon_source` int(11) DEFAULT NULL,
  `kd_suplier` varchar(35) DEFAULT NULL,
  `no_po` varchar(255) DEFAULT NULL,
  `tgl_transaksi` varchar(25) DEFAULT NULL,
  `nama_suplier` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `nominal` double NOT NULL DEFAULT 0,
  `source_payload` text DEFAULT NULL,
  `synced_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_kd_po` (`kd_po`),
  KEY `idx_id_diskon_source` (`id_diskon_source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
