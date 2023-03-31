-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2023 at 04:50 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydrive`
--

-- --------------------------------------------------------

--
-- Table structure for table `file_system`
--

CREATE TABLE `file_system` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('file','folder') NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  `size` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `file_system`
--

INSERT INTO `file_system` (`id`, `name`, `type`, `parent_id`, `path`, `size`, `updated_at`) VALUES
(42, 'logo.png', 'file', NULL, 'uploads/logo.png', 65400, '2023-03-31 14:00:56'),
(43, 'adfa', 'folder', NULL, 'uploads/adfa', 0, '2023-03-31 14:00:33'),
(44, 'todoes count.JPG', 'file', NULL, 'uploads/todoes count.JPG', 52088, '2023-03-31 14:00:47'),
(45, 'adfa', 'folder', 43, 'uploads/adfa/adfa', 0, '2023-03-31 14:01:05'),
(46, 'biner', 'folder', NULL, 'uploads/biner', 0, '2023-03-31 14:01:36'),
(47, 'todoes count.JPG', 'file', 45, 'uploads/adfa/adfa/todoes count.JPG', 52088, '2023-03-31 14:01:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `file_system`
--
ALTER TABLE `file_system`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `file_system`
--
ALTER TABLE `file_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `file_system`
--
ALTER TABLE `file_system`
  ADD CONSTRAINT `file_system_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `file_system` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
