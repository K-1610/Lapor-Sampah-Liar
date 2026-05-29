<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const APP_NAME = 'Lapor Sampah Liar';
const APP_URL = 'http://localhost/laporan-sampah-liar';
const APP_ENV = 'local';

// Isi jika punya API key Google Maps, jika kosong map tetap menampilkan placeholder
const GOOGLE_MAPS_API_KEY = 'YOUR_GOOGLE_MAPS_API_KEY';

// Keamanan session / remember me
const APP_SECRET = 'ganti_dengan_string_panjang_random_yang_unik';

// Upload
const MAX_UPLOAD_BYTES = 5242880; // 5MB
const UPLOAD_ALLOWED_EXT = ['jpg', 'jpeg', 'png'];
