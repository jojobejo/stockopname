<script>
    $(function() {
        $("#tb_inventaris").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        $("#owner_isi").select2({
            placeholder: "Cari Nama Pengguna ... ",
            theme: 'bootstrap4',
            allowClear: true,
            minimumInputLenght: 1,
            dropdownParent: $("#addInventaris"),
            language: {
                inputTooShort: function(args) {

                    return "Input 2 Huruf atau lebih";
                },
                noResults: function() {
                    return "Tidak Ditemukan .. ";
                },
                searching: function() {
                    return "Mencari .... ";
                }
            },
            minimumInputLenght: 1,
            ajax: {
                url: '<?= base_url('selectowner') ?>',
                type: "post",
                dataType: 'json',
                delay: 200,

                data: function(params) {
                    return {
                        kd_user: params.term

                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.nama_user,
                                id: item.kode_user
                            }
                        })
                    };
                }
            }
        });

    });
</script>