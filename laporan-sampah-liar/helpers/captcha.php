<?php
declare(strict_types=1);

function captcha_generate(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $code = substr(
        str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'),
        0,
        5
    );

    $_SESSION['_captcha'] = $code;
}

function captcha_value(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['_captcha'])) {
        captcha_generate();
    }

    return $_SESSION['_captcha'];
}

function captcha_verify(?string $input): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    return isset($_SESSION['_captcha']) &&
           strtoupper(trim((string)$input)) ===
           strtoupper($_SESSION['_captcha']);
}