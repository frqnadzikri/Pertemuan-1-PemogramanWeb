<?php
header('Content-Type: text/plain; charset=utf-8');
require __DIR__ . '/includes/koneksi.php';

$file = __DIR__ . '/data/buku.json';
if (!file_exists($file)) {
    die("File data/buku.json tidak ditemukan. Salin dulu dari folder jobsheet-06.");
}

$data = json_decode(file_get_contents($file), true);
if (!is_array($data)) {
    die("Isi buku.json bukan JSON yang valid.");
}
// Kalau JSON dibungkus, misalnya {"buku": [ ... ]}
if (isset($data['buku'])) {
    $data = $data['buku'];
}

// Cek supaya menjalankan skrip dua kali tidak membuat data ganda
$cek = $pdo->prepare("SELECT COUNT(*) FROM buku WHERE judul = :judul AND pengarang = :pengarang");
$insert = $pdo->prepare(
    "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
     VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)"
);

$berhasil = 0;
$dilewati = 0;

foreach ($data as $b) {
    if (!is_array($b)) {
        $dilewati++;
        continue;
    }

    $judul = trim($b['judul'] ?? '');
    $pengarang = trim($b['pengarang'] ?? '');
    $tahun = (int) ($b['tahun'] ?? 0);

    // Lewati data kosong / tidak valid
    if ($judul === '' || $pengarang === '' || $tahun < 1900 || $tahun > 2026) {
        $dilewati++;
        continue;
    }

    // Lewati kalau buku yang sama sudah ada di database
    $cek->execute(['judul' => $judul, 'pengarang' => $pengarang]);
    if ($cek->fetchColumn() > 0) {
        $dilewati++;
        continue;
    }

    $insert->execute([
        'judul' => $judul,
        'pengarang' => $pengarang,
        'tahun' => $tahun,
        'isbn' => trim($b['isbn'] ?? ''),
        'stok' => max(0, (int) ($b['stok'] ?? 0)),
        'kategori' => trim($b['kategori'] ?? ''),
    ]);
    $berhasil++;
}

echo "Migrasi selesai.\n";
echo "Berhasil dimasukkan : $berhasil buku\n";
echo "Dilewati            : $dilewati buku (kosong/tidak valid/sudah ada)\n";