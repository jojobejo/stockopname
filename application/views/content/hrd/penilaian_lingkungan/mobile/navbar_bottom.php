<?php
$active = $mobile_active ?? '';
$items = [
    ['key' => 'home', 'label' => 'Home', 'icon' => 'fas fa-home', 'url' => site_url('mobile-erp')],
    ['key' => 'transaksi', 'label' => 'Form', 'icon' => 'fas fa-clipboard-list', 'url' => site_url('penilaian_lingkungan')],
    ['key' => 'laporan', 'label' => 'Laporan', 'icon' => 'fas fa-chart-line', 'url' => site_url('mobile-erp/list')],
    ['key' => 'profile', 'label' => 'Profile', 'icon' => 'far fa-user', 'url' => site_url('mobile-erp/profile')],
];
?>
<nav class="bottom-nav" aria-label="Navigasi utama mobile">
    <?php foreach ($items as $item) : ?>
        <a href="<?= $item['url'] ?>" class="bottom-nav-link <?= $active === $item['key'] ? 'active' : '' ?>">
            <i class="<?= $item['icon'] ?>"></i>
            <span><?= $item['label'] ?></span>
        </a>
    <?php endforeach; ?>
</nav>
