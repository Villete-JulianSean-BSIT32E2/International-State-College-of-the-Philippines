-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 09:11 AM
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

--
-- Dumping data for table `admission`
--

INSERT INTO `admission` (`id`, `name`, `bdate`, `gender`, `nat`, `religion`, `curraddress`, `province`, `peraddress`, `zip`, `email`, `city`, `phoneno`) VALUES
(47, 'ROSWELL GRECIA', '2025-04-10', '', 'asdasd', 'asdas', 'asdasdasd', 'asd', 'dasasd', '1231', 'fasdfasda@gmila.com', 'das', '0921237474'),
(48, 'Lolo Bron', '2025-04-18', 'm', 'asdfasdf', 'asdfasdfadsf', 'asdfasdf', 'asdf', 'fasdf', '21313', 'Ampongs@yahoo.com', 'dsfadsdf', '0921237474'),
(49, 'Kevin Dirhams', '2025-04-30', '', 'dfasdf', 'fasdfsa', 'fsdafasd', 'fasd', 'fasdfasdf', '2312312', 'Ampongs@yahoo.com', 'sdafs', '0921237474'),
(50, 'Sung Jin-woo', '2025-04-11', '', 'asdfasda', 'asdfafas', 'ba talga to', 'fdasdfasd', 'dasasd', '12312312', 'SungjinWoo@gmail.com', 'sadas', '0921237474');

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
-- Table structure for table `irregular`
--

CREATE TABLE `irregular` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `irregular`
--

INSERT INTO `irregular` (`id`, `name`) VALUES
(10, 'Sung Jin-woo');

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
-- Table structure for table `new`
--

CREATE TABLE `new` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `new`
--

INSERT INTO `new` (`id`, `name`) VALUES
(8, 'ROSWELL GRECIA');

-- --------------------------------------------------------

--
-- Table structure for table `old`
--

CREATE TABLE `old` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `old`
--

INSERT INTO `old` (`id`, `name`) VALUES
(17, 'Kevin Dirhams');

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
-- Table structure for table `transferee`
--

CREATE TABLE `transferee` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transferee`
--

INSERT INTO `transferee` (`id`, `name`) VALUES
(11, 'Lolo Bron');

-- --------------------------------------------------------

--
-- Structure for view `joined_students`
--
DROP TABLE IF EXISTS `joined_students`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `joined_students`  AS SELECT `a`.`name` AS `name`, `d`.`applying_grade` AS `applying_grade`, `d`.`Course` AS `Course` FROM (`admission` `a` join `student_documents` `d` on(`a`.`name` <> 0)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admission`
--
ALTER TABLE `admission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guardian_info`
--
ALTER TABLE `guardian_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `irregular`
--
ALTER TABLE `irregular`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `new`
--
ALTER TABLE `new`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `old`
--
ALTER TABLE `old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_documents`
--
ALTER TABLE `student_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transferee`
--
ALTER TABLE `transferee`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admission`
--
ALTER TABLE `admission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `guardian_info`
--
ALTER TABLE `guardian_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `irregular`
--
ALTER TABLE `irregular`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `new`
--
ALTER TABLE `new`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `old`
--
ALTER TABLE `old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `student_documents`
--
ALTER TABLE `student_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `transferee`
--
ALTER TABLE `transferee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `update_joined_students` ON SCHEDULE EVERY 1 HOUR STARTS '2025-04-24 16:15:14' ON COMPLETION NOT PRESERVE ENABLE DO INSERT INTO joined_students (name, applying_grade, course)
  SELECT name, applying_grade, course FROM temp_admission$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
