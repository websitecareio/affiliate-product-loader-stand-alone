-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Vært: mysql25.unoeuro.com
-- Genereringstid: 08. 09 2020 kl. 10:10:29
-- Serverversion: 5.7.31-34-log
-- PHP-version: 7.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dicm_dk_db_kundesider`
--

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `wcio_apl_feeds`
--

CREATE TABLE `wcio_apl_feeds` (
  `id` int(255) NOT NULL,
  `feedUrl` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `updated` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `wcio_apl_products`
--

CREATE TABLE `wcio_apl_products` (
  `id` int(255) NOT NULL,
  `feedId` int(10) NOT NULL,
  `feedUrl` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `productDealer` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `productId` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `productEan` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `productName` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `productDescription` varchar(10000) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `productPrice` int(100) DEFAULT NULL,
  `productPriceOld` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `productPriceDiscount` int(10) DEFAULT NULL,
  `productImage` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `productUrl` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `created` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Begrænsninger for dumpede tabeller
--

--
-- Indeks for tabel `wcio_apl_feeds`
--
ALTER TABLE `wcio_apl_feeds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `feedUrl` (`feedUrl`);

--
-- Indeks for tabel `wcio_apl_products`
--
ALTER TABLE `wcio_apl_products`
  ADD PRIMARY KEY (`id`);

--
-- Brug ikke AUTO_INCREMENT for slettede tabeller
--

--
-- Tilføj AUTO_INCREMENT i tabel `wcio_apl_feeds`
--
ALTER TABLE `wcio_apl_feeds`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- Tilføj AUTO_INCREMENT i tabel `wcio_apl_products`
--
ALTER TABLE `wcio_apl_products`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
