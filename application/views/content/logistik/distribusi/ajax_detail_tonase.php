<script>
    $(document).ready(function() {
        const rute = <?= json_encode($rute) ?>;
        const $bodyTerkirim = $('#tbl_tonase_terkirim tbody');
        const $bodyPending = $('#tbl_tonase_pending tbody');

        function escapeHtml(value) {
            return $('<div>').text(value == null ? '' : value).html();
        }

        function resetDataTable(tableId) {
            if ($.fn.DataTable.isDataTable(tableId)) {
                $(tableId).DataTable().destroy();
            }
        }

        function initDataTable(tableId) {
            $(tableId).DataTable({
                paging: true,
                lengthChange: false,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true
            });
        }

        function renderRows(rows, emptyMessage) {
            if (!rows || rows.length === 0) {
                return '<tr><td colspan="5" class="text-center text-muted">' + emptyMessage + '</td></tr>';
            }

            let html = '';
            rows.forEach((row, index) => {
                const kdFaktur = escapeHtml(row.kd_faktur ? row.kd_faktur : '-');
                const kdCustomer = escapeHtml(row.kd_customer ? row.kd_customer : '-');
                const totalBarang = row.total_barang ? row.total_barang : 0;
                const totalTonase = row.total_tonase ? row.total_tonase : 0;

                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${kdFaktur}</td>
                        <td>${kdCustomer}</td>
                        <td>${totalBarang}</td>
                        <td>${totalTonase}</td>
                    </tr>
                `;
            });

            return html;
        }

        function loadDetailTonase() {
            resetDataTable('#tbl_tonase_terkirim');
            resetDataTable('#tbl_tonase_pending');
            $bodyTerkirim.html('<tr><td colspan="5" class="text-center text-muted">Memuat data...</td></tr>');
            $bodyPending.html('<tr><td colspan="5" class="text-center text-muted">Memuat data...</td></tr>');

            $.ajax({
                url: "<?= base_url('logistik/distibusi/ajax_detail_tonase_by_rute') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    rute: rute
                },
                success: function(res) {
                    const rows = res && res.data ? res.data : [];
                    const terkirim = rows.filter(row => row.keterangan_status === 'Terkirim');
                    const pending = rows.filter(row => row.keterangan_status !== 'Terkirim');

                    $('#count_terkirim').text(terkirim.length);
                    $('#count_pending').text(pending.length);

                    $bodyTerkirim.html(renderRows(terkirim, 'Data faktur terkirim tidak ditemukan'));
                    $bodyPending.html(renderRows(pending, 'Data faktur pending / belum terkirim tidak ditemukan'));

                    initDataTable('#tbl_tonase_terkirim');
                    initDataTable('#tbl_tonase_pending');
                },
                error: function() {
                    $bodyTerkirim.html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data dari server</td></tr>');
                    $bodyPending.html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data dari server</td></tr>');
                }
            });
        }

        loadDetailTonase();
    });
</script>
