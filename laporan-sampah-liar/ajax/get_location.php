<?php
header('Content-Type: application/json');
echo json_encode([
    'ok' => true,
    'message' => 'Gunakan geolocation browser + Google Maps API untuk lokasi otomatis.',
]);
