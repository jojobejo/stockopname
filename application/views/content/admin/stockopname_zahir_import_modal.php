<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="zahirCsvImportModal" tabindex="-1" role="dialog" aria-labelledby="zahirCsvImportTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="zahirCsvImportTitle"><i class="fas fa-file-upload mr-2" aria-hidden="true"></i>Impor Data Zahir CSV</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="px-3 pt-3 zahir-import-progress d-none" role="status" aria-live="polite">
        <div class="zahir-import-progress-label small mb-2"></div>
        <div class="progress" style="height:8px"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Progres upload CSV" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" style="width:0%"></div></div>
      </div>
      <div class="modal-body zahir-import-modal-body" aria-live="polite"></div>
      <div class="modal-footer"><small class="text-muted mr-auto">Pilih file, lalu impor.</small><button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button></div>
    </div>
  </div>
</div>
<script src="<?= base_url('assets/js/stockopname-zahir-import.js') . '?v=20261110-direct' ?>"></script>
