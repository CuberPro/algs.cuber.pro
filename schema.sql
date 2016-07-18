-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2016 at 06:24 AM
-- Server version: 5.6.31
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `algs.cuber.pro`
--
CREATE DATABASE IF NOT EXISTS `algs.cuber.pro` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `algs.cuber.pro`;

-- --------------------------------------------------------

--
-- Table structure for table `Algs`
--

CREATE TABLE IF NOT EXISTS `Algs` (
  `id` char(32) NOT NULL COMMENT 'a hash of the alg',
  `text` varchar(200) NOT NULL COMMENT 'text representation of the alg',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `Algs`:
--

-- --------------------------------------------------------

--
-- Table structure for table `Algs_For_Case`
--

CREATE TABLE IF NOT EXISTS `Algs_For_Case` (
  `alg` char(32) NOT NULL COMMENT 'id of an alg',
  `case` char(32) NOT NULL COMMENT 'id of the case',
  UNIQUE KEY `alg_for_case` (`alg`,`case`) USING BTREE,
  KEY `Algs_For_Case_ibfk_2` (`case`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `Algs_For_Case`:
--   `alg`
--       `Algs` -> `id`
--   `case`
--       `Cases` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `Auth`
--

CREATE TABLE IF NOT EXISTS `Auth` (
  `user_id` int(11) NOT NULL COMMENT 'user id',
  `source` varchar(10) NOT NULL COMMENT 'source site id',
  `source_id` varchar(255) NOT NULL COMMENT 'user id in source site',
  `source_name` varchar(255) NOT NULL COMMENT 'user name in source site',
  PRIMARY KEY (`user_id`,`source`),
  KEY `source_id` (`source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `Auth`:
--   `user_id`
--       `Users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `Cases`
--

CREATE TABLE IF NOT EXISTS `Cases` (
  `id` char(32) NOT NULL COMMENT 'md5 hash of state',
  `state` varchar(300) CHARACTER SET ascii NOT NULL COMMENT 'sticker states',
  PRIMARY KEY (`id`),
  UNIQUE KEY `state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `Cases`:
--

-- --------------------------------------------------------

--
-- Table structure for table `Cases_In_Subset`
--

CREATE TABLE IF NOT EXISTS `Cases_In_Subset` (
  `cube` varchar(10) NOT NULL COMMENT 'cube id',
  `subset` varchar(50) NOT NULL COMMENT 'subset name',
  `case` char(32) NOT NULL COMMENT 'case id',
  `sequence` int(11) NOT NULL COMMENT 'case order in subset',
  `alias` varchar(50) DEFAULT NULL COMMENT 'case alias',
  UNIQUE KEY `unique_case` (`cube`,`subset`,`sequence`,`case`) USING BTREE,
  UNIQUE KEY `unique_alias` (`cube`,`subset`,`sequence`,`alias`) USING BTREE,
  KEY `Cases_In_Subset_ibfk_2` (`case`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `Cases_In_Subset`:
--   `cube`
--       `Subsets` -> `cube`
--   `subset`
--       `Subsets` -> `name`
--   `case`
--       `Cases` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `Cubes`
--

CREATE TABLE IF NOT EXISTS `Cubes` (
  `id` varchar(10) NOT NULL COMMENT 'Cube Identifier',
  `name` varchar(50) NOT NULL COMMENT 'Cube Name',
  `size` int(11) NOT NULL COMMENT 'cube size',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `Cubes`:
--

-- --------------------------------------------------------

--
-- Table structure for table `Subsets`
--

CREATE TABLE IF NOT EXISTS `Subsets` (
  `cube` varchar(10) NOT NULL COMMENT 'Cube Applied To',
  `name` varchar(50) NOT NULL COMMENT 'Subset Name',
  `view` varchar(10) DEFAULT NULL COMMENT 'view type for rendering',
  PRIMARY KEY (`cube`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `Subsets`:
--   `cube`
--       `Cubes` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'user id',
  `email` varchar(255) NOT NULL COMMENT 'user email',
  `name` varchar(100) NOT NULL COMMENT 'user name',
  `password` varchar(255) NOT NULL COMMENT 'password hash',
  `wcaid` char(10) DEFAULT NULL COMMENT 'wca id of user',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'register time',
  `status` int(11) NOT NULL COMMENT 'user status',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `wcaid` (`wcaid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- RELATIONS FOR TABLE `Users`:
--

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Algs_For_Case`
--
ALTER TABLE `Algs_For_Case`
  ADD CONSTRAINT `Algs_For_Case_ibfk_1` FOREIGN KEY (`alg`) REFERENCES `Algs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Algs_For_Case_ibfk_2` FOREIGN KEY (`case`) REFERENCES `Cases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Auth`
--
ALTER TABLE `Auth`
  ADD CONSTRAINT `Auth_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Cases_In_Subset`
--
ALTER TABLE `Cases_In_Subset`
  ADD CONSTRAINT `Cases_In_Subset_ibfk_1` FOREIGN KEY (`cube`,`subset`) REFERENCES `Subsets` (`cube`, `name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Cases_In_Subset_ibfk_2` FOREIGN KEY (`case`) REFERENCES `Cases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Subsets`
--
ALTER TABLE `Subsets`
  ADD CONSTRAINT `Subsets_ibfk_1` FOREIGN KEY (`cube`) REFERENCES `Cubes` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
