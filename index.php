<?php
require_once __DIR__ . "/config/db.php";

// Ambil data kendaraan dari database (kendaraan terbaru dari admin tampil paling atas)
$result = $conn->query("SELECT * FROM kendaraan ORDER BY created_at DESC, id ASC");
$kendaraan = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Mengamankan output HTML
if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars((string)($value ?? ""), ENT_QUOTES, "UTF-8");
    }
}

// Format harga ke Rupiah
function rupiah($angka) {
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}

$status = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SHOWROOM ZAHRAH MOTOR | Showroom Motor & Mobil</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <style>
    /* BACKGROUND HALAMAN AWAL */
    #home.hero {
        position: relative;
        min-height: 90vh;

        background-image: url("img/background.jpg");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    /* Lapisan gelap agar tulisan tetap jelas */
    #home.hero .hero-overlay {
        position: absolute;
        inset: 0;

        background: rgba(0, 0, 0, 0.45);

        z-index: 1;
    }

    #home.hero .hero-content,
    #home.hero .hero-scroll {
        position: relative;
        z-index: 2;
    }
</style>

</head>

<body>

    <!-- =========================
         NAVBAR
    ========================== -->
    <header class="header">

        <nav class="navbar">

            <a href="#home" class="logo">
                SHOWROOM<span>ZAHRAH MOTOR</span>
            </a>

            <button class="menu-toggle" id="menuToggle">
                ☰
            </button>

            <ul class="nav-menu" id="navMenu">
                <li><a href="#home">Home</a></li>
                <li><a href="#koleksi">Koleksi</a></li>
                <li><a href="#tentang">Tentang</a></li>
                <li><a href="#galeri">Galeri</a></li>
                <li><a href="#kontak">Kontak</a></li>
            </ul>

        </nav>

    </header>


    <!-- =========================
         HERO
    ========================== -->
    <main>

        <section class="hero" id="home">

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <p class="hero-subtitle">
                    SHOWROOM ZAHRAH MOTOR

                </p>

                <h1>
                    Terima Tukar Tambah Cash/Kredit
                    <span>Angsuran Pasti Rendah </span>
                </h1>

                <p class="hero-description">
                    Pilihan motor dan mobil berkualitas dengan
                    pelayanan profesional untuk perjalanan terbaik Anda.
                </p>

                <div class="hero-buttons">

                    <a href="#koleksi" class="btn btn-primary">
                        Lihat Koleksi
                    </a>

                    <a href="#kontak" class="btn btn-outline">
                        Hubungi Kami
                    </a>

                </div>

            </div>

            <div class="hero-scroll">
                <span>Scroll untuk melihat lebih banyak</span>
                <div class="scroll-line"></div>
            </div>

        </section>


        <!-- =========================
             STATISTIK
        ========================== -->
        <section class="stats">

            <div class="container stats-grid">

                <div class="stat-item">
                    <h2>150+</h2>
                    <p>Unit Terjual</p>
                </div>

                <div class="stat-item">
                    <h2>50+</h2>
                    <p>Koleksi Kendaraan</p>
                </div>

                <div class="stat-item">
                    <h2>5+</h2>
                    <p>Tahun Pengalaman</p>
                </div>

                <div class="stat-item">
                    <h2>100%</h2>
                    <p>Pelayanan Terbaik</p>
                </div>

            </div>

        </section>


        <!-- =========================
             KOLEKSI
        ========================== -->
        <section class="collection section" id="koleksi">

            <div class="container">

                <div class="section-heading">


                    <div>
                        <p class="section-label">
                            KOLEKSI KENDARAAN KAMI
                        </p>

                        <h2>
                            Pilihan Kendaraan
                            <span>Terbaik</span>
                        </h2>
                    </div>

                    <p class="section-description">
                        Temukan berbagai pilihan motor dan mobil
                        yang sesuai dengan kebutuhan dan gaya Anda.
                    </p>

                </div>


                <!-- SEARCH -->
                <div class="collection-tools">

                    <div class="search-box">

                        <input
                            type="text"
                            id="searchInput"
                            placeholder="Cari kendaraan..."
                        >

                        <span>⌕</span>

                    </div>


                    <!-- FILTER -->
                    <div class="filter-buttons">

                        <button
                            class="filter-btn active"
                            data-filter="all">
                            Semua
                        </button>

                        <button
                            class="filter-btn"
                            data-filter="motor">
                            Motor
                        </button>

                        <button
                            class="filter-btn"
                            data-filter="mobil">
                            Mobil
                        </button>

                    </div>

                </div>


                <!-- VEHICLE GRID (data dari database) -->
                <div class="vehicle-grid" id="vehicleGrid">

                    <?php foreach ($kendaraan as $row): ?>
                    <article
                        class="vehicle-card"
                        data-category="<?= e($row['kategori']) ?>"
                        data-name="<?= e($row['nama']) ?>">

                        <div class="vehicle-image">

                            <img
                                src="img/<?= e($row['gambar_utama']) ?>"
                                alt="<?= e($row['nama']) ?>">

                            <span class="vehicle-category">
                                <?= strtoupper(e($row['kategori'])) ?>
                            </span>

                        </div>

                        <div class="vehicle-info">

                            <p class="vehicle-brand">
                                <?= e($row['merek']) ?>
                            </p>

                            <h3>
                                <?= e($row['nama']) ?>
                            </h3>

                            <div class="vehicle-details">
                                <span><?= (int)$row['tahun'] ?></span>
                                <span><?= e($row['mesin']) ?></span>
                                <span><?= e($row['transmisi']) ?></span>
                            </div>

                            <div class="vehicle-bottom">

                                <div>
                                    <small>Mulai dari</small>
                                    <strong>
                                        <?= rupiah($row['harga']) ?>
                                    </strong>
                                </div>

                                <button
                                    class="detail-btn"
                                    data-name="<?= e($row['nama']) ?>"
                                    data-price="<?= rupiah($row['harga']) ?>"
                                    data-year="<?= (int)$row['tahun'] ?>"
                                    data-engine="<?= e($row['mesin']) ?>"
                                    data-transmission="<?= e($row['transmisi']) ?>"
                                    data-color="<?= e($row['warna']) ?>"

                                    data-image1="img/<?= e($row['gambar_utama']) ?>"
                                    <?php if (!empty($row['gambar_2'])): ?>data-image2="img/<?= e($row['gambar_2']) ?>"<?php endif; ?>

                                    <?php if (!empty($row['gambar_3'])): ?>data-image3="img/<?= e($row['gambar_3']) ?>"<?php endif; ?>

                                    <?php if (!empty($row['gambar_4'])): ?>data-image4="img/<?= e($row['gambar_4']) ?>"<?php endif; ?>>

                                    Lihat Detail
                                </button>

                            </div>

                        </div>

                    </article>
                    <?php endforeach; ?>

                </div>

                <!-- NO RESULT -->
                <div id="noResult" class="no-result">
                    <h3>Kendaraan tidak ditemukan</h3>
                    <p>
                        Coba gunakan kata pencarian yang berbeda.
                    </p>
                </div>

            </div>

        </section>


        <!-- =========================
             PROMO
        ========================== -->
        <section class="promo">

            <div class="container promo-content">

                <div>

                    <p class="section-label">
                         KONSULTASI
                    </p>

                    <h2>
                        Wujudkan kendaraan
                        impianmu hari ini.
                    </h2>

                    <p>
                        Dapatkan informasi harga, promo dan
                        ketersediaan kendaraan terbaru dari
                        showroom kami.
                    </p>

                </div>

                <a href="#kontak" class="btn btn-primary">
                    Konsultasi Sekarang
                </a>

            </div>

        </section>


        <!-- =========================
             TENTANG
        ========================== -->
        <section class="about section" id="tentang">

            <div class="container about-grid">

                <div class="about-image">

                    <img
                        src="img/toko.png"
                        alt="Showroom ZAHRAH MOTOR">

                    <div class="experience-box">
                        <strong>5+</strong>
                        <span>Tahun Pengalaman</span>
                    </div>

                </div>


                <div class="about-content">

                    <p class="section-label">
                        TENTANG ZAHRAH MOTOR
                    </p>

                    <h2>
                        Lebih dari sekadar
                        <span>showroom.</span>
                    </h2>

                    <p>
                        ZAHRAH MOTOR hadir sebagai showroom kendaraan
                        yang menyediakan berbagai pilihan motor dan mobil
                        untuk memenuhi kebutuhan masyarakat.
                    </p>

                    <p>
                        Kami mengutamakan kualitas kendaraan, informasi
                        yang transparan dan pelayanan yang profesional
                        agar pelanggan mendapatkan pengalaman terbaik.
                    </p>


                    <div class="about-features">

                        <div>
                            <span>✓</span>
                            <p>Unit berkualitas</p>
                        </div>

                        <div>
                            <span>✓</span>
                            <p>Harga kompetitif</p>
                        </div>

                        <div>
                            <span>✓</span>
                            <p>Pelayanan profesional</p>
                        </div>

                        <div>
                            <span>✓</span>
                            <p>Konsultasi kendaraan</p>
                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- =========================
             GALERI
        ========================== -->
        <section class="gallery section" id="galeri">

            <div class="container">

                <div class="section-heading">

                    <div>
                        <p class="section-label">
                            GALERI KAMI
                        </p>

                        <h2>
                            Galeri <span>Showroom</span>
                        </h2>
                    </div>

                    <p class="section-description">
                        Lihat koleksi kendaraan dan suasana
                        showroom ZAHRAH MOTOR.
                    </p>

                </div>


                <div class="gallery-grid">

                    <div class="gallery-item gallery-large">

                        <img
                            src="img/Agya.jpg"
                            alt="Mobil">

                    </div>

                    <div class="gallery-item">

                        <img
                            src="img/Mio_biru.jpg"
                            alt="Motor">

                    </div>

                    <div class="gallery-item">

                        <img
                            src="img/Pajero.jpg"
                            alt="Mobil sport">

                    </div>

                    <div class="gallery-item">

                        <img
                            src="img/NinjaRR.jpg"
                            alt="Motor">

                    </div>

                </div>

            </div>

        </section>


        <!-- =========================
             CONTACT
        ========================== -->
        <section class="contact section" id="kontak">

            <div class="container contact-grid">

                <div class="contact-info">

                    <p class="section-label">
                        HUBUNGI KAMI
                    </p>

                    <h2>
                        Mari ngobrol
                        <span>bersama kami.</span>
                    </h2>

                    <p>
                        Tertarik dengan salah satu kendaraan?
                        Hubungi kami untuk mendapatkan informasi
                        lebih lengkap mengenai harga dan ketersediaan.
                    </p>


                    <div class="contact-list">

                        <div class="contact-item">

                            <div class="contact-icon">
                                ☎
                            </div>

                            <div>
                                <small>Telepon</small>
                                <p>0823-5323-1630</p>
                            </div>

                        </div>

                        <div class="contact-item">

                            <div class="contact-icon">
                                ●
                            </div>

                            <div>
                                <small>Alamat</small>
                                <p>JL.Poros Pallangga, Pallangga, kec.Pallangga</p>
                            </div>

                        </div>

                    </div>

                </div>


                <!-- FORM -->
                <div class="contact-form-wrapper">

                    <?php if ($status === 'berhasil'): ?>
                        <p class="success-message" style="padding:12px 16px;margin-bottom:16px;border-radius:8px;background:#e6f7ec;color:#14663a;">
                            Pesan berhasil dikirim. Terima kasih telah menghubungi kami.
                        </p>
                    <?php endif; ?>

                    <form id="contactForm" action="proses/kontak.php" method="POST">

                        <div class="form-row">

                            <div class="form-group">

                                <label for="name">
                                    Nama
                                </label>

                                <input
                                    type="text"
                                    id="name"
                                    name="nama"
                                    placeholder="Masukkan nama"
                                    required>

                            </div>


                            <div class="form-group">

                                <label for="phone">
                                    Nomor HP
                                </label>

                                <input
                                    type="tel"
                                    id="phone"
                                    name="telepon"
                                    placeholder="08xxxxxxxxxx"
                                    required>

                            </div>

                        </div>


                        <div class="form-group">

                            <label for="email">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="nama@email.com"
                                required>

                        </div>


                        <div class="form-group">

                            <label for="vehicle">
                                Kendaraan yang diminati
                            </label>

                            <select id="vehicle" name="kendaraan">

                                <option value="">
                                    Pilih kendaraan
                                </option>

                                <?php foreach ($kendaraan as $row): ?>
                                <option value="<?= e($row['nama'] . ' (' . $row['tahun'] . ')') ?>">
                                    <?= e($row['nama']) ?> (<?= (int)$row['tahun'] ?>)
                                </option>
                                <?php endforeach; ?>

                            </select>

                        </div>


                        <div class="form-group">

                            <label for="message">
                                Pesan
                            </label>

                            <textarea
                                id="message"
                                name="pesan"
                                rows="5"
                                placeholder="Tuliskan pesan Anda..."
                                required></textarea>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary form-submit">

                            Kirim Pesan

                        </button>

                    </form>

                </div>

            </div>

        </section>

    </main>


    <!-- =========================
         MODAL DETAIL
    ========================== -->
    <div class="modal" id="vehicleModal">

        <div class="modal-overlay"></div>

        <div class="modal-content">

            <button
                class="modal-close"
                id="modalClose">
                ×
            </button>

            <div class="modal-gallery">

                <div class="gallery-main" style="
                    position: relative;
                    width: 100%;
                    height: 520px;
                    overflow: hidden;
                    background: #111;
                    border-radius: 12px;
                ">

                    <!-- FOTO UTAMA -->
                    <img
                        id="modalImage"
                        alt="Detail Kendaraan"
                        style="
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                            display: block;
                        "
                    >

                    <!-- TOMBOL KIRI -->
                    <button
                        type="button"
                        class="gallery-btn gallery-prev"
                        id="galleryPrev"
                        style="
                            position: absolute;
                            left: 15px;
                            top: 50%;
                            transform: translateY(-50%);
                            width: 45px;
                            height: 45px;
                            padding: 0;
                            margin: 0;
                            border: none;
                            border-radius: 50%;
                            background: rgba(0, 0, 0, 0.75);
                            color: white;
                            font-size: 22px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            cursor: pointer;
                            z-index: 9999;
                        "
                    >
                        ❮
                    </button>

                    <!-- TOMBOL KANAN -->
                    <button
                        type="button"
                        class="gallery-btn gallery-next"
                        id="galleryNext"
                        style="
                            position: absolute;
                            right: 15px;
                            top: 50%;
                            transform: translateY(-50%);
                            width: 45px;
                            height: 45px;
                            padding: 0;
                            margin: 0;
                            border: none;
                            border-radius: 50%;
                            background: rgba(0, 0, 0, 0.75);
                            color: white;
                            font-size: 22px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            cursor: pointer;
                            z-index: 9999;
                        "
                    >
                        ❯
                    </button>

                </div>

                <!-- Thumbnail -->
                <div class="gallery-thumbnails">

                    <img class="thumbnail active" data-index="0" src="">

                    <img class="thumbnail" data-index="1" src="">

                    <img class="thumbnail" data-index="2" src="">

                    <img class="thumbnail" data-index="3" src="">

                </div>

            </div>

            <div class="modal-info">

                <p class="section-label">
                    VEHICLE DETAIL
                </p>

                <h2 id="modalName">
                    Nama Kendaraan
                </h2>

                <div class="modal-price" id="modalPrice">
                    Rp 0
                </div>

                <div class="modal-specs">

                    <div>
                        <small>Tahun</small>
                        <strong id="modalYear"></strong>
                    </div>

                    <div>
                        <small>Mesin</small>
                        <strong id="modalEngine"></strong>
                    </div>

                    <div>
                        <small>Transmisi</small>
                        <strong id="modalTransmission"></strong>
                    </div>

                    <div>
                        <small>Warna</small>
                        <strong id="modalColor"></strong>
                    </div>

                </div>

                <a
                    href="#kontak"
                    class="btn btn-primary modal-contact"
                    id="modalContact">

                    Saya Tertarik

                </a>

            </div>

        </div>

    </div>


    <!-- =========================
         FOOTER
    ========================== -->
    <footer class="footer">

        <div class="container footer-grid">

            <div>

                <a href="#home" class="logo">
                    ZAHRAH<span>MOTOR</span>
                </a>

                <p>
                    Solusi kendaraan berkualitas
                    untuk perjalanan terbaik Anda.
                </p>

            </div>


            <div>

                <h4>Navigasi</h4>

                <a href="#home">Home</a>
                <a href="#koleksi">Koleksi</a>
                <a href="#tentang">Tentang</a>
                <a href="#kontak">Kontak</a>

            </div>


            <div>

                <h4>Jam Operasional</h4>

                <p>Senin - Minggu </p>
                <p>08:00 - 23:00 </p>

            </div>


            <div>

                <h4>Ikuti Kami</h4>

                <div class="social-links">

                    <a
                        href="https://wa.me/6282353231630"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="WhatsApp">
                        WA
                    </a>

                </div>

            </div>

        </div>


        <div class="footer-bottom">

        </div>

    </footer>


    <!-- BACK TO TOP -->
    <button
        id="backToTop"
        class="back-to-top">
        ↑
    </button>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- JavaScript -->
    <script src="js/script.js"></script>

</body>
</html>