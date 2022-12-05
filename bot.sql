-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 01, 2019 at 09:05 PM
-- Server version: 5.7.21-20-beget-5.7.21-20-1-log
-- PHP Version: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jora13y6_eup`
--

-- --------------------------------------------------------

--
-- Table structure for table `bot`
--
-- Creation: Jan 20, 2019 at 10:43 AM
-- Last update: Feb 01, 2019 at 07:43 AM
--

DROP TABLE IF EXISTS `bot`;
CREATE TABLE `bot` (
  `id` int(255) NOT NULL,
  `user` int(255) NOT NULL,
  `command` text NOT NULL,
  `step` int(5) NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bot`
--

INSERT INTO `bot` (`id`, `user`, `command`, `step`, `data`) VALUES
(43, 732195445, '/buy', 2, '4;Yes'),
(46, 781721885, '/buy', 2, '3;Yes'),
(121, 401230431, '/buy', 1, '2'),
(124, 527371836, '/buy', 2, '1;Yes'),
(129, 472766147, '/faq', 0, ''),
(132, 781030005, '/buy', 0, ''),
(133, 555606315, '/faq', 0, ''),
(134, 793440614, '/faq', 0, ''),
(136, 693935842, '/buy', 2, '1;Yes'),
(140, 486608466, '/faq', 0, ''),
(145, 692993256, '/buy', 0, ''),
(151, 666366429, '/buy', 1, '2'),
(154, 583777971, '/faq', 0, ''),
(156, 336332110, '/buy', 2, '1;Yes'),
(158, 529420619, '/faq', 0, ''),
(159, 671890164, '/faq', 0, ''),
(167, 575084142, '/buy', 2, '2;Yes'),
(170, 772355125, '/buy', 0, ''),
(171, 528790296, '/buy', 2, '1;Yes'),
(172, 707500972, '/faq', 0, ''),
(173, 679575239, '/faq', 0, ''),
(174, 361143160, '/buy', 0, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bot`
--
ALTER TABLE `bot`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bot`
--
ALTER TABLE `bot`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
