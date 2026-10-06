<?php
require __DIR__ . '/../includes/session.php';
require __DIR__ . '/../includes/csrf.php';

// Logout hanya lewat POST + token CSRF, supaya tidak bisa dipicu lewat <img src="logout.php">.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}
csrf_verify();

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();

header('Location: login.php');
exit;
