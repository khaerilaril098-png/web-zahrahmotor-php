<?php
// Form kendaraan yang dipakai bersama oleh tambah.php dan edit.php.

function tampil_error(array $errors) {
    if (!$errors) return;
    echo '<div class="adm-alert adm-alert-error"><ul>';
    foreach ($errors as $err) {
        echo '<li>' . e($err) . '</li>';
    }
    echo '</ul></div>';
}

function form_kendaraan(array $data, $mode, $row = null) {
    $edit = ($mode === 'edit');
    $aksi = $edit ? 'edit.php?id=' . (int)$row['id'] : 'tambah.php';
    ?>
    <form class="adm-form" method="POST" action="<?= e($aksi) ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="adm-grid">

            <div class="adm-sec">INFORMASI KENDARAAN</div>

            <div class="adm-field">
                <label for="nama">Nama kendaraan</label>
                <input type="text" id="nama" name="nama" value="<?= e($data['nama']) ?>" placeholder="Contoh: Honda Beat F1" maxlength="100" required>
            </div>

            <div class="adm-field">
                <label for="merek">Merek</label>
                <input type="text" id="merek" name="merek" value="<?= e($data['merek']) ?>" placeholder="Contoh: HONDA" maxlength="100" required>
            </div>

            <div class="adm-field">
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori" required>
                    <option value="motor" <?= $data['kategori'] === 'motor' ? 'selected' : '' ?>>Motor</option>
                    <option value="mobil" <?= $data['kategori'] === 'mobil' ? 'selected' : '' ?>>Mobil</option>
                </select>
            </div>

            <div class="adm-field">
                <label for="tahun">Tahun</label>
                <input type="number" id="tahun" name="tahun" value="<?= e($data['tahun']) ?>" min="1980" max="<?= (int)date('Y') + 1 ?>" required>
            </div>

            <div class="adm-field">
                <label for="mesin">Mesin</label>
                <input type="text" id="mesin" name="mesin" value="<?= e($data['mesin']) ?>" placeholder="Contoh: 110 CC" maxlength="50" required>
            </div>

            <div class="adm-field">
                <label for="transmisi">Transmisi</label>
                <select id="transmisi" name="transmisi" required>
                    <option value="Automatic" <?= $data['transmisi'] === 'Automatic' ? 'selected' : '' ?>>Automatic</option>
                    <option value="Manual" <?= $data['transmisi'] === 'Manual' ? 'selected' : '' ?>>Manual</option>
                </select>
            </div>

            <div class="adm-field">
                <label for="warna">Warna</label>
                <input type="text" id="warna" name="warna" value="<?= e($data['warna']) ?>" placeholder="Contoh: Hitam" maxlength="50" required>
            </div>

            <div class="adm-field">
                <label for="harga">Harga (Rp)</label>
                <input type="text" id="harga" name="harga" value="<?= $data['harga'] ? e((int)$data['harga']) : '' ?>" placeholder="Contoh: 8500000" inputmode="numeric" required>
                <div class="adm-hint">Tulis angka saja, titik pemisah boleh dipakai.</div>
            </div>

            <div class="adm-field adm-full">
                <label for="deskripsi">Deskripsi (opsional)</label>
                <textarea id="deskripsi" name="deskripsi" placeholder="Kondisi, kelengkapan surat, dan catatan lain..."><?= e($data['deskripsi']) ?></textarea>
            </div>

            <div class="adm-sec">FOTO KENDARAAN</div>

            <?php
            $slot = [
                'gambar_utama' => 'Foto utama' . ($edit ? '' : ' (wajib)'),
                'gambar_2'     => 'Foto 2',
                'gambar_3'     => 'Foto 3',
                'gambar_4'     => 'Foto 4',
            ];
            foreach ($slot as $kolom => $label):
                $sekarang = $edit ? ($row[$kolom] ?? '') : '';
            ?>
            <div class="adm-field">
                <label for="<?= e($kolom) ?>"><?= e($label) ?></label>

                <?php if ($sekarang): ?>
                <div class="adm-foto-sekarang">
                    <img src="../img/<?= e($sekarang) ?>" alt="Foto saat ini">
                    <span>Foto saat ini</span>
                    <?php if ($kolom !== 'gambar_utama'): ?>
                        <label><input type="checkbox" name="hapus_<?= e($kolom) ?>" value="1"> Hapus foto ini</label>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <input type="file" id="<?= e($kolom) ?>" name="<?= e($kolom) ?>" accept="image/jpeg,image/png,image/webp" <?= (!$edit && $kolom === 'gambar_utama') ? 'required' : '' ?>>
                <div class="adm-hint">
                    <?= $sekarang ? 'Pilih file baru untuk mengganti. ' : '' ?>JPG, PNG, atau WEBP, maksimal 2 MB.
                </div>
            </div>
            <?php endforeach; ?>

        </div>

        <div class="adm-form-aksi">
            <button type="submit" class="adm-btn adm-btn-primary"><?= $edit ? 'Simpan Perubahan' : 'Simpan Kendaraan' ?></button>
            <a href="index.php" class="adm-btn">Batal</a>
        </div>
    </form>
    <?php
}
