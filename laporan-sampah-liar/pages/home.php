<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/functions.php';
$pageTitle = APP_NAME . ' | Beranda';
include __DIR__ . '/../templates/header.php';

$total = (int)($conn->query("SELECT COUNT(*) c FROM laporan")->fetch_assoc()['c'] ?? 0);
$selesai = (int)($conn->query("SELECT COUNT(*) c FROM laporan WHERE status='selesai'")->fetch_assoc()['c'] ?? 0);
$diproses = (int)($conn->query("SELECT COUNT(*) c FROM laporan WHERE status='diproses'")->fetch_assoc()['c'] ?? 0);
$menunggu = (int)($conn->query("SELECT COUNT(*) c FROM laporan WHERE status='menunggu'")->fetch_assoc()['c'] ?? 0);
?>
<section class="hero">
  <div class="container position-relative">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="eyebrow mb-4"><i class="bi bi-shield-check"></i> Platform pelaporan warga yang cepat dan transparan</div>
        <h1 class="display-4 fw-black lh-1 mb-4">Laporkan Sampah Liar dengan lebih cepat, rapi, dan terpantau.</h1>
        <p class="lead text-white-75 mb-4">Warga dapat melaporkan tumpukan sampah, TPS liar, atau titik pembuangan ilegal lengkap dengan foto, lokasi GPS, dan tracking status penanganan.</p>
        <div class="d-flex flex-wrap gap-3">
          <a href="<?= base_url('pages/laporan.php'); ?>" class="btn btn-success btn-lg px-4"><i class="bi bi-megaphone me-2"></i>Laporkan Sampah</a>
          <a href="<?= base_url('pages/tracking.php'); ?>" class="btn btn-outline-light btn-lg px-4"><i class="bi bi-search me-2"></i>Tracking Laporan</a>
        </div>

        <div class="row g-3 mt-5">
          <div class="col-6 col-md-3"><div class="hero-card p-3 text-center"><div class="h4 fw-bold mb-0"><?= $total; ?></div><small>Total</small></div></div>
          <div class="col-6 col-md-3"><div class="hero-card p-3 text-center"><div class="h4 fw-bold mb-0"><?= $selesai; ?></div><small>Selesai</small></div></div>
          <div class="col-6 col-md-3"><div class="hero-card p-3 text-center"><div class="h4 fw-bold mb-0"><?= $diproses; ?></div><small>Diproses</small></div></div>
          <div class="col-6 col-md-3"><div class="hero-card p-3 text-center"><div class="h4 fw-bold mb-0"><?= $menunggu; ?></div><small>Menunggu</small></div></div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="hero-card p-4 p-lg-5">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <div class="text-white-50 small">Dashboard cepat</div>
              <div class="h4 fw-bold mb-0">Visualisasi Laporan</div>
            </div>
            <span class="badge text-bg-light text-success rounded-pill px-3 py-2">Live Monitoring</span>
          </div>
          <div class="row g-3">
            <div class="col-md-7">
              <div class="info-card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <h5 class="mb-0 fw-bold text-muted">Alur Sistem</h5>
                  <i class="bi bi-arrow-right-circle text-success fs-4"></i>
                </div>
                <div class="d-grid gap-3">
                  <div class="step-box d-flex gap-3 align-items-start">
                    <div class="step-index">1</div>
                    <div><div class="fw-semibold">Warga isi form</div><small class="text-muted">Data, foto, dan lokasi otomatis.</small></div>
                  </div>
                  <div class="step-box d-flex gap-3 align-items-start">
                    <div class="step-index">2</div>
                    <div><div class="fw-semibold">Admin verifikasi</div><small class="text-muted">Status diubah menjadi diproses.</small></div>
                  </div>
                  <div class="step-box d-flex gap-3 align-items-start">
                    <div class="step-index">3</div>
                    <div><div class="fw-semibold">Progress & selesai</div><small class="text-muted">Foto progress dan timeline tersimpan.</small></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="info-card p-4 h-100">
                <h5 class="fw-bold text-muted">Fitur Utama</h5>
                <ul class="list-unstyled small mb-0">
                  <li class="mb-3 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Google Maps + GPS</li>
                  <li class="mb-3 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Captcha anti bot</li>
                  <li class="mb-3 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>DataTables admin</li>
                  <li class="mb-3 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Chart.js dashboard</li>
                  <li class="mb-3 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Upload foto aman</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="d-flex align-items-end justify-content-between mb-4">
      <div>
        <div class="soft-pill mb-2"><i class="bi bi-bar-chart-line"></i> Statistik laporan</div>
        <h2 class="section-title mb-0">Ringkasan cepat</h2>
      </div>
      <a href="<?= base_url('pages/about.php'); ?>" class="text-success fw-semibold">Pelajari sistem <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row g-4">
      <div class="col-md-3"><div class="stat-card p-4"><div class="icon mb-3"><i class="bi bi-list-check"></i></div><div class="text-muted small">Total Laporan</div><div class="kpi"><?= $total; ?></div></div></div>
      <div class="col-md-3"><div class="stat-card p-4"><div class="icon mb-3"><i class="bi bi-check2-circle"></i></div><div class="text-muted small">Laporan Selesai</div><div class="kpi"><?= $selesai; ?></div></div></div>
      <div class="col-md-3"><div class="stat-card p-4"><div class="icon mb-3"><i class="bi bi-gear-wide-connected"></i></div><div class="text-muted small">Diproses</div><div class="kpi"><?= $diproses; ?></div></div></div>
      <div class="col-md-3"><div class="stat-card p-4"><div class="icon mb-3"><i class="bi bi-hourglass-split"></i></div><div class="text-muted small">Menunggu</div><div class="kpi"><?= $menunggu; ?></div></div></div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="row g-4 align-items-stretch">
      <div class="col-lg-7">
        <div class="glass-card p-4 p-lg-5 h-100">
          <div class="soft-pill mb-3"><i class="bi bi-info-circle"></i> Penjelasan sistem</div>
          <h2 class="section-title">Dibuat untuk mempercepat respons kebersihan wilayah</h2>
          <p class="text-muted mt-3">Sistem ini dirancang agar warga bisa melapor dengan mudah, petugas bisa memantau dengan jelas, dan pimpinan bisa melihat data secara real-time melalui dashboard modern.</p>
          <div class="row g-3 mt-4">
            <div class="col-md-6"><div class="step-box h-100"><div class="fw-bold mb-2"><i class="bi bi-geo-alt text-success me-2"></i>Lokasi otomatis</div><div class="small text-muted">Koordinat dan alamat diambil dari GPS serta Leaflet.</div></div></div>
            <div class="col-md-6"><div class="step-box h-100"><div class="fw-bold mb-2"><i class="bi bi-image text-success me-2"></i>Foto kondisi</div><div class="small text-muted">Upload bukti visual dengan validasi format dan ukuran file.</div></div></div>
            <div class="col-md-6"><div class="step-box h-100"><div class="fw-bold mb-2"><i class="bi bi-shield-lock text-success me-2"></i>Keamanan</div><div class="small text-muted">Dilengkapi CSRF, captcha, sanitasi input, dan prepared statement.</div></div></div>
            <div class="col-md-6"><div class="step-box h-100"><div class="fw-bold mb-2"><i class="bi bi-graph-up-arrow text-success me-2"></i>Monitoring</div><div class="small text-muted">Statistik, grafik, dan status laporan terlihat jelas di admin panel.</div></div></div>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="glass-card p-4 p-lg-5 h-100">
          <div class="soft-pill mb-3"><i class="bi bi-lightning-charge"></i> Cara kerja singkat</div>
          <div class="timeline">
            <div class="timeline-item">
              <div class="fw-semibold">1. Warga mengisi form pelaporan</div>
              <div class="text-muted small">Input nama, kontak, jenis sampah, deskripsi, foto, dan lokasi.</div>
            </div>
            <div class="timeline-item">
              <div class="fw-semibold">2. Data masuk ke dashboard admin</div>
              <div class="text-muted small">Admin melakukan verifikasi dan menentukan status.</div>
            </div>
            <div class="timeline-item">
              <div class="fw-semibold">3. Progress pembersihan diunggah</div>
              <div class="text-muted small">Foto progress dan catatan tindak lanjut tersimpan.</div>
            </div>
            <div class="timeline-item">
              <div class="fw-semibold">4. Laporan ditandai selesai</div>
              <div class="text-muted small">Warga dapat memantau status hingga tuntas.</div>
            </div>
          </div>
          <a class="btn btn-success w-100 mt-4" href="<?= base_url('pages/laporan.php'); ?>">Mulai Laporkan</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="glass-card p-4 p-lg-5">
      <div class="row g-4 align-items-center">
        <div class="col-lg-8">
          <div class="soft-pill mb-3"><i class="bi bi-cpu"></i> Teknologi modern</div>
          <h2 class="section-title mb-3">Bootstrap 5, DataTables, Chart.js, SweetAlert2, dan Leaflet</h2>
          <p class="text-muted mb-0">Desain dibuat clean, profesional, dan cocok untuk aplikasi pemerintahan digital yang terlihat rapi di desktop maupun mobile.</p>
        </div>
        <div class="col-lg-4 text-lg-end">
          <a href="<?= base_url('pages/laporan.php'); ?>" class="btn btn-success btn-lg px-4">Laporkan Sekarang</a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../templates/footer.php'; ?>
