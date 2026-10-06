<?php
// Kredensial dibaca dari environment variable; nilai default hanya untuk dev lokal.
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '5432';
$db   = getenv('DB_NAME') ?: 'simpus_mini';
$user = getenv('DB_USER') ?: 'postgres';
$pass = getenv('DB_PASS') ?: '12345678';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false, // prepared statement asli di sisi server
    ]);
} catch (PDOException $e) {
    error_log('Koneksi DB gagal: ' . $e->getMessage()); // detail hanya ke log server
    http_response_code(500);
    die('Terjadi kesalahan pada server. Silakan coba lagi nanti.');
}
