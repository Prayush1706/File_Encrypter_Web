-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2025 at 01:46 PM
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
-- Database: `file_encrypter`
--

-- --------------------------------------------------------

--
-- Table structure for table `encrypted_files`
--

CREATE TABLE `encrypted_files` (
  `id` int(11) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `encrypted_filename` varchar(255) NOT NULL,
  `encryption_keys` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `encrypted_files`
--

INSERT INTO `encrypted_files` (`id`, `original_filename`, `encrypted_filename`, `encryption_keys`, `created_at`) VALUES
(7, 'normal.txt', 'encrypted_1759232689_normal.txt', '[[168,127,2,128,136,208,77,103,80,236,99,185,227,35,145,223,152,81,219,231,159],[245,15,58,144,73,135,60,146,92,34,217,245,100,113,127,76,101,29,151,210,208,14,225,35,191,41,130,213,189,129,95,96,134]]', '2025-09-30 17:14:49'),
(8, 'norma1.txt', 'encrypted_1759232695_norma1.txt', '[[65,97,223,234,66,167,183,173,192,176,144,40,69,97,62,179,108,187,166,49],[]]', '2025-09-30 17:14:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `encrypted_files`
--
ALTER TABLE `encrypted_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_encrypted_filename` (`encrypted_filename`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `encrypted_files`
--
ALTER TABLE `encrypted_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
