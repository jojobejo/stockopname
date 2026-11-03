<script>
    $(function() {
        let mode = 'create';
        let currentId = 0;
        const hasSwal = typeof Swal !== 'undefined';
        const endpointBase = "<?= base_url($this->uri->uri_string() === 'purchase/listBarang' ? 'purchase/listBarang' : 'master_barang') ?>";

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

        const table = $('#tb_master_barang').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [],
            ajax: {
                url: endpointBase + "/list",
                type: "POST"
            },
            columns: [{
                    data: 'kode_barang'
                },
                {
                    data: 'nama_barang'
                },
                {
                    data: 'bahan_aktif'
                },
                {
                    data: 'satuan'
                },
                {
                    data: 'berat'
                },
                {
                    data: 'kubikasi'
                },
                {
                    data: 'dimensi'
                },
                {
                    data: 'aksi',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        function resetForm() {
            $('#formMasterBarang')[0].reset();
            $('#master_id').val('');
        }

        $('#btnTambahMasterBarang').on('click', function() {
            mode = 'create';
            currentId = 0;
            resetForm();
            $('#modalMasterBarangTitle').text('Tambah Master Barang');
            $('#modalMasterBarang').modal('show');
        });

        $('#tb_master_barang').on('click', '.btn-edit-master', function() {
            mode = 'edit';
            currentId = $(this).data('id');

            $.ajax({
                url: endpointBase + "/detail",
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

                    $('#master_id').val(resp.data.id);
                    $('#kode_barang').val(resp.data.kode_barang);
                    $('#nama_barang').val(resp.data.nama_barang);
                    $('#bahan_aktif').val(resp.data.bahan_aktif);
                    $('#satuan').val(resp.data.satuan);
                    $('#berat').val(resp.data.berat);
                    $('#kubikasi').val(resp.data.kubikasi);
                    $('#p').val(resp.data.p);
                    $('#l').val(resp.data.l);
                    $('#t').val(resp.data.t);

                    $('#modalMasterBarangTitle').text('Edit Master Barang');
                    $('#modalMasterBarang').modal('show');
                },
                error: function() {
                    notify('error', 'Gagal', 'Gagal mengambil detail data.');
                }
            });
        });

        $('#formMasterBarang').on('submit', function(e) {
            e.preventDefault();

            const endpoint = mode === 'edit' ?
                endpointBase + "/update" :
                endpointBase + "/store";

            const payload = {
                id: mode === 'edit' ? currentId : '',
                kode_barang: $('#kode_barang').val(),
                nama_barang: $('#nama_barang').val(),
                bahan_aktif: $('#bahan_aktif').val(),
                satuan: $('#satuan').val(),
                berat: $('#berat').val(),
                kubikasi: $('#kubikasi').val(),
                p: $('#p').val(),
                l: $('#l').val(),
                t: $('#t').val()
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

                    $('#modalMasterBarang').modal('hide');
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

        $('#tb_master_barang').on('click', '.btn-delete-master', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            const doDelete = function() {
                $.ajax({
                    url: endpointBase + "/delete",
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
