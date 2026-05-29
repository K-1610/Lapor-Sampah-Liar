<?php
declare(strict_types=1);

function sanitize_text(?string $value): string {
    $value = trim((string)$value);
    $value = preg_replace('/\s+/', ' ', $value);
    return $value;
}

function validate_email_safe(?string $email): bool {
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validate_phone_safe(?string $phone): bool {
    return (bool) preg_match('/^[0-9+()\-\s]{8,20}$/', (string)$phone);
}

function clean_numeric(?string $value): string {
    return preg_replace('/[^0-9.\-]/', '', (string)$value);
}
