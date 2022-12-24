-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2022 at 05:18 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `datapengguna`
--

-- --------------------------------------------------------

--
-- Table structure for table `info_pengguna`
--

CREATE TABLE `info_pengguna` (
  `id` int(3) NOT NULL,
  `nama_pengguna` varchar(255) NOT NULL,
  `no_ndp` varchar(10) NOT NULL,
  `no_kp` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `jantina` varchar(50) NOT NULL,
  `no_hp` varchar(50) NOT NULL,
  `tarikh_daftar` datetime DEFAULT NULL,
  `logmasuk_terakhir` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `info_pengguna`
--

INSERT INTO `info_pengguna` (`id`, `nama_pengguna`, `no_ndp`, `no_kp`, `email`, `password`, `jantina`, `no_hp`, `tarikh_daftar`, `logmasuk_terakhir`) VALUES
(11, 'Fulan binti Fulan', '246346723', '9178238971', 'test@gmail.com', 'abc', 'perempuan', '012341231121', '2022-12-22 23:25:52', '2022-12-22 12:14:46');

-- --------------------------------------------------------

--
-- Table structure for table `permohonan`
--

CREATE TABLE `permohonan` (
  `id` int(11) NOT NULL,
  `id_pengguna` int(11) DEFAULT NULL,
  `tarikh_keluar` date NOT NULL,
  `tarikh_masuk` date NOT NULL,
  `tujuan` varchar(10) NOT NULL,
  `tempat_tujuan` varchar(255) NOT NULL,
  `status_permohonan` varchar(50) NOT NULL,
  `tarikh_permohonan` datetime DEFAULT NULL,
  `tarikh_kemaskini_pentadbir` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permohonan`
--

INSERT INTO `permohonan` (`id`, `id_pengguna`, `tarikh_keluar`, `tarikh_masuk`, `tujuan`, `tempat_tujuan`, `status_permohonan`, `tarikh_permohonan`, `tarikh_kemaskini_pentadbir`) VALUES
(5, 11, '2022-12-27', '2022-12-30', 'balik', 'saya kena pergi ke sana', 'LULUS', '2022-12-23 06:32:16', NULL),
(6, 11, '2022-12-30', '2022-12-31', 'outing', 'haha', 'DITOLAK', '2022-12-23 09:30:27', '2022-12-23 09:33:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `info_pengguna`
--
ALTER TABLE `info_pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_kp_idx` (`no_kp`);

--
-- Indexes for table `permohonan`
--
ALTER TABLE `permohonan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `info_pengguna`
--
ALTER TABLE `info_pengguna`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `permohonan`
--
ALTER TABLE `permohonan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
