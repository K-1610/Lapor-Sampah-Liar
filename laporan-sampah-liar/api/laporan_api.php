<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

$rows = $conn->query("SELECT kode_laporan, nama_pelapor, jenis_sampah, status, created_at FROM laporan ORDER BY created_at DESC LIMIT 20")->fetch_all(MYSQLI_ASSOC);
echo json_encode(['ok' => true, 'data' => $rows]);
