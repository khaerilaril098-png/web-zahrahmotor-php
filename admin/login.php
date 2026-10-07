<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/layout.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    if (!csrf_valid()) {
        $error = 'Sesi tidak valid. Muat ulang halaman lalu coba lagi.';
    } elseif ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']   = (int)$admin['id'];
            $_SESSION['admin_nama'] = $admin['username'];
            header('Location: index.php');
            exit;
        }

        usleep(400000); // perlambat tebakan password
        $error = 'Username atau password salah.';
    }
}

admin_header('Login', false);
?>
        <div class="adm-login-box">
            <h1>ZAHRAH<span>MOTOR</span></h1>
            <p class="adm-sub">Masuk ke panel admin</p>

            <?php if ($error): ?>
                <div class="adm-alert adm-alert-error" style="margin-top:18px;"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php" autocomplete="off">
                <?= csrf_field() ?>

                <div class="adm-field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= e($_POST['username'] ?? '') ?>" required autofocus>
                </div>

                <div class="adm-field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="adm-btn adm-btn-primary">Masuk</button>
            </form>

            <a href="../index.php" class="adm-login-back">← Kembali ke website</a>
        </div>
<?php admin_footer();
