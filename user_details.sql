-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 22, 2020 at 01:42 PM
-- Server version: 5.7.28-0ubuntu0.18.04.4
-- PHP Version: 7.2.24-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_details`
--

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `Id` int(11) NOT NULL,
  `SkillName` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`Id`, `SkillName`) VALUES
(1, 'C'),
(2, 'Cpp'),
(3, 'Java'),
(4, 'Python'),
(5, 'Web Development');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `Prefix` enum('Mr.','Mrs.','Ms.') DEFAULT NULL,
  `LastName` varchar(25) NOT NULL,
  `FirstName` varchar(25) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `State` varchar(30) DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `CreationTime` datetime DEFAULT CURRENT_TIMESTAMP,
  `ModificationTime` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `Prefix`, `LastName`, `FirstName`, `Email`, `Age`, `State`, `Gender`, `CreationTime`, `ModificationTime`) VALUES
(1, 'Mr.', 'Das', 'Akash', 'mfs.akash@gmail.com', 22, 'Odisha', 'Male', '2020-01-22 13:02:52', '2020-01-22 13:02:52');

-- --------------------------------------------------------

--
-- Table structure for table `userSkills`
--

CREATE TABLE `userSkills` (
  `Id` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `SkillsId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `userSkills`
--

INSERT INTO `userSkills` (`Id`, `UserId`, `SkillsId`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `userSkills`
--
ALTER TABLE `userSkills`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `FK_UserId` (`UserId`),
  ADD KEY `FK_SkillsId` (`SkillsId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `userSkills`
--
ALTER TABLE `userSkills`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `userSkills`
--
ALTER TABLE `userSkills`
  ADD CONSTRAINT `FK_SkillsId` FOREIGN KEY (`SkillsId`) REFERENCES `skills` (`Id`),
  ADD CONSTRAINT `FK_UserId` FOREIGN KEY (`UserId`) REFERENCES `users` (`Id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
