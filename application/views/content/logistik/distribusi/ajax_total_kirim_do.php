<script>
    $(document).ready(function() {
        const $tableBody = $('#tbl_kirim_do tbody');

        function escapeHtml(value) {
            return $('<div>').text(value == null ? '' : value).html();
        }

        function renderRows(rows) {
            if (!rows || rows.length === 0) {
                $('#summary_top_kirim_do').html('<li>-</li>');
                $('#summary_bottom_kirim_do').html('<li>-</li>');
                return `
                    <tr>
                        <td colspan="4" class="text-center text-muted">Data tidak ditemukan</td>
                    </tr>
                `;
            }

            let minVal = null;
            let maxVal = null;
            const ranked = rows.map((row) => {
                const terkirimVal = parseInt(row.total_faktur_terkirim, 10);
                const safeVal = Number.isNaN(terkirimVal) ? 0 : terkirimVal;
                return {
                    rute: row.rute ? row.rute : '-',
                    terkirim: safeVal
                };
            });

            ranked.forEach((r) => {
                if (minVal === null || r.terkirim < minVal) minVal = r.terkirim;
                if (maxVal === null || r.terkirim > maxVal) maxVal = r.terkirim;
            });

            const top2 = [...ranked]
                .sort((a, b) => b.terkirim - a.terkirim)
                .slice(0, 2);
            const bottom2 = [...ranked]
                .sort((a, b) => a.terkirim - b.terkirim)
                .slice(0, 2);

            const topHtml = top2.length
                ? top2.map((r) => `<li>${escapeHtml(r.rute)} (${r.terkirim})</li>`).join('')
                : '<li>-</li>';
            const bottomHtml = bottom2.length
                ? bottom2.map((r) => `<li>${escapeHtml(r.rute)} (${r.terkirim})</li>`).join('')
                : '<li>-</li>';

            $('#summary_top_kirim_do').html(topHtml);
            $('#summary_bottom_kirim_do').html(bottomHtml);

            let html = '';
            rows.forEach((row) => {
                const rute = escapeHtml(row.rute ? row.rute : '-');
                const total = escapeHtml(row.total_faktur ? row.total_faktur : '0');
                const terkirim = escapeHtml(row.total_faktur_terkirim ? row.total_faktur_terkirim : '0');
                const pending = escapeHtml(row.total_faktur_pending ? row.total_faktur_pending : '0');
                const terkirimVal = parseInt(row.total_faktur_terkirim, 10);
                let rowClass = '';
                if (!Number.isNaN(terkirimVal)) {
                    if (maxVal !== null && terkirimVal === maxVal) {
                        rowClass = 'row-max';
                    } else if (minVal !== null && terkirimVal === minVal) {
                        rowClass = 'row-min';
                    }
                }
                html += `
                    <tr class="${rowClass}">
                        <td>${rute}</td>
                        <td>${total}</td>
                        <td>${terkirim}</td>
                        <td>${pending}</td>
                    </tr>
                `;
            });
            return html;
        }

        function resetDataTable() {
            if ($.fn.DataTable.isDataTable('#tbl_kirim_do')) {
                $('#tbl_kirim_do').DataTable().destroy();
            }
        }

        function initDataTable() {
            $('#tbl_kirim_do').DataTable({
                paging: false,
                lengthChange: false,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true
            });
        }

        function loadKirimDo() {
            const tanggal = $('#filter_tanggal_kirim_do').val();
            const ketStatus = $('#tab_kirim_do .nav-link.active').data('status');

            resetDataTable();
            $tableBody.html(`
                <tr>
                    <td colspan="4" class="text-center text-muted">Memuat data...</td>
                </tr>
            `);

            $.ajax({
                url: "<?= base_url('logistik/distibusi/ajax_total_kirim_do') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    tanggal: tanggal,
                    ket_status: ketStatus
                },
                success: function(res) {
                    const rows = res && res.data ? res.data : [];
                    $tableBody.html(renderRows(rows));
                    initDataTable();
                },
                error: function() {
                    $tableBody.html(`
                        <tr>
                            <td colspan="4" class="text-center text-danger">Gagal memuat data dari server</td>
                        </tr>
                    `);
                }
            });
        }

        $('#filter_tanggal_kirim_do').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Clear'
            }
        });

        $('#filter_tanggal_kirim_do').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(
                picker.startDate.format('YYYY-MM-DD') +
                ' - ' +
                picker.endDate.format('YYYY-MM-DD')
            );
        });

        $('#filter_tanggal_kirim_do').on('cancel.daterangepicker', function() {
            $(this).val('');
        });

        $('#btn_filter_kirim_do').on('click', loadKirimDo);
        $('#btn_reload_kirim_do').on('click', function() {
            $('#filter_tanggal_kirim_do').val('');
            loadKirimDo();
        });
        $('#btn_export_kirim_do').on('click', function() {
            const tanggal = $('#filter_tanggal_kirim_do').val();
            const ketStatus = $('#tab_kirim_do .nav-link.active').data('status');

            const url = "<?= base_url('logistik/distibusi/export_total_kirim_do') ?>" +
                '?tanggal=' + encodeURIComponent(tanggal || '') +
                '&ket_status=' + encodeURIComponent(ketStatus || '');
            window.location.href = url;
        });
        $('#tab_kirim_do .nav-link').on('click', function(e) {
            e.preventDefault();
            $('#tab_kirim_do .nav-link').removeClass('active');
            $(this).addClass('active');
            loadKirimDo();
        });

        loadKirimDo();
    });
</script>
