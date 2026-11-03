<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <section class="content">

                    <div class="row m-2">
                        <?php foreach ($gudang as $gdg) : ?>
                            <div class="col-md-2 col-sm-6 mb-3">
                                <div class="small-box bg-info gudang-btn" data-id="<?= $gdg->id_gudang ?>" style="cursor:pointer;">
                                    <div class="inner">
                                        <h5><?= $gdg->nama_gudang ?></h5>
                                        <p>Wilayah <?= $gdg->tipe ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-warehouse"></i>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="card d-none" id="cardTable">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm w-100" id="tb_pergudang">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Expired Date</th>
                                            <th>Qty Tersedia</th>
                                            <th>Qty Box</th>
                                            <th>Qty Pcs</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyBarang"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </div>

        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <script>
        let tbPergudang = null;

        function initDataTable() {
            if ($.fn.DataTable.isDataTable('#tb_pergudang')) {
                $('#tb_pergudang').DataTable().destroy();
            }

            tbPergudang = $('#tb_pergudang').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
                pageLength: 10,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampil _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    paginate: {
                        next: "Next",
                        previous: "Prev"
                    },
                    zeroRecords: "Data tidak ditemukan"
                }
            });
        }

        $(document).on('click', '.gudang-btn', function() {
            let idGudang = $(this).data('id');

            $('.gudang-btn').removeClass('bg-success');
            $(this).addClass('bg-success');

            $('#cardTable').removeClass('d-none').hide().fadeIn(150);

            if ($.fn.DataTable.isDataTable('#tb_pergudang')) {
                $('#tb_pergudang').DataTable().clear().destroy();
            }

            $('#tbodyBarang').html(`
        <tr>
            <td colspan="5" class="text-center">Loading data...</td>
        </tr>
    `);

            $.ajax({
                url: "<?= base_url('ics/ajax_barang_pergudang') ?>",
                type: "POST",
                data: {
                    id_gudang: idGudang
                },
                dataType: "json",
                success: function(res) {
                    let html = '';

                    if (!res || res.length === 0) {
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
                            <td class="text-right">${v.qty}</td>
                            <td class="text-right">${v.qty_box}</td>
                            <td class="text-right">${v.qty_pcs}</td>
                        </tr>
                    `;
                        });
                    }

                    $('#tbodyBarang').html(html);
                    initDataTable();
                }
            });
        });

        $(document).ready(function() {
            let defaultGudang = "<?= $id_gudang_induk ?>";

            if (defaultGudang) {
                $('.gudang-btn[data-id="' + defaultGudang + '"]').trigger('click');
                $('.gudang-btn').removeClass('bg-success');
                $('.gudang-btn[data-id="' + defaultGudang + '"]').addClass('bg-success');
            }
        });
    </script>