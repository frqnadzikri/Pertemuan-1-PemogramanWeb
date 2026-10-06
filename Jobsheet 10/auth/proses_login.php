<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// LATIHAN 3: batasi percobaan login gagal, dihitung per username di session
$maxAttempt = 3;
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = [];
}
$attempt = $_SESSION['login_attempts'][$username] ?? 0;

if ($attempt >= $maxAttempt) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => "Terlalu banyak percobaan gagal untuk username \"$username\". Coba lagi nanti."
    ];
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Login berhasil — reset penghitung percobaan gagal untuk username ini
    unset($_SESSION['login_attempts'][$username]);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];

    // LATIHAN 2: kalau "Ingat Saya" dicentang, simpan id user ke cookie
    // selama 30 hari supaya sesi tetap aktif walau browser ditutup.
    if ($remember) {
        setcookie('remember_id', $user['id'], time() + 30 * 24 * 60 * 60, '/');
    }

    header('Location: ../index.php');
    exit;
}

// Login gagal — tambah penghitung percobaan untuk username ini
$_SESSION['login_attempts'][$username] = $attempt + 1;
$sisaPercobaan = $maxAttempt - $_SESSION['login_attempts'][$username];

$_SESSION['flash'] = [
    'type' => 'error',
    'pesan' => "Username atau password salah. Sisa percobaan: $sisaPercobaan."
];
header('Location: login.php');
exit;