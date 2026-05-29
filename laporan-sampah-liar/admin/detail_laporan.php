<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
auth_check_remember();
auth_require();

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM laporan WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row) {
    redirect(base_url('admin/laporan.php'));
}

$stmt2 = $conn->prepare("SELECT * FROM progress_laporan WHERE laporan_id = ? ORDER BY created_at ASC");
$stmt2->bind_param('i', $id);
$stmt2->execute();
$progress = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Detail Laporan | ' . APP_NAME;
include __DIR__ . '/../templates/header.php';
?>
<div class="admin-layout">
  <?php include __DIR__ . '/../templates/sidebar.php'; ?>
  <main class="admin-main">
    <div class="admin-topbar mb-4 d-flex justify-content-between align-items-center">
      <div>
        <div class="text-muted small">Detail laporan</div>
        <h4 class="fw-bold mb-0"><?= e($row['kode_laporan']); ?></h4>
      </div>
      <a href="<?= base_url('admin/laporan.php'); ?>" class="btn btn-outline-success">Kembali</a>
    </div>

    <div class="row g-4">
      <div class="col-lg-7">
        <div class="glass-card p-4">
          <div class="row g-3">
            <div class="col-md-6"><div class="step-box"><div class="text-muted small">Pelapor</div><div class="fw-semibold"><?= e($row['nama_pelapor']); ?></div></div></div>
            <div class="col-md-6"><div class="step-box"><div class="text-muted small">Status</div><div class="fw-semibold"><span class="badge rounded-pill <?= badge_status($row['status']); ?>"><?= e(label_status($row['status'])); ?></span></div></div></div>
            <div class="col-md-6"><div class="step-box"><div class="text-muted small">Email</div><div class="fw-semibold"><?= e($row['email']); ?></div></div></div>
            <div class="col-md-6"><div class="step-box"><div class="text-muted small">No HP</div><div class="fw-semibold"><?= e($row['no_hp']); ?></div></div></div>
            <div class="col-12"><div class="step-box"><div class="text-muted small">Alamat</div><div class="fw-semibold"><?= e($row['alamat']); ?></div></div></div>
            <div class="col-12"><div class="step-box"><div class="text-muted small">Deskripsi</div><div class="fw-semibold"><?= e($row['deskripsi']); ?></div></div></div>
          </div>

          <div class="mt-4">
            <h5 class="fw-bold mb-3">Foto Laporan</h5>
            <?php if (!empty($row['foto'])): ?>
              <img src="<?= base_url('uploads/laporan/' . e($row['foto'])); ?>" class="img-fluid rounded-4 border" style="max-height:420px;object-fit:cover;width:100%">
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="glass-card p-4 mb-4">
          <h5 class="fw-bold mb-3">Peta Lokasi</h5>
          <div id="detailMap" class="map-box" style="height:300px"></div>
        </div>
        <div class="glass-card p-4">
          <h5 class="fw-bold mb-3">Timeline Progress</h5>
          <div class="timeline">
            <div class="timeline-item">
              <div class="fw-semibold">Laporan masuk</div>
              <small class="text-muted"><?= format_date_id($row['created_at']); ?></small>
            </div>
            <?php foreach ($progress as $p): ?>
              <div class="timeline-item">
                <div class="fw-semibold"><?= e($p['keterangan']); ?></div>
                <small class="text-muted"><?= format_date_id($p['created_at']); ?></small>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const lat = parseFloat(<?= json_encode($row['latitude']); ?>);
    const lng = parseFloat(<?= json_encode($row['longitude']); ?>);

    if (!lat || !lng) {
        return;
    }

    const map = L.map('detailMap').setView([lat, lng], 15);

    L.tileLayer(
        'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
        {
            attribution: '&copy; OpenStreetMap & CartoDB'
        }
    ).addTo(map);

    L.marker([lat, lng])
        .addTo(map)
        .bindPopup(`
            <b><?= e($row['kode_laporan']); ?></b><br>
            <?= e($row['alamat']); ?>
        `)
        .openPopup();

});
</script>
<?php include __DIR__ . '/../templates/footer.php'; ?>
