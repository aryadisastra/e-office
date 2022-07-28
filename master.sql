/*
 Navicat Premium Data Transfer

 Source Server         : database
 Source Server Type    : MySQL
 Source Server Version : 100422
 Source Host           : localhost:3306
 Source Schema         : master

 Target Server Type    : MySQL
 Target Server Version : 100422
 File Encoding         : 65001

 Date: 04/03/2022 15:47:13
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for arsip
-- ----------------------------
DROP TABLE IF EXISTS `arsip`;
CREATE TABLE `arsip`  (
  `id_arsip` int NOT NULL AUTO_INCREMENT,
  `nomor_arsip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_terima` date NOT NULL,
  `tanggal_arsip` datetime NOT NULL,
  `asal_arsip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kepada` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tembusan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `subjek` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lampiran` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sifat` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_arsip`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of arsip
-- ----------------------------
INSERT INTO `arsip` VALUES (1, '658358588', '2021-11-19', '2021-11-19 13:48:24', 'PT. Indismart', 'DIRCAB', 'KAPUS,WAKAPUS', 'Test', '<p>Test</p>', 'http://localhost/master/uploads/files/cd3lirpunx0zg56.JPG', 'Rahasia');
INSERT INTO `arsip` VALUES (2, '9598346737', '2021-11-20', '2021-11-20 09:03:12', 'Presiden', 'KABAG,KASUB,DIRUM', 'KAPUS,WAKAPUS,DIRCAB', 'Test', '<p>Test</p>', 'http://localhost/master/uploads/files/ba0zrc25k_tvjos.jpg', 'Prioritas');

-- ----------------------------
-- Table structure for bagian
-- ----------------------------
DROP TABLE IF EXISTS `bagian`;
CREATE TABLE `bagian`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `bagian` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of bagian
-- ----------------------------
INSERT INTO `bagian` VALUES (1, 'staff');
INSERT INTO `bagian` VALUES (2, 'kasi');
INSERT INTO `bagian` VALUES (3, 'kabag');
INSERT INTO `bagian` VALUES (4, 'kasub');
INSERT INTO `bagian` VALUES (5, 'dircab');

-- ----------------------------
-- Table structure for balasan_surat
-- ----------------------------
DROP TABLE IF EXISTS `balasan_surat`;
CREATE TABLE `balasan_surat`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_surat` int NOT NULL,
  `nomor_surat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pengguna` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` datetime NOT NULL,
  `balasan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of balasan_surat
-- ----------------------------
INSERT INTO `balasan_surat` VALUES (1, 0, '456546546', 'DIRCAB', '2021-11-19 16:21:17', 'Siap Bosque');
INSERT INTO `balasan_surat` VALUES (2, 0, '123456789', 'DIRCAB', '2021-11-19 16:22:11', 'Lapan anam');

-- ----------------------------
-- Table structure for index_surat
-- ----------------------------
DROP TABLE IF EXISTS `index_surat`;
CREATE TABLE `index_surat`  (
  `id_surat` int NOT NULL AUTO_INCREMENT,
  `nomor_surat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` datetime NOT NULL,
  `pengguna` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kepada` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tembusan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `disposisi` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `subjek` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lampiran` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sifat` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `persetujuan` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `balasan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NULL DEFAULT NULL,
  PRIMARY KEY (`id_surat`, `nomor_surat`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of index_surat
-- ----------------------------
INSERT INTO `index_surat` VALUES (17, '234324324234', '2022-03-01 14:45:34', 'staff', 'kasi', 'kasi', 'kasi', 'dsafsaf', '<p>dsafsfsdaff</p>', '', 'Biasa', '', '', 6);
INSERT INTO `index_surat` VALUES (18, '323242134', '2022-03-01 14:57:44', 'staff', 'OPERATOR,staff,kasi,kabag,kasub,dircab', 'OPERATOR,staff,kasi,kabag,kasub,dircab', 'OPERATOR,staff,kasi,kabag,kasub,dircab', 'sadsadsad', '<p>dsafsafsafd</p>', '', 'Biasa', '', '', 6);
INSERT INTO `index_surat` VALUES (19, '213213213', '2022-03-01 15:06:29', 'staff', 'kabag', 'kabag', 'kabag', 'adsfdsafsa', '<p>dsafsafsafds</p>', '', 'Biasa', '', '', 1);
INSERT INTO `index_surat` VALUES (20, '312432143', '2022-03-01 15:07:12', 'staff', 'staff', 'staff', 'staff', 'sadsadsad', '<p>dsadsadfsaf</p>', '', 'Biasa', '', '', 6);
INSERT INTO `index_surat` VALUES (21, '45646', '2022-03-02 18:52:44', 'staff', 'kasi,kabag', 'kasi,kabag', 'kasi,kabag', 'test', '<p>dsafsaf</p>', '', 'Biasa', '', '', 2);
INSERT INTO `index_surat` VALUES (22, '64757', '2022-03-03 01:42:37', 'staff', 'kasi,kabag', 'kasi,kabag', 'kasi,kabag', 'sadsadsad', '<p>dsafsfsaf</p>', '', 'Biasa', '', '', 3);
INSERT INTO `index_surat` VALUES (23, '324325', '2022-03-03 01:58:10', 'staff', 'kasi,kabag', 'kasi,kabag', 'kasi,kabag', 'cxvcxvcxvcxzvcxz', '<p>cxvcxzvxczv</p>', '', 'Biasa', '', '', 2);

-- ----------------------------
-- Table structure for isi_disposisi
-- ----------------------------
DROP TABLE IF EXISTS `isi_disposisi`;
CREATE TABLE `isi_disposisi`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `isi_disposisi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `bagian` int NOT NULL,
  `status` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of isi_disposisi
-- ----------------------------
INSERT INTO `isi_disposisi` VALUES (2, 'Konsepkan', 3, 1);
INSERT INTO `isi_disposisi` VALUES (3, 'Konsepkan', 2, 1);
INSERT INTO `isi_disposisi` VALUES (4, 'Awasi', 3, 1);
INSERT INTO `isi_disposisi` VALUES (5, 'Bahan Evaluasi', 3, 1);
INSERT INTO `isi_disposisi` VALUES (6, 'Bahan Laporan', 3, 1);
INSERT INTO `isi_disposisi` VALUES (7, 'Bahan Perencanaan', 3, 1);
INSERT INTO `isi_disposisi` VALUES (8, 'Tindak Lanjuti', 3, 1);
INSERT INTO `isi_disposisi` VALUES (9, 'ST Ke Bawah', 3, 1);
INSERT INTO `isi_disposisi` VALUES (10, 'Arsipkan', 3, 1);
INSERT INTO `isi_disposisi` VALUES (11, 'Wakili', 3, 1);
INSERT INTO `isi_disposisi` VALUES (12, 'Sesuaikan Disposisi', 3, 1);
INSERT INTO `isi_disposisi` VALUES (13, 'Laporkan Hasilnya', 3, 1);
INSERT INTO `isi_disposisi` VALUES (14, 'Perhatikan Waktu', 3, 1);
INSERT INTO `isi_disposisi` VALUES (15, 'Siapkan Bahan', 3, 1);
INSERT INTO `isi_disposisi` VALUES (16, 'Selesaikan', 3, 1);
INSERT INTO `isi_disposisi` VALUES (17, 'Monitor', 3, 1);
INSERT INTO `isi_disposisi` VALUES (18, 'Koordinasikan', 3, 1);
INSERT INTO `isi_disposisi` VALUES (19, 'Ikuti Perkembangan', 3, 1);
INSERT INTO `isi_disposisi` VALUES (20, 'Telah Dilaksanakan', 3, 1);
INSERT INTO `isi_disposisi` VALUES (21, 'Catat & Ingatkan', 3, 1);
INSERT INTO `isi_disposisi` VALUES (22, 'Lanjutkan', 3, 1);
INSERT INTO `isi_disposisi` VALUES (23, 'Pedomani', 3, 1);
INSERT INTO `isi_disposisi` VALUES (24, 'Pelajari', 3, 1);
INSERT INTO `isi_disposisi` VALUES (25, 'Sarankan', 3, 1);
INSERT INTO `isi_disposisi` VALUES (26, 'Himpun', 3, 1);
INSERT INTO `isi_disposisi` VALUES (27, 'Dukung', 3, 1);
INSERT INTO `isi_disposisi` VALUES (28, 'Cocokan Data', 3, 1);
INSERT INTO `isi_disposisi` VALUES (29, 'Data Ajuan', 3, 1);
INSERT INTO `isi_disposisi` VALUES (30, 'UDK', 3, 1);
INSERT INTO `isi_disposisi` VALUES (31, 'UDL', 3, 1);
INSERT INTO `isi_disposisi` VALUES (32, 'UDL YBS', 3, 1);

-- ----------------------------
-- Table structure for pengguna
-- ----------------------------
DROP TABLE IF EXISTS `pengguna`;
CREATE TABLE `pengguna`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telp` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `parent_id` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `login_session_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password_expire_date` datetime NULL DEFAULT '2022-02-18 00:00:00',
  `password_reset_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `role` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `bagian` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pengguna
-- ----------------------------
INSERT INTO `pengguna` VALUES (7, 'OPERATOR', '$2y$10$us.zmDc3H9rQiYa3kxDr1e/ZJzUUUQDTBwWr6xRWti6Mu.y.gf4/.', 'OPERATOR', 'Jakarta', '081122334455', 'mail@mail.com', '6', NULL, NULL, '2022-02-18 00:00:00', NULL, 'admin', NULL);
INSERT INTO `pengguna` VALUES (9, 'staff', '$2y$10$c07tqJ/9ok9DhrVPPwWrwe95P91MxAm9P6VvvlC1FrrdQh5MUr9Um', 'staff', 'test', '32214214124', 'test@test.test', '1', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 1);
INSERT INTO `pengguna` VALUES (10, 'kasi', '$2y$10$1N5weLX8ZF3fKEHkgOX...2a16vmNAtUWnZrd/hnvNcO3WaqXce5O', 'kasi', 'kasi', '12321321', 'kasi@kasi.kasi', '2', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 2);
INSERT INTO `pengguna` VALUES (11, 'kabag', '$2y$10$Khle9XEGiF9Ksyh0ygXKYO1y.dRvLCplDkIrPvRJOpMSCK2qB7sbi', 'kabag', 'kabag', '213123', 'kabag@kabag.kabag', '3', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 3);
INSERT INTO `pengguna` VALUES (12, 'kasub', '$2y$10$6rMIx5iuZwsODwWXghcASOQFTTPe498uVTa8J.ArwrTJTPN1SjKua', 'kasub', 'kasub', '2132444421', 'kasub@kasub.kasub', '4', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 4);
INSERT INTO `pengguna` VALUES (13, 'dircab', '$2y$10$xzlopcJxw1xCap0pzpVmiedvrhjdeTO8eGhpKfRQk5z6f83.Fx8yy', 'dircab', 'dircab', '213213', 'dircab@dircab.dircab', '5', NULL, NULL, '2022-02-18 00:00:00', NULL, 'user', 5);

-- ----------------------------
-- Table structure for persetujuan_disposisi
-- ----------------------------
DROP TABLE IF EXISTS `persetujuan_disposisi`;
CREATE TABLE `persetujuan_disposisi`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_surat` int NOT NULL,
  `nomor_surat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pengguna` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` datetime NOT NULL,
  `persetujuan` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `isi_disposisi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `komentar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id`, `nomor_surat`) USING BTREE,
  INDEX `persetujuan`(`persetujuan` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of persetujuan_disposisi
-- ----------------------------
INSERT INTO `persetujuan_disposisi` VALUES (12, 0, '234324324234', 'kasi', '2022-03-01 14:48:16', 'Disetujui', 'ewsdfsaf', NULL);
INSERT INTO `persetujuan_disposisi` VALUES (13, 0, '234324324234', 'kabag', '2022-03-01 14:52:33', 'Disetujui', 'dsafdsafdsafdsaf', NULL);
INSERT INTO `persetujuan_disposisi` VALUES (14, 0, '323242134', 'staff', '2022-03-01 15:00:30', 'Disetujui', NULL, NULL);
INSERT INTO `persetujuan_disposisi` VALUES (15, 0, '323242134', 'staff', '2022-03-01 15:00:30', 'Disetujui', NULL, NULL);
INSERT INTO `persetujuan_disposisi` VALUES (16, 0, '312432143', 'staff', '2022-03-01 15:07:36', 'Disetujui', NULL, NULL);
INSERT INTO `persetujuan_disposisi` VALUES (17, 0, '312432143', 'staff', '2022-03-01 15:07:36', 'Disetujui', NULL, NULL);
INSERT INTO `persetujuan_disposisi` VALUES (18, 0, '312432143', 'staff', '2022-03-01 15:12:21', 'Disetujui', NULL, NULL);
INSERT INTO `persetujuan_disposisi` VALUES (19, 0, '312432143', 'staff', '2022-03-01 15:14:28', 'Disetujui', NULL, NULL);
INSERT INTO `persetujuan_disposisi` VALUES (20, 0, '234324324234', 'staff', '2022-03-01 15:15:30', 'Disetujui', NULL, NULL);
INSERT INTO `persetujuan_disposisi` VALUES (21, 0, '323242134', 'kasi', '2022-03-02 15:00:43', 'Disetujui', '1,3', NULL);
INSERT INTO `persetujuan_disposisi` VALUES (22, 0, '234324324234', 'kasi', '2022-03-02 15:05:58', 'Disetujui', '1,3', 'dsafsdafwaqerfdsagfadsg');

-- ----------------------------
-- Table structure for sifat_persetujuan
-- ----------------------------
DROP TABLE IF EXISTS `sifat_persetujuan`;
CREATE TABLE `sifat_persetujuan`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `persetujuan` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sifat_persetujuan
-- ----------------------------
INSERT INTO `sifat_persetujuan` VALUES (1, 'Disetujui');
INSERT INTO `sifat_persetujuan` VALUES (2, 'Ditolak');

-- ----------------------------
-- Table structure for sifat_surat
-- ----------------------------
DROP TABLE IF EXISTS `sifat_surat`;
CREATE TABLE `sifat_surat`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `sifat_surat` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sifat_surat
-- ----------------------------
INSERT INTO `sifat_surat` VALUES (1, 'Biasa');
INSERT INTO `sifat_surat` VALUES (2, 'Prioritas');
INSERT INTO `sifat_surat` VALUES (3, 'Rahasia');

-- ----------------------------
-- View structure for dis
-- ----------------------------
DROP VIEW IF EXISTS `dis`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `dis` AS SELECT `persetujuan_disposisi`.`nomor_surat` AS `nomor_surat`, group_concat(`persetujuan_disposisi`.`pengguna` separator ', ') AS `pengguna`, group_concat(`persetujuan_disposisi`.`persetujuan` separator ', ') AS `persetujuan` FROM `persetujuan_disposisi` GROUP BY `persetujuan_disposisi`.`nomor_surat` ;

-- ----------------------------
-- View structure for dis_fil
-- ----------------------------
DROP VIEW IF EXISTS `dis_fil`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `dis_fil` AS SELECT `d`.`nomor_surat` AS `nomor_surat`, `d`.`pengguna` AS `pengguna`, `d`.`persetujuan` AS `persetujuan` FROM `dis` AS `d` WHERE `d`.`persetujuan` not like '%Ditolak%' AND `d`.`persetujuan` <> '' ;

-- ----------------------------
-- View structure for dis_final
-- ----------------------------
DROP VIEW IF EXISTS `dis_final`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `dis_final` AS SELECT `n`.`id_surat` AS `id_surat`, `n`.`nomor_surat` AS `nomor_surat`, `n`.`tanggal` AS `tanggal`, `n`.`pengguna` AS `pengguna`, `n`.`kepada` AS `kepada`, `n`.`tembusan` AS `tembusan`, `n`.`disposisi` AS `disposisi`, `n`.`subjek` AS `subjek`, `n`.`keterangan` AS `keterangan`, `n`.`lampiran` AS `lampiran`, `n`.`sifat` AS `sifat`, `n`.`persetujuan` AS `persetujuan`, `n`.`balasan` AS `balasan` FROM (`notnondis` `n` join `dis_fil` `df` on(`n`.`nomor_surat` = `df`.`nomor_surat`)) ;

-- ----------------------------
-- View structure for nondis
-- ----------------------------
DROP VIEW IF EXISTS `nondis`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `nondis` AS SELECT `index_surat`.`id_surat` AS `id_surat`, `index_surat`.`nomor_surat` AS `nomor_surat`, `index_surat`.`tanggal` AS `tanggal`, `index_surat`.`pengguna` AS `pengguna`, `index_surat`.`kepada` AS `kepada`, `index_surat`.`tembusan` AS `tembusan`, `index_surat`.`disposisi` AS `disposisi`, `index_surat`.`subjek` AS `subjek`, `index_surat`.`keterangan` AS `keterangan`, `index_surat`.`lampiran` AS `lampiran`, `index_surat`.`sifat` AS `sifat`, `index_surat`.`persetujuan` AS `persetujuan`, `index_surat`.`balasan` AS `balasan` FROM `index_surat` WHERE `index_surat`.`disposisi` = '' ;

-- ----------------------------
-- View structure for notnondis
-- ----------------------------
DROP VIEW IF EXISTS `notnondis`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `notnondis` AS SELECT `index_surat`.`id_surat` AS `id_surat`, `index_surat`.`nomor_surat` AS `nomor_surat`, `index_surat`.`tanggal` AS `tanggal`, `index_surat`.`pengguna` AS `pengguna`, `index_surat`.`kepada` AS `kepada`, `index_surat`.`tembusan` AS `tembusan`, `index_surat`.`disposisi` AS `disposisi`, `index_surat`.`subjek` AS `subjek`, `index_surat`.`keterangan` AS `keterangan`, `index_surat`.`lampiran` AS `lampiran`, `index_surat`.`sifat` AS `sifat`, `index_surat`.`persetujuan` AS `persetujuan`, `index_surat`.`balasan` AS `balasan` FROM `index_surat` WHERE `index_surat`.`disposisi` <> '' ;

-- ----------------------------
-- View structure for surat_masuk
-- ----------------------------
DROP VIEW IF EXISTS `surat_masuk`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `surat_masuk` AS SELECT `nondis`.`id_surat` AS `id_surat`, `nondis`.`nomor_surat` AS `nomor_surat`, `nondis`.`tanggal` AS `tanggal`, `nondis`.`pengguna` AS `pengguna`, `nondis`.`kepada` AS `kepada`, `nondis`.`tembusan` AS `tembusan`, `nondis`.`disposisi` AS `disposisi`, `nondis`.`subjek` AS `subjek`, `nondis`.`keterangan` AS `keterangan`, `nondis`.`lampiran` AS `lampiran`, `nondis`.`sifat` AS `sifat`, `nondis`.`persetujuan` AS `persetujuan`, `nondis`.`balasan` AS `balasan` FROM `nondis` ;

SET FOREIGN_KEY_CHECKS = 1;
