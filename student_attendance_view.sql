-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2025 at 09:28 PM
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
-- Structure for view `student_attendance_view`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_attendance_view`  AS SELECT `a`.`AttendanceID` AS `AttendanceID`, `a`.`Date` AS `Date`, `a`.`Status` AS `Status`, CASE WHEN `a`.`Status` = 0 THEN 'Absent' WHEN `a`.`Status` = 1 THEN 'Present' WHEN `a`.`Status` = 2 THEN 'Late' ELSE 'Unknown' END AS `StatusText`, `ad`.`id` AS `StudentID`, `ad`.`name` AS `StudentName`, `ad`.`gender` AS `gender`, `ad`.`email` AS `email`, `ad`.`phoneno` AS `phoneno`, `sd`.`Course` AS `Course`, `sd`.`applying_grade` AS `CurrentGrade`, `sd`.`last_grade` AS `PreviousGrade`, `sd`.`status_std` AS `StudentStatus`, `sd`.`prevschool` AS `PreviousSchool` FROM ((`attendance` `a` join `admission` `ad` on(`a`.`id` = `ad`.`id`)) join `student_documents` `sd` on(`a`.`id` = `sd`.`id`)) ORDER BY `a`.`Date` DESC, `ad`.`name` ASC ;

--
-- VIEW `student_attendance_view`
-- Data: None
--

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
