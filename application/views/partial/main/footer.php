<!-- jQuery -->
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url('assets/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url('assets/plugins/jquery-knob/jquery.knob.min.js') ?>"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url('assets/plugins/moment/moment.min.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/daterangepicker/daterangepicker.js') ?>"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo base_url('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
<!-- Summernote -->
<script src="<?php echo base_url('assets/plugins/summernote/summernote-bs4.min.js') ?>"></script>
<!-- overlayScrollbars -->
<script src="<?php echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
<!-- DataTables -->
<script src="<?php echo base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/jszip/jszip.min.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/pdfmake/pdfmake.min.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/pdfmake/vfs_fonts.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
<!-- bs-custom-file-input -->
<script src="<?php echo base_url('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js') ?>"></script>
<!-- SweetAlert2 -->
<script src="<?php echo base_url('assets/plugins/sweetalert2/sweetalert2.all.js') ?>"></script>
<!-- Select2 -->
<script src="<?php echo base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/dist/js/adminlte.js') ?>"></script>

<script>
  $(document).ready(function() {
    $('#list_tb_opname').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "<?php echo site_url('stockopname'); ?>",
        type: "POST"
      }
    });
  });
</script>

<script>
  $(function() {
    $('#tb_schedule').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });


    $('#tbpricelist').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

    $('#tbics_erp_diff').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

    $('#fkcashonsite').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

    // $('#tbics_byallbarang').DataTable({
    //   "paging": true,
    //   "lengthChange": false,
    //   "searching": true,
    //   "ordering": false,
    //   "info": true,
    //   "autoWidth": false,
    //   "responsive": true,
    // });

    $('#tb_masterbr_ics').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

    $('#tb_ics_do').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

    // $('#tb_ics_po').DataTable({
    //   "paging": true,
    //   "lengthChange": false,
    //   "searching": true,
    //   "ordering": false,
    //   "info": true,
    //   "autoWidth": false,
    //   "responsive": true,
    // });
    $('#tbgudang').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
    $('#dailyod').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
    $('#tracking_input_ics_byexp').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
    $('#ics_do_byexp').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
    $('#ics_po_byexp').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
    $('#tbics_pic').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
    $('#tbfakturbintang').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
    $('#forminputer').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
    $('#tbDashboardLogistik').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
   
    $('#lsfakturbyrute').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

    $('#tabelBongkaran').DataTable({
        "paging": true, "lengthChange": true, "searching": true,
        "ordering": false, "info": true, "autoWidth": false, "responsive": true,
    });
    $('#tabelKK').DataTable({
        "paging": true, "lengthChange": true, "searching": true,
        "ordering": false, "info": true, "autoWidth": false, "responsive": true,
    });
    $('#tabelLK').DataTable({
        "paging": true, "lengthChange": true, "searching": true,
        "ordering": false, "info": true, "autoWidth": false, "responsive": true,
    });
    $('#tabelArsip').DataTable({
        "paging": true, "lengthChange": true, "searching": true,
        "ordering": false, "info": true, "autoWidth": false, "responsive": true,
    });
    $('#tabelArsipKK').DataTable({
        "paging": true, "lengthChange": true, "searching": true,
        "ordering": false, "info": true, "autoWidth": false, "responsive": true,
    });
    $('#tabelArsipLK').DataTable({
        "paging": true, "lengthChange": true, "searching": true,
        "ordering": false, "info": true, "autoWidth": false, "responsive": true,
    });
</script>

<script type="text/javascript">
  var table;
  $(document).ready(function() {

    //datatables
    table = $('#tb_qty').DataTable({

      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": "<?= base_url('get_data_a') ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [0],
        "orderable": false,
      }, ],
    });



  });
</script>

<script>
  $(document).ready(function() {

    $('#tbics_erpss').DataTable({
      "pageLength": 10,
      "lengthMenu": [
        [10, 20, -1],
        [10, 20, "All"]
      ],
      "ordering": true,
      "responsive": true
    });

    $('#tbfilterpic').DataTable({
      "paging": false,
      "searching": false,
      "info": false
    });
  });

  console.log("CLICK:", $(this).data('ids'));

  $(document).on('click', '.btn-edit-pic', function(e) {

    e.preventDefault();

    let ids = $(this).data('ids');

    ids = ids.toString();
    const arr = ids.split(',');

    $('#edit_id').val(arr[0]);
    $('#edit_list_id').val(ids);

    $('#edit_nama_barang').val($(this).data('namabarang'));
    $('#edit_lokasi').val($(this).data('lokasi'));
    $('#kd_barang').val($(this).data('kdbarang'));
    $('#expdate').val($(this).data('exp'));

    $('#modalEditPIC').modal('show');
  });
</script>

</script>

<script>
  $(function() {
    const table = $('#tableGudang').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "<?= base_url('ics/gudang_list') ?>",
        type: "POST"
      },
      columns: [{
          data: null,
          render: (d, t, r, m) => m.row + m.settings._iDisplayStart + 1
        },
        {
          data: "nama_gudang"
        },
        {
          data: "tipe"
        },
        {
          data: null,
          orderable: false,
          render: function(row) {
            return `
                        <a href="<?= base_url('ics/detail_wilayah/') ?>${row.id_gudang}"
                           class="btn btn-sm btn-info">
                            Wilayah
                        </a>
                        <button class="btn btn-sm btn-danger btn-hapus"
                                data-id="${row.id_gudang}">
                            Hapus
                        </button>
                    `;
          }
        }
      ]
    });

    // aksi hapus
    $('#tableGudang').on('click', '.btn-hapus', function() {
      const id = $(this).data('id');

      if (!confirm('Hapus gudang ini? Data tidak bisa dikembalikan.')) return;

      $.ajax({
        url: "<?= base_url('ics/hapus_gudang') ?>",
        type: "POST",
        data: {
          id_gudang: id
        },
        dataType: "json",
        success: function(res) {
          if (res.status === 'success') {
            table.ajax.reload(null, false);
          } else {
            alert(res.message || 'Gagal menghapus data');
          }
        },
        error: function() {
          alert('Server error. Infrastruktur lagi capek.');
        }
      });
    });
  });
</script>

<script>
  $('#formGudang').submit(function(e) {
    e.preventDefault();
    $.post("<?= base_url('ics/gudang_save') ?>", $(this).serialize(), function() {
      $('#modalGudang').modal('hide');
      $('#tableGudang').DataTable().ajax.reload();
    });
  });
</script>



</body>

</html>