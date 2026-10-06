<?php
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_id'])) {
    try {
        require_once __DIR__ . '/koneksi.php';
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $_COOKIE['remember_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];
        }
    } catch (Throwable $e) {
        // Database bermasalah — anggap saja belum login
    }
}