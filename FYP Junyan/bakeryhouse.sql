-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-12-31 15:16:29
-- 服务器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `bakeryhouse`
--

-- --------------------------------------------------------

--
-- 表的结构 `admins`
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
-- 转存表中的数据 `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `role`, `status`, `login_attempts`, `last_login`, `created_at`) VALUES
(1, 'superadmin', 'superadmin@gmail.com', '$2y$12$fyerT6O5XC5d2gopGVhUhu9lJId.Xe5vOp71Te4hvgPwmQqNovVeO', 'super_admin', 'active', 0, '2025-12-03 16:59:03', '2025-12-03 16:48:52');

-- --------------------------------------------------------

--
-- 表的结构 `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Cake', '2025-12-14 18:32:37'),
(2, 'Bread', '2025-12-14 18:32:37'),
(3, 'Pastry', '2025-12-14 18:32:37');

-- --------------------------------------------------------

--
-- 表的结构 `contact_messages`
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
-- 转存表中的数据 `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `user_id`, `name`, `email`, `message`, `status`, `created_at`) VALUES
(1, 18, 'test', 'iplaygame317@gmail.com', 'good food', 'unread', '2025-12-31 12:37:39'),
(2, 18, 'test', 'iplaygame317@gmail.com', 'nicee service', 'unread', '2025-12-31 12:38:03'),
(3, 18, 'test', 'iplaygame317@gmail.com', 'ggood food', 'unread', '2025-12-31 14:15:49');

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `items` text NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','preparing','ready','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_phone`, `delivery_address`, `city`, `postcode`, `items`, `total`, `status`, `created_at`) VALUES
(2, 'test', 'test@bakery.com', '012-3456789', '30, Bukit Beruang, Ayer Keroh', 'Melaka', '75450', '[{\"id\":1,\"name\":\"A LITTLE SWEET\",\"price\":98,\"image\":\"cake\\/A_Little_Sweet.jpg\",\"quantity\":1}]', 98.00, 'delivered', '2025-12-02 15:29:27');

-- --------------------------------------------------------

--
-- 表的结构 `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `products`
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
-- 转存表中的数据 `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category`, `stock`, `description`, `image`, `created_at`) VALUES
(698, 'test', 0.10, 'Cake', 1, '', '', '2025-11-25 14:54:18'),
(699, 'test', 0.10, 'Cake', 1, '', '', '2025-11-25 14:54:27');

-- --------------------------------------------------------

--
-- 表的结构 `user_db`
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
-- 转存表中的数据 `user_db`
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
(15, 'test', 'test@gmail.com', '$2y$10$Ztpnyf7PmViK7ew/Ju2Lt.kg6bKB31BslzLjglUJ.KO0K4M0ostA6', '01234567', 'Ayer Keroh|75000|123,jalan merdeka', '2025-12-13 10:22:04', '2025-12-16 07:11:55'),
(18, 'test', 'iplaygame317@gmail.com', '$2y$10$5pvdZ8nB9N.gv8cAp6Lu5ejFazDH/66CJyvhsyd0m.RHJbq/A2OTC', NULL, NULL, '2025-12-25 09:57:43', '2025-12-31 07:08:31');

--
-- 转储表的索引
--

--
-- 表的索引 `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- 表的索引 `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- 表的索引 `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_message_user` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- 表的索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_email` (`email`);

--
-- 表的索引 `user_db`
--
ALTER TABLE `user_db`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique message ID', AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- 使用表AUTO_INCREMENT `user_db`
--
ALTER TABLE `user_db`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- 限制导出的表
--

--
-- 限制表 `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `fk_message_user` FOREIGN KEY (`user_id`) REFERENCES `user_db` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
