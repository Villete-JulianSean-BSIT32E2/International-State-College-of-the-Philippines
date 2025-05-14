-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 09:07 AM
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
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `AttendanceID` int(11) NOT NULL,
  `Admission_ID` int(11) NOT NULL COMMENT 'ID IS FROM ADMISSION TABLE',
  `Date` date NOT NULL,
  `Status` smallint(1) NOT NULL COMMENT '0 - ABSENT, 1 - PRESENT, 2 - LATE',
  `Notes` text DEFAULT NULL,
  `TimeIn` timestamp NOT NULL DEFAULT current_timestamp(),
  `TimeOut` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`AttendanceID`, `Admission_ID`, `Date`, `Status`, `Notes`, `TimeIn`, `TimeOut`) VALUES
(1, 1007, '2025-05-13', 1, NULL, '2025-05-12 16:11:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `class_schedules`
--

CREATE TABLE `class_schedules` (
  `id` int(11) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `instructor` varchar(100) DEFAULT NULL,
  `day` varchar(20) DEFAULT NULL,
  `time` varchar(100) DEFAULT NULL,
  `room` varchar(50) DEFAULT NULL,
  `course` varchar(100) NOT NULL,
  `section` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `class_schedules`
--

INSERT INTO `class_schedules` (`id`, `subject`, `instructor`, `day`, `time`, `room`, `course`, `section`) VALUES
(1, 'English', 'Mr. De Jesus', 'Monday', '12:00 - 1:30 PM', '103', 'BSA', 'BSIT41A1'),
(2, 'English', 'Mr. De Jesus', 'Tuesday', '1:00 - 2:30 PM', '211', 'BSA', 'BSIT41A2');

-- --------------------------------------------------------

--
-- Table structure for table `deadlines`
--

CREATE TABLE `deadlines` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `due_date` date NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `deadlines`
--

INSERT INTO `deadlines` (`id`, `title`, `due_date`, `description`) VALUES
(1, 'Enrollment Deadline', '2025-05-15', 'Last day to enroll for Summer semester.'),
(2, 'Document Submission', '2025-05-20', 'Submit all required student documents.'),
(3, 'Tuition Fee Payment', '2025-06-01', 'Final date to pay tuition without late fee.'),
(4, 'Clearance Signing', '2025-06-10', 'Deadline for department clearance submissions.');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Paid'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `student_id`, `amount_paid`, `payment_date`, `payment_method`, `status`) VALUES
(0, 1013, 3000.00, '2025-05-14', 'Cash', 'Paid'),
(1, 0, 0.00, '2025-05-12', 'Gcash', 'Paid'),
(2, 1008, 5000.00, '2025-05-06', 'Cash', 'Paid'),
(3, 1008, 0.00, '2025-05-12', '	', 'Paid'),
(4, 1010, 2000.00, '2025-05-13', 'Cash', 'Paid'),
(5, 1010, 200.00, '2025-05-15', 'Gcash', 'Paid'),
(6, 1010, 3000.00, '2025-05-12', 'Cash', 'Paid'),
(7, 1010, 3000.00, '2025-05-12', 'Cash', 'Paid'),
(8, 1010, 3000.00, '2025-05-29', 'Cash', 'Paid'),
(9, 1010, 200.00, '2025-05-12', 'Bank Transfer', 'Paid'),
(10, 0, 3000.00, '2025-05-12', 'Cash', 'Paid'),
(11, 0, 3000.00, '2025-05-12', 'Cash', 'Paid'),
(12, 1010, 2222.00, '2025-05-12', 'Cash', 'Paid'),
(13, 1010, 2222.00, '2025-05-12', 'Cash', 'Paid'),
(14, 1009, 3000.00, '2025-05-07', 'Bank Transfer', 'Paid'),
(15, 1007, 2000.00, '2025-05-13', 'Cash', 'Paid'),
(16, 0, 2000.00, '2025-05-05', 'Cash', 'Paid'),
(17, 0, 2000.00, '2025-05-19', 'Cash', 'Paid'),
(18, 0, 3000.00, '2025-05-15', 'Cash', 'Paid'),
(19, 0, 2000.00, '2025-05-17', 'Cash', 'Paid'),
(20, 1011, 2000.00, '2025-05-29', 'Cash', 'Paid'),
(21, 1008, 2000.00, '2025-05-08', 'Cash', 'Paid'),
(22, 1012, 3000.00, '2025-05-12', 'Cash', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `receivables`
--

CREATE TABLE `receivables` (
  `id` int(11) NOT NULL,
  `or_no` varchar(10) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `total_fee` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `course` varchar(50) NOT NULL,
  `payment_date` date NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_amount` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `receivables`
--

INSERT INTO `receivables` (`id`, `or_no`, `student_name`, `total_fee`, `date`, `course`, `payment_date`, `balance`, `created_at`, `payment_amount`) VALUES
(1, '069305', 'AHmed Hadadi', 8000.00, '2025-05-20', 'CSS', '2025-05-26', 0.00, '2025-05-12 07:25:42', 15844),
(2, '685331', 'Ferdi', 10000.00, '2025-05-14', 'CSS', '2025-05-14', 10000.00, '2025-05-14 05:20:14', 3000);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `request_type` varchar(100) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `date_requested` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `student_name`, `request_type`, `status`, `date_requested`) VALUES
(0, 'Gabriel', 'Tor', 'Approved', '2025-05-13');

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
,`StudentID` int(11)
,`StudentName` varchar(255)
,`gender` varchar(20)
,`email` varchar(255)
,`phoneno` varchar(20)
,`Course` varchar(100)
,`StudentStatus` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `student_clearance`
--

CREATE TABLE `student_clearance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `library_clearance` enum('Pending','Cleared') DEFAULT 'Pending',
  `accounting_clearance` enum('Pending','Cleared') DEFAULT 'Pending',
  `dept_head_clearance` enum('Pending','Cleared') DEFAULT 'Pending',
  `final_clearance` enum('Pending','Cleared') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_clearance`
--

INSERT INTO `student_clearance` (`id`, `student_id`, `library_clearance`, `accounting_clearance`, `dept_head_clearance`, `final_clearance`) VALUES
(1, 1007, 'Cleared', 'Cleared', 'Cleared', 'Cleared'),
(2, 1008, 'Pending', 'Pending', 'Pending', 'Pending'),
(3, 1009, 'Cleared', 'Cleared', 'Cleared', 'Cleared'),
(4, 1011, 'Pending', 'Pending', 'Pending', 'Pending'),
(5, 1010, 'Pending', 'Pending', 'Pending', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `student_documents`
--

CREATE TABLE `student_documents` (
  `id` int(11) NOT NULL,
  `Admission_ID` int(11) NOT NULL,
  `birth_cert` tinyint(1) DEFAULT 0,
  `form137` tinyint(1) DEFAULT 0,
  `tor` tinyint(1) DEFAULT 0,
  `good_moral` tinyint(1) DEFAULT 0,
  `honorable_dismissal` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_documents`
--

INSERT INTO `student_documents` (`id`, `Admission_ID`, `birth_cert`, `form137`, `tor`, `good_moral`, `honorable_dismissal`) VALUES
(0, 0, 1, 1, 1, 1, 1),
(1, 1007, 0, 0, 1, 0, 0),
(2, 1008, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_grades`
--

CREATE TABLE `student_grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL,
  `school_year` int(255) NOT NULL,
  `semester` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_grades`
--

INSERT INTO `student_grades` (`id`, `student_id`, `subject`, `grade`, `remarks`, `school_year`, `semester`) VALUES
(1, 1010, 'Programming 1', '2', 'good', 0, ''),
(2, 1010, 'Programming 2', '3', 'Fair', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `days` varchar(50) NOT NULL,
  `room` varchar(50) NOT NULL,
  `teacher` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_subjects`
--

INSERT INTO `student_subjects` (`id`, `student_id`, `subject`, `start_time`, `end_time`, `days`, `room`, `teacher`) VALUES
(2, 1013, 'Programming', '14:57:00', '16:58:00', 'Mon', '211', 'Mr.Jino Barrentes'),
(3, 1008, 'Programming 2', '14:11:00', '18:13:00', 'Mon', '401', 'Mr.Burotos'),
(4, 1007, 'English', '15:03:00', '23:00:00', 'Wednesday', '123', 'Mr.Naruto');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmission_addstudent`
--

CREATE TABLE `tbladmission_addstudent` (
  `Admission_ID` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `gender` varchar(20) NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `province` varchar(100) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `city` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `fathers_name` varchar(255) NOT NULL,
  `fathers_occupation` varchar(255) NOT NULL,
  `father_contact` varchar(20) NOT NULL,
  `mothers_name` varchar(255) NOT NULL,
  `mothers_occupation` varchar(255) NOT NULL,
  `mother_contact` varchar(20) NOT NULL,
  `guardian_name` varchar(255) NOT NULL,
  `guardian_relationship` varchar(100) NOT NULL,
  `guardian_contact` varchar(20) NOT NULL,
  `applying_grade` varchar(20) NOT NULL,
  `prevschool` varchar(255) NOT NULL,
  `last_grade` varchar(20) NOT NULL,
  `course` varchar(100) NOT NULL,
  `student_type` varchar(50) NOT NULL,
  `confirm` tinyint(1) NOT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `sigdate` date DEFAULT NULL,
  `signature_path` varchar(255) NOT NULL,
  `document_path` varchar(255) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmission_addstudent`
--

INSERT INTO `tbladmission_addstudent` (`Admission_ID`, `full_name`, `birthdate`, `gender`, `nationality`, `religion`, `address`, `province`, `zip`, `city`, `email`, `phone`, `photo`, `fathers_name`, `fathers_occupation`, `father_contact`, `mothers_name`, `mothers_occupation`, `mother_contact`, `guardian_name`, `guardian_relationship`, `guardian_contact`, `applying_grade`, `prevschool`, `last_grade`, `course`, `student_type`, `confirm`, `signature`, `sigdate`, `signature_path`, `document_path`, `document_name`, `status`) VALUES
(1007, 'Gabriel', '2025-05-19', 'Male', 'zcvzxcvx', 'zxcvxczv', 'zxcvz', 'xzcv', 'cxvzxcv', 'zxvxz', 'aa@gmail.com', '', 'uploads/6820e48ce4184.jfif', 'zxcvzxcv', 'zxcv', '', 'xzcvxzcvzx', 'zxv', '', 'cvzxcv', 'zxcvzxc', '', '1st Year', 'vzxcv', '1st Year', 'BSCRIM', 'Irregular', 0, NULL, '2025-05-22', 'uploads/1746986180_signature_student-profile.jfif', '', '', 'Enrolled'),
(1008, 'Kevin durant', '2025-05-13', 'Male', 'vxvczxv', 'zxcvzxcv', 'zxcvzcx', 'vzxcv', 'zxcvz', 'xcvzxc', 'xvzxc@rasd.com', '', 'uploads/6820e8dd22459.jfif', 'cxvbcx', 'vbxcv', '', 'b,bm', 'bnm,', '', ',bm', 'nb,', '', '3rd Year', 'zbxcvbxc', '2nd Year', 'CTHM', 'New', 0, NULL, '2025-05-23', 'uploads/1746987255_signature_Archi.jfif', '', '', 'Enrolled'),
(1009, 'Taylor Sweep', '2025-05-20', 'Male', 'Tiger Commando', 'bvmnvbnm', 'zcxvzcxv', 'zxcvzxc', 'zxcvzxc', 'zxcvzxcv', 'z@gmial.com', '', 'uploads/68216c902b0ce.jfif', 'cxvbxcv', 'bxcvb', '', 'cxvbcxv', 'vbcxvb', '', 'bcxvbcxv', 'bcxvbcxv', '', '3rd Year', 'xcbcxvb', '2nd Year', 'BSA', 'new', 0, NULL, '2025-05-30', 'uploads/1747020971_signature_student-profile.jfif', '', '', 'Enrolled'),
(1010, 'AHmed Hadadi', '2025-05-21', 'Male', 'vcxbxcv', 'zxcvzxcvzxcv', 'xzvzcx', 'cvzxcvxz', 'vzxcv', 'zxcv', 'zxcvzxcv@gmail.com', '', 'uploads/68216dba4b123.jfif', 'xzcvzxcv', 'zxcvzx', '', 'zxcv', 'zxcvzxcv', '', 'cvzxcv', 'zxcvzxcv', '', '2nd Year', 'xzcvzcxv', '3rd Year', 'BSED', 'Old', 0, NULL, '2025-05-22', 'uploads/1747021262_signature_Intimacy.jfif', '', '', 'Enrolled'),
(1011, 'damaspo', '2025-05-11', 'Male', 'asdsad', 'asdasdas', 'asdas', 'asda', 'das', 'dasd', 'dasdas@gmail.com', '', 'uploads/6821b26ad13c3.jfif', 'xzcvzxcv', 'zxcvz', '', 'zxcvzxc', 'cvxzcv', '', 'zxcvzx', 'cvzxcvz', '', '2nd Year', 'zxczxc', '2nd Year', 'BSCRIM', 'irregular', 0, NULL, '2025-05-12', 'uploads/1747038850_signature_Intimacy.jfif', '', '', 'Enrolled'),
(1012, 'CongTibe', '2025-05-16', 'Male', 'asdasdasd', 'asdasd', 'asdasd', 'asdas', 'asdasd', 'dasdas', 'asdasd@gmail.com', '', 'uploads/6821bc7fa834a.jfif', 'asdas', 'asdas', '', 'asda', 'dasd', '', 'dsad', 'asdas', '', '3rd Year', 'asdas', '1st Year', 'BSCRIM', 'old', 0, NULL, '2025-05-15', 'uploads/1747041424_signature_Theology.jfif', '', '', 'Enrolled'),
(1013, 'Ferdi', '2025-05-14', 'Male', 'asdasd', 'sadasdas', 'asdasda', 'asdfad', 'sdasdas', 'safa', 'sadasd@gmail.com', '41244', '', 'cxzvxcv', 'zxcvzxv', '', 'zxcvzxc', 'xcvzxcv', '', 'xcvzxcv', 'zxcvzx', '', '2nd Year', 'xcvzxcvz', '1st Year', 'BSCS', 'new', 0, NULL, '2025-05-13', 'uploads/1747097720_signature_Selected photo (1).jfif', '', '', 'Enrolled');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmission_studenttype`
--

CREATE TABLE `tbladmission_studenttype` (
  `StudentType_ID` int(11) NOT NULL,
  `Admission_ID` int(11) NOT NULL,
  `StudentType` enum('new','old','irregular') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmission_studenttype`
--

INSERT INTO `tbladmission_studenttype` (`StudentType_ID`, `Admission_ID`, `StudentType`) VALUES
(1, 1007, 'irregular'),
(2, 1008, 'old'),
(3, 1009, 'new'),
(4, 1010, 'irregular'),
(5, 1011, 'irregular'),
(6, 1012, 'old'),
(0, 0, 'new');

-- --------------------------------------------------------

--
-- Table structure for table `tblstatus`
--

CREATE TABLE `tblstatus` (
  `id` int(11) NOT NULL,
  `Admission_ID` int(11) DEFAULT NULL,
  `enrollment_status` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstatus`
--

INSERT INTO `tblstatus` (`id`, `Admission_ID`, `enrollment_status`, `payment_status`) VALUES
(1, 1010, 'Enrolled', 'Paid'),
(2, 1009, 'Enrolled', 'Paid'),
(3, 1007, 'Enrolled', 'Paid'),
(8, 1011, 'Enrolled', 'Paid'),
(9, 1008, 'Enrolled', 'Paid'),
(10, 1012, 'Enrolled', 'Paid'),
(0, 1013, 'Enrolled', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `tuition`
--

CREATE TABLE `tuition` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `year_level` varchar(50) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `tuition` decimal(10,2) DEFAULT NULL,
  `monthly` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
  `misc_fee` decimal(10,2) DEFAULT NULL,
  `lab_fee` decimal(10,2) DEFAULT NULL,
  `total_tuition` decimal(10,2) DEFAULT NULL,
  `total_fee` decimal(10,2) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tuition`
--

INSERT INTO `tuition` (`id`, `student_id`, `year_level`, `course`, `tuition`, `monthly`, `payment_method`, `misc_fee`, `lab_fee`, `total_tuition`, `total_fee`, `balance`) VALUES
(6, 1010, '2nd Year', 'BSED', 6000.00, 3000.00, 'Installment', 1500.00, 500.00, 8000.00, 8000.00, 0.00),
(7, 1008, '3rd Year', 'CTHM', 6000.00, 3000.00, 'Installment', 1500.00, 499.98, 7999.98, 7999.98, 7999.98),
(8, 1011, '2nd Year', 'BSCRIM', 6000.00, 3000.00, 'Installment', 1500.00, 499.98, 7999.98, 7999.98, 7999.98),
(0, 1013, '2nd Year', 'BSCS', 6000.00, 3000.00, 'Installment', 1500.00, 2500.00, 10000.00, 10000.00, 10000.00);

-- --------------------------------------------------------

--
-- Structure for view `student_attendance_view`
--
DROP TABLE IF EXISTS `student_attendance_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_attendance_view`  AS SELECT `a`.`AttendanceID` AS `AttendanceID`, `a`.`Date` AS `Date`, `a`.`Status` AS `Status`, CASE WHEN `a`.`Status` = 0 THEN 'Absent' WHEN `a`.`Status` = 1 THEN 'Present' WHEN `a`.`Status` = 2 THEN 'Late' ELSE 'Unknown' END AS `StatusText`, `ad`.`Admission_ID` AS `StudentID`, `ad`.`full_name` AS `StudentName`, `ad`.`gender` AS `gender`, `ad`.`email` AS `email`, `ad`.`phone` AS `phoneno`, `ad`.`course` AS `Course`, `ad`.`status` AS `StudentStatus` FROM ((`attendance` `a` join `tbladmission_addstudent` `ad` on(`a`.`Admission_ID` = `ad`.`Admission_ID`)) join `student_documents` `sd` on(`a`.`Admission_ID` = `sd`.`Admission_ID`)) ORDER BY `a`.`Date` DESC, `ad`.`full_name` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`AttendanceID`),
  ADD UNIQUE KEY `student_date_unique` (`Admission_ID`,`Date`);

--
-- Indexes for table `class_schedules`
--
ALTER TABLE `class_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `receivables`
--
ALTER TABLE `receivables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_clearance`
--
ALTER TABLE `student_clearance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_documents`
--
ALTER TABLE `student_documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Admission_ID` (`Admission_ID`);

--
-- Indexes for table `student_grades`
--
ALTER TABLE `student_grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `tbladmission_addstudent`
--
ALTER TABLE `tbladmission_addstudent`
  ADD PRIMARY KEY (`Admission_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `AttendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `class_schedules`
--
ALTER TABLE `class_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `receivables`
--
ALTER TABLE `receivables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_clearance`
--
ALTER TABLE `student_clearance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_grades`
--
ALTER TABLE `student_grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbladmission_addstudent`
--
ALTER TABLE `tbladmission_addstudent`
  MODIFY `Admission_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1019;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD CONSTRAINT `student_subjects_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tbladmission_addstudent` (`Admission_ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
