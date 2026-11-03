<script>
    $(function() {
        $("#tb_plat").DataTable({
            "responsive": true,
            "lengthChange": false,
            "aaSorting": [],
            "autoWidth": false,
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        $("#tb_driver").DataTable({
            "responsive": true,
            "lengthChange": false,
            "aaSorting": [],
            "autoWidth": false,
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        $("#tb_det_list_driver_order").DataTable({
            "responsive": true,
            "lengthChange": false,
            "aaSorting": [],
            "autoWidth": false,
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        $("#tb_tmp_distribusi").DataTable({
            "responsive": true,
            "lengthChange": false,
            "aaSorting": [],
            "autoWidth": false,
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
</script>