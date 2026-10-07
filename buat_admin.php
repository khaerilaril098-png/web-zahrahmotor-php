<?php
// Jalankan SEKALI lewat browser: http://localhost/Web%20Showroom%20Motor/buat_admin.php
// Membuat akun admin pertama. Setelah berhasil login, HAPUS file ini.
//
// Lupa password / tidak bisa login?  buka:  buat_admin.php?reset=1
// (password akun "admin" dikembalikan menjadi admin123)

require_once __DIR__ . '/config/db.php';

$username = 'admin';
$password = 'admin123';
$reset    = isset($_GET['reset']);

$stmt = $conn->prepare("SELECT id FROM admin WHERE username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$ada = $stmt->get_result()->fetch_assoc();
$stmt->close();

$hash = password_hash($password, PASSWORD_DEFAULT);

if (!$ada) {
    $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
    $stmt->bind_param('ss', $username, $hash);
    $stmt->execute();
    $stmt->close();
    $pesan = "Akun admin berhasil dibuat.";
    $detail = true;
} elseif ($reset) {
    $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE username = ?");
    $stmt->bind_param('ss', $hash, $username);
    $stmt->execute();
    $stmt->close();
    $pesan = "Password akun admin berhasil direset.";
    $detail = true;
} else {
    $pesan = "Akun admin sudah ada. Tidak ada yang diubah.";
    $detail = false;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background: #0a0a0a; color: #f2f2f2; display: grid; place-items: center; min-height: 100vh; margin: 0; }
        .box { background: #151515; border: 1px solid #262626; border-radius: 12px; padding: 32px; max-width: 460px; }
        code { background: #222; padding: 2px 8px; border-radius: 4px; }
        a { color: #e10600; }
        p { line-height: 1.6; }
    </style>
</head>
<body>
    <div class="box">
        <h2><?= htmlspecialchars($pesan) ?></h2>
        <?php if ($detail): ?>
            <p>Username: <code><?= htmlspecialchars($username) ?></code><br>
               Password: <code><?= htmlspecialchars($password) ?></code></p>
        <?php else: ?>
            <p>Tidak bisa login? Buka <a href="buat_admin.php?reset=1">buat_admin.php?reset=1</a> untuk mengembalikan password menjadi <code>admin123</code>.</p>
        <?php endif; ?>
        <p><a href="admin/login.php">Ke halaman login admin →</a></p>
        <p style="color:#ff6b6b;font-size:14px;">Setelah berhasil login, hapus file <code>buat_admin.php</code> dari folder proyek.</p>
    </div>
</body>
</html>
