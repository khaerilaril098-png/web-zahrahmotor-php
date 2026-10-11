-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Okt 2026 pada 08.48
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zahrah_motor`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$fHfmOwCMjzlY6h0pqiSmA.iPPY8fVr5Otlhfl19DubJ6bKalcj3Cy');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `merek` varchar(100) NOT NULL,
  `kategori` enum('motor','mobil') NOT NULL,
  `tahun` int(11) NOT NULL,
  `mesin` varchar(50) DEFAULT NULL,
  `transmisi` varchar(50) DEFAULT NULL,
  `warna` varchar(50) DEFAULT NULL,
  `harga` decimal(15,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar_utama` varchar(255) NOT NULL,
  `gambar_2` varchar(255) DEFAULT NULL,
  `gambar_3` varchar(255) DEFAULT NULL,
  `gambar_4` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kendaraan`
--

INSERT INTO `kendaraan` (`id`, `nama`, `merek`, `kategori`, `tahun`, `mesin`, `transmisi`, `warna`, `harga`, `deskripsi`, `gambar_utama`, `gambar_2`, `gambar_3`, `gambar_4`, `created_at`) VALUES
(1, 'Honda Beat F1', 'HONDA', 'motor', 2015, '110 CC', 'Automatic', 'Merah', 10000000.00, '', 'Beat.jpg', 'beatdepan.jpg', 'beatbelakang.jpg', 'beatsamping.jpg', '2026-10-03 05:45:10'),
(2, 'Yamaha Mio M3', 'YAMAHA', 'motor', 2024, '125 CC', 'Automatic', 'Hitam', 16000000.00, NULL, 'Mio_m3.jpg', 'miomdepan.jpg', 'miomsamping.jpg', 'miombelakang.jpg', '2026-10-03 05:45:10'),
(3, 'Yamaha Mio Sporty', 'YAMAHA', 'motor', 2010, '115 CC', 'Automatic', 'Biru', 10000000.00, NULL, 'Mio_biru.jpg', 'miobirudepan.jpg', 'miobirusamping.jpg', 'miobirubelakang.jpg', '2026-10-03 05:45:10'),
(4, 'Yamaha Mio Sporty', 'YAMAHA', 'motor', 2011, '115 CC', 'Automatic', 'Hitam', 11000000.00, NULL, 'Mio_hitam.jpg', 'miohitamdepan.jpg', 'miohitamsamping.jpg', 'miohitambelakang.jpg', '2026-10-03 05:45:10'),
(5, 'Yamaha Ninja Kawasaki RR', 'YAMAHA', 'motor', 2013, '150 CC', 'Manual', 'Kuning', 100000000.00, NULL, 'NinjaRR.jpg', 'ninjasamping.jpg', NULL, NULL, '2026-10-03 05:45:10'),
(6, 'Yamaha FizR', 'YAMAHA', 'motor', 2003, '110 CC', 'Manual', 'Hitam', 15000000.00, NULL, 'Fizr.jpg', 'fizrdepan.jpg', 'fizrsamping.jpg', 'fizrmunri.jpg', '2026-10-03 05:45:10'),
(7, 'Honda Jazz', 'HONDA', 'mobil', 2008, '1500 CC', 'Automatic', 'White', 70000000.00, NULL, 'Jazz.jpg', 'jassamping.jpg', 'jazbelakang.jpg', 'jazmunri.jpg', '2026-10-03 05:45:10'),
(8, 'Toyota Agya', 'TOYOTA', 'mobil', 2014, '1500 CC', 'Automatic', 'Black', 87000000.00, NULL, 'Agya.jpg', 'agyadepan.jpg', 'agyasamping.jpg', 'agyabelakang.jpg', '2026-10-03 05:45:10'),
(9, 'Mitsubishi Pajero', 'MITSUBISHI', 'mobil', 2015, '2400 CC', 'Automatic', 'Black', 315000000.00, NULL, 'Pajero.jpg', 'pajerodepan.jpg', 'pajerosamping.jpg', NULL, '2026-10-03 05:45:10'),
(10, 'Honda Brio', 'HONDA', 'mobil', 2017, '1500 CC', 'Manual', 'Black', 82000000.00, NULL, 'Brio.jpg', 'briodepan.jpg', 'briosamping.jpg', NULL, '2026-10-03 05:45:10'),
(11, 'Toyota Hilux', 'TOYOTA', 'mobil', 2023, '2400 CC', 'Automatic', 'Black', 400000000.00, NULL, 'Hilux.jpg', 'hiluxdepan.jpg', 'hiluxsamping.jpg', 'hiluxbelakang.jpg', '2026-10-03 05:45:10'),
(12, 'Daihatsu Xenia', 'DAIHATSU', 'mobil', 2016, '1500 CC', 'Automatic', 'Black', 127000000.00, NULL, 'Xenia.jpg', 'xeniadepan.jpg', 'xeniasamping.jpg', 'xeniabelakang.jpg', '2026-10-03 05:45:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan`
--

CREATE TABLE `pesan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telepon` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `kendaraan` varchar(100) DEFAULT NULL,
  `pesan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
