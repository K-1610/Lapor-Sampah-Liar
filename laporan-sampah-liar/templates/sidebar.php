<?php
$active = basename($_SERVER['PHP_SELF'] ?? 'dashboard.php');
?>
<aside class="admin-sidebar">
    <div class="brand-box">
        <div class="brand-mark"><i class="bi bi-building-check"></i></div>
        <div>
            <div class="fw-bold">Admin Panel</div>
            <small class="text-white-50">Sampah Liar</small>
        </div>
    </div>

    <nav class="nav flex-column mt-4">
        <a class="nav-link <?= $active==='dashboard.php'?'active':''; ?>" href="<?= base_url('admin/dashboard.php'); ?>"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
        <a class="nav-link <?= $active==='laporan.php'?'active':''; ?>" href="<?= base_url('admin/laporan.php'); ?>"><i class="bi bi-file-earmark-text me-2"></i> Data Laporan</a>
        <a class="nav-link <?= $active==='progress.php'?'active':''; ?>" href="<?= base_url('admin/progress.php'); ?>"><i class="bi bi-journal-check me-2"></i> Progress</a>
        <a class="nav-link <?= $active==='users.php'?'active':''; ?>" href="<?= base_url('admin/users.php'); ?>"><i class="bi bi-people me-2"></i> Kelola User</a>
        <a class="nav-link" href="<?= base_url('admin/logout.php'); ?>"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
    </nav>
</aside>
