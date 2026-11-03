-- Remove seed/sample issues from dashboard_penilaian so the dashboard only
-- aggregates reports submitted through the application.
START TRANSACTION;

CREATE TEMPORARY TABLE tmp_hrd_dummy_issue_ids (
    id BIGINT PRIMARY KEY
);

INSERT INTO tmp_hrd_dummy_issue_ids (id)
SELECT id
FROM tbhrd_environment_issues
WHERE id IN (1, 2, 3, 4, 5)
  AND created_at = '2026-05-07 15:42:18'
  AND description IN (
      'Terdapat tumpahan cairan pestisida di area gudang utama yang berpotensi menyebabkan lantai licin.',
      'Area packing terlihat berdebu dan beberapa sampah kardus belum dibersihkan.',
      'Forklift parkir sembarangan menghalangi jalur evakuasi.',
      'Lampu parkiran belakang mati sebagian.',
      'Kabel LAN dan listrik di ruang HRD tidak tertata rapi.'
  );

DELETE ev
FROM tbhrd_issue_evidences ev
JOIN tmp_hrd_dummy_issue_ids d ON d.id = ev.issue_id;

DELETE lg
FROM tbhrd_issue_logs lg
JOIN tmp_hrd_dummy_issue_ids d ON d.id = lg.issue_id;

DELETE iss
FROM tbhrd_environment_issues iss
JOIN tmp_hrd_dummy_issue_ids d ON d.id = iss.id;

DROP TEMPORARY TABLE tmp_hrd_dummy_issue_ids;

COMMIT;
