-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 11, 2025 at 01:43 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Crypto`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` int(11) NOT NULL,
  `portfolio_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `quantity` decimal(18,8) DEFAULT 0.00000000,
  `purchase_price` decimal(18,8) DEFAULT 0.00000000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `portfolio_id`, `asset_id`, `quantity`, `purchase_price`) VALUES
(1, 1, 1, 3.20000000, 3000000.00000000),
(8, 12, 3, 200.00000000, 7.00000000),
(9, 1, 2, 12.00000000, 200000.00000000),
(14, 13, 1, 0.00000000, 0.00000000),
(16, 13, 3, 0.00000000, 7.00000000);

-- --------------------------------------------------------

--
-- Table structure for table `crypto`
--

CREATE TABLE `crypto` (
  `asset_id` int(11) NOT NULL,
  `asset_name` varchar(100) DEFAULT NULL,
  `price_live` double NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `short_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crypto`
--

INSERT INTO `crypto` (`asset_id`, `asset_name`, `price_live`, `image_url`, `short_name`) VALUES
(1, 'Bitcoin', 3000000, '/bitcoin.svg.png', 'BTC'),
(2, 'Ethereum', 80000, '/eth.svg', 'ETH'),
(3, 'Dogecoin', 7, '/doge.png', 'DOGE');

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE `portfolios` (
  `portfolio_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `portname` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `portfolios`
--

INSERT INTO `portfolios` (`portfolio_id`, `user_id`, `portname`, `description`, `created_at`) VALUES
(1, 1, 'Bike & Boo', 'tanakon panapong portfolio', '2025-02-19 14:32:17'),
(2, 2, 'ThanakritPort', 'none', '2025-03-09 08:06:16'),
(3, 3, 'wichaya', 'none', '2025-03-09 08:16:05'),
(12, 18, 'boonrod55', NULL, '2025-03-09 09:31:40'),
(13, 19, 'new port', NULL, '2025-03-11 11:09:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pass_see` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fname`, `lname`, `age`, `username`, `email`, `password`, `created_at`, `pass_see`) VALUES
(1, 'Tanakon', 'Panapong', 20, 'tanakon1436', 'tanakon1436@gmail.com', '$2y$10$kDIKLxe7oSoV./e5JPW4qu3LnVNmL3PcsafufZtQF.xuqljENzJHy', '2025-02-19 13:24:14', 'as123456789'),
(2, 'Thanakrit', 'Katenual', 20, 'thanakrit11', '6610210142@psu.ac.th', '$2y$10$iVWDxt8wKAkImcgmbZPDYuztacc7SDQhl0aCorogcUfqqdPLS0sJS', '2025-02-20 05:21:01', 'as1234567890'),
(3, 'Wichayapon', 'Panapong', 19, 'wichaya11', 'wichaya11@gmail.com', '$2y$10$xuV3e3jjtn9CbK01ngF5hOAEL9MR3EUb.i9VhDx4BiyZkP16G7ei2', '2025-03-09 08:13:31', 'as1234'),
(18, 'Somchai', 'Boonrod', 45, 'boonrod11', 'boonrood11@gmail.com', '$2y$10$5sIcu.i0Vxb0SzNQ8bI42ujVcNbheYG9vK93QYBYIDHut6FgUIPPW', '2025-03-09 09:31:40', 'as1234'),
(19, 'tarathep', 'madman', 19, 'tara11', 'asdfas@gmail.com', '$2y$10$rdlO8eIoAlLQbFig.3q01OgSNv3apnYxG0thvXNb/lGwA1x.Yy6o2', '2025-03-11 11:09:29', 'as1234');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `portfolio_id` (`portfolio_id`,`asset_id`),
  ADD KEY `asset_id` (`asset_id`);

--
-- Indexes for table `crypto`
--
ALTER TABLE `crypto`
  ADD PRIMARY KEY (`asset_id`);

--
-- Indexes for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD PRIMARY KEY (`portfolio_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `portfolios`
--
ALTER TABLE `portfolios`
  MODIFY `portfolio_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `assets_ibfk_1` FOREIGN KEY (`portfolio_id`) REFERENCES `portfolios` (`portfolio_id`),
  ADD CONSTRAINT `assets_ibfk_2` FOREIGN KEY (`asset_id`) REFERENCES `crypto` (`asset_id`);

--
-- Constraints for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD CONSTRAINT `portfolios_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
