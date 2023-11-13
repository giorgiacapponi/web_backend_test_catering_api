-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 13, 2023 at 11:09 AM
-- Server version: 5.7.24
-- PHP Version: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `catering_facilities_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `facility_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `last_name`, `date_of_birth`, `facility_id`) VALUES
(1, 'Martin', 'din', '1989-01-01', 1),
(2, 'Javer', 'moon', '1988-02-02', 1),
(3, 'Robert', 'black', '1990-03-03', 2),
(4, 'Mary', 'White', '1995-04-04', 2),
(5, 'jude', 'Brown', '2000-05-05', 3);

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `creation_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `location_id`, `name`, `creation_date`) VALUES
(1, 1, 'Catering Masters', '2023-11-10'),
(2, 2, 'party planners', '2013-10-01'),
(3, 3, 'gourmet amsterdam', '2013-10-01'),
(4, 4, 'event catering', '2020-10-01'),
(5, 5, 'event gourmet services', '2020-10-01'),
(6, 6, 'new catering utrecht', '2020-10-01'),
(7, 7, 'banquet services', '2020-10-01'),
(8, 8, 'catering services', '2010-11-01');

-- --------------------------------------------------------

--
-- Table structure for table `facility_tag`
--

CREATE TABLE `facility_tag` (
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `facility_tag`
--

INSERT INTO `facility_tag` (`facility_id`, `tag_id`) VALUES
(1, 1),
(1, 2),
(8, 2),
(1, 3),
(1, 4),
(3, 5),
(5, 6),
(8, 6),
(6, 7);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `city` varchar(50) NOT NULL,
  `address` varchar(150) NOT NULL,
  `zip_code` varchar(12) NOT NULL,
  `country_code` varchar(2) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `city`, `address`, `zip_code`, `country_code`, `phone_number`) VALUES
(1, 'Amsterdam', 'straat 123', '1012 XY', 'NL', '+31 20 765 4321'),
(2, 'Utrecht', 'straat 123', '14323 xw', 'NL', '+31 20 5434 323'),
(3, 'amsterdam', 'straat 3', '1442 22', 'NL', '+31 322 34 323'),
(4, 'rotterdam', 'straat 232', '1422 fd', 'NL', '+31 32232 43434'),
(5, 'rotterdam', 'straat 1', '1324 hg', 'NL', '+31 4334 5534'),
(6, 'utrecht', 'straat 23', '3224 rf', 'NL', '+31 433443 33'),
(7, 'amsterdam', 'straat 2', '3224 rf', 'NL', '+31 2334 33'),
(8, 'amsterdam', 'straat 222', '4225', 'NL', '+31 2122 33'),
(9, 'utrecht', 'straat 222', '4225', 'NL', '+31 2122 33');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(1, 'catering'),
(4, 'event'),
(2, 'food'),
(5, 'gourmet'),
(6, 'party'),
(3, 'service'),
(7, 'wedding');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facility_id` (`facility_id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `facility_tag`
--
ALTER TABLE `facility_tag`
  ADD PRIMARY KEY (`facility_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `facilities`
--
ALTER TABLE `facilities`
  ADD CONSTRAINT `facilities_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`);

--
-- Constraints for table `facility_tag`
--
ALTER TABLE `facility_tag`
  ADD CONSTRAINT `facility_tag_ibfk_1` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `facility_tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
