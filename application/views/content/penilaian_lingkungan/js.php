<style>
    .env-page { background: #f4f6f8; }
    .env-toolbar { align-items: center; display: flex; gap: 16px; justify-content: space-between; }
    .env-title { color: #17202a; font-size: 1.35rem; font-weight: 800; letter-spacing: 0; margin: 0; }
    .env-subtitle { color: #6b7280; font-size: .9rem; }
    .env-nav .btn { border-radius: 6px; font-weight: 700; }
    .env-card, .env-modal { border: 0; border-radius: 8px; box-shadow: 0 10px 26px rgba(15, 23, 42, .07); }
    .env-kpi-row { margin-bottom: 10px; }
    .env-kpi { align-items: center; background: #fff; border: 1px solid #e8edf2; border-radius: 8px; display: flex; gap: 12px; margin-bottom: 10px; min-height: 78px; padding: 14px; }
    .env-kpi-icon { align-items: center; background: #f3f6f9; border-radius: 8px; display: inline-flex; height: 42px; justify-content: center; width: 42px; }
    .env-kpi small { color: #64748b; display: block; font-weight: 700; line-height: 1.1; text-transform: uppercase; }
    .env-kpi strong { color: #111827; display: block; font-size: 1.45rem; line-height: 1.1; }
    .env-table thead th { border-top: 0; color: #64748b; font-size: .75rem; letter-spacing: 0; text-transform: uppercase; white-space: nowrap; }
    .env-table td { vertical-align: middle; }
    .env-desc { color: #334155; max-width: 360px; white-space: normal; }
    .env-badge { border-radius: 999px; display: inline-block; font-size: .75rem; font-weight: 800; padding: 4px 9px; }
    .env-badge-open { background: #fff7ed; color: #c2410c; }
    .env-badge-progress { background: #eff6ff; color: #1d4ed8; }
    .env-badge-done { background: #ecfdf5; color: #047857; }
    .env-badge-muted { background: #f1f5f9; color: #475569; }
    .env-mini-list .env-mini-item { align-items: center; border-bottom: 1px solid #edf1f5; display: flex; gap: 12px; justify-content: space-between; padding: 9px 0; }
    .env-mini-list .env-mini-item:last-child { border-bottom: 0; }
    .env-progress { background: #e5e7eb; border-radius: 99px; height: 6px; margin-top: 6px; overflow: hidden; width: 100%; }
    .env-progress span { background: #111827; display: block; height: 100%; }
    .env-tabs .nav-link { border-radius: 6px; color: #475569; font-weight: 800; }
    .env-tabs .nav-link.active { background: #111827; color: #fff; }
    .env-master-panel { border: 1px solid #e8edf2; border-radius: 8px; padding: 14px; }
    .env-inline-form { background: #f8fafc; border-radius: 8px; margin-bottom: 12px; padding: 12px; }
    .env-detail-box { background: #f8fafc; border-radius: 8px; display: grid; gap: 12px; grid-template-columns: repeat(2, minmax(0, 1fr)); padding: 12px; }
    .env-detail-box .wide { grid-column: span 2; }
    .env-detail-box small { color: #64748b; display: block; font-weight: 800; text-transform: uppercase; }
    .env-detail-box strong { color: #111827; display: block; font-weight: 700; overflow-wrap: anywhere; }
    .env-upload-zone { align-items: center; border: 2px dashed #cbd5e1; border-radius: 8px; cursor: pointer; display: flex; flex-direction: column; gap: 6px; justify-content: center; min-height: 150px; padding: 22px; text-align: center; transition: .2s ease; width: 100%; }
    .env-upload-zone:hover, .env-upload-zone.is-dragging { background: #f8fafc; border-color: #111827; }
    .env-upload-zone i { color: #111827; font-size: 2rem; }
    .env-upload-zone span { color: #111827; font-weight: 800; }
    .env-upload-zone small { color: #64748b; }
    .env-preview-grid { display: grid; gap: 10px; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); margin-top: 10px; }
    .env-preview-grid figure { border: 1px solid #e5e7eb; border-radius: 8px; margin: 0; overflow: hidden; }
    .env-preview-grid img { aspect-ratio: 1 / 1; display: block; object-fit: cover; width: 100%; }
    .env-preview-grid figcaption { color: #64748b; font-size: .72rem; overflow: hidden; padding: 6px; text-overflow: ellipsis; white-space: nowrap; }
    .env-side-metric { align-items: flex-start; border-bottom: 1px solid #edf1f5; display: flex; gap: 12px; padding: 14px 0; }
    .env-side-metric:first-child { padding-top: 0; }
    .env-side-metric:last-child { border-bottom: 0; padding-bottom: 0; }
    .env-side-metric span { align-items: center; background: #f3f6f9; border-radius: 8px; color: #111827; display: inline-flex; height: 38px; justify-content: center; width: 38px; }
    .env-side-metric small { color: #64748b; display: block; font-weight: 800; text-transform: uppercase; }
    .env-side-metric strong { color: #111827; display: block; font-weight: 700; }
    .env-location-picker { align-items: center; background: #fff; border: 1px solid #ced4da; border-radius: 6px; color: #111827; display: flex; justify-content: space-between; min-height: 38px; padding: 7px 12px; text-align: left; width: 100%; }
    .env-location-picker:hover, .env-location-picker:focus { border-color: #111827; outline: 0; }
    .env-location-picker strong { font-weight: 700; }
    .env-location-grid { display: grid; gap: 10px; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); }
    .env-location-option { align-items: center; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; color: #111827; display: flex; gap: 10px; min-height: 58px; padding: 11px; text-align: left; transition: .15s ease; }
    .env-location-option span { align-items: center; background: #f3f6f9; border-radius: 8px; color: #111827; display: inline-flex; height: 34px; justify-content: center; width: 34px; }
    .env-location-option strong { font-weight: 700; overflow-wrap: anywhere; }
    .env-location-option:hover, .env-location-option.is-selected { background: #111827; border-color: #111827; color: #fff; }
    .env-location-option:hover span, .env-location-option.is-selected span { background: rgba(255,255,255,.16); color: #fff; }
    @media (max-width: 767.98px) {
        .env-toolbar { align-items: stretch; flex-direction: column; }
        .env-nav { display: grid; grid-template-columns: repeat(3, 1fr); width: 100%; }
        .env-nav .btn { font-size: .75rem; padding-left: 6px; padding-right: 6px; }
        .env-detail-box { grid-template-columns: 1fr; }
        .env-detail-box .wide { grid-column: span 1; }
    }
</style>

<script>
    $(function() {
        var urls = {
            submit: '<?= site_url('hrd/penilaian_lingkungan/submit') ?>',
            list: '<?= site_url('hrd/penilaian_lingkungan/list') ?>',
            detail: '<?= site_url('hrd/penilaian_lingkungan/detail') ?>',
            update: '<?= site_url('hrd/penilaian_lingkungan/update') ?>',
            stats: '<?= site_url('hrd/penilaian_lingkungan/stats') ?>',
            locations: '<?= site_url('hrd/penilaian_lingkungan/locations') ?>',
            locationsSave: '<?= site_url('hrd/penilaian_lingkungan/locations/save') ?>',
            locationsDelete: '<?= site_url('hrd/penilaian_lingkungan/locations/delete') ?>',
            ratings: '<?= site_url('hrd/penilaian_lingkungan/ratings') ?>',
            ratingsSave: '<?= site_url('hrd/penilaian_lingkungan/ratings/save') ?>',
            ratingsDelete: '<?= site_url('hrd/penilaian_lingkungan/ratings/delete') ?>',
            base: '<?= base_url('') ?>'
        };

        function escapeHtml(value) {
            return String(value == null ? '' : value).replace(/[&<>"'`=\/]/g, function(s) {
                return ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;',
                    '/': '&#x2F;',
                    '`': '&#x60;',
                    '=': '&#x3D;'
                })[s];
            });
        }

        function toast(message, icon) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: icon || 'success',
                    title: message,
                    showConfirmButton: false,
                    timer: 2400,
                    timerProgressBar: true
                });
            }
        }

        function alertBox(target, message, type) {
            var css = 'alert-' + (type || 'success');
            $(target).html('<div class="alert ' + css + ' alert-dismissible py-2 mb-2"><button type="button" class="close py-1" data-dismiss="alert">&times;</button>' + escapeHtml(message) + '</div>');
        }

        function confirmDialog(title, text, callback) {
            if (typeof Swal === 'undefined') {
                if (confirm(title)) callback();
                return;
            }
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjut',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) callback();
            });
        }

        function setLoading($button, isLoading, text) {
            if (!$button.length) return;
            if (isLoading) {
                $button.data('original-html', $button.html()).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> ' + text);
            } else {
                $button.prop('disabled', false).html($button.data('original-html'));
            }
        }

        function validateFiles(fileList) {
            for (var i = 0; i < fileList.length; i++) {
                var file = fileList[i];
                var type = (file.type || '').toLowerCase();
                if (type !== 'image/jpeg' && type !== 'image/png' && type !== 'image/jpg') {
                    return 'File harus berupa JPG atau PNG.';
                }
                if (file.size > 5 * 1024 * 1024) {
                    return 'Ukuran file maksimum 5MB per gambar.';
                }
            }
            return null;
        }

        function ajaxErrorMessage(xhr, fallback) {
            var response = xhr && xhr.responseText ? $('<div>').html(xhr.responseText).text().replace(/\s+/g, ' ').trim() : '';
            return response ? response.substring(0, 240) : fallback;
        }

        function statusBadge(name) {
            var label = name || 'Belum diproses';
            var lower = label.toLowerCase();
            var cls = 'env-badge-muted';
            if (lower.indexOf('selesai') >= 0 || lower.indexOf('done') >= 0 || lower.indexOf('closed') >= 0) cls = 'env-badge-done';
            else if (lower.indexOf('progress') >= 0 || lower.indexOf('proses') >= 0) cls = 'env-badge-progress';
            else if (lower.indexOf('pending') >= 0 || lower.indexOf('menunggu') >= 0 || lower.indexOf('belum') >= 0) cls = 'env-badge-open';
            return '<span class="env-badge ' + cls + '">' + escapeHtml(label) + '</span>';
        }

        function loadStats() {
            if (!$('#summaryLocations').length) return;
            $.getJSON(urls.stats, function(response) {
                if (!response.status) return;
                $('#summaryLocations').text(response.location_count || 0);
                $('#summaryOpen').text(response.open_count || 0);
                $('#summaryPending').text(response.pending_count || 0);
                $('#summaryInProgress').text(response.in_progress_count || 0);
                $('#summaryResolved').text(response.resolved_count || 0);

                var maxLocation = 1;
                $.each(response.by_location || [], function(_, item) {
                    maxLocation = Math.max(maxLocation, parseInt(item.total, 10) || 0);
                });

                var locationRows = '';
                var locationMini = '';
                if (response.by_location && response.by_location.length) {
                    $.each(response.by_location, function(_, item) {
                        var total = parseInt(item.total, 10) || 0;
                        var percent = Math.round((total / maxLocation) * 100);
                        locationRows += '<tr><td>' + escapeHtml(item.location_name || '-') + '</td><td class="text-right font-weight-bold">' + total + '</td></tr>';
                        locationMini += '<div class="env-mini-item"><div class="w-100"><div class="d-flex justify-content-between"><span>' + escapeHtml(item.location_name || '-') + '</span><strong>' + total + '</strong></div><div class="env-progress"><span style="width:' + percent + '%"></span></div></div></div>';
                    });
                } else {
                    locationRows = '<tr><td colspan="2" class="text-center text-muted">Tidak ada data.</td></tr>';
                    locationMini = '<div class="text-muted">Tidak ada data.</div>';
                }
                $('#locationCountsTable tbody').html(locationRows);
                $('#locationMiniList').html(locationMini);

                var ratingRows = '';
                var ratingMini = '';
                if (response.by_rating && response.by_rating.length) {
                    $.each(response.by_rating, function(_, item) {
                        ratingRows += '<tr><td>' + escapeHtml(item.rating_name || '-') + '</td><td>' + escapeHtml(item.score || 0) + '</td><td class="text-right font-weight-bold">' + escapeHtml(item.total || 0) + '</td></tr>';
                        ratingMini += '<div class="env-mini-item"><span>' + escapeHtml(item.rating_name || '-') + ' <small class="text-muted">(' + escapeHtml(item.score || 0) + ')</small></span><strong>' + escapeHtml(item.total || 0) + '</strong></div>';
                    });
                } else {
                    ratingRows = '<tr><td colspan="3" class="text-center text-muted">Tidak ada data.</td></tr>';
                    ratingMini = '<div class="text-muted">Tidak ada data.</div>';
                }
                $('#ratingCountsTable tbody').html(ratingRows);
                $('#ratingMiniList').html(ratingMini);
            });
        }

        function loadIssueTable() {
            if (!$('#issueTable').length) return;
            var params = {
                location_id: $('#filterLocation').val(),
                status_id: $('#filterStatus').val(),
                date_from: $('#filterFrom').val(),
                date_to: $('#filterTo').val()
            };
            $.getJSON(urls.list, params, function(response) {
                var tbody = '';
                if (response.data && response.data.length) {
                    $.each(response.data, function(index, item) {
                        var ratingLabel = item.rating_name ? escapeHtml(item.rating_name) + ' <small class="text-muted">(' + escapeHtml(item.score) + ')</small>' : '<span class="text-muted">Belum dirating</span>';
                        tbody += '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td class="font-weight-bold">' + escapeHtml(item.location_name || '-') + '</td>' +
                            '<td>' + ratingLabel + '</td>' +
                            '<td><div class="env-desc">' + escapeHtml(item.description || '-') + '</div></td>' +
                            '<td>' + escapeHtml(item.report_datetime || '-') + '</td>' +
                            '<td>' + escapeHtml(item.due_date || '-') + '</td>' +
                            '<td>' + statusBadge(item.status_name) + '</td>' +
                            '<td><button class="btn btn-xs btn-dark btn-update-issue" data-id="' + escapeHtml(item.id) + '"><i class="fas fa-pen"></i></button></td>' +
                            '</tr>';
                    });
                } else {
                    tbody = '<tr><td colspan="8" class="text-center text-muted">Tidak ada data issue.</td></tr>';
                }
                $('#issueTable tbody').html(tbody);
            });
        }

        function loadPendingIssuesTable() {
            if (!$('#pendingIssuesTable').length) return;
            $.getJSON(urls.list, { status_id: 1 }, function(response) {
                var tbody = '';
                if (response.data && response.data.length) {
                    $.each(response.data, function(_, item) {
                        tbody += '<tr>' +
                            '<td class="font-weight-bold">' + escapeHtml(item.location_name || '-') + '</td>' +
                            '<td><div class="env-desc">' + escapeHtml(item.description || '-') + '</div></td>' +
                            '<td>' + escapeHtml(item.report_datetime || '-') + '</td>' +
                            '<td>' + escapeHtml(item.created_by || '-') + '</td>' +
                            '<td><button class="btn btn-xs btn-dark btn-update-pending-issue" data-id="' + escapeHtml(item.id) + '"><i class="fas fa-pen"></i></button></td>' +
                            '</tr>';
                    });
                } else {
                    tbody = '<tr><td colspan="5" class="text-center text-muted">Tidak ada input user yang menunggu proses.</td></tr>';
                }
                $('#pendingIssuesTable tbody').html(tbody);
            });
        }

        function loadIssueDetail(issueId, mode) {
            $.getJSON(urls.detail + '/' + issueId, function(response) {
                if (!response.status) {
                    toast(response.message || 'Issue tidak ditemukan.', 'error');
                    return;
                }
                var issue = response.issue;
                if (mode === 'pending') {
                    $('#pendingUpdateIssueId').val(issue.id);
                    $('#pendingDetailLocation').text(issue.location_name || '-');
                    $('#pendingDetailReportDatetime').text(issue.report_datetime || '-');
                    $('#pendingDetailDescription').text(issue.description || '-');
                    $('#pendingDetailCurrentRating').text(issue.rating_name ? issue.rating_name + ' (' + issue.score + ')' : 'Belum ditentukan');
                    $('#pendingUpdateStatus').val(issue.status_id);
                    $('#pendingUpdateRating').val(issue.rating_id || '');
                    $('#pendingUpdateDueDate').val(issue.due_date || '');
                    $('#pendingFeedback').html('');
                    $('#pendingIssueUpdateModal').modal('show');
                    return;
                }

                $('#updateIssueId').val(issue.id);
                $('#updateRating').val(issue.rating_id || '');
                $('#updateStatus').val(issue.status_id);
                $('#updateDueDate').val(issue.due_date || '');
                $('#updateNote').val('');
                $('#updateFeedback').html('');

                var evidenceHtml = '<div class="font-weight-bold mb-1">Bukti yang sudah diunggah</div><div class="list-group list-group-flush">';
                if (response.evidence && response.evidence.length) {
                    $.each(response.evidence, function(_, file) {
                        evidenceHtml += '<a class="list-group-item list-group-item-action px-0 py-1" href="' + urls.base + escapeHtml(file.file_path) + '" target="_blank"><i class="fas fa-image mr-1"></i>' + escapeHtml(file.file_name || 'Evidence') + '</a>';
                    });
                } else {
                    evidenceHtml += '<div class="text-muted">Tidak ada evidence.</div>';
                }
                evidenceHtml += '</div>';
                $('#currentEvidence').html(evidenceHtml);

                var logHtml = '<div class="font-weight-bold mb-1">Riwayat perubahan</div><div class="list-group list-group-flush">';
                if (response.logs && response.logs.length) {
                    $.each(response.logs, function(_, log) {
                        logHtml += '<div class="list-group-item px-0 py-2"><div>' + statusBadge(log.status_name) + ' <small class="text-muted">oleh ' + escapeHtml(log.changed_by_name || '-') + ' pada ' + escapeHtml(log.changed_at || '-') + '</small></div><div>' + escapeHtml(log.note || '-') + '</div></div>';
                    });
                } else {
                    logHtml += '<div class="text-muted">Tidak ada riwayat.</div>';
                }
                logHtml += '</div>';
                $('#historyLogs').html(logHtml);
                $('#issueUpdateModal').modal('show');
            });
        }

        function resetLocationSettingsForm() {
            $('#locationSettingsId').val('');
            $('#locationSettingsName').val('');
            $('#locationSettingsActive').val('1');
            $('#locationSettingsFeedback').html('');
        }

        function resetRatingSettingsForm() {
            $('#ratingSettingsId').val('');
            $('#ratingSettingsName').val('');
            $('#ratingSettingsScore').val('');
            $('#ratingSettingsFeedback').html('');
        }

        function loadLocationSettings() {
            if (!$('#locationSettingsTable').length) return;
            $.getJSON(urls.locations, function(response) {
                var tbody = '';
                if (response.data && response.data.length) {
                    $.each(response.data, function(_, item) {
                        tbody += '<tr>' +
                            '<td class="font-weight-bold">' + escapeHtml(item.name || '-') + '</td>' +
                            '<td>' + (item.is_active == 1 ? '<span class="env-badge env-badge-done">Ya</span>' : '<span class="env-badge env-badge-muted">Tidak</span>') + '</td>' +
                            '<td><button class="btn btn-xs btn-outline-dark btn-edit-location mr-1" data-id="' + escapeHtml(item.id) + '"><i class="fas fa-pen"></i></button><button class="btn btn-xs btn-outline-danger btn-delete-location" data-id="' + escapeHtml(item.id) + '"><i class="fas fa-trash"></i></button></td>' +
                            '</tr>';
                    });
                } else {
                    tbody = '<tr><td colspan="3" class="text-center text-muted">Tidak ada lokasi.</td></tr>';
                }
                $('#locationSettingsTable tbody').html(tbody);
            });
        }

        function loadRatingSettings() {
            if (!$('#ratingSettingsTable').length) return;
            $.getJSON(urls.ratings, function(response) {
                var tbody = '';
                if (response.data && response.data.length) {
                    $.each(response.data, function(_, item) {
                        tbody += '<tr>' +
                            '<td class="font-weight-bold">' + escapeHtml(item.name || '-') + '</td>' +
                            '<td>' + escapeHtml(item.score || 0) + '</td>' +
                            '<td><button class="btn btn-xs btn-outline-dark btn-edit-rating mr-1" data-id="' + escapeHtml(item.id) + '"><i class="fas fa-pen"></i></button><button class="btn btn-xs btn-outline-danger btn-delete-rating" data-id="' + escapeHtml(item.id) + '"><i class="fas fa-trash"></i></button></td>' +
                            '</tr>';
                    });
                } else {
                    tbody = '<tr><td colspan="3" class="text-center text-muted">Tidak ada rating.</td></tr>';
                }
                $('#ratingSettingsTable tbody').html(tbody);
            });
        }

        function loadMasterData() {
            loadLocationSettings();
            loadRatingSettings();
        }

        if ($('#issueForm').length) {
            var $description = $('[name="description"]');
            $description.on('input', function() {
                $('#descriptionCounter').text($(this).val().length);
            });

            $('#openLocationPicker').on('click', function() {
                $('#locationPickerSearch').val('');
                $('.env-location-option').removeClass('d-none');
                $('#locationPickerEmpty').addClass('d-none');
                $('#locationPickerModal').modal('show');
                setTimeout(function() {
                    $('#locationPickerSearch').trigger('focus');
                }, 250);
            });

            $('#locationPickerSearch').on('input', function() {
                var keyword = $(this).val().toLowerCase();
                var visible = 0;
                $('.env-location-option').each(function() {
                    var isMatch = ($(this).data('name') || '').toString().toLowerCase().indexOf(keyword) >= 0;
                    $(this).toggleClass('d-none', !isMatch);
                    if (isMatch) visible++;
                });
                $('#locationPickerEmpty').toggleClass('d-none', visible > 0);
            });

            $(document).on('click', '.env-location-option', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#locationId').val(id);
                $('#selectedLocationText').text(name);
                $('.env-location-option').removeClass('is-selected');
                $(this).addClass('is-selected');
                $('#formFeedback').html('');
                $('#locationPickerModal').modal('hide');
            });

            $('#evidence').on('change', function() {
                var files = this.files;
                var error = validateFiles(files);
                var html = '';
                if (error) {
                    alertBox('#formFeedback', error, 'danger');
                    $(this).val('');
                    $('#evidencePreview').html('');
                    return;
                }
                $.each(files, function(_, file) {
                    html += '<figure><img src="' + URL.createObjectURL(file) + '" alt=""><figcaption>' + escapeHtml(file.name) + '</figcaption></figure>';
                });
                $('#evidencePreview').html(html);
            });

            $('.env-upload-zone').on('dragover dragenter', function(e) {
                e.preventDefault();
                $(this).addClass('is-dragging');
            }).on('dragleave drop', function(e) {
                e.preventDefault();
                $(this).removeClass('is-dragging');
                if (e.type === 'drop' && e.originalEvent.dataTransfer.files.length) {
                    $('#evidence')[0].files = e.originalEvent.dataTransfer.files;
                    $('#evidence').trigger('change');
                }
            });

            $('#issueForm').on('reset', function() {
                setTimeout(function() {
                    $('#locationId').val('');
                    $('#selectedLocationText').text('Pilih lokasi');
                    $('.env-location-option').removeClass('is-selected');
                    $('#descriptionCounter').text('0');
                    $('#evidencePreview').html('');
                    $('#formFeedback').html('');
                }, 0);
            });

            $('#issueForm').on('submit', function(e) {
                e.preventDefault();
                if (!$('#locationId').val()) {
                    alertBox('#formFeedback', 'Silakan pilih lokasi laporan.', 'danger');
                    toast('Lokasi laporan wajib dipilih.', 'warning');
                    return;
                }
                var files = $('#evidence')[0].files;
                if (!files.length) {
                    alertBox('#formFeedback', 'Silakan upload minimal satu bukti foto.', 'danger');
                    toast('Foto bukti wajib diisi.', 'warning');
                    return;
                }
                var error = validateFiles(files);
                if (error) {
                    alertBox('#formFeedback', error, 'danger');
                    toast(error, 'warning');
                    return;
                }
                var $button = $(this).find('[type="submit"]');
                var formData = new FormData(this);
                $.ajax({
                    url: urls.submit,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function() {
                        setLoading($button, true, 'Mengirim');
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#issueForm')[0].reset();
                            toast(response.message || 'Issue berhasil dikirim.', 'success');
                        } else {
                            alertBox('#formFeedback', response.message || 'Gagal mengirim laporan.', 'danger');
                            toast(response.message || 'Gagal mengirim laporan.', 'error');
                        }
                    },
                    error: function(xhr) {
                        var message = ajaxErrorMessage(xhr, 'Terjadi kesalahan saat mengirim laporan.');
                        alertBox('#formFeedback', message, 'danger');
                        toast(message, 'error');
                    },
                    complete: function() {
                        setLoading($button, false);
                    }
                });
            });
        }

        $('#reloadIssues, #filterLocation, #filterStatus, #filterFrom, #filterTo').on('click change', function() {
            loadIssueTable();
        });

        $(document).on('click', '.btn-update-issue', function() {
            loadIssueDetail($(this).data('id'), 'admin');
        });

        $('#issueUpdateForm').on('submit', function(e) {
            e.preventDefault();
            var error = validateFiles($('#updateEvidence')[0].files);
            if (error) {
                alertBox('#updateFeedback', error, 'danger');
                return;
            }
            var $button = $(this).find('[type="submit"]');
            $.ajax({
                url: urls.update,
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    setLoading($button, true, 'Menyimpan');
                },
                success: function(response) {
                    if (response.status) {
                        $('#issueUpdateModal').modal('hide');
                        toast(response.message || 'Issue berhasil diperbarui.', 'success');
                        loadIssueTable();
                        loadStats();
                    } else {
                        alertBox('#updateFeedback', response.message || 'Gagal memperbarui issue.', 'danger');
                    }
                },
                error: function() {
                    alertBox('#updateFeedback', 'Terjadi kesalahan saat menyimpan perubahan.', 'danger');
                },
                complete: function() {
                    setLoading($button, false);
                }
            });
        });

        $('#reloadPendingIssues').on('click', loadPendingIssuesTable);

        $(document).on('click', '.btn-update-pending-issue', function() {
            loadIssueDetail($(this).data('id'), 'pending');
        });

        $('#pendingIssueUpdateForm').on('submit', function(e) {
            e.preventDefault();
            var $button = $(this).find('[type="submit"]');
            $.ajax({
                url: urls.update,
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    setLoading($button, true, 'Menyimpan');
                },
                success: function(response) {
                    if (response.status) {
                        $('#pendingIssueUpdateModal').modal('hide');
                        toast(response.message || 'Input user berhasil diproses.', 'success');
                        loadPendingIssuesTable();
                        loadStats();
                    } else {
                        alertBox('#pendingFeedback', response.message || 'Gagal menyimpan perubahan.', 'danger');
                    }
                },
                error: function() {
                    alertBox('#pendingFeedback', 'Terjadi kesalahan saat menyimpan perubahan.', 'danger');
                },
                complete: function() {
                    setLoading($button, false);
                }
            });
        });

        $('a[href="#envMaster"]').on('shown.bs.tab', loadMasterData);
        $('#resetLocationSettings').on('click', resetLocationSettingsForm);
        $('#resetRatingSettings').on('click', resetRatingSettingsForm);

        $('#locationSettingsForm').on('submit', function(e) {
            e.preventDefault();
            var $button = $(this).find('[type="submit"]');
            $.post(urls.locationsSave, $(this).serialize(), function(response) {
                if (response.status) {
                    toast(response.message || 'Lokasi berhasil disimpan.', 'success');
                    resetLocationSettingsForm();
                    loadLocationSettings();
                    loadStats();
                } else {
                    alertBox('#locationSettingsFeedback', response.message || 'Gagal menyimpan lokasi.', 'danger');
                }
            }, 'json').fail(function() {
                alertBox('#locationSettingsFeedback', 'Terjadi kesalahan saat menyimpan lokasi.', 'danger');
            }).always(function() {
                setLoading($button, false);
            });
            setLoading($button, true, 'Simpan');
        });

        $('#ratingSettingsForm').on('submit', function(e) {
            e.preventDefault();
            var $button = $(this).find('[type="submit"]');
            $.post(urls.ratingsSave, $(this).serialize(), function(response) {
                if (response.status) {
                    toast(response.message || 'Rating berhasil disimpan.', 'success');
                    resetRatingSettingsForm();
                    loadRatingSettings();
                    loadStats();
                } else {
                    alertBox('#ratingSettingsFeedback', response.message || 'Gagal menyimpan rating.', 'danger');
                }
            }, 'json').fail(function() {
                alertBox('#ratingSettingsFeedback', 'Terjadi kesalahan saat menyimpan rating.', 'danger');
            }).always(function() {
                setLoading($button, false);
            });
            setLoading($button, true, 'Simpan');
        });

        $(document).on('click', '.btn-edit-location', function() {
            var id = $(this).data('id');
            $.getJSON(urls.locations, function(response) {
                var item = (response.data || []).find(function(x) { return x.id == id; });
                if (item) {
                    $('#locationSettingsId').val(item.id);
                    $('#locationSettingsName').val(item.name);
                    $('#locationSettingsActive').val(item.is_active);
                    $('#locationSettingsName').focus();
                }
            });
        });

        $(document).on('click', '.btn-delete-location', function() {
            var id = $(this).data('id');
            confirmDialog('Hapus lokasi?', 'Data lokasi akan dihapus dari master.', function() {
                $.post(urls.locationsDelete, { id: id }, function(response) {
                    if (response.status) {
                        toast(response.message || 'Lokasi berhasil dihapus.', 'success');
                        loadLocationSettings();
                        loadStats();
                    } else {
                        alertBox('#locationSettingsFeedback', response.message || 'Gagal menghapus lokasi.', 'danger');
                    }
                }, 'json').fail(function() {
                    alertBox('#locationSettingsFeedback', 'Terjadi kesalahan saat menghapus lokasi.', 'danger');
                });
            });
        });

        $(document).on('click', '.btn-edit-rating', function() {
            var id = $(this).data('id');
            $.getJSON(urls.ratings, function(response) {
                var item = (response.data || []).find(function(x) { return x.id == id; });
                if (item) {
                    $('#ratingSettingsId').val(item.id);
                    $('#ratingSettingsName').val(item.name);
                    $('#ratingSettingsScore').val(item.score);
                    $('#ratingSettingsName').focus();
                }
            });
        });

        $(document).on('click', '.btn-delete-rating', function() {
            var id = $(this).data('id');
            confirmDialog('Hapus rating?', 'Data rating akan dihapus dari master.', function() {
                $.post(urls.ratingsDelete, { id: id }, function(response) {
                    if (response.status) {
                        toast(response.message || 'Rating berhasil dihapus.', 'success');
                        loadRatingSettings();
                        loadStats();
                    } else {
                        alertBox('#ratingSettingsFeedback', response.message || 'Gagal menghapus rating.', 'danger');
                    }
                }, 'json').fail(function() {
                    alertBox('#ratingSettingsFeedback', 'Terjadi kesalahan saat menghapus rating.', 'danger');
                });
            });
        });

        loadStats();
        loadIssueTable();
        loadPendingIssuesTable();
        loadMasterData();
    });
</script>
