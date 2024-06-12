-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Jun 12, 2024 at 02:34 PM
-- Server version: 8.4.0
-- PHP Version: 8.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `express`
--

-- --------------------------------------------------------

--
-- Table structure for table `Biker`
--
CREATE DATABASE IF NOT EXISTS `express`;

USE `express`;

CREATE TABLE `Biker` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `longitude` decimal(9,6) NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Trip`
--

CREATE TABLE `Trip` (
  `id` int NOT NULL,
  `vendor_id` int NOT NULL,
  `biker_id` int DEFAULT NULL,
  `origin_latitude` decimal(9,6) NOT NULL,
  `origin_longitude` decimal(9,6) NOT NULL,
  `destination_latitude` decimal(9,6) NOT NULL,
  `destination_longitude` decimal(9,6) NOT NULL,
  `status` enum('requested','assigned','acked','picked','delivered','canceled') NOT NULL DEFAULT 'requested',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Vendor`
--

CREATE TABLE `Vendor` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `longitude` decimal(9,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Biker`
--
ALTER TABLE `Biker`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Trip`
--
ALTER TABLE `Trip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `biker_id` (`biker_id`);

--
-- Indexes for table `Vendor`
--
ALTER TABLE `Vendor`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Biker`
--
ALTER TABLE `Biker`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Trip`
--
ALTER TABLE `Trip`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Vendor`
--
ALTER TABLE `Vendor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Trip`
--
ALTER TABLE `Trip`
  ADD CONSTRAINT `Trip_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `Vendor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Trip_ibfk_2` FOREIGN KEY (`biker_id`) REFERENCES `Biker` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
