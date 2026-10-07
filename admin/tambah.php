<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/_form.php';
wajib_login();

$errors = [];
$data = [
    'nama' => '', 'merek' => '', 'kategori' => 'motor', 'tahun' => (int)date('Y'),
    'mesin' => '', 'transmisi' => 'Automatic', 'warna' => '', 'harga' => 0, 'deskripsi' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (post_terlalu_besar()) {
        $errors[] = PESAN_POST_BESAR;
    } elseif (!csrf_valid()) {
        $errors[] = 'Sesi tidak valid. Muat ulang halaman lalu coba lagi.';
    } else {
        $data = baca_input_kendaraan($_POST, $errors);

        // Upload foto (foto utama wajib)
        $kolomFoto = ['gambar_utama', 'gambar_2', 'gambar_3', 'gambar_4'];
        $foto = ['gambar_utama' => null, 'gambar_2' => null, 'gambar_3' => null, 'gambar_4' => null];

        if (!$errors) {
            foreach ($kolomFoto as $kolom) {
                $err = '';
                $hasil = simpan_gambar($_FILES[$kolom] ?? ['error' => UPLOAD_ERR_NO_FILE], $err);
                if ($hasil === false) {
                    $errors[] = ($kolom === 'gambar_utama' ? 'Foto utama' : 'Foto ' . substr($kolom, -1)) . ': ' . $err;
                    break;
                }
                $foto[$kolom] = $hasil;
            }
            if (!$errors && $foto['gambar_utama'] === null) {
                $errors[] = 'Foto utama wajib diunggah.';
            }
        }

        if (!$errors) {
            try {
                $stmt = $conn->prepare(
                    "INSERT INTO kendaraan
                     (nama, merek, kategori, tahun, mesin, transmisi, warna, harga, deskripsi,
                      gambar_utama, gambar_2, gambar_3, gambar_4)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt->bind_param(
                    'sssisssdsssss',
                    $data['nama'], $data['merek'], $data['kategori'], $data['tahun'],
                    $data['mesin'], $data['transmisi'], $data['warna'], $data['harga'],
                    $data['deskripsi'],
                    $foto['gambar_utama'], $foto['gambar_2'], $foto['gambar_3'], $foto['gambar_4']
                );
                $stmt->execute();
                $stmt->close();

                flash('Kendaraan "' . $data['nama'] . '" berhasil ditambahkan.');
                header('Location: index.php');
                exit;
            } catch (Throwable $ex) {
                $errors[] = 'Data gagal disimpan ke database. Coba lagi.';
            }
        }

        // Bila gagal, bersihkan file yang sudah terlanjur diunggah
        foreach ($foto as $nama) {
            if ($nama) hapus_file_gambar($conn, $nama);
        }
    }
}

admin_header('Tambah Kendaraan');
?>
        <div class="adm-title">
            <div>
                <h1>Tambah <span>Kendaraan</span></h1>
                <p class="adm-sub">Isi data kendaraan baru. Setelah disimpan, langsung tampil di website.</p>
            </div>
        </div>

        <?php tampil_error($errors); ?>
        <?php form_kendaraan($data, 'tambah'); ?>
<?php admin_footer();
