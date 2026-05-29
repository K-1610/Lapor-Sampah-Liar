<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/security.php';
require_once __DIR__ . '/../helpers/upload.php';
require_once __DIR__ . '/../helpers/captcha.php';
$pageTitle = APP_NAME . ' | Form Pelaporan';
include __DIR__ . '/../templates/header.php';

if (empty($_SESSION['_captcha'])) {
    captcha_generate();
}

if (is_post()) {
    $errors = [];

    if (!verify_csrf($_POST['_csrf'] ?? null)) $errors[] = 'Token keamanan tidak valid.';
    if (!captcha_verify($_POST['captcha'] ?? null)) {
    $errors[] = 'Captcha tidak sesuai.';

    captcha_generate();
    }

    $nama = sanitize_text($_POST['nama_pelapor'] ?? '');
    $email = sanitize_text($_POST['email'] ?? '');
    $hp = sanitize_text($_POST['no_hp'] ?? '');
    $jenis = sanitize_text($_POST['jenis_sampah'] ?? '');
    $deskripsi = sanitize_text($_POST['deskripsi'] ?? '');
    $latitude = clean_numeric($_POST['latitude'] ?? '');
    $longitude = clean_numeric($_POST['longitude'] ?? '');
    $alamat = sanitize_text($_POST['alamat'] ?? '');

    if ($nama === '') $errors[] = 'Nama pelapor wajib diisi.';
    if (!validate_email_safe($email)) $errors[] = 'Email tidak valid.';
    if (!validate_phone_safe($hp)) $errors[] = 'Nomor HP tidak valid.';
    if ($jenis === '') $errors[] = 'Jenis sampah wajib dipilih.';
    if ($deskripsi === '') $errors[] = 'Deskripsi wajib diisi.';
    if ($latitude === '' || $longitude === '') $errors[] = 'Lokasi GPS belum terdeteksi.';
    if ($alamat === '') $errors[] = 'Alamat lokasi wajib diisi.';
    if (empty($_FILES['foto']['name'])) $errors[] = 'Foto sampah wajib diupload.';

    $fotoName = null;
    if (!$errors) {
        $up = upload_image($_FILES['foto'], 'laporan');
        if (!$up['ok']) {
            $errors[] = $up['message'];
        } else {
            $fotoName = $up['filename'];
        }
    }

    if (!$errors) {
        $kode = 'LAP-' . date('Ymd') . '-' . random_int(1000, 9999);
        $status = 'menunggu';

        $stmt = $conn->prepare("INSERT INTO laporan (kode_laporan, nama_pelapor, email, no_hp, jenis_sampah, deskripsi, foto, latitude, longitude, alamat, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssssssss', $kode, $nama, $email, $hp, $jenis, $deskripsi, $fotoName, $latitude, $longitude, $alamat, $status);

        if ($stmt->execute()) {
            unset($_SESSION['_captcha']);
            flash_set('success', 'Laporan berhasil dikirim dengan kode: ' . $kode);
            redirect(base_url('pages/tracking.php?kode=' . urlencode($kode)));
        } else {
            $errors[] = 'Gagal menyimpan data laporan.';
        }
    }
}
?>
<section class="py-5">
  <div class="container">
    <?php if ($msg = flash_get('success')): ?>
      <div class="alert alert-success"><?= e($msg); ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <div class="fw-bold mb-2">Terjadi kendala:</div>
        <ul class="mb-0">
          <?php foreach ($errors as $err): ?><li><?= e($err); ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="row g-4 align-items-start">
      <div class="col-lg-7">
        <div class="glass-card p-4 p-lg-5">
          <div class="soft-pill mb-3"><i class="bi bi-pencil-square"></i> Form pelaporan</div>
          <h1 class="section-title mb-2">Laporkan sampah liar dengan detail yang lengkap</h1>
          <p class="text-muted mb-4">Isi data berikut agar laporan Anda mudah diverifikasi dan diproses oleh admin.</p>

          <form method="post" enctype="multipart/form-data" class="row g-3">
            <?= csrf_field(); ?>
            <div class="col-md-6">
              <label class="form-label">Nama Pelapor</label>
              <input type="text" name="nama_pelapor" class="form-control" placeholder="Nama lengkap" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nomor HP</label>
              <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Jenis Sampah</label>
              <select name="jenis_sampah" class="form-select" required>
                <option value="">Pilih jenis</option>
                <option value="organik">Organik</option>
                <option value="anorganik">Anorganik</option>
                <option value="campuran">Campuran</option>
                <option value="b3">B3 / Berbahaya</option>
                <option value="tps_ilegal">TPS Ilegal</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Deskripsi</label>
              <textarea name="deskripsi" rows="4" class="form-control" placeholder="Jelaskan kondisi sampah, titik lokasi, dan informasi penting lainnya" required></textarea>
            </div>
            <div class="col-12">
              <label class="form-label">Upload Foto Kondisi Sampah</label>
              <input type="file" name="foto" class="form-control" accept="image/png,image/jpeg,image/jpg" data-preview-input required>
              <div class="form-text">Format JPG/JPEG/PNG, maksimal 5MB.</div>
              <img src="" alt="Preview" class="upload-preview mt-3 d-none" data-preview-target>
            </div>

            <div class="col-12">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Latitude</label>
                  <input type="text" name="latitude" id="latitude" class="form-control" readonly>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Longitude</label>
                  <input type="text" name="longitude" id="longitude" class="form-control" readonly>
                </div>
              </div>
            </div>

            <div class="col-12">
              <label class="form-label">Alamat Lengkap</label>
              <textarea name="alamat" id="alamat" rows="2" class="form-control" readonly></textarea>
            </div>

            <div class="col-12">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <label class="form-label mb-0">Captcha</label>
                <button type="button" class="btn btn-sm btn-outline-success" onclick="location.reload()">Ganti Captcha</button>
              </div>
              <div class="d-flex align-items-center gap-3">
                <div class="captcha-box"><?= e(captcha_value()); ?></div>
                <input type="text" name="captcha" class="form-control" placeholder="Masukkan kode captcha" required>
              </div>
            </div>

            <div class="col-12 d-flex gap-2 flex-wrap">
              <button type="button" class="btn btn-outline-success" id="detectLocation"><i class="bi bi-geo-alt me-1"></i>Deteksi Lokasi</button>
              <button type="submit" class="btn btn-success px-4"><i class="bi bi-send me-1"></i>Kirim Laporan</button>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="glass-card p-4 p-lg-5 mb-4">
          <div class="soft-pill mb-3"><i class="bi bi-geo-alt"></i> Peta lokasi</div>
          <div id="map" class="map-box"></div>
          <div class="small text-muted mt-3">Geser marker untuk menyesuaikan titik pelaporan secara manual.</div>
        </div>

        <div class="glass-card p-4 p-lg-5 mb-4">
          <div class="soft-pill mb-3"><i class="bi bi-clipboard-check"></i> Catatan penting</div>
          <ul class="small text-muted mb-0">
            <li class="mb-2">Pastikan foto menampilkan kondisi sampah dengan jelas.</li>
            <li class="mb-2">Lokasi otomatis akan diambil dari perangkat Anda.</li>
            <li class="mb-2">Alamat akan diisi dari Leaflet.</li>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?= base_url('assets/js/map.js'); ?>"></script>
<?php include __DIR__ . '/../templates/footer.php'; ?>
