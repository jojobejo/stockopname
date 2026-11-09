<?php
 defined('BASEPATH') or exit('No direct script access allowed');
 $embedded = !empty($embedded);
 $importAction = site_url('admin/stockopname/zahir-csv') . ($embedded ? '?embedded=1' : '');
 $completed = !empty($import_completed);
?>
<?php if (!$embedded): ?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Impor CSV Zahir</title></head><body style="font:16px system-ui;max-width:760px;margin:2rem auto;padding:1rem">
<h1>Impor CSV Zahir</h1><a href="<?= site_url('admin/stockopname/master_barang/catalog') ?>">Kembali ke katalog</a>
<?php endif ?>
<style>
.zahir-import-panel .zahir-file-picker{display:flex;flex-direction:column;align-items:center;justify-content:center;position:relative;border:2px dashed #b6c8df;border-radius:12px;padding:28px 16px;text-align:center;background:#f7faff;cursor:pointer;min-height:160px;margin:16px 0}.zahir-file-picker:focus-within{outline:3px solid #80bdff}.zahir-file-picker input{position:absolute;inset:0;width:100%;height:100%;opacity:0;cursor:pointer}.zahir-file-picker strong{display:block;margin:10px 0 4px;overflow-wrap:anywhere}.zahir-file-picker i{font-size:30px;color:#217346}.zahir-import-panel .zahir-import-submit{width:100%;padding:12px}.zahir-import-panel .zahir-import-current{font-size:13px;color:#6b7280;margin:12px 0}.zahir-import-panel .zahir-import-errors{max-height:220px;overflow:auto}.zahir-import-panel .zahir-result{padding:12px;border-radius:8px;margin-bottom:16px;background:#f3f4f6}
</style>
<div class="zahir-import-panel" data-import-completed="<?= $completed?'1':'0' ?>" data-csrf-name="<?= $this->config->item('csrf_protection') ? html_escape($this->security->get_csrf_token_name()) : '' ?>" data-csrf-hash="<?= $this->config->item('csrf_protection') ? html_escape($this->security->get_csrf_hash()) : '' ?>">
<?php if($message!==''): ?><div class="alert <?= $completed?'alert-success':'alert-danger' ?>" role="status"><?= html_escape($message) ?></div><?php endif ?>
<?php if($completed && $report): ?><div class="zahir-result"><strong><?= (int)$report['valid_count'] ?> baris diproses</strong><span class="small text-muted"> &middot; <?= count($report['new']) ?> barang baru &middot; <?= count($report['updates']) ?> nama diperbarui</span></div><?php endif ?>
<?php if($report && (!empty($report['errors']) || !empty($report['conflicts']))): ?>
<div class="zahir-import-errors mb-3"><table class="table table-sm"><thead><tr><th>Baris / kode</th><th>Perbaikan</th></tr></thead><tbody>
<?php foreach($report['errors'] as $error): ?><tr><td><?= (int)$error['row'] ?></td><td><?= html_escape(implode(' ', $error['errors'])) ?></td></tr><?php endforeach ?>
<?php foreach($report['conflicts'] as $conflict): ?><tr><td><?= html_escape($conflict['code']) ?></td><td><?= html_escape($conflict['reason']) ?></td></tr><?php endforeach ?>
</tbody></table></div><?php endif ?>
<form action="<?= html_escape($importAction) ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="action_token" value="<?= html_escape($action_token) ?>">
<?php if($this->config->item('csrf_protection')): ?><input type="hidden" name="<?= html_escape($this->security->get_csrf_token_name()) ?>" value="<?= html_escape($this->security->get_csrf_hash()) ?>"><?php endif ?>
<input type="hidden" name="operation" value="import">
<label class="zahir-file-picker"><i class="fas fa-file-csv" aria-hidden="true"></i><strong class="zahir-file-name">Pilih file CSV</strong><span class="zahir-file-meta small text-muted">Klik untuk memilih file &middot; Maks. 5 MB</span><input type="file" name="snapshot_file" accept=".csv" aria-label="Pilih file CSV Zahir" required></label>
<p class="small text-muted">Gunakan ekspor lengkap. File ini mengganti katalog aktif; hasil opname tetap.</p>
<button type="submit" class="btn btn-primary zahir-import-submit"><i class="fas fa-upload mr-1" aria-hidden="true"></i> Impor sekarang</button>
</form>
<?php if($active): ?><p class="zahir-import-current">Terakhir diimpor: <?= html_escape($active['source_file']?:'CSV Zahir') ?> &middot; <?= html_escape($active['imported_at']) ?> UTC</p><?php endif ?>
<?php if(!$embedded && $active): ?><details class="mt-4"><summary>Pulihkan impor sebelumnya</summary><form action="<?= html_escape($importAction) ?>" method="post"><input type="hidden" name="operation" value="restore"><input type="hidden" name="expected_snapshot" value="<?= html_escape($active['snapshot_id']) ?>"><input type="hidden" name="action_token" value="<?= html_escape($action_token) ?>">
<?php if($this->config->item('csrf_protection')): ?><input type="hidden" name="<?= html_escape($this->security->get_csrf_token_name()) ?>" value="<?= html_escape($this->security->get_csrf_hash()) ?>"><?php endif ?>
<label><input type="checkbox" name="confirm_restore" value="1" required> Pulihkan data sebelum impor terakhir.</label><button type="submit">Pulihkan</button></form></details><?php endif ?>
</div>
<?php if (!$embedded): ?></body></html><?php endif ?>
