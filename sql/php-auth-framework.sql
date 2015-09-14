-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Vært: localhost
-- Genereringstid: 14. 09 2015 kl. 18:56:45
-- Serverversion: 5.6.26
-- PHP-version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php-mvc-cmf`
--

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `Roles`
--

CREATE TABLE IF NOT EXISTS `Roles` (
  `ID` int(11) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `Roles`
--

INSERT INTO `Roles` (`ID`, `Name`, `Role`) VALUES
(1, 'admin', '{"admin": 1,"editor": 1,"author": 1,"subscriber": 1}'),
(2, 'editor', '{"admin": 0,"editor": 1,"author": 1,"subscriber": 1}'),
(3, 'author', '{"admin": 0,"editor": 0,"author": 1,"subscriber": 1}'),
(4, 'subscriber', '{"admin": 0,"editor": 0,"author": 0,"subscriber": 1}');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `Sessions`
--

CREATE TABLE IF NOT EXISTS `Sessions` (
  `ID` int(11) NOT NULL,
  `Token` varchar(64) NOT NULL,
  `User_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `Sessions`
--

INSERT INTO `Sessions` (`ID`, `Token`, `User_ID`) VALUES
(10, 'vRM7F3/q8zRek75kYvXfn8F/WiyakcxfdbSKBSGIWlDb4UBVSqForNCMN3o06g==', 1);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `Status`
--

CREATE TABLE IF NOT EXISTS `Status` (
  `ID` int(11) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `Status`
--

INSERT INTO `Status` (`ID`, `Name`, `Status`) VALUES
(1, 'Inactive', 'inactive'),
(2, 'Activ', 'activ'),
(3, 'Blocked', 'blocked');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `ID` int(11) NOT NULL,
  `Created` int(11) NOT NULL,
  `Updated` int(11) DEFAULT NULL,
  `Username` varchar(20) NOT NULL,
  `Email` varchar(20) NOT NULL,
  `Password` varchar(60) NOT NULL,
  `Firstname` varchar(50) NOT NULL,
  `Lastname` varchar(50) NOT NULL,
  `Role_ID` int(11) NOT NULL,
  `Status_ID` int(11) NOT NULL,
  `Auth_token` varchar(64) NOT NULL,
  `Reset_token` varchar(64) NOT NULL,
  `Last_login` int(11) DEFAULT NULL,
  `Timeout` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
