-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Host: 88.79.198.46:3306
-- Erstellungszeit: 06. Nov 2016 um 13:19
-- Server-Version: 5.6.30
-- PHP-Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `btcpics`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pics`
--

CREATE TABLE `pics` (
  `ID` int(11) NOT NULL,
  `approved` tinyint(1) DEFAULT NULL COMMENT '1 for approved images',
  `reviewed` tinyint(1) DEFAULT NULL COMMENT '1 if pic was reviewed',
  `fileExt` varchar(5) NOT NULL,
  `btcAddress` varchar(36) NOT NULL,
  `description` text NOT NULL,
  `album` varchar(36) DEFAULT NULL,
  `price` float NOT NULL,
  `received` float NOT NULL,
  `lastUpdatedDate` int(8) NOT NULL COMMENT 'Date format: Ymd',
  `lastUpdatedTime` int(6) NOT NULL COMMENT 'Time format: His',
  `email` varchar(62) NOT NULL,
  `spamtyLink` varchar(62) DEFAULT NULL,
  `ip` varchar(45) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `pics`
--

INSERT INTO `pics` (`ID`, `approved`, `reviewed`, `fileExt`, `btcAddress`, `description`, `album`, `price`, `received`, `lastUpdatedDate`, `lastUpdatedTime`, `email`, `spamtyLink`, `ip`, `date`) VALUES
(1, 1, 1, 'jpg', '13nr9dTqCYdrhifeVaS6AwAUgz2U4EGxXZ', 'Saint Pauls Cathedral in London United Kingdom (UK)', '31eafa2a7fcbf07c0f0928f3409a7f92', 0.02, 0.014, 20160712, 135406, '', '', '46.5.0.107', '2016-01-23 13:45:19');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `pics`
--
ALTER TABLE `pics`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `pics`
--
ALTER TABLE `pics`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;