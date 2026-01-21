-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2026 at 05:04 PM
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
(1, 'superadmin', 'superadmin@gmail.com', '$2y$12$fyerT6O5XC5d2gopGVhUhu9lJId.Xe5vOp71Te4hvgPwmQqNovVeO', 'super_admin', 'active', 0, '2026-01-19 13:19:48', '2025-12-03 16:48:52'),
(0, 'admin', 'admin@bakeryhouse.com', '$2y$12$A./qXhTYT/S88sgr6gMGkeD4mjIX35Ldq4jIwlcI2BoIQGCJg7c76', 'admin', 'active', 0, '2026-01-16 17:45:16', '2025-12-24 04:50:31');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(75, 26, 113, 1, '2026-01-15 07:54:15'),
(76, 26, 161, 1, '2026-01-15 07:54:15'),
(80, 23, 161, 1, '2026-01-15 07:55:12'),
(91, 17, 255, 1, '2026-01-15 07:59:25'),
(92, 17, 257, 1, '2026-01-15 07:59:25'),
(93, 17, 263, 1, '2026-01-15 07:59:25'),
(94, 21, 1, 1, '2026-01-15 08:03:19'),
(95, 21, 161, 1, '2026-01-15 08:03:19'),
(100, 27, 1, 5, '2026-01-15 08:17:09'),
(101, 15, 1, 1, '2026-01-15 08:37:22'),
(2296, 28, 113, 1, '2026-01-18 22:52:50'),
(2297, 28, 185, 5, '2026-01-18 22:52:50'),
(2298, 28, 1, 2, '2026-01-18 22:52:50'),
(2299, 29, 222, 1, '2026-01-18 22:54:01'),
(2300, 29, 23, 1, '2026-01-18 22:54:01'),
(2301, 29, 185, 34, '2026-01-18 22:54:01'),
(2325, 33, 1, 1, '2026-01-18 23:53:54'),
(2327, 34, 1, 1, '2026-01-18 23:54:47');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Cake', '2025-12-14 18:32:37'),
(2, 'Bread', '2025-12-14 18:32:37'),
(3, 'Pastry', '2025-12-14 18:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL COMMENT 'Unique message ID',
  `user_id` int(11) DEFAULT NULL COMMENT 'Reference to user_db id (NULL for guests)',
  `name` varchar(100) NOT NULL COMMENT 'Sender full name',
  `email` varchar(150) NOT NULL COMMENT 'Sender email address',
  `message` text NOT NULL COMMENT 'The actual message content',
  `status` enum('unread','read','replied') DEFAULT 'unread' COMMENT 'Message handling status',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp of submission'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `user_id`, `name`, `email`, `message`, `status`, `created_at`) VALUES
(1, 18, 'test', 'iplaygame317@gmail.com', 'good food', 'unread', '2025-12-31 04:37:39'),
(2, 18, 'test', 'iplaygame317@gmail.com', 'nicee service', 'unread', '2025-12-31 04:38:03'),
(3, 18, 'test', 'iplaygame317@gmail.com', 'ggood food', 'unread', '2025-12-31 06:15:49'),
(4, 18, 'test', 'iplaygame317@gmail.com', 'goood', 'unread', '2026-01-02 02:50:41'),
(5, 18, 'test', 'iplaygame317@gmail.com', 'goood', 'unread', '2026-01-02 02:56:44'),
(6, 18, 'test', 'iplaygame317@gmail.com', 'goood', 'unread', '2026-01-02 02:56:49'),
(7, NULL, 'test', 'test@gmail.com', 'good food', 'unread', '2026-01-02 03:07:05');

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
(12, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 516.00, 'pending', '2025-12-15 23:39:21', NULL, 'pending'),
(13, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 656.00, 'pending', '2025-12-15 23:43:27', NULL, 'pending'),
(14, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'pending', '2025-12-23 23:01:01', NULL, 'pending'),
(15, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 88.00, 'pending', '2025-12-23 23:06:30', NULL, 'pending'),
(16, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 78.00, 'pending', '2025-12-23 23:12:07', NULL, 'pending'),
(17, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'pending', '2025-12-30 03:27:30', NULL, 'pending'),
(18, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 516.00, 'pending', '2025-12-30 03:44:15', NULL, 'pending'),
(19, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 398.00, 'pending', '2025-12-30 04:15:15', NULL, 'pending'),
(20, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 300.00, 'pending', '2025-12-30 04:23:19', NULL, 'pending'),
(21, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 98.00, 'pending', '2026-01-03 22:49:42', NULL, 'pending'),
(22, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 14.20, 'pending', '2026-01-03 22:57:05', NULL, 'pending'),
(23, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 516.00, 'pending', '2026-01-05 09:39:00', NULL, 'pending'),
(24, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 356.00, 'pending', '2026-01-06 06:26:16', NULL, 'pending'),
(25, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 98.00, 'cancelled', '2026-01-06 07:58:00', 'fpx', 'failed'),
(26, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-06 08:01:25', 'eWallet', 'paid'),
(27, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 88.00, 'preparing', '2026-01-06 08:10:27', 'eWallet', 'paid'),
(28, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'pending', '2026-01-06 08:24:10', 'fpx', 'pending'),
(29, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 558.00, 'preparing', '2026-01-06 08:31:32', 'eWallet', 'paid'),
(30, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 8.90, 'preparing', '2026-01-06 09:12:40', 'eWallet', 'paid'),
(31, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-06 10:00:19', 'debitCard', 'paid'),
(32, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-06 11:18:35', 'debitCard', 'paid'),
(33, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 300.00, 'preparing', '2026-01-06 21:10:48', 'debitCard', 'paid'),
(34, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-06 21:15:01', 'eWallet', 'paid'),
(35, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 98.00, 'preparing', '2026-01-06 21:18:23', 'debitCard', 'paid'),
(36, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-06 21:22:35', 'eWallet', 'paid'),
(37, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 98.00, 'preparing', '2026-01-06 21:24:47', 'fpx', 'paid'),
(38, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 300.00, 'pending', '2026-01-06 21:25:56', 'eWallet', 'pending'),
(39, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 300.00, 'pending', '2026-01-06 21:46:16', 'eWallet', 'pending'),
(40, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 300.00, 'pending', '2026-01-07 02:59:03', 'debitCard', 'pending'),
(41, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 2532.00, 'preparing', '2026-01-07 03:09:53', 'debitCard', 'paid'),
(42, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 516.00, 'preparing', '2026-01-07 03:28:36', 'eWallet', 'paid'),
(43, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 98.00, 'pending', '2026-01-07 03:30:45', 'debitCard', 'pending'),
(44, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 356.00, 'pending', '2026-01-07 03:31:14', 'fpx', 'pending'),
(45, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 356.00, 'pending', '2026-01-07 03:32:15', 'eWallet', 'pending'),
(46, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 356.00, 'pending', '2026-01-07 03:36:29', 'eWallet', 'pending'),
(47, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 356.00, 'pending', '2026-01-07 03:36:55', 'eWallet', 'pending'),
(48, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 356.00, 'pending', '2026-01-07 03:37:38', 'fpx', 'pending'),
(49, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 356.00, 'pending', '2026-01-07 03:44:11', 'eWallet', 'pending'),
(50, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 614.00, 'preparing', '2026-01-07 03:48:06', 'eWallet', 'paid'),
(51, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 516.00, 'preparing', '2026-01-07 04:15:11', 'eWallet', 'paid'),
(52, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'pending', '2026-01-07 04:24:32', 'eWallet', 'pending'),
(53, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-07 04:29:36', 'eWallet', 'paid'),
(54, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'pending', '2026-01-07 04:30:47', NULL, 'pending'),
(55, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 1192.00, 'pending', '2026-01-07 07:58:40', NULL, 'pending'),
(56, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-11 02:42:52', 'eWallet', 'paid'),
(57, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 258.00, 'preparing', '2026-01-11 04:10:31', 'eWallet', 'paid'),
(58, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 98.00, 'pending', '2026-01-11 04:21:14', NULL, 'pending'),
(59, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 692.00, 'preparing', '2026-01-12 07:44:57', 'eWallet', 'paid'),
(60, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '+601121611161', '30,Jalan Setia 2/13，Taman Setia Indah\r\n30', 'Johor Bahru', '81100', 895.90, 'preparing', '2026-01-13 05:27:34', 'eWallet', 'paid'),
(61, 'ningyao', 'ningyao312@gmail.com', '0146700251', '17, Jalan Daya 9/15,Taman Daya', 'Bukit Beruang', '75400', 516.00, 'pending', '2026-01-13 10:23:26', 'eWallet', 'pending'),
(62, 'ningyao', 'ningyao312@gmail.com', '0146700251', '30, Jalan Setia 2/13,Taman Setia Indah', 'Bandar Melaka', '75400', 521.00, 'pending', '2026-01-13 10:32:01', 'eWallet', 'pending'),
(63, 'Chen Ping An', 'chenpingan111@gmail.com', '018-9117822', '26, Jalan Daya 1/15,Taman Daya', 'Bandar Melaka', '75400', 1871.20, 'pending', '2026-01-13 11:24:02', 'debitCard', 'pending'),
(64, 'Chen Ping An', 'chenpingan111@gmail.com', '018-9117822', '26, Jalan Daya 1/15,Taman Daya', 'Bandar Melaka', '75400', 1009.00, 'pending', '2026-01-13 12:10:45', 'tng', 'pending'),
(65, 'Chen Ping An', 'chenpingan111@gmail.com', '018-9117822', '26, Jalan Daya 1/15,Taman Daya', 'Bandar Melaka', '75400', 1009.00, 'pending', '2026-01-14 07:31:24', 'debitCard', 'pending'),
(66, 'Chen Ping An', 'chenpingan111@gmail.com', '018-9117822', '26, Jalan Daya 1/15,Taman Daya', 'Bandar Melaka', '75400', 1009.00, 'pending', '2026-01-14 07:31:58', 'tng', 'pending'),
(67, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30, Jalan Setia 2/13, Taman Setia Indah', 'Bandar Melaka', '75400', 1009.00, 'preparing', '2026-01-14 11:19:31', 'tng', 'paid'),
(68, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30, Jalan Setia 2/13, Taman Setia Indah', 'Bandar Melaka', '75400', 563.00, 'preparing', '2026-01-14 11:25:11', 'fpx', 'paid'),
(69, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30, Jalan Setia 2/13, Taman Setia Indah', 'Bandar Melaka', '75400', 263.00, 'preparing', '2026-01-14 11:30:28', 'tng', 'paid'),
(70, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30, Jalan Setia 2/13, Taman Setia Indah', 'Bandar Melaka', '75400', 863.00, 'preparing', '2026-01-14 11:55:30', 'tng', 'paid'),
(71, 'junjie', 'junjie312@gmail.com', '', '3,jalan lotus 3/33', 'Ayer Keroh', '73100', 661.00, 'preparing', '2026-01-15 02:47:10', 'tng', 'paid'),
(72, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30, Jalan Setia 2/13, Taman Setia Indah', 'Bandar Melaka', '75400', 191.00, 'preparing', '2026-01-15 03:34:45', 'tng', 'paid'),
(73, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30, Jalan Setia 2/13, Taman Setia Indah', 'Bandar Melaka', '75400', 563.00, 'preparing', '2026-01-15 03:51:25', 'fpx', 'paid'),
(74, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30, Jalan Setia 2/13, Taman Setia Indah', 'Bandar Melaka', '75400', 857.00, 'preparing', '2026-01-15 03:51:46', 'tng', 'paid'),
(75, 'tangsan', 'tangsan111@gmail.com', '', '', '', '', 521.00, 'preparing', '2026-01-15 06:39:13', 'tng', 'paid'),
(76, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30, Jalan Setia 2/13, Taman Setia Indah', 'Bandar Melaka', '75400', 433.00, 'preparing', '2026-01-15 06:40:07', 'tng', 'paid'),
(77, 'lllll', 'lllll719@gmail.com', '', '', '', '', 103.00, 'cancelled', '2026-01-15 08:09:25', 'tng', 'failed'),
(78, 'Lim Sin Yi', 'sinyi22222222@gmail.com', '', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1693.00, 'pending', '2026-01-15 09:49:39', 'debitCard', 'pending'),
(79, 'Lim Sin Yi', 'sinyi22222222@gmail.com', '', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1693.00, 'pending', '2026-01-15 09:52:28', 'debitCard', 'pending'),
(80, 'Lim Sin Yi', 'sinyi22222222@gmail.com', '', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1693.00, 'pending', '2026-01-15 09:56:20', 'debitCard', 'pending'),
(81, 'Lim Sin Yi', 'sinyi22222222@gmail.com', '', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1693.00, 'pending', '2026-01-15 10:03:40', 'debitCard', 'pending'),
(82, 'Lim Sin Yi', 'sinyi22222222@gmail.com', '', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1693.00, 'pending', '2026-01-15 10:09:42', 'debitCard', 'pending'),
(83, 'Lim Sin Yi', 'sinyi22222222@gmail.com', '', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1693.00, 'pending', '2026-01-15 10:10:45', 'debitCard', 'pending'),
(84, 'Lim Sin Yi', 'sinyi22222222@gmail.com', '', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1693.00, 'pending', '2026-01-15 10:16:24', 'debitCard', 'pending'),
(85, 'Lim Sin Yi', 'sinyi22222222@gmail.com', '', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1693.00, 'preparing', '2026-01-15 10:19:29', 'debitCard', 'paid'),
(86, 'Lim Sin Yi', 'sinyi22222222@gmail.com', '01120788149', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1693.00, 'preparing', '2026-01-15 10:51:07', 'tng', 'paid'),
(87, 'Lim Sin Yi', 'sinyi22222222@gmail.com', '01120788149', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1693.00, 'preparing', '2026-01-15 10:51:44', 'debitCard', 'paid'),
(88, 'Lim Sin Yi', 'sinyi22222222@gmail.com', '01120788149', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1693.00, 'preparing', '2026-01-15 11:46:59', 'debitCard', 'paid'),
(89, 'Lim Sin Yi', 'sinyi22222222@gmail.com', '01120788149', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 361.00, 'preparing', '2026-01-15 11:49:20', 'debitCard', 'paid'),
(90, 'Lim Sin Yi', 'sinyi22222222@gmail.com', '01120788149', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 361.00, 'preparing', '2026-01-15 11:53:27', 'debitCard', 'paid'),
(91, 'Lim Sin Yi', 'sinyi22222222@gmail.com', '01120788149', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 361.00, 'preparing', '2026-01-15 11:58:24', 'debitCard', 'paid'),
(92, 'Lim Sin Yi', 'sinyi22222222@gmail.com', '01120788149', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 103.00, 'preparing', '2026-01-15 12:08:39', 'debitCard', 'paid'),
(93, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1463.00, 'pending', '2026-01-16 02:36:01', 'debitCard', 'pending'),
(94, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1463.00, 'pending', '2026-01-16 02:42:38', 'tng', 'pending'),
(95, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1463.00, 'pending', '2026-01-16 02:42:53', 'fpx', 'pending'),
(96, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1463.00, 'pending', '2026-01-16 02:43:00', 'tng', 'pending'),
(97, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1463.00, 'pending', '2026-01-16 02:43:08', 'fpx', 'pending'),
(98, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1463.00, 'pending', '2026-01-16 02:44:35', 'tng', 'pending'),
(99, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1463.00, 'preparing', '2026-01-16 02:48:47', 'tng', 'paid'),
(100, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 103.00, 'cancelled', '2026-01-16 02:50:56', 'fpx', 'failed'),
(101, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'cancelled', '2026-01-16 02:51:46', 'tng', 'failed'),
(102, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'cancelled', '2026-01-16 02:53:06', 'debitCard', 'failed'),
(103, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 145.00, 'cancelled', '2026-01-16 02:59:17', 'tng', 'failed'),
(104, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 145.00, 'cancelled', '2026-01-16 03:03:45', 'tng', 'failed'),
(105, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 145.00, 'cancelled', '2026-01-16 03:04:30', 'fpx', 'failed'),
(106, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 145.00, 'cancelled', '2026-01-16 03:10:57', 'debitCard', 'failed'),
(107, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'cancelled', '2026-01-16 03:20:02', 'debitCard', 'failed'),
(108, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'cancelled', '2026-01-16 03:27:27', 'debitCard', 'failed'),
(109, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'cancelled', '2026-01-16 03:27:57', 'debitCard', 'failed'),
(110, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'preparing', '2026-01-16 03:34:17', 'tng', 'paid'),
(111, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 305.00, 'pending', '2026-01-16 03:36:59', 'debitCard', 'pending'),
(112, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 305.00, 'pending', '2026-01-16 03:37:39', 'tng', 'pending'),
(113, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 305.00, 'pending', '2026-01-16 03:37:48', 'tng', 'pending'),
(114, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 305.00, 'pending', '2026-01-16 03:38:10', 'debitCard', 'pending'),
(115, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 305.00, 'preparing', '2026-01-16 03:45:00', 'debitCard', 'paid'),
(116, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 305.00, 'cancelled', '2026-01-16 03:46:26', 'tng', 'failed'),
(117, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 305.00, 'preparing', '2026-01-16 03:46:34', 'tng', 'paid'),
(118, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'preparing', '2026-01-16 03:46:51', 'fpx', 'paid'),
(119, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 225.00, 'preparing', '2026-01-16 06:53:07', 'tng', 'paid'),
(120, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'pending', '2026-01-16 07:04:16', 'debitCard', 'pending'),
(121, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'preparing', '2026-01-16 07:04:57', 'tng', 'paid'),
(122, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'pending', '2026-01-16 07:13:03', 'debitCard', 'pending'),
(123, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'pending', '2026-01-16 07:19:27', 'fpx', 'pending'),
(124, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'pending', '2026-01-16 07:19:51', 'tng', 'pending'),
(125, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'pending', '2026-01-16 07:20:00', 'debitCard', 'pending'),
(126, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'pending', '2026-01-16 07:20:19', 'tng', 'pending'),
(127, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'pending', '2026-01-16 07:20:23', 'fpx', 'pending'),
(128, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'pending', '2026-01-16 07:24:52', 'fpx', 'pending'),
(129, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'pending', '2026-01-16 07:25:19', 'tng', 'pending'),
(130, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'cancelled', '2026-01-16 07:25:29', 'fpx', 'failed'),
(131, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'cancelled', '2026-01-16 07:26:32', 'debitCard', 'failed'),
(132, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'pending', '2026-01-16 07:27:21', 'tng', 'pending'),
(133, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 5.00, 'cancelled', '2026-01-16 07:40:05', 'tng', 'failed'),
(134, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 305.00, 'cancelled', '2026-01-16 08:25:34', 'tng', 'failed'),
(135, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 305.00, 'cancelled', '2026-01-16 08:25:42', 'fpx', 'failed'),
(136, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 305.00, 'pending', '2026-01-16 08:35:27', 'tng', 'pending'),
(137, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 305.00, 'pending', '2026-01-16 10:06:47', 'tng', 'pending'),
(138, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 838.00, 'cancelled', '2026-01-17 00:14:00', 'tng', 'failed'),
(139, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1010.00, 'pending', '2026-01-17 01:23:47', 'debitCard', 'pending'),
(140, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1010.00, 'pending', '2026-01-17 01:23:57', 'tng', 'pending'),
(141, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 1010.00, 'preparing', '2026-01-17 01:24:05', 'debitCard', 'paid'),
(142, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 263.00, 'pending', '2026-01-17 01:29:03', 'tng', 'pending'),
(143, 'jacky', 'jacky902@gmail.com', '', '49, Jalan Setia 9/12, Taman Setia Indah', 'Bukit Beruang', '75450', 305.00, 'preparing', '2026-01-17 01:35:38', 'tng', 'paid'),
(144, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 263.00, 'pending', '2026-01-17 01:59:56', 'debitCard', 'pending'),
(145, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 545.00, 'preparing', '2026-01-17 02:01:24', 'debitCard', 'paid'),
(146, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 633.00, 'delivered', '2026-01-17 02:04:37', 'tng', 'paid'),
(147, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 961.00, 'preparing', '2026-01-17 04:10:21', 'debitCard', 'paid'),
(148, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 29.70, 'preparing', '2026-01-17 04:13:25', 'tng', 'paid'),
(149, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 913.00, 'preparing', '2026-01-17 09:19:40', 'debitCard', 'paid'),
(150, 'jyyyy', 'jyyyy777@gmail.com', '', '88,Jalan Setia Indah 9/13,Taman Setia Indah', 'Ayer Keroh', '75450', 745.00, 'preparing', '2026-01-17 13:56:41', 'tng', 'paid'),
(151, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 821.00, 'preparing', '2026-01-18 06:47:56', 'tng', 'paid'),
(152, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 821.00, 'preparing', '2026-01-18 06:48:26', 'fpx', 'paid'),
(153, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 913.00, 'preparing', '2026-01-18 06:48:49', 'tng', 'paid'),
(154, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 913.00, 'preparing', '2026-01-18 06:49:35', 'tng', 'paid'),
(155, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 403.00, 'preparing', '2026-01-18 06:50:53', 'tng', 'paid'),
(156, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 4568.40, 'preparing', '2026-01-18 11:57:23', 'tng', 'paid'),
(157, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 263.00, 'preparing', '2026-01-18 19:12:29', 'tng', 'paid'),
(158, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 361.00, 'preparing', '2026-01-18 20:12:35', 'tng', 'paid'),
(159, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 263.00, 'preparing', '2026-01-18 20:24:48', 'tng', 'paid'),
(160, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 29485.00, 'preparing', '2026-01-18 20:28:31', 'tng', 'paid'),
(161, 'WONG CHUN KIT', 'chunkitwong719@gmail.com', '01121611161', '30,Jalan Setia 2/13,Taman Setia Indah', 'Ayer Keroh', '75450', 855.00, 'preparing', '2026-01-18 22:45:58', 'tng', 'paid'),
(162, 'rongrong', 'rongrong614@gmail.com', '', '', '', '', 576.20, 'pending', '2026-01-18 23:16:28', 'tng', 'pending'),
(163, 'rongrong', 'rongrong614@gmail.com', '', '', '', '', 576.20, 'pending', '2026-01-18 23:18:37', 'tng', 'pending'),
(164, 'rongrong', 'rongrong614@gmail.com', '01121611161', '30,Jalan Setia 6/13,Taman Setia Indah', 'Ayer Keroh', '75450', 576.20, 'delivered', '2026-01-18 23:35:01', 'tng', 'paid'),
(165, 'rongrong', 'rongrong614@gmail.com', '01121611161', '45,Jalan Setia 9/17,Taman Setia Indah', 'Bandar Melaka', '75000', 501.00, 'delivered', '2026-01-18 23:41:11', 'tng', 'paid'),
(166, 'rongrong', 'rongrong614@gmail.com', '01121611161', '30,Jalan Setia 6/13,Taman Setia Indah', 'Ayer Keroh', '75450', 103.00, 'delivered', '2026-01-18 23:52:35', 'debitCard', 'paid');

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
(1, 12, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2025-12-15 23:39:21'),
(2, 13, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2025-12-15 23:43:27'),
(3, 13, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2025-12-15 23:43:27'),
(4, 13, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2025-12-15 23:43:27'),
(5, 14, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2025-12-23 23:01:01'),
(6, 15, 73, 'BLUEBERRY CHEESE', 88.00, 1, 88.00, '2025-12-23 23:06:30'),
(7, 16, 24, 'BEAR CANDLE', 78.00, 1, 78.00, '2025-12-23 23:12:07'),
(8, 17, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2025-12-30 03:27:30'),
(9, 18, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2025-12-30 03:44:15'),
(10, 19, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2025-12-30 04:15:15'),
(11, 19, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2025-12-30 04:15:15'),
(12, 20, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2025-12-30 04:23:19'),
(13, 21, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-03 22:49:42'),
(14, 22, 260, 'Almond Mascarpone Danish', 14.20, 1, 14.20, '2026-01-03 22:57:05'),
(15, 23, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-05 09:39:00'),
(16, 24, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-06 06:26:16'),
(17, 24, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-06 06:26:16'),
(18, 25, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-06 07:58:00'),
(19, 26, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-06 08:01:25'),
(20, 27, 93, 'PINK CELEBRATION TIER', 88.00, 1, 88.00, '2026-01-06 08:10:27'),
(21, 28, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-06 08:24:10'),
(22, 29, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-06 08:31:32'),
(23, 29, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-06 08:31:32'),
(24, 30, 276, 'Double Chocolate Chip Cookies', 8.90, 1, 8.90, '2026-01-06 09:12:40'),
(25, 31, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-06 10:00:19'),
(26, 32, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-06 11:18:35'),
(27, 33, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-06 21:10:48'),
(28, 34, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-06 21:15:01'),
(29, 35, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-06 21:18:23'),
(30, 36, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-06 21:22:35'),
(31, 37, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-06 21:24:47'),
(32, 38, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-06 21:25:56'),
(33, 39, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-06 21:46:16'),
(34, 40, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-07 02:59:03'),
(35, 41, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 5, 1500.00, '2026-01-07 03:09:53'),
(36, 41, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 4, 1032.00, '2026-01-07 03:09:53'),
(37, 42, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-07 03:28:36'),
(38, 43, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 03:30:45'),
(39, 44, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 03:31:14'),
(40, 44, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 03:31:14'),
(41, 45, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 03:32:15'),
(42, 45, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 03:32:15'),
(43, 46, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 03:36:29'),
(44, 46, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 03:36:29'),
(45, 47, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 03:36:55'),
(46, 47, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 03:36:55'),
(47, 48, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 03:37:38'),
(48, 48, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 03:37:38'),
(49, 49, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 03:44:11'),
(50, 49, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 03:44:11'),
(51, 50, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 03:48:06'),
(52, 50, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-07 03:48:06'),
(53, 51, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-07 04:15:11'),
(54, 52, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 04:24:32'),
(55, 53, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 04:29:36'),
(56, 54, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-07 04:30:47'),
(57, 55, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 3, 774.00, '2026-01-07 07:58:40'),
(58, 55, 146, 'Baby Elephant Forest Fondant Cake', 320.00, 1, 320.00, '2026-01-07 07:58:40'),
(59, 55, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-07 07:58:40'),
(60, 56, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-11 02:42:52'),
(61, 57, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-11 04:10:31'),
(62, 58, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-11 04:21:14'),
(63, 59, 1, 'A LITTLE SWEET', 98.00, 4, 392.00, '2026-01-12 07:44:57'),
(64, 59, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-12 07:44:57'),
(65, 60, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 3, 774.00, '2026-01-13 05:27:34'),
(66, 60, 103, 'Fruit Tart', 8.00, 1, 8.00, '2026-01-13 05:27:34'),
(67, 60, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-13 05:27:34'),
(68, 60, 240, 'Alsatian Kugelhopf Sweet Bread', 15.90, 1, 15.90, '2026-01-13 05:27:34'),
(69, 61, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-13 10:23:26'),
(70, 62, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-13 10:32:01'),
(71, 63, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-13 11:24:02'),
(72, 63, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-13 11:24:02'),
(73, 63, 146, 'Baby Elephant Forest Fondant Cake', 320.00, 1, 320.00, '2026-01-13 11:24:02'),
(74, 63, 23, 'BABY PENGUINSSS', 140.00, 2, 280.00, '2026-01-13 11:24:02'),
(75, 63, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-13 11:24:02'),
(76, 63, 111, 'Durian Father\'s Day Cake', 220.00, 1, 220.00, '2026-01-13 11:24:02'),
(77, 63, 240, 'Alsatian Kugelhopf Sweet Bread', 15.90, 1, 15.90, '2026-01-13 11:24:02'),
(78, 63, 222, 'Basic Sourdough Boule', 11.50, 1, 11.50, '2026-01-13 11:24:02'),
(79, 63, 246, 'Braided Sweet Yeast Bread', 14.80, 1, 14.80, '2026-01-13 11:24:02'),
(80, 63, 126, 'Oreo Monster Animal Cake', 138.00, 1, 138.00, '2026-01-13 11:24:02'),
(81, 63, 127, 'Pastel Unicorn Animal Cake', 138.00, 1, 138.00, '2026-01-13 11:24:02'),
(82, 64, 126, 'Oreo Monster Animal Cake', 138.00, 1, 138.00, '2026-01-13 12:10:45'),
(83, 64, 127, 'Pastel Unicorn Animal Cake', 138.00, 1, 138.00, '2026-01-13 12:10:45'),
(84, 64, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-13 12:10:45'),
(85, 64, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-13 12:10:45'),
(86, 64, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-13 12:10:45'),
(87, 65, 126, 'Oreo Monster Animal Cake', 138.00, 1, 138.00, '2026-01-14 07:31:24'),
(88, 65, 127, 'Pastel Unicorn Animal Cake', 138.00, 1, 138.00, '2026-01-14 07:31:24'),
(89, 65, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-14 07:31:24'),
(90, 65, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-14 07:31:24'),
(91, 65, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-14 07:31:24'),
(92, 66, 126, 'Oreo Monster Animal Cake', 138.00, 1, 138.00, '2026-01-14 07:31:58'),
(93, 66, 127, 'Pastel Unicorn Animal Cake', 138.00, 1, 138.00, '2026-01-14 07:31:58'),
(94, 66, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-14 07:31:58'),
(95, 66, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-14 07:31:58'),
(96, 66, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-14 07:31:58'),
(97, 67, 126, 'Oreo Monster Animal Cake', 138.00, 1, 138.00, '2026-01-14 11:19:31'),
(98, 67, 127, 'Pastel Unicorn Animal Cake', 138.00, 1, 138.00, '2026-01-14 11:19:31'),
(99, 67, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-14 11:19:31'),
(100, 67, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-14 11:19:31'),
(101, 67, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-14 11:19:31'),
(102, 68, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-14 11:25:11'),
(103, 68, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-14 11:25:11'),
(104, 69, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-14 11:30:28'),
(105, 70, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-14 11:55:30'),
(106, 70, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-14 11:55:30'),
(107, 70, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-14 11:55:30'),
(108, 71, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-15 02:47:10'),
(109, 71, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-15 02:47:10'),
(110, 71, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-15 02:47:10'),
(111, 72, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-15 03:34:45'),
(112, 72, 84, 'Black Forest', 88.00, 1, 88.00, '2026-01-15 03:34:45'),
(113, 73, 1, 'A LITTLE SWEET', 98.00, 3, 294.00, '2026-01-15 03:51:25'),
(114, 73, 84, 'Black Forest', 88.00, 3, 264.00, '2026-01-15 03:51:25'),
(115, 74, 1, 'A LITTLE SWEET', 98.00, 6, 588.00, '2026-01-15 03:51:46'),
(116, 74, 84, 'Black Forest', 88.00, 3, 264.00, '2026-01-15 03:51:46'),
(117, 75, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-15 06:39:13'),
(118, 76, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-15 06:40:07'),
(119, 76, 30, 'CHOCO ERAL DRIP', 98.00, 1, 98.00, '2026-01-15 06:40:07'),
(120, 77, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-15 08:09:25'),
(121, 78, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-15 09:49:39'),
(122, 78, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-15 09:49:39'),
(123, 78, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-15 09:49:39'),
(124, 78, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, 220.00, '2026-01-15 09:49:39'),
(125, 78, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-15 09:49:39'),
(126, 78, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-15 09:49:39'),
(127, 78, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-15 09:49:39'),
(128, 79, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-15 09:52:28'),
(129, 79, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-15 09:52:28'),
(130, 79, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-15 09:52:28'),
(131, 79, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, 220.00, '2026-01-15 09:52:28'),
(132, 79, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-15 09:52:28'),
(133, 79, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-15 09:52:28'),
(134, 79, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-15 09:52:28'),
(135, 80, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-15 09:56:20'),
(136, 80, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-15 09:56:20'),
(137, 80, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-15 09:56:20'),
(138, 80, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, 220.00, '2026-01-15 09:56:20'),
(139, 80, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-15 09:56:20'),
(140, 80, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-15 09:56:20'),
(141, 80, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-15 09:56:20'),
(142, 81, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-15 10:03:40'),
(143, 81, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-15 10:03:40'),
(144, 81, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-15 10:03:40'),
(145, 81, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, 220.00, '2026-01-15 10:03:40'),
(146, 81, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-15 10:03:40'),
(147, 81, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-15 10:03:40'),
(148, 81, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-15 10:03:40'),
(149, 82, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-15 10:09:42'),
(150, 82, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-15 10:09:42'),
(151, 82, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-15 10:09:42'),
(152, 82, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, 220.00, '2026-01-15 10:09:42'),
(153, 82, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-15 10:09:42'),
(154, 82, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-15 10:09:42'),
(155, 82, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-15 10:09:42'),
(156, 83, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-15 10:10:45'),
(157, 83, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-15 10:10:45'),
(158, 83, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-15 10:10:45'),
(159, 83, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, 220.00, '2026-01-15 10:10:45'),
(160, 83, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-15 10:10:45'),
(161, 83, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-15 10:10:45'),
(162, 83, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-15 10:10:45'),
(163, 84, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-15 10:16:24'),
(164, 84, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-15 10:16:24'),
(165, 84, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-15 10:16:24'),
(166, 84, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, 220.00, '2026-01-15 10:16:24'),
(167, 84, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-15 10:16:24'),
(168, 84, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-15 10:16:24'),
(169, 84, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-15 10:16:24'),
(170, 85, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-15 10:19:29'),
(171, 85, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-15 10:19:29'),
(172, 85, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-15 10:19:29'),
(173, 85, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, 220.00, '2026-01-15 10:19:29'),
(174, 85, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-15 10:19:29'),
(175, 85, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-15 10:19:29'),
(176, 85, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-15 10:19:29'),
(177, 86, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-15 10:51:07'),
(178, 86, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-15 10:51:07'),
(179, 86, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-15 10:51:07'),
(180, 86, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, 220.00, '2026-01-15 10:51:07'),
(181, 86, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-15 10:51:07'),
(182, 86, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-15 10:51:07'),
(183, 86, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-15 10:51:07'),
(184, 87, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-15 10:51:44'),
(185, 87, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-15 10:51:44'),
(186, 87, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-15 10:51:44'),
(187, 87, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, 220.00, '2026-01-15 10:51:44'),
(188, 87, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-15 10:51:44'),
(189, 87, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-15 10:51:44'),
(190, 87, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-15 10:51:44'),
(191, 88, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-15 11:46:59'),
(192, 88, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-15 11:46:59'),
(193, 88, 138, 'Baby Airplane Fondant Cake', 330.00, 1, 330.00, '2026-01-15 11:46:59'),
(194, 88, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, 220.00, '2026-01-15 11:46:59'),
(195, 88, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-15 11:46:59'),
(196, 88, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-15 11:46:59'),
(197, 88, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-15 11:46:59'),
(198, 89, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-15 11:49:20'),
(199, 89, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-15 11:49:20'),
(200, 90, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-15 11:53:27'),
(201, 90, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-15 11:53:27'),
(202, 91, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-15 11:58:24'),
(203, 91, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-15 11:58:24'),
(204, 92, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-15 12:08:39'),
(205, 93, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-16 02:36:01'),
(206, 93, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 4, 1200.00, '2026-01-16 02:36:01'),
(207, 94, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-16 02:42:38'),
(208, 94, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 4, 1200.00, '2026-01-16 02:42:38'),
(209, 95, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-16 02:42:53'),
(210, 95, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 4, 1200.00, '2026-01-16 02:42:53'),
(211, 96, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-16 02:43:00'),
(212, 96, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 4, 1200.00, '2026-01-16 02:43:00'),
(213, 97, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-16 02:43:08'),
(214, 97, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 4, 1200.00, '2026-01-16 02:43:08'),
(215, 98, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-16 02:44:35'),
(216, 98, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 4, 1200.00, '2026-01-16 02:44:35'),
(217, 99, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-16 02:48:47'),
(218, 99, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 4, 1200.00, '2026-01-16 02:48:47'),
(219, 100, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-16 02:50:56'),
(220, 103, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-16 02:59:17'),
(221, 104, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-16 03:03:45'),
(222, 105, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-16 03:04:30'),
(223, 106, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-16 03:10:57'),
(224, 111, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-16 03:36:59'),
(225, 112, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-16 03:37:39'),
(226, 113, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-16 03:37:48'),
(227, 114, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-16 03:38:10'),
(228, 115, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-16 03:45:00'),
(229, 116, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-16 03:46:26'),
(230, 117, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-16 03:46:34'),
(231, 119, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, 220.00, '2026-01-16 06:53:07'),
(232, 134, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-16 08:25:34'),
(233, 135, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-16 08:25:42'),
(234, 136, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-16 08:35:27'),
(235, 137, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-16 10:06:47'),
(236, 138, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-17 00:14:00'),
(237, 138, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-17 00:14:00'),
(238, 138, 94, 'ROSE HEART STRAWBERRY CAKE', 72.00, 1, 72.00, '2026-01-17 00:14:00'),
(239, 138, 96, 'STRAWBERRY DELIGHT ROUND CAKE', 65.00, 1, 65.00, '2026-01-17 00:14:00'),
(240, 138, 98, 'STRAWBERRY FANTASY DRIP CAKE', 68.00, 1, 68.00, '2026-01-17 00:14:00'),
(241, 138, 100, 'STRAWBERRY LOVE MOM CAKE', 70.00, 1, 70.00, '2026-01-17 00:14:00'),
(242, 139, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-17 01:23:47'),
(243, 139, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, 220.00, '2026-01-17 01:23:47'),
(244, 139, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-17 01:23:47'),
(245, 139, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-17 01:23:47'),
(246, 139, 94, 'ROSE HEART STRAWBERRY CAKE', 72.00, 1, 72.00, '2026-01-17 01:23:47'),
(247, 139, 96, 'STRAWBERRY DELIGHT ROUND CAKE', 65.00, 1, 65.00, '2026-01-17 01:23:47'),
(248, 139, 98, 'STRAWBERRY FANTASY DRIP CAKE', 68.00, 1, 68.00, '2026-01-17 01:23:47'),
(249, 140, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-17 01:23:57'),
(250, 140, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, 220.00, '2026-01-17 01:23:57'),
(251, 140, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-17 01:23:57'),
(252, 140, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-17 01:23:57'),
(253, 140, 94, 'ROSE HEART STRAWBERRY CAKE', 72.00, 1, 72.00, '2026-01-17 01:23:57'),
(254, 140, 96, 'STRAWBERRY DELIGHT ROUND CAKE', 65.00, 1, 65.00, '2026-01-17 01:23:57'),
(255, 140, 98, 'STRAWBERRY FANTASY DRIP CAKE', 68.00, 1, 68.00, '2026-01-17 01:23:57'),
(256, 141, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-17 01:24:05'),
(257, 141, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, 220.00, '2026-01-17 01:24:05'),
(258, 141, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-17 01:24:05'),
(259, 141, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-17 01:24:05'),
(260, 141, 94, 'ROSE HEART STRAWBERRY CAKE', 72.00, 1, 72.00, '2026-01-17 01:24:05'),
(261, 141, 96, 'STRAWBERRY DELIGHT ROUND CAKE', 65.00, 1, 65.00, '2026-01-17 01:24:05'),
(262, 141, 98, 'STRAWBERRY FANTASY DRIP CAKE', 68.00, 1, 68.00, '2026-01-17 01:24:05'),
(263, 142, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-17 01:29:03'),
(264, 143, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-17 01:35:38'),
(265, 144, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-17 01:59:56'),
(266, 145, 111, 'Durian Father\'s Day Cake', 220.00, 1, 220.00, '2026-01-17 02:01:24'),
(267, 145, 146, 'Baby Elephant Forest Fondant Cake', 320.00, 1, 320.00, '2026-01-17 02:01:24'),
(268, 146, 171, 'Baby Rainbow Friends Smash Fresh Cream Cake', 228.00, 1, 228.00, '2026-01-17 02:04:37'),
(269, 146, 167, 'Butterfly Princess Smash Fresh Cream Cake', 400.00, 1, 400.00, '2026-01-17 02:04:37'),
(270, 147, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-17 04:10:21'),
(271, 147, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 2, 600.00, '2026-01-17 04:10:21'),
(272, 147, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-17 04:10:21'),
(273, 148, 270, 'Chocolate Puff Pastry Roll', 11.90, 1, 11.90, '2026-01-17 04:13:25'),
(274, 148, 254, 'Chocolate Almond Croissant', 12.80, 1, 12.80, '2026-01-17 04:13:25'),
(275, 149, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-17 09:19:40'),
(276, 149, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-17 09:19:40'),
(277, 149, 171, 'Baby Rainbow Friends Smash Fresh Cream Cake', 228.00, 1, 228.00, '2026-01-17 09:19:40'),
(278, 149, 167, 'Butterfly Princess Smash Fresh Cream Cake', 400.00, 1, 400.00, '2026-01-17 09:19:40'),
(279, 150, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-17 13:56:41'),
(280, 150, 139, 'Animal Friends Fondant Cake', 300.00, 1, 300.00, '2026-01-17 13:56:41'),
(281, 150, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-17 13:56:41'),
(282, 151, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-18 06:47:56'),
(283, 151, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-18 06:47:56'),
(284, 152, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-18 06:48:26'),
(285, 152, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-18 06:48:26'),
(286, 153, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-18 06:48:49'),
(287, 153, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-18 06:48:49'),
(288, 153, 171, 'Baby Rainbow Friends Smash Fresh Cream Cake', 228.00, 1, 228.00, '2026-01-18 06:48:49'),
(289, 153, 167, 'Butterfly Princess Smash Fresh Cream Cake', 400.00, 1, 400.00, '2026-01-18 06:48:49'),
(290, 154, 23, 'BABY PENGUINSSS', 140.00, 1, 140.00, '2026-01-18 06:49:35'),
(291, 154, 2, 'BABY PANDAA', 140.00, 1, 140.00, '2026-01-18 06:49:35'),
(292, 154, 171, 'Baby Rainbow Friends Smash Fresh Cream Cake', 228.00, 1, 228.00, '2026-01-18 06:49:35'),
(293, 154, 167, 'Butterfly Princess Smash Fresh Cream Cake', 400.00, 1, 400.00, '2026-01-18 06:49:35'),
(294, 155, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-18 06:50:53'),
(295, 155, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-18 06:50:53'),
(296, 156, 233, 'Crusty Artisan Bread', 12.50, 1, 12.50, '2026-01-18 11:57:23'),
(297, 156, 23, 'BABY PENGUINSSS', 140.00, 2, 280.00, '2026-01-18 11:57:23'),
(298, 156, 106, 'HOLI SPECIAL VANILLA', 75.00, 3, 225.00, '2026-01-18 11:57:23'),
(299, 156, 1, 'A LITTLE SWEET', 98.00, 3, 294.00, '2026-01-18 11:57:23'),
(300, 156, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 4, 1200.00, '2026-01-18 11:57:23'),
(301, 156, 139, 'Animal Friends Fondant Cake', 300.00, 3, 900.00, '2026-01-18 11:57:23'),
(302, 156, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 4, 1032.00, '2026-01-18 11:57:23'),
(303, 156, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 2, 440.00, '2026-01-18 11:57:23'),
(304, 156, 104, 'FLORAL VANILLA CREAM', 70.00, 2, 140.00, '2026-01-18 11:57:23'),
(305, 156, 115, 'Mini Purple Cat Strawberry Cake', 39.90, 1, 39.90, '2026-01-18 11:57:23'),
(306, 157, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-18 19:12:29'),
(307, 158, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-18 20:12:35'),
(308, 158, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-18 20:12:35'),
(309, 159, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-18 20:24:48'),
(310, 160, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 30, 7740.00, '2026-01-18 20:28:31'),
(311, 160, 1, 'A LITTLE SWEET', 98.00, 50, 4900.00, '2026-01-18 20:28:31'),
(312, 160, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 40, 12000.00, '2026-01-18 20:28:31'),
(313, 160, 156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 22, 4840.00, '2026-01-18 20:28:31'),
(314, 161, 113, 'Durian Lover Mini Cake', 138.00, 1, 138.00, '2026-01-18 22:45:58'),
(315, 161, 1, 'A LITTLE SWEET', 98.00, 2, 196.00, '2026-01-18 22:45:58'),
(316, 161, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 2, 516.00, '2026-01-18 22:45:58'),
(317, 162, 264, 'Cherry Cream Cheese Danish', 13.20, 1, 13.20, '2026-01-18 23:16:28'),
(318, 162, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-18 23:16:28'),
(319, 162, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-18 23:16:28'),
(320, 163, 264, 'Cherry Cream Cheese Danish', 13.20, 1, 13.20, '2026-01-18 23:18:37'),
(321, 163, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-18 23:18:37'),
(322, 163, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-18 23:18:37'),
(323, 164, 264, 'Cherry Cream Cheese Danish', 13.20, 1, 13.20, '2026-01-18 23:35:01'),
(324, 164, 185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, 258.00, '2026-01-18 23:35:01'),
(325, 164, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-18 23:35:01'),
(326, 165, 1, 'A LITTLE SWEET', 98.00, 2, 196.00, '2026-01-18 23:41:11'),
(327, 165, 161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, 300.00, '2026-01-18 23:41:11'),
(328, 166, 1, 'A LITTLE SWEET', 98.00, 1, 98.00, '2026-01-18 23:52:35');

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
(3, 'iplaygame317@gmail.com', '59f14842da4433c4cac270db411f0de715982179bdd8e38a5d761bf190d6fafb', '2025-12-15 15:08:20'),
(0, 'shanelim123@gmail.com', '580054', '2026-01-13 21:25:28');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`subcategory`)),
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `full_description` text DEFAULT NULL,
  `ingredients` text DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT 0.0,
  `review_count` int(11) DEFAULT 0,
  `sold_count` int(11) DEFAULT 0,
  `size_info` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category_id`, `subcategory`, `stock`, `description`, `full_description`, `ingredients`, `size`, `rating`, `review_count`, `sold_count`, `size_info`, `image`, `created_at`) VALUES
(1, 'A LITTLE SWEET', 98.00, 1, '\"5 inch\"', 97, 'Delicate 5 inch cake with a light and airy texture that melts in your mouth', 'Our signature \'A LITTLE SWEET\' cake is a perfect indulgence for any occasion. Featuring an incredibly light and airy texture that literally melts in your mouth, this 5 inch delight is crafted with the finest ingredients to bring you a moment of pure happiness.', 'Premium flour, fine sugar, fresh eggs, whole milk, creamy butter, pure vanilla extract', '5-inch (Serves 4-6 people)', 4.7, 42, 55, '5 INCH', 'cake/A_Little_Sweet.jpg', '2026-01-15 02:58:51'),
(2, 'BABY PANDAA', 140.00, 1, '\"5 inch\"', 100, 'Premium Japanese matcha creates a beautiful green color and delicate flavor', 'Our adorable BABY PANDAA cake combines premium Japanese matcha with charming panda design. The rich matcha flavor creates a beautiful green hue while maintaining a delicate balance of sweetness. Perfect for celebrations or as a special treat that\'s almost too cute to eat!', 'Flour, sugar, eggs, premium Japanese matcha powder, milk, butter', '5-inch (Serves 4-6 people)', 4.5, 28, 0, '5 INCH', 'cake/Baby_Pandaa.jpg', '2026-01-15 02:58:51'),
(19, 'Luxury Wedding Cake Package', 200.00, 1, '\"wedding\"', 100, 'Premium wedding cake with customization', 'Custom-designed wedding cake package including consultation, design, and delivery.', 'Premium ingredients based on selection', '3-inch', 4.9, 15, 0, '3 INCH', 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', '2026-01-15 03:07:25'),
(23, 'BABY PENGUINSSS', 140.00, 1, '\"5 inch\"', 100, 'Adorable blue-themed cake featuring a cute penguin ready to celebrate.', 'This charming 5-inch celebration cake features a cheerful penguin wearing a tiny birthday hat, surrounded by stars, sprinkles, and a dreamy blue frosting swirl. Soft, airy, and delicately sweet, it\'s perfect for birthdays, surprises, or anyone who loves cute themed cakes.', 'Premium flour, sugar, eggs, milk, butter, light whipped cream', '5-inch (Serves 4-6 people)', 4.7, 18, 0, '5 INCH', 'cake/Baby_Penguinsss.jpg', '2026-01-15 02:58:51'),
(24, 'BEAR CANDLE', 78.00, 1, '\"5 inch\"', 100, 'Soft pastel cake topped with a sweet teddy and playful cream piping.', 'A lovely pastel-style 5-inch cake dressed in soft pink tones, decorated with rounded cream swirls and a tiny teddy holding a heart. Light, creamy, and beautifully piped, this cake brings a gentle sweetness to baby showers, birthdays, or heartfelt moments.', 'Flour, sugar, eggs, butter, whipped cream, food-safe fondant', '5-inch (Serves 4-6 people)', 4.6, 14, 0, '5 INCH', 'cake/Bear_Candle.jpg', '2026-01-15 02:58:51'),
(25, 'BLACK AND WHITE', 78.00, 1, '\"5 inch\"', 100, 'Elegant monochrome cake featuring a stylish black bear and luxe decorations.', 'This 5-inch modern cake combines cream white icing with black geometric accents, metallic elements, and a deluxe bear topper. Perfect for those who love bold aesthetics, minimal contrast designs, and a touch of luxury for adult birthdays or refined gatherings.', 'Flour, sugar, eggs, butter, fondant accents, edible metallic decor', '5-inch (Serves 4-6 people)', 4.8, 22, 0, '5 INCH', 'cake/Black_and_White.jpg', '2026-01-15 02:58:51'),
(26, 'BLAZING LOVE', 140.00, 1, '\"5 inch\"', 100, 'Romantic heart-themed cake with a cute teddy surrounded by red hearts.', 'Wrapped in red chocolate panels and topped with a teddy hugging a big heart, this 5-inch cake is designed for love-filled surprises. Charming heart decorations and a soft cream center make it ideal for anniversaries, Valentine’s Day, or romantic surprises.', 'Flour, sugar, eggs, butter, cream, chocolate panels', '5-inch (Serves 4-6 people)', 4.6, 16, 0, '5 INCH', 'cake/Blazing_Love.jpg', '2026-01-15 02:58:51'),
(27, 'Peach Capybara Cake', 98.00, 1, '\"5 inch\"', 100, 'Cute capybara cake topped with a tiny peach for an adorable finish.', 'This 5-inch cake captures the gentle and silly charm of a capybara, complete with expressive face details and a peach on its head. Soft, fluffy and irresistibly cute — perfect for animal lovers and casual celebrations.', 'Flour, sugar, eggs, butter, cream, fondant details', '5-inch (Serves 4-6 people)', 4.5, 12, 0, '5 INCH', 'cake/Capybara.jpg', '2026-01-15 02:58:51'),
(28, 'CASTLE RABBIT', 140.00, 1, '\"5 inch\"', 100, 'Sweet bunny cake sitting in a swirl of rainbow marshmallows and hearts.', 'Dreamy and colorful, this 5-inch cake features an adorable bunny surrounded by marshmallow twists and pastel hearts. Light, cheerful, and perfect for kids or anyone who loves charming pastel aesthetics.', 'Flour, sugar, eggs, butter, whipped cream, marshmallow candy', '5-inch (Serves 4-6 people)', 4.7, 20, 0, '5 INCH', 'cake/Castle_Rabbit.jpg', '2026-01-15 02:58:51'),
(29, 'CHEERS BEER MUG CAKE', 68.00, 1, '\"5 inch\"', 100, 'Fun beer-themed cream cake with bubbly foam decoration.', 'This cheerful 5-inch celebration cake is decorated with mini beer mugs and creamy foam accents. It’s the perfect choice for birthdays, gatherings, or a funny surprise for someone who loves beer-themed designs.', 'Flour, sugar, eggs, butter, cream, fondant decorations', '5-inch (Serves 4-6 people)', 4.4, 11, 0, '5 INCH', 'cake/Cheers.jpg', '2026-01-15 02:58:51'),
(30, 'CHOCO ERAL DRIP', 98.00, 1, '\"5 inch\"', 100, 'Tall chocolate drip cake dusted with cocoa, topped with fresh strawberries and chocolate curls.', 'A premium 5-inch tall chocolate cake coated in fine cocoa powder and finished with a glossy dark chocolate drip. The top is elegantly decorated with fresh strawberries, chocolate curls and a cinnamon accent for aroma. Moist, rich and velvety — ideal for chocolate lovers and special moments.', 'Flour, sugar, cocoa powder, eggs, milk, butter, chocolate, fresh strawberries', '5-inch (Serves 4-6 people)', 4.7, 21, 0, '5 INCH', 'cake/Choc_Eral.jpg', '2026-01-15 02:58:51'),
(32, 'CHOCOLATE DELIGHT FEATHER', 138.00, 1, '\"5 inch\"', 100, 'Luxurious chocolate drip cake topped with Ferrero, macaron and dramatic chocolate feather.', 'A decadent 5-inch chocolate cake with a rich dark chocolate drip and a dramatic chocolate feather for height. Topped with Ferrero Rocher, macaron, mini Oreos and crunchy pearls, this cake offers layers of texture and deep chocolate flavour — a striking centrepiece for celebrations.', 'Flour, sugar, cocoa, butter, cream, Ferrero Rocher, macaron, Oreo, chocolate pearls', '5-inch (Serves 4-6 people)', 4.8, 25, 0, '5 INCH', 'cake/Chocolate_Delight.jpg', '2026-01-15 02:58:51'),
(33, 'CHOCOLATE DREAMS TREATS', 138.00, 1, '\"5 inch\"', 100, 'Cream-gradient drip cake loaded with KitKat, Ferrero, pretzel and chocolate bites.', 'This playful 5-inch celebration cake features a smooth white-to-brown cream gradient with a dark chocolate drip. Generously topped with KitKat bars, Ferrero Rocher, pretzels, waffle pieces and chocolate accents, it delivers delightful contrasts of crunch and cream in every slice.', 'Flour, sugar, eggs, butter, cream, KitKat, Ferrero Rocher, pretzels, waffle pieces', '5-inch (Serves 4-6 people)', 4.7, 19, 0, '5 INCH', 'cake/Chocolate_Dreams.jpg', '2026-01-15 02:58:51'),
(34, 'COCOREO PARADISE', 128.00, 1, '\"5 inch\"', 100, 'Cookies & cream cake finished with chocolate drip, Ferrero and Oreo accents.', 'A delightful 5-inch cookies & cream cake finished with a rich chocolate drip and generous toppings of Ferrero Rocher, Oreo cookies and chocolate bars. Creamy, crunchy and indulgent — a favourite for Oreo lovers and anyone craving a textured chocolate treat.', 'Cream, Oreo crumbs, flour, eggs, sugar, chocolate, Ferrero Rocher', '5-inch (Serves 4-6 people)', 4.8, 17, 0, '5 INCH', 'cake/Cocoreo_Paradise.jpg', '2026-01-15 02:58:51'),
(35, 'COOKIES MONSTER FUN', 108.00, 1, '\"5 inch\"', 100, 'Playful Oreo monster cake topped with cute googly-eyed cookies.', 'This adorable 5-inch cookies & cream cake is decorated with playful Oreo \'monsters\' featuring edible googly eyes. Fun, whimsical and delicious — perfect for children\'s parties or themed celebrations that need a smile with every slice.', 'Cream, Oreo, flour, sugar, eggs, chocolate', '5-inch (Serves 4-6 people)', 4.6, 20, 0, '5 INCH', 'cake/Cookies_Monster.jpg', '2026-01-15 02:58:51'),
(36, 'CUTIE CANDLE STRAWBERRY', 88.00, 1, '\"5 inch\"', 100, 'Sweet pastel mini cake with fresh strawberries and a tall birthday candle.', 'A charming 5-inch pastel cake featuring a smiling face design, soft cream layers and fresh strawberries. Topped with a tall striped candle, this cake brings a warm and playful vibe to small celebrations and intimate birthdays.', 'Flour, sugar, eggs, cream, fresh strawberries', '5-inch (Serves 4-6 people)', 4.5, 14, 0, '5 INCH', 'cake/Cutiee_Candlee.jpg', '2026-01-15 02:58:51'),
(37, 'CUTIE POCHACCO BEACH', 140.00, 1, '\"5 inch\"', 100, 'Adorable Pochacco-themed beach cake with float ring and mini umbrella.', 'This playful 5-inch cake showcases Pochacco relaxing in a float ring surrounded by tiny waves, pearls and a colourful umbrella. Bright, cheerful and perfect for kids or character-themed parties, it’s as delightful to look at as it is to taste.', 'Flour, sugar, eggs, cream, fondant figure, decorative sprinkles', '5-inch (Serves 4-6 people)', 4.7, 22, 0, '5 INCH', 'cake/Cutiee_Pochacco.jpg', '2026-01-15 02:58:51'),
(38, 'DOUBLE DELIGHT MINI TIER', 148.00, 1, '\"5 inch\"', 100, 'Two-tier style mini cake with chocolate drip, fresh strawberries and assorted chocolates.', 'A premium 5-inch mini-tier cake featuring a peach-to-chocolate cream gradient and rich dark chocolate drip. Decorated with fresh strawberries, chocolate kisses and Ferrero accents, this cake is elegant, flavourful and perfect for special celebrations or gifting.', 'Flour, sugar, eggs, butter, chocolate, strawberries, assorted chocolates', '5-inch (Serves 4-6 people)', 4.8, 19, 0, '5 INCH', 'cake/Double_Delight.jpg', '2026-01-15 02:58:51'),
(39, 'DOUBLE MATCHA DELIGHT', 118.00, 1, '\"5 inch\"', 100, 'Light and refreshing double-layer matcha cake topped with fresh fruits.', 'DOUBLE MATCHA DELIGHT features soft sponge layers infused with premium Japanese matcha, covered in smooth matcha cream. Decorated with strawberries, blueberries and dried citrus, this cake delivers a balanced sweetness and a fragrant earthy matcha aroma — perfect for matcha lovers seeking a refreshing treat.', 'Flour, sugar, eggs, milk, unsalted butter, Japanese matcha powder, fresh cream, strawberries, blueberries, dried citrus slices', '5-inch (Serves 4-6 people)', 4.7, 12, 0, '5 INCH', 'cake/Double_Matcha.jpg', '2026-01-15 02:58:51'),
(40, 'DOUBLE OREO TEMPTATION', 128.00, 1, '\"5 inch\"', 100, 'A rich double-layer Oreo cake with creamy cookies-and-cream frosting and chocolate drip.', 'DOUBLE OREO TEMPTATION combines crushed Oreo cream filling with soft sponge layers, topped with a decadent chocolate drip and whole Oreo cookies. It offers the perfect mix of creamy, crunchy and chocolaty textures — a dream come true for Oreo fans.', 'Flour, sugar, eggs, milk, unsalted butter, fresh cream, Oreo cookies, dark chocolate', '5-inch (Serves 4-6 people)', 4.8, 20, 0, '5 INCH', 'cake/Double_Oreo.jpg', '2026-01-15 02:58:51'),
(41, 'EARTH MERMAID', 138.00, 1, '\"5 inch\"', 100, 'A charming ocean-themed cake topped with a mermaid tail and colorful sea decorations.', 'EARTH MERMAID brings ocean fantasy to life with its wave-textured blue frosting, fondant mermaid tail, shells and floral decorations. Cute, vibrant and full of character, this cake is ideal for birthdays, children’s parties, or anyone who loves whimsical themes.', 'Flour, sugar, eggs, milk, unsalted butter, fresh cream, fondant decorations, edible coloring, sugar pearls', '5-inch (Serves 4-6 people)', 4.6, 14, 0, '5 INCH', 'cake/Earthh_Mermaid.jpg', '2026-01-15 02:58:51'),
(42, 'ENJOY DREAM CHOCOLATE', 148.00, 1, '\"5 inch\"', 100, 'Elegant chocolate drip cake topped with a mini whiskey bottle and assorted chocolate pieces.', 'ENJOY DREAM CHOCOLATE is crafted with a rich chocolate frosting and glossy drip, finished with assorted chocolate bars, blueberries and a decorative whiskey bottle. With its bold and refined presentation, it’s a perfect choice for celebrations and chocolate lovers who enjoy a mature, premium look.', 'Flour, sugar, eggs, milk, unsalted butter, fresh cream, dark chocolate, assorted chocolate pieces, blueberries', '5-inch (Serves 4-6 people)', 4.9, 18, 0, '5 INCH', 'cake/Enjoy_Dream.jpg', '2026-01-15 02:59:34'),
(43, 'ENTWINE CHOCOLATE BARREL', 148.00, 1, '\"5 inch\"', 100, 'A premium chocolate-loaded cake with layered textures and whiskey-inspired decoration.', 'ENTWINE CHOCOLATE BARREL is packed with luxurious chocolate elements — chocolate drip, bars, truffles and cookies. Finished with a whiskey bottle decoration, this cake delivers deep chocolate flavor and an elegant celebration look.', 'Flour, sugar, eggs, milk, butter, fresh cream, dark chocolate, assorted chocolate bars, Ferrero Rocher, Oreo cookies', '5-inch (Serves 4-6 people)', 4.8, 22, 0, '5 INCH', 'cake/Entwine_Choc.jpg', '2026-01-15 02:59:34'),
(44, 'EXQUISITE GIRL', 158.00, 1, '\"5 inch\"', 100, 'A cute and stylish character cake featuring vibrant fondant decorations.', 'EXQUISITE GIRL showcases an adorable character made with detailed fondant elements — braids, glasses, headband and expressive features. Bright, charming and full of personality, it’s a perfect cake for girls’ birthdays or anyone who loves character-themed designs.', 'Flour, sugar, eggs, milk, butter, fresh cream, fondant, edible coloring', '5-inch (Serves 4-6 people)', 4.7, 15, 0, '5 INCH', 'cake/Exquisite_Girl.jpg', '2026-01-15 02:59:34'),
(45, 'BEARY GIFT DELIGHT', 148.00, 1, '\"5 inch\"', 100, 'A playful two-tier mini cake topped with a cute bear, strawberries and decorative sweets.', 'BEARY GIFT DELIGHT features a charming bear topper, layered cream pipings and assorted chocolate and fruit decorations. With bright pink accents and playful shapes, this cake is perfect for anniversaries, surprise gifts or any joyful celebration.', 'Flour, sugar, eggs, milk, butter, fresh cream, fondant decorations, strawberries, assorted chocolates', '5-inch (Serves 4-6 people)', 4.7, 11, 0, '5 INCH', 'cake/Gift_Lotsoo.jpg', '2026-01-15 02:59:34'),
(46, 'GREEN MATCHA KITWRAP', 138.00, 1, '\"5 inch\"', 100, 'Elegant matcha cake wrapped with green chocolate sticks and topped with macarons and strawberries.', 'GREEN MATCHA KITWRAP is a refined matcha-flavored cake encircled with matcha chocolate fingers and finished with powdered strawberries and macarons. The matcha crumb and smooth cream create a delicate balance of umami and sweetness.', 'Flour, sugar, eggs, milk, unsalted butter, Japanese matcha powder, fresh cream, matcha chocolate sticks, macarons, strawberries', '5-inch (Serves 4-6 people)', 4.8, 16, 0, '5 INCH', 'cake/Green_Matcha.jpg', '2026-01-15 02:59:34'),
(47, 'HONEY BUBBLE LATTE', 118.00, 1, '\"5 inch\"', 100, 'Trendy bubble-tea inspired cake with creamy swirl topping and chocolate boba accents.', 'HONEY BUBBLE LATTE captures the charm of a milk tea drink with its soft brown ombré frosting, swirled cream top and chocolate \'boba\' decorations around the base. A fun, modern choice for casual birthdays and themed parties.', 'Flour, sugar, eggs, milk, butter, fresh cream, chocolate pearls, espresso or tea flavoring, macaron', '5-inch (Serves 4-6 people)', 4.6, 10, 0, '5 INCH', 'cake/H_Tea.jpg', '2026-01-15 02:59:34'),
(48, 'ICE CREAM SCOOP FEST', 128.00, 1, '\"5 inch\"', 100, 'Playful ice-cream themed cake topped with colorful scoops, chocolate drips and fresh strawberries.', 'ICE CREAM SCOOP FEST is decorated to look like an ice-cream sundae with piped cream scoops, chocolate drips and sweet toppings. Bright, whimsical and full of texture, it’s great for children’s parties or anyone who loves playful dessert styling.', 'Flour, sugar, eggs, milk, butter, fresh cream, food coloring, chocolate topping, strawberries', '5-inch (Serves 4-6 people)', 4.7, 13, 0, '5 INCH', 'cake/Ice_Scream.jpg', '2026-01-15 02:59:34'),
(49, 'KITKAT CELEBRATION', 158.00, 1, '\"5 inch\"', 100, 'Classic KitKat-wrapped cake with rich chocolate rosette top and festive ribbon.', 'KITKAT CELEBRATION is surrounded by chocolate sticks and topped with a glossy chocolate rosette centre. A red ribbon finishes the presentation, making it an elegant yet fun choice for birthdays and special occasions.', 'Flour, sugar, eggs, milk, butter, fresh cream, KitKat bars, dark chocolate, decorative ribbon (non-edible)', '5-inch (Serves 4-6 people)', 4.9, 21, 0, '5 INCH', 'cake/Kitcake_Cross.jpg', '2026-01-15 02:59:34'),
(50, 'MUG DRIP TREASURE', 128.00, 1, '\"5 inch\"', 100, 'Creative mug-shaped drip cake with gold coin accents and chocolate toppers.', 'MUG DRIP TREASURE mimics a frothy mug with dripping icing and gold-foil chocolate coins. Finished with cookies and a small topper, this novelty cake is perfect for themed parties, groomsmen gifts or playful celebrations.', 'Flour, sugar, eggs, milk, butter, fresh cream, white chocolate drip, chocolate coins, Oreos', '5-inch (Serves 4-6 people)', 4.6, 9, 0, '5 INCH', 'cake/Lets_Beerrr.jpg', '2026-01-15 02:59:34'),
(51, 'CHOCOLATE MAGNUM RING', 168.00, 1, '\"5 inch\"', 100, 'Luxurious chocolate ring cake wrapped in premium bars and topped with glossy ganache.', 'CHOCOLATE MAGNUM RING is a decadent centerpiece wrapped with chocolate bars and finished with a rich ganache top.传递的完美平衡。', 'Flour, sugar, eggs, milk, butter, fresh cream, milk chocolate bars, dark ganache', '5-inch (Serves 4-6 people)', 4.9, 24, 0, '5 INCH', 'cake/Limited_Edition.jpg', '2026-01-15 02:59:34'),
(52, 'CHOCOLATE DRIP DELUXE', 148.00, 1, '\"5 inch\"', 100, 'Decadent chocolate drip cake topped with macaron, pretzel and fruit accents.', 'CHOCOLATE DRIP DELUXE features a smooth cream base with dramatic chocolate drip and an assortment of premium toppings — macarons, pretzels, berries and a mini chocolate bar. The layered textures and rich flavors make it a standout choice for chocolate lovers.', 'Flour, sugar, eggs, milk, butter, fresh cream, dark chocolate drip, macarons, pretzel, fresh strawberry, Oreo', '5-inch (Serves 4-6 people)', 4.8, 17, 0, '5 INCH', 'cake/Magnum_Chocolate.jpg', '2026-01-15 02:59:34'),
(53, 'MANGO ROYALE', 138.00, 1, '\"5 inch\"', 100, 'Elegant mango drip cake crowned with a glossy mango sphere and dried floral accents.', 'MANGO ROYALE features a smooth cream base with vibrant mango drip, topped with a decorative glossy mango sphere and delicate floral touches. Light, fruity and refined — perfect for summer celebrations or an elegant dessert centrepiece.', 'Flour, sugar, eggs, milk, butter, fresh cream, mango puree, gelatin (for sphere), edible flowers', '5-inch (Serves 4-6 people)', 4.8, 10, 0, '5 INCH', 'cake/Mango_Royale.jpg', '2026-01-15 02:59:34'),
(54, 'MERMAID MEMORY (AQUA)', 148.00, 1, '\"5 inch\"', 100, 'Ocean-inspired mermaid cake with pastel aquamarine frosting and fondant tail accents.', 'MERMAID MEMORY (AQUA) brings seaside fantasy with layered aquamarine frosting, shell and mermaid-tail fondant decorations, and shimmering edible accents. A whimsical choice for birthdays and mermaid-themed parties.', 'Flour, sugar, eggs, milk, butter, fresh cream, fondant decorations, edible glitter, food coloring', '5-inch (Serves 4-6 people)', 4.7, 13, 0, '5 INCH', 'cake/Mermaid_Memory.jpg', '2026-01-15 02:59:34'),
(55, 'MERMAID MEMORY (PINK)', 148.00, 1, '\"5 inch\"', 100, 'Pink ombré mermaid cake with seashells, pearls and a delicate mermaid-tail topper.', 'MERMAID MEMORY (PINK) features a soft pink-to-rose ombré frosting, pearl embellishments and a pastel mermaid tail. Cute, dreamy and perfect for pastel-themed birthdays or baby showers.', 'Flour, sugar, eggs, milk, butter, fresh cream, fondant decorations, edible pearls, food coloring', '5-inch (Serves 4-6 people)', 4.7, 11, 0, '5 INCH', 'cake/Mermaid_Story.jpg', '2026-01-15 02:59:34'),
(56, 'MOCHA LOVER TIRAMISU', 128.00, 1, '\"5 inch\"', 100, 'Layered mocha tiramisu-style cake finished with cocoa-dusted cream and a birthday topper.', 'MOCHA LOVER TIRAMISU stacks coffee-soaked sponge with silky mascarpone-style cream, decorated with cocoa dusting and creamy swirls. Rich coffee aroma with balanced sweetness — ideal for coffee fans and intimate celebrations.', 'Flour, sugar, eggs, milk, butter, mascarpone or cream cheese mix, espresso, cocoa powder', '5-inch (Serves 4-6 people)', 4.8, 14, 0, '5 INCH', 'cake/Mocha_Lover.jpg', '2026-01-15 02:59:34'),
(57, 'MONBEAR ROSETTE', 158.00, 1, '\"5 inch\"', 100, 'Elegant rosette cake in chocolate ombré tones crowned with a cute bear topper.', 'MONBEAR ROSETTE presents layered piped rosettes in gradient chocolate hues, finished with fresh chocolate-dipped strawberries and an adorable bear topper — a stylish and charming pick for special birthdays.', 'Flour, sugar, eggs, milk, butter, fresh cream, chocolate, strawberries, fondant topper', '5-inch (Serves 4-6 people)', 4.9, 18, 0, '5 INCH', 'cake/Monbear.jpg', '2026-01-15 02:59:34'),
(58, 'OMMA SWEET HEART', 128.00, 1, '\"5 inch\"', 100, 'Adorable drip mug-inspired cake with gold coins and cookie accents.', 'OMMA SWEET HEART mimics a frothy mug with white drip and gold-foil chocolate coins, finished with Oreos and macarons. A playful novelty cake perfect for casual celebrations and themed giftings.', 'Flour, sugar, eggs, milk, butter, fresh cream, white chocolate drip, chocolate coins, Oreos, macarons', '5-inch (Serves 4-6 people)', 4.6, 9, 0, '5 INCH', 'cake/Omma.jpg', '2026-01-15 02:59:34'),
(59, 'PARTY BEAR', 148.00, 1, '\"5 inch\"', 100, 'Cute bear-themed cake decorated with piped flowers and a playful character topper.', 'PARTY BEAR features pastel piped flowers encircling a cheerful bear figure. Bright, whimsical and filled with personality — a delightful choice for kids’ birthdays and family celebrations.', 'Flour, sugar, eggs, milk, butter, fresh cream, fondant topper, food coloring', '5-inch (Serves 4-6 people)', 4.7, 12, 0, '5 INCH', 'cake/Party_Bear.jpg', '2026-01-15 02:59:34'),
(60, 'PENGUIN BABY BLUE', 138.00, 1, '\"5 inch\"', 100, 'Adorable penguin-themed cake with blue accents and playful pocket detail.', 'PENGUIN BABY BLUE is a charming character cake with crisp fondant details and soft blue frosting. Cute pocket motif and simple, clean lines make it perfect for baby showers and children’s birthdays.', 'Flour, sugar, eggs, milk, butter, fresh cream, fondant decorations, food coloring', '5-inch (Serves 4-6 people)', 4.8, 15, 0, '5 INCH', 'cake/Penguin_Baby_Blue.jpg', '2026-01-15 02:59:34'),
(61, 'PENGUIN BABY PINK', 138.00, 1, '\"5 inch\"', 100, 'Cute penguin-themed cake with soft pink overalls and a tiny bear in the pocket.', 'PENGUIN BABY PINK features a charming penguin character dressed in pastel pink overalls, complete with a mini bear tucked in its pocket. Soft colors and clean fondant details make it perfect for children’s birthdays and baby celebrations.', 'Flour, sugar, eggs, milk, butter, fresh cream, fondant decorations, food coloring', '5-inch (Serves 4-6 people)', 4.8, 15, 0, '5 INCH', 'cake/Penguin_Baby_Pink.jpg', '2026-01-15 02:59:34'),
(62, 'ROYAL CHOCOLATE GOLD', 158.00, 1, '\"5 inch\"', 100, 'Elegant dark chocolate cake topped with edible gold bars and chocolate drip.', 'ROYAL CHOCOLATE GOLD showcases deep chocolate richness paired with luxurious edible gold bars. Smooth chocolate drip and gold accents make it the perfect premium centerpiece for celebrations and gifting.', 'Flour, sugar, eggs, milk, butter, fresh cream, dark chocolate, edible gold coating', '5-inch (Serves 4-6 people)', 4.9, 18, 0, '5 INCH', 'cake/Royal_Chocolate.jpg', '2026-01-15 02:59:34'),
(63, 'STARRY NIGHT BEAR', 148.00, 1, '\"5 inch\"', 100, 'Galaxy-themed buttercream cake with rosettes, Oreo pieces and a mini bear topper.', 'STARRY NIGHT BEAR features a galaxy-inspired frosting blend decorated with piped rosettes, Oreo cookies and a sleepy bear topper. Dreamy, whimsical and perfect for night-sky lovers.', 'Flour, sugar, eggs, milk, butter, fresh cream, Oreo cookies, fondant topper, food coloring', '5-inch (Serves 4-6 people)', 4.7, 14, 0, '5 INCH', 'cake/Starry_Night.jpg', '2026-01-15 02:59:34'),
(64, 'SUNFLOWER BLOOM', 138.00, 1, '\"5 inch\"', 100, 'Handcrafted sunflower-themed cake with detailed yellow petals and textured centre.', 'SUNFLOWER BLOOM features a beautifully piped sunflower top with layered petals and a textured centre. Clean green and white sides complete this elegant, cheerful design. Ideal for birthdays, mothers\' day or floral-theme celebrations.', 'Flour, sugar, eggs, milk, butter, fresh cream, food coloring, chocolate wafer center', '5-inch (Serves 4-6 people)', 4.8, 16, 0, '5 INCH', 'cake/Sunflower.jpg', '2026-01-15 02:59:34'),
(65, 'SUNSHINE GARDEN', 138.00, 1, '\"5 inch\"', 100, 'Bright and cheerful sunflower cake decorated with multiple piped flowers.', 'SUNSHINE GARDEN brings warm, sunny happiness with its multiple hand-piped sunflower designs and soft yellow frosting. Simple, elegant and uplifting — great for birthdays and appreciation occasions.', 'Flour, sugar, eggs, milk, butter, fresh cream, food coloring', '5-inch (Serves 4-6 people)', 4.7, 13, 0, '5 INCH', 'cake/Sunshine.jpg', '2026-01-15 02:59:34'),
(66, 'SWEET CASTLE DREAM', 148.00, 1, '\"5 inch\"', 100, 'Romantic strawberry-and-macaron cake with pink drip and ice-cream cone topper.', 'SWEET CASTLE DREAM is decorated with macarons, berries and a fantasy-style ice-cream cone swirl. Pink drip and heart-shaped accents make it a lovely choice for birthdays, anniversaries or sweet surprise gifts.', 'Flour, sugar, eggs, milk, butter, fresh cream, macarons, strawberries, blueberries, food coloring', '5-inch (Serves 4-6 people)', 4.8, 17, 0, '5 INCH', 'cake/Sweet_Castle.jpg', '2026-01-15 02:59:34'),
(67, 'SWEET LIFE ICE CREAM', 138.00, 1, '\"5 inch\"', 100, 'Cute ice-cream themed cake with cone decorations and pink whipped-cream swirls.', 'SWEET LIFE ICE CREAM features multiple piped soft-serve ice-cream swirls and cone designs around the cake. Playful, bright and full of fun textures — perfect for kids and pastel-themed celebrations.', 'Flour, sugar, eggs, milk, butter, fresh cream, wafer cones, food coloring', '5-inch (Serves 4-6 people)', 4.6, 12, 0, '5 INCH', 'cake/Sweet_Life.jpg', '2026-01-15 02:59:34'),
(69, 'SWEETY BERRY DRIP', 138.00, 1, '\"5 inch\"', 100, 'Fresh strawberry cake with soft pink cream and a smooth white drip finish.', 'SWEETY BERRY DRIP features a light strawberry-infused cream exterior paired with a smooth white chocolate drip. Topped generously with fresh strawberries, this cake offers a refreshing fruity sweetness — perfect for berry lovers and elegant celebrations.', 'Flour, sugar, eggs, milk, butter, fresh cream, strawberries, white chocolate, strawberry puree', '5-inch (Serves 4-6 people)', 4.8, 16, 0, '5 INCH', 'cake/Sweety_Berry.jpg', '2026-01-15 02:59:34'),
(70, 'TYCOON RICH MAN CAKE', 158.00, 1, '\"5 inch\"', 100, 'Playful rich-man themed fondant cake holding money and wearing sunglasses.', 'TYCOON RICH MAN CAKE delivers a humorous and stylish fondant character holding cash, paired with bold sunglasses and iconic detailing. Perfect for fun birthdays, playful surprises and celebrations for the ‘rich man’ in your life.', 'Flour, sugar, eggs, milk, butter, fresh cream, fondant, edible food coloring', '5-inch (Serves 4-6 people)', 4.7, 12, 0, '5 INCH', 'cake/Tycoon.jpg', '2026-01-15 02:59:34'),
(71, 'WAITING FOR U MATCHA', 148.00, 1, '\"5 inch\"', 100, 'Adorable matcha drip cake topped with a cute bear and mini cash decorations.', 'WAITING FOR U MATCHA combines soft vanilla cream with a gentle matcha drip, topped with a cute bear figure resting on fluffy cream puffs and mini money notes. Sweet, charming and perfect for birthday surprises.', 'Flour, sugar, eggs, milk, butter, fresh cream, matcha powder, fondant topper', '5-inch (Serves 4-6 people)', 4.8, 14, 0, '5 INCH', 'cake/Waiting_For_U.jpg', '2026-01-15 02:59:34'),
(72, 'WHITE KINDER DELIGHT', 148.00, 1, '\"5 inch\"', 100, 'Kinder-inspired cake with white drip, chocolate bars and creamy swirl topping.', 'WHITE KINDER DELIGHT features a velvety white drip paired with layers of whipped cream swirls, topped with Kinder-style chocolate bars.传递的完美平衡。', 'Flour, sugar, eggs, milk, butter, fresh cream, white chocolate, Kinder-style chocolate bars', '5-inch (Serves 4-6 people)', 4.9, 18, 0, '5 INCH', 'cake/White_Kinder.jpg', '2026-01-15 02:59:34'),
(73, 'BLUEBERRY CHEESE', 88.00, 1, '\"cheese\"', 100, 'Velvety blueberry cheesecake topped with glossy berry compote.', 'BLUEBERRY CHEESE features a smooth, creamy cheesecake layer with a sweet-tart blueberry compote crown. Finished with fresh strawberries and blueberries, this cake delivers a balanced creaminess and fruity brightness — perfect for classic cheesecake lovers.', 'Cream cheese, sugar, eggs, heavy cream, blueberry compote, graham cracker crust, fresh strawberries, blueberries', '5-inch (Serves 4-6 people)', 4.8, 12, 0, '5 INCH', 'cake/Blueberry_Cheese(cheese flavour).jpg', '2026-01-15 02:58:51'),
(74, 'CHOCOLATE CHEESE', 138.00, 1, '\"cheese\"', 100, 'Rich chocolate cheesecake with cocoa-sprinkled top and fresh berry garnish.', 'CHOCOLATE CHEESE combines creamy cheesecake with a rich chocolate layer and cocoa finish. Garnished with fresh strawberries and blueberries, it blends silky chocolate depth with classic cheesecake texture for a decadent treat.', 'Cream cheese, sugar, eggs, heavy cream, cocoa powder, dark chocolate, graham cracker crust, fresh strawberries, blueberries', '5-inch (Serves 4-6 people)', 4.8, 14, 0, '5 INCH', 'cake/Chocolate_Cheese.jpg', '2026-01-15 03:07:25'),
(75, 'JAPANESE COTTON CHEESE', 148.00, 1, '\"cheese\"', 100, 'Light and airy Japanese cotton-style cheesecake with delicate creaminess.', 'JAPANESE COTTON CHEESE delivers a fluffy, soufflé-like texture with a gentle cheese flavor. Lower in richness but high in melt-in-your-mouth softness, it’s an elegant choice for those who prefer a light and cloud-like cheesecake.', 'Cream cheese, eggs, sugar, flour, milk, butter, cornstarch', '5-inch (Serves 4-6 people)', 4.7, 11, 0, '5 INCH', 'cake/Japanese_Cheese.jpg', '2026-01-15 02:59:34'),
(76, 'LEMON CHEESE CLASSIC', 78.00, 1, '\"cheese\"', 100, 'Refreshing lemon cheesecake with zesty citrus glaze and buttery base.', 'LEMON CHEESE CLASSIC balances tangy lemon curd with silky cream cheese filling and a crisp graham base. Topped with lemon slices and a glossy lemon glaze, it offers a bright, refreshing finish ideal for sunny celebrations.', 'Cream cheese, sugar, eggs, heavy cream, lemon juice, lemon zest, graham cracker crust', '5-inch (Serves 4-6 people)', 4.9, 16, 0, '5 INCH', 'cake/Lemon_Cheese.jpg', '2026-01-15 02:59:34'),
(77, 'OREO CHEESE DELIGHT', 138.00, 1, '\"cheese\"', 100, 'Cookies-and-cream cheesecake layered with Oreo crumbs and creamy filling.', 'OREO CHEESE DELIGHT layers classic cheesecake with generous Oreo cookie crumbs in both crust and filling. Finished with a dusting of crushed cookies and whole Oreo accents, it’s a playful and indulgent crowd-pleaser.', 'Cream cheese, sugar, eggs, heavy cream, Oreo cookies (crumbs and pieces), graham/crust base', '5-inch (Serves 4-6 people)', 4.8, 18, 0, '5 INCH', 'cake/Oreo_Cheese.jpg', '2026-01-15 02:59:34'),
(78, 'PEACH CHEESE BLOSSOM', 98.00, 1, '\"cheese\"', 100, 'Delicate peach-topped cheesecake with fruity glaze and buttery crust.', 'PEACH CHEESE BLOSSOM features a creamy cheesecake base crowned with glossy poached peach pieces and a light fruity glaze.传递的完美平衡。', 'Cream cheese, sugar, eggs, heavy cream, poached peaches, peach glaze, graham cracker crust', '5-inch (Serves 4-6 people)', 4.7, 13, 0, '5 INCH', 'cake/peach cheese cake.jpeg', '2026-01-15 02:59:34'),
(79, 'RAINBOW LOVE CHEESE', 148.00, 1, '\"cheese\"', 100, 'Vibrant rainbow-layered cheesecake topped with colorful macarons and sponge cubes.', 'RAINBOW LOVE CHEESE is a joyful multi-layered cheesecake that pairs light, creamy cheese layers with colorful sponge inserts and a playful macaron topping. Bright, festive and perfect for celebrations or anyone who loves a cheerful, picture-ready dessert.', 'Cream cheese, sugar, eggs, heavy cream, sponge cake layers, macarons, graham cracker crust, food coloring', '5-inch (Serves 4-6 people)', 4.8, 20, 0, '5 INCH', 'cake/Rainbow_Love_Cheese.jpg', '2026-01-15 03:07:25'),
(80, 'RED VELVET CHEESE', 138.00, 1, '\"cheese\"', 100, 'Elegant red velvet cheesecake crowned with floral chocolate disc and rose accents.', 'RED VELVET CHEESE combines the classic moist red velvet base with a silky cream-cheese layer, finished with a decorative white chocolate disc and delicate rose garnish. A refined twist on two beloved desserts in one elegant presentation.', 'Cream cheese, sugar, eggs, heavy cream, red velvet sponge, cocoa, white chocolate, edible rose decorations, graham cracker crust', '5-inch (Serves 4-6 people)', 4.7, 15, 0, '5 INCH', 'cake/Redvelvet-website.jpg', '2026-01-15 02:59:34'),
(81, 'MANGO & BERRY CHEESE TART', 148.00, 1, '\"cheese\"', 100, 'Deluxe mini cheesecake topped with fresh mango, berries and glossy fruit gel.', 'MANGO & BERRY CHEESE TART features a smooth cream-cheese filling on a buttery base, topped with fresh mango cubes, strawberries, blueberries and a shiny fruit glaze. Bright, fruity and refreshing — a refined single-serve cheesecake experience in cake form.', 'Cream cheese, sugar, eggs, heavy cream, mango, strawberries, blueberries, fruit glaze, graham cracker crust', '5-inch (Serves 4-6 people)', 4.9, 18, 0, '5 INCH', 'cake/Say_Cheese.jpg', '2026-01-15 02:59:34'),
(82, 'TRIO CHOCO CHEESE', 158.00, 1, '\"cheese\"', 100, 'Decadent three-layer chocolate & cheese cake with dark chocolate glaze.', 'TRIO CHOCO CHEESE layers dark chocolate mousse, creamy cheesecake and chocolate ganache on a crunchy base. Topped with macaron accents, chocolate decorations and a glossy finish, this cake is for chocolate lovers seeking complex textures and rich flavor.', 'Cream cheese, sugar, eggs, heavy cream, dark chocolate, milk chocolate, ganache, graham/crust base, macarons', '5-inch (Serves 4-6 people)', 4.9, 22, 0, '5 INCH', 'cake/Trio_Choco_Cheese.jpg', '2026-01-15 02:59:34'),
(83, 'Belgium Chocolate', 95.00, 1, '\"chocolate\"', 100, 'A rich Belgian chocolate cake layered with premium chocolate cream.', 'Indulge in the intense flavour of our Belgium Chocolate cake, crafted with premium dark chocolate and layered with smooth chocolate cream. Perfect for true chocolate lovers.', 'Dark chocolate, flour, eggs, sugar, butter, cocoa powder', '8-inch', 4.9, 52, 0, '8 INCH', 'cake/Chocolate & Coffee/BELGIUM CHOCOLATE.webp', '2026-01-15 03:07:25'),
(84, 'Black Forest', 88.00, 1, '\"chocolate\"', 100, 'A timeless favourite—layers of light chocolate sponge, fresh cream, and cherries.', 'A timeless favourite—layers of light chocolate sponge, fresh cream, and cherries, topped with chocolate curls for the perfect finish.', 'Flour, cocoa, eggs, sugar, cream, cherries, chocolate', '8-inch', 4.7, 41, 0, '8 INCH', 'cake/Chocolate & Coffee/BLACK FOREST.webp', '2026-01-15 02:59:34'),
(85, 'Chocolate Mousse', 92.00, 1, '\"chocolate\"', 100, 'Smooth and airy chocolate mousse cake with rich cocoa flavour.', 'Our chocolate mousse cake is crafted with silky smooth chocolate mousse layered over soft sponge, delivering a melt-in-your-mouth experience.', 'Chocolate, cream, cocoa, gelatin, eggs, sugar', '8-inch', 4.8, 37, 0, '8 INCH', 'cake/Chocolate & Coffee/CHOCOLATE MOUSSE.webp', '2026-01-15 02:59:34'),
(86, 'Chocolate Sesame', 89.00, 1, '\"chocolate\"', 100, 'Unique blend of chocolate richness with nutty sesame aroma.', 'A modern twist combining chocolate sponge with roasted sesame cream for a deep, aromatic flavour profile unlike traditional chocolate cakes.', 'Chocolate, sesame paste, flour, sugar, eggs, butter', '8-inch', 4.6, 28, 0, '8 INCH', 'cake/Chocolate & Coffee/CHOCOLATE SESAME.webp', '2026-01-15 02:59:34'),
(87, 'Chotiramisu', 98.00, 1, '\"chocolate\"', 100, 'A delightful fusion of tiramisu and chocolate, blending cocoa with coffee.', 'A delightful fusion of tiramisu and chocolate, blending rich cocoa layers with aromatic coffee-soaked sponge and mascarpone cream.', 'Mascarpone, coffee, cocoa, eggs, ladyfingers, sugar', '8-inch', 4.8, 44, 0, '8 INCH', 'cake/Chocolate & Coffee/CHOTIRAMISU.webp', '2026-01-15 02:59:34'),
(88, 'Green Gato', 90.00, 1, '\"chocolate\"', 100, 'Green Gato blends the earthy flavour of matcha with the richness of chocolate.', 'Green Gato blends the earthy flavour of matcha with the richness of chocolate, creating a refined dessert with layered textures.', 'Matcha, chocolate, flour, eggs, sugar, cream', '8-inch', 4.7, 33, 0, '8 INCH', 'cake/Chocolate & Coffee/GREEN GATO.webp', '2026-01-15 02:59:34'),
(89, 'Moonlight Eve', 105.00, 1, '\"chocolate\"', 100, 'Moonlight Eve features deep, velvety dark chocolate mousse layered with smooth cream.', 'Moonlight Eve features deep, velvety dark chocolate mousse layered with smooth cream and topped with an elegant chocolate sphere for a premium presentation.', 'Dark chocolate, cocoa, cream, sugar, eggs, butter', '8-inch', 4.9, 56, 0, '8 INCH', 'cake/Chocolate & Coffee/MOONLIGHT EVE.webp', '2026-01-15 02:59:34'),
(90, 'Opera Cake', 110.00, 1, '\"chocolate\"', 100, 'A sophisticated French dessert combining almond sponge soaked in coffee syrup.', 'A sophisticated French dessert combining almond sponge soaked in coffee syrup, layered with chocolate ganache and coffee buttercream.', 'Almond flour, coffee, chocolate, butter, eggs, sugar', '8-inch', 4.9, 49, 0, '8 INCH', 'cake/Chocolate & Coffee/OPERA.webp', '2026-01-15 02:59:34'),
(91, 'Rich Chocolate', 85.00, 1, '\"chocolate\"', 100, 'A cake for true chocolate fans — dense cocoa sponge paired with rich buttercream.', 'A cake for true chocolate fans — dense cocoa sponge paired with rich chocolate buttercream for a bold flavour experience.', 'Cocoa, flour, eggs, sugar, butter', '8-inch', 4.6, 31, 0, '8 INCH', 'cake/Chocolate & Coffee/RICH CHOCOLATE.webp', '2026-01-15 02:59:34'),
(92, 'Tiramisu', 95.00, 1, '\"chocolate\"', 100, 'Traditional tiramisu crafted with espresso-soaked ladyfingers and creamy mascarpone.', 'A traditional tiramisu crafted with espresso-soaked ladyfingers, creamy mascarpone, and a generous dusting of cocoa for a perfect balance.', 'Mascarpone, coffee, cocoa, ladyfingers, eggs, sugar', '8-inch', 4.8, 45, 0, '8 INCH', 'cake/Chocolate & Coffee/Tiramisu.webp', '2026-01-15 02:59:34'),
(93, 'PINK CELEBRATION TIER', 88.00, 1, '\"strawberry\"', 100, 'A beautifully crafted two-tier cake featuring soft pink rosettes and pearl piping.', 'A beautifully crafted two-tier cake featuring soft pink rosettes, pearl piping, and elegant quilted patterns. Perfect for birthdays and grand celebrations with a delightful strawberry-inspired finish.', 'Flour, sugar, eggs, butter, fresh cream, strawberry essence, food coloring', '3-inch', 4.8, 42, 0, '3 INCH', 'cake/Strawberry Flavour/Birthday Cake.webp', '2026-01-15 03:00:13'),
(94, 'ROSE HEART STRAWBERRY CAKE', 72.00, 1, '\"strawberry\"', 100, 'A heart-shaped cake decorated with luscious pink and white buttercream rosettes.', 'A heart-shaped cake decorated with luscious pink and white buttercream rosettes, creating a dreamy floral texture. Ideal for anniversaries, birthdays, or romantic surprises.', 'Flour, sugar, eggs, butter, fresh cream, strawberry puree, food coloring', '3-inch', 4.9, 37, 0, '3 INCH', 'cake/Strawberry Flavour/Designer Rose Heart Cake.jpg', '2026-01-15 03:00:13'),
(95, 'STRAWBERRY HEART DRIP CAKE', 68.00, 1, '\"strawberry\"', 100, 'A striking heart cake featuring a smooth strawberry glaze and chocolate drip accent.', 'A striking heart cake featuring a smooth strawberry glaze, chocolate drip accent, and striped chocolate heart toppers. Sweet, stylish, and perfect for special occasions.', 'Flour, sugar, eggs, butter, strawberry flavor, chocolate, food coloring', '3-inch', 4.8, 33, 0, '3 INCH', 'cake/Strawberry Flavour/Five Star Strawberry Heart Cake.jpg', '2026-01-15 03:00:13'),
(96, 'STRAWBERRY DELIGHT ROUND CAKE', 65.00, 1, '\"strawberry\"', 100, 'A premium strawberry-glazed cake drizzled with artistic chocolate elements.', 'A premium strawberry-glazed cake drizzled with artistic chocolate elements, finished with cherries and decorative sticks. A perfect blend of sweetness and sophistication.', 'Flour, sugar, eggs, butter, fresh cream, strawberry glaze, chocolate', '2 INCH', 4.7, 29, 0, '2 INCH', 'cake/Strawberry Flavour/Five Star Strawberry.jpg', '2026-01-15 03:00:13'),
(97, 'HEARTS & ROSES STRAWBERRY CAKE', 66.00, 1, '\"strawberry\"', 100, 'Featuring a smooth marbled pink surface and delicate strawberry drip.', 'Featuring a smooth marbled pink surface, delicate strawberry drip, and charming heart-shaped chocolate pieces, this cake is made for heartfelt celebrations.', 'Flour, sugar, eggs, butter, cream, strawberry syrup, chocolate', '3-inch', 4.8, 31, 0, '3 INCH', 'cake/Strawberry Flavour/Happy Birthday Strawberry Cake.jpg', '2026-01-15 03:00:13'),
(98, 'STRAWBERRY FANTASY DRIP CAKE', 68.00, 1, '\"strawberry\"', 100, 'A classic strawberry cake topped with a glossy red glaze.', 'A classic strawberry cake topped with a glossy red glaze, decorative strawberry topper, and swirl piping around the edges. A bright and cheerful cake for all strawberry lovers.', 'Flour, sugar, eggs, butter, cream, strawberry essence, food coloring', '3-inch', 4.7, 27, 0, '3 INCH', 'cake/Strawberry Flavour/Heartwarming-strawberry.jpg', '2026-01-15 03:00:13'),
(99, 'PINK SWIRL STRAWBERRY CAKE', 62.00, 1, '\"strawberry\"', 100, 'A beautifully piped strawberry cake featuring soft pink swirls.', 'A beautifully piped strawberry cake featuring soft pink swirls, smooth glaze, and charming decorative chocolate accents. Light, creamy, and perfect for gifting.', 'Flour, sugar, eggs, butter, cream, strawberry flavoring', '3-inch', 4.6, 22, 0, '3 INCH', 'cake/Strawberry Flavour/Strawberry Cake - Midnight Delivery.jpg', '2026-01-15 03:00:13'),
(100, 'STRAWBERRY LOVE MOM CAKE', 70.00, 1, '\"strawberry\"', 100, 'A soft pink strawberry cream cake specially designed for moms.', 'A meaningful strawberry cake with elegant cream drops, chocolate sticks, and a loving message design. Ideal as a Mother’s Day gift or a sweet surprise for someone special.', 'Flour, sugar, eggs, butter, whipping cream, strawberry flavor, chocolate', '3-inch', 4.9, 40, 0, '3 INCH', 'cake/Strawberry Flavour/Strawberry Cream cake For Mom.jpg', '2026-01-15 03:00:13'),
(101, 'SILKEN VANILLA DRIP', 68.00, 1, '\"vanilla\"', 100, 'Smooth vanilla cake with a glossy white drip and delicate chocolate accents.', 'A classic 5-inch vanilla cake finished with a silky white chocolate drip, topped with light whipped cream rosettes, fresh blueberries and decorative chocolate sticks. Light, elegant and perfectly balanced in sweetness.', 'Flour, sugar, eggs, butter, fresh cream, white chocolate, blueberries', '5-inch (Serves 4-6 people)', 4.7, 12, 0, '5 INCH', 'cake/Vanilla Flavour/choco vanilla.webp', '2026-01-15 03:00:13'),
(102, 'PENGUIN VANILLA DREAM', 72.00, 1, '\"vanilla\"', 100, 'Cute penguin-themed vanilla cake with blue-and-white drip effect.', 'A charming 5-inch vanilla cake featuring an adorable penguin topper, blue-to-white gradient cream and a glossy chocolate drip. Decorated with mini meringues and pearls, it’s a favourite for children’s birthdays.', 'Flour, sugar, eggs, butter, cream, fondant topper, food colouring', '5-inch (Serves 4-6 people)', 4.8, 15, 0, '5 INCH', 'cake/Vanilla Flavour/Dripping Penguin Vanilla.jpg', '2026-01-15 03:00:13'),
(103, 'VANILLA CHOCOLATE SWIRL', 66.00, 1, '\"vanilla\"', 100, 'Vanilla cake with chocolate swirl details and elegant piping.', 'This 5-inch vanilla cake features elegant chocolate-piped borders and a smooth vanilla cream centre. Garnished with berries and chocolate kisses, it offers a sophisticated look for small gatherings.', 'Flour, sugar, eggs, butter, cream, chocolate kisses, mixed berries', '5-inch (Serves 4-6 people)', 4.6, 10, 0, '5 INCH', 'cake/Vanilla Flavour/Flavorsome Vanilla Chocolate.jpg', '2026-01-15 03:00:13'),
(104, 'FLORAL VANILLA CREAM', 70.00, 1, '\"vanilla\"', 100, 'Vanilla cake decorated with floral cream piping and pastel hues.', 'A beautiful 5-inch vanilla cake adorned with hand-piped cream flowers in soft pastel tones. Light and airy, this cake is as delightful to look at as it is to eat — perfect for Mother’s Day or floral-themed celebrations.', 'Flour, sugar, eggs, butter, fresh cream, food colouring', '5-inch (Serves 4-6 people)', 4.7, 11, 0, '5 INCH', 'cake/Vanilla Flavour/Gesture of Floral Vanilla Cream.jpg', '2026-01-15 03:00:13'),
(105, 'GLOSS VANILLA GLAZE', 64.00, 1, '\"vanilla\"', 100, 'Classic vanilla cake with a shiny glaze and simple piped border.', 'A simple yet elegant 5-inch vanilla cake with a glossy glaze finish and delicate cream piping. Perfect for those who appreciate the pure and timeless flavour of premium vanilla.', 'Flour, sugar, eggs, butter, vanilla glaze, fresh cream', '5-inch (Serves 4-6 people)', 4.5, 9, 0, '5 INCH', 'cake/Vanilla Flavour/glaze vanilla.webp', '2026-01-15 03:00:13'),
(106, 'HOLI SPECIAL VANILLA', 75.00, 1, '\"vanilla\"', 100, 'Vibrant Holi-inspired vanilla cake with bright coloured drip.', 'Celebrate with this colourful 5-inch vanilla cake featuring a rainbow of drips and sprinkles. A joyful choice for any festive occasion that needs a burst of colour and sweetness.', 'Flour, sugar, eggs, butter, cream, food colouring, sprinkles', '5-inch (Serves 4-6 people)', 4.8, 14, 0, '5 INCH', 'cake/Vanilla Flavour/Holi Special Vanilla Photo.jpg', '2026-01-15 03:00:13'),
(107, 'LAVENDER VANILLA CAKE', 78.00, 1, '\"vanilla\"', 100, 'Light vanilla cake infused with subtle lavender notes.', 'A refined 5-inch vanilla cake with a hint of lavender infusion, decorated with soft purple cream rosettes and dried lavender buds. An elegant and aromatic dessert for refined tastes.', 'Flour, sugar, eggs, butter, cream, food-grade lavender, vanilla', '5-inch (Serves 4-6 people)', 4.7, 12, 0, '5 INCH', 'cake/Vanilla Flavour/Lavender Vanilla Cake.webp', '2026-01-15 03:00:13'),
(108, 'PILOT KIDS VANILLA', 72.00, 1, '\"vanilla\"', 100, 'A fun themed vanilla cake decorated with playful airplane toppers.', 'Take flight with this 5-inch vanilla cake, featuring blue sky-themed frosting and toy airplane decorations. A popular choice for little pilots and travel-themed birthdays.', 'Flour, sugar, eggs, butter, cream, plastic toppers (non-edible), food colouring', '5-inch (Serves 4-6 people)', 4.6, 13, 0, '5 INCH', 'cake/Vanilla Flavour/Pilot Travel Cake For Kids.jpg', '2026-01-15 03:00:13'),
(109, 'ROSE VANILLA ELEGANCE', 76.00, 1, '\"vanilla\"', 100, 'A minimalist layered vanilla cake finished with smooth buttercream.', 'Focusing on quality, this 5-inch vanilla cake features multiple layers of moist sponge and smooth buttercream. A clean and classic look suitable for any celebration.', 'Flour, sugar, eggs, butter, premium vanilla extract', '5-inch (Serves 4-6 people)', 4.5, 10, 0, '5 INCH', 'cake/Vanilla Flavour/rose vanilla.webp', '2026-01-15 03:00:13'),
(110, 'SNOWMAN VANILLA FUN', 65.00, 1, '\"vanilla\"', 100, 'A cheerful blue finish with adorable snowman decorations.', 'Bring the winter magic to your table with this 5-inch vanilla cake, featuring a soft blue cream exterior and a cute snowman fondant figure. Ideal for Christmas and winter birthdays.', 'Flour, sugar, eggs, butter, cream, fondant decorations', '5-inch (Serves 4-6 people)', 4.7, 11, 0, '5 INCH', 'cake/Vanilla Flavour/snowman vanilla.webp', '2026-01-15 03:00:13'),
(111, 'Durian Father\'s Day Cake', 220.00, 1, '\"durian\"', 100, 'A Father\'s Day themed durian cake topped with realistic durian flesh decoration.', 'This 5-inch durian cake is designed for Dads who love the king of fruits. Layered with premium durian pulp and decorated with realistic fondant ‘thorns’ and flesh, it’s a bold and flavourful tribute.', 'Flour, sugar, eggs, butter, durian pulp, fresh cream, fondant', '5-inch (Serves 4-6 people)', 4.9, 20, 0, '5 INCH', 'cake/Durian Series/Durian Cake 6 Inch.webp', '2026-01-15 03:07:25'),
(112, 'Realistic Spiky Durian Cake', 190.00, 1, '\"durian\"', 100, 'A hyper-realistic durian-shaped cake with detailed spiky texture.', 'An artistic masterpiece! This 5-inch cake is crafted to look exactly like a real durian. Inside, you’ll find layers of soft sponge and intense D24 durian cream. A must-try for durian enthusiasts.', 'Durian pulp, flour, sugar, eggs, butter, food colouring', '5-inch (Serves 4-6 people)', 4.9, 25, 0, '5 INCH', 'cake/Durian Series/Durian Cake.webp', '2026-01-15 03:00:13'),
(113, 'Durian Lover Mini Cake', 138.00, 1, '\"durian\"', 100, 'A delightful mini-sized durian cake layered with creamy durian filling.', 'The perfect individual treat! This petite durian cake features rich pulp between light sponge layers, finished with a smooth cream crown. Great for gifts or personal cravings.', 'Durian pulp, flour, sugar, eggs, butter, fresh cream', 'Petite (Serves 1-2)', 4.8, 18, 1, 'MINI', 'cake/Durian Series/DURIAN LOVER.webp', '2026-01-15 03:00:13'),
(114, 'Mini Brown Bear For You Cake', 39.90, 1, '\"mini\"', 100, 'Cute mini chocolate cream cake with a 3D brown bear.', 'A 3-inch mini chocolate cake covered in smooth cocoa cream, topped with an adorable brown bear figure, fresh strawberry accents and crunchy chocolate pearls. Perfect for small celebrations, gifts or as a personal treat.', 'Flour, sugar, eggs, butter, cocoa powder, chocolate, fresh cream, strawberry, chocolate pearls', '3-inch', 4.6, 21, 0, '3 INCH', 'cake/Cute Mini Cake/CUTE MINI BROWN.webp', '2026-01-15 03:00:13');
INSERT INTO `products` (`id`, `name`, `price`, `category_id`, `subcategory`, `stock`, `description`, `full_description`, `ingredients`, `size`, `rating`, `review_count`, `sold_count`, `size_info`, `image`, `created_at`) VALUES
(115, 'Mini Purple Cat Strawberry Cake', 39.90, 1, '\"mini\"', 100, 'Pastel purple mini cake topped with a black cat and fresh strawberries.', 'A 3-inch mini sponge cake coated in pastel purple cream, decorated with a charming black cat topper, whipped rosette border and juicy strawberries dusted with sugar. Ideal for cat lovers and small birthday surprises.', 'Flour, sugar, eggs, butter, cream, strawberries, fondant decorations', '3-inch', 4.7, 18, 0, '3 INCH', 'cake/Cute Mini Cake/CUTE MINI CAKE PURPLE.webp', '2026-01-15 03:00:13'),
(116, 'Mini Lazy Egg Drip Cake', 39.90, 1, '\"mini\"', 100, 'Yellow mini drip cake with a lazy egg character and pink hearts.', 'A fun 3-inch mini cake frosted in pale yellow cream with rich chocolate drip, topped with a sleepy egg character, fresh strawberry and shimmering heart toppers. A playful design for birthdays and casual celebrations.', 'Flour, sugar, eggs, butter, chocolate, fresh cream, strawberry, fondant decorations', '3-inch', 4.5, 16, 0, '3 INCH', 'cake/Cute Mini Cake/CUTE MINI CAKE YELLOW.webp', '2026-01-15 03:00:13'),
(117, 'Mini Oreo Brown Bear Cake', 39.90, 1, '\"mini\"', 100, 'Cookies & cream mini cake with a chocolate bear and Oreo pieces.', 'A 3-inch cookies-and-cream style mini cake packed with crunchy biscuit crumbs, topped with a chocolate brown bear, Oreo chunks and chocolate cubes. Great for Oreo lovers who want a small, indulgent treat.', 'Flour, sugar, eggs, butter, chocolate, Oreo biscuits, fresh cream', '3-inch', 4.6, 19, 0, '3 INCH', 'cake/Cute Mini Cake/CUTE MINI DARK BISCUIT.webp', '2026-01-15 03:00:13'),
(118, 'Mini Pink Bunny Cup Cake', 39.90, 1, '\"mini\"', 100, 'Pink mug-shaped mini cake with a bunny, lollipop and macarons.', 'A creative 3-inch mini cake designed as a pink cup filled with whipped cream, a cute sleeping bunny, heart sprinkles and a lollipop topper, finished with two mini macarons at the base. Perfect for kids and kawaii-style celebrations.', 'Flour, sugar, eggs, butter, cream, macarons, fondant decorations', '3-inch', 4.7, 22, 0, '3 INCH', 'cake/Cute Mini Cake/CUTE MINI PINK.webp', '2026-01-15 03:00:13'),
(119, 'Curly Sheep Animal Cake', 138.00, 1, '\"animal\"', 100, 'Fluffy sheep-themed cake with curly cream texture.', 'A delightful 5-inch animal cake featuring hand-piped cream wool and cute sheep features. Soft, airy and perfect for animal-themed parties.', 'Flour, sugar, eggs, butter, fresh cream, fondant', '5-inch (Serves 4-6 people)', 4.8, 14, 0, '5 INCH', 'cake/The Animal Series/Mini Character Design Cake 3 Inch - Curly Sheep.webp', '2026-01-15 03:00:13'),
(120, 'Green Dinosaur Animal Cake', 138.00, 1, '\"animal\"', 100, 'Bright green dinosaur cake decorated with colourful spikes.', 'Roar into fun with this 5-inch dinosaur cake. Featuring vibrant green frosting, chocolate spikes and a friendly face.', 'Flour, sugar, eggs, butter, cream, chocolate, food colouring', '5-inch (Serves 4-6 people)', 4.7, 12, 0, '5 INCH', 'cake/The Animal Series/Mini Character Design Cake 3 Inch - Green Dinosaur.webp', '2026-01-15 03:00:13'),
(121, 'Green Snake Animal Cake', 138.00, 1, '\"animal\"', 100, 'Mint green snake cake with playful spots.', 'A unique and fun 5-inch snake-themed cake. Coiled in a friendly design with pastel green cream and edible spots.', 'Flour, sugar, eggs, butter, fresh cream, food colouring', '5-inch (Serves 4-6 people)', 4.6, 10, 0, '5 INCH', 'cake/The Animal Series/Mini Character Design Cake 3 Inch - Green Snake.webp', '2026-01-15 03:00:13'),
(122, 'Grey Mouse Animal Cake', 138.00, 1, '\"animal\"', 100, 'Soft grey mouse cake with rounded ears.', 'This adorable 5-inch mouse cake features soft grey frosting, large rounded ears and a sweet pink nose.', 'Flour, sugar, eggs, butter, fresh cream, fondant', '5-inch (Serves 4-6 people)', 4.8, 11, 0, '5 INCH', 'cake/The Animal Series/Mini Character Design Cake 3 Inch - Grey Mouse.webp', '2026-01-15 03:00:13'),
(123, 'Milk Tea Animal Cake', 138.00, 1, '\"animal\"', 100, 'Bubble milk tea inspired cake with boba pearls.', 'The perfect cake for milk tea fans! This 5-inch cake mimics a cup of bubble tea, complete with milk tea-infused cream and chocolate boba pearls.', 'Flour, sugar, eggs, butter, tea-infused cream, chocolate pearls', '5-inch (Serves 4-6 people)', 4.9, 18, 0, '5 INCH', 'cake/The Animal Series/Mini Character Design Cake 3 Inch - Milk Tea.webp', '2026-01-15 03:00:13'),
(124, 'Moo Moo Cow Animal Cake', 138.00, 1, '\"animal\"', 100, 'Classic black-and-white cow cake with soft cream texture.', 'A cute 5-inch Moo Moo cow cake with signature black-and-white spots. Soft cream exterior and a light vanilla interior.', 'Flour, sugar, eggs, butter, fresh cream, cocoa powder', '5-inch (Serves 4-6 people)', 4.7, 13, 0, '5 INCH', 'cake/The Animal Series/Mini Character Design Cake 3 Inch - Moo Moo Cow.webp', '2026-01-15 03:00:13'),
(125, 'Orange Tiger Animal Cake', 138.00, 1, '\"animal\"', 100, 'Bright orange tiger cake with playful stripes.', 'A bold 5-inch tiger cake featuring vibrant orange frosting and chocolate stripes. Perfect for jungle-themed parties.', 'Flour, sugar, eggs, butter, fresh cream, chocolate, food colouring', '5-inch (Serves 4-6 people)', 4.8, 15, 0, '5 INCH', 'cake/The Animal Series/Mini Character Design Cake 3 Inch - Orange Tiger.webp', '2026-01-15 03:00:13'),
(126, 'Oreo Monster Animal Cake', 138.00, 1, '\"animal\"', 100, 'Cookies-and-cream monster cake with dramatic drip icing.', 'This 5-inch cookies-and-cream monster cake is as fun as it is tasty. With big eyes, blue fur and an Oreo in its mouth.', 'Flour, sugar, eggs, butter, cream, Oreos, food colouring', '5-inch (Serves 4-6 people)', 4.7, 16, 0, '5 INCH', 'cake/The Animal Series/Mini Character Design Cake 3 Inch - Oreo Monster.webp', '2026-01-15 03:00:13'),
(127, 'Pastel Unicorn Animal Cake', 138.00, 1, '\"animal\"', 100, 'Dreamy unicorn cake with pastel rosette mane and a delicate golden horn.', 'Magical and enchanting! This 5-inch unicorn cake features a rainbow-piped mane, shimmering golden horn and delicate eyelashes.', 'Flour, sugar, eggs, butter, fresh cream, fondant horn, food colouring', '5-inch (Serves 4-6 people)', 4.9, 22, 0, '5 INCH', 'cake/The Animal Series/Mini Character Design Cake 3 Inch - Pastel Unicorn.webp', '2026-01-15 03:07:25'),
(128, 'Pink Piggy Animal Cake', 138.00, 1, '\"animal\"', 100, 'Soft pink pig cake with rounded ears and tiny snout.', 'This 5-inch pink piggy cake is simple, sweet and incredibly cute. Coated in light strawberry-vanilla cream.', 'Flour, sugar, eggs, butter, cream, strawberry puree', '5-inch (Serves 4-6 people)', 4.7, 11, 0, '5 INCH', 'cake/The Animal Series/Mini Character Design Cake 3 Inch - Pink Pig.webp', '2026-01-15 03:00:13'),
(129, 'White Rabbit Animal Cake', 138.00, 1, '\"animal\"', 100, 'Elegant white rabbit cake with long ears.', 'A pure and lovely 5-inch white rabbit cake. Featuring long fondant ears and a fluffy cream tail.', 'Flour, sugar, eggs, butter, fresh cream, fondant ears', '5-inch (Serves 4-6 people)', 4.8, 12, 0, '5 INCH', 'cake/The Animal Series/Mini Character Design Cake 3 Inch - White Rabbit.webp', '2026-01-15 03:00:13'),
(130, 'Yellow Chick Animal Cake', 138.00, 1, '\"animal\"', 100, 'A joyful 5-inch chick animal cake decorated in vibrant yellow cream.', 'Brighten up your day with this sunny 5-inch chick cake. With its tiny orange beak and cheerful expression.', 'Flour, sugar, eggs, butter, fresh cream, food colouring', '5-inch (Serves 4-6 people)', 4.6, 10, 0, '5 INCH', 'cake/The Animal Series/Mini Character Design Cake 3 Inch - Yellow Chick.webp', '2026-01-15 03:00:13'),
(131, 'Pink Elegant Floral Fondant Cake', 320.00, 1, '\"fondant\"', 100, 'Elegant pink floral fondant cake perfect for birthday celebrations.', 'A sophisticated 6-inch fondant-covered cake adorned with handcrafted pink sugar flowers. Elegant, smooth and perfect for birthdays.', 'Flour, sugar, eggs, butter, fondant, sugar pearls, edible gold leaf', '3-inch', 4.9, 18, 0, '3 INCH', 'cake/Fondant Cake Design/FD056.webp', '2026-01-15 03:00:13'),
(132, 'Dinosaur Birthday Fondant Cake', 280.00, 1, '\"fondant\"', 100, 'Cute dinosaur themed fondant cake specially designed for kids.', 'Bring the Jurassic world to life with this detailed fondant cake. Featuring a handmade dinosaur topper and tropical forest decorations.', 'Flour, sugar, eggs, butter, fondant, food colouring', '3-inch', 4.8, 15, 0, '3 INCH', 'cake/Fondant Cake Design/FD057.webp', '2026-01-15 03:00:13'),
(133, 'Luxury Car Birthday Fondant Cake', 360.00, 1, '\"fondant\"', 100, 'Premium handmade fondant cake featuring luxury car theme.', 'A high-end 6-inch fondant cake for car enthusiasts. Features a handcrafted luxury car topper and sleek, modern finish.', 'Flour, sugar, eggs, butter, fondant, edible silver paint', '3-inch', 4.9, 12, 0, '3 INCH', 'cake/Fondant Cake Design/FD067.webp', '2026-01-15 03:00:13'),
(134, 'Unicorn Rainbow Fondant Cake', 340.00, 1, '\"fondant\"', 100, 'Magical rainbow unicorn fondant cake perfect for dreamy celebrations.', 'A stunning rainbow-themed fondant cake crowned with a detailed unicorn topper. Shimmering colors and magical details.', 'Flour, sugar, eggs, butter, fondant, edible glitter', '3-inch', 4.9, 20, 0, '3 INCH', 'cake/Fondant Cake Design/FD069.webp', '2026-01-15 03:00:13'),
(135, 'Sweet Bunny Night Fondant Cake', 300.00, 1, '\"fondant\"', 100, 'Adorable bunny themed fondant cake with moon and star design.', 'A dreamy 6-inch cake featuring a sleeping bunny on a crescent moon, surrounded by golden stars.', 'Flour, sugar, eggs, butter, fondant, food colouring', '3-inch', 4.8, 14, 0, '3 INCH', 'cake/Fondant Cake Design/FD075.webp', '2026-01-15 03:00:13'),
(136, 'Birthday Bear Fondant Cake', 280.00, 1, '\"fondant\"', 100, 'Lovely bear fondant cake perfect for kids and couple celebrations.', 'A sweet 6-inch fondant cake featuring a cute teddy bear holding a heart. Simple, clean and heartwarming.', 'Flour, sugar, eggs, butter, fondant', '3-inch', 4.7, 11, 0, '3 INCH', 'cake/Fondant Cake Design/FD078.webp', '2026-01-15 03:00:13'),
(137, 'Plants vs Zombie Fondant Cake', 350.00, 1, '\"fondant\"', 100, 'Fun and creative Plants vs Zombie themed fondant cake.', 'A fun and detailed 6-inch cake inspired by Plants vs Zombies. Features Peashooters, Zombies and Sunflowers handcrafted in fondant.', 'Flour, sugar, eggs, butter, fondant, food colouring', '3-inch', 4.8, 13, 0, '3 INCH', 'cake/Fondant Cake Design/FD081.webp', '2026-01-15 03:00:13'),
(138, 'Baby Airplane Fondant Cake', 330.00, 1, '\"fondant\"', 100, 'Cute baby airplane theme fondant cake', 'A sky-blue fondant cake featuring a handmade baby airplane and fluffy cloud decorations. Perfect for baby showers.', 'Flour, sugar, eggs, butter, fondant, vanilla', '3-inch', 4.7, 9, 0, '3 INCH', 'cake/Fondant Cake Design/FD084.webp', '2026-01-15 03:07:25'),
(139, 'Animal Friends Fondant Cake', 300.00, 1, '\"fondant\"', 100, 'Lovely animal themed fondant cake.', 'A colourful garden-themed cake featuring various animal toppers like lions, giraffes and monkeys.', 'Flour, sugar, eggs, butter, fondant, food colouring', '3-inch', 4.8, 10, 0, '3 INCH', 'cake/Fondant Cake Design/FD086.webp', '2026-01-15 02:58:51'),
(140, 'Cute Baby One Year Fondant Cake', 360.00, 1, '\"fondant\"', 100, 'Premium baby first birthday fondant cake with cute cartoon design.', 'Celebrate the big number ONE with this premium fondant cake. Featuring delicate pastel accents and a cute character topper.', 'Flour, sugar, eggs, butter, fondant, sugar pearls', '3-inch', 4.9, 16, 0, '3 INCH', 'cake/Fondant Cake Design/FD087.webp', '2026-01-15 03:00:13'),
(141, 'International Travel Airplane Fondant Cake', 360.00, 1, '\"fondant\"', 100, 'Premium travel fondant cake with airplane and flag decorations.', 'For the globetrotters! This detailed cake features a globe design, mini airplane and flags from around the world.', 'Flour, sugar, eggs, butter, fondant, edible paint', '3-inch', 4.9, 14, 0, '3 INCH', 'cake/Fondant Cake Design/FD088.webp', '2026-01-15 03:00:13'),
(142, 'Cute Bear Girl Fondant Cake', 320.00, 1, '\"fondant\"', 100, 'Premium cute bear girl fondant cake with pastel colors.', 'A beautiful pastel pink fondant cake featuring a cute bear girl wearing a bow. Soft, dreamy and perfect for girls.', 'Flour, sugar, eggs, butter, fondant', '3-inch', 4.8, 12, 0, '3 INCH', 'cake/Fondant Cake Design/FD089.webp', '2026-01-15 03:00:13'),
(143, 'Kungfu Panda Bamboo Fondant Cake', 320.00, 1, '\"fondant\"', 100, 'Premium kungfu panda inspired fondant cake with bamboo forest.', 'Unleash the dragon warrior! This cake features a Kung Fu Panda topper in a bamboo forest made of fondant.', 'Flour, sugar, eggs, butter, fondant, green tea extract', '3-inch', 4.8, 11, 0, '3 INCH', 'cake/Fondant Cake Design/FD090.webp', '2026-01-15 03:00:13'),
(144, 'Captain America Superhero Fondant Cake', 360.00, 1, '\"fondant\"', 100, 'Premium Captain America superhero fondant cake.', 'A heroic cake featuring the Captain America shield and iconic patriotic colors. Made with smooth fondant.', 'Flour, sugar, eggs, butter, fondant, food colouring', '3-inch', 4.9, 15, 0, '3 INCH', 'cake/Fondant Cake Design/FD091.webp', '2026-01-15 03:00:13'),
(145, 'Luxury Bow Brand Style Fondant Cake', 330.00, 1, '\"fondant\"', 100, 'Premium luxury style fondant cake with elegant bow.', 'A high-fashion inspired fondant cake in quilted pattern with a large, elegant bow.', 'Flour, sugar, eggs, butter, fondant, sugar pearls', '3-inch', 4.8, 13, 0, '3 INCH', 'cake/Fondant Cake Design/FD092.webp', '2026-01-15 03:00:13'),
(146, 'Baby Elephant Forest Fondant Cake', 320.00, 1, '\"fondant\"', 100, 'Premium baby elephant fondant cake with forest leaves.', 'A charming 6-inch cake featuring a baby elephant holding a flower, surrounded by green fondant leaves.', 'Flour, sugar, eggs, butter, fondant', '3-inch', 4.7, 10, 0, '3 INCH', 'cake/Fondant Cake Design/FD093.webp', '2026-01-15 03:00:13'),
(147, 'Demon Slayer Anime Fondant Cake', 360.00, 1, '\"fondant\"', 100, 'Premium Demon Slayer inspired fondant cake with detailed character design.', 'A must-have for anime fans! This 6-inch fondant cake features iconic patterns and handcrafted character details from Demon Slayer.', 'Flour, sugar, eggs, butter, fondant, food colouring', '3-inch', 5.0, 20, 0, '3 INCH', 'cake/Fondant Cake Design/FD094.webp', '2026-01-15 03:00:13'),
(148, 'Hot Air Balloon Baby Girl Fondant Cake', 330.00, 1, '\"fondant\"', 100, 'Premium baby girl fondant cake with hot air balloon.', 'Floating on clouds! This dreamy fondant cake features a hot air balloon topper and soft pink hues.', 'Flour, sugar, eggs, butter, fondant', '3-inch', 4.8, 15, 0, '3 INCH', 'cake/Fondant Cake Design/FD095.webp', '2026-01-15 03:00:13'),
(149, 'Thomas Train Birthday Fondant Cake', 340.00, 1, '\"fondant\"', 100, 'Premium Thomas train fondant cake with railway track.', 'Choo-choo! A fun 6-inch fondant cake featuring Thomas the Tank Engine and a railway track.', 'Flour, sugar, eggs, butter, fondant, food colouring', '3-inch', 4.7, 12, 0, '3 INCH', 'cake/Fondant Cake Design/FD096.webp', '2026-01-15 03:00:13'),
(150, 'Firefighter Truck Birthday Fondant Cake', 340.00, 1, '\"fondant\"', 100, 'Premium firefighter fondant cake with fire truck and water splash.', 'A heroic firefighter-themed cake featuring a handcrafted fire truck and water decorations.', 'Flour, sugar, eggs, butter, fondant, food colouring', '3-inch', 4.8, 11, 0, '3 INCH', 'cake/Fondant Cake Design/FD097.webp', '2026-01-15 03:00:13'),
(151, 'Frozen Elsa Princess Fondant Cake', 320.00, 1, '\"fondant\"', 100, 'Beautiful Frozen Elsa themed fondant cake with elegant blue princess design.', 'Beautiful Frozen Elsa themed fondant cake with elegant blue princess design and snowflake accents.', 'Flour, sugar, eggs, butter, fondant, food colouring', '3-inch', 4.9, 22, 0, '3 INCH', 'cake/Fondant Cake Design/FD098.webp', '2026-01-15 03:07:37'),
(152, 'Roblox Theme Birthday Fondant Cake', 300.00, 1, '\"fondant\"', 100, 'Fun and colorful Roblox themed fondant cake with playful block-style characters.', 'Fun and colorful Roblox themed fondant cake with playful block-style characters.', 'Flour, sugar, eggs, butter, fondant', '3-inch', 4.7, 15, 0, '3 INCH', 'cake/Fondant Cake Design/FD099.webp', '2026-01-15 03:07:37'),
(153, 'Harry Potter Hedwig Fondant Cake', 360.00, 1, '\"fondant\"', 100, 'Elegant Harry Potter themed fondant cake featuring the iconic Hedwig owl design.', 'Elegant Harry Potter themed fondant cake featuring the iconic Hedwig owl design and magical props.', 'Flour, sugar, eggs, butter, fondant', '3-inch', 5.0, 30, 0, '3 INCH', 'cake/Fondant Cake Design/FD100.webp', '2026-01-15 03:07:37'),
(154, 'Snoopy Strawberry Fresh Cream Cake', 150.00, 1, '\"fresh-cream\"', 100, 'Round fresh cream cake with Snoopy illustration and strawberry base.', 'Cute Cinnamoroll themed fondant cake with soft blue clouds and stars.', 'Flour, sugar, eggs, butter, fondant', '3-inch', 4.8, 19, 0, '3 INCH', 'cake/Fresh Cream Cake/FK146.webp', '2026-01-15 03:07:37'),
(155, 'Galaxy Astronaut Fresh Cream Cake', 280.00, 1, '\"fresh-cream\"', 100, 'Galaxy-themed fresh cream cake topped with an astronaut and planets.', 'Galaxy-themed fresh cream cake topped with an astronaut and planets for space lovers.', 'Flour, sugar, eggs, butter, fresh cream, fondant topper', '6-inch', 4.9, 25, 0, '6 INCH', 'cake/Fresh Cream Cake/FK158.webp', '2026-01-15 03:07:37'),
(156, 'Angella Bunny Music Fresh Cream Cake', 220.00, 1, '\"fresh-cream\"', 100, 'Pastel bunny fresh cream cake with guitar and cloud decorations.', 'A soft and creamy bunny-themed cake featuring Angella the Bunny with musical notes and edible carrot decorations.', 'Flour, sugar, eggs, butter, fresh cream, vanilla, chocolate accents', '6-inch (Serves 6-8 people)', 4.7, 14, 22, '6 INCH', 'cake/Fresh Cream Cake/FK165.webp', '2026-01-15 03:07:37'),
(157, 'Doraemon Sphere Fresh Cream Cake', 260.00, 1, '\"fresh-cream\"', 100, '3D Doraemon-shaped fresh cream cake with hammer accessory.', 'This adorable pink piglet cake is decorated with smooth strawberry-infused cream and detailed fondant features.', 'Flour, sugar, eggs, butter, strawberry cream, fondant', '6-inch (Serves 6-8 people)', 4.6, 10, 0, '6 INCH', 'cake/Fresh Cream Cake/FK166.webp', '2026-01-15 03:07:37'),
(158, 'Bear Friends Celebration Fresh Cream Cake', 240.00, 1, '\"fresh-cream\"', 100, 'Round fresh cream cake with giant bear and panda friends topper.', 'A sunny yellow chick cake that brings instant happiness. Made with airy sponge and light vanilla cream.', 'Flour, sugar, eggs, butter, fresh cream, food coloring', '6-inch (Serves 6-8 people)', 4.8, 12, 0, '6 INCH', 'cake/Fresh Cream Cake/FK169.webp', '2026-01-15 03:07:37'),
(159, 'Giant Ferrero Rocher Fresh Cream Cake', 310.00, 1, '\"fresh-cream\"', 100, 'Oversized Ferrero Rocher-inspired chocolate fresh cream cake.', 'This realistic puppy cake is crafted with multiple layers of whipped cream to simulate fur. A delightful surprise for dog lovers.', 'Flour, sugar, eggs, butter, fresh cream, chocolate details', '6-inch (Serves 6-8 people)', 4.9, 22, 0, '6 INCH', 'cake/Fresh Cream Cake/FK175.webp', '2026-01-15 03:07:37'),
(160, 'Blue Planet Sphere Fresh Cream Cake', 290.00, 1, '\"fresh-cream\"', 100, 'Blue planet sphere fresh cream cake with golden orbits.', 'A romantic heart-shaped cake featuring delicate pink rosettes, sweet macarons, and edible flowers.', 'Flour, sugar, eggs, butter, fresh cream, macarons, floral decor', '6-inch (Serves 6-8 people)', 4.7, 12, 0, '6 INCH', 'cake/Fresh Cream Cake/FK176.webp', '2026-01-15 03:07:37'),
(161, 'Among Us Galaxy Fresh Cream Cake', 300.00, 1, '\"fresh-cream\"', 98, 'Galaxy planet fresh cream cake with Among Us characters.', 'Add magic to your party with this enchanted unicorn cake. Features pastel cream rosettes and a handmade edible horn.', 'Flour, sugar, eggs, butter, fresh cream, fondant horn', '6-inch (Serves 6-8 people)', 4.9, 31, 42, '6 INCH', 'cake/Fresh Cream Cake/FK179.webp', '2026-01-15 03:07:37'),
(162, 'Yellow Bear Smash Fresh Cream Cake', 180.00, 1, '\"fresh-cream\"', 100, 'Cute yellow bear smash-style fresh cream cake with hammer.', 'Roar with delight! This king-of-the-jungle cake features a textured orange mane and a golden crown topper.', 'Flour, sugar, eggs, butter, fresh cream, chocolate crown', '6-inch (Serves 6-8 people)', 4.8, 18, 0, '6 INCH', 'cake/Fresh Cream Cake/FK181.webp', '2026-01-15 03:07:37'),
(163, 'Purple Bunny Smash Fresh Cream Cake', 200.00, 1, '\"fresh-cream\"', 100, 'Purple bunny smash-style fresh cream cake with bow and gift.', 'A dreamy forest-themed cake featuring a sleeping fox with a delicate crown of cream flowers.', 'Flour, sugar, eggs, butter, fresh cream, food coloring', '6-inch (Serves 6-8 people)', 4.7, 11, 0, '6 INCH', 'cake/Fresh Cream Cake/FK182.webp', '2026-01-15 03:07:37'),
(164, 'Sailor Moon Smash Fresh Cream Cake', 208.00, 1, '\"fresh-cream\"', 100, 'Sailor Moon themed smash fresh cream cake with magical wand and princess bow.', 'This adorable sheep cake is covered in tiny whipped cream curls for a fluffy effect.', 'Flour, sugar, eggs, butter, fresh cream, fondant', '6-inch (Serves 6-8 people)', 4.8, 14, 0, '6 INCH', 'cake/Fresh Cream Cake/FK183.webp', '2026-01-15 03:07:37'),
(165, 'Unicorn Rose Smash Fresh Cream Cake', 215.00, 1, '\"fresh-cream\"', 100, 'Pastel unicorn smash fresh cream cake with floral decorations and golden horn.', 'A royal celebration cake featuring smooth ombré blue frosting and a beautiful princess topper.', 'Flour, sugar, eggs, butter, fresh cream, princess figurine', '6-inch (Serves 6-8 people)', 4.9, 20, 0, '6 INCH', 'cake/Fresh Cream Cake/FK184.webp', '2026-01-15 03:07:37'),
(166, 'Earth Globe Smash Fresh Cream Cake', 225.00, 1, '\"fresh-cream\"', 100, 'Planet-themed smash fresh cream cake with globe design and cloud decorations.', 'Unleash your wild side with this fun tiger cake. Bold orange and black details make it a standout.', 'Flour, sugar, eggs, butter, fresh cream, cocoa stripes', '6-inch (Serves 6-8 people)', 4.8, 15, 0, '6 INCH', 'cake/Fresh Cream Cake/FK185.webp', '2026-01-15 03:07:37'),
(167, 'Butterfly Princess Smash Fresh Cream Cake', 400.00, 1, '\"fresh-cream\"', 99, 'Elegant butterfly smash fresh cream cake with three-tier princess design.', 'This sweet little mouse cake is simple and elegant. Features large fondant ears and a soft grey cream exterior.', 'Flour, sugar, eggs, butter, fresh cream, fondant ears', '6-inch (Serves 6-8 people)', 4.7, 10, 0, '6 INCH', 'cake/Fresh Cream Cake/FK186.webp', '2026-01-15 03:07:37'),
(168, 'Purple Chocolate Drip Smash Fresh Cream Cake', 218.00, 1, '\"fresh-cream\"', 100, 'Purple gradient smash fresh cream cake with chocolate drip and premium toppings.', 'A monster-sized treat! This messy blue cake is packed with Oreo crumbs and topped with googly eyes.', 'Flour, sugar, eggs, butter, fresh cream, Oreos, cookie', '6-inch (Serves 6-8 people)', 4.8, 25, 0, '6 INCH', 'cake/Fresh Cream Cake/FK187.webp', '2026-01-15 03:07:37'),
(169, 'Cartoon Tractor Smash Fresh Cream Cake', 198.00, 1, '\"fresh-cream\"', 100, 'Cute cartoon tractor themed smash fresh cream cake for kids birthday.', 'Take a bite out of history! This vibrant green dinosaur cake is fun, playful, and perfect for adventurous kids.', 'Flour, sugar, eggs, butter, fresh cream, food coloring', '6-inch (Serves 6-8 people)', 4.7, 13, 0, '6 INCH', 'cake/Fresh Cream Cake/FK188.webp', '2026-01-15 03:07:37'),
(170, 'Mouse One-Year Smash Fresh Cream Cake', 195.00, 1, '\"fresh-cream\"', 100, 'One-year-old mouse themed smash fresh cream cake with cheese elements.', 'Celebrate with the purity of a white rabbit cake. Clean lines and long fondant ears make it an elegant choice.', 'Flour, sugar, eggs, butter, fresh cream, fondant ears', '6-inch (Serves 6-8 people)', 4.8, 16, 0, '6 INCH', 'cake/Fresh Cream Cake/FK189.webp', '2026-01-15 03:07:37'),
(171, 'Baby Rainbow Friends Smash Fresh Cream Cake', 228.00, 1, '\"fresh-cream\"', 99, 'Baby rainbow themed smash fresh cream cake with animal characters.', 'A powerful and detailed dragon cake representing strength and luck. Decorated with vibrant reds and golden accents.', 'Flour, sugar, eggs, butter, fresh cream, fondant scales', '6-inch (Serves 6-8 people)', 5.0, 35, 0, '6 INCH', 'cake/Fresh Cream Cake/FK190.webp', '2026-01-15 03:07:37'),
(172, 'Pikachu Family Smash Fresh Cream Cake', 248.00, 1, '\"fresh-cream\"', 100, 'Pokemon Pikachu family smash fresh cream cake with gold decorations.', 'Pokemon Pikachu family smash fresh cream cake with gold decorations and a surprise hammer.', 'Flour, sugar, eggs, fresh cream, chocolate shell', '6-inch', 4.9, 42, 0, '6 INCH', 'cake/Fresh Cream Cake/FK191.webp', '2026-01-15 03:07:37'),
(173, 'Prosperity Tiger Smash Fresh Cream Cake', 258.00, 1, '\"fresh-cream\"', 100, 'Chinese prosperity tiger themed smash fresh cream cake with gold coins.', 'Money-themed fresh cream cake with edible dollar prints and chocolate coins.', 'Flour, sugar, eggs, fresh cream, chocolate, edible paper', '6-inch', 4.8, 31, 0, '6 INCH', 'cake/Fresh Cream Cake/FK195.webp', '2026-01-15 03:07:37'),
(174, 'Christmas Cottage Festival Cake', 268.00, 1, '\"festival\"', 100, 'Festive Christmas cottage cake decorated with Santa, Christmas tree and toy train.', 'A warm and festive Christmas cottage cake with chocolate trees and a toy train.', 'Flour, eggs, sugar, butter, fresh cream, chocolate decorations', '6-inch', 4.8, 36, 0, '6 INCH', 'cake/Festival/Christmas Cottage Cake 6 Inch.webp', '2026-01-15 03:07:37'),
(175, 'Floral Memorial Chrysanthemum Cake', 198.00, 1, '\"festival\"', 100, 'Elegant floral memorial cake topped with white and yellow chrysanthemum flowers.', 'Elegant floral memorial cake with chrysanthemum decorations.', 'Flour, eggs, sugar, butter, fresh cream, vanilla', '6-inch', 4.6, 22, 0, '6 INCH', 'cake/Festival/FLORAL MEMORIAL.webp', '2026-01-15 03:07:37'),
(176, 'Gold Ingot Prosperity Cake', 238.00, 1, '\"festival\"', 100, 'Golden ingot-shaped cake decorated with chocolate coins and prosperity symbol.', 'Golden ingot-shaped cake decorated with chocolate coins for Chinese New Year prosperity.', 'Flour, eggs, sugar, butter, fresh cream, chocolate, edible gold colouring, fondant', '6-inch', 4.8, 31, 0, '6 INCH', 'cake/Festival/GOLD INGOT.webp', '2026-01-15 03:07:37'),
(177, 'Halloween Spooky Spider Cake', 198.00, 1, '\"festival\"', 100, 'Bright orange Halloween cake decorated with spooky spiders and chocolate drips.', 'A vibrant Halloween cake in bright orange tones featuring creepy chocolate spider hangings.', 'Flour, eggs, sugar, butter, fresh cream, cocoa, chocolate, fondant decorations', '6-inch', 4.7, 27, 0, '6 INCH', 'cake/Festival/HALLOWEEN 2024.webp', '2026-01-15 03:07:37'),
(178, 'Heart of Love Rose Cake', 258.00, 1, '\"festival\"', 100, 'Romantic heart-shaped cake fully covered with red buttercream roses.', 'A stunning heart-shaped cake decorated with hand-piped red buttercream roses.', 'Flour, eggs, sugar, butter, fresh cream, vanilla, food colouring', '6-inch', 4.9, 43, 0, '6 INCH', 'cake/Festival/HEART OF LOVE.webp', '2026-01-15 03:07:37'),
(179, 'Horror Happy Hour Pumpkin Bunny Cake', 248.00, 1, '\"festival\"', 100, 'Pumpkin-style Halloween cake topped with playful bunny monster character.', 'Traditional Longevity Peach cake for elderly birthdays, symbolising health and long life.', 'Flour, sugar, eggs, butter, fresh cream, lotus paste (optional), fondant peach', '8-inch', 4.7, 45, 0, '8 INCH', 'cake/Festival/HORROR HAPPY HOUR.webp', '2026-01-15 03:07:37'),
(180, 'King\'s World Currency Prosperity Cake', 288.00, 1, '\"festival\"', 100, 'Triple-tone money-themed cake decorated with global currency symbols.', 'Prosperity Money Bag cake filled with chocolate coins, perfect for business openings and CNY.', 'Flour, sugar, eggs, butter, fresh cream, chocolate coins, fondant', '6-inch', 4.8, 31, 0, '6 INCH', 'cake/Festival/KING\'S.webp', '2026-01-15 03:07:37'),
(181, 'Kopi Gao Gao Father\'s Day Mug Cake', 268.00, 1, '\"festival\"', 100, 'Classic kopi mug cake with biscuits and Father\'s Day topper.', 'Treat yourself like royalty with this 3D crown cake. Featuring a rich purple quilted fondant base.', 'Flour, eggs, sugar, butter, fondant, edible pearls', '6-inch', 4.9, 24, 0, '6 INCH', 'cake/Festival/KOPI GAO GAO.webp', '2026-01-15 03:08:06'),
(182, 'Mack Daddy Black Gold Whiskey Cake', 298.00, 1, '\"festival\"', 100, 'Bold black and gold drip cake topped with whiskey bottle decorations.', 'Bold black and gold drip cake topped with whiskey bottle decorations.', 'Flour, cocoa, eggs, butter, chocolate, decorative bottle', '6-inch', 4.9, 28, 0, '6 INCH', 'cake/Festival/MACK DADDY.webp', '2026-01-15 03:08:06'),
(183, 'Money Huatt Mahjong Treasure Cake', 288.00, 1, '\"festival\"', 100, 'Prosperity chocolate drip cake decorated with gold mahjong tiles and coins.', 'Super Dad themed cake with a cape and shield design for Father\'s Day.', 'Flour, sugar, eggs, fresh cream, fondant', '6-inch', 4.9, 40, 0, '6 INCH', 'cake/Festival/MONEY HUATT.webp', '2026-01-15 03:08:06'),
(184, 'I Love Daddy Nutty Festival Cake', 178.00, 1, '\"festival\"', 100, 'Elegant purple-toned festival cake with nuts and blueberries.', 'Celebrate in style with this sharp tuxedo-themed cake. Designed with a black fondant coat.', 'Flour, eggs, sugar, butter, fondant details', '6-inch', 4.8, 33, 0, '6 INCH', 'cake/Festival/MR MOUSTACHE.webp', '2026-01-15 03:08:06'),
(185, '3D Red Velvet Christmas Tree Cake', 258.00, 1, '\"festival\"', 99, 'A spectacular 3D Christmas tree cake.', 'A whimsical 3D red velvet cake shaped like a cozy cottage, covered in a thick layer of snowy white frosting.', 'Flour, cocoa powder, buttermilk, sugar, butter, cream cheese frosting', '6-inch', 4.9, 26, 34, '6 INCH', 'cake/Festival/Red Velvet 3D Christmas Tree Cake.jpg', '2026-01-15 02:58:51'),
(186, 'Snowy Christmas Tree Buttercream Cake', 188.00, 1, '\"festival\"', 100, 'Snowy white buttercream cake with a piped Christmas tree design.', 'Our Halloween pumpkin cake features a vibrant orange textured exterior and spooky carved-face details.', 'Flour, pumpkin puree, eggs, sugar, spice blend, fresh cream', '6-inch', 4.7, 21, 0, '6 INCH', 'cake/Festival/Simple Christmas Cake Ideas.jpg', '2026-01-15 03:08:06'),
(187, 'Welcome Mr Gold Prosperity Cake', 238.00, 1, '\"festival\"', 100, 'Bright red prosperity cake topped with a God of Wealth figurine.', 'Show your gratitude with this bright sunflower appreciation cake.', 'Flour, eggs, sugar, butter, fresh cream, food colouring', '6-inch', 4.8, 38, 0, '6 INCH', 'cake/Festival/WELCOME MR GOLD.webp', '2026-01-15 03:08:06'),
(188, 'Wonderful Year Fortune Cake', 228.00, 1, '\"festival\"', 100, 'Red fortune cake decorated with coins and gold sprinkles.', 'Elegant Best Mom cake with pink carnations and gold lettering.', 'Flour, sugar, eggs, fresh cream, edible flowers', '6-inch', 4.9, 52, 0, '6 INCH', 'cake/Festival/WONDERFUL YEAR.webp', '2026-01-15 03:08:06'),
(189, 'The Chick Animal Cake', 98.00, 1, '\"animal\"', 100, 'Bright yellow chick-shaped animal cake with tiny wings and rosy cheeks.', 'Bright yellow chick-shaped animal cake with tiny wings and rosy cheeks.', 'Flour, sugar, eggs, butter, fresh cream', '5-inch', 4.8, 23, 0, '5 INCH', 'cake/The Animal Series/THE CHICK.webp', '2026-01-15 03:08:06'),
(190, 'The Deer Animal Cake', 108.00, 1, '\"animal\"', 100, 'Gentle deer-inspired animal cake with antlers and soft cream details.', 'Gentle deer-inspired animal cake with antlers and soft cream details.', 'Flour, sugar, eggs, butter, fresh cream, chocolate', '5-inch', 4.9, 27, 0, '5 INCH', 'cake/The Animal Series/THE DEER.webp', '2026-01-15 03:08:06'),
(191, 'The Fox Animal Cake', 108.00, 1, '\"animal\"', 100, 'Sleepy fox animal cake decorated with floral crown details.', 'Sleepy fox animal cake with a floral crown and orange-cream fur.', 'Flour, sugar, eggs, fresh cream', '5-inch', 4.8, 25, 0, '5 INCH', 'cake/The Animal Series/THE FOX.webp', '2026-01-15 03:08:06'),
(192, 'The Monkey Animal Cake', 108.00, 1, '\"animal\"', 100, 'Playful monkey-shaped animal cake with round ears.', 'Friendly bear animal cake with a party hat and smile.', 'Flour, sugar, eggs, butter, fresh cream', '5-inch', 4.7, 20, 0, '5 INCH', 'cake/The Animal Series/THE MONKEY.webp', '2026-01-15 03:08:06'),
(193, 'The Penguin Animal Cake', 118.00, 1, '\"animal\"', 100, 'Cool blue penguin animal cake with sailor hat styling.', 'Adorable pink piglet animal cake with round ears and a tiny snout.', 'Flour, sugar, eggs, butter, fresh cream', '5-inch', 4.7, 18, 0, '5 INCH', 'cake/The Animal Series/THE PENGUIN.webp', '2026-01-15 03:08:06'),
(194, 'The Puppy Animal Cake', 108.00, 1, '\"animal\"', 100, 'Smiling puppy-shaped animal cake with playful expression.', 'Playful puppy animal cake with floppy ears and a spotty eye.', 'Flour, sugar, eggs, butter, fresh cream, chocolate', '5-inch', 4.9, 29, 0, '5 INCH', 'cake/The Animal Series/THE PUPPY.webp', '2026-01-15 03:08:06'),
(195, 'The Sheep Animal Cake', 108.00, 1, '\"animal\"', 100, 'Fluffy sheep animal cake covered in soft whipped cream swirls.', 'Cute penguin animal cake with a winter scarf made of fondant.', 'Flour, sugar, eggs, butter, fresh cream, fondant', '5-inch', 4.8, 22, 0, '5 INCH', 'cake/The Animal Series/THE SHEEP.webp', '2026-01-15 03:08:06'),
(196, 'The Unicorn Animal Cake', 128.00, 1, '\"animal\"', 100, 'Dreamy unicorn animal cake with pastel rosettes and golden horn.', 'Dreamy unicorn animal cake with pastel rosettes and golden horn.', 'Flour, sugar, eggs, butter, fresh cream, fondant', '5-inch', 4.9, 35, 0, '5 INCH', 'cake/The Animal Series/THE UNICORN.webp', '2026-01-15 03:08:06'),
(197, 'Fig Whisper Little Cake', 68.00, 1, '\"little\"', 100, 'Delicate fresh cream little cake crowned with juicy figs and cream swirls.', 'Petite and elegant little cake crowned with fresh fig wedges.', 'Flour, eggs, sugar, fresh cream, fresh figs', '5-inch petite', 4.7, 21, 0, '5 INCH', 'cake/Little Series/LITTLE A.webp', '2026-01-15 03:08:06'),
(198, 'Macaron Citrus Little Cake', 68.00, 1, '\"little\"', 100, 'Textured caramel-tone cake topped with macarons and dried citrus slices.', 'Textured caramel-tone cake topped with macarons and dried citrus slices.', 'Flour, sugar, eggs, butter, fresh cream, macarons', '5-inch petite', 4.8, 24, 0, '5 INCH', 'cake/Little Series/LITTLE B.webp', '2026-01-15 03:08:06'),
(199, 'Pink Ribbon Fig Little Cake', 68.00, 1, '\"little\"', 100, 'Romantic fig and macaron little cake tied with a pink ribbon.', 'Simple yet classic little cake with cherries and chocolate flakes.', 'Flour, sugar, eggs, butter, fresh cream, cherries', '5-inch petite', 4.6, 18, 0, '5 INCH', 'cake/Little Series/LITTLE C.webp', '2026-01-15 03:08:06'),
(200, 'Red Heart Splash Little Cake', 68.00, 1, '\"little\"', 100, 'White cream cake with a bold red glaze and twin hearts.', 'Petite strawberry garden cake with layers of fresh fruit and whipped cream.', 'Flour, sugar, eggs, strawberries, fresh cream', '5-inch petite', 4.7, 21, 0, '5 INCH', 'cake/Little Series/LITTLE D.webp', '2026-01-15 03:08:06'),
(201, 'Blueberry Heart Little Cake', 68.00, 1, '\"little\"', 100, 'Petite blueberry cake with blue cream splash.', 'Minimalist white cake with a single red heart topper.', 'Flour, sugar, eggs, butter, fresh cream, fondant heart', '5-inch petite', 4.5, 15, 0, '5 INCH', 'cake/Little Series/LITTLE E.webp', '2026-01-15 03:08:06'),
(202, 'Golden Chocolate Drip Little Cake', 68.00, 1, '\"little\"', 100, 'Rich chocolate drip cake loaded with gold-wrapped chocolates.', 'Rustic naked cake with visible sponge layers and fresh berries.', 'Flour, sugar, eggs, butter, fresh cream, mixed berries', '5-inch petite', 4.8, 26, 0, '5 INCH', 'cake/Little Series/LITTLE F.webp', '2026-01-15 03:08:06'),
(203, 'Citrus Garden Little Cake', 68.00, 1, '\"little\"', 100, 'Fresh cream citrus cake topped with fruits and herbs.', 'Sunny lemon-flavoured little cake with dried lemon slices.', 'Flour, sugar, eggs, lemon zest, fresh cream', '5-inch petite', 4.7, 19, 0, '5 INCH', 'cake/Little Series/LITTLE G.webp', '2026-01-15 03:08:06'),
(204, 'Sunny Daisy Citrus Little Cake', 68.00, 1, '\"little\"', 100, 'Yellow ombré citrus cake decorated with daisies.', 'Rich chocolate truffle cake in a petite size.', 'Flour, sugar, eggs, dark chocolate, cream', '5-inch petite', 4.9, 30, 0, '5 INCH', 'cake/Little Series/LITTLE H.webp', '2026-01-15 03:08:06'),
(205, 'Matcha Strawberry Meadow Little Cake', 68.00, 1, '\"little\"', 100, 'Matcha cream little cake topped with strawberries.', 'Green tea matcha cake with red bean filling.', 'Flour, sugar, eggs, matcha powder, red bean paste', '5-inch petite', 4.8, 22, 0, '5 INCH', 'cake/Little Series/LITTLE I.webp', '2026-01-15 03:08:06'),
(206, 'Rustic Blueberry Swirl Little Cake', 68.00, 1, '\"little\"', 100, 'Rustic-style cream cake with caramel swirl top.', 'Pink rosette cake covered in buttercream swirls.', 'Flour, sugar, eggs, butter, vanilla extract, food colouring', '5-inch petite', 4.7, 17, 0, '5 INCH', 'cake/Little Series/LITTLE N.webp', '2026-01-15 03:08:06'),
(207, 'Little Lavender Berry Cake', 68.00, 1, '\"little\"', 100, 'Lavender-toned petite blueberry cake with soft cream swirls.', 'Charming purple-themed little cake with fresh blueberries and lavender aroma.', 'Flour, sugar, eggs, fresh cream, blueberries', '5-inch petite', 4.8, 24, 0, '5 INCH', 'cake/Little Series/LITTLE Q.webp', '2026-01-15 03:08:06'),
(208, 'Little Nutty Biscuit Crunch', 68.00, 1, '\"little\"', 100, 'Caramel-toned petite cake topped with biscuits and nuts.', 'Caramel drizzle cake with crushed biscuits and sea salt.', 'Flour, sugar, eggs, caramel sauce, sea salt', '5-inch petite', 4.9, 28, 0, '5 INCH', 'cake/Little Series/LITTLE R.webp', '2026-01-15 03:08:06'),
(209, 'Little Vintage Cherry Cake', 68.00, 1, '\"little\"', 100, 'Retro-style pink cherry cake with piped cream borders.', 'Coffee walnut cake with espresso buttercream.', 'Flour, sugar, eggs, espresso, walnuts, butter', '5-inch petite', 4.7, 18, 0, '5 INCH', 'cake/Little Series/LITTLE U.webp', '2026-01-15 03:08:06'),
(210, 'Little Strawberry Drip Delight', 68.00, 1, '\"little\"', 100, 'Petite strawberry cake with white chocolate drip.', 'Small Earl Grey infused tea cake with a light bergamot aroma.', 'Flour, sugar, eggs, Earl Grey tea, fresh cream', '5-inch petite', 4.8, 16, 0, '5 INCH', 'cake/Little Series/LITTLE X.webp', '2026-01-15 03:08:06'),
(211, 'Little Cocoa Berry Drip', 68.00, 1, '\"little\"', 100, 'Chocolate-strawberry petite cake with dark drip.', 'Mango mousse little cake with a mirror glaze.', 'Mango puree, cream, gelatin, sugar, sponge cake', '5-inch petite', 4.9, 25, 0, '5 INCH', 'cake/Little Series/LITTLE Y.webp', '2026-01-15 03:08:06'),
(212, 'Little Cookies & Cream Drip', 68.00, 1, '\"little\"', 100, 'Petite cookies and cream cake with chocolate drip.', 'Small yet indulgent chocolate cake with rich Oreo cream and drip finish.', 'Flour, sugar, eggs, butter, Oreo crumbs, chocolate', '5-inch petite', 4.6, 20, 0, '5 INCH', 'cake/Little Series/LITTLE Z.webp', '2026-01-15 03:08:06'),
(220, 'Traditional Artisan Sourdough', 12.50, 2, '\"sourdough\"', 100, 'Classic artisan sourdough with a deep golden crust and airy crumb.', 'Classic artisan sourdough with a deep golden crust and airy crumb.', 'Flour, water, sea salt, sourdough starter', '500g', 4.9, 55, 0, 'REGULAR', 'bread/Sourdough Bread/traditional-sourdough.jpg', '2026-01-15 03:01:56'),
(221, 'Same Day Sourdough Bread', 11.90, 2, '\"wholegrain\"', 100, 'Freshly baked sourdough with a lighter tang and soft interior.', 'Soft and milky Japanese shokupan, perfect for toasts and sandwiches.', 'Flour, milk, butter, sugar, salt, yeast', '400g', 4.9, 82, 0, 'REGULAR', 'bread/Sourdough Bread/Same Day Sourdough Bread.webp', '2026-01-15 03:01:56'),
(222, 'Basic Sourdough Boule', 11.50, 2, '\"sourdough\"', 100, 'Simple and balanced sourdough boule with crunchy crust.', 'This Basic Sourdough Boule focuses on clean flavour and traditional technique. Naturally leavened and slow fermented, it delivers a well-balanced tang with a chewy crumb and rustic appearance.', 'Bread flour, water, sea salt, sourdough starter', '500g', 4.7, 29, 0, '500G', 'bread/Sourdough Bread/Basic Sourdough Boule.webp', '2026-01-15 03:01:56'),
(223, 'No-Knead Rustic Sourdough', 12.90, 2, '\"sourdough\"', 100, 'Rustic no-knead sourdough with open crumb and bold crust.', 'Crafted using a no-knead method, this rustic sourdough develops flavour through long fermentation. The result is a deeply caramelised crust with a moist and airy interior.', 'Bread flour, water, sea salt, sourdough starter', '520g', 4.8, 41, 0, '520G', 'bread/Sourdough Bread/Simple No-Knead Sourdough Bread.jpeg', '2026-01-15 03:01:56'),
(224, 'Overnight Fermented Sourdough', 13.50, 2, '\"sourdough\"', 100, 'Traditional rye sourdough with a deeper, earthier flavour.', 'A dense and flavorful loaf made with a blend of rye and wheat flour. Perfect for those who enjoy the rich, nutty notes of rye combined with a slight sourdough tang.', 'Rye flour, bread flour, water, salt, sourdough starter', '550g', 4.6, 25, 0, '550G', 'bread/Sourdough Bread/Overnight Sourdough Bread.jpg', '2026-01-15 03:01:56'),
(225, 'Rye Sourdough Bread', 14.50, 2, '\"sourdough\"', 100, 'Hearty rye sourdough with earthy flavour and dense crumb.', 'Hearty rye sourdough with earthy flavour and dense crumb.', 'Rye flour, wheat flour, water, salt, starter', '600g', 4.8, 32, 0, 'REGULAR', 'bread/Sourdough Bread/Rye Sourdough Bread.jpg', '2026-01-15 03:01:56'),
(226, 'Oatmeal Sourdough Bread', 13.90, 2, '\"sourdough\"', 100, 'Multigrain loaf with sunflower, flax, and sesame seeds.', 'Packed with a wholesome mix of sunflower, flax, and sesame seeds, this multigrain loaf offers a fantastic crunch and nutty aroma in every slice. Healthy and delicious.', 'Whole wheat flour, seeds mix (sunflower, flax, sesame), water, salt, yeast', '500g', 4.8, 34, 0, '500G', 'bread/Sourdough Bread/Oatmeal Sourdough Bread.jpg', '2026-01-15 03:01:56'),
(228, 'Homestyle Whole Grain Loaf', 10.90, 2, '\"wholegrain\"', 100, 'Naturally sweet pumpkin bread with a soft golden crumb.', 'Infused with roasted pumpkin purée, this bread boasts a beautiful golden colour and a naturally sweet, moist crumb. A seasonal favourite that pairs perfectly with soups.', 'Bread flour, pumpkin puree, water, yeast, salt, sugar', '450g', 4.7, 22, 0, '450G', 'bread/Whole Grain Bread/Homestyle Whole Grain Loaf.webp', '2026-01-15 03:01:56'),
(229, 'Classic Multigrain Bread', 11.50, 2, '\"wholegrain\"', 100, 'Fragrant garlic and herb loaf, perfect for garlic toast.', 'Loaded with roasted garlic and fresh herbs like rosemary and thyme, this aromatic loaf is a savoury delight. Slice it up for the ultimate garlic bread experience.', 'Bread flour, roasted garlic, mixed herbs, olive oil, water, yeast', '400g', 4.9, 45, 0, '400G', 'bread/Whole Grain Bread/Multigrain Bread.webp', '2026-01-15 03:01:56'),
(230, 'Honey Whole Wheat Bread', 11.90, 2, '\"whole grain\"', 100, 'Lightly sweetened whole wheat bread with natural honey.', 'Multi-seed whole grain bread packed with fibre and nutrients.', 'Whole wheat flour, oats, flaxseeds, sunflower seeds, honey', '450g', 4.7, 38, 0, 'REGULAR', 'bread/Whole Grain Bread/Basic Honey Whole Wheat Bread.jpg', '2026-01-15 03:01:56'),
(231, 'Classic Whole Wheat Bread', 10.50, 2, '\"wholegrain\"', 100, 'Traditional whole wheat loaf with a balanced, nutty taste.', 'Soft and milky Japanese style white bread, incredibly fluffy and perfect for toast.', 'High protein flour, milk, butter, sugar, yeast', '400g', 4.9, 55, 0, 'REGULAR', 'bread/Whole Grain Bread/Whole Wheat Bread.jpg', '2026-01-15 03:01:56'),
(232, 'Whole Grain Seeded Bread', 12.90, 2, '\"wholegrain\"', 100, 'Rich brioche loaf with high butter content and egg wash glaze.', 'This luxurious Brioche Loaf is enriched with plenty of butter and eggs, creating a pillowy soft texture and a rich, golden crust. Ideal for French toast or indulgent sandwiches.', 'Flour, butter, eggs, milk, sugar, yeast, salt', '400g', 4.9, 50, 0, '400G', 'bread/Whole Grain Bread/Whole Grain Seeded Bread.jpg', '2026-01-15 03:01:56'),
(233, 'Crusty Artisan Bread', 12.50, 2, '\"artisan\"', 100, 'Sweet chocolate chip brioche twist with soft texture.', 'A twist on the classic brioche, literally! Swirled with high-quality chocolate chips, this soft bread is a sweet treat that works for breakfast or dessert.', 'Flour, butter, eggs, chocolate chips, sugar, yeast', '420g', 4.8, 39, 0, '420G', 'bread/Artisan Bread/Crusty Artisan Bread.webp', '2026-01-15 03:01:56'),
(234, 'Gluten-Free Artisan Bread', 13.90, 2, '\"artisan\"', 100, 'Savoury cheese brioche topped with shredded cheddar.', 'For savoury lovers, this brioche is baked with cubes of cheddar cheese inside and a crispy cheese crust on top. Soft, fluffy, and cheesy in every bite.', 'Flour, butter, eggs, cheddar cheese, milk, yeast', '420g', 4.8, 36, 0, '420G', 'bread/Artisan Bread/Gluten Free Artisan Bread.jpg', '2026-01-15 03:01:56'),
(235, 'Easy Homemade Artisan Bread', 11.90, 2, '\"artisan\"', 100, 'Simple homemade-style artisan bread with rustic texture.', 'Traditional French baguette with a crisp crust and airy interior.', 'Flour, water, sea salt, yeast', '300g', 4.8, 45, 0, 'REGULAR', 'bread/Artisan Bread/Easy Homemade Artisan Bread.webp', '2026-01-15 03:01:56'),
(236, 'Jalapeño Cheese Artisan Bread', 14.50, 2, '\"artisan\"', 100, 'Rustic olive baguette with Kalamata olives and herbs.', 'A Mediterranean-inspired baguette studded with Kalamata olives and a hint of herbs. The salty olives contrast perfectly with the crisp crust and chewy interior.', 'Bread flour, olives, herbs, water, salt, yeast', '320g', 4.7, 28, 0, '320G', 'bread/Artisan Bread/Jalapeno Cheese Artisan Bread.jpg', '2026-01-15 03:01:56'),
(237, 'Classic Artisan Loaf', 12.20, 2, '\"artisan\"', 100, 'Seeded baguette coated in sesame, poppy, and sunflower seeds.', 'This baguette is rolled in a generous mix of sesame, poppy, and sunflower seeds before baking, giving it an extra crunchy crust and nutty flavour profile.', 'Bread flour, water, mixed seeds, salt, yeast', '320g', 4.8, 30, 0, '320G', 'bread/Artisan Bread/Artisan loaf.webp', '2026-01-15 03:01:56'),
(238, 'No-Knead Artisan Bread', 12.80, 2, '\"artisan\"', 100, 'Cheesy baguette topped with melted parmesan and mozzarella.', 'Baked with a topping of melted Parmesan and Mozzarella, this cheesy baguette is a savoury snack on its own or a perfect side for pasta dishes.', 'Bread flour, water, parmesan, mozzarella, salt, yeast', '330g', 4.9, 42, 0, '330G', 'bread/Artisan Bread/Easy No-Knead Artisan Bread.jpg', '2026-01-15 03:01:56'),
(239, 'Country-Style Artisan Bread', 12.60, 2, '\"artisan\"', 100, 'Mini baguette sandwich perfect for lunchboxes.', 'A smaller, personal-sized baguette designed for sandwiches. Crisp on the outside, soft on the inside, and perfectly portioned for lunch.', 'Bread flour, water, salt, yeast', '150g', 4.6, 19, 0, '150G', 'bread/Artisan Bread/Classic Artisan Country Bread.jpg', '2026-01-15 03:01:56'),
(240, 'Alsatian Kugelhopf Sweet Bread', 15.90, 2, '\"sweet\"', 100, 'Traditional Alsatian sweet bread with almonds and raisins.', 'Traditional Alsatian sweet bread with almonds and raisins.', 'Flour, milk, yeast, eggs, butter, raisins, almonds', '400g', 4.9, 41, 0, 'REGULAR', 'bread/Sweet Bread/Alsatian Kugelhopf Sweet Bread.webp', '2026-01-15 03:01:56'),
(241, 'Sweet Almond Braided Loaf', 14.50, 2, '\"sweet\"', 100, 'Cinnamon raisin swirl bread with a sweet glaze.', 'A sweet bread swirled with cinnamon and plump raisins, finished with a light sugar glaze. A comforting breakfast loaf that smells amazing when toasted.', 'Flour, milk, raisins, cinnamon, sugar, butter, yeast', '450g', 4.8, 37, 0, '450G', 'bread/Sweet Bread/Sweet Almond Braided Loaf.jpg', '2026-01-15 03:01:56'),
(242, 'Lemon Blueberry Sweet Bread', 13.90, 2, '\"sweet\"', 100, 'Zesty lemon poppy seed bread with a tender crumb.', 'Bright lemon zest and crunchy poppy seeds come together in this tender loaf. It’s a refreshing tea-time bread with a lovely citrus aroma.', 'Flour, sugar, eggs, lemon zest, poppy seeds, butter', '400g', 4.7, 24, 0, '400G', 'bread/Sweet Bread/Lemon Blueberry Bread.jpg', '2026-01-15 03:01:56'),
(243, 'Classic Lemon Glazed Loaf', 13.50, 2, '\"sweet\"', 100, 'Classic banana bread made with ripe bananas and walnuts.', 'Our classic banana bread uses over-ripe bananas for maximum sweetness and moisture, studded with walnuts for a satisfying crunch. Homestyle baking at its best.', 'Bananas, flour, sugar, walnuts, eggs, butter', '450g', 4.9, 55, 0, '450G', 'bread/Sweet Bread/Starbucks Lemon Loaf.jpg', '2026-01-15 03:01:56'),
(244, 'Moist Banana Sweet Bread', 12.90, 2, '\"sweet\"', 100, 'Rich double chocolate bread with cocoa and chocolate chips.', 'A dream for chocolate lovers! This loaf is made with cocoa powder and packed with chocolate chips, creating a double-dose of chocolatey goodness.', 'Flour, cocoa powder, chocolate chips, sugar, milk, butter', '450g', 4.8, 48, 0, '450G', 'bread/Sweet Bread/Moist Banana Bread.jpg', '2026-01-15 03:01:56'),
(245, 'Twisted Sweet Bread', 14.20, 2, '\"sweet\"', 100, 'Sweet cranberry orange bread with citrus zest.', 'Tart cranberries and sweet orange zest make this bread a vibrant and fruity delight. Dense yet moist, it is perfect with a cup of tea.', 'Flour, cranberries, orange zest, sugar, eggs, butter', '450g', 4.7, 31, 0, '450G', 'bread/Sweet Bread/Twisted Sweet Bread.jpg', '2026-01-15 03:01:56');
INSERT INTO `products` (`id`, `name`, `price`, `category_id`, `subcategory`, `stock`, `description`, `full_description`, `ingredients`, `size`, `rating`, `review_count`, `sold_count`, `size_info`, `image`, `created_at`) VALUES
(246, 'Braided Sweet Yeast Bread', 14.80, 2, '\"sweet\"', 100, 'Soft coconut bun filled with sweet desiccated coconut.', 'A soft, fluffy bun filled with a sweet and juicy mixture of desiccated coconut and sugar. A nostalgic treat that transports you to the tropics.', 'Flour, desiccated coconut, sugar, milk, butter, yeast', '80g', 4.8, 26, 0, '80G', 'bread/Sweet Bread/Braided Sweet Yeast Bread.webp', '2026-01-15 03:01:56'),
(247, 'Honey Sweet Bread Rolls', 13.90, 2, '\"sweet\"', 100, 'Classic red bean bun with smooth adzuki bean paste.', 'The classic Asian bakery staple. A soft bun filled with smooth, sweet red bean paste and topped with black sesame seeds.', 'Flour, red bean paste, sugar, milk, butter, sesame seeds', '80g', 4.9, 40, 0, '80G', 'bread/Sweet Bread/BEST Honey Sweet Bread Rolls.jpg', '2026-01-15 03:01:56'),
(248, 'Pulla Sweet Bread', 14.60, 2, '\"sweet\"', 100, 'Savoury sausage bun wrapped in soft dough.', 'A kid-favourite! A juicy chicken sausage wrapped in soft, slightly sweet bread dough and baked until golden brown.', 'Flour, chicken sausage, milk, butter, sugar, yeast', '90g', 4.8, 45, 0, '90G', 'bread/Sweet Bread/Pulla Bread.jpg', '2026-01-15 03:01:56'),
(249, 'Swiss Roll Croissant', 9.80, 3, '\"croissant\"', 100, 'Flaky croissant with a unique rolled shape.', 'Flaky croissant with a unique rolled shape.', 'Wheat flour, butter, yeast, milk, sugar, salt', '80g', 4.7, 29, 0, 'REGULAR', 'pastries/Croissants/Swiss Roll  croissant(1).jpg', '2026-01-15 03:01:56'),
(250, 'Croissant Bread Pudding', 12.90, 3, '\"croissant\"', 100, 'Two-tone chocolate croissant with striped appearance.', 'A visually striking croissant featuring alternating layers of chocolate and plain dough. Filled with a rich chocolate ganache for extra indulgence.', 'Flour, butter, chocolate, cocoa powder, milk, sugar', '85g', 4.9, 33, 0, '85G', 'pastries/Croissants/Croissant Bread Pudding.jpg', '2026-01-15 03:01:56'),
(251, 'Chocolate Filled Croissant', 8.90, 3, '\"croissant\"', 100, 'Classic croissant filled with rich chocolate.', 'Classic croissant filled with rich chocolate.', 'Flour, butter, chocolate, milk, yeast', '90g', 4.9, 63, 0, 'REGULAR', 'pastries/Croissants/Chocolate-Filled Croissant.jpg', '2026-01-15 03:01:56'),
(252, 'Ham and Cheese Croissant', 13.50, 3, '\"croissant\"', 100, 'Mini croissant selection box for sharing.', 'A delightful box of bite-sized mini croissants, perfect for sharing at breakfast or meetings. Includes plain, chocolate, and almond varieties.', 'Flour, butter, milk, sugar, chocolate, almonds', 'Box of 6', 4.8, 28, 0, 'BOX', 'pastries/Croissants/Ham and Cheese Croissant.jpg', '2026-01-15 03:01:56'),
(253, 'Nutella Croissant', 10.20, 3, '\"croissant\"', 100, 'Delicate fruit danish topped with custard and kiwi.', 'A buttery pastry base topped with smooth vanilla custard and a slice of fresh kiwi. The tart fruit balances the sweet custard perfectly.', 'Flour, butter, custard, kiwi, sugar glaze', '100g', 4.7, 20, 0, '100G', 'pastries/Croissants/3-Ingredient Nutella Croissants.jpg', '2026-01-15 03:01:56'),
(254, 'Chocolate Almond Croissant', 12.80, 3, '\"croissant\"', 100, 'Sweet strawberry danish with a glaze finish.', 'A crowd-pleaser featuring a flaky danish pastry, creamy custard center, and a fresh, juicy strawberry on top, finished with an apricot glaze.', 'Flour, butter, custard, strawberries, glaze', '100g', 4.9, 35, 0, '100G', 'pastries/Croissants/Chocolate Almond Croissants.jpg', '2026-01-15 03:01:56'),
(255, 'Classic French Croissant', 7.90, 3, '\"croissant\"', 100, 'Tangy blueberry danish with fresh blueberries.', 'Bursting with fresh blueberries, this danish combines the flaky texture of puff pastry with the zesty pop of berries and sweet custard.', 'Flour, butter, custard, blueberries, sugar', '100g', 4.8, 29, 0, '100G', 'pastries/Croissants/Homemade French Croissants.jpg', '2026-01-15 03:01:56'),
(256, 'Chocolate Cream Cheese Danish', 12.80, 3, '\"danish\"', 100, 'Rich cream cheese danish with a hint of lemon.', 'A classic danish filled with a rich, smooth cream cheese mixture spiked with a hint of lemon zest for freshness. Simple and satisfying.', 'Flour, butter, cream cheese, lemon zest, sugar', '100g', 4.8, 32, 0, '100G', 'pastries/Danish Pastries/Chocolate Cream Cheese Danish.jpg', '2026-01-15 03:01:56'),
(257, 'Cream Cheese Danish Braid with Berries', 14.50, 3, '\"danish\"', 100, 'Apple cinnamon danish with spiced apple filling.', 'Warm spiced apple filling nestled in crispy pastry dough. Topped with a crumble or icing drizzle, it tastes like apple pie in pastry form.', 'Flour, butter, apples, cinnamon, sugar', '110g', 4.9, 40, 0, '110G', 'pastries/Danish Pastries/Cream Cheese Danish Braid with Berries.webp', '2026-01-15 03:01:56'),
(258, 'Mini Cheese Danish', 7.90, 3, '\"danish\"', 100, 'Nutty pecan danish with maple glaze.', 'Crunchy pecans and sweet maple syrup glaze top this rich danish pastry. A perfect autumn treat that pairs wonderfully with coffee.', 'Flour, butter, pecans, maple syrup, sugar', '100g', 4.8, 25, 0, '100G', 'pastries/Danish Pastries/Mini Cheese Danish.png', '2026-01-15 03:01:56'),
(259, 'Lemon Raspberry Cream Cheese Danish', 13.90, 3, '\"danish\"', 100, 'Mixed berry danish with raspberry, blueberry, and blackberry.', 'Can\'t decide on a fruit? This mixed berry danish offers a colourful medley of raspberries, blueberries, and blackberries atop creamy custard.', 'Flour, butter, mixed berries, custard, sugar', '110g', 4.9, 38, 0, '110G', 'pastries/Danish Pastries/Lemon Raspberry Cream Cheese Danish.webp', '2026-01-15 03:01:56'),
(260, 'Almond Mascarpone Danish', 14.20, 3, '\"danish\"', 100, 'Danish pastry with almond and mascarpone filling.', 'Artisan danish pastry topped with fresh peach slices and a sweet glaze.', 'Flour, butter, fresh peaches, custard, sugar', '110g', 4.7, 28, 0, 'REGULAR', 'pastries/Danish Pastries/Almond Mascarpone Danish Pastries.jpg', '2026-01-15 03:01:56'),
(261, 'Raspberry Cream Cheese Pinwheel', 11.80, 3, '\"danish\"', 100, 'Chicken curry puff with spicy potato and chicken filling.', 'A savoury favourite! Flaky puff pastry filled with a spiced mixture of minced chicken, potatoes, and curry spices. A hearty snack.', 'Flour, butter, chicken, potatoes, curry powder, onions', '120g', 4.9, 60, 0, '120G', 'pastries/Danish Pastries/Raspberry Cream Cheese Pinwheel Pastries.jpg', '2026-01-15 03:01:56'),
(262, 'Fruit and Cream Cheese Danish', 13.50, 3, '\"danish\"', 100, 'Sardine puff with spicy tomato sardine filling.', 'Crispy pastry pockets filled with sardines cooked in a spicy tomato and onion sauce. A local classic with a zesty kick.', 'Flour, butter, sardines, tomato paste, chilli, onions', '110g', 4.7, 45, 0, '110G', 'pastries/Danish Pastries/Fruit and Cream Cheese Breakfast Pastries.jpg', '2026-01-15 03:01:56'),
(263, 'Classic Cheese Danish', 10.90, 3, '\"danish\"', 100, 'Tuna mayo puff with creamy tuna filling.', 'A creamy and savoury puff filled with a mixture of tuna chunks, mayonnaise, corn, and onions. Mild and comforting.', 'Flour, butter, tuna, mayonnaise, corn, onions', '110g', 4.8, 42, 0, '110G', 'pastries/Danish Pastries/Cheese Danish.jpg', '2026-01-15 03:01:56'),
(264, 'Cherry Cream Cheese Danish', 13.20, 3, '\"danish\"', 99, 'Veggie curry puff with potato and peas.', 'A vegetarian version of the classic curry puff, filled with spiced potatoes, peas, and carrots. Just as delicious and flavourful.', 'Flour, butter, potatoes, peas, carrots, curry spices', '120g', 4.6, 30, 1, '120G', 'pastries/Danish Pastries/Cherry Cream Cheese Danish.jpg', '2026-01-15 03:01:56'),
(265, 'Mini Fruit Tarts', 9.80, 3, '\"tart\"', 100, 'Egg mayo puff with smooth egg filling.', 'Light puff pastry encasing a rich and creamy egg mayonnaise filling. A simple yet satisfying savoury bite.', 'Flour, butter, eggs, mayonnaise, pepper', '110g', 4.7, 28, 0, '110G', 'pastries/Tarts/Mini Fruit Tarts.jpg', '2026-01-15 03:01:56'),
(266, 'White Chocolate Raspberry Mini Tarts', 11.50, 3, '\"tart\"', 100, 'Mushroom chicken puff with creamy sauce.', 'Savoury puff pastry filled with diced chicken and mushrooms in a creamy white sauce. Rich, earthy, and satisfying.', 'Flour, butter, chicken, mushrooms, cream, onions', '120g', 4.8, 35, 0, '120G', 'pastries/Tarts/White Chocolate Raspberry Mini Tarts.webp', '2026-01-15 03:01:56'),
(267, 'Vegan Lemon Tart', 12.90, 3, '\"tart\"', 100, 'Cheesy hotdog puff wrapped in crispy pastry.', 'A fun twist on pigs in a blanket. A juicy hotdog sausage wrapped in flaky puff pastry with a slice of melted cheese.', 'Flour, butter, sausage, cheese', '100g', 4.8, 50, 0, '100G', 'pastries/Tarts/Easy Vegan Lemon Tarts.jpg', '2026-01-15 03:01:56'),
(268, 'White Chocolate Mousse Tart', 13.80, 3, '\"tart\"', 100, 'Black pepper chicken puff with a spicy kick.', 'For those who like a bit of heat, this puff is filled with chicken chunks cooked in a robust black pepper sauce.', 'Flour, butter, chicken, black pepper sauce, onions', '120g', 4.7, 32, 0, '120G', 'pastries/Tarts/Rich White Chocolate Mousse Tart.webp', '2026-01-15 03:01:56'),
(269, 'Mascarpone Berry Puff Pastry', 13.50, 3, '\"puff\"', 100, 'Puff pastry filled with mascarpone and fresh berries.', 'Flaky puff pastry filled with creamy mascarpone and seasonal berries.', 'Puff pastry, mascarpone cheese, fresh strawberries/blueberries', '120g', 4.9, 32, 0, 'REGULAR', 'pastries/Puff Pastry/Mascarpone Puff Pastry.jpg', '2026-01-15 03:01:56'),
(270, 'Chocolate Puff Pastry Roll', 11.90, 3, '\"puff\"', 100, 'Chocolate puff pastry roll filled with melted chocolate.', 'Flaky puff pastry filled with melted chocolate.', 'Flour, butter, chocolate, sugar', '100g', 4.8, 48, 0, 'REGULAR', 'pastries/Puff Pastry/Chocolate Puff Pastry.jpg', '2026-01-15 03:01:56'),
(271, 'Ham, Egg and Cheese Puff Pastry', 14.90, 3, '\"puff\"', 100, 'Savory puff pastry with ham, egg, and cheese.', 'Savoury puff pastry with ham, egg, and melted cheese.', 'Flour, butter, ham, eggs, cheddar cheese', '130g', 4.8, 41, 0, 'REGULAR', 'pastries/Puff Pastry/Ham Egg and Cheese Puff Pastry.jpg', '2026-01-15 03:01:56'),
(272, 'Puff Pastry Cinnamon Rolls', 12.50, 3, '\"puff\"', 100, 'Cinnamon rolls made with flaky puff pastry.', 'Sweet cinnamon rolls made from flaky puff pastry dough.', 'Flour, butter, cinnamon, brown sugar, yeast', '100g', 4.8, 52, 0, 'REGULAR', 'pastries/Puff Pastry/Puff Pastry Cinnamon Rolls.jpg', '2026-01-15 03:01:56');

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
(19, 23, 'Ayer Keroh|73100|3,jalan lotus 3/33', 0, '2026-01-09 19:24:00'),
(20, 23, 'Bandar Melaka|75000|2,jalan aeon ,2/22', 1, '2026-01-09 19:24:00'),
(21, 18, '17,taman jonker 8/12, Ayer Keroh, 75450', 0, '2026-01-13 13:41:31'),
(22, 19, 'Bandar Melaka|75000|30,Taman Jaya', 1, '2026-01-13 13:51:20'),
(23, 19, 'Ayer Keroh|64432|30 Jalan J4', 0, '2026-01-13 13:51:20');

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
(14, 'jackson', 'jackson16@gmail.com', '$2y$10$O/BR780RknNnUtBxAZvMbuijC/wAjWpvmRuHjHXCyiTzosLnfiSdy', '', '', '2025-12-08 08:53:28', '2025-12-08 09:04:49'),
(15, 'test', 'test@gmail.com', '$2y$10$Ztpnyf7PmViK7ew/Ju2Lt.kg6bKB31BslzLjglUJ.KO0K4M0ostA6', '01234567', 'Ayer Keroh|75000|123,jalan merdeka', '2025-12-13 10:22:04', '2025-12-16 07:11:55'),
(18, 'test', 'iplaygame317@gmail.com', '$2y$10$oMre/vjL6qFEMfhqG/Yv3eoO34HfgSMUiOe/x/OVTsE/bC3UVLvAO', NULL, NULL, '2025-12-25 09:57:43', '2025-12-25 09:57:43'),
(19, 'Lim See Yuan Shane', 'shanelim1019@gmail.com', '$2y$10$U3jhVgwWEOXXuKJ0Sqnle.wAI0LqLRdxh8sgMzs6gy0EGPWzLBsGC', '0166859333', NULL, '2025-12-30 12:05:57', '2026-01-13 14:01:24'),
(20, 'shane', 'shanelim123@gmail.com', '$2y$10$D3NT4oBkbiYawpsPAACEJObeOoYfVRFvjJxgArkFVT5n1nxWqM6uG', NULL, NULL, '2026-01-07 12:47:23', '2026-01-07 12:47:23');

-- --------------------------------------------------------

--
-- Table structure for table `user_favorites`
--

CREATE TABLE `user_favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_favorites`
--

INSERT INTO `user_favorites` (`id`, `user_id`, `product_id`, `product_name`, `created_at`) VALUES
(654, 29, 185, '3D Red Velvet Christmas Tree Cake', '2026-01-18 10:20:38'),
(745, 28, 1, 'A LITTLE SWEET', '2026-01-18 22:52:47'),
(746, 28, 185, '3D Red Velvet Christmas Tree Cake', '2026-01-18 22:52:54'),
(748, 28, 156, 'Angella Bunny Music Fresh Cream Cake', '2026-01-18 22:53:40'),
(750, 28, 139, 'Animal Friends Fondant Cake', '2026-01-18 22:53:44'),
(751, 29, 161, 'Among Us Galaxy Fresh Cream Cake', '2026-01-18 22:53:53'),
(752, 29, 126, 'Oreo Monster Animal Cake', '2026-01-18 22:54:00'),
(753, 33, 185, '3D Red Velvet Christmas Tree Cake', '2026-01-18 23:05:36'),
(754, 33, 264, 'Cherry Cream Cheese Danish', '2026-01-18 23:05:52'),
(0, 19, 1, 'A LITTLE SWEET', '2026-01-19 12:38:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_product` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_message_user` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `orders_detail`
--
ALTER TABLE `orders_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_detail_order` (`order_id`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user_db`
--
ALTER TABLE `user_db`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `fk_message_user` FOREIGN KEY (`user_id`) REFERENCES `user_db` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
