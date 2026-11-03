<script>
    $('#filter_tanggal').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD'
        }
    });

    $('#btnFilter').on('click', function() {
        $.get('<?= base_url("ics/ajax_filter_mutasi") ?>', {
            gudang: $('#filter_gudang').val(),
            tanggal: $('#filter_tanggal').val()
        }, function(html) {
            $('#mutasi_barang tbody').html(html);
        });
    });
</script>

<script>
    console.log('ajax_mutasi loaded');

    $(document).on('click', '.btn-detail', function() {
        let noreff = $(this).data('id');

        $.ajax({
            url: '<?= base_url("ics/ajax_detail_mutasi") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                noreff
            },
            success: function(res) {
                if (!res.status) {
                    Swal.fire('Gagal', res.msg, 'error');
                    return;
                }

                let h = res.header;

                $('#infoMutasi').html(`
                <table class="table table-sm table-borderless">
                    <tr><td><b>No Ref</b></td><td>: ${h.noreff}</td></tr>
                    <tr><td><b>Tanggal</b></td><td>: ${h.tgl_transaksi}</td></tr>
                    <tr><td><b>Dari Gudang</b></td><td>: ${h.nama_gudang_asal}</td></tr>
                    <tr><td><b>Ke Gudang</b></td><td>: ${h.nama_gudang_tujuan}</td></tr>
                    <tr>
                        <td><b>Status</b></td>
                        <td>: <span class="badge badge-${badgeStatus(h.status)}">${h.status}</span></td>
                    </tr>
                </table>
            `);

                let rows = '';
                res.detail.forEach(d => {
                    rows += `
                    <tr>
                        <td>${d.kode_barang}</td>
                        <td>${d.nama_barang}</td>
                        <td>${d.exp_date}</td>
                        <td class="text-right">${d.qty}</td>
                    </tr>`;
                });

                $('#detailMutasiBody').html(rows);
                $('#modalDetailMutasi').modal('show');
            }
        });
    });

    $(document).on('click', '.btn-unpost', function() {
        let noreff = $(this).data('id');

        Swal.fire({
            title: 'UNPOST Mutasi?',
            text: noreff,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, UNPOST'
        }).then((res) => {
            if (res.isConfirmed) {
                $.post('<?= base_url("ics/ajax_unpost_mutasi") ?>', {
                    noreff
                }, function(r) {
                    Swal.fire(r.msg, '', r.status ? 'success' : 'error');
                    if (r.status) location.reload();
                }, 'json');
            }
        });
    });


    $(document).on('click', '.btn-delete', function() {
        let noreff = $(this).data('id');

        Swal.fire({
            title: 'HAPUS MUTASI?',
            text: 'Data akan dihapus permanen',
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Hapus'
        }).then((res) => {
            if (res.isConfirmed) {
                $.post('<?= base_url("ics/ajax_delete_mutasi") ?>', {
                    noreff
                }, function(r) {
                    Swal.fire(r.msg, '', r.status ? 'success' : 'error');
                    if (r.status) location.reload();
                }, 'json');
            }
        });
    });


    $(document).on('click', '.btn-rollback', function(e) {
        e.preventDefault();

        let noreff = $(this).data('id');
        console.log('rollback', noreff);

        Swal.fire({
            title: 'ROLLBACK MUTASI?',
            text: 'Barang akan dikembalikan ke gudang 2',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Rollback'
        }).then((res) => {
            if (res.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("ics/ajax_rollback_mutasi") ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        noreff
                    },
                    success: function(r) {
                        Swal.fire(r.msg, '', r.status ? 'success' : 'error');
                        if (r.status) location.reload();
                    }
                });
            }
        });
    });



    function badgeStatus(status) {
        if (status === 'POSTED') return 'success';
        if (status === 'HOLD') return 'warning';
        if (status === 'ROLLBACK') return 'danger';
        return 'secondary';
    }
</script>