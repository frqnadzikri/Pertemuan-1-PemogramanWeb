<?php
// Satu pintu untuk memulai session dengan cookie yang lebih aman.
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,   // cookie tidak bisa dibaca JavaScript (mengurangi dampak XSS)
        'samesite' => 'Lax',  // cookie tidak ikut dikirim pada POST lintas-situs (lapisan tambahan anti-CSRF)
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
}
