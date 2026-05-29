<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
header('Content-Type: application/json');
auth_check_remember();
auth_require();

if (!verify_csrf($_POST['_csrf'] ?? null)) {
    echo json_encode(['ok' => false, 'message' => 'Token tidak valid']);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$status = trim($_POST['status'] ?? '');
$allowed = ['menunggu','diproses','selesai'];

if ($id <= 0 || !in_array($status, $allowed, true)) {
    echo json_encode(['ok' => false, 'message' => 'Data tidak valid']);
    exit;
}

$stmt = $conn->prepare("UPDATE laporan SET status = ? WHERE id = ?");
$stmt->bind_param('si', $status, $id);

if ($stmt->execute()) {
    echo json_encode(['ok' => true, 'message' => 'Status berhasil diperbarui']);
} else {
    echo json_encode(['ok' => false, 'message' => 'Gagal memperbarui status']);
}
