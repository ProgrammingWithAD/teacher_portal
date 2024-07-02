-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2024 at 11:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teacher_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `marks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `subject`, `marks`) VALUES
(2, 'Jane Smith', 'Science', 85),
(3, 'Emily Davis', 'History', 57),
(4, 'Akash Deep Sarkar', 'DBMS', 95),
(5, 'Deep Sarkar', 'CN', 89),
(6, 'Michael Johnson', 'Math', 78),
(7, 'Sarah Wilson', 'Science', 88),
(8, 'Kevin Brown', 'History', 82),
(10, 'Daniel Taylor', 'Science', 85),
(11, 'Amy Garcia', 'History', 79),
(12, 'Ryan Martinez', 'Math', 89),
(13, 'Lisa Rodriguez', 'Science', 92),
(14, 'David Hernandez', 'History', 84),
(15, 'Mary Gonzalez', 'Math', 87),
(16, 'James Miller', 'Science', 83),
(17, 'Laura Lopez', 'History', 76),
(18, 'Andrew Perez', 'Math', 80),
(19, 'Sophia Moore', 'Science', 86),
(20, 'Jason Adams', 'History', 81),
(21, 'Olivia Hall', 'Math', 93),
(22, 'Matthew Clark', 'Science', 90),
(23, 'Rachel White', 'History', 77),
(24, 'Joshua Scott', 'Math', 82),
(25, 'Ava Thomas', 'Science', 91),
(26, 'Eric Green', 'History', 78),
(27, 'Emma Hill', 'Math', 88),
(28, 'Christopher Baker', 'Science', 84),
(29, 'Megan Carter', 'History', 80),
(30, 'Tyler Rivera', 'Math', 85),
(31, 'Grace Ward', 'Science', 90),
(32, 'Benjamin Turner', 'History', 75),
(33, 'Chloe Foster', 'Math', 79),
(34, 'Justin Perry', 'Science', 88),
(35, 'Hannah Collins', 'History', 82),
(36, 'Brandon Long', 'Math', 91),
(37, 'Victoria Murphy', 'Science', 85),
(38, 'Katie Reed', 'History', 79),
(39, 'Samuel King', 'Math', 89),
(40, 'Natalie Cooper', 'Science', 92),
(41, 'Jordan Morgan', 'History', 84),
(42, 'Alexis Bell', 'Math', 87),
(43, 'William Cox', 'Science', 83),
(44, 'Kayla Ross', 'History', 76),
(45, 'Gabriel Howard', 'Math', 80),
(46, 'Madison Price', 'Science', 86),
(47, 'Steven Ward', 'History', 81),
(48, 'Isabella Brooks', 'Math', 93),
(49, 'Jonathan Bailey', 'Science', 89),
(50, 'Lily Stewart', 'History', 77),
(51, 'Connor Murphy', 'Math', 82),
(52, 'Audrey Sanders', 'Science', 91),
(53, 'Nicholas Wood', 'History', 78),
(54, 'Raj Sharma', 'History', 65),
(55, 'sarkar', 'History', 89);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `username`, `password`) VALUES
(1, 'teacher', '$2y$10$H6KyFVsafGOmrpTkSYJdH.KrKtjpEl340ZA2vR.14IpziO0IwTAkK');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`subject`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
