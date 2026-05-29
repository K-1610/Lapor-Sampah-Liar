<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

function auth_user(): ?array {
    if (!empty($_SESSION['auth_user']) && is_array($_SESSION['auth_user'])) {
        return $_SESSION['auth_user'];
    }
    return null;
}

function auth_require(): void {
    if (!auth_user()) {
        redirect(base_url('admin/login.php'));
    }
}

function auth_logout(): void {
    unset($_SESSION['auth_user'], $_SESSION['auth_remember']);
    setcookie('remember_admin', '', time() - 3600, '/');
    session_destroy();
}

function auth_attempt(string $email, string $password): ?array {
    global $conn;
    $stmt = $conn->prepare("SELECT id, nama, email, password, role FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        unset($user['password']);
        return $user;
    }
    return null;
}

function auth_remember_cookie(array $user): void {
    $token = hash_hmac('sha256', $user['id'] . '|' . $user['email'], APP_SECRET);
    setcookie('remember_admin', $token, [
        'expires' => time() + 60 * 60 * 24 * 30,
        'path' => '/',
        'secure' => is_https(),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    $_SESSION['auth_remember'] = $token;
}

function auth_check_remember(): void {
    if (auth_user()) return;
    if (empty($_COOKIE['remember_admin'])) return;

    global $conn;
    $stmt = $conn->prepare("SELECT id, nama, email, role FROM users LIMIT 1");
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    if (!$user) return;

    $expected = hash_hmac('sha256', $user['id'] . '|' . $user['email'], APP_SECRET);
    if (hash_equals($expected, $_COOKIE['remember_admin'])) {
        $_SESSION['auth_user'] = $user;
    }
}
