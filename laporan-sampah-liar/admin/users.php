<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
auth_check_remember();
auth_require();

$pageTitle = 'Kelola User | ' . APP_NAME;

$users = $conn->query("SELECT id, nama, email, role, created_at FROM users ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
include __DIR__ . '/../templates/header.php';
?>
<div class="admin-layout">
  <?php include __DIR__ . '/../templates/sidebar.php'; ?>
  <main class="admin-main">
    <div class="admin-topbar mb-4">
      <div class="text-muted small">Manajemen akun</div>
      <h4 class="fw-bold mb-0">Kelola User Admin</h4>
    </div>

    <div class="glass-card p-4">
      <table class="table table-hover align-middle datatable">
        <thead>
          <tr><th>Nama</th><th>Email</th><th>Role</th><th>Dibuat</th></tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= e($u['nama']); ?></td>
              <td><?= e($u['email']); ?></td>
              <td><span class="badge text-bg-light text-success"><?= e($u['role']); ?></span></td>
              <td><?= format_date_id($u['created_at']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
<?php include __DIR__ . '/../templates/footer.php'; ?>
