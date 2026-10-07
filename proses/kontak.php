<?php
require_once __DIR__ . '/../config/db.php';

// Hanya menerima POST dari form di index.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$nama      = trim($_POST['nama'] ?? '');
$telepon   = trim($_POST['telepon'] ?? '');
$email     = trim($_POST['email'] ?? '');
$kendaraan = trim($_POST['kendaraan'] ?? '');
$pesan     = trim($_POST['pesan'] ?? '');

if ($nama === '' || $telepon === '' || $pesan === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../index.php?status=gagal#kontak');
    exit;
}

// Batasi panjang agar sesuai kolom database
$nama      = mb_substr($nama, 0, 100);
$telepon   = mb_substr($telepon, 0, 25);
$email     = mb_substr($email, 0, 100);
$kendaraan = mb_substr($kendaraan, 0, 100);

try {
    $stmt = $conn->prepare(
        "INSERT INTO pesan (nama, telepon, email, kendaraan, pesan) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param('sssss', $nama, $telepon, $email, $kendaraan, $pesan);
    $stmt->execute();
    $stmt->close();
} catch (Throwable $ex) {
    header('Location: ../index.php?status=gagal#kontak');
    exit;
}

header('Location: ../index.php?status=berhasil#kontak');
exit;
