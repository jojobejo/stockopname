<?php $portal_asset_base = preg_replace('#^https?:#', '', base_url()); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#071a35">
    <title>KarismaERP Portal Apps</title>
    <link rel="icon" href="<?= $portal_asset_base ?>assets/images/Karisma.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;family=Space+Grotesk:wght@500;600;700&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $portal_asset_base ?>assets/plugins/fontawesome-free/css/all.min.css">
    <style>
        :root {
            --navy: #071a35;
            --blue: #1769ff;
            --cyan: #00c2ff;
            --ink: #10213b;
            --muted: #64748b;
            --line: rgba(32, 86, 158, .13);
            --surface: rgba(255, 255, 255, .92);
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body.portal-page {
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            color: var(--ink);
            font-family: "Manrope", sans-serif;
            background:
                radial-gradient(circle at 8% 4%, rgba(0, 194, 255, .12), transparent 23rem),
                radial-gradient(circle at 92% 34%, rgba(23, 105, 255, .11), transparent 26rem),
                linear-gradient(180deg, #f7fbff 0%, #eef5ff 56%, #f8fbff 100%);
        }

        body.portal-page::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: .42;
            background-image:
                linear-gradient(rgba(23, 105, 255, .055) 1px, transparent 1px),
                linear-gradient(90deg, rgba(23, 105, 255, .055) 1px, transparent 1px);
            background-size: 52px 52px;
            mask-image: linear-gradient(to bottom, #000, transparent 82%);
        }

        .portal-shell {
            position: relative;
            z-index: 1;
            width: min(1440px, calc(100% - 40px));
            margin: 0 auto;
            padding: 30px 0 80px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--navy);
            font-family: "Space Grotesk", sans-serif;
            font-weight: 700;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            padding: 7px;
            object-fit: contain;
            border: 1px solid rgba(23, 105, 255, .13);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(28, 72, 132, .12);
        }

        .system-status {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 9px 14px;
            color: #16745a;
            border: 1px solid rgba(24, 179, 127, .18);
            border-radius: 999px;
            background: rgba(236, 253, 245, .82);
            font-size: .76rem;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c78a;
            box-shadow: 0 0 0 5px rgba(34, 199, 138, .12);
            animation: pulse 2.2s ease-out infinite;
        }

        .portal-hero {
            position: relative;
            display: grid;
            grid-template-columns: minmax(0, 1.3fr) minmax(310px, .7fr);
            min-height: 390px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 32px;
            background:
                radial-gradient(circle at 78% 15%, rgba(0, 194, 255, .22), transparent 15rem),
                linear-gradient(120deg, #071a35 0%, #0a2d62 58%, #0b4c83 100%);
            box-shadow: 0 30px 80px rgba(7, 26, 53, .2);
        }

        .hero-copy {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 58px clamp(30px, 5vw, 72px);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            align-self: flex-start;
            gap: 9px;
            padding: 8px 13px;
            color: #a8eaff;
            border: 1px solid rgba(148, 224, 255, .23);
            border-radius: 999px;
            background: rgba(0, 194, 255, .09);
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .portal-title {
            max-width: 760px;
            margin: 23px 0 16px;
            color: #fff;
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(2.35rem, 5vw, 4.65rem);
            font-weight: 700;
            letter-spacing: -.055em;
            line-height: .98;
        }

        .portal-title span {
            color: transparent;
            background: linear-gradient(90deg, #84e7ff, #63a9ff);
            background-clip: text;
            -webkit-background-clip: text;
        }

        .portal-subtitle {
            max-width: 650px;
            margin: 0;
            color: rgba(229, 240, 255, .72);
            font-size: clamp(.95rem, 1.4vw, 1.08rem);
            line-height: 1.8;
        }

        .hero-visual {
            position: relative;
            min-height: 390px;
            isolation: isolate;
        }

        .hero-visual::before,
        .hero-visual::after {
            content: "";
            position: absolute;
            left: 50%;
            top: 50%;
            border: 1px solid rgba(117, 210, 255, .2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }

        .hero-visual::before { width: 290px; height: 290px; animation: spin 22s linear infinite; }
        .hero-visual::after { width: 210px; height: 210px; border-style: dashed; animation: spinReverse 17s linear infinite; }

        .visual-core {
            position: absolute;
            z-index: 2;
            left: 50%;
            top: 50%;
            display: grid;
            width: 112px;
            height: 112px;
            padding: 19px;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, .3);
            border-radius: 32px;
            background: rgba(255, 255, 255, .96);
            box-shadow: 0 0 0 12px rgba(84, 196, 255, .08), 0 22px 60px rgba(0, 0, 0, .3);
            transform: translate(-50%, -50%) rotate(-6deg);
            animation: float 5s ease-in-out infinite;
        }

        .visual-core img { width: 100%; height: 100%; object-fit: contain; }

        .orbit-node {
            position: absolute;
            z-index: 3;
            display: grid;
            width: 48px;
            height: 48px;
            place-items: center;
            color: #d9f6ff;
            border: 1px solid rgba(152, 226, 255, .25);
            border-radius: 16px;
            background: rgba(7, 37, 77, .72);
            box-shadow: 0 14px 34px rgba(0, 0, 0, .22), inset 0 1px rgba(255, 255, 255, .12);
            backdrop-filter: blur(12px);
        }

        .node-one { left: 12%; top: 22%; animation: float 4.4s ease-in-out infinite; }
        .node-two { right: 12%; top: 20%; animation: float 5.1s .7s ease-in-out infinite; }
        .node-three { left: 16%; bottom: 18%; animation: float 4.8s .3s ease-in-out infinite; }
        .node-four { right: 10%; bottom: 20%; animation: float 5.3s 1s ease-in-out infinite; }

        .apps-section { padding-top: 54px; }

        .section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 34px;
        }

        .section-label {
            margin: 0 0 9px;
            color: var(--blue);
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .section-title {
            margin: 0;
            color: var(--navy);
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(1.65rem, 3vw, 2.35rem);
            letter-spacing: -.035em;
        }

        .app-counter {
            flex: 0 0 auto;
            color: var(--muted);
            font-size: .88rem;
            font-weight: 700;
        }

        .app-counter strong { color: var(--blue); font-size: 1.5rem; }

        .portal-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            column-gap: 24px;
            row-gap: 56px;
            align-items: stretch;
        }

        .portal-card {
            --accent: #1769ff;
            position: relative;
            display: flex;
            min-width: 0;
            min-height: 318px;
            padding: 26px;
            flex-direction: column;
            overflow: hidden;
            color: inherit;
            border: 1px solid var(--line);
            border-radius: 24px;
            background: var(--surface);
            box-shadow: 0 18px 45px rgba(25, 64, 116, .09);
            text-decoration: none;
            isolation: isolate;
            transition: transform .28s cubic-bezier(.2, .8, .2, 1), border-color .28s ease, box-shadow .28s ease;
            animation: cardIn .65s both;
            animation-delay: calc(var(--index) * 80ms);
        }

        .portal-card::before {
            content: "";
            position: absolute;
            z-index: -1;
            width: 190px;
            height: 190px;
            right: -95px;
            top: -100px;
            border-radius: 50%;
            background: var(--accent);
            opacity: .08;
            filter: blur(2px);
            transition: transform .35s ease, opacity .35s ease;
        }

        .portal-card::after {
            content: "";
            position: absolute;
            left: 26px;
            right: 26px;
            bottom: 0;
            height: 3px;
            border-radius: 3px 3px 0 0;
            background: var(--accent);
            opacity: .65;
            transform: scaleX(.35);
            transform-origin: left;
            transition: transform .3s ease;
        }

        .portal-card:hover,
        .portal-card:focus-visible {
            z-index: 2;
            color: inherit;
            border-color: color-mix(in srgb, var(--accent) 38%, transparent);
            box-shadow: 0 28px 60px rgba(20, 58, 111, .16);
            outline: none;
            text-decoration: none;
            transform: translateY(-8px);
        }

        .portal-card:hover::before { opacity: .14; transform: scale(1.14); }
        .portal-card:hover::after { transform: scaleX(1); }

        .card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 28px;
        }

        .portal-icon-wrap { position: relative; }

        .portal-icon-wrap::after {
            content: "";
            position: absolute;
            inset: 9px -9px -9px 9px;
            z-index: -1;
            border-radius: 20px;
            background: var(--accent);
            opacity: .1;
        }

        .portal-icon {
            display: grid;
            width: 64px;
            height: 64px;
            place-items: center;
            color: #fff;
            border-radius: 20px;
            background: linear-gradient(145deg, color-mix(in srgb, var(--accent) 78%, white), var(--accent));
            box-shadow: 0 14px 30px color-mix(in srgb, var(--accent) 25%, transparent), inset 0 1px rgba(255,255,255,.36);
            font-size: 1.5rem;
        }

        .app-number {
            color: rgba(48, 78, 117, .32);
            font-family: "Space Grotesk", sans-serif;
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .12em;
        }

        .portal-card h3 {
            margin: 0 0 10px;
            color: var(--navy);
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.22rem;
            font-weight: 700;
            letter-spacing: -.025em;
        }

        .portal-card p {
            margin: 0;
            color: var(--muted);
            font-size: .9rem;
            line-height: 1.7;
        }

        .card-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: auto;
            padding-top: 26px;
            color: var(--navy);
            font-size: .83rem;
            font-weight: 800;
        }

        .arrow-button {
            display: grid;
            width: 39px;
            height: 39px;
            place-items: center;
            color: var(--accent);
            border: 1px solid color-mix(in srgb, var(--accent) 18%, transparent);
            border-radius: 13px;
            background: color-mix(in srgb, var(--accent) 8%, white);
            transition: color .25s ease, background .25s ease, transform .25s ease;
        }

        .portal-card:hover .arrow-button { color: #fff; background: var(--accent); transform: translateX(3px); }

        .portal-footer {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 58px;
            padding-top: 23px;
            color: #7b8ba1;
            border-top: 1px solid rgba(37, 85, 145, .11);
            font-size: .78rem;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { margin-top: 0; }
            50% { margin-top: -10px; }
        }

        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        @keyframes spinReverse {
            to { transform: translate(-50%, -50%) rotate(-360deg); }
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(34, 199, 138, .28); }
            70%, 100% { box-shadow: 0 0 0 8px rgba(34, 199, 138, 0); }
        }

        @media (min-width: 768px) {
            .portal-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                column-gap: 28px;
                row-gap: 62px;
            }
        }

        @media (min-width: 1200px) {
            .portal-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
                column-gap: 30px;
                row-gap: 68px;
            }
        }

        @media (max-width: 900px) {
            .portal-hero { grid-template-columns: 1fr; }
            .hero-copy { padding-bottom: 34px; }
            .hero-visual { display: none; }
        }

        @media (max-width: 575px) {
            .portal-shell { width: min(100% - 24px, 1440px); padding-top: 18px; }
            .topbar { align-items: flex-start; }
            .brand { max-width: 230px; font-size: .86rem; }
            .brand-logo { width: 40px; height: 40px; }
            .system-status { padding: 8px 10px; font-size: .62rem; }
            .portal-hero { min-height: 0; border-radius: 24px; }
            .hero-copy { padding: 38px 24px 40px; }
            .portal-title { font-size: 2.55rem; }
            .apps-section { padding-top: 42px; }
            .section-heading { align-items: flex-start; margin-bottom: 28px; }
            .section-title { font-size: 1.55rem; }
            .app-counter { display: none; }
            .portal-grid { column-gap: 13px; row-gap: 42px; }
            .portal-card { min-height: 282px; padding: 18px; border-radius: 20px; }
            .card-top { margin-bottom: 22px; }
            .portal-icon { width: 54px; height: 54px; border-radius: 17px; font-size: 1.25rem; }
            .app-number { font-size: .66rem; }
            .portal-card h3 { font-size: 1rem; }
            .portal-card p { font-size: .78rem; line-height: 1.55; }
            .card-action { padding-top: 20px; font-size: .72rem; }
            .arrow-button { width: 34px; height: 34px; border-radius: 11px; }
            .portal-footer { flex-direction: column; margin-top: 48px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation: none !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body class="portal-page">
<main class="portal-shell">
    <header class="topbar">
        <div class="brand">
            <img class="brand-logo" src="<?= $portal_asset_base ?>assets/images/Karisma.png" alt="Logo Karisma">
            <span>PT. Karisma Indoagro Universal</span>
        </div>
        <div class="system-status"><span class="status-dot"></span> System Online</div>
    </header>

    <section class="portal-hero" aria-labelledby="portal-title">
        <div class="hero-copy">
            <div class="eyebrow"><i class="fas fa-layer-group"></i> Digital Ecosystem</div>
            <h1 class="portal-title" id="portal-title">KarismaERP<br><span>Portal Apps.</span></h1>
            <p class="portal-subtitle">Satu gerbang menuju seluruh ekosistem digital Karisma. Pilih aplikasi untuk memulai pekerjaan dengan lebih cepat dan terhubung.</p>
        </div>
        <div class="hero-visual" aria-hidden="true">
            <div class="visual-core"><img src="<?= $portal_asset_base ?>assets/images/Karisma.png" alt=""></div>
            <div class="orbit-node node-one"><i class="fas fa-cubes"></i></div>
            <div class="orbit-node node-two"><i class="fas fa-chart-line"></i></div>
            <div class="orbit-node node-three"><i class="fas fa-shield-alt"></i></div>
            <div class="orbit-node node-four"><i class="fas fa-boxes"></i></div>
        </div>
    </section>

    <section class="apps-section" aria-labelledby="apps-title">
        <div class="section-heading">
            <div>
                <p class="section-label">Application Directory</p>
                <h2 class="section-title" id="apps-title">Pilih ruang kerja Anda</h2>
            </div>
            <div class="app-counter"><strong><?= (int)$app_count ?></strong> aplikasi tersedia</div>
        </div>

        <div class="portal-grid">
            <?php foreach ($apps as $index => $app): ?>
                <a class="portal-card"
                   style="--accent: <?= html_escape($app['accent']) ?>; --index: <?= (int)$index ?>;"
                   href="<?= html_escape($app['url']) ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   aria-label="Buka <?= html_escape($app['name']) ?> di tab baru">
                    <div class="card-top">
                        <div class="portal-icon-wrap">
                            <div class="portal-icon"><i class="fas <?= html_escape($app['icon']) ?>"></i></div>
                        </div>
                        <span class="app-number"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div>
                        <h3><?= html_escape($app['name']) ?></h3>
                        <p><?= html_escape($app['description']) ?></p>
                    </div>
                    <div class="card-action">
                        <span>Buka Aplikasi</span>
                        <span class="arrow-button"><i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <footer class="portal-footer">
        <span>&copy; <?= date('Y') ?> PT. Karisma Indoagro Universal</span>
    </footer>
</main>
</body>
</html>
