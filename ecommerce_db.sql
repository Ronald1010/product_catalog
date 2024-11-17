-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2024 at 03:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `blacklisted_tokens`
--

CREATE TABLE `blacklisted_tokens` (
  `id` int(11) NOT NULL,
  `token` text NOT NULL,
  `blacklisted_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `blacklisted_tokens`
--

INSERT INTO `blacklisted_tokens` (`id`, `token`, `blacklisted_at`) VALUES
(6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiNjk4MGVhZDQtZmMwMy00MTk0LTk0MjAtODU1ZTgwZTNlNzFmIiwiZW1haWwiOiJhbGpvbkBleGFtcGxlLmNvbSIsInJvbGUiOiIwMDAxIiwiaWF0IjoxNzI5MDk0MDU2fQ.eLAG1kPsG5teYTFq5nNMEIAv327X5VdN2oCe_rIEYQY', '2024-10-16 15:54:38'),
(7, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiYTBiZTUxNjYtY2RiMC00MDgxLWFiODYtOTYyZjc3YmEzYWI0IiwiZW1haWwiOiJub3JhbHJ1c3NlbEBleGFtcGxlLmNvbSIsInJvbGUiOiIwMDAxIiwiaWF0IjoxNzI5MTE5MDA5fQ.zy1_OkKN8G7ifSOWI98BjShxiW2cWo29SufPixPxRYI', '2024-10-16 22:50:21'),
(8, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiYTBiZTUxNjYtY2RiMC00MDgxLWFiODYtOTYyZjc3YmEzYWI0IiwiZW1haWwiOiJub3JhbHJ1c3NlbEBleGFtcGxlLmNvbSIsInJvbGUiOiIwMDAxIiwiaWF0IjoxNzI5MTI5MDE3fQ.yk2PE1Lcfzbg6Nno4I0dAYxB21Qd1guyuAbzHMBRC0s', '2024-10-22 03:17:14'),
(9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiYTZmNzEyMWMtYTYwOC00NmQ0LTk4YjctOTJlNjcxM2E1ZDY1IiwiZW1haWwiOiJhZG1pbkBleGFtcGxlLmNvbSIsInJvbGUiOiI0RE0xTiIsImlhdCI6MTcyOTU2NzA1MX0.6JQWuyPEJQOLWdrCVaU-b_403ppiTqJM_LMS-TYESNE', '2024-10-22 03:17:54'),
(10, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiYTBiZTUxNjYtY2RiMC00MDgxLWFiODYtOTYyZjc3YmEzYWI0IiwiZW1haWwiOiJub3JhbHJ1c3NlbEBleGFtcGxlLmNvbSIsInJvbGUiOiIwMDAxIiwiaWF0IjoxNzI5NTc0NDA2fQ.0bd2SftdG8F_ORWkrnzOwBURUbikOCaqm9FuuYhiFEc', '2024-10-22 05:20:54'),
(11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiYTZmNzEyMWMtYTYwOC00NmQ0LTk4YjctOTJlNjcxM2E1ZDY1IiwiZW1haWwiOiJhZG1pbkBleGFtcGxlLmNvbSIsInJvbGUiOiI0RE0xTiIsImlhdCI6MTcyOTU3NDcyNn0.LVo47ikJv1baf_Mt1w9ZY7UdGjHc3FscXW74ZBAbHfM', '2024-10-22 05:31:38'),
(12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiYTZmNzEyMWMtYTYwOC00NmQ0LTk4YjctOTJlNjcxM2E1ZDY1IiwiZW1haWwiOiJhZG1pbkBleGFtcGxlLmNvbSIsInJvbGUiOiI0RE0xTiIsImlhdCI6MTcyOTExNTIyNn0.LO17_XlfUpRiwPNyvsPCCSCF_q0lNL_JwZtEbPOaXyo', '2024-10-23 06:59:42'),
(13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiYTZmNzEyMWMtYTYwOC00NmQ0LTk4YjctOTJlNjcxM2E1ZDY1IiwiZW1haWwiOiJhZG1pbkBleGFtcGxlLmNvbSIsInJvbGUiOiI0RE0xTiIsImlhdCI6MTczMTM4MTU0Nn0.abda8ZsQ3Djt_ihDex5j4spaK62JNoTmzTY2h8HpIfk', '2024-11-12 03:19:38'),
(14, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiMmQ2Y2RhMDQtZDUyMy00ZjdlLTgxZjgtYzUzMjMzMDRkNGI3IiwiZW1haWwiOiJtYXJ5LmphbmVAZXhhbXBsZS5jb20iLCJyb2xlIjoiMDAwMiIsImlhdCI6MTczMTQyNDQ4MH0.Q0pixbvGTd9OPB9ReYPMouHbvd8XRrr1lIp0obdd6_4', '2024-11-12 15:29:42'),
(15, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1dWlkIjoiMmQ2Y2RhMDQtZDUyMy00ZjdlLTgxZjgtYzUzMjMzMDRkNGI3IiwiZW1haWwiOiJtYXJ5LmphbmVAZXhhbXBsZS5jb20iLCJyb2xlIjoiMDAwMiIsImlhdCI6MTczMTQ2ODgwOH0.mIqdHpxIeclj9BiAvtHyCOuekeu3OBE20XkKouQe2nc', '2024-11-16 05:52:54');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `add_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `cart_item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_table`
--

CREATE TABLE `order_table` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_amount` decimal(10,10) NOT NULL,
  `status` varchar(50) NOT NULL,
  `payment_method` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_table`
--

CREATE TABLE `payment_table` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payment_amount` decimal(10,10) NOT NULL,
  `payment_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `size` varchar(50) NOT NULL,
  `color` varchar(50) NOT NULL,
  `product_image` text NOT NULL,
  `seller_id` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `description`, `price`, `stock_quantity`, `size`, `color`, `product_image`, `seller_id`, `created_at`, `updated_at`, `category`) VALUES
(1, 'Casual T-Shirt', 'Comfortable cotton t-shirt for everyday wear.', 250.00, 150, 'M', 'Blue', 'public/images/tshirt_blue.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Casual Wear'),
(2, 'Summer Shorts', 'Lightweight shorts perfect for warm days.', 300.00, 100, 'L', 'Khaki', 'public/images/shorts_khaki.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Casual Wear'),
(3, 'Winter Jacket', 'Waterproof jacket suitable for cold weather.', 890.00, 30, 'L', 'Gray', 'public/images/winter_jacket.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Winter Clothing'),
(4, 'Woolen Scarf', 'Warm scarf made from pure wool.', 200.00, 50, 'One Size', 'Red', 'public/images/woolen_scarf.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Winter Clothing'),
(5, 'Blazer Jacket', 'Elegant blazer for formal occasions.', 1500.00, 25, 'XL', 'Black', 'public/images/blazer_black.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Formal Wear'),
(6, 'Silk Tie', 'Luxury silk tie to complete your formal look.', 500.00, 80, 'One Size', 'Navy', 'public/images/silk_tie.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Formal Wear'),
(7, 'Running Tights', 'Breathable running tights for maximum comfort.', 400.00, 60, 'M', 'Black', 'public/images/running_tights.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Sportswear'),
(8, 'Sports Jersey', 'Lightweight jersey for active lifestyles.', 350.00, 100, 'L', 'Green', 'public/images/sports_jersey.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Sportswear'),
(9, 'Swimsuit', 'Stretchable swimsuit for water activities.', 800.00, 40, 'M', 'Blue', 'public/images/swimsuit.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Swimwear'),
(10, 'Beach Shorts', 'Colorful shorts for sunny beach days.', 350.00, 70, 'L', 'Yellow', 'public/images/beach_shorts.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Swimwear'),
(11, 'Lab Coat', 'Essential lab coat for professionals.', 1200.00, 15, 'XL', 'White', 'public/images/lab_coat.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Workwear'),
(12, 'Work Gloves', 'Durable gloves for all kinds of tasks.', 100.00, 200, 'One Size', 'Gray', 'public/images/work_gloves.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Workwear'),
(13, 'Hiking Jacket', 'Durable and lightweight jacket for hiking trips.', 1200.00, 40, 'L', 'Olive Green', 'public/images/hiking_jacket.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Outdoor Gear'),
(14, 'Cargo Pants', 'Multi-pocket cargo pants for outdoor adventures.', 850.00, 60, 'M', 'Brown', 'public/images/cargo_pants.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Outdoor Gear'),
(15, 'Pajama Set', 'Soft and cozy pajamas for a good night’s sleep.', 400.00, 100, 'L', 'Gray', 'public/images/pajama_set.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Sleepwear'),
(16, 'Nightgown', 'Elegant silk nightgown.', 750.00, 70, 'M', 'Pink', 'public/images/nightgown.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Sleepwear'),
(17, 'Kurta', 'Traditional kurta made with breathable fabric.', 550.00, 90, 'XL', 'White', 'public/images/kurta.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Ethnic Wear'),
(18, 'Saree', 'Elegant saree with intricate designs.', 3000.00, 20, 'One Size', 'Red', 'public/images/saree.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Ethnic Wear'),
(19, 'Graphic T-Shirt', 'Stylish t-shirt with modern graphic design.', 270.00, 120, 'M', 'Black', 'public/images/graphic_tshirt.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Casual Wear'),
(20, 'Jeans', 'Classic denim jeans for everyday wear.', 800.00, 85, 'L', 'Indigo', 'public/images/jeans.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Casual Wear'),
(21, 'Dress Pants', 'Tailored pants for formal occasions.', 950.00, 60, 'XL', 'Gray', 'public/images/dress_pants.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Formal Wear'),
(22, 'Sports Shorts', 'Quick-dry shorts for active lifestyles.', 350.00, 150, 'M', 'Blue', 'public/images/sports_shorts.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Sportswear'),
(23, 'Swim Trunks', 'Comfortable swimwear for men.', 500.00, 50, 'L', 'Red', 'public/images/swim_trunks.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Swimwear'),
(24, 'Work Apron', 'Protective apron for chefs and artists.', 300.00, 40, 'One Size', 'Black', 'public/images/work_apron.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Workwear'),
(25, 'Raincoat', 'Lightweight and waterproof raincoat for outdoor activities.', 700.00, 80, 'M', 'Yellow', 'public/images/raincoat.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Outdoor Gear'),
(26, 'Hiking Boots', 'Durable boots for rough terrain.', 2400.00, 35, '10', 'Brown', 'public/images/hiking_boots.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Outdoor Gear'),
(27, 'Sleep Mask', 'Soft sleep mask for better rest.', 150.00, 120, 'One Size', 'Black', 'public/images/sleep_mask.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Sleepwear'),
(28, 'Fleece Robe', 'Warm fleece robe for lounging.', 850.00, 40, 'L', 'Gray', 'public/images/fleece_robe.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Sleepwear'),
(29, 'Lehenga', 'Traditional Indian lehenga with embroidery.', 4500.00, 15, 'One Size', 'Maroon', 'public/images/lehenga.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Ethnic Wear'),
(30, 'Sherwani', 'Elegant sherwani for special occasions.', 6000.00, 10, 'L', 'Gold', 'public/images/sherwani.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Ethnic Wear'),
(31, 'Hoodie', 'Comfortable hoodie for casual wear.', 900.00, 100, 'XL', 'Black', 'public/images/hoodie.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Casual Wear'),
(32, 'Printed Tank Top', 'Trendy tank top with modern prints.', 350.00, 120, 'M', 'White', 'public/images/tank_top.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Casual Wear'),
(33, 'Tuxedo', 'Elegant tuxedo for formal events.', 8500.00, 5, 'L', 'Black', 'public/images/tuxedo.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Formal Wear'),
(34, 'Pencil Skirt', 'Sophisticated pencil skirt for office wear.', 700.00, 70, 'M', 'Navy', 'public/images/pencil_skirt.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Formal Wear'),
(35, 'Yoga Pants', 'Stretchable pants for yoga and exercise.', 500.00, 150, 'M', 'Black', 'public/images/yoga_pants.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Sportswear'),
(36, 'Gym Tank Top', 'Lightweight top for gym workouts.', 300.00, 80, 'L', 'Gray', 'public/images/gym_tank.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Sportswear'),
(37, 'Bikini Set', 'Two-piece swimsuit for beachwear.', 1200.00, 50, 'M', 'Blue', 'public/images/bikini_set.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Swimwear'),
(38, 'Swimming Goggles', 'Anti-fog goggles for swimming.', 250.00, 200, 'One Size', 'Black', 'public/images/swimming_goggles.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Swimwear'),
(39, 'Coveralls', 'Protective coveralls for industrial work.', 1500.00, 25, 'XL', 'Navy', 'public/images/coveralls.jpg', '56fc9f91-520c-4dd6-b737-69b0557a683e', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Workwear'),
(40, 'Steel Toe Boots', 'Sturdy boots with steel toe protection.', 3200.00, 30, '10', 'Brown', 'public/images/steel_toe_boots.jpg', '2d6cda04-d523-4f7e-81f8-c5323304d4b7', '2024-11-12 14:40:53', '2024-11-12 14:40:53', 'Workwear'),
(101, 'Plaid Shirt', 'Comfortable plaid shirt perfect for casual wear.', 750.00, 100, 'L', 'Red', 'public/images/plaid_shirt.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Casual Wear'),
(102, 'Hooded Sweatshirt', 'Warm and cozy hoodie for everyday use.', 1200.00, 75, 'M', 'Gray', 'public/images/hooded_sweatshirt.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Casual Wear'),
(103, 'Skinny Jeans', 'Stylish skinny jeans with stretch fabric.', 950.00, 120, '32', 'Dark Blue', 'public/images/skinny_jeans.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Casual Wear'),
(104, 'Bomber Jacket', 'Lightweight bomber jacket with a classic fit.', 2200.00, 50, 'M', 'Black', 'public/images/bomber_jacket.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Outerwear'),
(105, 'Sports Tights', 'Performance tights for running and training.', 850.00, 80, 'L', 'Black', 'public/images/sports_tights.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Sportswear'),
(106, 'Yoga Pants', 'High-waisted yoga pants for flexibility and comfort.', 700.00, 90, 'M', 'Gray', 'public/images/yoga_pants.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Sportswear'),
(107, 'Winter Scarf', 'Knitted scarf for warmth and style.', 400.00, 200, 'One Size', 'Beige', 'public/images/winter_scarf.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Accessories'),
(108, 'Leather Gloves', 'Elegant leather gloves for cold weather.', 1500.00, 40, 'M', 'Brown', 'public/images/leather_gloves.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Accessories'),
(109, 'Floral Dress', 'Lightweight floral dress for summer.', 1400.00, 60, 'M', 'Pink', 'public/images/floral_dress.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Dresses'),
(110, 'Linen Pants', 'Breathable linen pants for casual outings.', 1100.00, 70, 'L', 'White', 'public/images/linen_pants.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Casual Wear'),
(111, 'Tank Top', 'Basic tank top for layering or workouts.', 350.00, 150, 'S', 'Navy', 'public/images/tank_top.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Casual Wear'),
(112, 'Puffer Jacket', 'Insulated puffer jacket for winter.', 3200.00, 35, 'M', 'Green', 'public/images/puffer_jacket.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Outerwear'),
(113, 'Wide-Brim Hat', 'Stylish hat for sun protection.', 600.00, 50, 'One Size', 'Tan', 'public/images/wide_brim_hat.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Accessories'),
(114, 'Tracksuit Set', 'Comfortable tracksuit for leisure or training.', 2200.00, 45, 'L', 'Black/Gray', 'public/images/tracksuit_set.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Sportswear'),
(115, 'Sundress', 'Light and breezy sundress for summer days.', 1300.00, 50, 'M', 'Yellow', 'public/images/sundress.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Dresses'),
(116, 'Cardigan', 'Soft knit cardigan for layering.', 1200.00, 60, 'L', 'Maroon', 'public/images/cardigan.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Outerwear'),
(117, 'Button-Up Shirt', 'Classic button-up shirt for work or casual use.', 800.00, 90, 'M', 'White', 'public/images/button_up_shirt.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Casual Wear'),
(118, 'Chinos', 'Slim-fit chinos with a modern look.', 1100.00, 80, '34', 'Beige', 'public/images/chinos.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Casual Wear'),
(119, 'Swim Shorts', 'Quick-drying swim shorts for the beach.', 600.00, 70, 'L', 'Blue', 'public/images/swim_shorts.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Swimwear'),
(120, 'Raincoat', 'Waterproof raincoat with a hood.', 2500.00, 40, 'M', 'Yellow', 'public/images/raincoat.jpg', '42ea0f8f-9217-40a6-a74c-e8d93724b9d4', '2024-11-17 02:00:00', '2024-11-17 02:00:00', 'Outerwear');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `token_id` int(11) NOT NULL,
  `uuid` char(36) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `token_type` varchar(50) DEFAULT NULL,
  `issued_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uuid` char(36) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `role` enum('0001','0002','4DM1N') DEFAULT NULL,
  `verification_token` varchar(64) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uuid`, `user_id`, `first_name`, `last_name`, `email`, `password`, `birthdate`, `email_verified_at`, `created_at`, `role`, `verification_token`, `address`, `phone_number`) VALUES
('074908a1-dfa8-40c5-8de4-6e8bab3772ae', 63, 'Admin', 'Admin', 'admin@example.com', '$2y$10$QgURm1QrnPwVoUDq77jIouOcQKt2iorBcGTK0VoNT/wWrwcfveSSi', '1990-01-01', '2024-11-12 14:36:28', '2024-11-12 14:36:40', '4DM1N', NULL, '123 Admin St.', '1234567890'),
('1a32c55b-a62a-4c1f-8141-a8c1f51f82d6', 1, 'John', 'Doe', 'john.doe@example.com', '$2y$10$JskYKUebDBL/yxedHzNsqOtjQaEsEhT.eSVKAWXMJU4bzoq79PBO.', '1990-01-01', '2024-11-12 22:46:36', '2024-11-12 14:38:03', '0001', NULL, '123 Main St', '123-456-7890'),
('2d6cda04-d523-4f7e-81f8-c5323304d4b7', 2, 'Mary Jane', 'Doe', 'mary.jane@example.com', '$2y$10$JskYKUebDBL/yxedHzNsqOtjQaEsEhT.eSVKAWXMJU4bzoq79PBO.', '1991-01-01', '2024-11-12 22:51:30', '2024-11-12 14:38:03', '0002', NULL, '456 Oak Ave', '234-567-8901'),
('3287ba46-bfb0-4ad8-9856-6ece577c38e7', 3, 'Juan', 'Dela Cruz', 'juan.delacruz@example.com', '$2y$10$JskYKUebDBL/yxedHzNsqOtjQaEsEhT.eSVKAWXMJU4bzoq79PBO.', '1992-01-01', '2024-11-12 22:51:38', '2024-11-12 14:38:03', '0001', NULL, '789 Pine Rd', '345-678-9012'),
('42ea0f8f-9217-40a6-a74c-e8d93724b9d4', 4, 'Victor', 'Magtanggol', 'victor.magtanggol@example.com', '$2y$10$JskYKUebDBL/yxedHzNsqOtjQaEsEhT.eSVKAWXMJU4bzoq79PBO.', '1993-01-01', '2024-11-12 23:01:53', '2024-11-12 14:38:03', '0002', NULL, '101 Maple St', '456-789-0123'),
('4329743b-c5b0-4130-bc1e-780e464ab67b', 5, 'Cardo', 'Dalisay', 'cardo.dalisay@example.com', '$2y$10$JskYKUebDBL/yxedHzNsqOtjQaEsEhT.eSVKAWXMJU4bzoq79PBO.', '1994-01-01', '2024-11-12 23:02:03', '2024-11-12 14:38:03', '0001', NULL, '202 Birch Ln', '567-890-1234'),
('56fc9f91-520c-4dd6-b737-69b0557a683e', 6, 'Jesus', 'Dimaguiba', 'jesus.dimaguiba@example.com', '$2y$10$JskYKUebDBL/yxedHzNsqOtjQaEsEhT.eSVKAWXMJU4bzoq79PBO.', '1995-01-01', '2024-11-05 23:02:06', '2024-11-12 14:38:03', '0002', NULL, '303 Cedar Blvd', '678-901-2345');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blacklisted_tokens`
--
ALTER TABLE `blacklisted_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `order_table`
--
ALTER TABLE `order_table`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_table`
--
ALTER TABLE `payment_table`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `unique_product_per_seller` (`product_name`,`seller_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`token_id`),
  ADD KEY `uuid` (`uuid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blacklisted_tokens`
--
ALTER TABLE `blacklisted_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_table`
--
ALTER TABLE `order_table`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `payment_table`
--
ALTER TABLE `payment_table`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `token`
--
ALTER TABLE `token`
  MODIFY `token_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `order_table`
--
ALTER TABLE `order_table`
  ADD CONSTRAINT `order_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `payment_table`
--
ALTER TABLE `payment_table`
  ADD CONSTRAINT `payment_table_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order_table` (`order_id`),
  ADD CONSTRAINT `payment_table_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `token_ibfk_1` FOREIGN KEY (`uuid`) REFERENCES `users` (`uuid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
