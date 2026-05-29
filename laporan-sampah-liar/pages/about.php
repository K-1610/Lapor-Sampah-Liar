<?php
require_once __DIR__ . '/../config/app.php';
$pageTitle = APP_NAME . ' | Tentang Sistem';
include __DIR__ . '/../templates/header.php';
?>
<section class="py-5">
  <div class="container">
    <div class="glass-card p-4 p-lg-5">
      <div class="row g-4 align-items-center">
        <div class="col-lg-8">
          <div class="soft-pill mb-3"><i class="bi bi-building"></i> Tentang sistem</div>
          <h1 class="section-title mb-3">Sistem pelaporan yang dibuat untuk membantu kebersihan lingkungan</h1>
          <p class="text-muted mb-0">Website ini dirancang untuk menerima laporan sampah liar secara cepat, memudahkan admin memproses laporan, dan memberi warga transparansi status penanganan.</p>
        </div>
        <div class="col-lg-4">
          <div class="stat-card p-4">
            <div class="icon mb-3"><i class="bi bi-clipboard-data"></i></div>
            <div class="fw-semibold">Fokus utama</div>
            <div class="text-muted small">Pelaporan, tracking, dashboard, dan monitoring kebersihan berbasis web modern.</div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4 mt-1">
      <div class="col-md-4"><div class="info-card p-4 h-100"><div class="fw-bold mb-2">Modern UI</div><div class="text-muted small">Menggunakan Bootstrap 5, visual clean, dan komponen yang nyaman dipakai di perangkat apa pun.</div></div></div>
      <div class="col-md-4"><div class="info-card p-4 h-100"><div class="fw-bold mb-2">Modular</div><div class="text-muted small">File dipisahkan ke config, helpers, templates, pages, admin, ajax, api, dan database.</div></div></div>
      <div class="col-md-4"><div class="info-card p-4 h-100"><div class="fw-bold mb-2">Aman</div><div class="text-muted small">Memakai prepared statement, CSRF token, validasi upload, dan captcha.</div></div></div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../templates/footer.php'; ?>
