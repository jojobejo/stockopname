<script src="<?php echo base_url('assets/plugins/chart.js/Chart.min.js') ?>"></script>

<script>
    $(function() {
        const fakturCtx = document.getElementById('chartFaktur').getContext('2d');
        const driverCtx = document.getElementById('chartDriver').getContext('2d');

        const fakturChart = new Chart(fakturCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Faktur Terkirim',
                    data: [],
                    borderColor: '#38bdf8',
                    backgroundColor: 'rgba(56, 189, 248, 0.2)',
                    pointBackgroundColor: '#0ea5e9',
                    borderWidth: 2,
                    lineTension: 0.35,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }]
                }
            }
        });

        const driverChart = new Chart(driverCtx, {
            type: 'horizontalBar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Total DO',
                    data: [],
                    backgroundColor: '#10b981'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        },
                        gridLines: {
                            color: 'rgba(148, 163, 184, 0.2)'
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        });

        function formatRangeLabel(start, end) {
            if (start === end) {
                return moment(start).format('DD MMM YYYY');
            }
            return moment(start).format('DD MMM YYYY') + ' - ' + moment(end).format('DD MMM YYYY');
        }

        function getRange(range) {
            const now = moment();
            if (range === 'month') {
                return {
                    start: now.clone().startOf('month').format('YYYY-MM-DD'),
                    end: now.clone().endOf('month').format('YYYY-MM-DD')
                };
            }
            if (range === 'year') {
                return {
                    start: now.clone().startOf('year').format('YYYY-MM-DD'),
                    end: now.clone().endOf('year').format('YYYY-MM-DD')
                };
            }
            if (range === 'custom') {
                const raw = $('#dash-range').val();
                if (raw && raw.indexOf(' - ') > -1) {
                    const parts = raw.split(' - ');
                    return {
                        start: parts[0],
                        end: parts[1]
                    };
                }
            }
            return {
                start: now.format('YYYY-MM-DD'),
                end: now.format('YYYY-MM-DD')
            };
        }

        function renderDashboard(data, range) {
            $('#dash-total-terkirim').text(data.summary.total_terkirim || 0);
            $('#dash-total-pending').text(data.summary.total_pending || 0);
            $('#dash-total-driver').text(data.summary.total_driver || 0);
            $('#dash-total-faktur').text('Total faktur: ' + (data.summary.total_faktur || 0));

            const labels = [];
            const values = [];
            (data.series || []).forEach(item => {
                labels.push(moment(item.tgl).format('DD/MM'));
                values.push(parseInt(item.total_terkirim, 10) || 0);
            });

            if (labels.length === 0) {
                labels.push(moment().format('DD/MM'));
                values.push(0);
            }

            fakturChart.data.labels = labels;
            fakturChart.data.datasets[0].data = values;
            fakturChart.update();

            const driverLabels = [];
            const driverValues = [];
            const listHtml = [];

            (data.top_driver || []).forEach((item, idx) => {
                driverLabels.push(item.nama_driver);
                driverValues.push(parseInt(item.total_do, 10) || 0);
                listHtml.push(`
                    <li>
                        <span>${idx + 1}. ${item.nama_driver}</span>
                        <span class="dash-pill">${item.total_do} DO</span>
                    </li>
                `);
            });

            if (listHtml.length === 0) {
                listHtml.push('<li><span class="text-muted">Belum ada data</span></li>');
            }

            $('#dash-top-driver-list').html(listHtml.join(''));

            driverChart.data.labels = driverLabels;
            driverChart.data.datasets[0].data = driverValues;
            driverChart.update();

            const topRuteHtml = [];
            (data.top_rute || []).forEach((item, idx) => {
                topRuteHtml.push(`
                    <li>
                        <span>${idx + 1}. ${item.kd_rute}</span>
                        <span class="dash-pill">${item.total_faktur} Faktur</span>
                    </li>
                `);
            });
            if (topRuteHtml.length === 0) {
                topRuteHtml.push('<li><span class="text-muted">Belum ada data</span></li>');
            }
            $('#dash-top-rute-list').html(topRuteHtml.join(''));

            const bottomRuteHtml = [];
            (data.bottom_rute || []).forEach((item, idx) => {
                bottomRuteHtml.push(`
                    <li>
                        <span>${idx + 1}. ${item.kd_rute}</span>
                        <span class="dash-pill">${item.total_faktur} Faktur</span>
                    </li>
                `);
            });
            if (bottomRuteHtml.length === 0) {
                bottomRuteHtml.push('<li><span class="text-muted">Belum ada data</span></li>');
            }
            $('#dash-bottom-rute-list').html(bottomRuteHtml.join(''));

            $('#dash-periode-label').text('Periode: ' + formatRangeLabel(range.start, range.end));
        }

        function showDashboardError() {
            $('#dash-total-terkirim').text('0');
            $('#dash-total-pending').text('0');
            $('#dash-total-driver').text('0');
            $('#dash-total-faktur').text('Total faktur: 0');
            $('#dash-top-driver-list').html('<li><span class="text-danger">Gagal memuat data</span></li>');
            $('#dash-top-rute-list').html('<li><span class="text-danger">Gagal memuat data</span></li>');
            $('#dash-bottom-rute-list').html('<li><span class="text-danger">Gagal memuat data</span></li>');
            fakturChart.data.labels = [moment().format('DD/MM')];
            fakturChart.data.datasets[0].data = [0];
            fakturChart.update();
            driverChart.data.labels = [];
            driverChart.data.datasets[0].data = [];
            driverChart.update();
        }

        function loadDashboard(rangeKey) {
            const range = getRange(rangeKey);

            $.ajax({
                url: "<?= base_url('logistik/distibusi/ajax_dashboard_distribusi') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    start: range.start,
                    end: range.end,
                    ket_status: $('#dash-ket-status').val(),
                    rute: $('#dash-rute').val()
                },
                success: function(res) {
                    if (res && res.status) {
                        renderDashboard(res, range);
                        return;
                    }
                    showDashboardError();
                },
                error: function() {
                    showDashboardError();
                }
            });
        }

        $('.dash-filter .btn').on('click', function() {
            $('.dash-filter .btn').removeClass('active');
            $(this).addClass('active');
            loadDashboard($(this).data('range'));
        });

        $('#dash-ket-status').on('change', function() {
            const activeRange = $('.dash-filter .btn.active').data('range') || 'today';
            const ket = $(this).val();
            const $rute = $('#dash-rute');
            const current = $rute.val();

            $rute.find('option').each(function() {
                const val = $(this).val();
                if (!val) {
                    $(this).prop('hidden', false);
                    return;
                }
                const ks = $(this).data('ket-status') || '';
                if (!ket || ks === ket) {
                    $(this).prop('hidden', false);
                } else {
                    $(this).prop('hidden', true);
                }
            });

            if (current && $rute.find('option[value="' + current + '"]').prop('hidden')) {
                $rute.val('');
            }

            loadDashboard(activeRange);
        });

        $('#dash-rute').on('change', function() {
            const activeRange = $('.dash-filter .btn.active').data('range') || 'today';
            loadDashboard(activeRange);
        });

        $('#dash-range').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Clear'
            }
        });

        $('#dash-range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(
                picker.startDate.format('YYYY-MM-DD') +
                ' - ' +
                picker.endDate.format('YYYY-MM-DD')
            );
            $('.dash-filter .btn').removeClass('active');
            loadDashboard('custom');
        });

        $('#dash-range').on('cancel.daterangepicker', function() {
            $(this).val('');
            const activeRange = $('.dash-filter .btn.active').data('range') || 'today';
            loadDashboard(activeRange);
        });

        loadDashboard('today');
    });
</script>

<script>
    $('#tbtotal_tonase').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
</script>

<script>
    $(function() {
        $('#filter_tanggal').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Clear'
            }
        });

        $('#filter_tanggal').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(
                picker.startDate.format('YYYY-MM-DD') +
                ' - ' +
                picker.endDate.format('YYYY-MM-DD')
            ).trigger('change');
        });

        $('#filter_tanggal').on('cancel.daterangepicker', function() {
            $(this).val('').trigger('change');
        });

        $('#filter_tanggal_driver').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Clear'
            }
        });

        $('#filter_tanggal_driver').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(
                picker.startDate.format('YYYY-MM-DD') +
                ' - ' +
                picker.endDate.format('YYYY-MM-DD')
            ).trigger('change');
        });

        $('#filter_tanggal_driver').on('cancel.daterangepicker', function() {
            $(this).val('').trigger('change');
        });
    });
</script>

<script>
    $(document).ready(function() {

        function loadDriverReady() {
            let tanggal = $('#filter_tanggal').val();
            let rute = $('#filter_rute').val();

            if (!tanggal || !rute) return;

            $('#tbody_ready').html(`
            <tr>
                <td colspan="2" class="text-center text-muted">Memuat data…</td>
            </tr>
        `);

            $.ajax({
                url: "<?= base_url('logistik/distibusi/driver_ready') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    tanggal: tanggal,
                    rute: rute
                },
                success: function(res) {
                    let html = '';

                    if (res.length === 0) {
                        html = `
                        <tr>
                            <td colspan="2" class="text-center text-danger">
                                Tidak ada driver tersedia
                            </td>
                        </tr>`;
                    } else {
                        res.forEach(d => {
                            html += `
                            <tr>
                                <td>${d.nama_driver}</td>
                                <td>
                                    <span class="badge bg-success">READY</span>
                                </td>
                            </tr>`;
                        });
                    }

                    $('#tbody_ready').html(html);
                }
            });
        }

        $('#filter_tanggal, #filter_rute').on('change', loadDriverReady);
    });
</script>


<script>
    $(function() {

        function loadDistribusi() {
            $.ajax({
                url: "<?= base_url('logistik/distibusi/get_ploting_rute') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    rute: $('#s_rute').val(),
                    tanggal: $('#filter_tanggal').val()
                },
                success: function(res) {
                    let html = '';

                    if (res.length === 0) {
                        html = `<tr>
                                <td colspan="2" class="text-center">
                                    Data tidak ditemukan
                                </td>
                            </tr>`;
                    } else {
                        res.forEach(row => {
                            html += `
                            <tr>
                                <td>${row.nama}</td>
                                <td>${row.tanggal_pengiriman}</td>
                            </tr>`;
                        });
                    }
                    $('#result_data').html(html);
                }
            });
        }
        $('#s_rute, #filter_tanggal').on('change', loadDistribusi);
    });
</script>

<script>
    $('#tbody_driver').html(`
        <tr>
            <td colspan="100%" class="text-center text-muted">
                Memuat data…
            </td>
        </tr>
        `);

    $(function() {

        function loadMatrix() {
            $.ajax({
                url: "<?= base_url('logistik/distibusi/driver_rute_matrix') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    tanggal: $('#filter_tanggal_driver').val()
                },
                success: function(res) {

                    let thead = `<tr><th>Driver</th>`;
                    res.rute.forEach(r => {
                        thead += `<th>${r.kd_rute}</th>`;
                    });
                    thead += `</tr>`;
                    $('#thead_rute').html(thead);

                    let tbody = '';
                    res.data.forEach(d => {
                        tbody += `<tr><td>${d.nama_driver}</td>`;
                        res.rute.forEach(r => {
                            let val = d.rute[r.kd_rute] ?? 0;

                            if (val > 0) {
                                tbody += `<td><span class="cell-active">${val}</span></td>`;
                            } else {
                                tbody += `<td class="cell-zero">0</td>`;
                            }
                        });
                        tbody += `</tr>`;
                    });

                    $('#tbody_driver').html(tbody);
                }
            });
        }
        $('#filter_tanggal_driver').on('change', loadMatrix);
    });
</script>
