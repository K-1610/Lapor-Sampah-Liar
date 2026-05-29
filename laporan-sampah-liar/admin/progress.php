<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/upload.php';
require_once __DIR__ . '/../helpers/security.php';
auth_check_remember();
auth_require();

$pageTitle = 'Progress Laporan | ' . APP_NAME;
$errors = [];

$id = (int)($_GET['id'] ?? ($_POST['laporan_id'] ?? 0));

if (is_post()) {
    if (!verify_csrf($_POST['_csrf'] ?? null)) $errors[] = 'Token tidak valid.';
    $id = (int)($_POST['laporan_id'] ?? 0);
    $keterangan = sanitize_text($_POST['keterangan'] ?? '');

    if ($id <= 0) $errors[] = 'Laporan tidak valid.';
    if ($keterangan === '') $errors[] = 'Keterangan progress wajib diisi.';

    $fileName = null;
    if (!empty($_FILES['foto_progress']['name'])) {
        $up = upload_image($_FILES['foto_progress'], 'progress');
        if (!$up['ok']) {
            $errors[] = $up['message'];
        } else {
            $fileName = $up['filename'];
        }
    }

    if (!$errors) {
        $stmt = $conn->prepare("INSERT INTO progress_laporan (laporan_id, keterangan, foto_progress) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $id, $keterangan, $fileName);
        if ($stmt->execute()) {
            $upd = $conn->prepare("UPDATE laporan SET status = IF(status='selesai','selesai','diproses') WHERE id = ?");
            $upd->bind_param('i', $id);
            $upd->execute();
            flash_set('success', 'Progress berhasil ditambahkan.');
            redirect(base_url('admin/detail_laporan.php?id=' . $id));
        } else {
            $errors[] = 'Gagal menyimpan progress.';
        }
    }
}

$dataLaporan = $conn->query("SELECT id, kode_laporan, nama_pelapor FROM laporan ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
include __DIR__ . '/../templates/header.php';
?>
<div class="admin-layout">
  <?php include __DIR__ . '/../templates/sidebar.php'; ?>
  <main class="admin-main">
    <div class="admin-topbar mb-4">
      <div class="text-muted small">Tambah progress</div>
      <h4 class="fw-bold mb-0">Upload Foto Progress Pembersihan</h4>
    </div>

    <div class="glass-card p-4 p-lg-5">
      <?php if ($errors): ?>
        <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= e($e); ?></li><?php endforeach; ?></ul></div>
      <?php endif; ?>

      <form method="post" enctype="multipart/form-data" class="row g-3">
        <?= csrf_field(); ?>
        <div class="col-md-6">
          <label class="form-label">Laporan</label>
          <select name="laporan_id" class="form-select" required>
            <option value="">Pilih laporan</option>
            <?php foreach ($dataLaporan as $l): ?>
              <option value="<?= (int)$l['id']; ?>" <?= $id===(int)$l['id']?'selected':''; ?>>
                <?= e($l['kode_laporan'] . ' - ' . $l['nama_pelapor']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Foto Progress</label>
          <input type="file" name="foto_progress" class="form-control" accept="image/png,image/jpeg,image/jpg" data-preview-input>
        </div>
        <div class="col-12">
          <label class="form-label">Keterangan</label>
          <textarea name="keterangan" rows="4" class="form-control" required></textarea>
        </div>
        <div class="col-12">
          <img src="" class="upload-preview d-none" data-preview-target>
        </div>
        <div class="col-12">
          <button class="btn btn-success px-4">Simpan Progress</button>
        </div>
      </form>
    </div>
  </main>
</div>
<?php include __DIR__ . '/../templates/footer.php'; ?>
