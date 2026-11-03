(function ($) {
    'use strict';

    const app = window.MobileERP || {};

    function toast(message, icon) {
        if (window.Swal) {
            Swal.fire({
                toast: true,
                position: 'top',
                icon: icon || 'success',
                title: message,
                showConfirmButton: false,
                timer: 2200,
                timerProgressBar: true
            });
            return;
        }

        $('#mobileAppToastMessage').text(message);
        const toastEl = document.getElementById('mobileAppToast');
        if (toastEl && window.bootstrap) {
            bootstrap.Toast.getOrCreateInstance(toastEl).show();
        }
    }

    function escapeHtml(value) {
        return String(value == null ? '' : value).replace(/[&<>"'`=\/]/g, function (s) {
            return ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;',
                "'": '&#39;', '/': '&#x2F;', '`': '&#x60;', '=': '&#x3D;'
            })[s];
        });
    }

    function statusBadge(status) {
        const label = status || 'Open';
        const lower = label.toLowerCase();
        let cls = 'status-muted';
        if (lower.indexOf('selesai') >= 0 || lower.indexOf('done') >= 0) cls = 'status-done';
        else if (lower.indexOf('progress') >= 0 || lower.indexOf('proses') >= 0) cls = 'status-progress';
        else if (lower.indexOf('open') >= 0 || lower.indexOf('pending') >= 0 || lower.indexOf('belum') >= 0) cls = 'status-open';
        return '<span class="status-badge ' + cls + '">' + escapeHtml(label) + '</span>';
    }

    function timelineIcon(status) {
        const label = (status || '').toLowerCase();
        if (label.indexOf('selesai') >= 0 || label.indexOf('done') >= 0 || label.indexOf('closed') >= 0) {
            return '<i class="fas fa-circle-check text-success"></i>';
        }
        if (label.indexOf('progress') >= 0 || label.indexOf('proses') >= 0 || label.indexOf('sedang') >= 0) {
            return '<i class="fas fa-spinner text-primary"></i>';
        }
        return '<i class="far fa-clock text-warning"></i>';
    }

    function renderTimeline(issue, logs) {
        let rows = logs || [];
        if (!rows.length && issue) {
            rows = [{
                status_name: issue.status_name || 'Laporan dibuat',
                note: 'Laporan dikirim dari form mobile.',
                changed_at: issue.created_at || issue.report_datetime || '-'
            }];
        }

        return '<div class="mobile-card">' +
            '<div class="card-title-row"><h2>Timeline</h2><span class="status-badge status-muted">' + rows.length + ' aktivitas</span></div>' +
            rows.map(function (log, index) {
                const title = log.status_name || (index === 0 ? 'Laporan dibuat' : 'Aktivitas laporan');
                const note = log.note || 'Status laporan diperbarui.';
                const time = log.changed_at || '-';
                const actor = log.changed_by_name ? '<span><i class="far fa-user me-1"></i>' + escapeHtml(log.changed_by_name) + '</span>' : '';
                const borderClass = index < rows.length - 1 ? ' border-bottom pb-3 mb-3' : '';

                return '<div class="data-card' + borderClass + '">' +
                    '<div class="data-card-head"><div><h3>' + escapeHtml(title) + '</h3>' +
                    '<div class="meta-row"><span><i class="far fa-clock me-1"></i>' + escapeHtml(time) + '</span>' + actor + '</div>' +
                    '<p class="mb-0 text-muted-soft">' + escapeHtml(note) + '</p></div>' +
                    timelineIcon(title) + '</div></div>';
            }).join('') +
            '</div>';
    }

    function setLoading($button, loading, label) {
        if (!$button.length) return;
        if (loading) {
            $button.data('html', $button.html()).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>' + (label || 'Memproses'));
        } else {
            $button.prop('disabled', false).html($button.data('html'));
        }
    }

    function initSelect2() {
        $('.mobile-select2').each(function () {
            const $select = $(this);
            const ajaxUrl = $select.data('ajax-url');
            const config = {
                width: '100%',
                dropdownParent: $select.closest('.modal, .offcanvas, body'),
                placeholder: $select.data('placeholder') || 'Pilih data',
                allowClear: true
            };

            if (ajaxUrl) {
                config.ajax = {
                    url: ajaxUrl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term || '' };
                    },
                    processResults: function (response, params) {
                        const keyword = ((params && params.term) || '').toString().toLowerCase();
                        const rows = response.data || [];
                        return {
                            results: rows
                                .filter(function (row) {
                                    return !keyword || (row.name || '').toLowerCase().indexOf(keyword) >= 0;
                                })
                                .map(function (row) {
                                    return { id: row.id, text: row.name };
                                })
                        };
                    }
                };
            }

            $select.select2(config);
        });
    }

    function initIssueForm() {
        const $form = $('#mobileIssueForm');
        if (!$form.length) return;

        $('#description').on('input', function () {
            $('#descriptionCount').text(this.value.length);
        });

        $('#evidence').on('change', function () {
            const files = Array.from(this.files || []);
            const html = files.map(function (file) {
                return '<img src="' + URL.createObjectURL(file) + '" alt="' + escapeHtml(file.name) + '">';
            }).join('');
            $('#evidencePreview').html(html);
        });

        $form.on('submit', function (event) {
            event.preventDefault();
            const $button = $form.find('[type="submit"]');
            const formData = new FormData(this);

            $.ajax({
                url: app.urls.submitIssue,
                type: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function () {
                    setLoading($button, true, 'Mengirim');
                },
                success: function (response) {
                    if (response.status) {
                        $form[0].reset();
                        $('.mobile-select2').val(null).trigger('change');
                        $('#descriptionCount').text('0');
                        $('#evidencePreview').empty();
                        toast(response.message || 'Laporan berhasil dikirim.', 'success');
                    } else {
                        toast(response.message || 'Laporan belum bisa dikirim.', 'error');
                    }
                },
                error: function () {
                    toast('Server belum merespons. Coba lagi beberapa saat.', 'error');
                },
                complete: function () {
                    setLoading($button, false);
                }
            });
        });
    }

    function initListLoader() {
        const $list = $('#mobileIssueList');
        if (!$list.length) return;

        $list.html('<div class="skeleton"></div><div class="skeleton"></div><div class="skeleton"></div>');

        $.getJSON(app.urls.issueList, function (response) {
            const rows = response.data || [];
            if (!rows.length) {
                $list.html('<div class="mobile-card empty-state"><i class="far fa-folder-open"></i><strong>Belum ada data</strong><span>Laporan operasional akan muncul di sini.</span></div>');
                return;
            }

            $list.html(rows.slice(0, 12).map(function (item) {
                return '<a class="mobile-card data-card text-decoration-none text-reset" href="' + app.siteUrl + '/mobile-erp/detail/' + item.id + '">' +
                    '<div class="data-card-head"><div><h3>' + escapeHtml(item.location_name || 'Lokasi') + '</h3><div class="meta-row"><span><i class="far fa-clock me-1"></i>' + escapeHtml(item.report_datetime || '-') + '</span><span><i class="far fa-user me-1"></i>' + escapeHtml(item.created_by || '-') + '</span></div></div>' +
                    statusBadge(item.status_name) + '</div>' +
                    '<p class="mb-0 text-muted-soft">' + escapeHtml(item.description || '-').slice(0, 130) + '</p>' +
                    '</a>';
            }).join(''));
        }).fail(function () {
            $list.html('<div class="mobile-card empty-state"><i class="fas fa-wifi"></i><strong>Data belum termuat</strong><span>Periksa koneksi server aplikasi.</span></div>');
        });
    }

    function initDetailLoader() {
        const $detail = $('#mobileIssueDetail');
        const id = $detail.data('id');
        if (!$detail.length || !id) return;

        $.getJSON(app.urls.issueDetail + '/' + id, function (response) {
            if (!response.status) {
                $detail.html('<div class="empty-state"><i class="far fa-folder-open"></i><strong>Detail tidak ditemukan</strong></div>');
                return;
            }
            const issue = response.issue || {};
            const evidence = response.evidence || [];
            const logs = response.logs || [];
            $detail.html(
                '<div class="mobile-card data-card">' +
                '<div class="data-card-head"><div><h3>' + escapeHtml(issue.location_name || '-') + '</h3><div class="meta-row"><span>' + escapeHtml(issue.report_datetime || '-') + '</span></div></div>' + statusBadge(issue.status_name) + '</div>' +
                '<p class="mb-0">' + escapeHtml(issue.description || '-') + '</p>' +
                '</div>' +
                '<div class="mobile-card"><div class="card-title-row"><h2>Bukti Foto</h2><span class="status-badge status-muted">' + evidence.length + ' file</span></div>' +
                '<div class="preview-grid">' + evidence.map(function (file) {
                    return '<a href="' + app.baseUrl + escapeHtml(file.file_path) + '" target="_blank"><img src="' + app.baseUrl + escapeHtml(file.file_path) + '" alt="' + escapeHtml(file.file_name || 'Bukti') + '"></a>';
                }).join('') + '</div></div>' +
                renderTimeline(issue, logs)
            );
        });
    }

    $(function () {
        initSelect2();
        initIssueForm();
        initListLoader();
        initDetailLoader();

        $('[data-mobile-toast]').on('click', function () {
            toast($(this).data('mobile-toast'), 'info');
        });

    });
})(jQuery);
