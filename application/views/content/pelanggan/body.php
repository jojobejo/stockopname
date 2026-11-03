<body class="hold-transition layout-top-nav bgipkp">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>


        <div class="content-wrapper bgipkp">

            <!-- Main content -->
            <div class="content">
                <?php date_default_timezone_set('Asia/Jakarta');  ?>
                <div class="container">
                    <input type="text" value="<?= date("Y-m-d") ?>" name="tglinput" id="tglinput" hidden>
                    <input type="text" value="<?= date("H:i") ?>" name="jminput" id="" hidden>
                    <span id="jam" class="sjam"></span>
                    <?php  ?>

                    <span class="sdate"><?= format_indo(date("Y-m-d")) ?></span>

                    <div class="row divgbrindek" style="text-align: center;">
                        <a href="<?= base_url('nilai_ipkp/1') ?>" class="tmbl1"></a>
                        <a href="<?= base_url('nilai_ipkp/2') ?>" class="tmbl2"></a>
                        <a href="<?= base_url('nilai_ipkp/3') ?>" class="tmbl3"></a>
                        <a href="<?= base_url('nilai_ipkp/4') ?>" class="tmbl4"></a>
                        <a href="<?= base_url('nilai_ipkp/5') ?>" class="tmbl5"></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>


    <aside class="control-sidebar control-sidebar-dark">
    </aside>
    </div>

    <script type="text/javascript">
        window.onload = function() {
            jam();
        }

        function jam() {
            var e = document.getElementById('jam'),
                d = new Date(),
                h, m, s;
            h = d.getHours();
            m = set(d.getMinutes());
            s = set(d.getSeconds());

            if (h < 10) {
                h = '0' + h;
            }

            e.innerHTML = h + ':' + m + ':' + s;

            setTimeout('jam()', 1000);
        }

        function set(e) {
            e = e < 10 ? '0' + e : e;
            return e;
        }
    </script>