<?php
session_start();

$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];
if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}
if ($noAnggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
}

// LATIHAN 2: aturan validasi tambahan untuk anggota
// (a) No. HP (kalau diisi) hanya angka, panjang 10-13 digit
if ($noHp !== '' && !preg_match('/^[0-9]{10,13}$/', $noHp)) {
    $errors[] = "No. HP harus berupa angka 10-13 digit.";
}
// (b) No. Anggota tidak boleh sama dengan anggota yang sudah ada
foreach ($_SESSION['anggota'] ?? [] as $anggota) {
    if ($anggota['no_anggota'] === $noAnggota) {
        $errors[] = "No. Anggota sudah dipakai.";
        break;
    }
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

if (!isset($_SESSION['anggota'])) {
    $_SESSION['anggota'] = [];
}

$_SESSION['anggota'][] = [
    'nama' => $nama,
    'no_anggota' => $noAnggota,
    'alamat' => $alamat,
    'no_hp' => $noHp,
];

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
header('Location: list.php');
exit;