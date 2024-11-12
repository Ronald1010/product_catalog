-- -------------------------------------------------------------
-- TablePlus 6.1.8(574)
--
-- https://tableplus.com/
--
-- Database: ecommerce_db
-- Generation Time: 2024-11-13 01:37:52.2960
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


CREATE TABLE `blacklisted_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `token` text NOT NULL,
  `blacklisted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `cart` (
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `add_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `cart_item` (
  `cart_item_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`cart_item_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `order_item` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,5) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order_table` (`order_id`),
  CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `order_item_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `order_table` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `total_amount` decimal(10,10) NOT NULL,
  `status` varchar(50) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `order_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `payment_table` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `payment_amount` decimal(10,10) NOT NULL,
  `payment_status` varchar(50) NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `payment_table_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order_table` (`order_id`),
  CONSTRAINT `payment_table_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `product` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int NOT NULL,
  `category` varchar(50) NOT NULL,
  `size` varchar(50) NOT NULL,
  `color` varchar(50) NOT NULL,
  `product_image` text NOT NULL,
  `seller_id` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `unique_product_per_seller` (`product_name`,`seller_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `product_images` (
  `image_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `image_url` text NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `token` (
  `token_id` int NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `token_type` varchar(50) DEFAULT NULL,
  `issued_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`token_id`),
  KEY `uuid` (`uuid`),
  CONSTRAINT `token_ibfk_1` FOREIGN KEY (`uuid`) REFERENCES `users` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `users` (
  `uuid` char(36) NOT NULL,
  `user_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('0001','0002','4DM1N') DEFAULT NULL,
  `verification_token` varchar(64) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`uuid`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `blacklisted_tokens` (`id`, `token`, `blacklisted_at`) VALUES
(6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiNjk4MGVhZDQtZmMwMy00MTk0LTk0MjAtODU1ZTgwZTNlNzFmIiwiZW1haWwiOiJhbGpvbkBleGFtcGxlLmNvbSIsInJvbGUiOiIwMDAxIiwiaWF0IjoxNzI5MDk0MDU2fQ.eLAG1kPsG5teYTFq5nNMEIAv327X5VdN2oCe_rIEYQY', '2024-10-16 23:54:38'),
(7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiYTBiZTUxNjYtY2RiMC00MDgxLWFiODYtOTYyZjc3YmEzYWI0IiwiZW1haWwiOiJub3JhbHJ1c3NlbEBleGFtcGxlLmNvbSIsInJvbGUiOiIwMDAxIiwiaWF0IjoxNzI5MTE5MDA5fQ.zy1_OkKN8G7ifSOWI98BjShxiW2cWo29SufPixPxRYI', '2024-10-17 06:50:21'),
(8, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiYTBiZTUxNjYtY2RiMC00MDgxLWFiODYtOTYyZjc3YmEzYWI0IiwiZW1haWwiOiJub3JhbHJ1c3NlbEBleGFtcGxlLmNvbSIsInJvbGUiOiIwMDAxIiwiaWF0IjoxNzI5MTI5MDE3fQ.yk2PE1Lcfzbg6Nno4I0dAYxB21Qd1guyuAbzHMBRC0s', '2024-10-22 11:17:14'),
(9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiYTZmNzEyMWMtYTYwOC00NmQ0LTk4YjctOTJlNjcxM2E1ZDY1IiwiZW1haWwiOiJhZG1pbkBleGFtcGxlLmNvbSIsInJvbGUiOiI0RE0xTiIsImlhdCI6MTcyOTU2NzA1MX0.6JQWuyPEJQOLWdrCVaU-b_403ppiTqJM_LMS-TYESNE', '2024-10-22 11:17:54'),
(10, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiYTBiZTUxNjYtY2RiMC00MDgxLWFiODYtOTYyZjc3YmEzYWI0IiwiZW1haWwiOiJub3JhbHJ1c3NlbEBleGFtcGxlLmNvbSIsInJvbGUiOiIwMDAxIiwiaWF0IjoxNzI5NTc0NDA2fQ.0bd2SftdG8F_ORWkrnzOwBURUbikOCaqm9FuuYhiFEc', '2024-10-22 13:20:54'),
(11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiYTZmNzEyMWMtYTYwOC00NmQ0LTk4YjctOTJlNjcxM2E1ZDY1IiwiZW1haWwiOiJhZG1pbkBleGFtcGxlLmNvbSIsInJvbGUiOiI0RE0xTiIsImlhdCI6MTcyOTU3NDcyNn0.LVo47ikJv1baf_Mt1w9ZY7UdGjHc3FscXW74ZBAbHfM', '2024-10-22 13:31:38'),
(12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiYTZmNzEyMWMtYTYwOC00NmQ0LTk4YjctOTJlNjcxM2E1ZDY1IiwiZW1haWwiOiJhZG1pbkBleGFtcGxlLmNvbSIsInJvbGUiOiI0RE0xTiIsImlhdCI6MTcyOTExNTIyNn0.LO17_XlfUpRiwPNyvsPCCSCF_q0lNL_JwZtEbPOaXyo', '2024-10-23 14:59:42'),
(13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiYTZmNzEyMWMtYTYwOC00NmQ0LTk4YjctOTJlNjcxM2E1ZDY1IiwiZW1haWwiOiJhZG1pbkBleGFtcGxlLmNvbSIsInJvbGUiOiI0RE0xTiIsImlhdCI6MTczMTM4MTU0Nn0.abda8ZsQ3Djt_ihDex5j4spaK62JNoTmzTY2h8HpIfk', '2024-11-12 11:19:38'),
(14, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiMmQ2Y2RhMDQtZDUyMy00ZjdlLTgxZjgtYzUzMjMzMDRkNGI3IiwiZW1haWwiOiJtYXJ5LmphbmVAZXhhbXBsZS5jb20iLCJyb2xlIjoiMDAwMiIsImlhdCI6MTczMTQyNDQ4MH0.Q0pixbvGTd9OPB9ReYPMouHbvd8XRrr1lIp0obdd6_4', '2024-11-12 23:29:42');

INSERT INTO `product` (`product_id`, `product_name`, `description`, `price`, `stock_quantity`, `category`, `size`, `color`, `product_image`, `seller_id`, `created_at`, `updated_at`) VALUES
(1, 'Casual T-Shirt', 'Comfortable cotton t-shirt for everyday wear.', 250.00, 150, 'Clothing', 'M', 'Blue', 'public/images/tshirt_blue.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 22:40:53', '2024-11-12 22:40:53'),
(2, 'Running Shoes', 'Lightweight and durable running shoes.', 1799.00, 75, 'Shoes', '9', 'Black', 'public/images/running_shoes_black.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-12 22:40:53', '2024-11-12 22:40:53'),
(3, 'Phone Case', 'Shock-resistant case for all phone models.', 120.00, 250, 'Mobile Accessories', 'One Size', 'Transparent', 'public/images/phone_case.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 22:40:53', '2024-11-12 22:40:53'),
(4, 'Winter Jacket', 'Waterproof jacket suitable for cold weather.', 890.00, 30, 'Clothing', 'L', 'Gray', 'public/images/winter_jacket.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 22:40:53', '2024-11-12 22:40:53'),
(5, 'Formal Shoes', 'Elegant formal shoes for special occasions.', 2500.00, 40, 'Shoes', '10', 'Brown', 'public/images/formal_shoes_brown.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 22:40:53', '2024-11-12 22:40:53'),
(6, 'Wireless Earbuds', 'Noise-cancelling wireless earbuds with high-quality sound.', 200.00, 100, 'Mobile Accessories', 'One Size', 'White', 'public/images/wireless_earbuds.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 22:40:53', '2024-11-12 22:40:53'),
(12, 'Boots of Travel', 'Maroon', 2500.00, 10, 'Shoes', 'Large', 'Maroon', 'public/images/673370601d550_300x300_2.jpeg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 23:12:32', '2024-11-12 23:12:32'),
(13, 'Arcane Boots', 'Magi equipped with these boots are valued in battle.', 1500.00, 10, 'Shoes', 'Large', 'Blue', 'public/images/6733725f7bf1b_300x300_2.jpeg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 23:21:03', '2024-11-12 23:21:03');

INSERT INTO `product_images` (`image_id`, `product_id`, `image_url`) VALUES
(7, 12, 'public/images/673370602246a_500x500.jpeg'),
(8, 12, 'public/images/673370602387f_500x500.jpeg'),
(9, 13, 'public/images/6733725f7fd26_500x500.jpeg'),
(10, 13, 'public/images/6733725f82ca3_500x500.jpeg');

INSERT INTO `users` (`uuid`, `user_id`, `first_name`, `last_name`, `email`, `password`, `birthdate`, `email_verified_at`, `created_at`, `role`, `verification_token`, `address`, `phone_number`) VALUES
('074908a1-dfa8-40c5-8de4-6e8bab3772ae', 63, 'Admin', 'Admin', 'admin@example.com', '$2y$10$QgURm1QrnPwVoUDq77jIouOcQKt2iorBcGTK0VoNT/wWrwcfveSSi', '1990-01-01', '2024-11-12 14:36:28', '2024-11-12 22:36:40', '4DM1N', NULL, '123 Admin St.', '1234567890'),
('1a32c55b-a62a-4c1f-8141-a8c1f51f82d6', 1, 'John', 'Doe', 'john.doe@example.com', '$2y$10$JskYKUebDBL/yxedHzNsqOtjQaEsEhT.eSVKAWXMJU4bzoq79PBO.', '1990-01-01', '2024-11-12 22:46:36', '2024-11-12 22:38:03', '0001', NULL, '123 Main St', '123-456-7890'),
('2d6cda04-d523-4f7e-81f8-c5323304d4b7', 2, 'Mary Jane', 'Doe', 'mary.jane@example.com', '$2y$10$JskYKUebDBL/yxedHzNsqOtjQaEsEhT.eSVKAWXMJU4bzoq79PBO.', '1991-01-01', '2024-11-12 22:51:30', '2024-11-12 22:38:03', '0002', NULL, '456 Oak Ave', '234-567-8901'),
('3287ba46-bfb0-4ad8-9856-6ece577c38e7', 3, 'Juan', 'Dela Cruz', 'juan.delacruz@example.com', '$2y$10$JskYKUebDBL/yxedHzNsqOtjQaEsEhT.eSVKAWXMJU4bzoq79PBO.', '1992-01-01', '2024-11-12 22:51:38', '2024-11-12 22:38:03', '0001', NULL, '789 Pine Rd', '345-678-9012'),
('42ea0f8f-9217-40a6-a74c-e8d93724b9d4', 4, 'Victor', 'Magtanggol', 'victor.magtanggol@example.com', '$2y$10$JskYKUebDBL/yxedHzNsqOtjQaEsEhT.eSVKAWXMJU4bzoq79PBO.', '1993-01-01', '2024-11-12 23:01:53', '2024-11-12 22:38:03', '0002', NULL, '101 Maple St', '456-789-0123'),
('4329743b-c5b0-4130-bc1e-780e464ab67b', 5, 'Cardo', 'Dalisay', 'cardo.dalisay@example.com', '$2y$10$JskYKUebDBL/yxedHzNsqOtjQaEsEhT.eSVKAWXMJU4bzoq79PBO.', '1994-01-01', '2024-11-12 23:02:03', '2024-11-12 22:38:03', '0001', NULL, '202 Birch Ln', '567-890-1234'),
('56fc9f91-520c-4dd6-b737-69b0557a683e', 6, 'Jesus', 'Dimaguiba', 'jesus.dimaguiba@example.com', '$2y$10$JskYKUebDBL/yxedHzNsqOtjQaEsEhT.eSVKAWXMJU4bzoq79PBO.', '1995-01-01', '2024-11-05 23:02:06', '2024-11-12 22:38:03', '0002', NULL, '303 Cedar Blvd', '678-901-2345');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;