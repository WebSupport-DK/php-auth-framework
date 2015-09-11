-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Vært: localhost
-- Genereringstid: 11. 09 2015 kl. 19:49:15
-- Serverversion: 5.6.26
-- PHP-version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php-auth-framework`
--

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `Roles`
--

CREATE TABLE IF NOT EXISTS `Roles` (
  `ID` int(11) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Role` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

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
  `Activation_token` varchar(64) NOT NULL,
  `Reactivation_token` varchar(64) NOT NULL,
  `Last_login` int(11) DEFAULT NULL,
  `Timeout` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `Users`
--

INSERT INTO `Users` (`ID`, `Created`, `Updated`, `Username`, `Email`, `Password`, `Firstname`, `Lastname`, `Role_ID`, `Status_ID`, `Activation_Key`, `Reactivation_Key`, `Last_login`, `Timeout`) VALUES
(1, 1441975753, 0, 'demo', 'demo@email.com', '$2y$07$pLnzm4pUH5FoWjiyEnVQK.1e4j1t5XDPLPAmatemc8P.m.97c.CGm', 'Demo', 'User', 1, 1, 'qS(e-Kv?G8)eit242pG5d7nywHT*#CRxfeDtRZcjZeifsb*p]i#-5v=o3r8e4{u0', '', 1441985272, NULL);

--
-- Begrænsninger for dumpede tabeller
--

--
-- Indeks for tabel `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`ID`);

--
-- Indeks for tabel `Sessions`
--
ALTER TABLE `Sessions`
  ADD PRIMARY KEY (`ID`);

--
-- Indeks for tabel `Status`
--
ALTER TABLE `Status`
  ADD PRIMARY KEY (`ID`);

--
-- Indeks for tabel `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`ID`);

--
-- Brug ikke AUTO_INCREMENT for slettede tabeller
--

--
-- Tilføj AUTO_INCREMENT i tabel `Roles`
--
ALTER TABLE `Roles`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- Tilføj AUTO_INCREMENT i tabel `Sessions`
--
ALTER TABLE `Sessions`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- Tilføj AUTO_INCREMENT i tabel `Status`
--
ALTER TABLE `Status`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Tilføj AUTO_INCREMENT i tabel `Users`
--
ALTER TABLE `Users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
