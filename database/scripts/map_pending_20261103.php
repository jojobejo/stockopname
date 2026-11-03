<?php
// CLI only. Default dry-run; --apply performs the approved unique-key mapping.
if (PHP_SAPI !== 'cli') { exit; }
define('BASEPATH', dirname(__DIR__, 2) . '/system/');
define('ENVIRONMENT', 'production');
require dirname(__DIR__, 2) . '/application/config/database.php';
date_default_timezone_set('Asia/Bangkok');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$c = $db['default'];
$conn = new mysqli($c['hostname'], $c['username'], $c['password'], $c['database']);
$conn->set_charset('utf8mb4');
$apply = in_array('--apply', $argv, true);
$conn->begin_transaction();
try {
    $rows = $conn->query('SELECT * FROM stockopname_pending ORDER BY id FOR UPDATE')->fetch_all(MYSQLI_ASSOC);
    $candidate = $conn->prepare("SELECT kode_barang, COALESCE(NULLIF(TRIM(no_lot), ''), '-') AS no_lot, expired_date
        FROM stockopname_master_item WHERE nama_barang = ? AND expired_date = ?
        GROUP BY kode_barang, COALESCE(NULLIF(TRIM(no_lot), ''), '-'), expired_date");
    $plan = []; $unresolved = [];
    foreach ($rows as $row) {
        $candidate->bind_param('ss', $row['nama_barang'], $row['expired_date']);
        $candidate->execute();
        $targets = $candidate->get_result()->fetch_all(MYSQLI_ASSOC);
        if (count($targets) !== 1) {
            $unresolved[] = ['id'=>$row['id'], 'candidate_count'=>count($targets)];
            continue;
        }
        $target = $targets[0];
        if ($target['kode_barang'] !== $row['kode_barang'] || $target['no_lot'] !== $row['no_lot']) {
            $plan[] = ['id'=>$row['id'], 'old_code'=>$row['kode_barang'], 'old_lot'=>$row['no_lot'], 'target'=>$target];
        }
    }
    $summary = ['mode'=>$apply ? 'apply' : 'dry-run', 'pending_rows'=>count($rows), 'will_change'=>count($plan), 'unresolved'=>count($unresolved)];
    if (!$apply) { $conn->rollback(); echo json_encode($summary, JSON_PRETTY_PRINT) . PHP_EOL; exit; }
    $folder = dirname(__DIR__) . '/backups/pending_mapping_' . date('Ymd_His');
    if (!mkdir($folder, 0775, true) && !is_dir($folder)) { throw new RuntimeException('Backup folder gagal dibuat'); }
    $save = static function ($path, $content) {
        if (file_put_contents($path, $content, LOCK_EX) === false) { throw new RuntimeException('Backup gagal ditulis'); }
    };
    $save($folder.'/pending_before.json', json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
    $save($folder.'/mapping_plan.json', json_encode(['plan'=>$plan,'unresolved'=>$unresolved], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
    $changedIds = array_column($plan, 'id');
    $rollback = "-- Restore only mapped rows; review if later business edits exist.\nSTART TRANSACTION;\n";
    foreach ($rows as $row) {
        if (!in_array($row['id'], $changedIds, true)) { continue; }
        $sets = [];
        foreach ($row as $column=>$value) {
            if ($column === 'id') { continue; }
            $sets[] = '`'.str_replace('`','``',$column).'` = '.($value === null ? 'NULL' : "'".$conn->real_escape_string($value)."'");
        }
        $rollback .= 'UPDATE stockopname_pending SET '.implode(', ', $sets).' WHERE id = '.(int)$row['id'].";\n";
    }
    $save($folder.'/rollback.sql', $rollback."COMMIT;\n");
    $before = $conn->query('SELECT COUNT(*) n, SUM(qty) qty, SUM(qty_box) box, SUM(qty_pcs) pcs FROM stockopname_pending')->fetch_assoc();
    $update = $conn->prepare('UPDATE stockopname_pending SET kode_barang = ?, no_lot = ? WHERE id = ? AND kode_barang = ? AND no_lot = ?');
    foreach ($plan as $change) {
        $update->bind_param('ssiss', $change['target']['kode_barang'], $change['target']['no_lot'], $change['id'], $change['old_code'], $change['old_lot']);
        $update->execute();
        if ($update->affected_rows !== 1) { throw new RuntimeException('Jumlah row update tidak sesuai'); }
    }
    $after = $conn->query('SELECT COUNT(*) n, SUM(qty) qty, SUM(qty_box) box, SUM(qty_pcs) pcs FROM stockopname_pending')->fetch_assoc();
    if ($before !== $after) { throw new RuntimeException('Jumlah baris atau total qty berubah'); }
    $summary['totals_before'] = $before;
    $summary['totals_after'] = $after;
    $summary['backup'] = $folder;
    $summary['applied_rows'] = count($plan);
    $save($folder.'/verification.json', json_encode($summary, JSON_PRETTY_PRINT));
    $conn->commit();
    echo json_encode($summary, JSON_PRETTY_PRINT) . PHP_EOL;
} catch (Throwable $e) {
    $conn->rollback();
    fwrite(STDERR, 'Mapping dibatalkan: '.$e->getMessage().PHP_EOL);
    exit(1);
} finally { $conn->close(); }
