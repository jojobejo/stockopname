<script>
    $(document).ready(function() {
        const $tableBody = $('#tbl_faktur_status tbody');

        function statusBadge(status) {
            if (status === '3') {
                return '<span class="badge badge-success">TERKIRIM</span>';
            }
            return '<span class="badge badge-warning">BELUM TERKIRIM</span>';
        }

        function escapeHtml(value) {
            return $('<div>').text(value == null ? '' : value).html();
        }

        function renderRows(rows, status) {
            if (!rows || rows.length === 0) {
                return `
                    <tr>
                        <td colspan="11" class="text-center text-muted">Data faktur tidak ditemukan</td>
                    </tr>
                `;
            }

            let html = '';
            rows.forEach((row, index) => {
                const tgl = escapeHtml(row.tgl_inputer_fmt ? row.tgl_inputer_fmt : '-');
                const faktur = escapeHtml(row.kd_faktur ? row.kd_faktur : '-');
                const kodeDo = escapeHtml(row.kode_do ? row.kode_do : '-');
                const customer = escapeHtml(row.kd_customer ? row.kd_customer : '-');
                const kios = escapeHtml(row.nama_kios ? row.nama_kios : '-');
                const rute = escapeHtml(row.kd_rute ? row.kd_rute : '-');
                const namaBarang = escapeHtml(row.nama_barang ? row.nama_barang : '-');
                const qty = escapeHtml(row.qty ? row.qty : '0');
                const tglExp = escapeHtml(row.tgl_exp ? row.tgl_exp : '-');
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${tgl}</td>
                        <td>${faktur}</td>
                        <td>${kodeDo}</td>
                        <td>${customer}</td>
                        <td>${kios}</td>
                        <td>${rute}</td>
                        <td>${namaBarang}</td>
                        <td>${qty}</td>
                        <td>${tglExp}</td>
                        <td>${statusBadge(status)}</td>
                    </tr>
                `;
            });
            return html;
        }

        function resetDataTable() {
            if ($.fn.DataTable.isDataTable('#tbl_faktur_status')) {
                $('#tbl_faktur_status').DataTable().destroy();
            }
        }

        function initDataTable() {
            $('#tbl_faktur_status').DataTable({
                paging: true,
                lengthChange: false,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true
            });
        }

        function loadFakturStatus() {
            const status = $('#filter_status_faktur').val();

            resetDataTable();
            $tableBody.html(`
                <tr>
                    <td colspan="11" class="text-center text-muted">Memuat data...</td>
                </tr>
            `);

            $.ajax({
                url: "<?= base_url('logistik/distibusi/ajax_list_faktur_status') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    status: status
                },
                success: function(res) {
                    const rows = res && res.data ? res.data : [];
                    $tableBody.html(renderRows(rows, status));
                    initDataTable();
                },
                error: function() {
                    $tableBody.html(`
                        <tr>
                            <td colspan="11" class="text-center text-danger">Gagal memuat data dari server</td>
                        </tr>
                    `);
                }
            });
        }

        $('#filter_status_faktur').on('change', loadFakturStatus);
        $('#btn_reload_status_faktur').on('click', loadFakturStatus);

        loadFakturStatus();
    });
</script>
