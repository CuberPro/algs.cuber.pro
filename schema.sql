-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2016 at 12:56 PM
-- Server version: 5.6.31
-- PHP Version: 7.0.7

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
  `cube` varchar(10) NOT NULL COMMENT 'cube applied to',
  `subset` varchar(20) NOT NULL COMMENT 'subset of the case',
  `sequence` int(11) NOT NULL COMMENT 'sequence in the subset',
  UNIQUE KEY `alg` (`alg`,`subset`) USING BTREE,
  KEY `case` (`cube`,`subset`,`sequence`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `Algs_For_Case`:
--   `alg`
--       `Algs` -> `id`
--   `cube`
--       `Cases` -> `cube`
--   `subset`
--       `Cases` -> `subset`
--   `sequence`
--       `Cases` -> `sequence`
--

-- --------------------------------------------------------

--
-- Table structure for table `Cases`
--

CREATE TABLE IF NOT EXISTS `Cases` (
  `cube` varchar(10) NOT NULL COMMENT 'cube applied to',
  `subset` varchar(20) NOT NULL COMMENT 'subset this case belongs to',
  `sequence` int(11) NOT NULL COMMENT 'sequence in the subset',
  `alias` varchar(50) DEFAULT NULL COMMENT 'alias for a case',
  `state` varchar(300) NOT NULL COMMENT 'sticker states',
  PRIMARY KEY (`cube`,`subset`,`sequence`),
  UNIQUE KEY `alias` (`cube`,`subset`,`alias`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `Cases`:
--   `cube`
--       `Subsets` -> `cube`
--   `subset`
--       `Subsets` -> `name`
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

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Algs_For_Case`
--
ALTER TABLE `Algs_For_Case`
  ADD CONSTRAINT `Algs_For_Case_ibfk_1` FOREIGN KEY (`alg`) REFERENCES `Algs` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Algs_For_Case_ibfk_2` FOREIGN KEY (`cube`,`subset`,`sequence`) REFERENCES `Cases` (`cube`, `subset`, `sequence`) ON UPDATE CASCADE;

--
-- Constraints for table `Cases`
--
ALTER TABLE `Cases`
  ADD CONSTRAINT `Cases_ibfk_1` FOREIGN KEY (`cube`,`subset`) REFERENCES `Subsets` (`cube`, `name`) ON UPDATE CASCADE;

--
-- Constraints for table `Subsets`
--
ALTER TABLE `Subsets`
  ADD CONSTRAINT `Subsets_ibfk_1` FOREIGN KEY (`cube`) REFERENCES `Cubes` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
