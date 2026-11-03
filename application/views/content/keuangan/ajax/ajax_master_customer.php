<script>
    $(function() {
        let mode = 'create';
        let currentId = 0;
        const hasSwal = typeof Swal !== 'undefined';

        function notify(icon, title, text) {
            if (hasSwal) {
                Swal.fire({
                    icon: icon,
                    title: title,
                    text: text
                });
            } else {
                alert((title ? title + ': ' : '') + text);
            }
        }

        const table = $('#tb_master_customer').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [],
            ajax: {
                url: "<?= base_url('master_customer/list') ?>",
                type: "POST"
            },
            columns: [{
                    data: 'kd_customer'
                },
                {
                    data: 'nama_customer'
                },
                {
                    data: 'nama_kios'
                },
                {
                    data: 'alamat_kios'
                },
                {
                    data: 'telp1'
                },
                {
                    data: 'telp2'
                },
                {
                    data: 'regional'
                },
                {
                    data: 'jam_buka_tutup'
                },
                {
                    data: 'karakteristik_kios'
                },
                {
                    data: 'aksi',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        function resetForm() {
            $('#formMasterCustomer')[0].reset();
            $('#customer_id').val('');
        }

        $('#btnTambahMasterCustomer').on('click', function() {
            mode = 'create';
            currentId = 0;
            resetForm();
            $('#modalMasterCustomerTitle').text('Tambah Master Customer');
            $('#modalMasterCustomer').modal('show');
        });

        $('#tb_master_customer').on('click', '.btn-edit-customer', function() {
            mode = 'edit';
            currentId = $(this).data('id');

            $.ajax({
                url: "<?= base_url('master_customer/detail') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    id: currentId
                },
                success: function(resp) {
                    if (!resp.status || !resp.data) {
                        notify('warning', 'Perhatian', resp.message || 'Data tidak ditemukan');
                        return;
                    }

                    $('#customer_id').val(resp.data.id);
                    $('#kd_customer').val(resp.data.kd_customer);
                    $('#nama_customer').val(resp.data.nama_customer);
                    $('#nama_kios').val(resp.data.nama_kios);
                    $('#alamat_kios').val(resp.data.alamat_kios);
                    $('#telp1').val(resp.data.telp1);
                    $('#telp2').val(resp.data.telp2);
                    $('#regional').val(resp.data.regional);
                    $('#jam_buka_tutup').val(resp.data.jam_buka_tutup);
                    $('#karakteristik_kios').val(resp.data.karakteristik_kios);

                    $('#modalMasterCustomerTitle').text('Edit Master Customer');
                    $('#modalMasterCustomer').modal('show');
                },
                error: function() {
                    notify('error', 'Gagal', 'Gagal mengambil detail data.');
                }
            });
        });

        $('#formMasterCustomer').on('submit', function(e) {
            e.preventDefault();

            const endpoint = mode === 'edit' ?
                "<?= base_url('master_customer/update') ?>" :
                "<?= base_url('master_customer/store') ?>";

            const payload = {
                id: mode === 'edit' ? currentId : '',
                kd_customer: $('#kd_customer').val(),
                nama_customer: $('#nama_customer').val(),
                nama_kios: $('#nama_kios').val(),
                alamat_kios: $('#alamat_kios').val(),
                telp1: $('#telp1').val(),
                telp2: $('#telp2').val(),
                regional: $('#regional').val(),
                jam_buka_tutup: $('#jam_buka_tutup').val(),
                karakteristik_kios: $('#karakteristik_kios').val()
            };

            $.ajax({
                url: endpoint,
                type: "POST",
                dataType: "json",
                data: payload,
                success: function(resp) {
                    if (!resp.status) {
                        notify('error', 'Gagal', resp.message || 'Proses gagal.');
                        return;
                    }

                    $('#modalMasterCustomer').modal('hide');
                    table.ajax.reload(null, false);
                    notify('success', 'Berhasil', resp.message || 'Data berhasil diproses.');
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan pada server.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    notify('error', 'Gagal', message);
                }
            });
        });

        $('#tb_master_customer').on('click', '.btn-delete-customer', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            const doDelete = function() {
                $.ajax({
                    url: "<?= base_url('master_customer/delete') ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        id: id
                    },
                    success: function(resp) {
                        if (!resp.status) {
                            notify('error', 'Gagal', resp.message || 'Gagal menghapus data.');
                            return;
                        }

                        table.ajax.reload(null, false);
                        notify('success', 'Berhasil', resp.message || 'Data berhasil dihapus.');
                    },
                    error: function() {
                        notify('error', 'Gagal', 'Gagal menghapus data.');
                    }
                });
            };

            if (!hasSwal) {
                if (confirm('Hapus data "' + nama + '" ?')) {
                    doDelete();
                }
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Hapus data "' + nama + '" ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    doDelete();
                }
            });
        });
    });
</script>
