<?php
$page_title = "Daftar Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// LATIHAN 3: pencarian di server memakai ILIKE (tidak peduli huruf besar/kecil)
$keyword = trim($_GET['q'] ?? '');
$stmt = $pdo->prepare("SELECT * FROM buku WHERE judul ILIKE :keyword ORDER BY id DESC");
$stmt->execute(['keyword' => '%' . $keyword . '%']);
$daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
        <section>
            <h2>Daftar Buku</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <!-- LATIHAN 3: kolom pencarian dijadikan form GET ke server -->
            <form method="get" action="list.php" class="search-box">
                <label for="search-input">Cari Judul Buku</label>
                <input type="text" id="search-input" name="q" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Ketik judul buku...">
                <button type="submit">Cari</button>
            </form>

            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th>Tanggal Ditambahkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarBuku)): ?>
                    <tr>
                        <td colspan="6">
                            <?php if ($keyword !== ''): ?>
                                Tidak ada buku yang judulnya cocok dengan "<?php echo htmlspecialchars($keyword); ?>".
                            <?php else: ?>
                                Belum ada data buku. Silakan tambah lewat menu "Tambah Buku".
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarBuku as $buku): ?>
                        <tr>
                            <td><?php echo $buku['judul']; ?></td>
                            <td><?php echo $buku['pengarang']; ?></td>
                            <td><?php echo $buku['tahun']; ?></td>
                            <td><?php echo $buku['stok']; ?></td>
                            <!-- LATIHAN 2: tampilkan kolom tanggal_ditambahkan -->
                            <td><?php echo $buku['tanggal_ditambahkan'] ? date('d-m-Y H:i', strtotime($buku['tanggal_ditambahkan'])) : '-'; ?></td>
                            <td>
                                <button type="button">Edit</button>
                                <button type="button" class="btn-hapus">Hapus</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>