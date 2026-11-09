-- Only for installations that applied the earlier snapshot migration. Back up first.
ALTER TABLE stockopname_zahir_batch
 MODIFY exported_at VARCHAR(40) DEFAULT NULL,
 MODIFY exported_epoch BIGINT DEFAULT NULL,
 ADD input_format VARCHAR(20) NOT NULL DEFAULT 'legacy-json',
 ADD source_file VARCHAR(255) DEFAULT NULL;
ALTER TABLE stockopname_zahir_qrlotexp
 MODIFY p INT DEFAULT NULL, MODIFY l INT DEFAULT NULL,
 MODIFY t INT DEFAULT NULL, MODIFY dimensi INT DEFAULT NULL;
