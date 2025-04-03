-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 02, 2025 at 11:07 PM
-- Server version: 5.7.33-0ubuntu0.16.04.1
-- PHP Version: 7.0.33-0ubuntu0.16.04.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `it329_lumiere_clinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `patientID` int(11) NOT NULL,
  `doctorID` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` enum('Pending','Confirmed','Done') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `uniqueFileName` varchar(255) NOT NULL,
  `specialityID` int(11) NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `firstName`, `lastName`, `uniqueFileName`, `specialityID`, `emailAddress`, `password`) VALUES
(1, 'Ahmed', 'Hassan', 'images/default_user.jpg', 1, 'ahmed.hassan@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(2, 'John', 'Smith', 'images/default_user.jpg', 1, 'john.smith@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(3, 'Mona', 'Youssef', 'images/default_user.jpg', 2, 'mona.youssef@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(4, 'Emily', 'Johnson', 'images/default_user.jpg', 2, 'emily.johnson@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(5, 'Khaled', 'Omar', 'images/default_user.jpg', 3, 'khaled.omar@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(6, 'Sophia', 'Martinez', 'images/default_user.jpg', 3, 'sophia.martinez@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(7, 'salma', 'nabil', 'images/default_user.jpg', 4, 'salma.nabil@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(8, 'Robert', 'Williams', 'images/default_user.jpg', 4, 'robert.williams@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(9, 'Youssef', 'Adel', 'images/default_user.jpg', 5, 'youssef.adel@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(10, 'David', 'Brown', 'images/default_user.jpg', 5, 'david.brown@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(11, 'Hanaa', 'Mostafa', 'images/default_user.jpg', 6, 'hanaa.mostafa@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(12, 'Laura', 'Davis', 'images/default_user.jpg', 6, 'laura.davis@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(13, 'Layla', 'Saeed', 'images/default_user.jpg', 7, 'layla.saeed@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(14, 'Lisa', 'Brain', 'images/default_user.jpg', 7, 'lisa.brain@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(15, 'Rami', 'Fares', 'images/default_user.jpg', 8, 'rami.fares@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(16, 'Julia', 'Thompson', 'images/default_user.jpg', 8, 'julia.thompson@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(17, 'Omar', 'Tarek', 'images/default_user.jpg', 9, 'omar.tarek@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(18, 'Chris', 'Miller', 'images/default_user.jpg', 9, 'chris.miller@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra');

-- --------------------------------------------------------

--
-- Table structure for table `medication`
--

CREATE TABLE `medication` (
  `id` int(11) NOT NULL,
  `medicationName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `medication`
--

INSERT INTO `medication` (`id`, `medicationName`) VALUES
(1, 'Paracetamol'),
(2, 'Ibuprofen'),
(3, 'Amoxicillin'),
(4, 'Metformin'),
(5, 'Lisinopril'),
(6, 'Atorvastatin'),
(7, 'Omeprazole'),
(8, 'Salbutamol'),
(9, 'Hydrochlorothiazide'),
(10, 'Cetirizine');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `Gender` enum('Male','Female') NOT NULL DEFAULT 'Male',
  `DoB` date DEFAULT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `firstName`, `lastName`, `Gender`, `DoB`, `emailAddress`, `password`) VALUES
(1, 'Nora', 'Saad', 'Female', '2010-03-01', 'nora.saad@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(2, 'Majed', 'Ahmed', 'Male', '1980-02-25', 'majed.ahmed@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(3, 'Leena', 'Naser', 'Female', '1985-08-04', 'leena.naser@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(4, 'Majed', 'Saleh', 'Male', '1990-02-23', 'majed.saleh@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `id` int(11) NOT NULL,
  `appointmentID` int(11) NOT NULL,
  `medicationID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `speciality`
--

CREATE TABLE `speciality` (
  `id` int(11) NOT NULL,
  `speciality` varchar(100) NOT NULL,
  `group` enum('General Medicine Clinic','Dermatology & Aesthetic Clinic','Dentistry') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `speciality`
--

INSERT INTO `speciality` (`id`, `speciality`, `group`) VALUES
(1, 'Routine Check-ups', 'General Medicine Clinic'),
(2, 'Medical Consultations', 'General Medicine Clinic'),
(3, 'Vaccinations', 'General Medicine Clinic'),
(4, 'Geriatric Care', 'General Medicine Clinic'),
(5, 'Laboratory Tests', 'General Medicine Clinic'),
(6, 'Regular Health Check-ups', 'General Medicine Clinic'),
(7, 'Dermatology', 'Dermatology & Aesthetic Clinic'),
(8, 'Cosmetic & Laser Treatments', 'Dermatology & Aesthetic Clinic'),
(9, 'Dentistry', 'Dentistry');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patientID` (`patientID`),
  ADD KEY `doctorID` (`doctorID`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`),
  ADD KEY `specialityID` (`specialityID`);

--
-- Indexes for table `medication`
--
ALTER TABLE `medication`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointmentID` (`appointmentID`),
  ADD KEY `medicationID` (`medicationID`);

--
-- Indexes for table `speciality`
--
ALTER TABLE `speciality`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `medication`
--
ALTER TABLE `medication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `speciality`
--
ALTER TABLE `speciality`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`doctorID`) REFERENCES `doctor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`patientID`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`specialityID`) REFERENCES `speciality` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`appointmentID`) REFERENCES `appointment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prescription_ibfk_2` FOREIGN KEY (`medicationID`) REFERENCES `medication` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
