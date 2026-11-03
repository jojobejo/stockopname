        <?php $this->load->view('partial/mobile/navbar_bottom'); ?>
    </div>

    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3">
        <div id="mobileAppToast" class="toast mobile-toast" role="status" aria-live="polite" aria-atomic="true">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="fas fa-circle-check text-primary"></i>
                <span id="mobileAppToastMessage">Berhasil disimpan.</span>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.js') ?>"></script>
    <script>
        window.MobileERP = {
            baseUrl: '<?= base_url() ?>',
            siteUrl: '<?= site_url() ?>',
            urls: {
                submitIssue: '<?= site_url('hrd/penilaian_lingkungan/submit') ?>',
                issueList: '<?= site_url('hrd/penilaian_lingkungan/list') ?>',
                issueDetail: '<?= site_url('hrd/penilaian_lingkungan/detail') ?>',
                locationAjax: '<?= site_url('hrd/penilaian_lingkungan/locations') ?>'
            }
        };
    </script>
    <script src="<?= base_url('assets/js/mobile-erp.js') ?>"></script>
</body>

</html>
