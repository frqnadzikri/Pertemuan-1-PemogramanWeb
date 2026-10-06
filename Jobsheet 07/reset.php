<?php
// LATIHAN 4: kosongkan seluruh data sesi tanpa menutup browser.
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION = [];      // kosongkan isi $_SESSION
    session_destroy();   // hancurkan sesi lama
    session_start();     // mulai sesi baru supaya flash message bisa dipakai
    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Semua data berhasil direset.'];
}

header('Location: index.php');
exit;