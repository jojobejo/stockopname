<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#2563eb">
    <link rel="icon" href="<?= base_url('assets/images/Karisma.png') ?>">
    <title><?= htmlspecialchars($page_title ?? 'Karisma ERP', ENT_QUOTES, 'UTF-8') ?></title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/mobile-erp.css') ?>">
</head>

<body class="mobile-erp-body">
    <div class="mobile-shell">
        <header class="mobile-topbar">
            <div class="brand-block">
                <img src="<?= base_url('assets/images/Karisma.png') ?>" alt="Karisma" class="brand-logo">
                <div>
                    <span class="app-eyebrow"><?= htmlspecialchars($module_label ?? 'Karisma ERP', ENT_QUOTES, 'UTF-8') ?></span>
                    <h1><?= htmlspecialchars($page_heading ?? $page_title ?? 'Mobile ERP', ENT_QUOTES, 'UTF-8') ?></h1>
                </div>
            </div>
            <div class="topbar-actions">
                <button class="icon-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSearchCanvas" aria-label="Cari dan filter">
                    <i class="fas fa-search"></i>
                </button>
                <button class="icon-btn has-dot" type="button" data-mobile-toast="3 notifikasi operasional baru" aria-label="Notifikasi">
                    <i class="far fa-bell"></i>
                </button>
                <a href="<?= site_url('mobile-erp/profile') ?>" class="avatar-btn" aria-label="Profil">
                    <?= htmlspecialchars(substr($this->session->userdata('username') ?: 'KU', 0, 2), ENT_QUOTES, 'UTF-8') ?>
                </a>
            </div>
        </header>

        <?php $this->load->view('partial/mobile/sidebar'); ?>
