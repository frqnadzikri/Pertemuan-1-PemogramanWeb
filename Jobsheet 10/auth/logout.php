<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_destroy();

// LATIHAN 2: hapus juga cookie "Ingat Saya" supaya logout benar-benar
// keluar, bukan langsung login otomatis lagi lewat cookie.
setcookie('remember_id', '', time() - 3600, '/');

header('Location: login.php');
exit;