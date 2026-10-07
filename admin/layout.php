<?php
// Kerangka halaman admin (header & footer).

function admin_header($judul, $tampilNav = true) {
    $flash = ambil_flash();
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($judul) ?> | Admin ZAHRAH MOTOR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<?php if ($tampilNav): ?>
    <header class="adm-header">
        <div class="adm-wrap adm-header-in">
            <a href="index.php" class="adm-logo">ZAHRAH<span>MOTOR</span> <small>ADMIN</small></a>
            <nav class="adm-nav">
                <a href="index.php">Data Kendaraan</a>
                <a href="tambah.php">+ Tambah</a>
                <a href="../index.php" target="_blank" rel="noopener">Lihat Website</a>
                <a href="logout.php" class="adm-logout">Keluar (<?= e($_SESSION['admin_nama'] ?? '') ?>)</a>
            </nav>
        </div>
    </header>
<?php endif; ?>
    <main class="adm-wrap adm-main">
        <?php if ($flash): ?>
            <div class="adm-alert adm-alert-<?= e($flash['tipe']) ?>"><?= e($flash['pesan']) ?></div>
        <?php endif; ?>
<?php
}

function admin_footer() {
    ?>
    </main>
</body>
</html>
<?php
}
