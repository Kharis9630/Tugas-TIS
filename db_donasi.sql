-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 08, 2025 at 05:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_donasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `donasi`
--

CREATE TABLE `donasi` (
  `id` int(11) NOT NULL,
  `donatur_id` int(11) DEFAULT NULL,
  `lembaga_id` int(11) DEFAULT NULL,
  `kategori` enum('pendidikan','kesehatan','bencana','lainnya') NOT NULL,
  `nominal` decimal(10,2) NOT NULL,
  `status` enum('pending','terima','batal') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `bank_tujuan` varchar(50) NOT NULL,
  `no_rekening` varchar(50) NOT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donasi`
--

INSERT INTO `donasi` (`id`, `donatur_id`, `lembaga_id`, `kategori`, `nominal`, `status`, `created_at`, `bank_tujuan`, `no_rekening`, `bukti_transfer`) VALUES
(41, 1, 1, 'pendidikan', 50000.00, 'terima', '2025-02-05 16:35:25', 'BCA', '123-456-7890', '245-2452743_023-307-kb-grand-blue-anime-faces-hd.png'),
(45, 1, 1, 'pendidikan', 333.00, 'terima', '2025-02-05 17:37:44', 'BCA', '123-456-7890', 'R.png'),
(46, 10, 1, 'pendidikan', 5000000.00, 'terima', '2025-02-06 04:30:50', 'BCA', '123-456-7890', 't59779.png'),
(47, 11, 2, 'kesehatan', 2000000.00, 'terima', '2025-02-06 04:47:02', 'BRI', '555-666-777', 'farp,small,wall_texture,product,750x1000.jpg'),
(48, 11, 2, 'kesehatan', 2000000.00, 'terima', '2025-02-06 04:59:07', 'BRI', '555-666-777', 'farp,small,wall_texture,product,750x1000.jpg'),
(49, 15, 3, 'pendidikan', 2000000.00, 'terima', '2025-02-06 16:19:06', 'BRI', '555-666-777', 'st,small,507x507-pad,600x600,f8f8f8.u2.jpg'),
(50, 2, 4, 'bencana', 250000.00, 'terima', '2025-02-06 17:01:41', 'BCA', '123-456-7890', 't59779.png'),
(51, 10, 2, 'kesehatan', 150000.00, 'terima', '2025-02-06 17:02:38', 'BCA', '', 't59779.png'),
(52, 1, 4, 'bencana', 250000.00, 'terima', '2025-02-08 09:25:23', 'Mandiri', '987-654-3210', 't59779.png'),
(53, 2, 3, 'pendidikan', 99999999.99, 'terima', '2025-02-08 09:50:19', 'BCA', '123-456-7890', 'R.jpg'),
(54, 19, 2, 'kesehatan', 600000.00, 'terima', '2025-02-08 11:23:44', 'BNI', '222-333-444', 'R.png'),
(56, 19, 3, 'pendidikan', 200000.00, 'batal', '2025-02-08 14:42:44', 'BNI', '222-333-444', 'Dota-2-Emblem.png'),
(57, 19, 2, 'kesehatan', 300000.00, 'terima', '2025-02-08 14:48:04', 'BNI', '222-333-444', 'W.jpg'),
(58, 19, 1, 'pendidikan', 150000.00, 'batal', '2025-02-08 14:57:11', 'BCA', '123-456-7890', 'dcfvg.jpg'),
(60, 19, 1, 'pendidikan', 10100.00, 'batal', '2025-02-08 15:03:43', 'BNI', '222-333-444', 'kmjnhbg.jpg'),
(61, 19, 1, 'pendidikan', 11222.00, 'batal', '2025-02-08 15:08:13', 'Mandiri', '987-654-3210', 'Asd.png'),
(62, 19, 2, 'kesehatan', 12345.00, 'batal', '2025-02-08 15:12:09', 'BRI', '555-666-777', 'Mjha.jpg'),
(76, 19, 1, 'pendidikan', 77777.00, 'batal', '2025-02-08 16:00:05', 'BRI', '555-666-777', 'nhbgvf.jpg'),
(77, 19, 1, 'pendidikan', 44552.00, 'pending', '2025-02-08 16:02:52', 'Mandiri', '987-654-3210', 'Untitled_1739030586.jpg'),
(79, 19, 2, 'kesehatan', 45000.00, 'pending', '2025-02-08 16:12:30', 'BCA', '123-456-7890', 'kmjnhb.jpg'),
(80, 19, 4, 'bencana', 450000.00, 'pending', '2025-02-08 16:17:09', 'Mandiri', '987-654-3210', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `lembaga_id` int(11) DEFAULT NULL,
  `nama_lembaga` varchar(255) DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `nominal` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id`, `lembaga_id`, `nama_lembaga`, `deskripsi`, `nominal`, `created_at`) VALUES
(15, 3, 'Yayasan Pendidikan Cerdas', 'penggunaan dana untuk membeli buku ', 50000.00, '2025-02-05 14:38:56'),
(16, 4, 'Luka mereka luka kita semua', 'membantu korban banjir', 20000.00, '2025-02-05 14:40:15'),
(17, 3, 'Yayasan Pendidikan Cerdas', 'we outsmart em', 24.00, '2025-02-05 16:42:45'),
(18, 3, 'Yayasan Pendidikan Cerdas', 'penggunaan dana untuk pembelian buku sekolah anak sd', 2000000.00, '2025-02-06 04:33:59'),
(19, 2, 'Lembaga Kesehatan Indonesia', 'dana digunakan untuk membeli vaksin', 1000000.00, '2025-02-06 04:49:46'),
(20, 3, 'Yayasan Pendidikan Cerdas', 'dana digunakan untuk membeli buku ', 1000000.00, '2025-02-06 16:22:01'),
(21, 3, 'Yayasan Pendidikan Cerdas', 'well done sir', 25000.00, '2025-02-08 11:26:19');

-- --------------------------------------------------------

--
-- Table structure for table `lembaga_sosial`
--

CREATE TABLE `lembaga_sosial` (
  `id` int(11) NOT NULL,
  `nama_lembaga` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lembaga_sosial`
--

INSERT INTO `lembaga_sosial` (`id`, `nama_lembaga`, `email`) VALUES
(1, 'Yayasan Peduli Anak', 'peduli@anak.com'),
(2, 'Lembaga Kesehatan Indonesia', 'sehat@indonesia.id'),
(3, 'Yayasan Pendidikan Cerdas', 'outsmart@pendidikan.net'),
(4, 'Luka mereka luka kita semua', 'luka@mail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('donatur','lembaga','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`) VALUES
(1, 'gabe newell', 'valve@corp.com', '$2y$10$FS9DAZU8D4pjPoGgacKOVemRhEjXpdOmMkVW6JgE8KchfNpp49j0a', 'donatur'),
(2, 'mas elon', 'spacex@mars.moon', '$2y$10$vzc/zWOFibknTeGpBuvtKO94USgaHZA64eK6yv4MyinezouikzG3a', 'donatur'),
(3, 'Yayasan Peduli Anak', 'peduli@anak.com', '$2y$10$MUPy3/EsRUroWyNVsNkPl.KoH0UlMf1OQdsvQZ7lSiUHp4JN/9e3i', 'lembaga'),
(4, 'admin jamal', 'admin@hood.com', '$2y$10$Bc9ttNsG2DdNzvG4rakKNO67AXnLnLPky0rCcrjVr7yWkKvCNu.ym', 'admin'),
(5, 'sugun', 'sugunkroco666@gmail.com', '$2y$10$AToPCvt1zwo7GbOI5ToLT.qBLMxNJAoME09ZOsGJhTbmkm2ryUSnS', 'donatur'),
(6, 'Lembaga Kesehatan Indonesia', 'sehat@indonesia.id', '$2y$10$WJcyb7ZBjZ3PraNN/dNVAOMLOC1FRZla4JiowntihXtXc63w/sUAu', 'lembaga'),
(7, 'Yayasan Pendidikan Cerdas', 'outsmart@pendidikan.net', '$2y$10$raVlPYFoCxh5naZKAtmFHu/6j8Z8iTtfjz686YMyFyKooFlthm6Ty', 'lembaga'),
(8, 'Luka mereka luka kita semua', 'luka@mail.com', '$2y$10$Mh4hpf2XjcugMVi/LaSqs.qJbu6mzEUbejGmXuEudEz0Ev7RgPHNq', 'lembaga'),
(9, 'Ujang Dasa', 'jang@ujang.com', '$2y$10$sCp9mn9v49r8QWKUeie4MOE/yL61VZolTJ96bKjoZCn1iyI7txfCS', 'donatur'),
(10, 'mirana', 'memid@throw.com', '$2y$10$3q.GuJKejrta0N9rDdIYsemeO4xJ5gVkDQK94j5xAkFfuO3T55ih6', 'donatur'),
(11, 'Anurdazle', 'anur@gmail.com', '$2y$10$1SBJQPlLvi.4cqucqxFtk.yldf0uZ0rjeUMq9h8qQiWuqvvTAVdB6', 'donatur'),
(13, 'lmao', 'rofl@king.com', '', 'lembaga'),
(14, 'Stop scrolling', 'getsomehelp@gmail.com', '$2y$10$QrHVHjDQ2SJ9EvValjzjoO8CkuRBcYCpOHNwMWuyL..RPJ..qcGuO', 'donatur'),
(15, 'Kilua', 'kilua@gmail.com', '$2y$10$NR/BG1BNhDxflkgTyiH8jOGGChE0jHY2vhi50mVjTliKPdKLEPDbK', 'donatur'),
(18, 'well', 'get@well.soon', '', 'lembaga'),
(19, 'Arteezy', 'cry@baby.com', '$2y$10$5JKpADMyLOOMOOj53TTcke5gOW81b2bzw3/l6BObjy5g9C1fHbkXC', 'donatur'),
(21, 'Arteezy', 'what@you.mean', '$2y$10$LSpTfGITYjrr.NjdgbjPzORVXo62Uc6geSEO53QRrPib2HH52pVia', 'donatur');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donasi`
--
ALTER TABLE `donasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donatur_id` (`donatur_id`),
  ADD KEY `lembaga_id` (`lembaga_id`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lembaga_id` (`lembaga_id`);

--
-- Indexes for table `lembaga_sosial`
--
ALTER TABLE `lembaga_sosial`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donasi`
--
ALTER TABLE `donasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `lembaga_sosial`
--
ALTER TABLE `lembaga_sosial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donasi`
--
ALTER TABLE `donasi`
  ADD CONSTRAINT `donasi_ibfk_1` FOREIGN KEY (`donatur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `donasi_ibfk_2` FOREIGN KEY (`lembaga_id`) REFERENCES `lembaga_sosial` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`lembaga_id`) REFERENCES `lembaga_sosial` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
