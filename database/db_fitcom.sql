-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for tani_digital_db
CREATE DATABASE IF NOT EXISTS `tani_digital_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `tani_digital_db`;

-- Dumping structure for table tani_digital_db.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `unit` enum('pcs','g','kg','ton') DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

-- Dumping data for table tani_digital_db.products: ~17 rows (approximately)
INSERT INTO `products` (`id`, `code`, `name`, `price`, `unit`, `image`, `created_at`) VALUES
	(1, 'PRD-001', 'Bawang Merah', 34500.00, 'kg', 'eacf06adc35b50bd_1758979031.jpg', '2025-09-09 07:37:03'),
	(2, 'PRD-002', 'Kol Putih', 12750.00, 'pcs', '944e6343e9fdc009_1758979254.jpg', '2025-09-18 12:21:53'),
	(3, 'PRD-003', 'Labu Kuning', 16065.00, 'pcs', 'eb888d9979a7db72_1758979315.jpg', '2025-09-18 12:42:55'),
	(4, 'PRD-004', 'Bawang Putih', 25900.00, 'g', 'e87073a621da27b2_1758979404.jpg', '2025-09-21 12:36:15'),
	(5, 'PRD-005', 'Kol Ungu', 24000.00, 'kg', '064f9ba05ca98350_1758979436.jpg', '2025-09-23 16:05:19'),
	(6, 'PRD-006', 'Pare', 15150.00, 'g', 'dbe158529c1441c7_1758979464.jpg', '2025-09-23 16:05:39'),
	(7, 'PRD-007', 'Jeruk Mandarin', 53500.00, 'kg', 'c7e486c5c0cffea8_1758979522.png', '2025-09-23 16:06:03'),
	(8, 'PRD-008', 'Stroberi', 32000.00, 'kg', 'd58854f6dcf18678_1758979545.png', '2025-09-23 16:06:43'),
	(9, 'PRD-09', 'Paprika', 45000.00, 'kg', '45f9f68bd66bd165_1758979569.png', '2025-09-23 16:07:15'),
	(10, 'PRD-010', 'Peterseli', 10500.00, 'kg', 'aa6af7318c4d62d0_1758979596.png', '2025-09-27 12:22:09'),
	(11, 'PRD-011', 'Jagung', 18000.00, 'kg', '4172f0e25639f233_1758979616.png', '2025-09-27 12:25:03'),
	(12, 'PRD-012', 'Paket Sayur & Buah', 42500.00, 'pcs', '6ada48c995c01241_1758979692.png', '2025-09-27 13:28:10'),
	(13, 'PRD-013', 'Tomat', 14100.00, 'kg', '2c3d3fa5883b6b46_1758979717.png', '2025-09-27 13:28:35'),
	(14, 'PRD-014', 'Kacang Almond', 80000.00, 'g', '4e4773f1e560630b_1758979755.png', '2025-09-27 13:29:13'),
	(15, 'PRD-015', 'Daun Teh', 23750.00, 'g', '16c73d0399c1cfcb_1758979778.png', '2025-09-27 13:29:35'),
	(16, 'PRD-016', 'Jus Jeruk', 25000.00, 'pcs', '2e26a2fcc7009cc1_1758979800.png', '2025-09-27 13:29:57'),
	(17, 'PRD-017', 'Jus Apel', 25000.00, 'pcs', '66c501a49626e99f_1758979822.png', '2025-09-27 13:30:19');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
