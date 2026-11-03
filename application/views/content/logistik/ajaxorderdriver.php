<script type="text/javascript">
    $(document).ready(function() {
        initializeSelect2();
    });

    $(function() {
        $("#tb_list_order").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "aaSorting": [],
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
    var i = 1;
    $('#add').click(function() {
        i++;
        $('#driveroption').append('<div id="row' + i + '"><div class="row"><div class="col-sm-2"><label for="noInv" class="">Nama Driver</label><select class="form-control select2bs4 select2Driver" name="nm_driver_i[]" id="nm_driver_i[]"><option value="&nbsp" selected></option></select></div><div class="col-sm-1"><label for="noInv" class="">Kode Truk</label><input type="text" id="kd_truk_i[]" name="kd_truk_i[]" value="" class="form-control"></div><div class="col-sm-4"><label for="noInv" class="">Destinasi Kota</label><input type="text" id="destinsasi_i[]" name="destinsasi_i[]" value="" class="form-control"></div><div class="col-sm-4"><label for="noInv" class="">Nama Toko</label><input type="text" id="nm_toko_i[]" name="nm_toko_i[]" value="" class="form-control"></div><div class="col-sm-1"><label for="noInv" class=""></label><br><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove m-2"><i class="fas fa-ban"></i></button></div></div>');
        initializeSelect2();
    });
    $(document).on('click', '.btn_remove', function() {
        var button_id = $(this).attr("id");
        var res = confirm('Apakah akan menghapus field ini ? ');
        if (res == true) {
            $('#row' + button_id + '').remove();
            $('#' + button_id + '').remove();
        }
    });

    $(".kd_truk_i").select2({
        placeholder: "Kode Truk",
        theme: "bootstrap",
        allowClear: true,
        minimumInputLenght: 1,
        language: {
            inputTooShort: function(args) {

                return "2 or more symbol.";
            },
            noResults: function() {
                return "Not Found.";
            },
            searching: function() {
                return "Searching...";
            }
        },
        minimumInputLenght: 1,
        ajax: {
            url: '<?= base_url('get_kd_truk_order') ?>',
            type: "post",
            dataType: 'json',
            delay: 200,

            data: function(params) {
                return {
                    nm_truk: params.term

                };
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.nm_truk,
                            id: item.nm_truk
                        }
                    })
                };
            }
        }
    });
</script>