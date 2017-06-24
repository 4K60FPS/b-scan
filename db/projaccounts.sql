-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 11, 2017 at 10:41 PM
-- Server version: 10.1.22-MariaDB-cll-lve
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brutefor_cheats`
--

-- --------------------------------------------------------

--
-- Table structure for table `projaccounts`
--

CREATE TABLE `projaccounts` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `register_time` date NOT NULL,
  `last_login` date NOT NULL,
  `request_scan` tinyint(1) NOT NULL DEFAULT '0',
  `assigned_ip` text,
  `assigned_port` varchar(6) NOT NULL DEFAULT '80'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projaccounts`
--

INSERT INTO `projaccounts` (`id`, `username`, `password`, `email`, `register_time`, `last_login`, `request_scan`, `assigned_ip`, `assigned_port`) VALUES
(1, 'admin', 'a3a2754f94b4f8c1ca8d29290bc37ba90cedf0e13a9e702a829740835e5ed564', 'admin@admin.com', '2017-06-09', '2017-06-11', 1, '120.76.136.195', '80');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `projaccounts`
--
ALTER TABLE `projaccounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `projaccounts`
--
ALTER TABLE `projaccounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
