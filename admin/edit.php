<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/_form.php';
wajib_login();

$id = (int)($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM kendaraan WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    flash('Kendaraan tidak ditemukan.', 'error');
    header('Location: index.php');
    exit;
}

$errors = [];
$data = [
    'nama' => $row['nama'], 'merek' => $row['merek'], 'kategori' => $row['kategori'],
    'tahun' => (int)$row['tahun'], 'mesin' => $row['mesin'], 'transmisi' => $row['transmisi'],
    'warna' => $row['warna'], 'harga' => (float)$row['harga'], 'deskripsi' => $row['deskripsi'] ?? '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (post_terlalu_besar()) {
        $errors[] = PESAN_POST_BESAR;
    } elseif (!csrf_valid()) {
        $errors[] = 'Sesi tidak valid. Muat ulang halaman lalu coba lagi.';
    } else {
        $data = baca_input_kendaraan($_POST, $errors);

        $kolomFoto = ['gambar_utama', 'gambar_2', 'gambar_3', 'gambar_4'];
        $baru   = [];      // file yang baru diunggah
        $final  = [];      // nilai akhir tiap kolom foto
        $lama   = [];      // file lama yang harus dibersihkan setelah sukses

        if (!$errors) {
            foreach ($kolomFoto as $kolom) {
                $final[$kolom] = $row[$kolom];
                $err = '';
                $hasil = simpan_gambar($_FILES[$kolom] ?? ['error' => UPLOAD_ERR_NO_FILE], $err);

                if ($hasil === false) {
                    $errors[] = ($kolom === 'gambar_utama' ? 'Foto utama' : 'Foto ' . substr($kolom, -1)) . ': ' . $err;
                    break;
                }
                if ($hasil !== null) {                       // diganti foto baru
                    $baru[] = $hasil;
                    if ($row[$kolom]) $lama[] = $row[$kolom];
                    $final[$kolom] = $hasil;
                } elseif ($kolom !== 'gambar_utama' && !empty($_POST['hapus_' . $kolom])) { // dihapus
                    if ($row[$kolom]) $lama[] = $row[$kolom];
                    $final[$kolom] = null;
                }
            }
        }

        if (!$errors) {
            try {
                $stmt = $conn->prepare(
                    "UPDATE kendaraan SET
                        nama = ?, merek = ?, kategori = ?, tahun = ?, mesin = ?, transmisi = ?,
                        warna = ?, harga = ?, deskripsi = ?,
                        gambar_utama = ?, gambar_2 = ?, gambar_3 = ?, gambar_4 = ?
                     WHERE id = ?"
                );
                $stmt->bind_param(
                    'sssisssdsssssi',
                    $data['nama'], $data['merek'], $data['kategori'], $data['tahun'],
                    $data['mesin'], $data['transmisi'], $data['warna'], $data['harga'],
                    $data['deskripsi'],
                    $final['gambar_utama'], $final['gambar_2'], $final['gambar_3'], $final['gambar_4'],
                    $id
                );
                $stmt->execute();
                $stmt->close();

                // Bersihkan foto lama yang sudah tidak dipakai
                foreach ($lama as $nama) {
                    hapus_file_gambar($conn, $nama, $id);
                }

                flash('Data "' . $data['nama'] . '" berhasil diperbarui.');
                header('Location: index.php');
                exit;
            } catch (Throwable $ex) {
                $errors[] = 'Perubahan gagal disimpan ke database. Coba lagi.';
            }
        }

        // Gagal: buang file baru yang terlanjur diunggah
        foreach ($baru as $nama) {
            hapus_file_gambar($conn, $nama, $id);
        }
    }
}

admin_header('Edit Kendaraan');
?>
        <div class="adm-title">
            <div>
                <h1>Edit <span>Kendaraan</span></h1>
                <p class="adm-sub">Ubah data <?= e($row['nama']) ?>.</p>
            </div>
        </div>

        <?php tampil_error($errors); ?>
        <?php form_kendaraan($data, 'edit', $row); ?>
<?php admin_footer();
