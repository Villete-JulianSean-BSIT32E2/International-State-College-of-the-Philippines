-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2025 at 08:30 AM
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
-- Database: `iscpdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admission`
--

CREATE TABLE `admission` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `bdate` date NOT NULL,
  `gender` enum('m','f','o') NOT NULL,
  `nat` varchar(255) NOT NULL,
  `religion` varchar(255) NOT NULL,
  `curraddress` text NOT NULL,
  `province` varchar(255) NOT NULL,
  `peraddress` text NOT NULL,
  `zip` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `phoneno` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `AttendanceID` int(11) NOT NULL,
  `id` int(11) NOT NULL COMMENT 'ID IS FROM ADMISSION TABLE',
  `Date` date NOT NULL,
  `Status` smallint(1) NOT NULL COMMENT '0 - ABSENT, 1 - PRESENT, 2 - LATE',
  `Notes` text DEFAULT NULL,
  `TimeIn` timestamp NOT NULL DEFAULT current_timestamp(),
  `TimeOut` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guardian_info`
--

CREATE TABLE `guardian_info` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `foccu` varchar(255) NOT NULL,
  `moccu` varchar(255) NOT NULL,
  `fno` varchar(20) NOT NULL,
  `mno` varchar(20) NOT NULL,
  `gname` varchar(255) NOT NULL,
  `relationship` varchar(255) NOT NULL,
  `gno` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guardian_info`
--

INSERT INTO `guardian_info` (`id`, `fname`, `mname`, `foccu`, `moccu`, `fno`, `mno`, `gname`, `relationship`, `gno`) VALUES
(43, 'vbxcbx', 'bxcvbx', 'cvbxc', 'vbxcvb', 'bxcvb', 'xcvbxcvb', 'cvbxcv', 'xcvbxcv', 'xcvbxcv'),
(44, 'sdfafda', 'fadsf', 'asdfasdfas', 'asfd', 'adfasdfs', 'asdfasdf', 'dsfas', 'dfasdf', 'asfdasdf'),
(45, 'vxzcvzx', 'zxcvz', 'xcvzxcvz', 'xzcvzcv', 'xzcvzcx', 'zxcvzx', 'cvzvzcv', 'xcvzxcv', 'cvxzv'),
(46, 'afasdfa', 'sdfasfd', 'adsfasd', 'sfasfas', 'dfasdf', 'asdfasf', 'asdfa', 'fasdfa', 'asdfasdf');

-- --------------------------------------------------------

--
-- Stand-in structure for view `joined_students`
-- (See below for the actual view)
--
CREATE TABLE `joined_students` (
`name` varchar(255)
,`applying_grade` varchar(20)
,`Course` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_attendance_view`
-- (See below for the actual view)
--
CREATE TABLE `student_attendance_view` (
`AttendanceID` int(11)
,`Date` date
,`Status` smallint(1)
,`StatusText` varchar(7)
,`TimeIn` timestamp
,`TimeOut` timestamp
,`StudentID` int(11)
,`StudentName` varchar(255)
,`gender` enum('m','f','o')
,`email` varchar(255)
,`phoneno` varchar(20)
,`Course` varchar(255)
,`CurrentGrade` varchar(20)
,`PreviousGrade` varchar(20)
,`StudentStatus` varchar(255)
,`PreviousSchool` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `student_documents`
--

CREATE TABLE `student_documents` (
  `id` int(11) NOT NULL,
  `applying_grade` varchar(20) DEFAULT NULL,
  `prevschool` varchar(255) DEFAULT NULL,
  `last_grade` varchar(20) DEFAULT NULL,
  `Course` varchar(255) NOT NULL,
  `status_std` varchar(255) NOT NULL,
  `birth_cert_path` varchar(255) DEFAULT NULL,
  `form137_path` varchar(255) DEFAULT NULL,
  `tor_path` varchar(255) DEFAULT NULL,
  `good_moral_path` varchar(255) DEFAULT NULL,
  `honorable_dismissal_path` varchar(255) NOT NULL,
  `signature_path` varchar(255) DEFAULT NULL,
  `sigdate` date DEFAULT NULL,
  `confirmed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_documents`
--

INSERT INTO `student_documents` (`id`, `applying_grade`, `prevschool`, `last_grade`, `Course`, `status_std`, `birth_cert_path`, `form137_path`, `tor_path`, `good_moral_path`, `honorable_dismissal_path`, `signature_path`, `sigdate`, `confirmed`, `created_at`) VALUES
(47, '3rd Year', '12dASDdasc', '2nd Year', 'BSIT', 'new', 'uploads/1745907336_', 'uploads/1745907336_', 'uploads/1745907336_', 'uploads/1745907336_', 'uploads/1745907336_', 'uploads/1745907336_Report Acknowledgement.jfif', '2025-04-22', 1, '2025-04-29 06:15:36'),
(48, '2nd Year', 'sdfadfasf', '1st Year', 'CTHM', 'transferee', 'uploads/1745907478_', 'uploads/1745907478_', 'uploads/1745907478_', 'uploads/1745907478_', 'uploads/1745907478_', 'uploads/1745907478_Selected photo (1).jpg', '2025-04-29', 1, '2025-04-29 06:17:58'),
(49, '4th Year', 'minybubomp2131', '3rd Year', 'BSCRIM', 'old', 'uploads/1745907552_', 'uploads/1745907552_', 'uploads/1745907552_', 'uploads/1745907552_', 'uploads/1745907552_', 'uploads/1745907552_Selected photo (1).jfif', '2025-04-30', 1, '2025-04-29 06:19:12'),
(50, '4th Year', '12dASDdasc', '1st Year', 'BSCS', 'irregular', 'uploads/1745907795_', 'uploads/1745907795_', 'uploads/1745907795_', 'uploads/1745907795_', 'uploads/1745907795_', 'uploads/1745907795_Selected photo (1).jfif', '2025-04-30', 1, '2025-04-29 06:23:15');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmission_addstudent`
--

CREATE TABLE `tbladmission_addstudent` (
  `Admission_ID` varchar(150) NOT NULL,
  `Name` varchar(150) NOT NULL,
  `Birthdate` varchar(150) NOT NULL,
  `Gender` varchar(100) NOT NULL,
  `Nationality` varchar(100) NOT NULL,
  `Address` varchar(150) NOT NULL,
  `Province` varchar(150) NOT NULL,
  `City` varchar(150) NOT NULL,
  `ZIP` int(100) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `Phone_Number` int(100) NOT NULL,
  `2x2Photo` varchar(200) NOT NULL,
  `Fathers_Name` varchar(150) NOT NULL,
  `Fathers_Occupation` varchar(150) NOT NULL,
  `Fathers_Contact` varchar(150) NOT NULL,
  `Mothers_Name` varchar(150) NOT NULL,
  `Mothers_Occupation` varchar(150) NOT NULL,
  `Mothers_Contact` varchar(150) NOT NULL,
  `Guardians_Name` varchar(150) NOT NULL,
  `Guardian_Relationship` varchar(150) NOT NULL,
  `Guardian_Contact` varchar(150) NOT NULL,
  `Applying_Class` varchar(150) NOT NULL,
  `Grade_Completed` varchar(150) NOT NULL,
  `Student_Type` varchar(150) NOT NULL,
  `Previous_School` varchar(150) NOT NULL,
  `Course` varchar(150) NOT NULL,
  `Birth_Certi` varchar(150) NOT NULL,
  `Transcript_Records` varchar(150) NOT NULL,
  `Honorable_Dismissal` varchar(150) NOT NULL,
  `Form_137` varchar(150) NOT NULL,
  `Good_Moral` varchar(150) NOT NULL,
  `Document_Confirmation` varchar(150) NOT NULL,
  `Signature` varchar(150) NOT NULL,
  `Date` varchar(150) NOT NULL,
  `Status` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbladmission_studenttype`
--

CREATE TABLE `tbladmission_studenttype` (
  `StudentType_ID` varchar(100) NOT NULL,
  `New` varchar(100) NOT NULL,
  `Old` varchar(100) NOT NULL,
  `Irregular` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblstatus`
--

CREATE TABLE `tblstatus` (
  `StatusID` int(11) NOT NULL,
  `Enrolled` varchar(100) NOT NULL,
  `Pending` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure for view `joined_students`
--
DROP TABLE IF EXISTS `joined_students`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `joined_students`  AS SELECT `a`.`name` AS `name`, `d`.`applying_grade` AS `applying_grade`, `d`.`Course` AS `Course` FROM (`admission` `a` join `student_documents` `d` on(`a`.`name` <> 0)) ;

-- --------------------------------------------------------

--
-- Structure for view `student_attendance_view`
--
DROP TABLE IF EXISTS `student_attendance_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_attendance_view`  AS SELECT `a`.`AttendanceID` AS `AttendanceID`, `a`.`Date` AS `Date`, `a`.`Status` AS `Status`, CASE WHEN `a`.`Status` = 0 THEN 'Absent' WHEN `a`.`Status` = 1 THEN 'Present' WHEN `a`.`Status` = 2 THEN 'Late' ELSE 'Unknown' END AS `StatusText`, `a`.`TimeIn` AS `TimeIn`, `a`.`TimeOut` AS `TimeOut`, `ad`.`id` AS `StudentID`, `ad`.`name` AS `StudentName`, `ad`.`gender` AS `gender`, `ad`.`email` AS `email`, `ad`.`phoneno` AS `phoneno`, `sd`.`Course` AS `Course`, `sd`.`applying_grade` AS `CurrentGrade`, `sd`.`last_grade` AS `PreviousGrade`, `sd`.`status_std` AS `StudentStatus`, `sd`.`prevschool` AS `PreviousSchool` FROM ((`attendance` `a` join `admission` `ad` on(`a`.`id` = `ad`.`id`)) join `student_documents` `sd` on(`a`.`id` = `sd`.`id`)) ORDER BY `a`.`Date` DESC, `ad`.`name` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admission`
--
ALTER TABLE `admission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`AttendanceID`),
  ADD UNIQUE KEY `student_date_unique` (`id`,`Date`);

--
-- Indexes for table `guardian_info`
--
ALTER TABLE `guardian_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_documents`
--
ALTER TABLE `student_documents`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admission`
--
ALTER TABLE `admission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `AttendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `guardian_info`
--
ALTER TABLE `guardian_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `student_documents`
--
ALTER TABLE `student_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`id`) REFERENCES `admission` (`id`);

DELIMITER $$
--
-- Events
--
$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
