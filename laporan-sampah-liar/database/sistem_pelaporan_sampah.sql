CREATE DATABASE IF NOT EXISTS sistem_pelaporan_sampah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistem_pelaporan_sampah;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE laporan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_laporan VARCHAR(20) NOT NULL,
    nama_pelapor VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    jenis_sampah VARCHAR(100) NOT NULL,
    deskripsi TEXT NOT NULL,
    foto VARCHAR(255),
    latitude VARCHAR(50) NOT NULL,
    longitude VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL,
    status ENUM('menunggu','diproses','selesai') DEFAULT 'menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE progress_laporan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    laporan_id INT NOT NULL,
    keterangan TEXT NOT NULL,
    foto_progress VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_progress_laporan FOREIGN KEY (laporan_id) REFERENCES laporan(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO users (nama, email, password, role) VALUES
('Administrator', 'admin@mail.com', '$2b$10$ecAVMGLkb56XcD.A7VuvXujwBveWuu7vfQ1JfSUj8hutFLOpwJgUe', 'admin');

INSERT INTO laporan (kode_laporan, nama_pelapor, email, no_hp, jenis_sampah, deskripsi, foto, latitude, longitude, alamat, status) VALUES
('LAP-20260529-1001', 'Budi Santoso', 'budi@mail.com', '081234567890', 'organik', 'Tumpukan sampah di dekat taman RT 02.', 'sample1.jpg', '-6.200100', '106.816500', 'Jl. Contoh No. 1, Jakarta', 'diproses'),
('LAP-20260529-1002', 'Siti Aisyah', 'siti@mail.com', '081298765432', 'anorganik', 'Sampah plastik menumpuk di pinggir jalan.', 'sample2.jpg', '-6.201200', '106.817800', 'Jl. Contoh No. 2, Jakarta', 'menunggu'),
('LAP-20260529-1003', 'Andi Pratama', 'andi@mail.com', '081377788899', 'tps_ilegal', 'Ada TPS ilegal di lahan kosong belakang pasar.', 'sample3.jpg', '-6.198900', '106.814200', 'Jl. Contoh No. 3, Jakarta', 'selesai');

INSERT INTO progress_laporan (laporan_id, keterangan, foto_progress) VALUES
(1, 'Tim kebersihan melakukan pengecekan lokasi.', 'progress1.jpg'),
(1, 'Pembersihan sebagian sudah dilakukan.', 'progress2.jpg'),
(3, 'Laporan ditutup karena lokasi sudah dibersihkan.', 'progress3.jpg');
