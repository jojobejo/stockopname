<script>
    $(document).on('click', '.gudang-btn', function() {
        let idGudang = $(this).data('id');

        $('#cardTable').removeClass('d-none').hide().fadeIn(300);
        $('#tbodyBarang').html(`
        <tr>
            <td colspan="5" class="text-center">Loading data...</td>
        </tr>
    `);
        $('.gudang-btn[data-id="<?= $id_gudang_induk ?>"]').addClass('bg-success');


        $.ajax({
            url: "<?= base_url('ics/ajax_barang_pergudang') ?>",
            type: "POST",
            data: {
                id_gudang: idGudang
            },
            dataType: "json",
            success: function(res) {
                let html = '';

                if (res.length === 0) {
                    html = `
                    <tr>
                        <td colspan="5" class="text-center">Data kosong</td>
                    </tr>
                `;
                } else {
                    $.each(res, function(i, v) {
                        html += `
                        <tr>
                            <td>${v.nama_barang}</td>
                            <td>${v.exp_date}</td>
                            <td>${v.saldo_available}</td>
                            <td>${v.qty_box}</td>
                            <td>${v.qty_pcs}</td>
                        </tr>
                    `;
                    });
                }

                $('#tbodyBarang').hide().html(html).fadeIn(200);
            }
        });
    });
</script>