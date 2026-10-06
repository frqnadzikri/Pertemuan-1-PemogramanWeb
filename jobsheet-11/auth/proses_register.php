<?php
require __DIR__ . '/../includes/session.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';

csrf_verify();

$nama = input_str($_POST, 'nama');
$username = input_str($_POST, 'username');
$password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

$errors = [];
if ($nama === '' || mb_strlen($nama) > 255) {
    $errors[] = "Nama wajib diisi (maks. 255 karakter).";
}
if (!preg_match('/^[A-Za-z0-9_]{3,50}$/', $username)) {
    $errors[] = "Username 3-50 karakter: huruf, angka, atau underscore.";
}
if (strlen($password) < 6) {
    $errors[] = "Password minimal 6 karakter.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: register.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO users (nama, username, password, role) VALUES (:nama, :username, :password, 'petugas')"
    );
    $stmt->execute([
        'nama' => $nama,
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT),
    ]);
} catch (PDOException $ex) {
    // 23505 = unique_violation (menangani juga race condition, bukan hanya SELECT cek di awal)
    if ($ex->getCode() === '23505') {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Username sudah digunakan.'];
        header('Location: register.php');
        exit;
    }
    throw $ex;
}

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Registrasi berhasil, silakan login.'];
header('Location: login.php');
exit;
