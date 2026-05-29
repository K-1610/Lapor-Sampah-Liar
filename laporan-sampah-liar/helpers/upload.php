<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';

function ensure_upload_dir(string $folder): string {
    $dir = __DIR__ . '/../uploads/' . trim($folder, '/');
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    return $dir;
}

function upload_image(array $file, string $folder = 'laporan'): array {
    if (!isset($file['name']) || !is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'message' => 'File tidak valid.'];
    }

    if (($file['size'] ?? 0) > MAX_UPLOAD_BYTES) {
        return ['ok' => false, 'message' => 'Ukuran file maksimal 5MB.'];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, UPLOAD_ALLOWED_EXT, true)) {
        return ['ok' => false, 'message' => 'Format file harus JPG, JPEG, atau PNG.'];
    }

    $imgInfo = @getimagesize($file['tmp_name']);
    if ($imgInfo === false) {
        return ['ok' => false, 'message' => 'File bukan gambar yang valid.'];
    }

    $dir = ensure_upload_dir($folder);
    $safeName = date('YmdHis') . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
    $target = $dir . '/' . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        return ['ok' => false, 'message' => 'Gagal menyimpan file upload.'];
    }

    return ['ok' => true, 'filename' => $safeName, 'path' => $target];
}
