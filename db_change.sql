-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 03, 2018 at 08:40 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `martcontrol`
--

-- --------------------------------------------------------

--
-- Table structure for table `sm_book`
--

CREATE TABLE `sm_book` (
  `book_id` int(11) NOT NULL,
  `book_title` varchar(255) NOT NULL,
  `book_author` varchar(255) NOT NULL,
  `book_publication` varchar(255) NOT NULL,
  `book_status` char(10) NOT NULL DEFAULT '',
  `book_br_id` int(11) NOT NULL,
  `book_issue_stu_id` int(11) NOT NULL,
  `book_issue_date` date NOT NULL,
  `book_create_date` datetime NOT NULL,
  `book_create_by_id` int(11) NOT NULL,
  `book_update_date` datetime NOT NULL,
  `book_update_by_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sm_book`
--

INSERT INTO `sm_book` (`book_id`, `book_title`, `book_author`, `book_publication`, `book_status`, `book_br_id`, `book_issue_stu_id`, `book_issue_date`, `book_create_date`, `book_create_by_id`, `book_update_date`, `book_update_by_id`) VALUES
(2, 'Mayur Title', 'mayur author', 'mayur publication', 'A', 1, 0, '0000-00-00', '2018-09-04 00:07:01', 1, '2018-09-04 00:07:01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sm_book_issue_history`
--

CREATE TABLE `sm_book_issue_history` (
  `bi_id` int(11) NOT NULL,
  `bi_book_id` int(11) NOT NULL,
  `bi_stu_id` int(11) NOT NULL,
  `bi_issue_date` date DEFAULT NULL,
  `bi_issue_date_valid` date DEFAULT NULL,
  `bi_return_date` date DEFAULT NULL,
  `bi_status` char(10) DEFAULT '',
  `bi_admin_id` int(11) NOT NULL,
  `bi_br_id` int(11) NOT NULL,
  `bi_create_date` datetime NOT NULL,
  `bi_create_by_id` int(11) NOT NULL,
  `bi_update_date` datetime NOT NULL,
  `bi_update_by_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sm_faculty`
--

CREATE TABLE `sm_faculty` (
  `fac_id` int(11) NOT NULL,
  `fac_identity_id` varchar(20) NOT NULL,
  `fac_name` varchar(50) NOT NULL,
  `fac_phone_1` varchar(15) NOT NULL,
  `fac_phone_2` varchar(15) NOT NULL,
  `fac_email` varchar(50) NOT NULL,
  `fac_address_1` varchar(100) NOT NULL,
  `fac_address_2` varchar(100) NOT NULL,
  `fac_city` varchar(50) NOT NULL,
  `fac_photo` varchar(255) NOT NULL DEFAULT '',
  `fac_st_id` int(11) NOT NULL,
  `fac_subject` varchar(50) NOT NULL,
  `fac_experience` varchar(50) NOT NULL,
  `fac_br_id` int(11) NOT NULL,
  `fac_status` char(1) NOT NULL DEFAULT 'A',
  `fac_designation` varchar(255) NOT NULL DEFAULT '',
  `fac_create_date` datetime NOT NULL,
  `fac_create_by_id` int(11) NOT NULL,
  `fac_update_date` datetime NOT NULL,
  `fac_update_by_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sm_faculty`
--

INSERT INTO `sm_faculty` (`fac_id`, `fac_identity_id`, `fac_name`, `fac_phone_1`, `fac_phone_2`, `fac_email`, `fac_address_1`, `fac_address_2`, `fac_city`, `fac_photo`, `fac_st_id`, `fac_subject`, `fac_experience`, `fac_br_id`, `fac_status`, `fac_designation`, `fac_create_date`, `fac_create_by_id`, `fac_update_date`, `fac_update_by_id`) VALUES
(2, '2BML44ZM2R', 'Mayur Dhudasia', '8000638058', '', 'it.mayur@yahoo.com', '', '', '', 'http://localhost/martialart-admin/images/faculties/faculties_imld9_prakalp_date_error.png', 0, 'no subjecrt', '', 1, 'A', 'no desi', '2018-09-03 09:13:35', 1, '2018-09-03 09:13:35', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sm_faculty_document`
--

CREATE TABLE `sm_faculty_document` (
  `doc_id` int(11) NOT NULL,
  `doc_fac_id` int(11) NOT NULL,
  `doc_name` varchar(200) NOT NULL DEFAULT '',
  `doc_br_id` int(11) NOT NULL DEFAULT '0',
  `doc_file_name_original` varchar(255) NOT NULL,
  `doc_file_name_save` varchar(255) NOT NULL,
  `doc_create_date` datetime NOT NULL,
  `doc_create_by_id` int(11) NOT NULL,
  `doc_update_date` datetime NOT NULL,
  `doc_update_by_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sm_book`
--
ALTER TABLE `sm_book`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `sm_book_issue_history`
--
ALTER TABLE `sm_book_issue_history`
  ADD PRIMARY KEY (`bi_id`);

--
-- Indexes for table `sm_faculty`
--
ALTER TABLE `sm_faculty`
  ADD PRIMARY KEY (`fac_id`),
  ADD UNIQUE KEY `fac_identity_id` (`fac_identity_id`),
  ADD UNIQUE KEY `fac_email` (`fac_email`),
  ADD UNIQUE KEY `fac_email_2` (`fac_email`);

--
-- Indexes for table `sm_faculty_document`
--
ALTER TABLE `sm_faculty_document`
  ADD PRIMARY KEY (`doc_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sm_book`
--
ALTER TABLE `sm_book`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sm_book_issue_history`
--
ALTER TABLE `sm_book_issue_history`
  MODIFY `bi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sm_faculty`
--
ALTER TABLE `sm_faculty`
  MODIFY `fac_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sm_faculty_document`
--
ALTER TABLE `sm_faculty_document`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
