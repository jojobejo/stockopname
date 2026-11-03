-- User Management normalization layer for KARISMA ERP / CodeIgniter 3
-- Run once after backup. Existing legacy columns remain compatible.

CREATE TABLE IF NOT EXISTS `tb_jobdesk` (
  `id_jobdesk` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jobdesk` varchar(100) NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jobdesk`),
  UNIQUE KEY `uk_jobdesk_nama` (`nama_jobdesk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tb_akses_level` (
  `id_akses_level` int(11) NOT NULL AUTO_INCREMENT,
  `nama_akses_level` varchar(100) NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_akses_level`),
  UNIQUE KEY `uk_akses_nama` (`nama_akses_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tb_departemen` (
  `id_departemen` int(11) NOT NULL AUTO_INCREMENT,
  `kode_departemen` varchar(30) NOT NULL,
  `nama_departemen` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_departemen`),
  UNIQUE KEY `uk_departemen_kode` (`kode_departemen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tb_tim` (
  `id_tim` int(11) NOT NULL AUTO_INCREMENT,
  `nama_tim` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_tim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tb_wilayah` (
  `id_wilayah` int(11) NOT NULL AUTO_INCREMENT,
  `nama_wilayah` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_wilayah`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tb_menu` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `nama_menu` varchar(120) NOT NULL,
  `icon` varchar(80) NOT NULL DEFAULT 'fas fa-circle',
  `url` varchar(180) NOT NULL DEFAULT '#',
  `urutan` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_menu`),
  KEY `idx_menu_parent` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tb_akses_menu` (
  `id_akses_menu` int(11) NOT NULL AUTO_INCREMENT,
  `akses_lv_id` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT 0,
  `can_add` tinyint(1) NOT NULL DEFAULT 0,
  `can_edit` tinyint(1) NOT NULL DEFAULT 0,
  `can_delete` tinyint(1) NOT NULL DEFAULT 0,
  `can_approve` tinyint(1) NOT NULL DEFAULT 0,
  `can_print` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_akses_menu`),
  UNIQUE KEY `uk_akses_menu` (`akses_lv_id`,`id_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tb_login_log` (
  `id_login_log` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) DEFAULT NULL,
  `nik` varchar(25) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `ip_address` varchar(60) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_login_log`),
  KEY `idx_login_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Compatibility patch untuk tabel yang sudah ada sebelum modul ini dibuat.
-- MySQL/MariaDB lama tidak selalu support ADD COLUMN IF NOT EXISTS,
-- jadi kolom dicek lewat INFORMATION_SCHEMA.
SET @db_name := DATABASE();

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_jobdesk' AND COLUMN_NAME = 'nama_jobdesk') = 0,
  'ALTER TABLE `tb_jobdesk` ADD COLUMN `nama_jobdesk` varchar(100) NOT NULL DEFAULT '''' AFTER `id_jobdesk`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_jobdesk' AND COLUMN_NAME = 'deskripsi') = 0,
  'ALTER TABLE `tb_jobdesk` ADD COLUMN `deskripsi` varchar(255) DEFAULT NULL AFTER `nama_jobdesk`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_jobdesk' AND COLUMN_NAME = 'status') = 0,
  'ALTER TABLE `tb_jobdesk` ADD COLUMN `status` tinyint(1) NOT NULL DEFAULT 1 AFTER `deskripsi`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_akses_level' AND COLUMN_NAME = 'nama_akses_level') = 0,
  'ALTER TABLE `tb_akses_level` ADD COLUMN `nama_akses_level` varchar(100) NOT NULL DEFAULT '''' AFTER `id_akses_level`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_akses_level' AND COLUMN_NAME = 'deskripsi') = 0,
  'ALTER TABLE `tb_akses_level` ADD COLUMN `deskripsi` varchar(255) DEFAULT NULL AFTER `nama_akses_level`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_akses_level' AND COLUMN_NAME = 'status') = 0,
  'ALTER TABLE `tb_akses_level` ADD COLUMN `status` tinyint(1) NOT NULL DEFAULT 1 AFTER `deskripsi`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_akses_menu' AND COLUMN_NAME = 'akses_lv_id') = 0,
  'ALTER TABLE `tb_akses_menu` ADD COLUMN `akses_lv_id` int(11) NOT NULL DEFAULT 0 AFTER `id_akses_menu`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_akses_menu' AND COLUMN_NAME = 'id_menu') = 0,
  'ALTER TABLE `tb_akses_menu` ADD COLUMN `id_menu` int(11) NOT NULL DEFAULT 0 AFTER `akses_lv_id`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_akses_menu' AND COLUMN_NAME = 'menu_id') > 0,
  'UPDATE `tb_akses_menu` SET `id_menu` = `menu_id` WHERE `id_menu` = 0',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_akses_menu' AND COLUMN_NAME = 'can_view') = 0,
  'ALTER TABLE `tb_akses_menu` ADD COLUMN `can_view` tinyint(1) NOT NULL DEFAULT 0 AFTER `id_menu`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_akses_menu' AND COLUMN_NAME = 'can_add') = 0,
  'ALTER TABLE `tb_akses_menu` ADD COLUMN `can_add` tinyint(1) NOT NULL DEFAULT 0 AFTER `can_view`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_akses_menu' AND COLUMN_NAME = 'can_edit') = 0,
  'ALTER TABLE `tb_akses_menu` ADD COLUMN `can_edit` tinyint(1) NOT NULL DEFAULT 0 AFTER `can_add`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_akses_menu' AND COLUMN_NAME = 'can_delete') = 0,
  'ALTER TABLE `tb_akses_menu` ADD COLUMN `can_delete` tinyint(1) NOT NULL DEFAULT 0 AFTER `can_edit`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_akses_menu' AND COLUMN_NAME = 'can_approve') = 0,
  'ALTER TABLE `tb_akses_menu` ADD COLUMN `can_approve` tinyint(1) NOT NULL DEFAULT 0 AFTER `can_delete`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_akses_menu' AND COLUMN_NAME = 'can_print') = 0,
  'ALTER TABLE `tb_akses_menu` ADD COLUMN `can_print` tinyint(1) NOT NULL DEFAULT 0 AFTER `can_approve`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_menu' AND COLUMN_NAME = 'parent_id') = 0,
  'ALTER TABLE `tb_menu` ADD COLUMN `parent_id` int(11) NOT NULL DEFAULT 0 AFTER `id_menu`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_menu' AND COLUMN_NAME = 'nama_menu') = 0,
  'ALTER TABLE `tb_menu` ADD COLUMN `nama_menu` varchar(120) NOT NULL DEFAULT '''' AFTER `parent_id`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_menu' AND COLUMN_NAME = 'icon') = 0,
  'ALTER TABLE `tb_menu` ADD COLUMN `icon` varchar(80) NOT NULL DEFAULT ''fas fa-circle'' AFTER `nama_menu`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_menu' AND COLUMN_NAME = 'url') = 0,
  'ALTER TABLE `tb_menu` ADD COLUMN `url` varchar(180) NOT NULL DEFAULT ''#'' AFTER `icon`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_menu' AND COLUMN_NAME = 'link_menu') > 0,
  'UPDATE `tb_menu` SET `url` = `link_menu` WHERE (`url` = ''#'' OR `url` = '''') AND `link_menu` <> ''''',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_menu' AND COLUMN_NAME = 'urutan') = 0,
  'ALTER TABLE `tb_menu` ADD COLUMN `urutan` int(11) NOT NULL DEFAULT 0 AFTER `url`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_menu' AND COLUMN_NAME = 'status') = 0,
  'ALTER TABLE `tb_menu` ADD COLUMN `status` tinyint(1) NOT NULL DEFAULT 1 AFTER `urutan`',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_jobdesk' AND COLUMN_NAME = 'jobdesk') > 0,
  'UPDATE `tb_jobdesk` SET `nama_jobdesk` = `jobdesk` WHERE `nama_jobdesk` = ''''',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'tb_akses_level' AND COLUMN_NAME = 'akses_lv') > 0,
  'UPDATE `tb_akses_level` SET `nama_akses_level` = `akses_lv` WHERE `nama_akses_level` = ''''',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

ALTER TABLE `tb_karyawan`
  ADD COLUMN IF NOT EXISTS `jobdesk_id` int(11) DEFAULT NULL AFTER `jobdesk`,
  ADD COLUMN IF NOT EXISTS `akses_lv_id` int(11) DEFAULT NULL AFTER `akses_lv`,
  ADD COLUMN IF NOT EXISTS `status` tinyint(1) NOT NULL DEFAULT 1 AFTER `akses_lv_id`,
  ADD COLUMN IF NOT EXISTS `foto` varchar(255) DEFAULT NULL AFTER `status`,
  ADD COLUMN IF NOT EXISTS `last_login` datetime DEFAULT NULL AFTER `foto`;

CREATE OR REPLACE VIEW `v_karyawan` AS
SELECT
  k.*,
  jd.nama_jobdesk,
  al.nama_akses_level
FROM tb_karyawan k
LEFT JOIN tb_jobdesk jd ON jd.id_jobdesk = k.jobdesk_id
LEFT JOIN tb_akses_level al ON al.id_akses_level = k.akses_lv_id;

INSERT IGNORE INTO tb_menu (id_menu,parent_id,nama_menu,icon,url,urutan,status) VALUES
(1,0,'Master Data','fas fa-layer-group','#',10,1),
(2,1,'User Management','fas fa-users','master/user-management',11,1),
(3,1,'Jobdesk','fas fa-briefcase','master/jobdesk',12,1),
(4,1,'Akses Level','fas fa-user-shield','master/akses-level',13,1),
(5,1,'Menu','fas fa-bars','master/menu',14,1);

INSERT IGNORE INTO tb_jobdesk (nama_jobdesk, status)
SELECT DISTINCT jobdesk, 1 FROM tb_karyawan WHERE jobdesk IS NOT NULL AND jobdesk <> '';

INSERT IGNORE INTO tb_akses_level (id_akses_level, nama_akses_level, status)
SELECT DISTINCT CAST(akses_lv AS UNSIGNED), CAST(akses_lv AS CHAR), 1
FROM tb_karyawan
WHERE akses_lv IS NOT NULL AND akses_lv <> '';

INSERT IGNORE INTO tb_akses_menu (akses_lv_id, id_menu, can_view, can_add, can_edit, can_delete, can_approve, can_print)
SELECT 1, id_menu, 1, 1, 1, 1, 1, 1 FROM tb_menu;
