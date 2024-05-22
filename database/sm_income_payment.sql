-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2024 at 10:03 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbkkw4rfsaxdu5`
--

-- --------------------------------------------------------

--
-- Table structure for table `sm_income_payment`
--

CREATE TABLE `sm_income_payment` (
  `pt_id` int(11) NOT NULL,
  `pt_receipt_no` int(11) NOT NULL DEFAULT 0,
  `pt_voucher_no` varchar(12) DEFAULT NULL,
  `pt_tran_u_type` varchar(25) NOT NULL COMMENT 'Exam Fee,Event Fee,Course Fee',
  `pt_tran_bank` varchar(50) NOT NULL DEFAULT '',
  `pt_tran_mode_of_payent` varchar(25) NOT NULL COMMENT 'Cheque,Cash,DD',
  `pt_tran_no` varchar(255) NOT NULL,
  `pt_tran_amount` float NOT NULL,
  `pt_tran_date` date NOT NULL,
  `pt_tran_remarks` varchar(1000) NOT NULL,
  `pt_sc_id` int(11) DEFAULT NULL,
  `pt_br_id` int(11) NOT NULL DEFAULT 0,
  `pt_iet_id` int(11) DEFAULT 0,
  `pt_stu_id` int(11) NOT NULL DEFAULT 0,
  `pt_create_date` date NOT NULL,
  `pt_create_by_id` int(11) NOT NULL,
  `pt_update_date` date DEFAULT NULL,
  `pt_update_by_id` int(11) NOT NULL,
  `pt_discount_amount` float DEFAULT 0,
  `pt_refund_amount` float DEFAULT 0,
  `pt_receipt_no_dealer` int(11) DEFAULT NULL,
  `pt_receipt_no_income` int(11) DEFAULT NULL,
  `pt_receipt_no_expance` int(11) DEFAULT NULL,
  `pt_ac_id` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sm_income_payment`
--

INSERT INTO `sm_income_payment` (`pt_id`, `pt_receipt_no`, `pt_voucher_no`, `pt_tran_u_type`, `pt_tran_bank`, `pt_tran_mode_of_payent`, `pt_tran_no`, `pt_tran_amount`, `pt_tran_date`, `pt_tran_remarks`, `pt_sc_id`, `pt_br_id`, `pt_iet_id`, `pt_stu_id`, `pt_create_date`, `pt_create_by_id`, `pt_update_date`, `pt_update_by_id`, `pt_discount_amount`, `pt_refund_amount`, `pt_receipt_no_dealer`, `pt_receipt_no_income`, `pt_receipt_no_expance`, `pt_ac_id`) VALUES
(1, 0, NULL, 'Income', '', 'Net Banking', '', 88788, '2024-05-22', 'jbwdwkdjb', 1, 1, 34, 0, '2024-05-22', 1, '2024-05-22', 1, 0, 0, NULL, 72, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sm_income_payment`
--
ALTER TABLE `sm_income_payment`
  ADD PRIMARY KEY (`pt_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sm_income_payment`
--
ALTER TABLE `sm_income_payment`
  MODIFY `pt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
