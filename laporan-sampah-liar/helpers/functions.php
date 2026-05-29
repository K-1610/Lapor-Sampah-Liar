<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';

function base_url(string $path = ''): string {
    return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
}

function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never {
    header('Location: ' . $path);
    exit;
}

function is_post(): bool {
    return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
}

function flash_set(string $key, string $message): void {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['_flash'][$key] = $message;
}

function flash_get(string $key): ?string {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (!isset($_SESSION['_flash'][$key])) return null;
    $msg = $_SESSION['_flash'][$key];
    unset($_SESSION['_flash'][$key]);
    return $msg;
}

function csrf_token(): string {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(?string $token): bool {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    return isset($_SESSION['_csrf']) && is_string($token) && hash_equals($_SESSION['_csrf'], $token);
}

function format_date_id(?string $date): string {
    if (!$date) return '-';
    return date('d M Y, H:i', strtotime($date));
}

function badge_status(string $status): string {
    return match ($status) {
        'diproses' => 'bg-primary',
        'selesai' => 'bg-success',
        default => 'bg-warning text-dark',
    };
}

function label_status(string $status): string {
    return match ($status) {
        'diproses' => 'Diproses',
        'selesai' => 'Selesai',
        default => 'Menunggu',
    };
}

function label_jenis(?string $jenis): string {
    return $jenis ? ucfirst(str_replace('_', ' ', $jenis)) : '-';
}

function rupiah_or_number(int $value): string {
    return number_format($value, 0, ',', '.');
}

function is_https(): bool {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
}
