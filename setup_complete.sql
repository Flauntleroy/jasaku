-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `sign` CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `sign`;

-- Import the schema from sign.sql
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Table structure for ci_sessions
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table structure for jasa_bonus
DROP TABLE IF EXISTS `jasa_bonus`;
CREATE TABLE `jasa_bonus`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `periode` date NOT NULL,
  `terima_sebelum_pajak` decimal(15, 2) NOT NULL,
  `pajak_5` decimal(15, 2) NULL DEFAULT 0.00,
  `pajak_15` decimal(15, 2) NULL DEFAULT 0.00,
  `pajak_0` decimal(15, 2) NULL DEFAULT 0.00,
  `terima_setelah_pajak` decimal(15, 2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `jasa_bonus_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- Table structure for tanda_tangan
DROP TABLE IF EXISTS `tanda_tangan`;
CREATE TABLE `tanda_tangan`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `jasa_bonus_id` int NOT NULL,
  `user_id` int NOT NULL,
  `tanda_tangan_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `signed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jasa_bonus_id`(`jasa_bonus_id` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `tanda_tangan_ibfk_1` FOREIGN KEY (`jasa_bonus_id`) REFERENCES `jasa_bonus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tanda_tangan_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- Table structure for users
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ruangan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `asn` enum('Ya','Tidak') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'Tidak',
  `nik` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `status_ptkp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `golongan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` enum('pegawai','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'pegawai',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username` ASC) USING BTREE,
  UNIQUE INDEX `nik`(`nik` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- Insert sample data
-- Admin user (password: admin123)
INSERT INTO `users` (`nama`, `ruangan`, `asn`, `nik`, `status_ptkp`, `golongan`, `username`, `password_hash`, `role`) VALUES
('Administrator', 'IT', 'Ya', '1234567890123456', 'TK/0', 'III/c', 'admin', '$2y$10$yHM.KSXaL57kcBRD5Yj3huRYdA//NiwxUB46VDVOm5oGX1nv48k/2', 'admin');

-- Sample pegawai (password: pegawai123)
INSERT INTO `users` (`nama`, `ruangan`, `asn`, `nik`, `status_ptkp`, `golongan`, `username`, `password_hash`, `role`) VALUES
('John Doe', 'Keuangan', 'Ya', '1234567890123457', 'K/1', 'III/a', 'johndoe', '$2y$10$1ihwpWTx8W9cjVTjd6vV3uG8cKS7cBoBLxFN58Lw9z9FApl/TErim', 'pegawai'),
('Jane Smith', 'SDM', 'Ya', '1234567890123458', 'K/0', 'III/b', 'janesmith', '$2y$10$1ihwpWTx8W9cjVTjd6vV3uG8cKS7cBoBLxFN58Lw9z9FApl/TErim', 'pegawai'),
('Bob Wilson', 'Operasional', 'Tidak', '1234567890123459', 'TK/0', NULL, 'bobwilson', '$2y$10$1ihwpWTx8W9cjVTjd6vV3uG8cKS7cBoBLxFN58Lw9z9FApl/TErim', 'pegawai');

-- Sample jasa bonus data
INSERT INTO `jasa_bonus` (`user_id`, `periode`, `terima_sebelum_pajak`, `pajak_5`, `pajak_15`, `pajak_0`, `terima_setelah_pajak`) VALUES
(2, '2025-09-01', 2500000.00, 125000.00, 0.00, 0.00, 2375000.00),
(2, '2025-08-01', 2200000.00, 110000.00, 0.00, 0.00, 2090000.00),
(3, '2025-09-01', 2300000.00, 115000.00, 0.00, 0.00, 2185000.00),
(3, '2025-08-01', 2100000.00, 105000.00, 0.00, 0.00, 1995000.00),
(4, '2025-09-01', 1800000.00, 90000.00, 0.00, 0.00, 1710000.00),
(4, '2025-08-01', 1750000.00, 87500.00, 0.00, 0.00, 1662500.00);

SET FOREIGN_KEY_CHECKS = 1;