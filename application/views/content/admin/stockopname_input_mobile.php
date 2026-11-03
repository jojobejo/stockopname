<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="Karisma Logo" height="150" width="300">
    </div>

    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>

    <div class="content-wrapper so-input-page">
        <section class="content">
            <div class="container-fluid py-3 pb-4">
                <style>
                    .so-input-page{background:#eef3f8}.so-mobile-shell{max-width:460px;margin:0 auto}.so-panel{background:#fff;border:1px solid #dce5ee;border-radius:8px;box-shadow:0 8px 22px rgba(15,23,42,.07)}
                    .so-panel-header{padding:14px 16px;border-bottom:1px solid #e6edf4;display:flex;align-items:center;justify-content:space-between;gap:10px}.so-panel-title{font-size:16px;font-weight:800;color:#172033;margin:0}.so-scan-status{font-size:12px;color:#64748b}
                    .so-user-header{display:flex;align-items:center;gap:12px}.so-user-avatar{width:44px;height:44px;flex:0 0 44px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#e0edff;color:#1d4ed8;font-size:18px}.so-user-name{font-size:15px;font-weight:800;color:#172033;line-height:1.25}.so-user-meta{display:flex;flex-wrap:wrap;gap:6px 12px;margin-top:4px;font-size:12px;color:#64748b}.so-user-meta span{display:inline-flex;align-items:center;gap:5px}.so-user-actions{display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:0 16px 16px}.so-user-actions .btn{font-weight:800}
                    .so-scan-box{background:#0f172a;border-radius:8px;overflow:hidden;min-height:260px;display:none}.so-scan-box video{object-fit:cover}.so-info-grid{display:grid;gap:10px}.so-readonly{background:#f8fafc;border:1px solid #dbe4ef;border-radius:8px;padding:10px 12px}.so-readonly label{display:block;margin:0 0 3px;color:#64748b;font-size:11px;font-weight:800;text-transform:uppercase}.so-readonly div{font-size:14px;color:#111827;font-weight:700;word-break:break-word}
                    .so-qty-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}.so-qty-field label{font-size:12px;font-weight:800;color:#334155}.so-qty-field .input-group-text{border-radius:0 8px 8px 0;background:#f8fafc;font-size:12px;font-weight:800;color:#64748b}.so-qty-field .form-control{border-radius:8px 0 0 8px}.so-action-bar{position:sticky;bottom:0;background:linear-gradient(180deg,rgba(238,243,248,0),#eef3f8 28%);padding:16px 0 4px}.so-action-bar .btn{height:46px;font-weight:800}.so-back-btn{width:36px;height:32px;display:inline-flex;align-items:center;justify-content:center}.so-back-btn i,.so-icon-btn i{margin-right:0}.so-icon-btn{height:44px;font-weight:800}.btn i{margin-right:6px}.form-control{border-radius:8px}.so-muted{font-size:12px;color:#64748b}.so-mode-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}.so-mode-grid .btn{height:44px;font-weight:800}.so-mode-grid .so-mode-wide{grid-column:1/-1}.so-manual-form,.so-request-form{display:none}.so-manual-form label,.so-request-form label{font-size:12px;font-weight:800;color:#334155}.select2-container--bootstrap4 .select2-selection{border-radius:8px;min-height:38px}.select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered{line-height:36px}
                    @media(min-width:768px){.so-info-grid{grid-template-columns:1fr 1fr}.so-readonly.full{grid-column:1/-1}.so-scan-box{min-height:320px}}
                    @media(max-width:420px){.content-header h1{font-size:21px}.so-panel-header{padding:12px}.so-panel .p-3{padding:12px!important}.so-qty-grid{gap:8px}}
                </style>

                <div class="so-mobile-shell">
                    <div class="so-panel mb-3">
                        <div class="p-3 so-user-header">
                            <div class="so-user-avatar"><i class="fas fa-user"></i></div>
                            <div class="flex-grow-1">
                                <div class="so-user-name"><?= html_escape($this->session->userdata('nama') ?: '-') ?></div>
                                <div class="so-user-meta">
                                    <span><i class="fas fa-users"></i> Tim <?= html_escape($this->session->userdata('tim') ?: '-') ?></span>
                                    <span><i class="fas fa-map-marker-alt"></i> Wilayah <?= html_escape($nama_wilayah ?? '-') ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="so-user-actions">
                            <a href="<?= base_url('stockopname/history-input') ?>" class="btn btn-outline-primary">
                                <i class="fas fa-history"></i>Histori Input
                            </a>
                            <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger">
                                <i class="fas fa-sign-out-alt"></i>Logout
                            </a>
                        </div>
                    </div>

                    <div class="so-panel mb-3">
                        <div class="p-3">
                            <div class="so-mode-grid">
                                <button type="button" class="btn btn-primary so-icon-btn" id="btnScan">
                                    <i class="fas fa-qrcode"></i>Scan
                                </button>
                                <button type="button" class="btn btn-outline-primary so-icon-btn" id="btnManualMode">
                                    <i class="fas fa-keyboard"></i>Input Manual
                                </button>
                                <button type="button" class="btn btn-outline-warning so-icon-btn so-mode-wide" id="btnRequestMode">
                                    <i class="fas fa-clipboard-list"></i>Opname Request
                                </button>
                            </div>
                            <div class="so-muted mt-2" id="scanStatus"></div>
                            <div class="so-scan-box mt-3" id="qrReader"></div>
                            <input type="hidden" id="manualScanValue">
                        </div>
                    </div>

                    <form id="formInputOpname">
                        <input type="hidden" name="input_mode" id="inputMode" value="scan">
                        <input type="hidden" name="master_id" id="masterId">
                        <input type="hidden" name="manual_source_id" id="manualSourceId">
                        <div class="so-panel mb-3">
                            <div class="so-panel-header">
                                <h2 class="so-panel-title">Kartu Stock</h2>
                            </div>
                            <div class="p-3">
                                <div class="so-info-grid" id="scanStockView">
                                    <div class="so-readonly full">
                                        <label>Nama Barang</label>
                                        <div id="itemName">-</div>
                                    </div>
                                    <div class="so-readonly full">
                                        <label>Expired Date</label>
                                        <div id="itemExpired">-</div>
                                    </div>
                                </div>
                                <div class="so-manual-form" id="manualStockView">
                                    <div class="form-group">
                                        <label>Nama Barang</label>
                                        <select class="form-control" id="manualBarang" name="manual_kode_barang"></select>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Expired Date</label>
                                        <select class="form-control" id="manualExpired" name="manual_expired_id" disabled>
                                            <option value="">Pilih nama barang dahulu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="so-request-form" id="requestStockView">
                                    <div class="form-group">
                                        <label>Nama Barang</label>
                                        <select class="form-control" id="requestBarang" name="request_kode_barang"></select>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Expired Date</label>
                                        <input type="text" class="form-control" id="requestExpiredView" name="request_expired_date" placeholder="dd/mm/yyyy" inputmode="numeric" maxlength="10">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="so-panel">
                            <div class="so-panel-header">
                                <h2 class="so-panel-title">Qty Opname</h2>
                                <span class="so-muted" id="qtyTotalLabel">Total 0</span>
                            </div>
                            <div class="p-3">
                                <div class="so-qty-grid">
                                    <div class="form-group so-qty-field mb-0">
                                        <label>Qty Box</label>
                                        <div class="input-group input-group-lg">
                                            <input type="number" class="form-control" name="qty_box" id="qtyBox" min="0" step="1" placeholder="0" inputmode="numeric">
                                            <div class="input-group-append">
                                                <span class="input-group-text">BOX</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group so-qty-field mb-0">
                                        <label>Qty Pcs</label>
                                        <div class="input-group input-group-lg">
                                            <input type="number" class="form-control" name="qty_pcs" id="qtyPcs" min="0" step="1" placeholder="0" inputmode="numeric">
                                            <div class="input-group-append">
                                                <span class="input-group-text">PCS</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group so-qty-field mb-0 d-none" hidden style="display:none!important">
                                        <label>Dimensi</label>
                                        <div class="so-dimensi-value" id="itemDimensi">0</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="so-action-bar">
                            <button type="submit" class="btn btn-success btn-block" id="btnSaveOpname" disabled>
                                <i class="fas fa-save"></i>Input Opname
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0
        </div>
    </footer>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
window.addEventListener('load', function () {
    var scanner = null;
    var scanning = false;
    var selectedDimensi = 0;
    var inputMode = 'scan';

    function toast(icon, title) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({toast:true,position:'top-end',icon:icon,title:title,showConfirmButton:false,timer:2400});
            return;
        }
        alert(title);
    }

    function escapeHtml(value) {
        return String(value === null || value === undefined ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function ajaxMessage(xhr, fallback) {
        if (xhr.responseJSON && xhr.responseJSON.message) return xhr.responseJSON.message;
        if (xhr.responseText) {
            try {
                var parsed = JSON.parse(xhr.responseText);
                if (parsed.message) return parsed.message;
            } catch (e) {}
        }
        return fallback;
    }

    function setScannerStatus(text) {
        $('#scanStatus').text(text);
    }

    function stopScanner() {
        if (!scanner || !scanning) return $.Deferred().resolve().promise();
        return scanner.stop().then(function () {
            scanning = false;
            $('#qrReader').hide();
            $('#btnScan').prop('disabled', false).html('<i class="fas fa-qrcode"></i>Scan');
            setScannerStatus('');
        }).catch(function () {
            scanning = false;
            $('#qrReader').hide();
            $('#btnScan').prop('disabled', false).html('<i class="fas fa-qrcode"></i>Scan');
        });
    }

    function fillItem(row) {
        $('#masterId').val(row.id || '');
        $('#manualSourceId').val('');
        $('#itemName').html(escapeHtml(row.nama_barang || '-'));
        $('#itemExpired').text(formatExpiredDate(row.expired_date));
        selectedDimensi = parseInt(row.dimensi || 0, 10) || 0;
        updateDimensiView();
        updateQtyTotal();
        $('#btnSaveOpname').prop('disabled', false);
    }

    function resetAfterSave() {
        $('#formInputOpname')[0].reset();
        $('#masterId').val('');
        $('#manualSourceId').val('');
        $('#itemName,#itemExpired').text('-');
        $('#manualBarang').val(null).trigger('change');
        $('#requestBarang').val(null).trigger('change');
        $('#requestExpiredView').val('');
        resetManualLot('Pilih nama barang dahulu');
        resetManualExpired('Pilih no lot dahulu');
        selectedDimensi = 0;
        updateDimensiView();
        $('#qtyTotalLabel').text('Total 0');
        $('#btnSaveOpname').prop('disabled', true);
        setInputMode(inputMode);
    }

    function updateDimensiView() {
        $('#itemDimensi').text((selectedDimensi || 0).toLocaleString('id-ID'));
    }

    function updateQtyTotal() {
        var pcs = parseInt($('#qtyPcs').val() || 0, 10) || 0;
        var box = parseInt($('#qtyBox').val() || 0, 10) || 0;
        var total = (box * selectedDimensi) + pcs;
        $('#qtyTotalLabel').text('Total ' + total.toLocaleString('id-ID'));
    }

    function setInputMode(mode) {
        inputMode = mode === 'manual' || mode === 'request' ? mode : 'scan';
        $('#inputMode').val(inputMode);
        if (inputMode === 'manual') {
            stopScanner();
            $('#scanStockView').hide();
            $('#manualStockView').show();
            $('#requestStockView').hide();
            $('#btnManualMode').removeClass('btn-outline-primary').addClass('btn-primary');
            $('#btnScan').removeClass('btn-primary').addClass('btn-outline-primary').html('<i class="fas fa-qrcode"></i>Scan');
            $('#btnRequestMode').removeClass('btn-warning').addClass('btn-outline-warning');
            $('#masterId').val('');
            $('#itemName,#itemExpired').text('-');
            selectedDimensi = 0;
            updateDimensiView();
            updateQtyTotal();
            validateManualReady();
            return;
        }

        if (inputMode === 'request') {
            stopScanner();
            $('#scanStockView,#manualStockView').hide();
            $('#requestStockView').show();
            $('#btnRequestMode').removeClass('btn-outline-warning').addClass('btn-warning');
            $('#btnScan').removeClass('btn-primary').addClass('btn-outline-primary').html('<i class="fas fa-qrcode"></i>Scan');
            $('#btnManualMode').removeClass('btn-primary').addClass('btn-outline-primary');
            $('#masterId,#manualSourceId').val('');
            $('#itemName,#itemExpired').text('-');
            selectedDimensi = 0;
            updateDimensiView();
            updateQtyTotal();
            validateRequestReady();
            return;
        }

        $('#manualStockView,#requestStockView').hide();
        $('#scanStockView').show();
        $('#btnScan').removeClass('btn-outline-primary').addClass('btn-primary');
        $('#btnManualMode').removeClass('btn-primary').addClass('btn-outline-primary');
        $('#btnRequestMode').removeClass('btn-warning').addClass('btn-outline-warning');
        $('#manualSourceId').val('');
        $('#btnSaveOpname').prop('disabled', !$('#masterId').val());
    }

    function resetManualLot(text) {
        $('#manualLot').prop('disabled', true).empty().append(new Option(text || 'Pilih no lot', '', true, true)).trigger('change');
    }

    function resetManualExpired(text) {
        $('#manualExpired').prop('disabled', true).empty().append(new Option(text || 'Pilih expired date', '', true, true)).trigger('change');
        $('#manualSourceId').val('');
        selectedDimensi = 0;
        updateDimensiView();
        updateQtyTotal();
        validateManualReady();
    }

    function validateManualReady() {
        if (inputMode !== 'manual') return;
        $('#btnSaveOpname').prop('disabled', !$('#manualSourceId').val());
    }

    function requestExpiredStorageValue() {
        var value = $.trim($('#requestExpiredView').val() || '');
        var match = value.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
        if (!match) return '';
        return match[3] + '-' + match[2] + '-' + match[1];
    }

    function validateRequestReady() {
        if (inputMode !== 'request') return;
        var ready = !!$('#requestBarang').val() && !!requestExpiredStorageValue();
        $('#btnSaveOpname').prop('disabled', !ready);
    }

    function formatDateView(value) {
        var digits = String(value || '').replace(/\D/g, '').slice(0, 8);
        if (digits.length <= 2) return digits;
        if (digits.length <= 4) return digits.slice(0, 2) + '/' + digits.slice(2);
        return digits.slice(0, 2) + '/' + digits.slice(2, 4) + '/' + digits.slice(4);
    }

    function formatExpiredDate(value) {
        value = $.trim(value || '');
        var match = value.match(/^(\d{4})-(\d{2})-(\d{2})/);
        return match ? match[3] + '/' + match[2] + '/' + match[1] : (value || '-');
    }

    function fillManualExpiredOptions(rows) {
        var $expired = $('#manualExpired');
        $expired.empty().append(new Option('Pilih expired date', '', true, true));
        $.each(rows || [], function (_, row) {
            var option = new Option(formatExpiredDate(row.expired_date || row.text), row.id, false, false);
            $(option).attr('data-dimensi', row.dimensi || 0);
            $expired.append(option);
        });
        $expired.prop('disabled', false).trigger('change');
    }

    function lookupScan(value) {
        value = $.trim(value || '');
        if (!value) {
            toast('warning', 'QRCode belum terbaca');
            return;
        }
        setScannerStatus('Mencari');
        $.ajax({
            url: '<?= base_url('admin/stockopname/input/lookup') ?>',
            type: 'POST',
            dataType: 'json',
            data: {scan_value: value},
            success: function (res) {
                if (!res.status) {
                    toast('warning', res.message || 'Data tidak ditemukan');
                    setScannerStatus('');
                    return;
                }
                fillItem(res.data || {});
                $('#manualScanValue').val(value);
                toast('success', res.message || 'Data berhasil diisi');
                stopScanner();
            },
            error: function (xhr) {
                toast('error', ajaxMessage(xhr, 'Server tidak merespons'));
                setScannerStatus('');
            }
        });
    }

    $('#btnScan').on('click', function () {
        setInputMode('scan');
        if (scanning) {
            stopScanner();
            return;
        }
        if (typeof Html5Qrcode === 'undefined') {
            toast('error', 'Library scanner belum termuat');
            return;
        }

        $('#qrReader').show();
        $('#btnScan').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>Membuka kamera');
        setScannerStatus('Kamera');
        scanner = scanner || new Html5Qrcode('qrReader');
        scanner.start(
            {facingMode: 'environment'},
            {fps: 10, qrbox: {width: 220, height: 220}},
            function (decodedText) {
                if (!decodedText) return;
                setScannerStatus('Terbaca');
                lookupScan(decodedText);
            }
        ).then(function () {
            scanning = true;
            $('#btnScan').prop('disabled', false).html('<i class="fas fa-stop"></i>Tutup Scan');
        }).catch(function () {
            $('#qrReader').hide();
            $('#btnScan').prop('disabled', false).html('<i class="fas fa-qrcode"></i>Scan');
            setScannerStatus('');
            toast('error', 'Kamera tidak dapat dibuka');
        });
    });

    $('#btnManualMode').on('click', function () {
        setInputMode('manual');
    });

    $('#btnRequestMode').on('click', function () {
        setInputMode('request');
    });

    $('#manualBarang,#requestBarang').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Cari nama barang',
        allowClear: true,
        ajax: {
            url: '<?= base_url('admin/stockopname/input/manual/barang') ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term || '',
                    page: params.page || 1
                };
            },
            processResults: function (data) {
                return data;
            }
        }
    });

    $('#requestBarang').on('change', function () {
        var selected = $('#requestBarang').select2('data')[0] || {};
        selectedDimensi = parseInt(selected.dimensi || 0, 10) || 0;
        updateDimensiView();
        updateQtyTotal();
        validateRequestReady();
    });


    $('#requestExpiredView').on('input', function () {
        $(this).val(formatDateView($(this).val()));
        validateRequestReady();
    }).on('blur', function () {
        var value = $.trim($(this).val() || '');
        if (value && !requestExpiredStorageValue()) {
            toast('warning', 'Expired date gunakan format tanggal/bulan/tahun');
        }
        validateRequestReady();
    });

    $('#manualBarang').on('change', function () {
        var kodeBarang = $(this).val();
        resetManualExpired(kodeBarang ? 'Memuat expired date' : 'Pilih nama barang dahulu');
        if (!kodeBarang) return;

        $.ajax({
            url: '<?= base_url('admin/stockopname/input/manual/expired') ?>',
            type: 'POST',
            dataType: 'json',
            data: {kode_barang: kodeBarang},
            success: function (res) {
                if (!res.status || !(res.data || []).length) {
                    resetManualExpired('Expired date tidak tersedia');
                    toast('warning', res.message || 'Expired date tidak tersedia');
                    return;
                }
                fillManualExpiredOptions(res.data);
            },
            error: function (xhr) {
                resetManualExpired('Gagal memuat expired date');
                toast('error', ajaxMessage(xhr, 'Server tidak merespons'));
            }
        });
    });

    $('#manualExpired').on('change', function () {
        var selected = $(this).find(':selected');
        $('#manualSourceId').val($(this).val() || '');
        selectedDimensi = parseInt(selected.attr('data-dimensi') || 0, 10) || 0;
        updateDimensiView();
        updateQtyTotal();
        validateManualReady();
    });

    $('#btnManualLookup').on('click', function () {
        lookupScan($('#manualScanValue').val());
    });

    $('#manualScanValue').on('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            lookupScan($(this).val());
        }
    });

    $('#qtyPcs,#qtyBox').on('input', function () {
        updateQtyTotal();
    });

    $('#formInputOpname').on('submit', function (event) {
        event.preventDefault();
        var $button = $('#btnSaveOpname');
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>Menyimpan');
        $.ajax({
            url: inputMode === 'request'
                ? '<?= base_url('admin/stockopname/input/request/save') ?>'
                : (inputMode === 'manual' ? '<?= base_url('admin/stockopname/input/manual/save') ?>' : '<?= base_url('admin/stockopname/input/save') ?>'),
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function (res) {
                if (!res.status) {
                    toast('warning', res.message || 'Validasi gagal');
                    $button.prop('disabled', false).html('<i class="fas fa-save"></i>Input Opname');
                    return;
                }
                toast('success', res.message || 'Data opname tersimpan');
                resetAfterSave();
                $button.html('<i class="fas fa-save"></i>Input Opname');
            },
            error: function (xhr) {
                toast('error', ajaxMessage(xhr, 'Server tidak merespons'));
                $button.prop('disabled', false).html('<i class="fas fa-save"></i>Input Opname');
            }
        });
    });
});
</script>
