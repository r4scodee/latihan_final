-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 8.4.3 - MySQL Community Server - GPL
-- OS server:                    Win64
-- Versi HeidiSQL:               12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Mengekspor struktur database untuk final_test_db
CREATE DATABASE IF NOT EXISTS `final_test_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `final_test_db`;

-- --------------------------------------------------------
-- Buat tabel warehouses terlebih dahulu
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `gudang` (
  `kodegudang` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `namagudang` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `golongan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kodegudang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Mengekspor data untuk tabel warehouses
INSERT INTO `gudang` (`kodegudang`, `namagudang`, `golongan`, `keterangan`, `created_at`) VALUES
('G01', 'Gudang Utama', 'Sayur', 'Sayuran', '2025-10-19 05:25:49'),
('G02', 'Gudang Cabang', 'Buah', 'Buah-buahan', '2025-10-19 05:25:49');

-- --------------------------------------------------------
-- Buat tabel produk
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `produk` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT '0.00',
  `satuan` enum('pcs','g','kg','ton') DEFAULT NULL,
  `kodegudang` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT (now()),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`),
  KEY `fk_produk_gudang` (`kodegudang`),
  CONSTRAINT `fk_produk_gudang` FOREIGN KEY (`kodegudang`) 
    REFERENCES `gudang` (`kodegudang`) 
    ON DELETE SET NULL 
    ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;

-- Mengekspor data untuk tabel produk
INSERT INTO `produk` (`id`, `kode`, `nama`, `harga`, `satuan`, `kodegudang`, `image`, `created_at`) VALUES
(1, 'PRD-001', 'Bawang Merah', 34500.00, 'kg', NULL, 'eacf06adc35b50bd_1758979031.jpg', '2025-09-09 07:37:03'),
(2, 'PRD-002', 'Kol Putih', 12750.00, 'pcs', NULL, '944e6343e9fdc009_1758979254.jpg', '2025-09-18 12:21:53'),
(3, 'PRD-003', 'Labu Kuning', 16065.00, 'pcs', NULL, 'eb888d9979a7db72_1758979315.jpg', '2025-09-18 12:42:55'),
(4, 'PRD-004', 'Bawang Putih', 25900.00, 'g', NULL, 'e87073a621da27b2_1758979404.jpg', '2025-09-21 12:36:15'),
(5, 'PRD-005', 'Kol Ungu', 24000.00, 'kg', NULL, '064f9ba05ca98350_1758979436.jpg', '2025-09-23 16:05:19'),
(6, 'PRD-006', 'Pare', 15150.00, 'g', NULL, 'dbe158529c1441c7_1758979464.jpg', '2025-09-23 16:05:39'),
(7, 'PRD-007', 'Jeruk Mandarin', 53500.00, 'kg', NULL, 'c7e486c5c0cffea8_1758979522.png', '2025-09-23 16:06:03');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
