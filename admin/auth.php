<?php
// File pendukung untuk semua halaman admin: koneksi, sesi, CSRF, flash, upload gambar.
require_once __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

const IMG_DIR = __DIR__ . '/../img/';
const MAKS_UKURAN_GAMBAR = 2 * 1024 * 1024; // 2 MB (sama dengan batas bawaan PHP di XAMPP/Laragon)

/* ---------- Umum ---------- */

function e($nilai) {
    return htmlspecialchars((string)($nilai ?? ''), ENT_QUOTES, 'UTF-8');
}

function rupiah($angka) {
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}

function wajib_login() {
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

// True bila total kiriman form melebihi post_max_size PHP (PHP lalu mengosongkan $_POST dan $_FILES).
function post_terlalu_besar() {
    return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'
        && empty($_POST) && empty($_FILES)
        && !empty($_SERVER['CONTENT_LENGTH']);
}

const PESAN_POST_BESAR = 'Total ukuran foto terlalu besar. Gunakan foto yang lebih kecil (maksimal 2 MB per foto).';

/* ---------- CSRF ---------- */

function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_valid() {
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && is_string($_POST['csrf'])
        && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

/* ---------- Pesan flash ---------- */

function flash($pesan, $tipe = 'sukses') {
    $_SESSION['flash'] = ['pesan' => $pesan, 'tipe' => $tipe];
}

function ambil_flash() {
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

/* ---------- Validasi input kendaraan ---------- */

function baca_input_kendaraan(array $post, array &$errors) {
    $data = [
        'nama'      => trim($post['nama'] ?? ''),
        'merek'     => strtoupper(trim($post['merek'] ?? '')),
        'kategori'  => $post['kategori'] ?? '',
        'tahun'     => (int)($post['tahun'] ?? 0),
        'mesin'     => trim($post['mesin'] ?? ''),
        'transmisi' => $post['transmisi'] ?? '',
        'warna'     => trim($post['warna'] ?? ''),
        'harga'     => (float)preg_replace('/[^0-9]/', '', (string)($post['harga'] ?? '')),
        'deskripsi' => trim($post['deskripsi'] ?? ''),
    ];

    if ($data['nama'] === '' || mb_strlen($data['nama']) > 100)  $errors[] = 'Nama kendaraan wajib diisi (maksimal 100 karakter).';
    if ($data['merek'] === '' || mb_strlen($data['merek']) > 100) $errors[] = 'Merek wajib diisi (maksimal 100 karakter).';
    if (!in_array($data['kategori'], ['motor', 'mobil'], true))  $errors[] = 'Kategori harus motor atau mobil.';
    if ($data['tahun'] < 1980 || $data['tahun'] > (int)date('Y') + 1) $errors[] = 'Tahun tidak valid.';
    if (!in_array($data['transmisi'], ['Automatic', 'Manual'], true)) $errors[] = 'Transmisi harus Automatic atau Manual.';
    if ($data['mesin'] === '' || mb_strlen($data['mesin']) > 50) $errors[] = 'Mesin wajib diisi, contoh: 110 CC.';
    if ($data['warna'] === '' || mb_strlen($data['warna']) > 50) $errors[] = 'Warna wajib diisi.';
    if ($data['harga'] <= 0) $errors[] = 'Harga harus lebih dari 0.';

    return $data;
}

/* ---------- Upload gambar ---------- */

// Mengembalikan nama file yang tersimpan, null jika tidak ada file, atau false jika gagal (pesan di $error).
function simpan_gambar(array $file, &$error) {
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE)
            ? 'Ukuran gambar terlalu besar (maksimal 2 MB).'
            : 'Upload gambar gagal (kode ' . (int)$file['error'] . ').';
        return false;
    }
    if ($file['size'] > MAKS_UKURAN_GAMBAR) {
        $error = 'Ukuran gambar maksimal 2 MB.';
        return false;
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $ekstensi = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
    if ($ekstensi === null) {
        $error = 'Format gambar harus JPG, PNG, atau WEBP.';
        return false;
    }

    if (!is_dir(IMG_DIR) && !mkdir(IMG_DIR, 0775, true)) {
        $error = 'Folder img/ tidak bisa dibuat.';
        return false;
    }

    $nama = 'k_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ekstensi;
    if (!move_uploaded_file($file['tmp_name'], IMG_DIR . $nama)) {
        $error = 'Gagal menyimpan gambar ke folder img/. Pastikan folder bisa ditulis.';
        return false;
    }
    return $nama;
}

// Hapus file gambar hasil upload admin (awalan "k_") bila tidak dipakai kendaraan lain.
// Foto bawaan (Beat.jpg, dst.) tidak pernah dihapus.
function hapus_file_gambar($conn, $nama, $kecualiId = 0) {
    if (!$nama || strpos($nama, 'k_') !== 0 || $nama !== basename($nama)) {
        return;
    }
    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS jml FROM kendaraan
         WHERE id <> ? AND (gambar_utama = ? OR gambar_2 = ? OR gambar_3 = ? OR gambar_4 = ?)"
    );
    $stmt->bind_param('issss', $kecualiId, $nama, $nama, $nama, $nama);
    $stmt->execute();
    $jml = (int)$stmt->get_result()->fetch_assoc()['jml'];
    $stmt->close();

    $path = IMG_DIR . $nama;
    if ($jml === 0 && is_file($path)) {
        @unlink($path);
    }
}
