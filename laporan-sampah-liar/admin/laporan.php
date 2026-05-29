<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
auth_check_remember();
auth_require();

$pageTitle = 'Data Laporan | ' . APP_NAME;
include __DIR__ . '/../templates/header.php';

$data = $conn->query("SELECT * FROM laporan ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<div class="admin-layout">
  <?php include __DIR__ . '/../templates/sidebar.php'; ?>
  <main class="admin-main">
    <div class="admin-topbar mb-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
      <div>
        <div class="text-muted small">Manajemen laporan</div>
        <h4 class="fw-bold mb-0">Data Laporan Warga</h4>
      </div>
      <div class="text-muted small">DataTables aktif untuk pencarian dan filter</div>
    </div>

    <div class="glass-card p-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle datatable">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Pelapor</th>
              <th>Jenis</th>
              <th>Status</th>
              <th>Tanggal</th>
              <th width="240">Aksi</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($data as $row): ?>
            <tr>
              <td class="fw-semibold"><?= e($row['kode_laporan']); ?></td>
              <td><?= e($row['nama_pelapor']); ?><div class="small text-muted"><?= e($row['email']); ?></div></td>
              <td><?= e(label_jenis($row['jenis_sampah'])); ?></td>
              <td><span class="badge rounded-pill <?= badge_status($row['status']); ?>"><?= e(label_status($row['status'])); ?></span></td>
              <td><?= format_date_id($row['created_at']); ?></td>
              <td>
                <div class="d-flex flex-wrap gap-2">
                  <a href="<?= base_url('admin/detail_laporan.php?id=' . (int)$row['id']); ?>" class="btn btn-sm btn-outline-success">Detail</a>
                  <button class="btn btn-sm btn-primary" data-status-action data-url="<?= base_url('ajax/update_status.php'); ?>" data-id="<?= (int)$row['id']; ?>" data-status="diproses">Diproses</button>
                  <button class="btn btn-sm btn-success" data-status-action data-url="<?= base_url('ajax/update_status.php'); ?>" data-id="<?= (int)$row['id']; ?>" data-status="selesai">Selesai</button>
                  <a href="<?= base_url('admin/progress.php?id=' . (int)$row['id']); ?>" class="btn btn-sm btn-warning">Progress</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>
<?php include __DIR__ . '/../templates/footer.php'; ?>
