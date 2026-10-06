<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';

csrf_verify();

$id = to_int($_POST['id'] ?? null);
if ($id === null) {
    header('Location: list.php');
    exit;
}

$nama = input_str($_POST, 'nama');
$noAnggota = input_str($_POST, 'no_anggota');
$alamat = input_str($_POST, 'alamat');
$noHp = input_str($_POST, 'no_hp');

$errors = [];
if ($nama === '' || mb_strlen($nama) > 255) {
    $errors[] = "Nama wajib diisi (maks. 255 karakter).";
}
if ($noAnggota === '' || mb_strlen($noAnggota) > 50) {
    $errors[] = "No. Anggota wajib diisi (maks. 50 karakter).";
}
if (mb_strlen($alamat) > 255) {
    $errors[] = "Alamat maksimal 255 karakter.";
}
if ($noHp !== '' && !preg_match('/^[0-9+\-\s]{1,30}$/', $noHp)) {
    $errors[] = "No. HP hanya boleh berisi angka, +, -, dan spasi (maks. 30).";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: edit.php?id=' . $id);
    exit;
}

try {
    $stmt = $pdo->prepare(
        "UPDATE anggota SET nama = :nama, no_anggota = :no_anggota,
         alamat = :alamat, no_hp = :no_hp WHERE id = :id"
    );
    $stmt->execute([
        'nama' => $nama,
        'no_anggota' => $noAnggota,
        'alamat' => $alamat,
        'no_hp' => $noHp,
        'id' => $id,
    ]);
} catch (PDOException $ex) {
    if ($ex->getCode() === '23505') {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'No. Anggota sudah digunakan anggota lain.'];
        header('Location: edit.php?id=' . $id);
        exit;
    }
    throw $ex;
}

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil diperbarui.'];
header('Location: list.php');
exit;
