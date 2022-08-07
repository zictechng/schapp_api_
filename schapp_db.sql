-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 07, 2022 at 05:16 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schapp_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_sessions`
--

CREATE TABLE `academic_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `academic_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a_action` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academic_sessions`
--

INSERT INTO `academic_sessions` (`id`, `academic_name`, `add_by`, `a_status`, `a_date`, `a_action`, `created_at`, `updated_at`) VALUES
(1, '2010/2020 Updated', 'Ken220', 'Deleted', '30/07/2022 14:30:34', NULL, '2022-07-30 13:30:34', '2022-07-30 14:28:21'),
(2, '2009/2010', 'Ken220', 'Deleted', '30/07/2022 15:27:53', NULL, '2022-07-30 14:27:53', '2022-07-30 16:01:57'),
(3, '2010/2011', 'Ken220', 'Deleted', '30/07/2022 15:28:06', NULL, '2022-07-30 14:28:06', '2022-07-30 16:01:56'),
(4, '2012/2013', 'Ken220', 'Deleted', '30/07/2022 15:28:16', NULL, '2022-07-30 14:28:16', '2022-07-30 16:01:54'),
(5, '2011/2012', 'Ken220', 'Active', '31/07/2022 14:23:36', NULL, '2022-07-31 13:23:36', '2022-07-31 13:23:36'),
(6, '2013/2014', 'Ken220', 'Active', '31/07/2022 14:24:17', NULL, '2022-07-31 13:24:17', '2022-07-31 13:24:17'),
(7, '2015/2016', 'Ken220', 'Active', '31/07/2022 14:24:37', NULL, '2022-07-31 13:24:37', '2022-07-31 13:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `activitity_logs`
--

CREATE TABLE `activitity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `m_username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_action` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_details` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_date` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_uid` bigint(20) DEFAULT NULL,
  `m_device_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_broswer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_device_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_ip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_record_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activitity_logs`
--

INSERT INTO `activitity_logs` (`id`, `m_username`, `m_action`, `m_status`, `m_details`, `m_date`, `m_uid`, `m_device_name`, `m_broswer`, `m_device_number`, `m_location`, `m_ip`, `m_city`, `m_record_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Created new class', 'Successful', 'A new class was created by Ken Young', '29/07/2022 14:18:59', 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-07-29 13:18:59', '2022-07-29 13:18:59'),
(2, 'Ken220', 'Created new class', 'Successful', 'A new class was created by Ken Young', '29/07/2022 14:23:20', 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-07-29 13:23:20', '2022-07-29 13:23:20'),
(3, 'Ken220', 'Created new class', 'Successful', 'A new class was created by Ken Young', '29/07/2022 15:48:24', 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-07-29 14:48:24', '2022-07-29 14:48:24'),
(4, 'Ken220', 'Created new class', 'Successful', 'A new class was created by Ken Young', '29/07/2022 15:48:32', 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-07-29 14:48:32', '2022-07-29 14:48:32'),
(5, 'Ken220', 'Deleted class', 'Successful', 'Ken Young, Delete class details (2)', '29/07/2022 15:48:40', 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-07-29 14:48:40', '2022-07-29 14:48:40'),
(6, 'Ken220', 'Deleted class', 'Successful', 'Ken Young, Delete class details', '29/07/2022 15:51:12', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-29 14:51:12', '2022-07-29 14:51:12'),
(7, 'Ken220', 'Created new class', 'Successful', 'A new class was created by Ken Young', '29/07/2022 15:53:38', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-29 14:53:38', '2022-07-29 14:53:38'),
(8, 'Ken220', 'Created new subject', 'Successful', 'A new subject was created by Ken Young', '29/07/2022 16:50:49', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-29 15:50:49', '2022-07-29 15:50:49'),
(9, 'Ken220', 'Created new subject', 'Successful', 'A new subject was created by Ken Young', '29/07/2022 16:51:15', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-29 15:51:15', '2022-07-29 15:51:15'),
(10, 'Ken220', 'Created new subject', 'Successful', 'A new subject was created by Ken Young', '29/07/2022 16:51:28', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-29 15:51:28', '2022-07-29 15:51:28'),
(11, 'Ken220', 'Created new subject', 'Successful', 'A new subject was created by Ken Young', '29/07/2022 16:51:36', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-29 15:51:36', '2022-07-29 15:51:36'),
(12, 'Ken220', 'Created new subject', 'Successful', 'A new subject was created by Ken Young', '29/07/2022 16:51:47', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-29 15:51:47', '2022-07-29 15:51:47'),
(13, 'Ken220', 'Created new subject', 'Successful', 'A new subject was created by Ken Young', '29/07/2022 16:51:58', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-29 15:51:58', '2022-07-29 15:51:58'),
(14, 'Ken220', 'Created new subject', 'Successful', 'A new subject was created by Ken Young', '29/07/2022 16:53:27', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-29 15:53:27', '2022-07-29 15:53:27'),
(15, 'Ken220', 'Created new subject', 'Successful', 'A new subject was created by Ken Young', '29/07/2022 16:53:55', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-29 15:53:55', '2022-07-29 15:53:55'),
(16, 'Ken220', 'Deleted subject', 'Successful', 'Ken Young, Delete subject details', '29/07/2022 17:01:26', 1, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-07-29 16:01:26', '2022-07-29 16:01:26'),
(17, 'Ken220', 'Deleted subject', 'Successful', 'Ken Young, Delete subject details', '30/07/2022 09:44:16', 1, NULL, NULL, NULL, NULL, NULL, NULL, 8, '2022-07-30 08:44:16', '2022-07-30 08:44:16'),
(18, 'Ken220', 'Deleted subject', 'Successful', 'Ken Young, Delete subject details', '30/07/2022 09:50:39', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-30 08:50:39', '2022-07-30 08:50:39'),
(19, 'Ken220', 'Deleted subject', 'Successful', 'Ken Young, Delete subject details', '30/07/2022 10:01:23', 1, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2022-07-30 09:01:23', '2022-07-30 09:01:23'),
(20, 'Ken220', 'Updated subject', 'Successful', 'Ken Young, Updated subject details', '30/07/2022 12:54:05', 1, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2022-07-30 11:54:05', '2022-07-30 11:54:05'),
(21, 'Ken220', 'Updated subject', 'Successful', 'Ken Young, Updated subject details', '30/07/2022 12:58:03', 1, NULL, NULL, NULL, NULL, NULL, NULL, 5, '2022-07-30 11:58:03', '2022-07-30 11:58:03'),
(22, 'Ken220', 'Updated class', 'Successful', 'Ken Young, Updated class details', '30/07/2022 13:30:37', 1, NULL, NULL, NULL, NULL, NULL, NULL, 5, '2022-07-30 12:30:37', '2022-07-30 12:30:37'),
(23, 'Ken220', 'Updated class', 'Successful', 'Ken Young, Updated class details', '30/07/2022 13:30:52', 1, NULL, NULL, NULL, NULL, NULL, NULL, 8, '2022-07-30 12:30:52', '2022-07-30 12:30:52'),
(24, 'Ken220', 'Created academic session', 'Successful', 'Ken Young, added academic session details', '30/07/2022 14:30:34', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-30 13:30:34', '2022-07-30 13:30:34'),
(25, 'Ken220', 'Updated session', 'Successful', 'Ken Young, Updated academic details', '30/07/2022 15:23:31', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-30 14:23:31', '2022-07-30 14:23:31'),
(26, 'Ken220', 'Created academic session', 'Successful', 'Ken Young, added academic session details', '30/07/2022 15:27:53', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-30 14:27:53', '2022-07-30 14:27:53'),
(27, 'Ken220', 'Created academic session', 'Successful', 'Ken Young, added academic session details', '30/07/2022 15:28:06', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-30 14:28:06', '2022-07-30 14:28:06'),
(28, 'Ken220', 'Created academic session', 'Successful', 'Ken Young, added academic session details', '30/07/2022 15:28:16', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-30 14:28:16', '2022-07-30 14:28:16'),
(29, 'Ken220', 'Deleted academic session', 'Successful', 'Ken Young, Delete academic session details', '30/07/2022 15:28:21', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-30 14:28:21', '2022-07-30 14:28:21'),
(30, 'Ken220', 'Created academic term', 'Successful', 'Ken Young, added new academic term details', '30/07/2022 16:14:46', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-30 15:14:46', '2022-07-30 15:14:46'),
(31, 'Ken220', 'Updated academic term', 'Successful', 'Ken Young, Updated academic term details', '30/07/2022 16:37:06', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-30 15:37:06', '2022-07-30 15:37:06'),
(32, 'Ken220', 'Created academic term', 'Successful', 'Ken Young, added new academic term details', '30/07/2022 16:37:57', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-30 15:37:57', '2022-07-30 15:37:57'),
(33, 'Ken220', 'Created academic term', 'Successful', 'Ken Young, added new academic term details', '30/07/2022 16:38:04', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-30 15:38:04', '2022-07-30 15:38:04'),
(34, 'Ken220', 'Deleted academic term', 'Successful', 'Ken Young, Delete academic term details', '30/07/2022 16:43:47', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-30 15:43:47', '2022-07-30 15:43:47'),
(35, 'Ken220', 'Deleted academic term', 'Successful', 'Ken Young, Delete academic term details', '30/07/2022 16:43:54', 1, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-07-30 15:43:54', '2022-07-30 15:43:54'),
(36, 'Ken220', 'Deleted academic term', 'Successful', 'Ken Young, Delete academic term details', '30/07/2022 16:47:30', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-30 15:47:30', '2022-07-30 15:47:30'),
(37, 'Ken220', 'Deleted academic term', 'Successful', 'Ken Young, Delete academic term details', '30/07/2022 16:47:34', 1, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-07-30 15:47:34', '2022-07-30 15:47:34'),
(38, 'Ken220', 'Deleted academic term', 'Successful', 'Ken Young, Delete academic term details', '30/07/2022 16:47:41', 1, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2022-07-30 15:47:41', '2022-07-30 15:47:41'),
(39, 'Ken220', 'Created academic term', 'Successful', 'Ken Young, added new academic term details', '30/07/2022 16:57:14', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-30 15:57:14', '2022-07-30 15:57:14'),
(40, 'Ken220', 'Deleted academic session', 'Successful', 'Ken Young, Delete academic session details', '30/07/2022 17:01:54', 1, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2022-07-30 16:01:54', '2022-07-30 16:01:54'),
(41, 'Ken220', 'Deleted academic session', 'Successful', 'Ken Young, Delete academic session details', '30/07/2022 17:01:56', 1, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2022-07-30 16:01:56', '2022-07-30 16:01:56'),
(42, 'Ken220', 'Deleted academic session', 'Successful', 'Ken Young, Delete academic session details', '30/07/2022 17:01:57', 1, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-07-30 16:01:57', '2022-07-30 16:01:57'),
(43, 'Ken220', 'Deleted academic term', 'Successful', 'Ken Young, Delete academic term details', '30/07/2022 17:03:37', 1, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2022-07-30 16:03:37', '2022-07-30 16:03:37'),
(44, 'Ken220', 'Created academic term', 'Successful', 'Ken Young, added new academic term details', '30/07/2022 17:04:36', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-30 16:04:36', '2022-07-30 16:04:36'),
(45, 'Ken220', 'Created academic term', 'Successful', 'Ken Young, added new academic term details', '30/07/2022 17:04:43', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-30 16:04:43', '2022-07-30 16:04:43'),
(46, 'Ken220', 'Deleted academic term', 'Successful', 'Ken Young, Delete academic term details', '30/07/2022 17:04:54', 1, NULL, NULL, NULL, NULL, NULL, NULL, 5, '2022-07-30 16:04:54', '2022-07-30 16:04:54'),
(47, 'Ken220', 'Deleted academic term', 'Successful', 'Ken Young, Delete academic term details', '30/07/2022 17:04:55', 1, NULL, NULL, NULL, NULL, NULL, NULL, 6, '2022-07-30 16:04:55', '2022-07-30 16:04:55'),
(48, 'Ken220', 'Created school category', 'Successful', 'Ken Young, added new school category details', '31/07/2022 12:18:51', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 11:18:51', '2022-07-31 11:18:51'),
(49, 'Ken220', 'Created school category', 'Successful', 'Ken Young, added new school category details', '31/07/2022 12:47:36', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 11:47:36', '2022-07-31 11:47:36'),
(50, 'Ken220', 'Deleted school category', 'Successful', 'Ken Young, Delete school category details', '31/07/2022 13:02:20', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-31 12:02:20', '2022-07-31 12:02:20'),
(51, 'Ken220', 'Created academic session', 'Successful', 'Ken Young, added academic session details', '31/07/2022 14:23:36', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 13:23:36', '2022-07-31 13:23:36'),
(52, 'Ken220', 'Created academic session', 'Successful', 'Ken Young, added academic session details', '31/07/2022 14:24:17', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 13:24:17', '2022-07-31 13:24:17'),
(53, 'Ken220', 'Created academic session', 'Successful', 'Ken Young, added academic session details', '31/07/2022 14:24:37', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 13:24:37', '2022-07-31 13:24:37'),
(54, 'Ken220', 'Created academic term', 'Successful', 'Ken Young, added new academic term details', '31/07/2022 14:29:49', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 13:29:49', '2022-07-31 13:29:49'),
(55, 'Ken220', 'Created academic term', 'Successful', 'Ken Young, added new academic term details', '31/07/2022 14:29:58', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 13:29:58', '2022-07-31 13:29:58'),
(56, 'Ken220', 'Created resumption date', 'Successful', 'Ken Young, added academic resumption date details', '31/07/2022 14:39:42', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 13:39:42', '2022-07-31 13:39:42'),
(57, 'Ken220', 'Updated resumption', 'Successful', 'Ken Young, Updated academic resumption details', '31/07/2022 15:36:27', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-31 14:36:27', '2022-07-31 14:36:27'),
(58, 'Ken220', 'Updated resumption', 'Successful', 'Ken Young, Updated academic resumption details', '31/07/2022 15:49:43', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-31 14:49:43', '2022-07-31 14:49:43'),
(59, 'Ken220', 'Created resumption date', 'Successful', 'Ken Young, added academic resumption date details', '31/07/2022 15:55:04', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 14:55:04', '2022-07-31 14:55:04'),
(60, 'Ken220', 'Created resumption date', 'Successful', 'Ken Young, added academic resumption date details', '31/07/2022 15:57:17', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 14:57:17', '2022-07-31 14:57:17'),
(61, 'Ken220', 'Deleted academic resumption', 'Successful', 'Ken Young, Delete academic resumption details', '31/07/2022 15:57:45', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-31 14:57:45', '2022-07-31 14:57:45'),
(62, 'Ken220', 'Created days school open', 'Successful', 'Ken Young, added number of days school opening details', '31/07/2022 17:04:48', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 16:04:48', '2022-07-31 16:04:48'),
(63, 'Ken220', 'Updated number of days school open', 'Successful', 'Ken Young, Updated number of days school opened details', '31/07/2022 17:31:14', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-31 16:31:14', '2022-07-31 16:31:14'),
(64, 'Ken220', 'Created days school open', 'Successful', 'Ken Young, added number of days school opening details', '31/07/2022 17:37:16', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 16:37:16', '2022-07-31 16:37:16'),
(65, 'Ken220', 'Updated number of days school open', 'Successful', 'Ken Young, Updated number of days school opened details', '31/07/2022 17:42:51', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-31 16:42:51', '2022-07-31 16:42:51'),
(66, 'Ken220', 'Updated number of days school open', 'Successful', 'Ken Young, Updated number of days school opened details', '31/07/2022 17:43:11', 1, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-07-31 16:43:11', '2022-07-31 16:43:11'),
(67, 'Ken220', 'Created days school open', 'Successful', 'Ken Young, added number of days school opening details', '31/07/2022 17:47:05', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 16:47:05', '2022-07-31 16:47:05'),
(68, 'Ken220', 'Created days school open', 'Successful', 'Ken Young, added number of days school opening details', '31/07/2022 17:47:53', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 16:47:53', '2022-07-31 16:47:53'),
(69, 'Ken220', 'Deleted number of school open', 'Successful', 'Ken Young, Delete number of days school opened details', '31/07/2022 17:48:03', 1, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2022-07-31 16:48:03', '2022-07-31 16:48:03'),
(70, 'Ken220', 'Deleted number of school open', 'Successful', 'Ken Young, Delete number of days school opened details', '31/07/2022 17:48:38', 1, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-07-31 16:48:38', '2022-07-31 16:48:38'),
(71, 'Ken220', 'Created academic running session', 'Successful', 'Ken Young, added academic current running session details', '31/07/2022 18:25:08', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 17:25:08', '2022-07-31 17:25:08'),
(72, 'Ken220', 'Updated running session', 'Successful', 'Ken Young, Updated current academic running session details', '31/07/2022 19:06:00', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-31 18:06:00', '2022-07-31 18:06:00'),
(73, 'Ken220', 'Updated running session', 'Successful', 'Ken Young, Updated current academic running session details', '31/07/2022 19:06:07', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-31 18:06:07', '2022-07-31 18:06:07'),
(74, 'Ken220', 'Created academic running session', 'Successful', 'Ken Young, added academic current running session details', '31/07/2022 19:12:19', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 18:12:19', '2022-07-31 18:12:19'),
(75, 'Ken220', 'Created academic running session', 'Successful', 'Ken Young, added academic current running session details', '31/07/2022 19:12:26', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 18:12:26', '2022-07-31 18:12:26'),
(76, 'Ken220', 'Deleted current running session open', 'Successful', 'Ken Young, Delete academic current running session details', '31/07/2022 19:12:36', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-07-31 18:12:36', '2022-07-31 18:12:36'),
(77, 'Ken220', 'Updated class', 'Successful', 'Ken Young, Updated class details', '31/07/2022 20:14:30', 1, NULL, NULL, NULL, NULL, NULL, NULL, 8, '2022-07-31 19:14:30', '2022-07-31 19:14:30'),
(78, 'Ken220', 'Created new subject', 'Successful', 'A new subject was created by Ken Young', '31/07/2022 20:17:37', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-31 19:17:37', '2022-07-31 19:17:37'),
(79, 'Ken220', 'Updated subject', 'Successful', 'Ken Young, Updated subject details', '31/07/2022 20:17:46', 1, NULL, NULL, NULL, NULL, NULL, NULL, 9, '2022-07-31 19:17:46', '2022-07-31 19:17:46'),
(80, 'Ken220', 'Deleted subject', 'Successful', 'Ken Young, Delete subject details', '31/07/2022 20:17:49', 1, NULL, NULL, NULL, NULL, NULL, NULL, 9, '2022-07-31 19:17:49', '2022-07-31 19:17:49'),
(81, 'Ken220', 'Created resumption date', 'Successful', 'Ken Young, added academic resumption date details', '03/08/2022 10:55:16', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-03 09:55:16', '2022-08-03 09:55:16'),
(82, 'Ken220', 'Created days school open', 'Successful', 'Ken Young, added number of days school opening details', '03/08/2022 11:12:43', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-03 10:12:43', '2022-08-03 10:12:43'),
(83, 'Ken220', 'Created days school open', 'Successful', 'Ken Young, added number of days school opening details', '03/08/2022 11:17:40', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-03 10:17:40', '2022-08-03 10:17:40'),
(84, 'Ken220', 'Update student details', 'Successful', 'Ken Young, updated student details', '03/08/2022 13:24:58', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-03 12:24:58', '2022-08-03 12:24:58'),
(85, 'Ken220', 'Registered student details', 'Successful', 'Ken Young, added new student details', '03/08/2022 13:43:47', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-03 12:43:47', '2022-08-03 12:43:47'),
(86, 'Ken220', 'Deleted student record', 'Successful', 'Ken Young, Delete student info details', '03/08/2022 13:45:10', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-08-03 12:45:10', '2022-08-03 12:45:10'),
(87, 'Ken220', 'Registered student details', 'Successful', 'Ken Young, added new student details', '03/08/2022 14:47:31', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-03 13:47:31', '2022-08-03 13:47:31'),
(88, 'Ken220', 'Registered staff details', 'Successful', 'Ken Young, added new staff details', '04/08/2022 12:09:16', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-04 11:09:16', '2022-08-04 11:09:16'),
(89, 'Ken220', 'Update staff details', 'Successful', 'Ken Young, updated staff details', '04/08/2022 13:11:04', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-04 12:11:04', '2022-08-04 12:11:04'),
(90, 'Ken220', 'Update staff details', 'Successful', 'Ken Young, updated staff details', '04/08/2022 13:12:25', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-04 12:12:25', '2022-08-04 12:12:25'),
(91, 'Ken220', 'Update staff details', 'Successful', 'Ken Young, updated staff details', '04/08/2022 13:13:04', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-04 12:13:04', '2022-08-04 12:13:04'),
(92, 'Ken220', 'Update staff details', 'Successful', 'Ken Young, updated staff details', '04/08/2022 13:13:52', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-04 12:13:52', '2022-08-04 12:13:52'),
(93, 'Ken220', 'Registered staff details', 'Successful', 'Ken Young, added new staff details', '04/08/2022 13:23:40', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-04 12:23:40', '2022-08-04 12:23:40'),
(94, 'Ken220', 'Deleted staff record', 'Successful', 'Ken Young, Delete staff info details', '04/08/2022 13:34:31', 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-08-04 12:34:31', '2022-08-04 12:34:31'),
(95, 'Ken220', 'Update staff password details', 'Successful', 'Ken Young, staff password details updated', '04/08/2022 15:23:10', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-04 14:23:10', '2022-08-04 14:23:10'),
(96, 'Ken220', 'Update staff password details', 'Successful', 'Ken Young, staff password details updated', '04/08/2022 15:50:03', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-04 14:50:03', '2022-08-04 14:50:03'),
(97, 'Ken220', 'Registered admin user details', 'Successful', 'Ken Young, added new admin user details', '04/08/2022 18:02:40', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-04 17:02:40', '2022-08-04 17:02:40'),
(98, 'Ken220', 'Registered admin user details', 'Successful', 'Ken Young, added new admin user details', '04/08/2022 18:04:01', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-04 17:04:01', '2022-08-04 17:04:01'),
(99, 'Ken220', 'Update admin password details', 'Successful', 'Ken Young, admin staff password details updated', '04/08/2022 18:48:00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-04 17:48:00', '2022-08-04 17:48:00'),
(100, 'Ken220', 'Update admin details', 'Successful', 'Ken Young, updated admin staff details', '04/08/2022 18:58:06', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-04 17:58:06', '2022-08-04 17:58:06'),
(101, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 11:14:42', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 10:14:42', '2022-08-05 10:14:42'),
(102, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 11:16:15', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 10:16:15', '2022-08-05 10:16:15'),
(103, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 11:27:01', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 10:27:01', '2022-08-05 10:27:01'),
(104, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 11:28:10', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 10:28:10', '2022-08-05 10:28:10'),
(105, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 11:30:15', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 10:30:15', '2022-08-05 10:30:15'),
(106, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 11:33:47', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 10:33:47', '2022-08-05 10:33:47'),
(107, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 11:35:04', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 10:35:04', '2022-08-05 10:35:04'),
(108, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 11:38:53', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 10:38:53', '2022-08-05 10:38:53'),
(109, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 11:40:42', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 10:40:42', '2022-08-05 10:40:42'),
(110, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 11:41:38', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 10:41:38', '2022-08-05 10:41:38'),
(111, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 11:41:59', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 10:41:59', '2022-08-05 10:41:59'),
(112, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 11:42:55', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 10:42:55', '2022-08-05 10:42:55'),
(113, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:00:28', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:00:28', '2022-08-05 11:00:28'),
(114, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:20:09', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:20:09', '2022-08-05 11:20:09'),
(115, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:23:19', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:23:19', '2022-08-05 11:23:19'),
(116, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:24:22', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:24:22', '2022-08-05 11:24:22'),
(117, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:26:25', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:26:25', '2022-08-05 11:26:25'),
(118, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:27:20', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:27:20', '2022-08-05 11:27:20'),
(119, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:28:46', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:28:46', '2022-08-05 11:28:46'),
(120, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:41:20', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:41:20', '2022-08-05 11:41:20'),
(121, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:42:45', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:42:45', '2022-08-05 11:42:45'),
(122, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:45:29', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:45:29', '2022-08-05 11:45:29'),
(123, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:48:37', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:48:37', '2022-08-05 11:48:37'),
(124, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:49:22', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:49:22', '2022-08-05 11:49:22'),
(125, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:50:55', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:50:55', '2022-08-05 11:50:55'),
(126, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:51:32', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:51:32', '2022-08-05 11:51:32'),
(127, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:52:26', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:52:26', '2022-08-05 11:52:26'),
(128, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:52:57', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:52:57', '2022-08-05 11:52:57'),
(129, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:53:12', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:53:12', '2022-08-05 11:53:12'),
(130, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:53:50', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:53:50', '2022-08-05 11:53:50'),
(131, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 12:54:48', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 11:54:48', '2022-08-05 11:54:48'),
(132, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 13:02:02', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 12:02:02', '2022-08-05 12:02:02'),
(133, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 13:04:11', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 12:04:11', '2022-08-05 12:04:11'),
(134, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 13:05:36', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 12:05:36', '2022-08-05 12:05:36'),
(135, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 13:20:44', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 12:20:44', '2022-08-05 12:20:44'),
(136, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 13:21:08', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 12:21:08', '2022-08-05 12:21:08'),
(137, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 14:02:22', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 13:02:22', '2022-08-05 13:02:22'),
(138, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 14:03:16', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 13:03:16', '2022-08-05 13:03:16'),
(139, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 14:05:05', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 13:05:05', '2022-08-05 13:05:05'),
(140, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '05/08/2022 14:07:58', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 13:07:58', '2022-08-05 13:07:58'),
(141, 'Ken220', 'Created new class', 'Successful', 'A new class was created by Ken Young', '06/08/2022 13:47:05', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-06 12:47:05', '2022-08-06 12:47:05'),
(142, 'Ken220', 'Created new class', 'Successful', 'A new class was created by Ken Young', '07/08/2022 12:39:33', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-07 11:39:33', '2022-08-07 11:39:33'),
(143, 'Ken220', 'Deleted class', 'Successful', 'Ken Young, Delete class details', '07/08/2022 12:39:43', 1, NULL, NULL, NULL, NULL, NULL, NULL, 10, '2022-08-07 11:39:43', '2022-08-07 11:39:43'),
(144, 'Ken220', 'Initiated result processing', 'Successful', 'Ken Young, Initiated new result processing details', '07/08/2022 12:41:18', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-07 11:41:18', '2022-08-07 11:41:18');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_level` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sex` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `addby` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acct_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acct_action` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reg_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `first_name`, `other_name`, `phone`, `email`, `user_name`, `access_level`, `sex`, `addby`, `acct_status`, `acct_action`, `password`, `reg_date`, `created_at`, `updated_at`) VALUES
(1, 'Ben', 'loveth', '98989878778', 'ben@gmail.com', 'ben', '3', 'Female', 'Ken220', 'Active', NULL, '$2y$10$EZ.VaOPuMlyxD9ImDPId0ueci1gNFh/EadepN26JXCW6KUTHQVkza', '04/08/2022 18:02:40', '2022-08-04 17:02:40', '2022-08-04 17:48:00'),
(2, 'Ken', 'Developer', '08037250238', 'kendone@gmail.com', 'ken99', '1', 'Male', 'Ken220', 'Active', NULL, '$2y$10$4YS/gi4aaTF.Qg0sgp20tusTlBMYQS2XrHHpg6m5I0J1PaWOllBSS', '04/08/2022 18:04:01', '2022-08-04 17:04:01', '2022-08-04 17:58:06');

-- --------------------------------------------------------

--
-- Table structure for table `class_models`
--

CREATE TABLE `class_models` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `record_date` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_models`
--

INSERT INTO `class_models` (`id`, `class_name`, `added_by`, `status`, `action`, `record_date`, `created_at`, `updated_at`) VALUES
(1, 'Blue House', 'Ken Young', 'Deleted', NULL, '29/07/2022 12:34:03', '2022-07-29 11:34:03', '2022-07-29 14:51:12'),
(2, 'svswwe', 'Ken Young', 'Deleted', NULL, '29/07/2022 14:18:59', '2022-07-29 13:18:59', '2022-07-29 14:48:40'),
(3, 'ddffd', 'Ken Young', 'Deleted', NULL, '29/07/2022 14:23:20', '2022-07-29 13:23:20', '2022-07-29 14:45:43'),
(4, 'ffffgg', 'Ken Young', 'Deleted', NULL, '29/07/2022 14:30:43', '2022-07-29 13:30:43', '2022-07-29 14:45:36'),
(5, 'JSS 2A Updated', 'Ken Young', 'Active', NULL, '29/07/2022 15:48:24', '2022-07-29 14:48:24', '2022-07-30 12:30:37'),
(6, 'JSS 3A', 'Ken Young', 'Active', NULL, '29/07/2022 15:48:32', '2022-07-29 14:48:32', '2022-07-29 14:48:32'),
(7, 'SS 1A', 'Ken Young', 'Active', NULL, '29/07/2022 15:52:25', '2022-07-29 14:52:25', '2022-07-29 14:52:25'),
(8, 'SS 2B', 'Ken Young', 'Active', NULL, '29/07/2022 15:53:38', '2022-07-29 14:53:38', '2022-07-31 19:14:30'),
(9, 'Pre-Nursery', 'Ken Young', 'Active', NULL, '06/08/2022 13:47:05', '2022-08-06 12:47:05', '2022-08-06 12:47:05'),
(10, 'dfgdthhyyy', 'Ken Young', 'Deleted', NULL, '07/08/2022 12:39:33', '2022-08-07 11:39:33', '2022-08-07 11:39:43');

-- --------------------------------------------------------

--
-- Table structure for table `current_sessions`
--

CREATE TABLE `current_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `running_session` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_addedby` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `current_sessions`
--

INSERT INTO `current_sessions` (`id`, `running_session`, `session_status`, `session_addedby`, `session_date`, `created_at`, `updated_at`) VALUES
(1, '7', 'Deleted', 'Ken220', '31/07/2022 18:25:08', '2022-07-31 17:25:08', '2022-07-31 18:12:36'),
(2, '5', 'Active', 'Ken220', '31/07/2022 19:12:19', '2022-07-31 18:12:19', '2022-07-31 18:12:19'),
(3, '6', 'Active', 'Ken220', '31/07/2022 19:12:26', '2022-07-31 18:12:26', '2022-07-31 18:12:26');

-- --------------------------------------------------------

--
-- Table structure for table `days_school_opens`
--

CREATE TABLE `days_school_opens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `days_open` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `open_term` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_addedby` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `days_school_opens`
--

INSERT INTO `days_school_opens` (`id`, `days_open`, `open_term`, `open_year`, `open_status`, `open_date`, `open_addedby`, `created_at`, `updated_at`) VALUES
(1, '107', '8', '6', 'Active', '31/07/2022 17:04:48', 'Ken220', '2022-07-31 16:04:48', '2022-07-31 16:42:51'),
(2, '70', '7', '5', 'Deleted', '31/07/2022 17:37:16', 'Ken220', '2022-07-31 16:37:16', '2022-07-31 16:48:38'),
(3, '10', '8', '5', 'Active', '31/07/2022 17:47:05', 'Ken220', '2022-07-31 16:47:05', '2022-07-31 16:47:05'),
(4, '5', '8', '7', 'Active', '31/07/2022 17:47:53', 'Ken220', '2022-07-31 16:47:53', '2022-07-31 16:47:53');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_07_29_120551_create_class_models_table', 2),
(6, '2022_07_29_124221_create_activitity_logs_table', 3),
(7, '2022_07_29_161714_create_subjects_table', 4),
(8, '2022_07_30_140218_create_academic_sessions_table', 5),
(9, '2022_07_30_154750_create_term_models_table', 6),
(10, '2022_07_31_120534_create_school_categories_table', 7),
(11, '2022_07_31_135655_create_school_resumptions_table', 8),
(12, '2022_07_31_162331_create_days_school_opens_table', 9),
(13, '2022_07_31_180427_create_current_sessions_table', 10),
(14, '2022_08_02_181829_create_students_table', 11),
(15, '2022_08_04_095355_create_staff_table', 12),
(16, '2022_08_04_155620_create_admin_users_table', 13),
(17, '2022_08_05_081044_create_result_tables_table', 14),
(18, '2022_08_05_095618_create_result_process_starts_table', 15),
(19, '2022_08_05_145006_create_result_saves_table', 16),
(20, '2022_08_07_121815_create_test_records_table', 17);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'ken@gmail.com_token', 'f6cabd074868dcd4f6990347ae5c4b8a54a622b67946ea6bc578c0bc38f526a3', '[\"*\"]', NULL, '2022-07-27 14:35:26', '2022-07-27 14:35:26'),
(2, 'App\\Models\\User', 1, 'ken@gmail.com_Token', '12c4056c8b2045ef562999e71da16a4e142b14075b53c3b429280f28720e899c', '[\"\"]', NULL, '2022-07-27 14:36:03', '2022-07-27 14:36:03'),
(3, 'App\\Models\\User', 1, 'ken@gmail.com_Token', 'a9e93f86d76e255b07aeb1a7bc0ec89998068bdf0ea637645e2528bf98e6a8b1', '[\"\"]', NULL, '2022-07-27 16:26:53', '2022-07-27 16:26:53'),
(4, 'App\\Models\\User', 1, 'ken@gmail.com_Token', '9f02b803b492eff1f9cf516fec7b238db1e7dc05a8d61fe24d0c6edd00b4e657', '[\"\"]', NULL, '2022-07-27 16:42:13', '2022-07-27 16:42:13'),
(5, 'App\\Models\\User', 1, 'ken@gmail.com_Token', '1d98a0bfb4472d562ba660f0781d2df883a21cad839222de8193276818780ced', '[\"\"]', NULL, '2022-07-28 08:23:41', '2022-07-28 08:23:41'),
(6, 'App\\Models\\User', 1, 'ken@gmail.com_Token', 'a0172bf73600f61d85bb24a16f74253e26a98ac1fa6b95f8d4fd1eb6f48e5abd', '[\"\"]', NULL, '2022-07-29 08:05:49', '2022-07-29 08:05:49'),
(7, 'App\\Models\\User', 1, 'ken@gmail.com_Token', 'c14f1c1a8c6740b3161bdffc9668d2bf884b1d18eacef157ef0bfa28fed96736', '[\"\"]', '2022-07-29 13:37:07', '2022-07-29 11:32:39', '2022-07-29 13:37:07'),
(8, 'App\\Models\\User', 1, 'ken@gmail.com_Token', '102cef1874a05eeefa860248528df3863954230a67acb7b320d6047027246e4c', '[\"\"]', '2022-07-29 16:19:34', '2022-07-29 14:17:24', '2022-07-29 16:19:34'),
(9, 'App\\Models\\User', 1, 'ken@gmail.com_Token', 'b1b22a2f6ca3d5e146c227b7e6533b979513582019505d957f8222ccdddd7abc', '[\"\"]', '2022-07-30 16:05:42', '2022-07-30 08:40:48', '2022-07-30 16:05:42'),
(10, 'App\\Models\\User', 1, 'ken@gmail.com_Token', '5a95d0e4cecc2518f2aa7de3dacef759f8366f13442cc178fdcd3eaa67440c96', '[\"\"]', NULL, '2022-07-31 10:47:39', '2022-07-31 10:47:39'),
(11, 'App\\Models\\User', 1, 'ken@gmail.com_Token', '8da2d8db538c52e64b4f53e8332f8941f602ec8b2209f1a31d8d6626b225b470', '[\"\"]', '2022-07-31 19:17:52', '2022-07-31 10:51:38', '2022-07-31 19:17:52'),
(12, 'App\\Models\\User', 1, 'ken@gmail.com_Token', 'da4af7981e7a7f6adda4af52ad4746593b921675acd484a49d3165284d5ccde8', '[\"\"]', '2022-08-02 20:28:18', '2022-08-02 17:07:21', '2022-08-02 20:28:18'),
(13, 'App\\Models\\User', 1, 'ken@gmail.com_Token', '993ff352148b1f98437c83898ae5b138c6e7cadb640bf85b0dd08d986ea0fa94', '[\"\"]', '2022-08-03 15:49:39', '2022-08-03 07:57:39', '2022-08-03 15:49:39'),
(14, 'App\\Models\\User', 1, 'ken@gmail.com_Token', '3d9958b26ecac4bca2c646e42884f6fe30d017ae530864db8403805346ecbdc7', '[\"\"]', '2022-08-03 19:21:07', '2022-08-03 18:00:53', '2022-08-03 19:21:07'),
(15, 'App\\Models\\User', 1, 'ken@gmail.com_Token', 'cad181f11563a078569c74fa5080bcbafabd146f050034e9c519cf9b4f2fec14', '[\"\"]', '2022-08-04 18:07:25', '2022-08-04 07:22:29', '2022-08-04 18:07:25'),
(16, 'App\\Models\\User', 1, 'ken@gmail.com_Token', 'e6eec05751581cbab913f30fd9fc7776c3b8b334e2b9b46941132bc3cc65ac59', '[\"\"]', '2022-08-06 10:27:34', '2022-08-05 06:49:43', '2022-08-06 10:27:34'),
(17, 'App\\Models\\User', 1, 'ken@gmail.com_Token', '166ac9e41d809720fdf3f3faf882b649c4e9c2014b74d1a635dfd003c1f04ecd', '[\"\"]', '2022-08-06 14:33:07', '2022-08-06 12:18:27', '2022-08-06 14:33:07'),
(18, 'App\\Models\\User', 1, 'ken@gmail.com_Token', '435df6246dd49b07d14f4fd6f0ab285d35e31d5f3a369319606daa06951760c1', '[\"\"]', '2022-08-07 13:58:22', '2022-08-07 10:53:33', '2022-08-07 13:58:22');

-- --------------------------------------------------------

--
-- Table structure for table `result_process_starts`
--

CREATE TABLE `result_process_starts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_term` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `r_tid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `addby` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `r_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `r_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `result_process_starts`
--

INSERT INTO `result_process_starts` (`id`, `school_year`, `school_term`, `class`, `school_category`, `subject`, `r_tid`, `addby`, `r_status`, `r_date`, `created_at`, `updated_at`) VALUES
(1, '5', '8', '5', '6', '2', 'zego8KBXOGapLIft', 'Ken220', 'Active', '05/08/2022 11:14:42', '2022-08-05 10:14:42', '2022-08-05 10:14:42'),
(2, '6', '7', '6', '5', '2', '6BGLxAibEmrI7qV1', 'Ken220', 'Active', '05/08/2022 11:16:15', '2022-08-05 10:16:15', '2022-08-05 10:16:15'),
(3, '6', '7', '8', '7', '2', 'ysOCZkdYlLgiE0aq', 'Ken220', 'Active', '05/08/2022 11:27:01', '2022-08-05 10:27:01', '2022-08-05 10:27:01'),
(4, '6', '8', '6', '6', '2', 'UfwHZYBqD5hRxTIQ', 'Ken220', 'Active', '05/08/2022 11:28:10', '2022-08-05 10:28:10', '2022-08-05 10:28:10'),
(5, '6', '7', '6', '5', '2', '9cfTavV3zkHRhrZx', 'Ken220', 'Active', '05/08/2022 11:30:15', '2022-08-05 10:30:15', '2022-08-05 10:30:15'),
(6, '7', '7', '6', '6', '2', 'G3uJEcRQeq0UrYjb', 'Ken220', 'Active', '05/08/2022 11:33:47', '2022-08-05 10:33:47', '2022-08-05 10:33:47'),
(7, '6', '8', '6', '6', '2', 'cKmV62wO1naZXHej', 'Ken220', 'Active', '05/08/2022 11:35:04', '2022-08-05 10:35:04', '2022-08-05 10:35:04'),
(8, '5', '7', '6', '5', '2', 'kUhVdn0CODLlsbgG', 'Ken220', 'Active', '05/08/2022 11:38:53', '2022-08-05 10:38:53', '2022-08-05 10:38:53'),
(9, '6', '8', '6', '6', '2', 'mNQxFKU8HaMjW26p', 'Ken220', 'Active', '05/08/2022 11:40:42', '2022-08-05 10:40:42', '2022-08-05 10:40:42'),
(10, '7', '8', '6', '5', '2', 'DNxq16rFbeVUzR4v', 'Ken220', 'Active', '05/08/2022 11:41:38', '2022-08-05 10:41:38', '2022-08-05 10:41:38'),
(11, '6', '7', '6', '6', '2', 'SKVHJYesGhnFyBta', 'Ken220', 'Active', '05/08/2022 11:41:59', '2022-08-05 10:41:59', '2022-08-05 10:41:59'),
(12, '6', '8', '6', '5', '2', 'jKLsMyRTPcAkNUpt', 'Ken220', 'Active', '05/08/2022 11:42:55', '2022-08-05 10:42:55', '2022-08-05 10:42:55'),
(13, '6', '8', '6', '4', '2', 'LqsXGA8FbSm4phl5', 'Ken220', 'Active', '05/08/2022 12:00:28', '2022-08-05 11:00:28', '2022-08-05 11:00:28'),
(14, '6', '8', '6', '6', '2', 'wBPZYHg6tlDkzmNR', 'Ken220', 'Active', '05/08/2022 12:20:09', '2022-08-05 11:20:09', '2022-08-05 11:20:09'),
(15, '6', '7', '6', '7', '2', 'L0wVOz3X9iG1Ekqy', 'Ken220', 'Active', '05/08/2022 12:23:19', '2022-08-05 11:23:19', '2022-08-05 11:23:19'),
(16, '6', '8', '6', '7', '2', 'KdNp34ihG5j9MEmf', 'Ken220', 'Active', '05/08/2022 12:24:22', '2022-08-05 11:24:22', '2022-08-05 11:24:22'),
(17, '6', '8', '6', '6', '2', 'LbUTfEYZzjn6BJd4', 'Ken220', 'Active', '05/08/2022 12:26:25', '2022-08-05 11:26:25', '2022-08-05 11:26:25'),
(18, '6', '8', '7', '6', '2', 'JYjms21aLQHxB0dM', 'Ken220', 'Active', '05/08/2022 12:27:20', '2022-08-05 11:27:20', '2022-08-05 11:27:20'),
(19, '6', '8', '7', '6', '2', 'L1DupNtyOVX3IiBf', 'Ken220', 'Active', '05/08/2022 12:28:46', '2022-08-05 11:28:46', '2022-08-05 11:28:46'),
(20, '6', '7', '7', '5', '2', 'eXaYkQz42NJRjg6O', 'Ken220', 'Active', '05/08/2022 12:41:20', '2022-08-05 11:41:20', '2022-08-05 11:41:20'),
(21, '6', '7', '6', '6', '2', 'TnOrgPIbik8q1ANu', 'Ken220', 'Active', '05/08/2022 12:42:45', '2022-08-05 11:42:45', '2022-08-05 11:42:45'),
(22, '6', '7', '6', '7', '2', 'plfLad37gn61zjXC', 'Ken220', 'Active', '05/08/2022 12:45:29', '2022-08-05 11:45:29', '2022-08-05 11:45:29'),
(23, '6', '7', '6', '6', '2', '23zvuMPfL6TIOwlY', 'Ken220', 'Active', '05/08/2022 12:48:37', '2022-08-05 11:48:37', '2022-08-05 11:48:37'),
(24, '6', '7', '6', '6', '2', 'MEmsoOAY4HZhpkn8', 'Ken220', 'Active', '05/08/2022 12:49:22', '2022-08-05 11:49:22', '2022-08-05 11:49:22'),
(25, '6', '7', '6', '5', '2', 'loST42pXhbfR0jeK', 'Ken220', 'Active', '05/08/2022 12:50:55', '2022-08-05 11:50:55', '2022-08-05 11:50:55'),
(26, '6', '8', '6', '6', '2', 'tiEvLcAy8ZVNHB1o', 'Ken220', 'Active', '05/08/2022 12:51:32', '2022-08-05 11:51:32', '2022-08-05 11:51:32'),
(27, '6', '7', '6', '6', '2', 'xzBu4rpNJ2jySdh8', 'Ken220', 'Active', '05/08/2022 12:52:26', '2022-08-05 11:52:26', '2022-08-05 11:52:26'),
(28, '6', '8', '6', '4', '2', 'gG5kvfrx9RYnzbFE', 'Ken220', 'Active', '05/08/2022 12:52:57', '2022-08-05 11:52:57', '2022-08-05 11:52:57'),
(29, '5', '8', '6', '7', '2', 'A1eh4VkUX7Jt56Zi', 'Ken220', 'Active', '05/08/2022 12:53:12', '2022-08-05 11:53:12', '2022-08-05 11:53:12'),
(30, '6', '8', '6', '7', '2', 'Ox8QdsEY6Vcb1Pt7', 'Ken220', 'Active', '05/08/2022 12:53:50', '2022-08-05 11:53:50', '2022-08-05 11:53:50'),
(31, '6', '7', '6', '5', '2', 'T2wBZg4AKoWhFLVE', 'Ken220', 'Active', '05/08/2022 12:54:48', '2022-08-05 11:54:48', '2022-08-05 11:54:48'),
(32, '5', '7', '6', '5', '2', 'lcyoLdJf4aU8Gkgi', 'Ken220', 'Active', '05/08/2022 13:02:02', '2022-08-05 12:02:02', '2022-08-05 12:02:02'),
(33, '6', '7', '7', '6', '2', '2TFhnPmGY75oiklL', 'Ken220', 'Active', '05/08/2022 13:04:11', '2022-08-05 12:04:11', '2022-08-05 12:04:11'),
(34, '6', '7', '6', '6', '2', 'KzkJtTv0cURPCjy8', 'Ken220', 'Active', '05/08/2022 13:05:36', '2022-08-05 12:05:36', '2022-08-05 12:05:36'),
(35, '6', '8', '6', '7', '2', 'shigMBnZU46OTNz7', 'Ken220', 'Active', '05/08/2022 13:20:44', '2022-08-05 12:20:44', '2022-08-05 12:20:44'),
(36, '6', '7', '6', '6', '2', 'Jx6oC2iVkRBsYjzh', 'Ken220', 'Active', '05/08/2022 13:21:08', '2022-08-05 12:21:08', '2022-08-05 12:21:08'),
(37, '6', '8', '6', '6', '2', 'NLI47Ei21um6xbZD', 'Ken220', 'Active', '05/08/2022 14:02:22', '2022-08-05 13:02:22', '2022-08-05 13:02:22'),
(38, '6', '8', '6', '6', '2', 'xrX18RkCMJWjB24K', 'Ken220', 'Active', '05/08/2022 14:03:16', '2022-08-05 13:03:16', '2022-08-05 13:03:16'),
(39, '6', '7', '6', '6', '2', 'pyIC6TAkcYXdJvah', 'Ken220', 'Active', '05/08/2022 14:05:05', '2022-08-05 13:05:05', '2022-08-05 13:05:05'),
(40, '6', '7', '6', '6', '2', 'YTKXGxSVmQ3JvNtl', 'Ken220', 'Active', '05/08/2022 14:07:58', '2022-08-05 13:07:58', '2022-08-05 13:07:58'),
(41, '6', '7', '6', '6', '2', 'AdXfgjJSxPD0BE2n', 'Ken220', 'Active', '07/08/2022 12:41:18', '2022-08-07 11:41:18', '2022-08-07 11:41:18');

-- --------------------------------------------------------

--
-- Table structure for table `result_saves`
--

CREATE TABLE `result_saves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_3` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_4` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_5` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_6` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `addby` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `res_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reg_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `result_tables`
--

CREATE TABLE `result_tables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `academic_year` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `academy_term` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_ca` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_ca` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `earn_hrs` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hrs_work` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tca_score` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exam_scores` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_scores` bigint(20) DEFAULT NULL,
  `grade` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `average_scores` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_total` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tid_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `student_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `result_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `result_action` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `result_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `result_lowest` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `result_highest` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `result_action_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `school_categories`
--

CREATE TABLE `school_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sc_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sc_add_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sc_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sc_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sc_action` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_categories`
--

INSERT INTO `school_categories` (`id`, `sc_name`, `sc_add_by`, `sc_status`, `sc_date`, `sc_action`, `created_at`, `updated_at`) VALUES
(1, 'Primary School', 'Ken220', 'Deleted', '31/07/2022 12:18:51', NULL, '2022-07-31 11:18:51', '2022-07-31 12:02:20'),
(2, 'Pre-Nursery', 'Ken220', 'Active', '31/07/2022 12:47:36', NULL, '2022-07-31 11:47:36', '2022-07-31 11:47:36');

-- --------------------------------------------------------

--
-- Table structure for table `school_resumptions`
--

CREATE TABLE `school_resumptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `start_date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `close_date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `next_resumption` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_year` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_term` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `add_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_resumptions`
--

INSERT INTO `school_resumptions` (`id`, `start_date`, `close_date`, `next_resumption`, `school_year`, `school_term`, `added_by`, `status`, `add_date`, `created_at`, `updated_at`) VALUES
(1, '2022-07-04', '2022-07-08', '2022-07-12', '7', '7', 'Ken220', 'Deleted', '31/07/2022 14:39:42', '2022-07-31 13:39:42', '2022-07-31 14:57:45'),
(2, '2022-07-20', '2022-07-28', '2022-07-30', '6', '8', 'Ken220', 'Active', '31/07/2022 15:55:04', '2022-07-31 14:55:04', '2022-07-31 14:55:04'),
(3, '2022-07-09', '2022-07-11', '2022-07-06', '7', '7', 'Ken220', 'Active', '31/07/2022 15:57:17', '2022-07-31 14:57:17', '2022-07-31 14:57:17'),
(4, '2022-08-16', '2022-08-16', '2022-08-16', '5', '8', 'Ken220', 'Active', '03/08/2022 10:55:16', '2022-08-03 09:55:16', '2022-08-03 09:55:16');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `surname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sex` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qualification` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acct_username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_level` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `home_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `addby` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acct_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acct_action` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reg_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `surname`, `other_name`, `sex`, `email`, `phone`, `dob`, `staff_id`, `school_category`, `qualification`, `acct_username`, `staff_password`, `state`, `country`, `class`, `staff_level`, `home_address`, `staff_image`, `addby`, `acct_status`, `acct_action`, `reg_date`, `created_at`, `updated_at`) VALUES
(1, 'Bello 2', 'Young 2', 'Others', 'bellow2@gmail.com', '0987654345670000', '2022-08-19', '9909ooo', '2', 'BSC 2', 'bellow2', NULL, 'Benue', 'NG 2', '5', NULL, 'dcvwcqeqwcqwq 2', NULL, 'Ken220', 'Deleted', NULL, '04/08/2022 12:09:16', '2022-08-04 11:09:16', '2022-08-04 12:34:31'),
(2, 'Ken', 'Young', 'Male', 'ken@gmail.com', '08037250238', '2022-08-14', '778', '2', 'Software Eng.', 'ken', '$2y$10$iahc2Ly.Pqs2rTmKC0zpcunSTyDdJv0eClm.byt44c.BINBpfHi1S', 'Benue', 'UK', '6', 'Teacher', 'Home is good', 'uploads/staff_image/1659623727.jpg', 'Ken220', 'Active', NULL, '04/08/2022 13:23:40', '2022-08-04 12:23:40', '2022-08-04 14:50:03');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `surname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sex` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_age` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lga` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_sch_attend` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_class_attend` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_apply` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schooling_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `academic_year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_admin_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardia_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardia_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardia_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardia_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_zone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_depart` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_rank` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `health_issue` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reg_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acct_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_file_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acct_action` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `surname`, `other_name`, `sex`, `dob`, `st_age`, `state`, `lga`, `country`, `last_sch_attend`, `last_class_attend`, `class_apply`, `schooling_type`, `academic_year`, `school_category`, `st_admin_number`, `st_image`, `guardia_name`, `guardia_email`, `guardia_number`, `guardia_address`, `staff_zone`, `staff_depart`, `staff_rank`, `health_issue`, `reg_date`, `acct_status`, `staff_file_no`, `acct_action`, `created_at`, `updated_at`) VALUES
(1, 'rgerferf', 'rgergerg', NULL, '2022-08-16', NULL, NULL, NULL, 'gbdfgber', 'wefwef', 'rgw', '7', 'Boarding', '6', '2', '4356345432', NULL, 'rgerg', 'wgfwgw@gmail.com', '0987354', 'fvsfdvdcvw', 'erwe', 'IT', 'Staff', NULL, '03/08/2022 11:12:43', 'Deleted', '', 'Ken220', '2022-08-03 10:12:43', '2022-08-03 12:45:10'),
(2, 'gbfgbgb', 'fdfvsvs', 'Female', '2022-08-16', NULL, 'Borno', NULL, 'dfbfvs', 'svsdvsd', 'vsdvsd', '6', 'Boarding', '7', '2', 'CS/30303', NULL, 'werfwerfwr', 'sdfvsdcv', '32523525', 'sdcasxc', 'Benin', 'Staff', 'Operator', 'No', '03/08/2022 11:17:40', 'Active', '20202', 'Ken220', '2022-08-03 10:17:40', '2022-08-03 12:24:58'),
(3, 'Uwadia', 'Ken Developer', 'Male', '2022-08-23', NULL, 'Benue', NULL, 'Nigeria', 'ICC', 'Upper Class', '6', 'Day', '7', '2', 'ICC/90998/09', 'uploads/student_image/1659557828.jpg', 'Uwadia', 'ken@gmail.com', '08037250238', 'Benin Road, Lagos', 'Lagos', 'Admin', 'Level 12', NULL, '03/08/2022 13:43:47', 'Active', '20937', 'Ken220', '2022-08-03 12:43:47', '2022-08-03 19:17:08'),
(4, 'rgerg', 'regerg', 'Female', '2010-08-03', '12 Years', 'AkwaIbom', NULL, 'wrgweg', 'regrd', 'ggwegew', '6', 'Boarding', '6', '2', 'wewewe333', NULL, 'wergweg', 'wegwegew', 'wefwef', 'wefwefe', 'wewegw', 'gwgqegw', 'wegweg', 'Others', '03/08/2022 14:47:31', 'Active', '22352', 'Ken220', '2022-08-03 13:47:31', '2022-08-03 13:47:31');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_addedby` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_name`, `sub_addedby`, `sub_status`, `action`, `sub_date`, `created_at`, `updated_at`) VALUES
(1, 'Math', 'Ken Young', 'Deleted', NULL, '29/07/2022 16:50:49', '2022-07-29 15:50:49', '2022-07-30 08:50:39'),
(2, 'maths', 'Ken Young', 'Deleted', NULL, '29/07/2022 16:51:15', '2022-07-29 15:51:15', '2022-07-29 16:01:26'),
(3, 'English Language', 'Ken Young', 'Deleted', NULL, '29/07/2022 16:51:28', '2022-07-29 15:51:28', '2022-07-30 09:01:23'),
(4, 'Physic Updated', 'Ken Young', 'Active', NULL, '29/07/2022 16:51:36', '2022-07-29 15:51:36', '2022-07-30 11:54:05'),
(5, 'Chemistry Updated', 'Ken Young', 'Active', NULL, '29/07/2022 16:51:47', '2022-07-29 15:51:47', '2022-07-30 11:58:03'),
(6, 'Socialogy', 'Ken Young', 'Active', NULL, '29/07/2022 16:51:58', '2022-07-29 15:51:58', '2022-07-29 15:51:58'),
(7, 'Biology Science', 'Ken Young', 'Active', NULL, '29/07/2022 16:53:27', '2022-07-29 15:53:27', '2022-07-29 15:53:27'),
(8, 'Economics', 'Ken Young', 'Deleted', NULL, '29/07/2022 16:53:55', '2022-07-29 15:53:55', '2022-07-30 08:44:16'),
(9, 'Art 2', 'Ken Young', 'Deleted', NULL, '31/07/2022 20:17:37', '2022-07-31 19:17:37', '2022-07-31 19:17:49');

-- --------------------------------------------------------

--
-- Table structure for table `term_models`
--

CREATE TABLE `term_models` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `term_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t_action` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `term_models`
--

INSERT INTO `term_models` (`id`, `term_name`, `add_by`, `t_status`, `t_date`, `t_action`, `created_at`, `updated_at`) VALUES
(1, 'First Term 22', 'Ken220', 'Deleted', '30/07/2022 16:14:46', NULL, '2022-07-30 15:14:46', '2022-07-30 15:47:30'),
(2, 'Second Term', 'Ken220', 'Deleted', '30/07/2022 16:37:57', NULL, '2022-07-30 15:37:57', '2022-07-30 15:47:34'),
(3, 'Third Term', 'Ken220', 'Deleted', '30/07/2022 16:38:04', NULL, '2022-07-30 15:38:04', '2022-07-30 15:47:41'),
(4, 'First Term', 'Ken220', 'Deleted', '30/07/2022 16:57:14', NULL, '2022-07-30 15:57:14', '2022-07-30 16:03:37'),
(5, 'First Term', 'Ken220', 'Deleted', '30/07/2022 17:04:36', NULL, '2022-07-30 16:04:36', '2022-07-30 16:04:54'),
(6, 'Third Term', 'Ken220', 'Deleted', '30/07/2022 17:04:43', NULL, '2022-07-30 16:04:43', '2022-07-30 16:04:55'),
(7, 'First Term', 'Ken220', 'Active', '31/07/2022 14:29:49', NULL, '2022-07-31 13:29:49', '2022-07-31 13:29:49'),
(8, 'Third Term', 'Ken220', 'Active', '31/07/2022 14:29:58', NULL, '2022-07-31 13:29:58', '2022-07-31 13:29:58');

-- --------------------------------------------------------

--
-- Table structure for table `test_records`
--

CREATE TABLE `test_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `record_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` bigint(20) DEFAULT NULL,
  `unit_price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purch_price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selling_price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `addby` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rec_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rec_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sex` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mpcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acct_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reg_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `occupation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reg_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'User',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `sex`, `state`, `location`, `address`, `username`, `dob`, `mpcode`, `acct_status`, `reg_status`, `occupation`, `reg_date`, `photo`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ken Young', 'ken@gmail.com', '08037250238', NULL, NULL, NULL, NULL, 'Ken220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'User', NULL, '$2y$10$0nqm9txF1BII.lghYyo0.u30BS8B4RefXRRHsSG1d7gBozSC.jM0m', NULL, '2022-07-27 14:35:26', '2022-07-27 14:35:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_sessions`
--
ALTER TABLE `academic_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activitity_logs`
--
ALTER TABLE `activitity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_models`
--
ALTER TABLE `class_models`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `current_sessions`
--
ALTER TABLE `current_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `days_school_opens`
--
ALTER TABLE `days_school_opens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `result_process_starts`
--
ALTER TABLE `result_process_starts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `result_saves`
--
ALTER TABLE `result_saves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `result_tables`
--
ALTER TABLE `result_tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_categories`
--
ALTER TABLE `school_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_resumptions`
--
ALTER TABLE `school_resumptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `term_models`
--
ALTER TABLE `term_models`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_records`
--
ALTER TABLE `test_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_sessions`
--
ALTER TABLE `academic_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `activitity_logs`
--
ALTER TABLE `activitity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `class_models`
--
ALTER TABLE `class_models`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `current_sessions`
--
ALTER TABLE `current_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `days_school_opens`
--
ALTER TABLE `days_school_opens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `result_process_starts`
--
ALTER TABLE `result_process_starts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `result_saves`
--
ALTER TABLE `result_saves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `result_tables`
--
ALTER TABLE `result_tables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_categories`
--
ALTER TABLE `school_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `school_resumptions`
--
ALTER TABLE `school_resumptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `term_models`
--
ALTER TABLE `term_models`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `test_records`
--
ALTER TABLE `test_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
