-- Jalankan setelah memastikan tidak ada hasil opname baru yang memakai kedua master.
START TRANSACTION;
DELETE FROM stockopname_pending WHERE id = 210 AND kd_do = 'DUMMY-OPNAME-20261105' AND kode_barang = 'AALUF01';
DELETE FROM stockopname_master_item WHERE id = 1 AND kode_barang = 'AALUF01' AND input_source = 'DUMMY_TEST_20261105' AND NOT EXISTS (SELECT 1 FROM stockopname_opname WHERE source_id = 1) AND NOT EXISTS (SELECT 1 FROM stockopname_pending WHERE kode_barang = 'AALUF01');
DELETE FROM stockopname_master_item WHERE id = 2 AND kode_barang = 'AAMOL01' AND input_source = 'DUMMY_TEST_20261105' AND NOT EXISTS (SELECT 1 FROM stockopname_opname WHERE source_id = 2) AND NOT EXISTS (SELECT 1 FROM stockopname_pending WHERE kode_barang = 'AAMOL01');
COMMIT;
