-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2025-12-13 11:35:24
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bakeryhouse`
--

-- --------------------------------------------------------

--
-- Table structure for `user_db`
--

CREATE TABLE `user_db` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_db`
--

INSERT INTO `user_db` (`id`, `name`, `email`, `password`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Debug User', 'debug@123.com', '$2y$10$Ul6J1xAZIEGEhAeGta.6Y.kW/yFXfKZ3aTpaq956xGB0dFIonlAuu', NULL, NULL, '2025-11-25 18:13:56', '2025-11-25 18:13:56'),
(2, 'Test User', 'test@bakery.com', '$2y$10$1oETZt6Gm7WhRxBcFx045Ob40XfQHnAxW9hUr2XusPU1kuTC36odq', NULL, NULL, '2025-11-25 18:16:16', '2025-11-25 18:16:16'),
(3, 'test2', 'test2@bakery.com', '$2y$10$J8Dqf8OTQlZNotVR7PgSyOBAVURHx6dzDELxnZVEBSbcq6JFSicRm', NULL, NULL, '2025-11-26 21:11:42', '2025-11-26 21:11:42'),
(9, 'gigo', 'gigo@gmail.com', '$2y$10$FDFTjifntwtNo5ZXzBxSWuJqx.HNKAzZGtzCd25bO2FC0tmePzZku', '01123457798', '18,jalan bunga,taman bunga,melaka,74535', '2025-11-30 06:05:03', '2025-12-01 15:02:14'),
(11, 'janustan', 'janustan1156@gmail.com', '$2y$10$0yZJhx7kmGB7FnU1TfJkpeppCA9ChYRxBcm9cqCgaXMWBoV6vX/ea', '0156677235', '7,taman oren,oren tambah susu,melaka,75453', '2025-12-01 15:03:24', '2025-12-01 15:26:38'),
(12, 'bruce', 'bruce123@gmail.com', '$2y$10$3lfZz23rdWSSkX719GHDNOLnJ45YPFhevjgrxrTzkFU/fx0esQ9mC', '012345656', '18,taman bunga,melaka,76542', '2025-12-03 06:40:40', '2025-12-03 06:45:37'),
(13, 'shane', 'shane@gmail.com', '$2y$10$kbYtPYQxJsHuzzcNzpvCmuIy/Qtfg83RfYCOgQGfARKsuDNkZZ7v6', NULL, NULL, '2025-12-03 16:57:58', '2025-12-03 16:57:58'),
(14, 'jackson', 'jackson16@gmail.com', '$2y$10$O/BR780RknNnUtBxAZvMbuijC/wAjWpvmRuHjHXCyiTzosLnfiSdy', '', '', '2025-12-08 08:53:28', '2025-12-08 09:04:49'),
(15, 'test', 'test@gmail.com', '$2y$10$fGpZQ15B3SQqXtrWh77rh.TNDZyfdR/zPaLeJSxlfV5Q9FwoRsySm', NULL, NULL, '2025-12-13 10:22:04', '2025-12-13 10:33:59');

--
-- Dumping indexes for table `user_db`
--

--
-- Indexes for table `user_db`
--
ALTER TABLE `user_db`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Using AUTO_INCREMENT for dumped tables
--

--
-- Using AUTO_INCREMENT for table `user_db`
--
ALTER TABLE `user_db`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
