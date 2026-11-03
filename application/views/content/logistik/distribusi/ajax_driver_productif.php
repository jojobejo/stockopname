<script>
    $(function() {
        $('#filter_tanggal_driver_productif').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Clear'
            }
        });

        $('#filter_tanggal_driver_productif').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(
                picker.startDate.format('YYYY-MM-DD') +
                ' - ' +
                picker.endDate.format('YYYY-MM-DD')
            ).trigger('change');
        });

        $('#filter_tanggal_driver_productif').on('cancel.daterangepicker', function() {
            $(this).val('');
        });
    });
</script>

<script>
    $(function() {
        function renderEmpty(message) {
            const colCount = $('#tbl_driver_productif thead th').length || 1;
            $('#tbl_driver_productif tbody').html(`
                <tr>
                    <td colspan="${colCount}" class="text-center text-muted">${message}</td>
                </tr>
            `);
        }

        function loadDriverProductif() {
            const tanggal = $('#filter_tanggal_driver_productif').val();
            const ketStatus = $('#tab_driver_productif .nav-link.active').data('status') || '';

            if (!tanggal) {
                renderEmpty('Silakan pilih rentang tanggal terlebih dahulu');
                return;
            }

            renderEmpty('Memuat data...');

            $.ajax({
                url: "<?= base_url('logistik/distibusi/ajax_driver_productif') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    tanggal: tanggal,
                    ket_status: ketStatus
                },
                success: function(res) {
                    if (!res || !res.status) {
                        renderEmpty('Gagal memuat data');
                        return;
                    }

                    if (!res.data || res.data.length === 0) {
                        renderEmpty('Data tidak ditemukan');
                        $('#tbl_driver_productif thead').html('<tr><th>Nama Driver</th><th>Total Kirim</th></tr>');
                        $('#tbl_driver_productif tfoot').html('');
                        $('#summary_top_driver').html('<li class="text-muted">Tidak ada data</li>');
                        $('#summary_bottom_driver').html('<li class="text-muted">Tidak ada data</li>');
                        return;
                    }

                    let thead = `<tr><th>Nama Driver</th><th>Total Kirim</th>`;
                    (res.rute || []).forEach(r => {
                        thead += `<th>${r.kd_rute}</th>`;
                    });
                    thead += `</tr>`;
                    $('#tbl_driver_productif thead').html(thead);

                    const sorted = (res.data || []).slice().sort((a, b) => {
                        const av = a.total_kirim || 0;
                        const bv = b.total_kirim || 0;
                        if (av === bv) {
                            return String(a.nama_driver).localeCompare(String(b.nama_driver));
                        }
                        return bv - av;
                    });

                    let html = '';
                    const totalsByRute = {};
                    sorted.forEach(row => {
                        const totalKirim = row.total_kirim || 0;
                        const totalKirimText = `${totalKirim}`;

                        html += `<tr><td>${row.nama_driver}</td><td class="text-center">${totalKirimText}</td>`;
                        (res.rute || []).forEach(r => {
                            const val = (row.rute && row.rute[r.kd_rute]) ? row.rute[r.kd_rute] : 0;
                            totalsByRute[r.kd_rute] = (totalsByRute[r.kd_rute] || 0) + val;
                            if (val > 0) {
                                html += `<td class="text-center"><span class="cell-active">${val}</span></td>`;
                            } else {
                                html += `<td class="text-center cell-zero">0</td>`;
                            }
                        });
                        html += `</tr>`;
                    });

                    $('#tbl_driver_productif tbody').html(html);

                    let tfoot = `<tr><td class="text-right">Total per Rute</td><td class="text-center">-</td>`;
                    (res.rute || []).forEach(r => {
                        const totalRute = totalsByRute[r.kd_rute] || 0;
                        tfoot += `<td class="text-center">${totalRute}</td>`;
                    });
                    tfoot += `</tr>`;
                    $('#tbl_driver_productif tfoot').html(tfoot);

                    const renderHighlight = (list, targetId) => {
                        if (!list || list.length === 0) {
                            $(targetId).html('<li class="text-muted">Tidak ada data</li>');
                            return;
                        }
                        let items = '';
                        list.forEach(row => {
                            const totalKirim = row.total_kirim || 0;
                            items += `<li>${row.nama_driver} - ${totalKirim}</li>`;
                        });
                        $(targetId).html(items);
                    };

                    renderHighlight(res.top, '#summary_top_driver');
                    renderHighlight(res.bottom, '#summary_bottom_driver');
                },
                error: function() {
                    renderEmpty('Terjadi kesalahan saat memuat data');
                }
            });
        }

        $('#btn_filter_driver_productif').on('click', loadDriverProductif);
        $('#filter_tanggal_driver_productif').on('change', loadDriverProductif);
        $('#tab_driver_productif .nav-link').on('click', function(e) {
            e.preventDefault();
            $('#tab_driver_productif .nav-link').removeClass('active');
            $(this).addClass('active');
            loadDriverProductif();
        });
        $('#btn_reload_driver_productif').on('click', function() {
            $('#filter_tanggal_driver_productif').val('');
            renderEmpty('Silakan pilih rentang tanggal terlebih dahulu');
        });

        $('#btn_export_driver_productif').on('click', function() {
            const tanggal = $('#filter_tanggal_driver_productif').val();
            const ketStatus = $('#tab_driver_productif .nav-link.active').data('status') || '';

            if (!tanggal) {
                renderEmpty('Silakan pilih rentang tanggal terlebih dahulu');
                return;
            }

            const url = "<?= base_url('logistik/distibusi/export_driver_productif') ?>" +
                '?tanggal=' + encodeURIComponent(tanggal) +
                '&ket_status=' + encodeURIComponent(ketStatus);
            window.location.href = url;
        });

        renderEmpty('Silakan pilih rentang tanggal terlebih dahulu');
    });
</script>
