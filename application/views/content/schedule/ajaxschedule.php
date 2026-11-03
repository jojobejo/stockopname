<script>
    $(function() {
        $("#tbtamu").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "aaSorting": [],
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
</script>