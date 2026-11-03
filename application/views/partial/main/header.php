<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?php echo base_url("assets/images/Karisma.png") ?>">
  <title><?php echo $page_title; ?></title>
  <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.1.0/chartjs-plugin-datalabels.min.js" integrity="sha512-Tfw6etYMUhL4RTki37niav99C6OHwMDB2iBT5S5piyHO+ltK2YX8Hjy9TXxhE1Gm/TmAV0uaykSpnHKFIAif/A==" crossorigin="anonymous" rferrerpolicy="no-referrer"></script> -->
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js">
  <link rel="stylesheet" href="https://code.jquery.com/jquery-3.7.0.js">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url("assets/plugins/fontawesome-free/css/all.min.css") ?>">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?php echo base_url("assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css") ?>">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url("assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css") ?>">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?php echo base_url("assets/plugins/jqvmap/jqvmap.min.css") ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url("assets/dist/css/adminlte.css") ?>">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo base_url("assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css") ?>">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url("assets/plugins/daterangepicker/daterangepicker.css") ?>">
  <!-- summernote -->
  <link rel="stylesheet" href="<?php echo base_url("assets/plugins/summernote/summernote-bs4.min.css") ?>">
  <!-- Datables -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">

  <!-- CHARTS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- fullCalendar -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fullcalendar/main.css') ?>">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/select2/css/select2.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
  <script src="<?= base_url('assets/plugins/jquery/jquery.js') ?>"></script>
  <!-- CSS Custom Karisma -->
  <link rel="stylesheet" href="<?php echo base_url("assets/ipkp.css") ?>">
  <!-- CSS HEADER KPI -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous">
  <!-- END CSS HEADER KPI -->
  <script>
    $(function() {
      $("#tgl1, #tgl2").datepicker({
        dateFormat: "dd/mm/yy"
      });
    });
  </script>
</head>