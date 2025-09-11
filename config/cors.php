<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // <-- UBAH BAGIAN INI
    // Ganti wildcard '*' dengan daftar domain yang diizinkan secara spesifik.
    'allowed_origins' => [
        'https://siklayar.pacitankab.go.id',
        'https://siakik.pacitankab.go.id',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // <-- UBAH JUGA INI, SANGAT PENTING
    // Ubah menjadi 'true' untuk mengizinkan permintaan yang membawa
    // token otentikasi atau cookie.
    'supports_credentials' => true,

];
