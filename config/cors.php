<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'display/poli/*'], // Sesuaikan dengan route API
    'allowed_methods' => ['*'], // Izinkan semua metode (GET, POST, dll.)
    'allowed_origins' => ['*'], // Izinkan akses dari semua domain
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Izinkan semua header
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false, // Ganti `true` jika API pakai autentikasi cookie/session
];
