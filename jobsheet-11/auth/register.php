<?php
require __DIR__ . '/../includes/session.php';
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$page_title = "Registrasi Petugas";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
        <section>
            <h2>Registrasi Petugas</h2>

            <?php flash_render($flash); ?>

            <form method="post" action="proses_register.php">
                <?php echo csrf_field(); ?>
                <p>
                    <label for="nama">Nama</label><br>
                    <input type="text" id="nama" name="nama" required maxlength="255">
                </p>
                <p>
                    <label for="username">Username (huruf, angka, underscore, 3-50 karakter)</label><br>
                    <input type="text" id="username" name="username" required maxlength="50" pattern="[A-Za-z0-9_]{3,50}">
                </p>
                <p>
                    <label for="password">Password</label><br>
                    <input type="password" id="password" name="password" required minlength="6">
                </p>
                <p>
                    <button type="submit">Daftar</button>
                </p>
            </form>
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
