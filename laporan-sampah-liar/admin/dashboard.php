<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
auth_check_remember();
auth_require();

$pageTitle = 'Dashboard Admin | ' . APP_NAME;
include __DIR__ . '/../templates/header.php';

$auth = auth_user();

$total = (int)($conn->query("SELECT COUNT(*) c FROM laporan")->fetch_assoc()['c'] ?? 0);
$selesai = (int)($conn->query("SELECT COUNT(*) c FROM laporan WHERE status='selesai'")->fetch_assoc()['c'] ?? 0);
$diproses = (int)($conn->query("SELECT COUNT(*) c FROM laporan WHERE status='diproses'")->fetch_assoc()['c'] ?? 0);
$hariini = (int)($conn->query("SELECT COUNT(*) c FROM laporan WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['c'] ?? 0);

$monthly = [];
for ($i = 11; $i >= 0; $i--) {
    $monthKey = date('Y-m', strtotime("-$i months"));
    $stmt = $conn->prepare("SELECT COUNT(*) c FROM laporan WHERE DATE_FORMAT(created_at, '%Y-%m') = ?");
    $stmt->bind_param('s', $monthKey);
    $stmt->execute();
    $monthly[] = (int)($stmt->get_result()->fetch_assoc()['c'] ?? 0);
}

$statusCounts = [
    (int)($conn->query("SELECT COUNT(*) c FROM laporan WHERE status='menunggu'")->fetch_assoc()['c'] ?? 0),
    $diproses,
    $selesai
];

$typeCounts = [];
$types = ['organik','anorganik','campuran','b3','tps_ilegal'];
foreach ($types as $t) {
    $stmt = $conn->prepare("SELECT COUNT(*) c FROM laporan WHERE jenis_sampah = ?");
    $stmt->bind_param('s', $t);
    $stmt->execute();
    $typeCounts[] = (int)($stmt->get_result()->fetch_assoc()['c'] ?? 0);
}
?>
<div class="admin-layout">
  <?php include __DIR__ . '/../templates/sidebar.php'; ?>
  <main class="admin-main">
    <div class="admin-topbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
      <div>
        <div class="text-muted small">Selamat datang</div>
        <h4 class="fw-bold mb-0"><?= e($auth['nama'] ?? 'Admin'); ?></h4>
      </div>
      <div class="text-muted small"><?= date('l, d F Y'); ?></div>
    </div>

    <?php if ($msg = flash_get('success')): ?>
      <div class="alert alert-success"><?= e($msg); ?></div>
    <?php endif; ?>

    <div class="row g-4 mb-4">
      <div class="col-md-6 col-xl-3">
        <div class="metric">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small">Total Laporan</div>
              <div class="h2 fw-bold mb-0"><?= $total; ?></div>
            </div>
            <div class="icon"><i class="bi bi-file-earmark-text"></i></div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-xl-3">
        <div class="metric">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small">Selesai</div>
              <div class="h2 fw-bold mb-0"><?= $selesai; ?></div>
            </div>
            <div class="icon"><i class="bi bi-check2-circle"></i></div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-xl-3">
        <div class="metric">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small">Diproses</div>
              <div class="h2 fw-bold mb-0"><?= $diproses; ?></div>
            </div>
            <div class="icon"><i class="bi bi-gear"></i></div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-xl-3">
        <div class="metric">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small">Hari Ini</div>
              <div class="h2 fw-bold mb-0"><?= $hariini; ?></div>
            </div>
            <div class="icon"><i class="bi bi-calendar3"></i></div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4 mb-4">
      <div class="col-lg-8">
        <div class="glass-card p-4">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0">Grafik Laporan Per Bulan</h5>
            <span class="badge text-bg-light text-success">12 bulan terakhir</span>
          </div>
          <canvas id="chartMonthly" height="110"></canvas>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="glass-card p-4 h-100">
          <h5 class="fw-bold mb-3">Status Laporan</h5>
          <canvas id="chartStatus"></canvas>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-5">
        <div class="glass-card p-4 h-100">
          <h5 class="fw-bold mb-3">Jenis Sampah</h5>
          <canvas id="chartJenis"></canvas>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="glass-card p-4 h-100">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Laporan Terbaru</h5>
            <a class="text-success fw-semibold" href="<?= base_url('admin/laporan.php'); ?>">Lihat semua</a>
          </div>
          <?php
            $recent = $conn->query("SELECT * FROM laporan ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
          ?>
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead><tr><th>Kode</th><th>Pelapor</th><th>Status</th><th>Tanggal</th></tr></thead>
              <tbody>
                <?php foreach ($recent as $r): ?>
                  <tr>
                    <td><?= e($r['kode_laporan']); ?></td>
                    <td><?= e($r['nama_pelapor']); ?></td>
                    <td><span class="badge rounded-pill <?= badge_status($r['status']); ?>"><?= e(label_status($r['status'])); ?></span></td>
                    <td><?= format_date_id($r['created_at']); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

<script>
const monthly = <?= json_encode($monthly); ?>;
const statusCounts = <?= json_encode($statusCounts); ?>;
const typeCounts = <?= json_encode($typeCounts); ?>;

new Chart(document.getElementById('chartMonthly'), {
  type: 'line',
  data: {
    labels: ['Agu','Sep','Okt','Nov','Des','Jan','Feb','Mar','Apr','Mei','Jun','Jul'],
    datasets: [{
      label: 'Laporan',
      data: monthly,
      tension: .35,
      fill: true
    }]
  }
});

new Chart(document.getElementById('chartStatus'), {
  type: 'doughnut',
  data: {
    labels: ['Menunggu','Diproses','Selesai'],
    datasets: [{ data: statusCounts }]
  }
});

new Chart(document.getElementById('chartJenis'), {
  type: 'bar',
  data: {
    labels: ['Organik','Anorganik','Campuran','B3','TPS Ilegal'],
    datasets: [{ label: 'Jumlah', data: typeCounts }]
  }
});
</script>
<?php include __DIR__ . '/../templates/footer.php'; ?>
