-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 16, 2024 at 11:24 PM
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
-- Database: `simple_lamp`
--

-- --------------------------------------------------------

--
-- Table structure for table `upload_images`
--

CREATE TABLE `upload_images` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT '',
  `filename` varchar(255) DEFAULT '',
  `timeline` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(500) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL,
  `writer` varchar(500) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL,
  `age` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `upload_images`
--

INSERT INTO `upload_images` (`id`, `username`, `filename`, `timeline`, `name`, `writer`, `age`) VALUES
(57, 'a', '666f5448818a0.jpg', '2024-06-16 21:08:24', 'سلام', 'خودم', 1390),
(58, 'a', '666f545f6367c.jpeg', '2024-06-16 21:08:47', 'سلام بر تو', 'سارا', 1388),
(59, 'a', '666f5661d29d3.jpg', '2024-06-16 21:17:21', 'dsfds', 'dsfds', 555),
(60, 'a', '666f56796a98a.jpg', '2024-06-16 21:17:45', 'dsfds', 'dsfds', 555),
(62, 'a', '666f569e8a460.jpg', '2024-06-16 21:18:22', 'few', 'ewfw', 242);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(2, 'a', '$2y$10$Ly6a..XMUVdRRyICc8AsNe8pSE3gkyZ2YmKwdIbjMD3LXuwYu2xJu'),
(23, 'dsfsdf', 'dsfdsfdss'),
(25, 'al', '$2y$10$.hvXxMCdaIr7llHYSJqcs.FAc2oFBZ3VYduyLNihbED2WoOtfSNTi'),
(111, 'ab', '$2y$10$DaFhryI7BiM.us7w3pMb/eZEIkMUv6bH2VvBrAltovXUg3/4ukY2e'),
(112, 'pouria_azad1', '$2y$10$.zig8ClMbaF61zQGkoFqEONlmaaFW86jZttP1pKq.XBTWHZcIrSLC'),
(113, 'pouria_azad1', '$2y$10$mW3Sp4i4Y.MSkmAypxPCUeHEkAm3OcDH6WsHGqMIvRUV5lIS00xgC');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `upload_images`
--
ALTER TABLE `upload_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `upload_images`
--
ALTER TABLE `upload_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
