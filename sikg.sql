-- phpMyAdmin SQL Dump
-- version 5.2.2deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 12, 2025 at 08:52 AM
-- Server version: 11.8.3-MariaDB-0+deb13u1 from Debian
-- PHP Version: 8.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sikg`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nip_guru` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` char(1) NOT NULL,
  `catatan` text DEFAULT NULL,
  `nip_admin` bigint(20) UNSIGNED DEFAULT NULL,
  `waktu_modifikasi` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `nip` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `password_hash` varchar(64) NOT NULL,
  `jabatan` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`nip`, `nama`, `password_hash`, `jabatan`) VALUES
(19790307200513013, 'Edy Dian Ahmad', '6ca13d52ca70c883e0f0bb101e425a89e8624de51db2d2392593af6a84118090', 3),
(19821028200811011, 'Wisnu Kadek', '6ca13d52ca70c883e0f0bb101e425a89e8624de51db2d2392593af6a84118090', 1),
(19860526201114014, 'Indra Ellen Herman', '6ca13d52ca70c883e0f0bb101e425a89e8624de51db2d2392593af6a84118090', 4),
(19921218201915015, 'Bayu Putu Erwin', '6ca13d52ca70c883e0f0bb101e425a89e8624de51db2d2392593af6a84118090', 5),
(19961114202112012, 'Titin Santi Mochamad', '6ca13d52ca70c883e0f0bb101e425a89e8624de51db2d2392593af6a84118090', 2);

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `nip` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `password_hash` varchar(64) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_telp` bigint(20) UNSIGNED NOT NULL,
  `nip_admin` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`nip`, `nama`, `password_hash`, `alamat`, `no_telp`, `nip_admin`) VALUES
(19770619200706006, 'Leni Udinputri', '6ca13d52ca70c883e0f0bb101e425a89e8624de51db2d2392593af6a84118090', 'Jl. Radin Inten II Jl. Kav. Dki No.31 No.A30 13440', 6282787261901, 19821028200811011),
(19791224201002002, 'Putu Dina Merry Hendriputri', '6ca13d52ca70c883e0f0bb101e425a89e8624de51db2d2392593af6a84118090', 'Jl. Kembangan Raya No.8, 11610', 6281976537198, 19821028200811011),
(19810122200609009, 'Joni Ariputra', '6ca13d52ca70c883e0f0bb101e425a89e8624de51db2d2392593af6a84118090', 'Jl. Kopral Bosan No.11, RT.002/RW.022 17148', 6284961207012, 19821028200811011),
(19850430200904004, 'Ratnatika Merry Yusuf', '6ca13d52ca70c883e0f0bb101e425a89e8624de51db2d2392593af6a84118090', 'Jl. Insinyur H. Juanda No.141, RT.001/RW.001 17111', 6281563010921, 19821028200811011),
(19870315201401001, 'Andri Hartonoputra', '6ca13d52ca70c883e0f0bb101e425a89e8624de51db2d2392593af6a84118090', 'Jl. Walang Permai No.39, RT.6/RW.12 14260', 6287264917653, 19821028200811011),
(19880909201207007, 'Indra Sutrisno Andi', '6ca13d52ca70c883e0f0bb101e425a89e8624de51db2d2392593af6a84118090', 'Jl. Raya Jatiwaringin No.26, RT.007/RW.009 13620', 6284809803102, 19821028200811011),
(19900211201603003, 'Gunawan Abdullah Hadiputra', '6ca13d52ca70c883e0f0bb101e425a89e8624de51db2d2392593af6a84118090', 'Jl. Sultan Hasanudin No.39 17510', 6281808620182, 19821028200811011),
(19931125201805005, 'Udin Ade Idaputra', '6ca13d52ca70c883e0f0bb101e425a89e8624de51db2d2392593af6a84118090', 'Jl. Pd. Kelapa Raya No.16, RT.16/RW.2 13450', 6285191820312, 19821028200811011),
(19940617201710010, 'Desi Yani Sugengputri', '6ca13d52ca70c883e0f0bb101e425a89e8624de51db2d2392593af6a84118090', 'Jl. Jatibening no 40. Komplek Sentra Kota Jatibening RT.001/RW.003 , 17412', 6286898616103, 19821028200811011),
(19951205202008008, 'Nuraini Ita Rizkiputri', '6ca13d52ca70c883e0f0bb101e425a89e8624de51db2d2392593af6a84118090', 'Jl. Radin Inten II No.40 1, RT.1/RW.7 13810', 627917260999, 19821028200811011);

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id`, `nama`) VALUES
(1, 'Kepala Sekolah'),
(2, 'Wakil Kepala Sekolah'),
(3, 'Waka Kesiswaan'),
(4, 'Waka Kurikulum'),
(5, 'Tata Usaha');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nip_guru` (`nip_guru`),
  ADD KEY `nip_admin` (`nip_admin`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`nip`),
  ADD KEY `jabatan` (`jabatan`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`nip`),
  ADD KEY `nip_admin` (`nip_admin`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`nip_guru`) REFERENCES `guru` (`nip`),
  ADD CONSTRAINT `absensi_ibfk_2` FOREIGN KEY (`nip_admin`) REFERENCES `admin` (`nip`);

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`jabatan`) REFERENCES `jabatan` (`id`);

--
-- Constraints for table `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`nip_admin`) REFERENCES `admin` (`nip`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
