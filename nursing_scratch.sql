-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2018 at 09:02 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nursing_scratch`
--

-- --------------------------------------------------------

--
-- Table structure for table `collision`
--

CREATE TABLE `collision` (
  `Course_ID` int(11) NOT NULL,
  `Coll_ID` int(11) NOT NULL,
  `Room_ID` int(11) NOT NULL,
  `M` varchar(3) NOT NULL,
  `T` varchar(3) NOT NULL,
  `W` varchar(3) NOT NULL,
  `R` varchar(3) NOT NULL,
  `F` varchar(3) NOT NULL,
  `Week_ID` int(11) NOT NULL,
  `Semester_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `collision`
--

INSERT INTO `collision` (`Course_ID`, `Coll_ID`, `Room_ID`, `M`, `T`, `W`, `R`, `F`, `Week_ID`, `Semester_ID`) VALUES
(9000, 1, 236, 'no', 'no', 'yes', 'no', 'no', 15, 1);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `Course_ID` int(11) NOT NULL,
  `Prefix` varchar(255) NOT NULL,
  `Number` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Teacher_CWID` int(11) NOT NULL,
  `Start_time` time NOT NULL,
  `End_time` time NOT NULL,
  `M` varchar(3) NOT NULL,
  `T` varchar(3) NOT NULL,
  `W` varchar(3) NOT NULL,
  `R` varchar(3) NOT NULL,
  `F` varchar(3) NOT NULL,
  `student_count` int(11) NOT NULL,
  `Lead_teacher` int(11) NOT NULL,
  `Semester_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`Course_ID`, `Prefix`, `Number`, `Title`, `Teacher_CWID`, `Start_time`, `End_time`, `M`, `T`, `W`, `R`, `F`, `student_count`, `Lead_teacher`, `Semester_ID`) VALUES
(4000, 'CSCI', 4060, 'Principles of Software Engineering', 7000000, '12:30:00', '01:45:00', 'no', 'yes', 'no', 'yes', 'no', 20, 7000000, 1),
(5000, 'MATH', 4009, 'Cryptology', 7000000, '02:00:00', '03:15:00', 'no', 'yes', 'no', 'yes', 'no', 30, 7000000, 1),
(60000, 'CINS', 3041, 'Advanced Networking', 3000000, '17:30:00', '20:15:00', 'no', 'no', 'yes', 'no', 'no', 34, 3000000, 1),
(8000, 'CINS', 3045, 'Adv. Information Security', 3000000, '02:30:00', '03:30:00', 'no', 'yes', 'no', 'yes', 'no', 25, 0, 2),
(9000, 'CSCI', 4055, 'Theory of Database Management System', 3000000, '09:30:00', '10:15:00', 'yes', 'no', 'yes', 'no', 'no', 36, 3000000, 1),
(9012, 'CSCI', 2073, 'Data Structures', 7000000, '09:30:00', '10:30:00', 'yes', 'no', 'yes', 'no', 'no', 31, 7000000, 1),
(100000, 'CSCI', 4065, 'Advanced Topics', 3000000, '10:00:00', '11:00:00', 'yes', 'no', 'yes', 'no', 'no', 24, 3000000, 1),
(4001, 'CSCI', 4060, 'Principles of Software Engineering', 437, '12:30:00', '01:45:00', 'no', 'yes', 'no', 'yes', 'no', 20, 7000000, 1),
(43928, 'NURS', 2004, 'Health Assessment', 6000001, '13:00:00', '15:00:00', 'no', 'no', 'no', 'yes', 'no', 11, 600002, 1),
(439291, 'NURS', 2004, 'HEALTH ASSESSMENT\r\n', 600002, '13:00:00', '15:00:00', 'no', 'no', 'no', 'yes', 'yes', 11, 600002, 1),
(436721, 'NURS', 2009, 'FUND PROF NURSING PRACTICE', 600001, '08:00:00', '12:00:00', 'no', 'yes', 'yes', 'yes', 'no', 10, 600001, 1),
(436722, 'NURS', 2009, 'FUND PROF NURSING PRACTICE', 600003, '09:00:00', '12:00:00', 'no', 'yes', 'yes', 'yes', 'no', 10, 600001, 1),
(43678, 'NURS', 2011, 'INTRO GERONTOLOGICAL NURSING', 600005, '08:00:00', '09:00:00', 'yes', 'no', 'no', 'no', 'no', 25, 0, 1),
(43681, 'NURS', 2013, 'COMPUTING FOR NURSES\r\n', 600006, '08:00:00', '09:00:00', 'no', 'yes', 'no', 'no', 'no', 25, 0, 1),
(439361, 'NURS', 3009, 'ADULT HEALTH NURSING 1\r\n', 600007, '07:00:00', '15:00:00', 'no', 'yes', 'yes', 'no', 'no', 15, 0, 1),
(439362, 'NURS', 3009, 'ADULT HEALTH NURSING 1\r\n', 600008, '13:00:00', '15:00:00', 'no', 'no', 'no', 'yes', 'yes', 15, 0, 1),
(439401, 'NURS', 3010, 'MENTAL HEALTH NURSING\r\n', 600007, '09:00:00', '11:00:00', 'no', 'yes', 'yes', 'no', 'no', 15, 600007, 1),
(439402, 'NURS', 3010, 'MENTAL HEALTH NURSING', 600008, '08:00:00', '10:00:00', 'no', 'no', 'no', 'yes', 'yes', 15, 600007, 1),
(439421, 'NURS', 3011, 'NURSING SYNTHESIS I', 600008, '10:15:00', '11:15:00', 'no', 'no', 'no', 'yes', 'no', 15, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `CWID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `Course_ID` int(11) NOT NULL,
  `Room_ID` int(11) NOT NULL,
  `Week_ID` int(11) NOT NULL,
  `Time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`CWID`, `Name`, `activity`, `Course_ID`, `Room_ID`, `Week_ID`, `Time`) VALUES
(3000000, 'Nirjala Parajuli', 'Request - Wednesday', 100000, 312, 49, '2018-04-20 16:04:56'),
(800000, 'Sanjog Pokhrel', 'Added notes', 9000, 0, 1, '2018-04-20 20:31:44'),
(437, 'Kyle Willard', 'Deleted notes', 4001, 0, 15, '2018-04-20 20:33:31'),
(800000, 'Sanjog Pokhrel', 'Added notes', 4000, 0, 5, '2018-04-20 20:35:23'),
(3000000, 'Nirjala Parajuli', 'Added notes', 9000, 0, 1, '2018-04-20 20:39:19'),
(3000000, 'Nirjala Parajuli', 'Deleted notes', 9000, 0, 1, '2018-04-20 20:44:52'),
(800000, 'Sanjog Pokhrel', 'Added notes', 4001, 0, 49, '2018-04-20 23:41:33'),
(800000, 'Sanjog Pokhrel', 'Added notes', 4000, 0, 49, '2018-04-20 23:43:41'),
(3000000, 'Nirjala Parajuli', 'Request - Monday', 100000, 308, 5, '2018-04-20 23:50:20'),
(800000, 'Sanjog Pokhrel', 'Booked - Monday', 9000, 218, 5, '2018-04-20 23:50:38'),
(800000, 'Sanjog Pokhrel', 'Deleted - Monday', 9000, 308, 5, '2018-04-20 23:50:38'),
(800000, 'Sanjog Pokhrel', 'Added - Monday', 100000, 308, 5, '2018-04-20 23:51:04'),
(800000, 'Sanjog Pokhrel', 'Deleted collision- Monday', 100000, 308, 5, '2018-04-20 23:51:04'),
(800000, 'Sanjog Pokhrel', 'Updated notes', 4000, 0, 5, '2018-04-21 10:49:21'),
(800000, 'Sanjog Pokhrel', 'Deleted notes', 4000, 0, 5, '2018-04-21 10:49:27'),
(3000000, 'Nirjala Parajuli', 'Booked - Wednesday', 60000, 312, 49, '2018-04-21 16:10:07'),
(3000000, 'Nirjala Parajuli', 'Cancel reservation for  Wednesday', 60000, 312, 49, '2018-04-21 18:45:21'),
(800000, 'Sanjog Pokhrel', 'Deleted - Wednesday', 100000, 312, 49, '2018-04-22 17:03:34'),
(3000000, 'Nirjala Parajuli', 'Request - Wednesday', 9000, 218, 15, '2018-04-24 12:54:09'),
(3000000, 'Nirjala Parajuli', 'Booked - Monday Wednesday', 9000, 107, 15, '2018-04-24 12:54:33'),
(3000000, 'Nirjala Parajuli', 'Cancel reservation for  Monday Wednesday', 9000, 308, 49, '2018-04-24 12:55:15'),
(800000, 'Sanjog Pokhrel', 'Booked - Wednesday', 100000, 236, 15, '2018-04-24 13:00:36'),
(800000, 'Sanjog Pokhrel', 'Deleted - Wednesday', 100000, 218, 15, '2018-04-24 13:00:37'),
(800000, 'Sanjog Pokhrel', 'Added - Wednesday', 9000, 218, 15, '2018-04-24 13:00:48'),
(800000, 'Sanjog Pokhrel', 'Deleted collision- Wednesday', 9000, 218, 15, '2018-04-24 13:00:48'),
(437, 'Kyle Willard', 'Added notes', 4001, 0, 49, '2018-04-24 13:01:50'),
(3000000, 'Nirjala Parajuli', 'Booked - Monday Wednesday', 100000, 308, 49, '2018-04-24 13:03:43'),
(3000000, 'Nirjala Parajuli', 'Booked - Monday Wednesday', 100000, 308, 50, '2018-04-24 13:03:43'),
(3000000, 'Nirjala Parajuli', 'Booked - Monday Wednesday', 100000, 308, 51, '2018-04-24 13:03:43'),
(7000000, 'Rabi Tiwari', 'Booked - Thursday', 5000, 308, 15, '2018-04-24 13:04:34'),
(3000000, 'Nirjala Parajuli', 'Request - Monday Wednesday', 9000, 236, 14, '2018-04-29 21:19:11'),
(3000000, 'Nirjala Parajuli', 'Request - Monday Wednesday', 9000, 236, 15, '2018-04-29 21:19:11'),
(800000, 'Sanjog Pokhrel', 'Added - Monday', 9000, 236, 14, '2018-05-02 23:17:07'),
(800000, 'Sanjog Pokhrel', 'Deleted collision- Monday', 9000, 236, 14, '2018-05-02 23:17:07'),
(800000, 'Sanjog Pokhrel', 'Added - Wednesday', 9000, 236, 14, '2018-05-02 23:17:29'),
(800000, 'Sanjog Pokhrel', 'Deleted collision- Wednesday', 9000, 236, 14, '2018-05-02 23:17:29'),
(800000, 'Sanjog Pokhrel', 'Added - Monday', 9000, 236, 15, '2018-05-02 23:17:51'),
(800000, 'Sanjog Pokhrel', 'Deleted collision- Monday', 9000, 236, 15, '2018-05-02 23:17:51');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `Course_ID` int(10) NOT NULL,
  `Week_ID` int(10) NOT NULL,
  `Note` varchar(255) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Semester_ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`Course_ID`, `Week_ID`, `Note`, `Name`, `Semester_ID`) VALUES
(9000, 1, 'Db notes', 'Sanjog Pokhrel', 1),
(4001, 49, 'heheeee', 'Sanjog Pokhrel', 1),
(4000, 49, 'sadfad', 'Sanjog Pokhrel', 1),
(4001, 49, 'here is the note', 'Kyle Willard', 1);

-- --------------------------------------------------------

--
-- Table structure for table `occupied`
--

CREATE TABLE `occupied` (
  `Course_ID` int(11) NOT NULL,
  `Room_ID` int(11) NOT NULL,
  `Semester_ID` int(11) NOT NULL,
  `M` varchar(3) NOT NULL,
  `T` varchar(3) NOT NULL,
  `W` varchar(3) NOT NULL,
  `R` varchar(3) NOT NULL,
  `F` varchar(3) NOT NULL,
  `Week_ID` int(11) NOT NULL,
  `occupied_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `occupied`
--

INSERT INTO `occupied` (`Course_ID`, `Room_ID`, `Semester_ID`, `M`, `T`, `W`, `R`, `F`, `Week_ID`, `occupied_ID`) VALUES
(9000, 308, 1, 'no', 'no', 'yes', 'no', 'no', 5, 5),
(9012, 308, 1, 'no', 'no', 'yes', 'no', 'no', 15, 6),
(9000, 312, 1, 'no', 'no', 'yes', 'no', 'no', 15, 7),
(9000, 218, 1, 'yes', 'no', 'no', 'no', 'no', 15, 8),
(100000, 312, 1, 'yes', 'no', 'no', 'no', 'no', 15, 9),
(9000, 218, 1, 'no', 'no', 'yes', 'no', 'no', 49, 11),
(9000, 218, 1, 'no', 'no', 'yes', 'no', 'no', 50, 11),
(9000, 218, 1, 'yes', 'no', 'no', 'no', 'no', 5, 12),
(100000, 308, 1, 'yes', 'no', 'no', 'no', 'no', 5, 13),
(9000, 107, 1, 'yes', 'no', 'yes', 'no', 'no', 15, 14),
(100000, 236, 1, 'no', 'no', 'yes', 'no', 'no', 15, 15),
(9000, 218, 1, 'no', 'no', 'yes', 'no', 'no', 15, 16),
(100000, 308, 1, 'yes', 'no', 'yes', 'no', 'no', 49, 17),
(100000, 308, 1, 'yes', 'no', 'yes', 'no', 'no', 50, 17),
(100000, 308, 1, 'yes', 'no', 'yes', 'no', 'no', 51, 17),
(5000, 308, 1, 'no', 'no', 'no', 'yes', 'no', 15, 18),
(9000, 236, 1, 'yes', 'no', 'no', 'no', 'no', 14, 19),
(9000, 236, 1, 'no', 'no', 'yes', 'no', 'no', 14, 20),
(9000, 236, 1, 'yes', 'no', 'no', 'no', 'no', 15, 21);

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `CWID` int(11) NOT NULL,
  `Fname` varchar(255) NOT NULL,
  `Lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`CWID`, `Fname`, `Lname`, `email`, `password`, `role`) VALUES
(437, 'Kyle', 'Willard', 'kyle@warhawks.ulm.edu', 'kyle', 'teacher'),
(10000, 'Sasha', 'Denisheuskaya', 'sasha@warhawks.ulm.edu', 'sasha', 'student'),
(600001, 'Kathy', 'Davenport', 'Daven@warhawks.ulm.edu', 'Daven', 'teacher'),
(600002, 'Ebony', 'Watson', 'Watson@warhawks.ulm.edu', 'Watson', 'teacher'),
(600003, 'Kimberley', 'Letson', 'Letson@warhawks.ulm.edu', 'Letson', 'teacher'),
(600005, 'Nancy', 'Moss', 'Moss@warhawks.ulm.edu', 'Moss', 'teacher'),
(600006, 'Donna', 'Glaze', 'Glaze@warhawks.ulm.edu', 'Glaze', 'teacher'),
(600007, 'Martha', 'Goodman', 'Goodman@warhawks.ulm.edu', 'Goodman', 'teacher'),
(600008, 'Catherine', 'Campbell', 'Campbell@warhawks.ulm.edu', 'Campbell', 'teacher'),
(800000, 'Sanjog', 'Pokhrel', 'pokhres@warhawks.ulm.edu', 'sanjog', 'admin'),
(3000000, 'Nirjala', 'Parajuli', 'parajun@warhawks.ulm.edu', 'nirjala', 'teacher'),
(3005000, 'Alexander', 'Alexis', 'Alexis@warhawks.ulm.edu', 'Alexis', 'student'),
(3005001, 'Beal', 'Chase', 'Chase@warhawks.ulm.edu', 'Chase', 'student'),
(3005002, 'Caldwell', 'Blaise', 'Blaise@warhawks.ulm.edu', 'Blaise', 'student'),
(6000000, 'Sandesh', 'Tiwari', 'sandeshtiwari@live.com', 'sandesh', 'student'),
(7000000, 'Rabi', 'Tiwari', 'tiwarir@warhawks.ulm.edu', 'rabi', 'teacher');

-- --------------------------------------------------------

--
-- Table structure for table `registered`
--

CREATE TABLE `registered` (
  `CWID` int(11) NOT NULL,
  `Course_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `registered`
--

INSERT INTO `registered` (`CWID`, `Course_ID`) VALUES
(6000000, 4000),
(10000, 5000),
(3005000, 439282),
(3005000, 439291),
(3005001, 439361),
(3005001, 439401),
(3005001, 439421),
(3005002, 439282),
(6000000, 43928),
(6000000, 439401),
(6000000, 439402);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Available` varchar(3) NOT NULL,
  `Projector` varchar(3) NOT NULL,
  `Bed` varchar(3) NOT NULL,
  `capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`ID`, `Name`, `Available`, `Projector`, `Bed`, `capacity`) VALUES
(308, 'Hemp 308', 'yes', 'yes', 'no', 35),
(312, 'Hemp 312', 'yes', 'yes', 'no', 35),
(218, 'Hemp 218', 'yes', 'yes', 'no', 24),
(107, 'Auditorium 107', 'yes', 'yes', 'no', 300),
(236, 'LRC(a)', 'yes', 'no', 'no', 50),
(2362, 'LRC(b)', 'yes', 'no', 'no', 50),
(2363, 'LRC(c)', 'yes', 'no', 'no', 50),
(218, 'Room 218', 'yes', 'no', 'yes', 15),
(221, 'Room 221', 'yes', 'no', 'yes', 15),
(320, 'Room 320', 'yes', 'no', 'no', 10),
(327, 'Room 327', 'yes', 'no', 'no', 10),
(325, 'Room 325', 'yes', 'no', 'yes', 20),
(242, 'Room 242 ', 'yes', 'yes', 'no', 55),
(322, 'Room 322', 'yes', 'no', 'no', 77),
(327, 'Room 327', 'yes', 'no', 'no', 10),
(338, 'Room 338', 'yes', 'yes', 'no', 73),
(339, 'Room 339', 'yes', 'yes', 'no', 73),
(340, 'Room 340', 'yes', 'yes', 'no', 54),
(341, 'Room 341', 'yes', 'yes', 'no', 54),
(215, '\"Noel\" simulator', 'yes', 'no', 'yes', 9),
(243, 'ICU simulator', 'yes', 'no', 'yes', 9),
(343, 'Room 343', 'yes', 'yes', 'no', 70);

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `ID` int(11) NOT NULL,
  `semester` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `register_permission` varchar(10) NOT NULL,
  `Override` varchar(255) NOT NULL,
  `deadline` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`ID`, `semester`, `start_date`, `end_date`, `register_permission`, `Override`, `deadline`) VALUES
(1, 'Spring', '2017-12-05', '2018-04-12', 'yes', 'on', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `week`
--

CREATE TABLE `week` (
  `ID` int(11) NOT NULL,
  `semester_ID` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `week`
--

INSERT INTO `week` (`ID`, `semester_ID`, `start_date`, `end_date`) VALUES
(49, 1, '2017-12-03', '2017-12-09'),
(50, 1, '2017-12-10', '2017-12-16'),
(51, 1, '2017-12-17', '2017-12-23'),
(52, 1, '2017-12-24', '2017-12-30'),
(1, 1, '2017-12-31', '2018-01-06'),
(2, 1, '2018-01-07', '2018-01-13'),
(3, 1, '2018-01-14', '2018-01-20'),
(4, 1, '2018-01-21', '2018-01-27'),
(5, 1, '2018-01-28', '2018-02-03'),
(6, 1, '2018-02-04', '2018-02-10'),
(7, 1, '2018-02-11', '2018-02-17'),
(8, 1, '2018-02-18', '2018-02-24'),
(9, 1, '2018-02-25', '2018-03-03'),
(10, 1, '2018-03-04', '2018-03-10'),
(11, 1, '2018-03-11', '2018-03-17'),
(12, 1, '2018-03-18', '2018-03-24'),
(13, 1, '2018-03-25', '2018-03-31'),
(14, 1, '2018-04-01', '2018-04-07'),
(15, 1, '2018-04-08', '2018-04-14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`CWID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
