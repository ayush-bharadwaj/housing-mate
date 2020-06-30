-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 28, 2020 at 11:28 PM
-- Server version: 5.7.24
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `housing-mate`
--

-- --------------------------------------------------------

--
-- Table structure for table `ahouses`
--

CREATE TABLE `ahouses` (
  `houseId` int(10) NOT NULL,
  `houseNumber` varchar(128) NOT NULL,
  `houseFloor` varchar(128) NOT NULL,
  `houseOwnerId` int(11) NOT NULL,
  `housePreference` enum('Rent','Sale') DEFAULT NULL,
  `Price` varchar(128) NOT NULL,
  `avaliable` enum('yes','no') DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ahouses`
--

INSERT INTO `ahouses` (`houseId`, `houseNumber`, `houseFloor`, `houseOwnerId`, `housePreference`, `Price`, `avaliable`) VALUES
(1, 'A31', 'A Block, Third Floor', 2, 'Rent', '20000', 'yes'),
(2, 'A32', 'A Block, Third Floor', 2, 'Sale', '2000000', 'yes'),
(3, 'B17', 'B Block, First Floor', 1, 'Rent', '22000', 'yes'),
(4, 'C12', 'C Block, First Floor', 1, 'Rent', '22200', 'yes'),
(5, 'A12', 'A Block, First Floor', 1, 'Sale', '1220000', 'yes'),
(6, 'B22', 'B Block, Second Floor', 3, 'Rent', '23000', 'yes'),
(7, 'B25', 'B Block, Second Floor', 3, 'Rent', '24200', 'yes'),
(8, 'B12', 'B Block, First Floor', 1, 'Rent', '23000', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `requestId` int(10) NOT NULL,
  `tenantId` int(11) NOT NULL,
  `houseId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`requestId`, `tenantId`, `houseId`) VALUES
(1, 4, 2),
(2, 5, 5),
(3, 4, 4),
(4, 4, 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(10) NOT NULL,
  `userName` varchar(128) NOT NULL,
  `userType` enum('Tenant','Owner') DEFAULT 'Tenant',
  `userEmail` varchar(128) NOT NULL,
  `userPassword` varchar(128) NOT NULL,
  `userPreference` enum('Sale','Rent') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `userName`, `userType`, `userEmail`, `userPassword`, `userPreference`) VALUES
(1, 'Owner1', 'Owner', 'owner1@test.com', 'owner1', NULL),
(2, 'Owner2', 'Owner', 'owner2@test.com', 'owner2', NULL),
(3, 'Owner3', 'Owner', 'owner3@test.com', 'owner3', NULL),
(4, 'Tenant1', 'Tenant', 'tenant1@test.com', 'tenant1', 'Rent'),
(5, 'Tenant2', 'Tenant', 'tenant2@test.com', 'tenant2', 'Sale'),
(9, 'signupowner', 'Owner', 'signupowner@test.com', 'signupowner', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ahouses`
--
ALTER TABLE `ahouses`
  ADD PRIMARY KEY (`houseId`),
  ADD KEY `houseOwnertId` (`houseOwnerId`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`requestId`),
  ADD KEY `tenantId` (`tenantId`),
  ADD KEY `houseId` (`houseId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userEmail` (`userEmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ahouses`
--
ALTER TABLE `ahouses`
  MODIFY `houseId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `requestId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ahouses`
--
ALTER TABLE `ahouses`
  ADD CONSTRAINT `ahouses_ibfk_1` FOREIGN KEY (`houseOwnerId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`tenantId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`houseId`) REFERENCES `ahouses` (`houseId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
