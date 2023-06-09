-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 08, 2023 at 10:11 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trading`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `identifier` text NOT NULL,
  `symbol` varchar(256) NOT NULL,
  `price` bigint(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `identifier` varchar(256) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `stock_price` double NOT NULL,
  `transaction_amt` bigint(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `email`, `identifier`, `quantity`, `stock_price`, `transaction_amt`, `status`, `created_at`) VALUES
(26, 'kudlu2004@gmail.com', '', 0, 0, 18725, 1, '2023-06-08 17:11:46'),
(27, 'kudlu2004@gmail.com', '', 0, 0, 187250, 1, '2023-06-08 17:49:01'),
(28, 'kudlu2004@gmail.com', '', 0, 0, 187250, 1, '2023-06-08 18:27:00'),
(29, 'kudlu2004@gmail.com', '', 0, 0, 187250, 1, '2023-06-08 18:30:35'),
(30, 'kudlu2004@gmail.com', '', 0, 0, 297800, 1, '2023-06-08 18:35:06'),
(31, 'kudlu2004@gmail.com', '', 0, 0, 2978000, 1, '2023-06-08 18:35:32'),
(32, 'kudlu2004@gmail.com', '', 0, 0, 297800, 1, '2023-06-08 18:38:20'),
(33, 'kudlu2004@gmail.com', '', 0, 0, 29780, 1, '2023-06-08 18:55:56'),
(34, 'kudlu2004@gmail.com', '', 1, 0, 21042, 0, '2023-06-08 19:22:35'),
(35, 'kudlu2004@gmail.com', '', 1, 0, 297800, 1, '2023-06-08 19:24:14'),
(36, 'kudlu2004@gmail.com', '', 1, 0, 2978, 1, '2023-06-08 19:30:07'),
(37, 'kudlu2004@gmail.com', '', 1, 0, 2978, 1, '2023-06-08 19:37:02'),
(38, '', 'HEROMOTOCOEQN', 1, 0, 0, 1, '2023-06-08 19:37:02'),
(39, 'kudlu2004@gmail.com', '', 1, 0, 2317, 1, '2023-06-08 19:41:15'),
(40, 'kudlu2004@gmail.com', '', 1, 0, 2317, 1, '2023-06-08 19:43:25'),
(41, 'kudlu2004@gmail.com', '', 1, 0, 21042, 1, '2023-06-08 20:10:58'),
(42, 'kudlu2004@gmail.com', 'NIFTY 50', 1, 18725, 21042, 0, '2023-06-08 20:10:58'),
(43, 'kudlu2004@gmail.com', 'LTEQN', 1, 2317, 21042, 0, '2023-06-08 20:10:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(256) NOT NULL,
  `phone` bigint(10) DEFAULT NULL,
  `password` varchar(256) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ip` varchar(100) NOT NULL,
  `auth` int(11) NOT NULL DEFAULT 0,
  `connect` varchar(256) NOT NULL,
  `balance` bigint(20) NOT NULL DEFAULT 100000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `created_at`, `updated_at`, `ip`, `auth`, `connect`, `balance`) VALUES
(9, 'test', 'raja', 'kudlu2004@gmail.com', 123, '12', '2023-06-07 14:13:59', '2023-06-09 01:40:58', '::1', 1, '1', 46200),
(10, 'Kartikeya', 'Saini', 'kartik44068@gmail.com', NULL, '12', '2023-06-08 04:36:11', '2023-06-08 10:06:11', '', 1, '', 100000);

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(11) NOT NULL,
  `email` text NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `access_type` varchar(100) NOT NULL DEFAULT 'Login',
  `ip` varchar(256) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `email`, `updated_at`, `access_type`, `ip`, `status`) VALUES
(10, 'kudlu2004@gmail.com', '2023-06-08 08:51:42', 'Login', '::1', 0),
(11, 'kudlu2004@gmail.com', '2023-06-08 08:57:43', 'Login', '::1', 0),
(12, 'kudlu2004@gmail.com', '2023-06-08 09:10:03', 'Login', '::1', 0),
(13, 'kudlu2004@gmail.com', '2023-06-08 09:18:22', 'Login', '::1', 0),
(14, 'kudlu2004@gmail.com', '2023-06-08 09:59:39', 'Login', '::1', 0),
(15, 'kudlu2004@gmail.com', '2023-06-08 10:35:04', 'Login', '::1', 0),
(16, 'kudlu2004@gmail.com', '2023-06-08 11:41:49', 'Login', '::1', 0),
(17, 'kudlu2004@gmail.com', '2023-06-08 11:42:05', 'Login', '::1', 0),
(18, 'kudlu2004@gmail.com', '2023-06-08 14:09:56', 'Login', '::1', 0),
(19, 'kudlu2004@gmail.com', '2023-06-08 14:12:45', 'Login', '::1', 0),
(20, 'kudlu2004@gmail.com', '2023-06-08 16:12:25', 'Login', '::1', 0),
(21, 'kudlu2004@gmail.com', '2023-06-08 21:19:22', 'Login', '::1', 0),
(22, 'kudlu2004@gmail.com', '2023-06-08 22:05:25', 'Login', '::1', 0),
(23, 'kudlu2004@gmail.com', '2023-06-08 22:05:37', 'Login', '::1', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
