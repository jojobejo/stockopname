<script>
    $(document).ready(function() {
        const $tableBody = $('#tbl_faktur_not_list tbody');

        function escapeHtml(value) {
            return $('<div>').text(value == null ? '' : value).html();
        }

        function renderRows(rows) {
            if (!rows || rows.length === 0) {
                return `
                    <tr>
                        <td colspan="9" class="text-center text-muted">Data tidak ditemukan</td>
                    </tr>
                `;
            }

            let html = '';
            rows.forEach((row, index) => {
                const tgl = escapeHtml(row.tgl_inputer_fmt ? row.tgl_inputer_fmt : '-');
                const faktur = escapeHtml(row.kd_faktur ? row.kd_faktur : '-');
                const customer = escapeHtml(row.nama_customer ? row.nama_customer : '-');
                const kios = escapeHtml(row.nama_kios ? row.nama_kios : '-');
                const kdBarang = escapeHtml(row.kd_barang ? row.kd_barang : '-');
                const namaBarang = escapeHtml(row.nama_barang ? row.nama_barang : '-');
                const totalRow = row.total_row ? row.total_row : 0;

                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${tgl}</td>
                        <td>${faktur}</td>
                        <td>${customer}</td>
                        <td>${kios}</td>
                        <td>${kdBarang}</td>
                        <td>${namaBarang}</td>
                        <td>${totalRow}</td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-warning btn-sm btn-edit-kd-barang"
                                data-kd-faktur="${faktur}"
                                data-old-kd-barang="${kdBarang}">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            return html;
        }

        function resetDataTable() {
            if ($.fn.DataTable.isDataTable('#tbl_faktur_not_list')) {
                $('#tbl_faktur_not_list').DataTable().destroy();
            }
        }

        function initDataTable() {
            $('#tbl_faktur_not_list').DataTable({
                paging: true,
                lengthChange: false,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true
            });
        }

        function loadData() {
            resetDataTable();
            $tableBody.html(`
                <tr>
                    <td colspan="9" class="text-center text-muted">Memuat data...</td>
                </tr>
            `);

            $.ajax({
                url: "<?= base_url('ajax_view_faktur_not_list') ?>",
                type: "GET",
                dataType: "json",
                success: function(res) {
                    const rows = res && res.data ? res.data : [];
                    $tableBody.html(renderRows(rows));
                    initDataTable();
                },
                error: function() {
                    $tableBody.html(`
                        <tr>
                            <td colspan="9" class="text-center text-danger">Gagal memuat data dari server</td>
                        </tr>
                    `);
                }
            });
        }

        $(document).on('click', '.btn-edit-kd-barang', function() {
            const kdFaktur = $(this).data('kd-faktur');
            const oldKdBarang = $(this).data('old-kd-barang');

            $('#edit_kd_faktur').val(kdFaktur);
            $('#edit_old_kd_barang').val(oldKdBarang);
            $('#edit_kd_faktur_label').val(kdFaktur);
            $('#edit_old_kd_barang_label').val(oldKdBarang);
            $('#edit_new_kd_barang').val('');
            $('#modal_edit_kd_barang_not_list').modal('show');
        });

        $('#btn_submit_edit_kd_barang').on('click', function() {
            const kdFaktur = $('#edit_kd_faktur').val();
            const oldKdBarang = $('#edit_old_kd_barang').val();
            const newKdBarang = $('#edit_new_kd_barang').val().trim();

            if (!newKdBarang) {
                alert('Kode barang baru wajib diisi');
                return;
            }

            $.ajax({
                url: "<?= base_url('ajax_update_kd_barang_not_list') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    kd_faktur: kdFaktur,
                    old_kd_barang: oldKdBarang,
                    new_kd_barang: newKdBarang
                },
                success: function(res) {
                    if (res && res.status) {
                        $('#modal_edit_kd_barang_not_list').modal('hide');
                        loadData();
                        return;
                    }
                    alert(res && res.message ? res.message : 'Gagal update kode barang');
                },
                error: function() {
                    alert('Gagal update kode barang ke server');
                }
            });
        });

        $('#btn_reload_faktur_not_list').on('click', loadData);
        loadData();
    });
</script>
