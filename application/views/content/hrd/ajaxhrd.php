<script>
    $(function() {
        var table1;
        
        table1 = $('#tb_lap_distribusia').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "order": [],

            "ajax": {
                "url": "<?php echo site_url('get_server_lap_dis') ?>",
                "type": "POST"
            },

            "columnDefs": [{
                "targets": [0],
                "orderable": false,
            }, ],
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');


        $("#tb_lap_distribusi").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "aaSorting": [],
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        $("#tb_service_truk").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "aaSorting": [],
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>