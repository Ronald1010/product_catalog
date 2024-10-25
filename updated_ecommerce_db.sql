-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 24, 2024 at 01:42 AM
-- Server version: 8.0.35
-- PHP Version: 8.2.20

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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uuid` char(36) NOT NULL,
  `user_id` int NOT NULL,
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
  `phone_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uuid`, `user_id`, `first_name`, `last_name`, `email`, `password`, `birthdate`, `email_verified_at`, `created_at`, `role`, `verification_token`, `address`, `phone_number`) VALUES
('3287ba46-bfb0-4ad8-9856-6ece577c38e7', 51, 'John Michael', 'Galido', 'jmgalido@example.com', '$2y$10$/33jQB0kmjpch3gvpTcqZOp7Ds4l2bPR8vCXgESlPBgg31DSoYIxe', '1990-01-01', NULL, '2024-10-16 22:32:26', '0001', 'e6db0bca25c2fe26248820ce39a58f74a9206fc1b272d0c393b630fc2687fce0', NULL, NULL),
('6980ead4-fc03-4194-9420-855e80e3e71f', 47, 'Throw', 'Away', 'updatedemail@example.com', '$2y$10$uDEcDILFbATfSLngVaQDF.VUNb3aVJotDcI5L7eyIy0n5dFQfzF0m', '1990-01-01', '2024-10-16 23:54:02', '2024-10-16 15:53:24', '0002', NULL, 'New Address', '123456789'),
('a6f7121c-a608-46d4-98b7-92e6713a5d65', 49, 'Admin', 'Admin', 'admin@example.com', '$2y$10$k8Tabvhd4OE7exfKkBYk1.Z0jawIs1OB3S/nN/ERASF01oXgSPQQS', '1990-01-01', '2024-10-16 16:06:02', '2024-10-16 16:06:28', '4DM1N', NULL, '123 Admin St.', '1234567890');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
