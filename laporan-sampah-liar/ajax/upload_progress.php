<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/upload.php';
header('Content-Type: application/json');
auth_check_remember();
auth_require();

if (!verify_csrf($_POST['_csrf'] ?? null)) {
    echo json_encode(['ok' => false, 'message' => 'Token tidak valid']);
    exit;
}

$id = (int)($_POST['laporan_id'] ?? 0);
$keterangan = sanitize_text($_POST['keterangan'] ?? '');

if ($id <= 0 || $keterangan === '') {
    echo json_encode(['ok' => false, 'message' => 'Input tidak valid']);
    exit;
}

$fileName = null;
if (!empty($_FILES['foto_progress']['name'])) {
    $up = upload_image($_FILES['foto_progress'], 'progress');
    if (!$up['ok']) {
        echo json_encode(['ok' => false, 'message' => $up['message']]);
        exit;
    }
    $fileName = $up['filename'];
}

$stmt = $conn->prepare("INSERT INTO progress_laporan (laporan_id, keterangan, foto_progress) VALUES (?, ?, ?)");
$stmt->bind_param('iss', $id, $keterangan, $fileName);

if ($stmt->execute()) {
    $upd = $conn->prepare("UPDATE laporan SET status = 'diproses' WHERE id = ?");
    $upd->bind_param('i', $id);
    $upd->execute();
    echo json_encode(['ok' => true, 'message' => 'Progress berhasil disimpan']);
} else {
    echo json_encode(['ok' => false, 'message' => 'Gagal menyimpan progress']);
}
