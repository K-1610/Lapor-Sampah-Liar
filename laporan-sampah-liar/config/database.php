<?php
declare(strict_types=1);

require_once __DIR__ . '/app.php';

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'sistem_pelaporan_sampah';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    die('Koneksi database gagal.');
}
$conn->set_charset('utf8mb4');
