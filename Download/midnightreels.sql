-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2026 at 04:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET FOREIGN_KEY_CHECKS=0;
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

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`userID`, `address`) VALUES
(1, 'Skudai, Johor');

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

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`userID`, `staffID`) VALUES
(3, 'ADM0001'),
(2, 'STM0001');

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `emailAddress`, `phoneNumber`, `password`, `role`, `accountCreationDate`, `userStatus`) VALUES
(1, 'customer1', 'customer1@gmail.com', '0123456789', '$2y$10$E3jXcnxO.fj4ZgT.1yXQQOudmEJ4THr/ih0VgwUGfW5OzG3zjNfl2', 'CUSTOMER', '2026-06-28', 'ACTIVE'),
(2, 'storemanager1', 'storemanager1@gmail.com', '012345678910', '$2y$10$acmdz0Op33IVMxiTvXRwneh4zibwZSuDwRnIZvzVlWb.QqnLkTQEW', 'STORE MANAGER', '2026-06-28', 'ACTIVE'),
(3, 'admin1', 'admin1@gmail.com', '012345678', '$2y$10$1SDF43AZ.kGAtCwNcukFmuoaIvD9zMhZuDXW2oi83Gmx/NWwTdYx2', 'ADMIN', '2026-06-28', 'ACTIVE');

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
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
