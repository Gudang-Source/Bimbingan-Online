-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 19 Jun 2020 pada 11.27
-- Versi server: 5.7.26
-- Versi PHP: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bimbingan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

DROP TABLE IF EXISTS `dosen`;
CREATE TABLE IF NOT EXISTS `dosen` (
  `NPP` int(30) NOT NULL,
  `Nama` varchar(300) NOT NULL,
  `Jabatan` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`NPP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `dosen`
--

INSERT INTO `dosen` (`NPP`, `Nama`, `Jabatan`, `created_at`, `updated_at`) VALUES
(11223360, 'Herdiesel Santoso', 'Kaprodi Sistem Informasi', '2020-05-13 09:04:35', '0000-00-00 00:00:00'),
(11223362, 'Wahyu Widodo', 'Kaprodi Teknik Informatika', '2020-05-13 09:04:58', '0000-00-00 00:00:00'),
(11223364, 'Andri Safriyanto', 'Dosen', '2020-05-13 09:05:55', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'members', 'General User'),
(3, 'mahasiswa', 'Mahasiswa'),
(6, 'dosen', 'Dosen'),
(7, 'kaprodi', 'Ketua Program Studi'),
(11, 'kaprodi_SI', 'Ketua Program Studi Sistem Informasi'),
(12, 'kaprodi_TI', 'Ketua Program Studi Teknik Informatika');

-- --------------------------------------------------------

--
-- Struktur dari tabel `group_pesan`
--

DROP TABLE IF EXISTS `group_pesan`;
CREATE TABLE IF NOT EXISTS `group_pesan` (
  `GroupPesanID` int(30) NOT NULL AUTO_INCREMENT,
  `Name1` int(30) NOT NULL,
  `Name2` int(30) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`GroupPesanID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `ip_address`, `login`, `time`) VALUES
(3, '::1', 'admin@admin.com', 1592549833);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

DROP TABLE IF EXISTS `mahasiswa`;
CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `NIM` int(30) NOT NULL,
  `Nama` varchar(300) NOT NULL,
  `Prodi` varchar(100) NOT NULL,
  `Angkatan` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`NIM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`NIM`, `Nama`, `Prodi`, `Angkatan`, `created_at`, `updated_at`) VALUES
(11170386, 'Ana Suryaningsih', 'Sistem Informasi', '2017', '2020-05-13 08:57:56', '2020-05-17 09:20:07'),
(11170392, 'Intan Hidayah', 'Sistem Informasi', '2017', '2020-05-13 08:57:32', '2020-05-17 09:20:01'),
(11170403, 'Yulistiana', 'Sistem Informasi', '2017', '2020-05-13 08:58:53', '2020-05-17 09:19:54'),
(12171564, 'Linda Pratiwi', 'Teknik Informatika', '2017', '2020-05-13 08:47:33', '0000-00-00 00:00:00'),
(12171566, 'Miftakhul Huda Ari Santoso', 'Teknik Informatika', '2017', '2020-05-13 08:47:16', '0000-00-00 00:00:00'),
(12171568, 'Muhammad Alvian Rizky', 'Teknik Informatika', '2017', '2020-05-13 08:46:50', '0000-00-00 00:00:00'),
(12171570, 'Muhammad Munir Akromin', 'Teknik Informatika', '2017', '2020-05-13 08:48:13', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penelitian`
--

DROP TABLE IF EXISTS `penelitian`;
CREATE TABLE IF NOT EXISTS `penelitian` (
  `PenelitianID` int(30) NOT NULL AUTO_INCREMENT,
  `NIM` int(30) DEFAULT NULL,
  `NPP` int(30) DEFAULT NULL,
  `Jenis` varchar(100) NOT NULL,
  `Judul` varchar(300) NOT NULL,
  `TahunAkademikID` int(30) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Info` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`PenelitianID`),
  KEY `NIM` (`NIM`),
  KEY `fk_penelitian_dosen` (`NPP`),
  KEY `fk_penelitian_tahun` (`TahunAkademikID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan`
--

DROP TABLE IF EXISTS `pengajuan`;
CREATE TABLE IF NOT EXISTS `pengajuan` (
  `PengajuanID` int(30) NOT NULL AUTO_INCREMENT,
  `NIM` varchar(30) NOT NULL,
  `JenisPengajuan` varchar(100) NOT NULL,
  `JudulPenelitian` varchar(300) NOT NULL,
  `TahunAkademikID` int(30) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Info` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`PengajuanID`),
  KEY `fk_pengajuan_tahun` (`TahunAkademikID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan`
--

DROP TABLE IF EXISTS `pesan`;
CREATE TABLE IF NOT EXISTS `pesan` (
  `PesanID` int(30) NOT NULL AUTO_INCREMENT,
  `GroupPesanID` int(30) NOT NULL,
  `ProposalID` int(30) DEFAULT NULL,
  `Name` int(30) NOT NULL,
  `Pesan` text NOT NULL,
  `Info` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`PesanID`),
  KEY `ProposalID` (`ProposalID`),
  KEY `fk_pesan_group_pesan` (`GroupPesanID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `prodi`
--

DROP TABLE IF EXISTS `prodi`;
CREATE TABLE IF NOT EXISTS `prodi` (
  `ProdiID` int(30) NOT NULL AUTO_INCREMENT,
  `NamaProdi` varchar(150) NOT NULL,
  `Jenjang` varchar(30) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`ProdiID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `proposal`
--

DROP TABLE IF EXISTS `proposal`;
CREATE TABLE IF NOT EXISTS `proposal` (
  `ProposalID` int(30) NOT NULL AUTO_INCREMENT,
  `PenelitianID` int(30) NOT NULL,
  `NamaBAB` varchar(30) NOT NULL,
  `Status` varchar(30) NOT NULL,
  `Keterangan` varchar(300) NOT NULL,
  `NamaFile` varchar(100) NOT NULL,
  `Info` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`ProposalID`),
  KEY `fk_proposal_penelitian` (`PenelitianID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sk_penelitian`
--

DROP TABLE IF EXISTS `sk_penelitian`;
CREATE TABLE IF NOT EXISTS `sk_penelitian` (
  `SKID` int(30) NOT NULL AUTO_INCREMENT,
  `NomerSK` varchar(100) NOT NULL,
  `PenelitianID` int(30) NOT NULL,
  `Prodi` varchar(30) NOT NULL,
  `Jenis` varchar(30) NOT NULL,
  `TahunAkademikID` int(30) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`SKID`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tahun_akademik`
--

DROP TABLE IF EXISTS `tahun_akademik`;
CREATE TABLE IF NOT EXISTS `tahun_akademik` (
  `TahunAkademikID` int(30) NOT NULL AUTO_INCREMENT,
  `TahunAkademik` varchar(100) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`TahunAkademikID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tahun_akademik`
--

INSERT INTO `tahun_akademik` (`TahunAkademikID`, `TahunAkademik`, `Status`, `created_at`, `updated_at`) VALUES
(9, '2019/2020-1', '0', '2020-05-31 08:59:56', '2020-06-01 06:08:51'),
(10, '2019/2020-2', '1', '2020-05-31 09:00:04', '2020-06-01 06:08:51'),
(11, '2018/2019-1', '0', '2020-05-31 12:58:58', '2020-06-01 06:08:51'),
(12, '2018/2019-2', '0', '2020-05-31 12:59:05', '2020-06-01 06:08:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(254) NOT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_email` (`email`),
  UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  UNIQUE KEY `uc_remember_selector` (`remember_selector`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `phone`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`) VALUES
(1, '127.0.0.1', 'administrator', '$2y$08$200Z6ZZbp3RAEXoaWcMA6uJOFicwNZaqk4oDhqTUiFXFe63MG.Daa', '', '0', 'admin@admin.com', NULL, '', NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', '2020-06-19 09:52:58', 1, 'Admin', 'istrator', 'ADMIN'),
(42, '::1', '12171568', '$2y$08$c2aurkBaENepNddR9ucgN.53UQi4SoqWkCsf9m9BdXoQcD48t3Yly', NULL, '', 'alvin@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-05-13 09:05:33', '2020-06-19 09:10:07', 1, 'Muhammad Alvian Rizky', NULL, NULL),
(43, '::1', '12171566', '$2y$08$IracEBIS673gXxDLl4DakusA.uDNGCKpOP3hDf09WLsDjez0NQ92q', NULL, '', 'huda@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-05-13 09:05:56', '2020-06-03 10:04:53', 1, 'Miftakhul Huda Ari Santoso', NULL, NULL),
(44, '::1', '11170403', '$2y$08$IRBA3Ek6SmEwAZalGAeCb.yYim4XiOdB5xQq/l5Srpzi2xG9Pcw2W', NULL, '', 'yulis@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-05-13 09:05:36', '2020-06-19 09:53:23', 1, 'Yulistiana', NULL, NULL),
(45, '::1', '11170386', '$2y$08$UqUSxKL75tbe0U7mx0Kq0emLbS8MecqTXnLS5C7wqvWwARMC8hNfi', NULL, '', 'ana@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-05-13 09:05:51', '2020-06-19 07:01:32', 1, 'Ana Suryaningsih', NULL, NULL),
(46, '::1', '11223360', '$2y$08$1NRBMTX821L8jsskU4/aAOm.eP2EjMCicjbc3pHrYPB8gXKLh5vFe', NULL, '', 'herdiesel@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-05-13 09:05:22', '2020-06-19 09:54:40', 1, 'Herdiesel Santoso', NULL, NULL),
(47, '::1', '11223362', '$2y$08$jbIzrMoDUBKhVCEhCNKmfu6cASQqqbLIELErPjLfEeb/dN76K8lXm', NULL, '085751767774', 'wahyu@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-05-13 09:05:40', '2020-06-19 09:12:04', 1, 'Wahyu Widodo', NULL, NULL),
(48, '::1', '11223364', '$2y$08$8k/lxHWCRjz5dEvU27fRleusHj1hih7ZJLGYwCXkzpk6LkfVBFVVG', NULL, '', 'andri@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-05-13 09:05:57', '2020-06-19 09:56:28', 1, 'Andri Safriyanto', NULL, NULL),
(49, '::1', '12171570', '$2y$08$OMCA5LRno3cgVu/XfnXRyuQqeMGVqpxgrKyIf2joHr.1QPvUPif3y', NULL, '', 'munir@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-05-18 09:05:34', '2020-06-03 10:04:23', 1, 'Muhammad Munir Akromin', NULL, NULL),
(50, '::1', '11170392', '$2y$08$9WKEhX/HYFG9e5aSpEtL4.kT.j8eB2ygCGgXBRU3Iehxfd0o8Wwry', NULL, '', 'intan@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-05-27 02:05:44', '2020-06-04 13:40:25', 1, 'Intan Hidayah', NULL, NULL),
(51, '::1', '12171564', '$2y$08$G4.cz5x8M1RoRkAhk6te1uRF7dq4550JGcB/Y9zpPugRZyPW/8hbq', NULL, '', 'linda@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-06-14 14:06:09', '2020-06-14 14:27:57', 1, 'Linda Pratiwi', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users_groups`
--

DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(48, 42, 3),
(49, 43, 3),
(50, 44, 3),
(51, 45, 3),
(52, 46, 11),
(53, 47, 12),
(54, 48, 6),
(55, 49, 3),
(56, 50, 3),
(57, 51, 3);

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `penelitian`
--
ALTER TABLE `penelitian`
  ADD CONSTRAINT `fk_penelitian_dosen` FOREIGN KEY (`NPP`) REFERENCES `dosen` (`NPP`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penelitian_mahasiswa` FOREIGN KEY (`NIM`) REFERENCES `mahasiswa` (`NIM`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penelitian_tahun` FOREIGN KEY (`TahunAkademikID`) REFERENCES `tahun_akademik` (`TahunAkademikID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD CONSTRAINT `fk_pengajuan_tahun` FOREIGN KEY (`TahunAkademikID`) REFERENCES `tahun_akademik` (`TahunAkademikID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pesan`
--
ALTER TABLE `pesan`
  ADD CONSTRAINT `fk_pesan_group_pesan` FOREIGN KEY (`GroupPesanID`) REFERENCES `group_pesan` (`GroupPesanID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pesan_proposal` FOREIGN KEY (`ProposalID`) REFERENCES `proposal` (`ProposalID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `proposal`
--
ALTER TABLE `proposal`
  ADD CONSTRAINT `fk_proposal_penelitian` FOREIGN KEY (`PenelitianID`) REFERENCES `penelitian` (`PenelitianID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
