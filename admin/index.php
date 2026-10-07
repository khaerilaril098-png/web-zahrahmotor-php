<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/layout.php';
wajib_login();

// Pencarian & filter
$q   = trim($_GET['q'] ?? '');
$kat = $_GET['kategori'] ?? '';

$sql    = "SELECT * FROM kendaraan WHERE 1=1";
$types  = '';
$params = [];

if ($q !== '') {
    $like = '%' . $q . '%';
    $sql .= " AND (nama LIKE ? OR merek LIKE ?)";
    $types .= 'ss';
    $params[] = $like;
    $params[] = $like;
}
if (in_array($kat, ['motor', 'mobil'], true)) {
    $sql .= " AND kategori = ?";
    $types .= 's';
    $params[] = $kat;
}
$sql .= " ORDER BY created_at DESC, id ASC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$kendaraan = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Ringkasan jumlah
$jumlah = ['motor' => 0, 'mobil' => 0];
$res = $conn->query("SELECT kategori, COUNT(*) AS jml FROM kendaraan GROUP BY kategori");
foreach ($res->fetch_all(MYSQLI_ASSOC) as $r) {
    $jumlah[$r['kategori']] = (int)$r['jml'];
}

// Pesan masuk terbaru
$pesan = [];
$resPesan = $conn->query("SELECT * FROM pesan ORDER BY created_at DESC, id DESC LIMIT 20");
if ($resPesan) {
    $pesan = $resPesan->fetch_all(MYSQLI_ASSOC);
}

admin_header('Data Kendaraan');
?>
        <div class="adm-title">
            <div>
                <h1>Data <span>Kendaraan</span></h1>
                <p class="adm-sub">Kelola semua unit yang tampil di website.</p>
            </div>
            <a href="tambah.php" class="adm-btn adm-btn-primary">+ Tambah Kendaraan</a>
        </div>

        <div class="adm-stats">
            <div class="adm-stat"><strong><?= $jumlah['motor'] + $jumlah['mobil'] ?></strong><span>Total kendaraan</span></div>
            <div class="adm-stat"><strong><?= $jumlah['motor'] ?></strong><span>Motor</span></div>
            <div class="adm-stat"><strong><?= $jumlah['mobil'] ?></strong><span>Mobil</span></div>
        </div>

        <form class="adm-toolbar" method="GET" action="index.php">
            <input type="text" name="q" value="<?= e($q) ?>" placeholder="Cari nama atau merek...">
            <select name="kategori">
                <option value="">Semua kategori</option>
                <option value="motor" <?= $kat === 'motor' ? 'selected' : '' ?>>Motor</option>
                <option value="mobil" <?= $kat === 'mobil' ? 'selected' : '' ?>>Mobil</option>
            </select>
            <button type="submit" class="adm-btn">Cari</button>
            <?php if ($q !== '' || $kat !== ''): ?>
                <a href="index.php" class="adm-btn">Reset</a>
            <?php endif; ?>
        </form>

        <div class="adm-card">
            <div class="adm-table-wrap">
                <?php if ($kendaraan): ?>
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Kendaraan</th>
                            <th>Kategori</th>
                            <th>Tahun</th>
                            <th>Spesifikasi</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($kendaraan as $k): ?>
                        <tr>
                            <td><img src="../img/<?= e($k['gambar_utama']) ?>" alt="<?= e($k['nama']) ?>"></td>
                            <td>
                                <strong><?= e($k['nama']) ?></strong><br>
                                <span class="adm-sub"><?= e($k['merek']) ?></span>
                            </td>
                            <td><span class="adm-badge"><?= strtoupper(e($k['kategori'])) ?></span></td>
                            <td><?= (int)$k['tahun'] ?></td>
                            <td>
                                <?= e($k['mesin']) ?> · <?= e($k['transmisi']) ?><br>
                                <span class="adm-sub"><?= e($k['warna']) ?></span>
                            </td>
                            <td><?= rupiah($k['harga']) ?></td>
                            <td>
                                <div class="adm-aksi">
                                    <a href="edit.php?id=<?= (int)$k['id'] ?>" class="adm-btn adm-btn-sm">Edit</a>
                                    <form method="POST" action="hapus.php" onsubmit="return confirm('Hapus <?= e(addslashes($k['nama'])) ?>? Data tidak bisa dikembalikan.');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= (int)$k['id'] ?>">
                                        <button type="submit" class="adm-btn adm-btn-sm adm-btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div class="adm-kosong">
                        Belum ada kendaraan<?= ($q !== '' || $kat !== '') ? ' yang cocok dengan pencarian' : '' ?>.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="adm-card">
            <h2>Pesan Masuk (20 terbaru)</h2>
            <div class="adm-table-wrap">
                <?php if ($pesan): ?>
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Pengirim</th>
                            <th>Kendaraan diminati</th>
                            <th>Pesan</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pesan as $p): ?>
                        <tr>
                            <td><?= e($p['created_at']) ?></td>
                            <td>
                                <strong><?= e($p['nama']) ?></strong><br>
                                <span class="adm-sub"><?= e($p['telepon']) ?> · <?= e($p['email']) ?></span>
                            </td>
                            <td><?= e($p['kendaraan'] ?: '-') ?></td>
                            <td class="adm-pesan-isi"><?= e($p['pesan']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div class="adm-kosong">Belum ada pesan masuk.</div>
                <?php endif; ?>
            </div>
        </div>
<?php admin_footer();
