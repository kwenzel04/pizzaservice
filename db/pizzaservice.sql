-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 05. Feb 2020 um 19:38
-- Server-Version: 10.4.11-MariaDB
-- PHP-Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `pizzaservice`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `angebot`
--

CREATE TABLE `angebot` (
  `PizzaNummer` int(3) NOT NULL,
  `PizzaName` varchar(15) NOT NULL,
  `Bilddatei` varchar(128) NOT NULL,
  `Preis` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `angebot`
--

INSERT INTO `angebot` (`PizzaNummer`, `PizzaName`, `Bilddatei`, `Preis`) VALUES
(1, 'BBQ', 'bbq.png', 7.49),
(2, 'Beef', 'beef.png', 7.29),
(3, 'Hühnchen', 'chicken.png', 7.79),
(4, 'Gyros', 'gyros.png', 7.99);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestelltepizza`
--

CREATE TABLE `bestelltepizza` (
  `PizzaID` int(10) NOT NULL,
  `fBestellungID` int(10) NOT NULL,
  `fPizzanummer` int(3) NOT NULL,
  `Status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellung`
--

CREATE TABLE `bestellung` (
  `BestellungID` int(10) NOT NULL,
  `Adresse` varchar(128) NOT NULL,
  `Bestellzeitpunkt` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `angebot`
--
ALTER TABLE `angebot`
  ADD PRIMARY KEY (`PizzaNummer`);

--
-- Indizes für die Tabelle `bestelltepizza`
--
ALTER TABLE `bestelltepizza`
  ADD PRIMARY KEY (`PizzaID`),
  ADD KEY `Pizzanummer` (`fPizzanummer`),
  ADD KEY `BestellungID` (`fBestellungID`);

--
-- Indizes für die Tabelle `bestellung`
--
ALTER TABLE `bestellung`
  ADD PRIMARY KEY (`BestellungID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `angebot`
--
ALTER TABLE `angebot`
  MODIFY `PizzaNummer` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `bestelltepizza`
--
ALTER TABLE `bestelltepizza`
  MODIFY `PizzaID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `bestellung`
--
ALTER TABLE `bestellung`
  MODIFY `BestellungID` int(10) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bestelltepizza`
--
ALTER TABLE `bestelltepizza`
  ADD CONSTRAINT `BestellungID` FOREIGN KEY (`fBestellungID`) REFERENCES `bestellung` (`BestellungID`),
  ADD CONSTRAINT `Pizzanummer` FOREIGN KEY (`fPizzanummer`) REFERENCES `angebot` (`PizzaNummer`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
