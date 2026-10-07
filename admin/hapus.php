<?php
require_once __DIR__ . '/auth.php';
wajib_login();

// Penghapusan hanya lewat POST + token CSRF (bukan link biasa)
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valid()) {
    flash('Permintaan hapus tidak valid.', 'error');
    header('Location: index.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM kendaraan WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    flash('Kendaraan tidak ditemukan atau sudah dihapus.', 'error');
    header('Location: index.php');
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM kendaraan WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    foreach (['gambar_utama', 'gambar_2', 'gambar_3', 'gambar_4'] as $kolom) {
        hapus_file_gambar($conn, $row[$kolom], $id);
    }

    flash('Kendaraan "' . $row['nama'] . '" berhasil dihapus.');
} catch (Throwable $ex) {
    flash('Kendaraan gagal dihapus. Coba lagi.', 'error');
}

header('Location: index.php');
exit;
