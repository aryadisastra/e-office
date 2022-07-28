-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2022 at 09:21 PM
-- Server version: 10.5.13-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u652529167_eoffice`
--

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `telp` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `parent_id` varchar(11) NOT NULL,
  `login_session_key` varchar(255) DEFAULT NULL,
  `email_status` varchar(255) DEFAULT NULL,
  `password_expire_date` datetime DEFAULT '2022-02-18 00:00:00',
  `password_reset_key` varchar(255) DEFAULT NULL,
  `role` varchar(10) NOT NULL,
  `bagian` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `username`, `password`, `nama`, `alamat`, `telp`, `email`, `parent_id`, `login_session_key`, `email_status`, `password_expire_date`, `password_reset_key`, `role`, `bagian`) VALUES
(7, 'OPERATOR', '$2y$10$us.zmDc3H9rQiYa3kxDr1e/ZJzUUUQDTBwWr6xRWti6Mu.y.gf4/.', 'OPERATOR', 'Jakarta', '081122334455', 'mail@mail.com', '9', NULL, NULL, '2022-02-18 00:00:00', NULL, 'admin', 0),
(9, 'pokmin', '$2y$10$c07tqJ/9ok9DhrVPPwWrwe95P91MxAm9P6VvvlC1FrrdQh5MUr9Um', 'pokmin', 'test', '32214214124', 'test@test.test', '10', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 1),
(10, 'kasi', '$2y$10$1N5weLX8ZF3fKEHkgOX...2a16vmNAtUWnZrd/hnvNcO3WaqXce5O', 'kasi', 'kasi', '12321321', 'kasi@kasi.kasi', '14', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 2),
(11, 'kabagrenkom', '$2y$10$Khle9XEGiF9Ksyh0ygXKYO1y.dRvLCplDkIrPvRJOpMSCK2qB7sbi', 'kabag renkom', 'kabag', '213123', 'kabag@kabag.kabag', '12', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 3),
(12, 'kasubditbinkom', '$2y$10$6rMIx5iuZwsODwWXghcASOQFTTPe498uVTa8J.ArwrTJTPN1SjKua', 'kasubditbinkom', 'kasubditbinkom', '2132444421', 'kasub@kasub.kasub', '13', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 4),
(13, 'dircab', '$2y$10$xzlopcJxw1xCap0pzpVmiedvrhjdeTO8eGhpKfRQk5z6f83.Fx8yy', 'dircab', 'dircab', '213213', 'dircab@dircab.dircab', '5', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 5),
(14, 'kabagkomrad', '$2y$10$4LBBBnK.wXjQZQhDoIdFteV1F/Vu0/TIAo4b5GnAGOo8oE5fDs7yq', 'kabag komrad', 'kabag', '213432', 'kabagkom@gmail.com', '12', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 3),
(15, 'kabagjaringan', '$2y$10$yticVIpFaUgkyvyPHy0vR.sbQ8feL.T651oMsITMb4lBbAIGGx5ia', 'kabag jaringan', 'kabag', '76324987', 'kabagjar@gmail.com', '12', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 3),
(16, 'kabagmultimedia', '$2y$10$vzn4k12Ooz.KNR2uYhgdBO0EOlSt/jFShU0QjBZv62BZVmh5lrAPC', 'kabag multimedia', 'kabag', '2178632745', 'kabagmul@gmail.com', '12', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 3),
(17, 'kasisal', '$2y$10$9Ja1327JHTTbbBtwifH80.rkV7lZIdMoYQ/KHdqO2P24gMhlt9joG', 'Kasi Sal', 'kasi', '1236218', 'kasis@gmail.com', '15', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 2),
(18, 'kasisat', '$2y$10$n1K5Ot/wLxDhd894aZHSMudrKVbUOlw8Erk2msUNr0TeEVfsAjAcy', 'Kasi Sat', 'kasi', '2376324', 'kasisat@gmail.com', '15', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 2),
(19, 'kasidalfrek', '$2y$10$cT7zLsMV0GCELW4h2VnpZOUvPjfKShZry9eBp2PbQWy9edKz1iPNq', 'kasi dalfrek', 'kasi', '762138724', 'kasidal@gmail.com', '14', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 2),
(20, 'kasiauvis', '$2y$10$HqHYR9p79tIvKgPmIOxske1g.T0FNzM.fFlsQ5h6kf8n6lQNDOfv6', 'kasi auvis', 'kasi', '216387532', 'kasiau@gmail.com', '16', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
