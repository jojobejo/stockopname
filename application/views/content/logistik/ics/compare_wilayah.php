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
                    <div class="row">
                        <div class="col-auto">
                            <a href="<?= base_url('compare_opname') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">

                            <table class="table table-bordered" id="tbdetailwilyah">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Expired Date</th>
                                        <th>Qty Inputer 1</th>
                                        <th>Qty Inputer 2 </th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list as $list) : ?>
                                        <tr>
                                            <td><?= $list->nama_barang ?></td>
                                            <td><?= $list->exp_date ?></td>
                                            <td><?= $list->fisik_tim1 ?></td>
                                            <td><?= $list->fisik_tim2 ?></td>
                                            <?php if ($list->fisik_tim1 == $list->fisik_tim2) : ?>
                                                <td><a href="#" class="btn btn-sm btn-success"></a></td>
                                            <?php else : ?>
                                                <td><a href="#" class="btn btn-sm btn-danger"></a></td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </section>
            </div>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0
            </div>
        </footer>
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>