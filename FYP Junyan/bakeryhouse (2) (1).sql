-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2026 at 04:07 PM
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
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin') DEFAULT 'admin',
  `status` enum('active','inactive') DEFAULT 'active',
  `login_attempts` int(11) DEFAULT 0,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `role`, `status`, `login_attempts`, `last_login`, `created_at`) VALUES
(1, 'superadmin', 'superadmin@gmail.com', '$2y$12$fyerT6O5XC5d2gopGVhUhu9lJId.Xe5vOp71Te4hvgPwmQqNovVeO', 'super_admin', 'active', 0, '2025-12-03 16:59:03', '2025-12-03 16:48:52');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','preparing','ready','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_phone`, `delivery_address`, `city`, `postcode`, `total`, `status`, `created_at`, `payment_method`, `payment_status`) VALUES
(12, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 516.00, 'pending', '2025-12-16 07:39:21', NULL, 'pending'),
(13, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 656.00, 'pending', '2025-12-16 07:43:27', NULL, 'pending'),
(14, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'pending', '2025-12-24 07:01:01', NULL, 'pending'),
(15, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 88.00, 'pending', '2025-12-24 07:06:30', NULL, 'pending'),
(16, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 78.00, 'pending', '2025-12-24 07:12:07', NULL, 'pending'),
(17, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'pending', '2025-12-30 11:27:30', NULL, 'pending'),
(18, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 516.00, 'pending', '2025-12-30 11:44:15', NULL, 'pending'),
(19, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 398.00, 'pending', '2025-12-30 12:15:15', NULL, 'pending'),
(20, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 300.00, 'pending', '2025-12-30 12:23:19', NULL, 'pending'),
(21, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 98.00, 'pending', '2026-01-04 06:49:42', NULL, 'pending'),
(22, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 14.20, 'pending', '2026-01-04 06:57:05', NULL, 'pending'),
(23, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 516.00, 'pending', '2026-01-05 17:39:00', NULL, 'pending'),
(24, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 356.00, 'pending', '2026-01-06 14:26:16', NULL, 'pending'),
(25, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 98.00, 'cancelled', '2026-01-06 15:58:00', 'fpx', 'failed'),
(26, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-06 16:01:25', 'eWallet', 'paid'),
(27, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 88.00, 'preparing', '2026-01-06 16:10:27', 'eWallet', 'paid'),
(28, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'pending', '2026-01-06 16:24:10', 'fpx', 'pending'),
(29, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 558.00, 'preparing', '2026-01-06 16:31:32', 'eWallet', 'paid'),
(30, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 8.90, 'preparing', '2026-01-06 17:12:40', 'eWallet', 'paid'),
(31, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-06 18:00:19', 'debitCard', 'paid'),
(32, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-06 19:18:35', 'debitCard', 'paid'),
(33, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 300.00, 'preparing', '2026-01-07 05:10:48', 'debitCard', 'paid'),
(34, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-07 05:15:01', 'eWallet', 'paid'),
(35, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 98.00, 'preparing', '2026-01-07 05:18:23', 'debitCard', 'paid'),
(36, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-07 05:22:35', 'eWallet', 'paid'),
(37, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 98.00, 'preparing', '2026-01-07 05:24:47', 'fpx', 'paid'),
(38, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 300.00, 'pending', '2026-01-07 05:25:56', 'eWallet', 'pending'),
(39, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 300.00, 'pending', '2026-01-07 05:46:16', 'eWallet', 'pending'),
(40, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 300.00, 'pending', '2026-01-07 10:59:03', 'debitCard', 'pending'),
(41, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 2532.00, 'preparing', '2026-01-07 11:09:53', 'debitCard', 'paid'),
(42, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 516.00, 'preparing', '2026-01-07 11:28:36', 'eWallet', 'paid'),
(43, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 98.00, 'pending', '2026-01-07 11:30:45', 'debitCard', 'pending'),
(44, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 356.00, 'pending', '2026-01-07 11:31:14', 'fpx', 'pending'),
(45, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 356.00, 'pending', '2026-01-07 11:32:15', 'eWallet', 'pending'),
(46, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 356.00, 'pending', '2026-01-07 11:36:29', 'eWallet', 'pending'),
(47, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 356.00, 'pending', '2026-01-07 11:36:55', 'eWallet', 'pending'),
(48, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 356.00, 'pending', '2026-01-07 11:37:38', 'fpx', 'pending'),
(49, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 356.00, 'pending', '2026-01-07 11:44:11', 'eWallet', 'pending'),
(50, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 614.00, 'preparing', '2026-01-07 11:48:06', 'eWallet', 'paid'),
(51, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 516.00, 'preparing', '2026-01-07 12:15:11', 'eWallet', 'paid'),
(52, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'pending', '2026-01-07 12:24:32', 'eWallet', 'pending'),
(53, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-07 12:29:36', 'eWallet', 'paid'),
(54, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'pending', '2026-01-07 12:30:47', NULL, 'pending'),
(55, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 1192.00, 'pending', '2026-01-07 15:58:40', NULL, 'pending'),
(56, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-11 10:42:52', 'eWallet', 'paid'),
(57, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-11 12:10:31', 'eWallet', 'paid'),
(58, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 98.00, 'pending', '2026-01-11 12:21:14', NULL, 'pending'),
(59, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 692.00, 'preparing', '2026-01-12 15:44:57', 'eWallet', 'paid'),
(60, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 895.90, 'preparing', '2026-01-13 13:27:34', 'eWallet', 'paid'),
(61, 'ningyao', 'ningyao312@gmail.com', '0146700251', '17, Jalan Daya 9/15,Taman Daya', 'Bukit Beruang', '75400', 516.00, 'pending', '2026-01-13 18:23:26', 'eWallet', 'pending'),
(62, 'ningyao', 'ningyao312@gmail.com', '0146700251', '30, Jalan Setia 2/13,Taman Setia Indah', 'Bandar Melaka', '75400', 521.00, 'pending', '2026-01-13 18:32:01', 'eWallet', 'pending'),
(63, 'Chen Ping An', 'chenpingan111@gmail.com', '018-9117822', '26, Jalan Daya 1/15,Taman Daya', 'Bandar Melaka', '75400', 1871.20, 'pending', '2026-01-13 19:24:02', 'debitCard', 'pending'),
(64, 'Chen Ping An', 'chenpingan111@gmail.com', '018-9117822', '26, Jalan Daya 1/15,Taman Daya', 'Bandar Melaka', '75400', 1009.00, 'pending', '2026-01-13 20:10:45', 'tng', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `orders_detail`
--

CREATE TABLE `orders_detail` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders_detail`
--

INSERT INTO `orders_detail` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`, `created_at`) VALUES
(1, 12, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2025-12-16 07:39:21'),
(2, 13, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2025-12-16 07:43:27'),
(3, 13, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2025-12-16 07:43:27'),
(4, 13, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2025-12-16 07:43:27'),
(5, 14, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2025-12-24 07:01:01'),
(6, 15, 73, 'BLUEBERRY CHEESE', 88.00, 1, 88.00, '2025-12-24 07:06:30'),
(7, 16, 24, 'BEAR CANDLE', 78.00, 1, 78.00, '2025-12-24 07:12:07'),
(8, 17, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2025-12-30 11:27:30'),
(9, 18, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2025-12-30 11:44:15'),
(10, 19, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2025-12-30 12:15:15'),
(11, 19, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2025-12-30 12:15:15'),
(12, 20, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2025-12-30 12:23:19'),
(13, 21, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-04 06:49:42'),
(14, 22, 260, 'Almond Mascarpone Danish', 14.20, 1, 14.20, '2026-01-04 06:57:05'),
(15, 23, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-05 17:39:00'),
(16, 24, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-06 14:26:16'),
(17, 24, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-06 14:26:16'),
(18, 25, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-06 15:58:00'),
(19, 26, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-06 16:01:25'),
(20, 27, 93, 'PINK CELEBRATION TIER', 88.00, 1, 88.00, '2026-01-06 16:10:27'),
(21, 28, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-06 16:24:10'),
(22, 29, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-06 16:31:32'),
(23, 29, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-06 16:31:32'),
(24, 30, 276, 'Double Chocolate Chip Cookies', 8.90, 1, 8.90, '2026-01-06 17:12:40'),
(25, 31, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-06 18:00:19'),
(26, 32, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-06 19:18:35'),
(27, 33, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-07 05:10:48'),
(28, 34, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 05:15:01'),
(29, 35, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 05:18:23'),
(30, 36, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 05:22:35'),
(31, 37, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 05:24:47'),
(32, 38, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-07 05:25:56'),
(33, 39, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-07 05:46:16'),
(34, 40, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-07 10:59:03'),
(35, 41, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 5, 1500.00, '2026-01-07 11:09:53'),
(36, 41, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 4, 1032.00, '2026-01-07 11:09:53'),
(37, 42, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-07 11:28:36'),
(38, 43, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 11:30:45'),
(39, 44, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 11:31:14'),
(40, 44, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 11:31:14'),
(41, 45, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 11:32:15'),
(42, 45, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 11:32:15'),
(43, 46, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 11:36:29'),
(44, 46, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 11:36:29'),
(45, 47, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 11:36:55'),
(46, 47, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 11:36:55'),
(47, 48, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 11:37:38'),
(48, 48, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 11:37:38'),
(49, 49, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 11:44:11'),
(50, 49, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 11:44:11'),
(51, 50, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 11:48:06'),
(52, 50, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-07 11:48:06'),
(53, 51, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-07 12:15:11'),
(54, 52, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 12:24:32'),
(55, 53, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 12:29:36'),
(56, 54, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 12:30:47'),
(57, 55, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 3, 774.00, '2026-01-07 15:58:40'),
(58, 55, 146, 'Baby Elephant Forest Fondant Cake', 320.00, 1, 320.00, '2026-01-07 15:58:40'),
(59, 55, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 15:58:40'),
(60, 56, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-11 10:42:52'),
(61, 57, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-11 12:10:31'),
(62, 58, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-11 12:21:14'),
(63, 59, 1, 'A LITTLE SWEET', 98.00, 4, 392.00, '2026-01-12 15:44:57'),
(64, 59, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-12 15:44:57'),
(65, 60, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 3, 774.00, '2026-01-13 13:27:34'),
(66, 60, 103, 'Fruit Tart', 8.00, 1, 8.00, '2026-01-13 13:27:34'),
(67, 60, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-13 13:27:34'),
(68, 60, 240, 'Alsatian Kugelhopf Sweet Bread', 15.90, 1, 15.90, '2026-01-13 13:27:34'),
(69, 61, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-13 18:23:26'),
(70, 62, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-13 18:32:01'),
(71, 63, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-13 19:24:02'),
(72, 63, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-13 19:24:02'),
(73, 63, 146, 'Baby Elephant Forest Fondant Cake', 320.00, 1, 320.00, '2026-01-13 19:24:02'),
(74, 63, 23, 'BABY PENGUINSSS', 140.00, 2, 280.00, '2026-01-13 19:24:02'),
(75, 63, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-13 19:24:02'),
(76, 63, 111, 'Durian Father\'s Day Cake', 220.00, 1, 220.00, '2026-01-13 19:24:02'),
(77, 63, 240, 'Alsatian Kugelhopf Sweet Bread', 15.90, 1, 15.90, '2026-01-13 19:24:02'),
(78, 63, 222, 'Basic Sourdough Boule', 11.50, 1, 11.50, '2026-01-13 19:24:02'),
(79, 63, 246, 'Braided Sweet Yeast Bread', 14.80, 1, 14.80, '2026-01-13 19:24:02'),
(80, 63, 126, 'Oreo Monster Animal Cake', 138.00, 1, 138.00, '2026-01-13 19:24:02'),
(81, 63, 127, 'Pastel Unicorn Animal Cake', 138.00, 1, 138.00, '2026-01-13 19:24:02'),
(82, 64, 126, 'Oreo Monster Animal Cake', 138.00, 1, 138.00, '2026-01-13 20:10:45'),
(83, 64, 127, 'Pastel Unicorn Animal Cake', 138.00, 1, 138.00, '2026-01-13 20:10:45'),
(84, 64, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-13 20:10:45'),
(85, 64, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-13 20:10:45'),
(86, 64, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-13 20:10:45');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `created_at`) VALUES
(3, 'iplaygame317@gmail.com', '59f14842da4433c4cac270db411f0de715982179bdd8e38a5d761bf190d6fafb', '2025-12-15 15:08:20');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category`, `stock`, `description`, `image`, `created_at`) VALUES
(698, 'test', 0.10, 'Cake', 3, '', '', '2025-11-25 14:54:18'),
(699, 'test', 0.10, 'Cake', 1, '', '', '2025-11-25 14:54:27');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_text` text NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `address_text`, `is_default`, `updated_at`) VALUES
(1, 9, '18,jalan bunga,taman bunga,melaka,74535', 1, '2026-01-03 13:58:26'),
(2, 11, '7,taman oren,oren tambah susu,melaka,75453', 1, '2026-01-03 13:58:26'),
(3, 12, '18,taman bunga,melaka,76542', 1, '2026-01-03 13:58:26'),
(4, 15, 'Ayer Keroh|75000|123,jalan merdeka', 1, '2026-01-03 13:58:26'),
(9, 18, 'Ayer Keroh|75310|8,taman oren,5/23', 0, '2026-01-06 14:34:33'),
(13, 18, 'Ayer Keroh|73100|1,jalan mmu 9/100', 0, '2026-01-07 12:44:24'),
(14, 20, 'Bukit Beruang|75310|19,jalan kuning 1/21', 1, '2026-01-05 09:08:16'),
(15, 18, 'Ayer Keroh|75000|8,jalan mmu 8/8', 1, '2026-01-13 13:41:31'),
(17, 22, 'Bandar Melaka|75100|77,jalan height 9/99', 0, '2026-01-09 19:10:33'),
(18, 22, 'Ayer Keroh|75000|88,jalan jonker 88/8', 1, '2026-01-09 19:10:33'),
(19, 23, 'Ayer Keroh|73100|3,jalan lotus 3/33', 1, '2026-01-13 16:57:32'),
(20, 23, 'Bandar Melaka|75000|2,jalan aeon ,2/22', 0, '2026-01-13 16:57:32'),
(21, 18, '17,taman jonker 8/12, Ayer Keroh, 75450', 0, '2026-01-13 13:41:31'),
(22, 24, 'Bandar Melaka|75400|30,Jalan setia 2/13 Taman Setia Indah', 1, '2026-01-13 13:55:01'),
(23, 24, 'Bandar Melaka|75400|40,Jalan setia 6/13 Taman Setia Indah', 0, '2026-01-13 13:55:01'),
(24, 19, 'Bandar Melaka|75400|30, Jalan Setia 2/13,Taman Setia Indah', 1, '2026-01-13 18:28:36'),
(25, 19, 'Bukit Beruang|75400|17, Jalan Daya 9/15,Taman Daya', 0, '2026-01-13 18:28:36'),
(26, 25, 'Bandar Melaka|75400|26, Jalan Daya 1/15,Taman Daya', 1, '2026-01-14 14:53:50'),
(27, 25, 'Ayer Keroh|75400|68, Jalan Daya 3/4,Taman Daya', 0, '2026-01-14 14:53:50');

-- --------------------------------------------------------

--
-- Table structure for table `user_db`
--

CREATE TABLE `user_db` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_db`
--

INSERT INTO `user_db` (`id`, `name`, `email`, `password`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Debug User', 'debug@123.com', '$2y$10$Ul6J1xAZIEGEhAeGta.6Y.kW/yFXfKZ3aTpaq956xGB0dFIonlAuu', NULL, '2025-11-25 18:13:56', '2025-11-25 18:13:56'),
(2, 'Test User', 'test@bakery.com', '$2y$10$1oETZt6Gm7WhRxBcFx045Ob40XfQHnAxW9hUr2XusPU1kuTC36odq', NULL, '2025-11-25 18:16:16', '2025-11-25 18:16:16'),
(3, 'test2', 'test2@bakery.com', '$2y$10$J8Dqf8OTQlZNotVR7PgSyOBAVURHx6dzDELxnZVEBSbcq6JFSicRm', NULL, '2025-11-26 21:11:42', '2025-11-26 21:11:42'),
(9, 'gigo', 'gigo@gmail.com', '$2y$10$FDFTjifntwtNo5ZXzBxSWuJqx.HNKAzZGtzCd25bO2FC0tmePzZku', '01123457798', '2025-11-30 06:05:03', '2025-12-01 15:02:14'),
(11, 'janustan', 'janustan1156@gmail.com', '$2y$10$0yZJhx7kmGB7FnU1TfJkpeppCA9ChYRxBcm9cqCgaXMWBoV6vX/ea', '0156677235', '2025-12-01 15:03:24', '2025-12-01 15:26:38'),
(12, 'bruce', 'bruce123@gmail.com', '$2y$10$3lfZz23rdWSSkX719GHDNOLnJ45YPFhevjgrxrTzkFU/fx0esQ9mC', '012345656', '2025-12-03 06:40:40', '2025-12-03 06:45:37'),
(13, 'shane', 'shane@gmail.com', '$2y$10$kbYtPYQxJsHuzzcNzpvCmuIy/Qtfg83RfYCOgQGfARKsuDNkZZ7v6', NULL, '2025-12-03 16:57:58', '2025-12-03 16:57:58'),
(14, 'jackson', 'jackson16@gmail.com', '$2y$10$O/BR780RknNnUtBxAZvMbuijC/wAjWpvmRuHjHXCyiTzosLnfiSdy', '', '2025-12-08 08:53:28', '2025-12-08 09:04:49'),
(15, 'test', 'test@gmail.com', '$2y$10$fGpZQ15B3SQqXtrWh77rh.TNDZyfdR/zPaLeJSxlfV5Q9FwoRsySm', '01234567', '2025-12-13 10:22:04', '2025-12-16 06:12:22'),
(16, 'testing', 'iplaygame317@gmail.com', '$2y$10$PxbP3e8sEwB2vSDYPLfEzeBEuoEnjb0itreTKBvE0tFZX6/zB8/VW', NULL, '2025-12-14 14:14:31', '2025-12-14 14:14:31'),
(17, 'chunkit', 'chunkitwong719@gmail.com', '$2y$10$0d2rTrwKPJ9jw73utEqVOO0/mnR3MLYtL0BxsBZUwpe9XRdXQsji.', '01121611161', '2025-12-16 06:45:10', '2026-01-06 14:18:05'),
(18, 'Lim Sin Yi', 'sinyi224@gmail.com', '$2y$10$vk3GY0s3PjIvNG65UUPF5uw0r3lvPWZcDSkxbegsZzP6zdhdYUI.6', NULL, '2026-01-06 14:41:20', '2026-01-06 14:41:20'),
(19, 'ningyao', 'ningyao312@gmail.com', '$2y$10$388vzcMbk.CO/EUkPJofVOlobCJAjZaYO4LAJeRvCH6G4EP8G1mo6', '0146700251', '2026-01-07 11:12:48', '2026-01-13 18:09:04'),
(20, 'xiaowu', 'xiaowu269@gmail.com', '$2y$10$ftqQAAFlgoo6j8LPa.K0UuenmZUKWXPaReKfh.QIoYjmbD9ck7ceu', NULL, '2026-01-11 10:53:58', '2026-01-11 10:53:58'),
(21, 'tangsan', 'tangsan111@gmail.com', '$2y$10$gR2pSs9ElY6Sp5wtJshFYOb0MPAY9rkLKJOZNJD7YYt1/X5ZAE86C', NULL, '2026-01-11 10:55:11', '2026-01-11 10:55:11'),
(22, 'Ning Rong Rong', 'rongrong015@gmail.com', '$2y$10$EL3TPVCmGcEn5FoS1.ERturfRFg.Lmau2sj1H3oGSlb/IZfnNHCy2', NULL, '2026-01-11 11:00:22', '2026-01-11 11:00:22'),
(23, 'junjie', 'junjie312@gmail.com', '$2y$10$D./VbQ3J/zKilWkUhjqNPeIYHDZ8A6Jvq6kYPHVZjfaSMiS8PHoRi', NULL, '2026-01-11 11:01:29', '2026-01-11 11:01:29'),
(24, '@@@', 'ck719@gmail.com', '$2y$10$NPtQO5bMXdIdS7HQ/iP.DOGYIo5/GiZSHNDeF1NA/6E/6eL8xyYzK', '011-21611161', '2026-01-13 13:38:49', '2026-01-13 14:00:34'),
(25, 'Chen Ping An', 'chenpingan111@gmail.com', '$2y$10$qM9qnzVaHqXJD1oRGZkljeue9XBoZ208y5v2.0C6Qfo77Lfrtb5bq', '018-9117822', '2026-01-13 16:56:23', '2026-01-13 18:34:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders_detail`
--
ALTER TABLE `orders_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_detail_order` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_db`
--
ALTER TABLE `user_db`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `orders_detail`
--
ALTER TABLE `orders_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=700;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `user_db`
--
ALTER TABLE `user_db`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders_detail`
--
ALTER TABLE `orders_detail`
  ADD CONSTRAINT `fk_orders_detail_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
