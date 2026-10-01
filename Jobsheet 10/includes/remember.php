<?php
// LATIHAN 2 (Remember Me): kalau session belum login tapi ada cookie
// "remember_id", coba pulihkan sesi dari cookie itu.
//
// CATATAN KEAMANAN (bagian diskusi dari soal latihan):
// Cookie ini cuma menyimpan id user polos, tanpa token rahasia. Ini
// contoh paling sederhana untuk belajar, TAPI tidak aman untuk
// produksi, karena siapa pun yang menebak/mendapat angka id bisa
// memalsukan cookie dan ikut login sebagai user itu. Implementasi
// yang lebih aman biasanya memakai token acak panjang yang disimpan
// di tabel terpisah di database (bukan sekadar id), supaya cookie
// tidak bisa ditebak dan bisa dicabut sewaktu-waktu.
//
// Query ke database dibungkus try/catch supaya kalau PostgreSQL mati,
// guard di auth.php tetap mengarahkan ke Login (bukan error mentah),
// sesuai prinsip Jobsheet 10 bab 4.
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
        // Database bermasalah — anggap saja belum login, jangan hentikan halaman
    }
}