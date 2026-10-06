<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';

csrf_verify();

$judul = input_str($_POST, 'judul');
$pengarang = input_str($_POST, 'pengarang');
$tahun = to_int($_POST['tahun'] ?? null);
$isbn = input_str($_POST, 'isbn');
$stok = to_int($_POST['stok'] ?? null);
$kategori = input_str($_POST, 'kategori');

// Validasi server-side: validasi client (JS/HTML) bisa dilewati.
$errors = [];
if ($judul === '' || mb_strlen($judul) > 255) {
    $errors[] = "Judul wajib diisi (maks. 255 karakter).";
}
if ($pengarang === '' || mb_strlen($pengarang) > 255) {
    $errors[] = "Pengarang wajib diisi (maks. 255 karakter).";
}
if ($tahun === null || $tahun < 1900 || $tahun > 2026) {
    $errors[] = "Tahun harus bilangan bulat di antara 1900-2026.";
}
if ($stok === null || $stok < 0) {
    $errors[] = "Stok harus bilangan bulat dan tidak boleh negatif.";
}
if (mb_strlen($isbn) > 50) {
    $errors[] = "ISBN maksimal 50 karakter.";
}
if (!in_array($kategori, ['fiksi', 'non-fiksi', 'referensi'], true)) { // whitelist
    $errors[] = "Kategori tidak valid.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
     VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)"
);
$stmt->execute([
    'judul' => $judul,
    'pengarang' => $pengarang,
    'tahun' => $tahun,
    'isbn' => $isbn,
    'stok' => $stok,
    'kategori' => $kategori,
]);

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil ditambahkan.'];
header('Location: list.php');
exit;
