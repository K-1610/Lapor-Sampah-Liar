<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/security.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/captcha.php';

$pageTitle = 'Login Admin | ' . APP_NAME;
if (empty($_SESSION['_captcha'])) {
    captcha_generate();
}
auth_check_remember();
if (auth_user()) redirect(base_url('admin/dashboard.php'));

$errors = [];

if (is_post()) {
    if (!verify_csrf($_POST['_csrf'] ?? null)) {
        $errors[] = 'Token keamanan tidak valid.';
    }
    if (!captcha_verify($_POST['captcha'] ?? null)) {
        $errors[] = 'Captcha salah.';
        captcha_generate();
    }

    $email = sanitize_text($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    if (!$errors) {
        $user = auth_attempt($email, $password);
        if ($user) {
            $_SESSION['auth_user'] = $user;
            if (!empty($_POST['remember_me'])) {
                auth_remember_cookie($user);
            }
            flash_set('success', 'Login berhasil. Selamat datang, ' . $user['nama'] . '.');
            unset($_SESSION['_captcha']);
            redirect(base_url('admin/dashboard.php'));
        }
        $errors[] = 'Email atau password tidak cocok.';
    }
}

include __DIR__ . '/../templates/header.php';
?>
<section class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-xl-4">
        <div class="glass-card p-4 p-lg-5">
          <div class="text-center mb-4">
            <div class="brand-badge mx-auto mb-3"><i class="bi bi-shield-lock"></i></div>
            <h3 class="fw-bold mb-1">Login Admin</h3>
            <div class="text-muted small">Masuk untuk mengelola laporan, progress, dan statistik.</div>
          </div>

          <?php if ($errors): ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach ($errors as $error): ?><li><?= e($error); ?></li><?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form method="post" class="d-grid gap-3">
            <?= csrf_field(); ?>
            <div>
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" placeholder="admin@mail.com" required>
            </div>
            <div>
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div>
              <div class="d-flex align-items-center justify-content-between mb-2">
                <label class="form-label mb-0">Captcha</label>
                <button type="button" class="btn btn-sm btn-outline-success" onclick="location.reload()">Refresh</button>
              </div>
              <div class="d-flex align-items-center gap-3">
                <div class="captcha-box"><?= e(captcha_value()); ?></div>
                <input type="text" name="captcha" class="form-control" placeholder="Kode captcha" required>
              </div>
            </div>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me" value="1">
              <label class="form-check-label" for="remember_me">Remember me</label>
            </div>
            <button class="btn btn-success btn-lg">Masuk</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../templates/footer.php'; ?>
