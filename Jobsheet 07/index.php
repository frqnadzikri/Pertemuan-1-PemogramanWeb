<?php
$page_title = "Beranda";
include __DIR__ . '/includes/header.php';

$totalBuku = count($_SESSION['buku'] ?? []);
$totalAnggota = count($_SESSION['anggota'] ?? []);

// LATIHAN 4: ambil flash message (hasil reset), lalu hapus
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
        <section>
            <h2>Selamat Datang di Sistem Perpustakaan Mini</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <p>Aplikasi sederhana untuk mengelola data buku dan anggota perpustakaan.</p>
        </section>

        <section>
            <h2>Ringkasan</h2>
            <article>
                <h3>Total Buku</h3>
                <p><?php echo $totalBuku; ?></p>
            </article>
            <article>
                <h3>Total Anggota</h3>
                <p><?php echo $totalAnggota; ?></p>
            </article>
            <article>
                <h3>Sedang Dipinjam</h3>
                <p>0</p>
            </article>
        </section>

        <section>
            <h2>Reset Data</h2>
            <form method="post" action="reset.php" onsubmit="return confirm('Yakin ingin menghapus semua data buku dan anggota?');">
                <p>Kosongkan semua data buku dan anggota yang tersimpan di sesi.</p>
                <p>
                    <button type="submit">Reset Data</button>
                </p>
            </form>
        </section>
<?php include __DIR__ . '/includes/footer.php'; ?>