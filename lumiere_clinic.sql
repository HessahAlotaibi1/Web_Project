-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 05 أبريل 2025 الساعة 00:01
-- إصدار الخادم: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lumiere_clinic`
--

-- --------------------------------------------------------

--
-- بنية الجدول `appointment`
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

--
-- إرجاع أو استيراد بيانات الجدول `appointment`
--

INSERT INTO `appointment` (`id`, `patientID`, `doctorID`, `date`, `time`, `reason`, `status`) VALUES
(22, 6, 21, '2025-03-19', '15:00:00', 'Routine examination', 'Done'),
(23, 6, 10, '2025-04-18', '17:00:00', 'See the result', 'Pending'),
(24, 7, 17, '2025-04-07', '20:00:00', 'Toothache', 'Pending'),
(25, 7, 21, '2025-03-30', '15:00:00', 'Headache', 'Done');

-- --------------------------------------------------------

--
-- بنية الجدول `doctor`
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
-- إرجاع أو استيراد بيانات الجدول `doctor`
--

INSERT INTO `doctor` (`id`, `firstName`, `lastName`, `uniqueFileName`, `specialityID`, `emailAddress`, `password`) VALUES
(1, 'Ahmed', 'Hassan', 'images/Ahmed.jpeg', 1, 'ahmed.hassan@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(2, 'John', 'Smith', 'images/john.jpeg', 1, 'john.smith@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(3, 'Mona', 'Youssef', 'images/mona.jpeg', 2, 'mona.youssef@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(4, 'Emily', 'Johnson', 'images/emily.jpeg', 2, 'emily.johnson@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(5, 'Khaled', 'Omar', 'images/default_user.jpg', 3, 'khaled.omar@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(6, 'Sophia', 'Martinez', 'images/sophia.jpeg', 3, 'sophia.martinez@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(7, 'salma', 'nabil', 'images/default_user.jpg', 4, 'salma.nabil@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(8, 'Robert', 'Williams', 'images/robert.jpeg', 4, 'robert.williams@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(9, 'Youssef', 'Adel', 'images/default_user.jpg', 5, 'youssef.adel@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(10, 'David', 'Brown', 'images/default_user.jpg', 5, 'david.brown@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(11, 'Hanaa', 'Mostafa', 'images/default_user.jpg', 6, 'hanaa.mostafa@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(12, 'Laura', 'Davis', 'images/default_user.jpg', 6, 'laura.davis@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(13, 'Layla', 'Saeed', 'images/default_user.jpg', 7, 'layla.saeed@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(14, 'Lisa', 'Brain', 'images/default_user.jpg', 7, 'lisa.brain@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(15, 'Rami', 'Fares', 'images/default_user.jpg', 8, 'rami.fares@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(16, 'Julia', 'Thompson', 'images/default_user.jpg', 8, 'julia.thompson@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(17, 'Omar', 'Tarek', 'images/default_user.jpg', 9, 'omar.tarek@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(18, 'Chris', 'Miller', 'images/default_user.jpg', 9, 'chris.miller@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(21, 'Sara', 'Mohammed', 'uploads/67f07080247bc.jpg', 1, 'sara1988@email.com', '$2y$12$9YgYTwItuefgyiwxM6J8o.Rt7S5CslQIAgcLFm.jIp3IIgYQjEWtC');

-- --------------------------------------------------------

--
-- بنية الجدول `medication`
--

CREATE TABLE `medication` (
  `id` int(11) NOT NULL,
  `medicationName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- إرجاع أو استيراد بيانات الجدول `medication`
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
-- بنية الجدول `patient`
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
-- إرجاع أو استيراد بيانات الجدول `patient`
--

INSERT INTO `patient` (`id`, `firstName`, `lastName`, `Gender`, `DoB`, `emailAddress`, `password`) VALUES
(1, 'Nora', 'Saad', 'Female', '2010-03-01', 'nora.saad@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(2, 'Majed', 'Ahmed', 'Male', '1980-02-25', 'majed.ahmed@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(3, 'Leena', 'Naser', 'Female', '1985-08-04', 'leena.naser@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(4, 'Majed', 'Saleh', 'Male', '1990-02-23', 'majed.saleh@email.com', '$2y$12$44QSEkXKS1ZRKrkZd52gG.Ja0CizYu9tGMmTEM3s8nkeBvLOH.5ra'),
(6, 'Lulu', 'Salem', 'Female', '1999-06-15', 'lulu1999@email.com', '$2y$12$6nYyucYoengptpwXcJY23.2WTpxWVMWJuLN/Zfm4qn9u2Pu7z799e'),
(7, 'Saad', 'Saleh', 'Female', '2002-06-09', 'saad2@email.com', '$2y$12$zixg0nV7NSmw8pg0pM7Yu.2yxmfe3lKT0fG/iAMDmZYnn5bqG3gzW');

-- --------------------------------------------------------

--
-- بنية الجدول `prescription`
--

CREATE TABLE `prescription` (
  `id` int(11) NOT NULL,
  `appointmentID` int(11) NOT NULL,
  `medicationID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- إرجاع أو استيراد بيانات الجدول `prescription`
--

INSERT INTO `prescription` (`id`, `appointmentID`, `medicationID`) VALUES
(8, 22, 1),
(9, 22, 5),
(10, 25, 1);

-- --------------------------------------------------------

--
-- بنية الجدول `speciality`
--

CREATE TABLE `speciality` (
  `id` int(11) NOT NULL,
  `speciality` varchar(100) NOT NULL,
  `group` enum('General Medicine Clinic','Dermatology & Aesthetic Clinic','Dentistry') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- إرجاع أو استيراد بيانات الجدول `speciality`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `medication`
--
ALTER TABLE `medication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `speciality`
--
ALTER TABLE `speciality`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- قيود الجداول المحفوظة
--

--
-- القيود للجدول `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`doctorID`) REFERENCES `doctor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`patientID`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- القيود للجدول `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`specialityID`) REFERENCES `speciality` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- القيود للجدول `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`appointmentID`) REFERENCES `appointment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prescription_ibfk_2` FOREIGN KEY (`medicationID`) REFERENCES `medication` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
