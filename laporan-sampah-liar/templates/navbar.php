<?php
$active = basename($_SERVER['PHP_SELF'] ?? 'home.php');
?>
<nav class="navbar navbar-expand-lg navbar-dark navbar-glass sticky-top">
    <div class="container py-1">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= base_url('index.php'); ?>">
            <span class="brand-mark"><i class="bi bi-recycle"></i></span>
            <span><?= e(APP_NAME); ?></span>
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link <?= $active==='home.php'?'active':''; ?>" href="<?= base_url('pages/home.php'); ?>">Beranda</a></li>
                <li class="nav-item"><a class="nav-link <?= $active==='laporan.php'?'active':''; ?>" href="<?= base_url('pages/laporan.php'); ?>">Laporkan Sampah</a></li>
                <li class="nav-item"><a class="nav-link <?= $active==='tracking.php'?'active':''; ?>" href="<?= base_url('pages/tracking.php'); ?>">Tracking</a></li>
                <li class="nav-item"><a class="nav-link <?= $active==='about.php'?'active':''; ?>" href="<?= base_url('pages/about.php'); ?>">Tentang</a></li>
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-light btn-sm rounded-pill px-3 fw-semibold" href="<?= base_url('admin/login.php'); ?>">
                        <i class="bi bi-shield-lock me-1"></i> Admin
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
