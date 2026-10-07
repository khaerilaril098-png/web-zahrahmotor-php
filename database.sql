CREATE DATABASE IF NOT EXISTS zahrah_motor CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE zahrah_motor;

CREATE TABLE IF NOT EXISTS kendaraan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    merek VARCHAR(100) NOT NULL,
    kategori ENUM('motor', 'mobil') NOT NULL,
    tahun INT NOT NULL,
    mesin VARCHAR(50),
    transmisi VARCHAR(50),
    warna VARCHAR(50),
    harga DECIMAL(15,2) NOT NULL,
    deskripsi TEXT,
    gambar_utama VARCHAR(255) NOT NULL,
    gambar_2 VARCHAR(255),
    gambar_3 VARCHAR(255),
    gambar_4 VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS pesan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    telepon VARCHAR(25) NOT NULL,
    email VARCHAR(100) NOT NULL,
    kendaraan VARCHAR(100),
    pesan TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Data awal kendaraan (nama file gambar saja, tanpa "img/", karena index.php menambahkannya)
INSERT INTO kendaraan (nama, merek, kategori, tahun, mesin, transmisi, warna, harga, gambar_utama, gambar_2, gambar_3, gambar_4) VALUES
('Honda Beat F1',            'HONDA',      'motor', 2015, '110 CC',  'Automatic', 'Merah',  8500000,   'Beat.jpg',      'beatdepan.jpg',       'beatbelakang.jpg',     'beatsamping.jpg'),
('Yamaha Mio M3',            'YAMAHA',     'motor', 2024, '125 CC',  'Automatic', 'Hitam',  16000000,  'Mio_m3.jpg',    'miomdepan.jpg',       'miomsamping.jpg',      'miombelakang.jpg'),
('Yamaha Mio Sporty',        'YAMAHA',     'motor', 2010, '115 CC',  'Automatic', 'Biru',   10000000,  'Mio_biru.jpg',  'miobirudepan.jpg',    'miobirusamping.jpg',   'miobirubelakang.jpg'),
('Yamaha Mio Sporty',        'YAMAHA',     'motor', 2011, '115 CC',  'Automatic', 'Hitam',  11000000,  'Mio_hitam.jpg', 'miohitamdepan.jpg',   'miohitamsamping.jpg',  'miohitambelakang.jpg'),
('Yamaha Ninja Kawasaki RR', 'YAMAHA',     'motor', 2013, '150 CC',  'Manual',    'Kuning', 100000000, 'NinjaRR.jpg',   'ninjasamping.jpg',    NULL,                   NULL),
('Yamaha FizR',              'YAMAHA',     'motor', 2003, '110 CC',  'Manual',    'Hitam',  15000000,  'Fizr.jpg',      'fizrdepan.jpg',       'fizrsamping.jpg',      'fizrmunri.jpg'),
('Honda Jazz',               'HONDA',      'mobil', 2008, '1500 CC', 'Automatic', 'White',  70000000,  'Jazz.jpg',      'jassamping.jpg',      'jazbelakang.jpg',      'jazmunri.jpg'),
('Toyota Agya',              'TOYOTA',     'mobil', 2014, '1500 CC', 'Automatic', 'Black',  87000000,  'Agya.jpg',      'agyadepan.jpg',       'agyasamping.jpg',      'agyabelakang.jpg'),
('Mitsubishi Pajero',        'MITSUBISHI', 'mobil', 2015, '2400 CC', 'Automatic', 'Black',  315000000, 'Pajero.jpg',    'pajerodepan.jpg',     'pajerosamping.jpg',    NULL),
('Honda Brio',               'HONDA',      'mobil', 2017, '1500 CC', 'Manual',    'Black',  82000000,  'Brio.jpg',      'briodepan.jpg',       'briosamping.jpg',      NULL),
('Toyota Hilux',             'TOYOTA',     'mobil', 2023, '2400 CC', 'Automatic', 'Black',  400000000, 'Hilux.jpg',     'hiluxdepan.jpg',      'hiluxsamping.jpg',     'hiluxbelakang.jpg'),
('Daihatsu Xenia',           'DAIHATSU',   'mobil', 2016, '1500 CC', 'Automatic', 'Black',  127000000, 'Xenia.jpg',     'xeniadepan.jpg',      'xeniasamping.jpg',     'xeniabelakang.jpg');