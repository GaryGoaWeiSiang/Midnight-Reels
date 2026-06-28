-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2026 at 04:16 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `midnightreels`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `userID` int(11) NOT NULL,
  `address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`userID`, `address`) VALUES
(1, 'Skudai, Johor');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventoryID` int(11) NOT NULL,
  `videoID` int(11) NOT NULL,
  `inventoryStatus` enum('AVAILABLE','RENTED','BROKEN','UNAVAILABLE') NOT NULL DEFAULT 'AVAILABLE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventoryID`, `videoID`, `inventoryStatus`) VALUES
(1, 1, 'AVAILABLE'),
(2, 1, 'AVAILABLE'),
(3, 1, 'AVAILABLE'),
(4, 1, 'AVAILABLE'),
(5, 1, 'AVAILABLE'),
(6, 2, 'AVAILABLE'),
(7, 2, 'AVAILABLE'),
(8, 2, 'AVAILABLE'),
(9, 3, 'AVAILABLE'),
(10, 3, 'AVAILABLE'),
(11, 4, 'AVAILABLE'),
(12, 4, 'AVAILABLE'),
(13, 4, 'AVAILABLE'),
(14, 5, 'AVAILABLE'),
(15, 5, 'AVAILABLE'),
(16, 6, 'AVAILABLE'),
(17, 6, 'AVAILABLE');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentID` int(11) NOT NULL,
  `paymentAmount` decimal(10,2) NOT NULL,
  `paymentMethod` enum('CASH','CARD','QR') DEFAULT NULL,
  `paymentDateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `paymentStatus` enum('NOT PAID','PAID') DEFAULT NULL,
  `rentalID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rental`
--

CREATE TABLE `rental` (
  `rentalID` int(11) NOT NULL,
  `rentalBeginDate` date NOT NULL DEFAULT curdate(),
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rentalitem`
--

CREATE TABLE `rentalitem` (
  `rentalItemID` int(11) NOT NULL,
  `rentalDuration` int(11) NOT NULL,
  `dueRentalDate` date NOT NULL,
  `actualReturnDate` date DEFAULT NULL,
  `rentalID` int(11) NOT NULL,
  `inventoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `userID` int(11) NOT NULL,
  `staffID` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`userID`, `staffID`) VALUES
(3, 'ADM0001'),
(2, 'STM0001');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `phoneNumber` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('CUSTOMER','STORE MANAGER','ADMIN') NOT NULL,
  `accountCreationDate` date NOT NULL DEFAULT curdate(),
  `userStatus` enum('ACTIVE','DELETED') NOT NULL DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `emailAddress`, `phoneNumber`, `password`, `role`, `accountCreationDate`, `userStatus`) VALUES
(1, 'customer1', 'customer1@gmail.com', '0123456789', '$2y$10$E3jXcnxO.fj4ZgT.1yXQQOudmEJ4THr/ih0VgwUGfW5OzG3zjNfl2', 'CUSTOMER', '2026-06-28', 'ACTIVE'),
(2, 'storemanager1', 'storemanager1@gmail.com', '012345678910', '$2y$10$acmdz0Op33IVMxiTvXRwneh4zibwZSuDwRnIZvzVlWb.QqnLkTQEW', 'STORE MANAGER', '2026-06-28', 'ACTIVE'),
(3, 'admin1', 'admin1@gmail.com', '012345678', '$2y$10$1SDF43AZ.kGAtCwNcukFmuoaIvD9zMhZuDXW2oi83Gmx/NWwTdYx2', 'ADMIN', '2026-06-28', 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `videotape`
--

CREATE TABLE `videotape` (
  `videoID` int(11) NOT NULL,
  `videoName` varchar(100) NOT NULL,
  `videoDescription` varchar(1000) DEFAULT NULL,
  `videoGenre` enum('ACTION','COMEDY','SCI-FI','HORROR','ROMANCE') NOT NULL,
  `videoDuration` varchar(50) NOT NULL,
  `videoReleaseDate` date NOT NULL,
  `videoRentalPrice` decimal(10,2) NOT NULL,
  `videoImage` varchar(200) NOT NULL DEFAULT './img/default.png',
  `videoStatus` enum('AVAILABLE','DELETED') NOT NULL DEFAULT 'AVAILABLE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `videotape`
--

INSERT INTO `videotape` (`videoID`, `videoName`, `videoDescription`, `videoGenre`, `videoDuration`, `videoReleaseDate`, `videoRentalPrice`, `videoImage`, `videoStatus`) VALUES
(1, 'Attack on Cyberz', 'A mysterious, highly transmissible virus spreads across the internet, infecting all autonomous service machines, Bangboo, and Cyborgs. Those infected become highly aggressive out-of-control \"Cyberz,\" forcing human survivors to flee and resist the machine uprising.', 'ACTION', '1 hour 30 minutes', '2026-06-19', 1.90, './img/AttackOnCyberz.png', 'AVAILABLE'),
(2, '7710 and Its Cat', 'Abandoned in the Hollow by an investigator, Bangboo-7710 misunderstands its owner\'s instructions and identifies stray cat as its rescue target.', 'COMEDY', '1 hour 45 minutes', '2026-06-07', 2.50, './img/7710.png ', 'AVAILABLE'),
(3, 'Dimensional Musketeer', 'The movie follows a cool, unnamed sniper who combines Ether technology with his sniper rifle to a ridiculous degree.', 'SCI-FI', '1 hour 54 minutes', '2026-06-24', 3.50, './img/DimensionalMusketeers.png  ', 'AVAILABLE'),
(4, 'Enter The Ether', 'A Proxy who becomes infected by an unknown electronic virus. As the virus corrupts her system, she gradually loses the ability to distinguish between the virtual network and reality. She eventually falls into a deep \"sleep,\" where she must navigate the bizarre, cartoony electronic world of \"Ether\" to uncover the truth behind the virus and find her way back to the waking world.', 'HORROR', '1 hour', '2025-10-31', 3.25, './img/EnterTheEther.png  ', 'AVAILABLE'),
(5, 'Bangboo Knows!', 'A child-friendly educational program that teaches kids about avoiding Hollows in entertaining ways. During the live broadcast, while various \"accidents\" happen on stage, the host manages to turn the situation around using reasoning. Many New Eridu residents suspect these accidents are highly scripted parts of the show.', 'COMEDY', '1 hour', '2026-05-04', 1.90, './img/Knows.png', 'AVAILABLE'),
(6, 'Elevator', 'A mysterious, highly transmissible virus spreads across the internet, infecting all autonomous service machines, Bangboo, and Cyborgs. Those infected become highly aggressive out-of-control \"Cyberz,\" forcing human survivors to flee and resist the machine uprising.', 'HORROR', '1 hour 30 minutes', '2026-03-11', 3.50, './img/Elevator.png ', 'AVAILABLE'),
(7, 'Glorious Guardians', 'The documentary focuses on the Hollow Investigation Squads, but its release caused controversy when parents complained it glorified heroic deeds and encouraged children to enter the dangerous Hollows too early.', 'SCI-FI', '1 hour 45 minutes', '2026-04-08', 2.50, './img/GloriousGuardians.png', 'AVAILABLE');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventoryID`),
  ADD KEY `videoID` (`videoID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentID`),
  ADD KEY `rentalID` (`rentalID`);

--
-- Indexes for table `rental`
--
ALTER TABLE `rental`
  ADD PRIMARY KEY (`rentalID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `rentalitem`
--
ALTER TABLE `rentalitem`
  ADD PRIMARY KEY (`rentalItemID`),
  ADD KEY `rentalID` (`rentalID`),
  ADD KEY `inventoryID` (`inventoryID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `staffID` (`staffID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`),
  ADD UNIQUE KEY `phoneNumber` (`phoneNumber`);

--
-- Indexes for table `videotape`
--
ALTER TABLE `videotape`
  ADD PRIMARY KEY (`videoID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rental`
--
ALTER TABLE `rental`
  MODIFY `rentalID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rentalitem`
--
ALTER TABLE `rentalitem`
  MODIFY `rentalItemID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `videotape`
--
ALTER TABLE `videotape`
  MODIFY `videoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`videoID`) REFERENCES `videotape` (`videoID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`rentalID`) REFERENCES `rental` (`rentalID`);

--
-- Constraints for table `rental`
--
ALTER TABLE `rental`
  ADD CONSTRAINT `rental_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `customer` (`userID`);

--
-- Constraints for table `rentalitem`
--
ALTER TABLE `rentalitem`
  ADD CONSTRAINT `rentalitem_ibfk_1` FOREIGN KEY (`rentalID`) REFERENCES `rental` (`rentalID`),
  ADD CONSTRAINT `rentalitem_ibfk_2` FOREIGN KEY (`inventoryID`) REFERENCES `inventory` (`inventoryID`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
