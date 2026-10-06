<?php
// Proteksi CSRF berbasis token per-session. Butuh session aktif (lihat session.php).

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function csrf_verify()
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        http_response_code(405);
        die('Metode tidak diizinkan.');
    }
    $token = $_POST['csrf_token'] ?? '';
    $sesi  = $_SESSION['csrf_token'] ?? '';
    if (!is_string($token) || $token === '' || $sesi === '' || !hash_equals($sesi, $token)) {
        http_response_code(403);
        die('Permintaan ditolak: token CSRF tidak valid atau kedaluwarsa.');
    }
}
