-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 13, 2017 at 04:41 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_reglab`
--

-- --------------------------------------------------------

--
-- Table structure for table `t_audit_trail`
--

CREATE TABLE IF NOT EXISTS `t_audit_trail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `script` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `table` varchar(255) DEFAULT NULL,
  `field` varchar(255) DEFAULT NULL,
  `keyvalue` longtext,
  `oldvalue` longtext,
  `newvalue` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=85 ;

--
-- Dumping data for table `t_audit_trail`
--

INSERT INTO `t_audit_trail` (`id`, `datetime`, `script`, `user`, `action`, `table`, `field`, `keyvalue`, `oldvalue`, `newvalue`) VALUES
(1, '2017-06-13 09:20:24', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(2, '2017-06-13 09:22:47', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(3, '2017-06-13 09:22:49', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(4, '2017-06-13 09:28:42', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(5, '2017-06-13 09:33:00', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(6, '2017-06-13 09:34:11', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(7, '2017-06-13 09:34:14', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(8, '2017-06-13 09:34:40', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(9, '2017-06-13 09:37:10', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(10, '2017-06-13 09:37:17', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(11, '2017-06-13 09:37:21', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(12, '2017-06-13 09:37:44', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(13, '2017-06-13 09:37:47', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(14, '2017-06-13 09:37:53', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(15, '2017-06-13 09:37:56', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(16, '2017-06-13 09:38:17', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(17, '2017-06-13 09:38:24', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(18, '2017-06-13 09:38:38', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(19, '2017-06-13 09:38:42', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(20, '2017-06-13 10:01:33', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(21, '2017-06-13 10:01:35', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(22, '2017-06-13 11:07:21', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(23, '2017-06-13 11:10:54', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(24, '2017-06-13 11:10:59', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(25, '2017-06-13 11:18:03', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(26, '2017-06-13 11:18:09', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(27, '2017-06-13 11:18:12', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(28, '2017-06-13 11:18:19', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(29, '2017-06-13 11:36:01', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(30, '2017-06-13 11:50:30', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(31, '2017-06-13 11:50:33', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(32, '2017-06-13 11:52:12', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(33, '2017-06-13 11:52:14', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(34, '2017-06-13 11:52:34', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(35, '2017-06-13 11:52:38', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(36, '2017-06-13 11:53:10', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(37, '2017-06-13 11:53:18', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(38, '2017-06-13 11:53:48', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(39, '2017-06-13 11:53:52', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(40, '2017-06-13 11:55:25', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(41, '2017-06-13 11:55:53', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(42, '2017-06-13 11:56:04', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(43, '2017-06-13 11:56:07', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(44, '2017-06-13 13:08:22', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(45, '2017-06-13 13:08:25', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(46, '2017-06-13 13:08:42', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(47, '2017-06-13 13:55:47', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(48, '2017-06-13 13:59:00', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(49, '2017-06-13 13:59:04', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(50, '2017-06-13 13:59:25', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(51, '2017-06-13 13:59:29', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(52, '2017-06-13 14:04:32', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(53, '2017-06-13 14:04:46', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(54, '2017-06-13 15:20:39', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(55, '2017-06-13 15:20:41', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(56, '2017-06-13 15:20:57', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(57, '2017-06-13 15:21:01', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(58, '2017-06-13 15:21:20', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(59, '2017-06-13 15:25:26', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(60, '2017-06-13 15:25:38', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(61, '2017-06-13 15:25:41', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(62, '2017-06-13 15:31:41', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(63, '2017-06-13 15:31:44', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(64, '2017-06-13 15:31:51', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(65, '2017-06-13 15:31:54', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(66, '2017-06-13 15:41:45', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(67, '2017-06-13 15:41:48', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(68, '2017-06-13 15:50:51', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(69, '2017-06-13 15:51:01', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(70, '2017-06-13 15:51:40', '/reglab/t_praktikumadd.php', '-1', 'A', 't_praktikum', 'Nama', '1', '', 'Praktikum 1'),
(71, '2017-06-13 15:51:40', '/reglab/t_praktikumadd.php', '-1', 'A', 't_praktikum', 'Biaya', '1', '', '70000'),
(72, '2017-06-13 15:51:40', '/reglab/t_praktikumadd.php', '-1', 'A', 't_praktikum', 'PraktikumID', '1', '', '1'),
(73, '2017-06-13 15:52:17', '/reglab/t_praktikumadd.php', '-1', 'A', 't_praktikum', 'Nama', '2', '', 'Praktikum 2'),
(74, '2017-06-13 15:52:17', '/reglab/t_praktikumadd.php', '-1', 'A', 't_praktikum', 'Biaya', '2', '', '70000.00'),
(75, '2017-06-13 15:52:17', '/reglab/t_praktikumadd.php', '-1', 'A', 't_praktikum', 'PraktikumID', '2', '', '2'),
(76, '2017-06-13 15:53:10', '/reglab/t_praktikumlist.php', '-1', 'A', 't_praktikum', 'Nama', '3', '', 'Praktikum 3'),
(77, '2017-06-13 15:53:10', '/reglab/t_praktikumlist.php', '-1', 'A', 't_praktikum', 'Biaya', '3', '', '70000.00'),
(78, '2017-06-13 15:53:10', '/reglab/t_praktikumlist.php', '-1', 'A', 't_praktikum', 'PraktikumID', '3', '', '3'),
(79, '2017-06-13 15:59:41', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(80, '2017-06-13 15:59:44', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', ''),
(81, '2017-06-13 16:00:02', '/reglab/logout.php', 'Administrator', 'logout', '::1', '', '', '', ''),
(82, '2017-06-13 16:00:05', '/reglab/login.php', 'dodo', 'login', '::1', '', '', '', ''),
(83, '2017-06-13 16:03:50', '/reglab/logout.php', 'dodo', 'logout', '::1', '', '', '', ''),
(84, '2017-06-13 16:03:53', '/reglab/login.php', 'admin', 'login', '::1', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `t_daftard`
--

CREATE TABLE IF NOT EXISTS `t_daftard` (
  `DaftradID` int(11) NOT NULL AUTO_INCREMENT,
  `DaftarmID` int(11) NOT NULL,
  `PraktikumID` int(11) NOT NULL,
  `Tgl` datetime NOT NULL,
  PRIMARY KEY (`DaftradID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `t_daftard`
--


-- --------------------------------------------------------

--
-- Table structure for table `t_daftarm`
--

CREATE TABLE IF NOT EXISTS `t_daftarm` (
  `DaftarmID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT '0',
  `TglJam` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `BuktiBayar` varchar(255) NOT NULL DEFAULT '0',
  `JumlahBayar` float(10,2) NOT NULL DEFAULT '0.00',
  `Acc` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`DaftarmID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `t_daftarm`
--


-- --------------------------------------------------------

--
-- Table structure for table `t_praktikum`
--

CREATE TABLE IF NOT EXISTS `t_praktikum` (
  `PraktikumID` int(11) NOT NULL AUTO_INCREMENT,
  `Nama` varchar(50) NOT NULL,
  `Biaya` float(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`PraktikumID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `t_praktikum`
--

INSERT INTO `t_praktikum` (`PraktikumID`, `Nama`, `Biaya`) VALUES
(1, 'Praktikum 1', 70000.00),
(2, 'Praktikum 2', 70000.00),
(3, 'Praktikum 3', 70000.00);

-- --------------------------------------------------------

--
-- Table structure for table `t_user`
--

CREATE TABLE IF NOT EXISTS `t_user` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Nama` varchar(50) DEFAULT NULL,
  `NoHandphone` varchar(24) DEFAULT NULL,
  `Email` varchar(30) DEFAULT NULL,
  `Password` varchar(50) NOT NULL DEFAULT '',
  `UserLevel` int(11) DEFAULT NULL,
  `UserName` varchar(20) NOT NULL DEFAULT '',
  `NIM` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Username` (`UserName`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `t_user`
--

INSERT INTO `t_user` (`UserID`, `Nama`, `NoHandphone`, `Email`, `Password`, `UserLevel`, `UserName`, `NIM`) VALUES
(1, 'Davolio', '(206) 555-9857', 'nancy@northwind.com', '1234', 1, 'nancy', NULL),
(2, 'Fuller', '(206) 555-9482', 'andrew@northwind.com', '1234', 2, 'andrew', NULL),
(3, 'Leverling', '(206) 555-3412', 'janet@northwind.com', '1234', 1, 'janet', NULL),
(4, 'Peacock', '(206) 555-8122', 'margaret@northwind.com', '1234', 1, 'margaret', NULL),
(5, 'Buchanan', '(71) 555-4848', 'steven@northwind.com', '1234', 2, 'steven', NULL),
(6, 'Suyama', '(71) 555-7773', 'michael@northwind.com', '1234', 1, 'michael', NULL),
(7, 'King', '(71) 555-5598', 'robert@northwind.com', '1234', 1, 'robert', NULL),
(8, 'Callahan', '(206) 555-1189', 'laura@northwind.com', '1234', 1, 'laura', NULL),
(9, 'Dodsworth', '(71) 555-4444', 'anne@northwind.com', '1234', 1, 'anne', NULL),
(10, 'dodo ananto', '08113422516', 'dodoananto@gmail.com', '844449204749243ea490b2e996ccdabd', 1, 'dodo', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_user_levels`
--

CREATE TABLE IF NOT EXISTS `t_user_levels` (
  `userlevelid` int(11) NOT NULL,
  `userlevelname` varchar(255) NOT NULL,
  PRIMARY KEY (`userlevelid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_user_levels`
--

INSERT INTO `t_user_levels` (`userlevelid`, `userlevelname`) VALUES
(-2, 'Anonymous'),
(-1, 'Administrator'),
(0, 'Default'),
(1, 'Mahasiswa');

-- --------------------------------------------------------

--
-- Table structure for table `t_user_level_permissions`
--

CREATE TABLE IF NOT EXISTS `t_user_level_permissions` (
  `userlevelid` int(11) NOT NULL,
  `tablename` varchar(255) NOT NULL,
  `permission` int(11) NOT NULL,
  PRIMARY KEY (`userlevelid`,`tablename`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_user_level_permissions`
--

INSERT INTO `t_user_level_permissions` (`userlevelid`, `tablename`, `permission`) VALUES
(-2, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_user', 0),
(-2, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_audit_trail', 0),
(-2, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_user_level_permissions', 0),
(-2, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_user_levels', 0),
(0, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_user', 0),
(0, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_audit_trail', 0),
(0, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_user_level_permissions', 0),
(0, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_user_levels', 0),
(0, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}c_home.php', 111),
(1, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_user', 0),
(1, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_audit_trail', 0),
(1, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_user_level_permissions', 0),
(1, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_user_levels', 0),
(1, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}c_home.php', 111),
(1, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_daftarm', 111),
(1, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_praktikum', 0),
(1, '{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_daftard', 111);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
