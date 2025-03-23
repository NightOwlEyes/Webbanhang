-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping database structure for plant_store
CREATE DATABASE IF NOT EXISTS `plant_store` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `plant_store`;

-- Dumping structure for table plant_store.account
CREATE TABLE IF NOT EXISTS `account` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sửa lại bảng account_logs
CREATE TABLE IF NOT EXISTS `account_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `action` varchar(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `details` text,
  `timestamp` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sửa lại trigger với thêm thông tin debug
DELIMITER //
CREATE TRIGGER after_account_insert 
AFTER INSERT ON account
FOR EACH ROW 
BEGIN
    INSERT INTO account_logs (action, username, details) 
    VALUES ('INSERT', NEW.username, CONCAT('Role: ', NEW.role, ', Created at: ', NEW.created_at));
END//
DELIMITER ;

-- Dumping data for table plant_store.account: ~0 rows (approximately)

-- Dumping structure for table plant_store.category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table plant_store.category: ~6 rows (approximately)
INSERT INTO `category` (`id`, `name`, `description`) VALUES
	(1, 'Cây cảnh nội thất', 'Các loại cây phù hợp trồng trong nhà'),
	(2, 'Cây cảnh văn phòng', 'Các loại cây phù hợp đặt trong văn phòng'),
	(3, 'Cây cảnh để bàn', 'Các loại cây nhỏ phù hợp để bàn làm việc'),
	(4, 'Cây ăn quả', 'Các loại cây ăn quả trồng trong chậu'),
	(5, 'Cây thuốc', 'Các loại cây có tác dụng làm thuốc'),
	(6, 'Sen đá', 'Các loại sen đá và xương rồng');

-- Dumping structure for table plant_store.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','processing','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table plant_store.orders: ~0 rows (approximately)

-- Dumping structure for table plant_store.order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table plant_store.order_details: ~0 rows (approximately)

-- Dumping structure for table plant_store.product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `care_guide` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sunlight` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `water` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int DEFAULT '10',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table plant_store.product: ~6 rows (approximately)
INSERT INTO `product` (`id`, `name`, `description`, `price`, `image`, `category_id`, `care_guide`, `sunlight`, `water`, `size`, `stock`) VALUES
	(1, 'Cây Trầu Bà', '                                Cây Trầu Bà có khả năng thanh lọc không khí, phù hợp đặt trong phòng khách hoặc phòng ngủ.                            ', 150000.00, 'uploads/280px-Epipremnum_aureum_31082012.jpg', 1, '', '', '', '', 10),
	(2, 'Cây Lưỡi Hổ', '                                Cây Lưỡi Hổ dễ chăm sóc, có khả năng sống trong điều kiện khắc nghiệt và thanh lọc không khí.                            ', 120000.00, 'uploads/280px-Snake_plant.jpg', 2, '', '', '', '', 10),
	(3, 'Sen Đá Nhỏ', 'Sen đá mini xinh xắn, phù hợp để bàn làm việc hoặc trang trí góc nhỏ.', 50000.00, 'uploads/các-chậu-sen-đá-hình-lục-giác-đặt-trên-bàn-gỗ..webp', 3, 'Tưới nước 1 lần/2 tuần, đặt nơi có ánh sáng.', 'Ánh sáng trực tiếp', 'Rất ít', 'Nhỏ', 30),
	(4, 'Cây Chanh Cảnh', 'Cây chanh cảnh vừa làm cảnh vừa có thể thu hoạch quả để sử dụng.', 200000.00, 'uploads/img-0646.webp', 4, 'Tưới nước đều đặn, bón phân định kỳ.', 'Ánh sáng trực tiếp', '', '', 10),
	(5, 'Cây Lô Hội (Nha Đam)', 'Cây Lô Hội có nhiều công dụng làm đẹp và chữa bệnh.', 100000.00, 'uploads/lo-hoi.jpg', 5, 'Tưới nước 1 lần/2 tuần, chịu được điều kiện khô.', 'Ánh sáng trực tiếp', 'Ít', 'Trung bình', 25),
	(6, 'Xương Rồng Bát Tiên', 'Xương rồng Bát Tiên với hoa đẹp, dễ chăm sóc.', 80000.00, 'uploads/xuong-rong.jpg', 6, 'Tưới nước ít, đặt nơi có nhiều ánh sáng.', 'Ánh sáng trực tiếp', 'Rất ít', 'Nhỏ', 15);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;