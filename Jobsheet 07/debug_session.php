<?php
// LATIHAN 3: halaman sementara untuk mengintip isi $_SESSION.
$page_title = "Debug Session";
include __DIR__ . '/includes/header.php';
?>
        <section>
            <h2>Debug Session</h2>
            <pre><?php echo htmlspecialchars(print_r($_SESSION, true)); ?></pre>
        </section>
<?php include __DIR__ . '/includes/footer.php'; ?>