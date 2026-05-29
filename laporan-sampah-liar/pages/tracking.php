<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/functions.php';
$pageTitle = APP_NAME . ' | Tracking';
include __DIR__ . '/../templates/header.php';

$kode = trim($_GET['kode'] ?? '');
$found = null;
$progress = [];

if ($kode !== '') {
    $stmt = $conn->prepare("SELECT * FROM laporan WHERE kode_laporan = ? LIMIT 1");
    $stmt->bind_param('s', $kode);
    $stmt->execute();
    $found = $stmt->get_result()->fetch_assoc();

    if ($found) {
        $stmt2 = $conn->prepare("SELECT * FROM progress_laporan WHERE laporan_id = ? ORDER BY created_at ASC");
        $stmt2->bind_param('i', $found['id']);
        $stmt2->execute();
        $progress = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

$recent = $conn->query("SELECT * FROM laporan ORDER BY created_at DESC LIMIT 6")->fetch_all(MYSQLI_ASSOC);
?>
<section class="py-5">
  <div class="container">
    <div class="glass-card p-4 p-lg-5 mb-4">
      <div class="row align-items-center g-3">
        <div class="col-lg-7">
          <div class="soft-pill mb-3"><i class="bi bi-search"></i> Tracking laporan</div>
          <h1 class="section-title mb-2">Cek status laporan secara cepat</h1>
          <p class="text-muted mb-0">Masukkan kode laporan untuk melihat posisi penanganan, timeline progress, dan foto pembaruan dari admin.</p>
        </div>
        <div class="col-lg-5">
          <form class="d-flex gap-2" method="get">
            <input type="text" class="form-control" name="kode" value="<?= e($kode); ?>" placeholder="Masukkan kode laporan">
            <button class="btn btn-success px-4">Cari</button>
          </form>
        </div>
      </div>
    </div>

    <?php if ($kode !== '' && $found): ?>
      <div class="row g-4">
        <div class="col-lg-5">
          <div class="glass-card p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <div class="text-muted small">Kode laporan</div>
                <div class="h4 fw-bold mb-0"><?= e($found['kode_laporan']); ?></div>
              </div>
              <span class="badge rounded-pill px-3 py-2 <?= badge_status($found['status']); ?>"><?= e(label_status($found['status'])); ?></span>
            </div>
            <div class="timeline-card p-3">
              <div class="timeline">
                <div class="timeline-item"><div class="fw-semibold">Laporan diterima</div><small class="text-muted"><?= format_date_id($found['created_at']); ?></small></div>
                <div class="timeline-item"><div class="fw-semibold">Sedang diverifikasi</div><small class="text-muted">Menunggu tindak lanjut admin</small></div>
                <div class="timeline-item"><div class="fw-semibold">Penanganan selesai</div><small class="text-muted">Akan terisi saat status berubah</small></div>
              </div>
            </div>

            <hr class="my-4">

            <div class="small text-muted mb-2">Detail singkat</div>
            <ul class="list-unstyled mb-0 small">
              <li class="mb-2"><i class="bi bi-person me-2 text-success"></i><?= e($found['nama_pelapor']); ?></li>
              <li class="mb-2"><i class="bi bi-telephone me-2 text-success"></i><?= e($found['no_hp']); ?></li>
              <li class="mb-2"><i class="bi bi-envelope me-2 text-success"></i><?= e($found['email']); ?></li>
              <li class="mb-2"><i class="bi bi-tag me-2 text-success"></i><?= e(label_jenis($found['jenis_sampah'])); ?></li>
              <li class="mb-2"><i class="bi bi-geo-alt me-2 text-success"></i><?= e($found['alamat']); ?></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="glass-card p-4 mb-4">
            <div class="soft-pill mb-3"><i class="bi bi-journal-text"></i> Progress pekerjaan</div>
            <div class="timeline">
              <?php if ($progress): foreach ($progress as $row): ?>
                <div class="timeline-item">
                  <div class="fw-semibold"><?= e($row['keterangan']); ?></div>
                  <small class="text-muted"><?= format_date_id($row['created_at']); ?></small>
                  <?php if (!empty($row['foto_progress'])): ?>
                    <div class="mt-2">
                      <img src="<?= base_url('uploads/progress/' . e($row['foto_progress'])); ?>" class="img-fluid rounded-4 border" style="max-height:240px;object-fit:cover;">
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; else: ?>
                <div class="timeline-item">
                  <div class="fw-semibold">Belum ada progress tambahan</div>
                  <small class="text-muted">Admin belum mengunggah pembaruan.</small>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <div class="glass-card p-4">
            <div class="soft-pill mb-3"><i class="bi bi-image"></i> Foto pelaporan</div>
            <?php if (!empty($found['foto'])): ?>
              <img src="<?= base_url('uploads/laporan/' . e($found['foto'])); ?>" class="img-fluid rounded-4 border" style="width:100%;max-height:420px;object-fit:cover;">
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="glass-card p-4 p-lg-5">
        <div class="row g-4 align-items-start">
          <div class="col-lg-7">
            <h3 class="fw-bold mb-3">Laporan terbaru</h3>
            <div class="row g-3">
              <?php foreach ($recent as $item): ?>
                <div class="col-md-6">
                  <div class="info-card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <div class="fw-bold"><?= e($item['kode_laporan']); ?></div>
                        <div class="small text-muted"><?= e($item['nama_pelapor']); ?></div>
                      </div>
                      <span class="badge rounded-pill <?= badge_status($item['status']); ?>"><?= e(label_status($item['status'])); ?></span>
                    </div>
                    <div class="small text-muted mt-2"><?= e($item['alamat']); ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="step-box">
              <h5 class="fw-bold mb-3">Cara tracking</h5>
              <ol class="small text-muted mb-0">
                <li class="mb-2">Buka halaman tracking.</li>
                <li class="mb-2">Masukkan kode laporan dari hasil submit.</li>
                <li class="mb-2">Lihat status, timeline, dan foto progress.</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/../templates/footer.php'; ?>
