-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2026 at 12:48 PM
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
-- Database: `hospital_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_role` varchar(255) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `method` varchar(10) DEFAULT NULL,
  `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'low',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `user_role`, `action`, `module`, `description`, `model_type`, `model_id`, `old_values`, `new_values`, `extra`, `ip_address`, `user_agent`, `url`, `method`, `severity`, `created_at`, `updated_at`) VALUES
(366, 7, 'samin Afridi', 'doctor', 'created', 'Lab Order', 'Created Lab Order #10', 'App\\Models\\LabOrder', 10, NULL, '{\"patient_id\":\"1\",\"doctor_id\":\"9\",\"appointment_id\":null,\"order_date\":\"2026-06-15 10:20:00\",\"priority\":\"Routine\",\"notes\":null,\"discount\":\"0\",\"paid_amount\":0,\"order_number\":\"LAB-00010\",\"updated_at\":\"2026-06-15 10:20:37\",\"created_at\":\"2026-06-15 10:20:37\",\"id\":10}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/lab/orders', 'POST', 'medium', '2026-06-15 05:20:37', '2026-06-15 05:20:37'),
(367, 7, 'samin Afridi', 'doctor', 'created', 'labOrder', 'Created labOrder #15', 'App\\Models\\LabOrderItem', 15, NULL, '{\"lab_order_id\":10,\"lab_test_id\":\"1\",\"price\":\"500\",\"discount\":\"0\",\"final_price\":500,\"updated_at\":\"2026-06-15 10:20:37\",\"created_at\":\"2026-06-15 10:20:37\",\"id\":15}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/lab/orders', 'POST', 'medium', '2026-06-15 05:20:37', '2026-06-15 05:20:37'),
(368, 7, 'samin Afridi', 'doctor', 'updated', 'Lab Order', 'Updated Lab Order #10 — changed: total_amount', 'App\\Models\\LabOrder', 10, '{\"total_amount\":\"0.00\"}', '{\"total_amount\":\"500.00\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/lab/orders', 'POST', 'medium', '2026-06-15 05:20:37', '2026-06-15 05:20:37'),
(369, 7, 'samin Afridi', 'doctor', 'updated', 'Lab Order', 'Updated Lab Order #10 — changed: total_amount', 'App\\Models\\LabOrder', 10, NULL, '{\"total_amount\":\"500.00\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/lab/orders', 'POST', 'medium', '2026-06-15 05:20:37', '2026-06-15 05:20:37'),
(370, 7, 'samin Afridi', 'doctor', 'updated', 'Lab Order', 'Updated Lab Order #10 — changed: payment_status', 'App\\Models\\LabOrder', 10, NULL, '{\"payment_status\":\"Unpaid\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/lab/orders', 'POST', 'medium', '2026-06-15 05:20:37', '2026-06-15 05:20:37'),
(371, 11, 'Kamran Akmal', 'super_admin', 'viewed', 'bill', 'Viewed bill #24', 'App\\Models\\Bill', 24, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/billing/24', 'GET', 'low', '2026-06-15 05:27:04', '2026-06-15 05:27:04'),
(372, 7, 'samin Afridi', 'doctor', 'viewed', 'patient', 'Viewed patient #1', 'App\\Models\\Patient', 1, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/reports/patients/1', 'GET', 'low', '2026-06-15 05:30:54', '2026-06-15 05:30:54'),
(373, 7, 'samin Afridi', 'doctor', 'viewed', 'patient', 'Viewed patient #1', 'App\\Models\\Patient', 1, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/reports/patients/1', 'GET', 'low', '2026-06-15 05:33:37', '2026-06-15 05:33:37'),
(374, 11, 'Kamran Akmal', 'super_admin', 'viewed', 'bill', 'Viewed bill #24', 'App\\Models\\Bill', 24, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/billing/24/print', 'GET', 'low', '2026-06-15 05:38:32', '2026-06-15 05:38:32'),
(375, 11, 'Kamran Akmal', 'super_admin', 'viewed', 'bill', 'Viewed bill #24', 'App\\Models\\Bill', 24, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/billing/24/print', 'GET', 'low', '2026-06-15 05:39:59', '2026-06-15 05:39:59'),
(376, 11, 'Kamran Akmal', 'super_admin', 'viewed', 'bill', 'Viewed bill #24', 'App\\Models\\Bill', 24, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/billing/24/print', 'GET', 'low', '2026-06-15 05:40:33', '2026-06-15 05:40:33'),
(377, 11, 'Kamran Akmal', 'super_admin', 'viewed', 'bill', 'Viewed bill #24', 'App\\Models\\Bill', 24, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/billing/24/print', 'GET', 'low', '2026-06-15 05:40:56', '2026-06-15 05:40:56'),
(378, 11, 'Kamran Akmal', 'super_admin', 'viewed', 'bill', 'Viewed bill #24', 'App\\Models\\Bill', 24, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/billing/24/print', 'GET', 'low', '2026-06-15 05:41:08', '2026-06-15 05:41:08'),
(379, 11, 'Kamran Akmal', 'super_admin', 'viewed', 'bill', 'Viewed bill #24', 'App\\Models\\Bill', 24, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/billing/24/print', 'GET', 'low', '2026-06-15 05:41:17', '2026-06-15 05:41:17'),
(380, 11, 'Kamran Akmal', 'super_admin', 'viewed', 'bill', 'Viewed bill #24', 'App\\Models\\Bill', 24, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/billing/24/print', 'GET', 'low', '2026-06-15 05:41:21', '2026-06-15 05:41:21'),
(381, 11, 'Kamran Akmal', 'super_admin', 'viewed', 'bill', 'Viewed bill #24', 'App\\Models\\Bill', 24, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/billing/24/print', 'GET', 'low', '2026-06-15 05:41:23', '2026-06-15 05:41:23'),
(382, NULL, 'System', NULL, 'logout', 'Auth', 'User \'\' logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/logout', 'POST', 'low', '2026-06-15 06:50:59', '2026-06-15 06:50:59'),
(383, 5, 'sadia imran', 'receptionist', 'login', 'Auth', 'User \'sadia\' logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'http://127.0.0.1:8000/login', 'POST', 'medium', '2026-06-15 06:51:44', '2026-06-15 06:51:44'),
(384, 5, 'sadia imran', 'receptionist', 'viewed', 'bill', 'Viewed bill #24', 'App\\Models\\Bill', 24, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'http://127.0.0.1:8000/billing/24', 'GET', 'low', '2026-06-15 06:53:29', '2026-06-15 06:53:29'),
(385, 5, 'sadia imran', 'receptionist', 'viewed', 'bill', 'Viewed bill #24', 'App\\Models\\Bill', 24, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'http://127.0.0.1:8000/billing/24/print', 'GET', 'low', '2026-06-15 06:53:44', '2026-06-15 06:53:44'),
(386, 11, 'Kamran Akmal', 'super_admin', 'login', 'Auth', 'User \'kamran\' logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'medium', '2026-06-16 02:39:25', '2026-06-16 02:39:25'),
(387, 5, 'sadia imran', 'receptionist', 'login', 'Auth', 'User \'sadia\' logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'http://127.0.0.1:8000/login', 'POST', 'medium', '2026-06-16 02:39:57', '2026-06-16 02:39:57'),
(388, 11, 'Kamran Akmal', 'super_admin', 'login', 'Auth', 'User \'kamran\' logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'medium', '2026-06-16 06:02:37', '2026-06-16 06:02:37'),
(389, 11, 'Kamran Akmal', 'super_admin', 'updated', 'Bed', 'Updated Bed #22 — changed: status, patient_id, discharged_at, updated_at', 'App\\Models\\Bed', 22, '{\"status\":\"Occupied\",\"patient_id\":33,\"discharged_at\":null,\"updated_at\":\"2026-05-19T02:48:00.000000Z\"}', '{\"status\":\"Available\",\"patient_id\":null,\"discharged_at\":\"2026-06-16 11:03:16\",\"updated_at\":\"2026-06-16 11:03:16\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards/beds/22/discharge', 'POST', 'medium', '2026-06-16 06:03:16', '2026-06-16 06:03:16'),
(390, 11, 'Kamran Akmal', 'super_admin', 'updated', 'Patient', 'Updated Patient #33 — changed: status, updated_at', 'App\\Models\\Patient', 33, '{\"status\":\"Deceased\",\"updated_at\":\"2026-05-19T06:31:58.000000Z\"}', '{\"status\":\"Discharged\",\"updated_at\":\"2026-06-16 11:03:16\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards/beds/22/discharge', 'POST', 'medium', '2026-06-16 06:03:16', '2026-06-16 06:03:16'),
(391, 11, 'Kamran Akmal', 'super_admin', 'updated', 'medicine batch', 'Updated medicine batch #22 — changed: quantity_in_stock', 'App\\Models\\MedicineBatch', 22, '{\"quantity_in_stock\":19}', '{\"quantity_in_stock\":15}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/pharmacy/dispensings', 'POST', 'medium', '2026-06-16 06:03:34', '2026-06-16 06:03:34'),
(392, 11, 'Kamran Akmal', 'super_admin', 'updated', 'prescription item', 'Updated prescription item #16 — changed: dispensed_qty', 'App\\Models\\PrescriptionItem', 16, '{\"dispensed_qty\":0}', '{\"dispensed_qty\":4}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/pharmacy/dispensings', 'POST', 'medium', '2026-06-16 06:03:34', '2026-06-16 06:03:34'),
(393, 11, 'Kamran Akmal', 'super_admin', 'created', 'Dispensing Medicine', 'Created Dispensing Medicine #12', 'App\\Models\\Dispensing', 12, NULL, '{\"patient_id\":\"35\",\"prescription_id\":\"13\",\"dispensed_at\":\"2026-06-16 11:03:34\",\"total_amount\":840,\"payment_status\":\"Unpaid\",\"notes\":null,\"dispense_number\":\"DSP-00012\",\"updated_at\":\"2026-06-16 11:03:34\",\"created_at\":\"2026-06-16 11:03:34\",\"id\":12}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/pharmacy/dispensings', 'POST', 'medium', '2026-06-16 06:03:34', '2026-06-16 06:03:34'),
(394, 11, 'Kamran Akmal', 'super_admin', 'created', 'Dispensing Item', 'Created Dispensing Item #15', 'App\\Models\\DispensingItem', 15, NULL, '{\"medicine_id\":\"34\",\"medicine_batch_id\":\"22\",\"prescription_item_id\":\"16\",\"quantity\":\"4\",\"unit_price\":\"210.00\",\"total_price\":840,\"dispensing_id\":12,\"updated_at\":\"2026-06-16 11:03:34\",\"created_at\":\"2026-06-16 11:03:34\",\"id\":15}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/pharmacy/dispensings', 'POST', 'medium', '2026-06-16 06:03:34', '2026-06-16 06:03:34'),
(395, 11, 'Kamran Akmal', 'super_admin', 'updated', 'Medicine', 'Updated Medicine #34 — changed: total_stock, updated_at', 'App\\Models\\Medicine', 34, '{\"total_stock\":88,\"updated_at\":\"2026-06-05T02:40:40.000000Z\"}', '{\"total_stock\":\"84\",\"updated_at\":\"2026-06-16 11:03:34\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/pharmacy/dispensings', 'POST', 'medium', '2026-06-16 06:03:34', '2026-06-16 06:03:34'),
(396, 11, 'Kamran Akmal', 'super_admin', 'updated', 'Prescription', 'Updated Prescription #13 — changed: status, updated_at', 'App\\Models\\Prescription', 13, '{\"status\":\"Pending\",\"updated_at\":\"2026-06-15T09:12:38.000000Z\"}', '{\"status\":\"Dispensed\",\"updated_at\":\"2026-06-16 11:03:34\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/pharmacy/dispensings', 'POST', 'medium', '2026-06-16 06:03:34', '2026-06-16 06:03:34'),
(397, 11, 'Kamran Akmal', 'super_admin', 'viewed', 'bill', 'Viewed bill #24', 'App\\Models\\Bill', 24, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/billing/24/print', 'GET', 'low', '2026-06-16 06:06:32', '2026-06-16 06:06:32'),
(398, 11, 'Kamran Akmal', 'super_admin', 'viewed', 'patient', 'Viewed patient #35', 'App\\Models\\Patient', 35, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/reports/patients/35', 'GET', 'low', '2026-06-16 06:07:10', '2026-06-16 06:07:10'),
(399, 7, 'samin Afridi', 'doctor', 'login', 'Auth', 'User \'samin\' logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'medium', '2026-06-16 06:42:16', '2026-06-16 06:42:16'),
(400, 11, 'Kamran Akmal', 'super_admin', 'login', 'Auth', 'User \'kamran\' logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'medium', '2026-06-17 00:38:32', '2026-06-17 00:38:32'),
(401, 11, 'Kamran Akmal', 'super_admin', 'created', 'DoctorOrders', 'Created DoctorOrders #1', 'App\\Models\\PatientDoctorOrder', 1, NULL, '{\"order_type\":\"Investigation\",\"title\":\"Investor Integration Planner\",\"details\":\"Constans colo conturbo maxime.\",\"special_instructions\":\"Abeo accusator combibo arbitro ustilo.\",\"priority\":\"Urgent\",\"patient_id\":44,\"doctor_id\":1,\"status\":\"Pending\",\"bed_id\":21,\"order_number\":\"ORD-00001\",\"ordered_at\":\"2026-06-17 06:41:51\",\"updated_at\":\"2026-06-17 06:41:51\",\"created_at\":\"2026-06-17 06:41:51\",\"id\":1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/ward/patients/44/orders', 'POST', 'medium', '2026-06-17 01:41:51', '2026-06-17 01:41:51'),
(402, 11, 'Kamran Akmal', 'super_admin', 'updated', 'DoctorOrders', 'Updated DoctorOrders #1 — changed: acknowledged_by, status, acknowledged_at, updated_at', 'App\\Models\\PatientDoctorOrder', 1, '{\"acknowledged_by\":null,\"status\":\"Pending\",\"acknowledged_at\":null,\"updated_at\":\"2026-06-17T06:41:51.000000Z\"}', '{\"acknowledged_by\":11,\"status\":\"Acknowledged\",\"acknowledged_at\":\"2026-06-17 06:42:01\",\"updated_at\":\"2026-06-17 06:42:01\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/ward/orders/1/acknowledge', 'POST', 'medium', '2026-06-17 01:42:01', '2026-06-17 01:42:01'),
(403, 11, 'Kamran Akmal', 'super_admin', 'updated', 'DoctorOrders', 'Updated DoctorOrders #1 — changed: status, completed_at, updated_at', 'App\\Models\\PatientDoctorOrder', 1, '{\"status\":\"Acknowledged\",\"completed_at\":null,\"updated_at\":\"2026-06-17T06:42:01.000000Z\"}', '{\"status\":\"Completed\",\"completed_at\":\"2026-06-17 06:42:09\",\"updated_at\":\"2026-06-17 06:42:09\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/ward/orders/1/complete', 'POST', 'medium', '2026-06-17 01:42:09', '2026-06-17 01:42:09'),
(404, 11, 'Kamran Akmal', 'super_admin', 'created', 'Ward', 'Created Ward #4', 'App\\Models\\Ward', 4, NULL, '{\"type\":\"Orthopedic\",\"name\":\"Ortheropedic ward\",\"total_beds\":\"20\",\"floor\":\"4th\",\"block\":\"A\",\"bed_charges\":\"4000\",\"description\":\"the ward have all functions available\",\"ward_code\":\"W-003\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":4}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(405, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #394', 'App\\Models\\Bed', 394, NULL, '{\"bed_number\":\"W-003-01\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":394}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(406, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #395', 'App\\Models\\Bed', 395, NULL, '{\"bed_number\":\"W-003-02\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":395}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(407, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #396', 'App\\Models\\Bed', 396, NULL, '{\"bed_number\":\"W-003-03\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":396}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(408, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #397', 'App\\Models\\Bed', 397, NULL, '{\"bed_number\":\"W-003-04\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":397}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(409, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #398', 'App\\Models\\Bed', 398, NULL, '{\"bed_number\":\"W-003-05\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":398}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(410, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #399', 'App\\Models\\Bed', 399, NULL, '{\"bed_number\":\"W-003-06\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":399}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(411, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #400', 'App\\Models\\Bed', 400, NULL, '{\"bed_number\":\"W-003-07\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":400}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(412, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #401', 'App\\Models\\Bed', 401, NULL, '{\"bed_number\":\"W-003-08\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":401}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(413, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #402', 'App\\Models\\Bed', 402, NULL, '{\"bed_number\":\"W-003-09\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":402}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(414, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #403', 'App\\Models\\Bed', 403, NULL, '{\"bed_number\":\"W-003-10\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":403}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(415, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #404', 'App\\Models\\Bed', 404, NULL, '{\"bed_number\":\"W-003-11\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":404}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(416, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #405', 'App\\Models\\Bed', 405, NULL, '{\"bed_number\":\"W-003-12\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":405}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(417, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #406', 'App\\Models\\Bed', 406, NULL, '{\"bed_number\":\"W-003-13\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":406}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(418, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #407', 'App\\Models\\Bed', 407, NULL, '{\"bed_number\":\"W-003-14\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":407}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(419, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #408', 'App\\Models\\Bed', 408, NULL, '{\"bed_number\":\"W-003-15\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":408}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(420, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #409', 'App\\Models\\Bed', 409, NULL, '{\"bed_number\":\"W-003-16\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":409}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(421, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #410', 'App\\Models\\Bed', 410, NULL, '{\"bed_number\":\"W-003-17\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":410}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(422, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #411', 'App\\Models\\Bed', 411, NULL, '{\"bed_number\":\"W-003-18\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":411}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(423, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #412', 'App\\Models\\Bed', 412, NULL, '{\"bed_number\":\"W-003-19\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":412}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(424, 11, 'Kamran Akmal', 'super_admin', 'created', 'Bed', 'Created Bed #413', 'App\\Models\\Bed', 413, NULL, '{\"bed_number\":\"W-003-20\",\"ward_id\":4,\"type\":\"Standard\",\"status\":\"Available\",\"updated_at\":\"2026-06-17 10:17:09\",\"created_at\":\"2026-06-17 10:17:09\",\"id\":413}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/wards', 'POST', 'medium', '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(425, 11, 'Kamran Akmal', 'super_admin', 'created', 'user', 'Created user #12', 'App\\Models\\User', 12, NULL, '{\"employee_id\":\"10\",\"name\":\"Asma Javid\",\"username\":\"asma\",\"email\":\"asma@gmail.com\",\"password\":\"*** HIDDEN ***\",\"role\":\"nurse\",\"is_active\":true,\"updated_at\":\"2026-06-17 11:03:39\",\"created_at\":\"2026-06-17 11:03:39\",\"id\":12}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/admin/users', 'POST', 'medium', '2026-06-17 06:03:39', '2026-06-17 06:03:39'),
(426, 12, 'Asma Javid', 'nurse', 'login', 'Auth', 'User \'asma\' logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'http://127.0.0.1:8000/login', 'POST', 'medium', '2026-06-17 06:04:53', '2026-06-17 06:04:53'),
(427, NULL, 'System', NULL, 'logout', 'Auth', 'User \'\' logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'http://127.0.0.1:8000/logout', 'POST', 'low', '2026-06-17 06:06:56', '2026-06-17 06:06:56'),
(428, 12, 'Asma Javid', 'nurse', 'login', 'Auth', 'User \'asma\' logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'http://127.0.0.1:8000/login', 'POST', 'medium', '2026-06-17 06:07:09', '2026-06-17 06:07:09'),
(429, 11, 'Kamran Akmal', 'super_admin', 'created', 'Discharge', 'Created Discharge #1', 'App\\Models\\PatientDischarge', 1, NULL, '{\"doctor_id\":\"8\",\"discharge_date\":\"2026-06-17 00:00:00\",\"discharge_type\":\"Normal\",\"condition_at_discharge\":\"Improved\",\"admission_diagnosis\":\"the dasdfe ;asl erj\",\"final_diagnosis\":\"asldnf werffase\",\"treatment_summary\":\"ljasdf\'m awernasdf\",\"procedures_done\":\"asdfwefsdfe\",\"discharge_instructions\":\"sadfwefsdf\",\"medications_on_discharge\":\"sdfaserasdfwaef\",\"diet_instructions\":\"sdffwae\",\"activity_instructions\":\"wwercsdf\",\"follow_up_date\":null,\"follow_up_with\":\"samin afridi\",\"notes\":\"asdfsdfsd\",\"patient_id\":2,\"bed_id\":1,\"processed_by\":11,\"admitted_date\":\"2026-05-13 00:00:00\",\"status\":\"Draft\",\"discharge_number\":\"DC-00001\",\"total_days\":35,\"updated_at\":\"2026-06-17 11:38:28\",\"created_at\":\"2026-06-17 11:38:28\",\"id\":1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/ward/patients/2/discharge', 'POST', 'medium', '2026-06-17 06:38:28', '2026-06-17 06:38:28'),
(430, 11, 'Kamran Akmal', 'super_admin', 'updated', 'Bed', 'Updated Bed #1 — changed: status, patient_id, discharged_at, updated_at', 'App\\Models\\Bed', 1, '{\"status\":\"Occupied\",\"patient_id\":2,\"discharged_at\":null,\"updated_at\":\"2026-05-13T03:03:57.000000Z\"}', '{\"status\":\"Available\",\"patient_id\":null,\"discharged_at\":\"2026-06-17 11:38:28\",\"updated_at\":\"2026-06-17 11:38:28\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/ward/patients/2/discharge', 'POST', 'medium', '2026-06-17 06:38:28', '2026-06-17 06:38:28'),
(431, 11, 'Kamran Akmal', 'super_admin', 'updated', 'Discharge', 'Updated Discharge #1 — changed: status, finalized_at, updated_at', 'App\\Models\\PatientDischarge', 1, '{\"status\":\"Draft\",\"finalized_at\":null,\"updated_at\":\"2026-06-17T11:38:28.000000Z\"}', '{\"status\":\"Final\",\"finalized_at\":\"2026-06-17 11:39:36\",\"updated_at\":\"2026-06-17 11:39:36\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/ward/discharge/1/finalize', 'POST', 'medium', '2026-06-17 06:39:36', '2026-06-17 06:39:36'),
(432, 11, 'Kamran Akmal', 'super_admin', 'login', 'Auth', 'User \'kamran\' logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'medium', '2026-06-17 12:00:08', '2026-06-17 12:00:08'),
(433, NULL, 'System', NULL, 'logout', 'Auth', 'User \'\' logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/logout', 'POST', 'low', '2026-06-17 12:00:22', '2026-06-17 12:00:22'),
(434, 11, 'Kamran Akmal', 'super_admin', 'login', 'Auth', 'User \'kamran\' logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'medium', '2026-06-19 00:53:44', '2026-06-19 00:53:44'),
(435, 12, 'Asma Javid', 'nurse', 'login', 'Auth', 'User \'asma\' logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'medium', '2026-06-19 01:00:51', '2026-06-19 01:00:51'),
(436, 7, 'samin Afridi', 'doctor', 'login', 'Auth', 'User \'samin\' logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'http://127.0.0.1:8000/login', 'POST', 'medium', '2026-06-19 01:01:38', '2026-06-19 01:01:38'),
(437, 12, 'Asma Javid', 'nurse', 'deleted', 'Patient', 'Deleted Patient #65', 'App\\Models\\Patient', 65, '{\"id\":65,\"mrn\":\"MRN-00033\",\"name\":\"Kiel DuBuque\",\"father_name\":\"Tony97\",\"date_of_birth\":\"2026-01-10\",\"gender\":\"Female\",\"blood_group\":\"O+\",\"phone\":\"384-241-9962\",\"emergency_contact\":\"Qatar\",\"emergency_relation\":\"Decretum quibusdam conscendo.\",\"cnic\":\"1222111111111\",\"address\":\"43271 E 6 Avenue\",\"city\":\"Pearland\",\"patient_type\":\"OPD\",\"status\":\"Active\",\"doctor_id\":7,\"notes\":\"610\",\"created_at\":\"2026-06-15 09:09:43\",\"updated_at\":\"2026-06-19 06:08:53\",\"deleted_at\":\"2026-06-19 06:08:53\"}', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/patients/65', 'DELETE', 'high', '2026-06-19 01:08:53', '2026-06-19 01:08:53'),
(438, 12, 'Asma Javid', 'nurse', 'created', 'PatientVitals', 'Created PatientVitals #1', 'App\\Models\\PatientVital', 1, NULL, '{\"temperature\":null,\"pulse_rate\":null,\"respiratory_rate\":null,\"systolic_bp\":null,\"diastolic_bp\":null,\"oxygen_saturation\":null,\"blood_glucose\":null,\"weight\":null,\"height\":null,\"pain_score\":null,\"gcs_eye\":null,\"gcs_verbal\":null,\"gcs_motor\":null,\"shift\":\"Morning\",\"notes\":null,\"patient_id\":44,\"recorded_by\":12,\"recorded_at\":\"2026-06-19 06:59:39\",\"bed_id\":21,\"updated_at\":\"2026-06-19 06:59:39\",\"created_at\":\"2026-06-19 06:59:39\",\"id\":1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/ward/patients/44/vitals', 'POST', 'medium', '2026-06-19 01:59:39', '2026-06-19 01:59:39'),
(439, 12, 'Asma Javid', 'nurse', 'created', 'NursingNotes', 'Created NursingNotes #1', 'App\\Models\\PatientNursingNote', 1, NULL, '{\"shift\":\"Morning\",\"note_type\":\"Patient Complaint\",\"note\":\"Quis est in labore r\",\"interventions\":\"Mollitia pariatur E\",\"patient_response\":\"Consectetur incididu\",\"patient_id\":44,\"nurse_id\":12,\"noted_at\":\"2026-06-19 07:37:58\",\"requires_doctor_attention\":false,\"is_urgent\":false,\"bed_id\":21,\"updated_at\":\"2026-06-19 07:37:58\",\"created_at\":\"2026-06-19 07:37:58\",\"id\":1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/ward/patients/44/nursing-notes', 'POST', 'medium', '2026-06-19 02:37:58', '2026-06-19 02:37:58'),
(440, 12, 'Asma Javid', 'nurse', 'created', 'PatientVitals', 'Created PatientVitals #2', 'App\\Models\\PatientVital', 2, NULL, '{\"temperature\":\"98.6\",\"pulse_rate\":\"50\",\"respiratory_rate\":\"5\",\"systolic_bp\":\"120\",\"diastolic_bp\":\"80\",\"oxygen_saturation\":\"98\",\"blood_glucose\":\"120\",\"weight\":\"200\",\"height\":\"180\",\"pain_score\":\"3\",\"gcs_eye\":\"4\",\"gcs_verbal\":\"2\",\"gcs_motor\":\"3\",\"shift\":\"Morning\",\"notes\":\"this is just for the testing purpose not a proper data format is this\",\"gcs_score\":9,\"patient_id\":44,\"recorded_by\":12,\"recorded_at\":\"2026-06-19 07:39:43\",\"bed_id\":21,\"bmi\":61.7,\"updated_at\":\"2026-06-19 07:39:43\",\"created_at\":\"2026-06-19 07:39:43\",\"id\":2}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/ward/patients/44/vitals', 'POST', 'medium', '2026-06-19 02:39:43', '2026-06-19 02:39:43'),
(441, 12, 'Asma Javid', 'nurse', 'created', 'NursingNotes', 'Created NursingNotes #2', 'App\\Models\\PatientNursingNote', 2, NULL, '{\"shift\":\"Night\",\"note_type\":\"Medication Given\",\"note\":\"Non et aut quia eaqu\",\"interventions\":\"Est natus incidunt\",\"patient_response\":\"Provident ut volupt\",\"requires_doctor_attention\":true,\"is_urgent\":true,\"patient_id\":44,\"nurse_id\":12,\"noted_at\":\"2026-06-19 07:50:14\",\"bed_id\":21,\"updated_at\":\"2026-06-19 07:50:14\",\"created_at\":\"2026-06-19 07:50:14\",\"id\":2}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/ward/patients/44/nursing-notes', 'POST', 'medium', '2026-06-19 02:50:14', '2026-06-19 02:50:14'),
(442, 12, 'Asma Javid', 'nurse', 'created', 'PatientVitals', 'Created PatientVitals #3', 'App\\Models\\PatientVital', 3, NULL, '{\"temperature\":\"99\",\"pulse_rate\":\"77\",\"respiratory_rate\":\"14\",\"systolic_bp\":\"120\",\"diastolic_bp\":\"70\",\"oxygen_saturation\":\"97\",\"blood_glucose\":\"110\",\"weight\":\"89\",\"height\":\"170\",\"pain_score\":\"3\",\"gcs_eye\":\"2\",\"gcs_verbal\":\"2\",\"gcs_motor\":\"2\",\"shift\":\"Morning\",\"notes\":null,\"gcs_score\":6,\"patient_id\":44,\"recorded_by\":12,\"recorded_at\":\"2026-06-19 07:55:08\",\"bed_id\":21,\"bmi\":30.8,\"updated_at\":\"2026-06-19 07:55:08\",\"created_at\":\"2026-06-19 07:55:08\",\"id\":3}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/ward/patients/44/vitals', 'POST', 'medium', '2026-06-19 02:55:08', '2026-06-19 02:55:08'),
(443, 12, 'Asma Javid', 'nurse', 'created', 'PatientVitals', 'Created PatientVitals #4', 'App\\Models\\PatientVital', 4, NULL, '{\"temperature\":\"99\",\"pulse_rate\":\"77\",\"respiratory_rate\":\"14\",\"systolic_bp\":\"120\",\"diastolic_bp\":\"70\",\"oxygen_saturation\":\"97\",\"blood_glucose\":\"110\",\"weight\":\"89\",\"height\":\"170\",\"pain_score\":\"3\",\"gcs_eye\":\"2\",\"gcs_verbal\":\"2\",\"gcs_motor\":\"2\",\"shift\":\"Morning\",\"notes\":null,\"gcs_score\":6,\"patient_id\":44,\"recorded_by\":12,\"recorded_at\":\"2026-06-19 07:55:09\",\"bed_id\":21,\"bmi\":30.8,\"updated_at\":\"2026-06-19 07:55:09\",\"created_at\":\"2026-06-19 07:55:09\",\"id\":4}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/ward/patients/44/vitals', 'POST', 'medium', '2026-06-19 02:55:09', '2026-06-19 02:55:09'),
(444, 7, 'samin Afridi', 'doctor', 'created', 'DoctorVisits', 'Created DoctorVisits #1', 'App\\Models\\PatientVisitNote', 1, NULL, '{\"visit_type\":\"Morning Round\",\"subjective\":\"nothing he says\",\"objective\":\"crtitcal stone in there belly\",\"assessment\":\"procedure operation will be perform\",\"plan\":\"operation and respiror\",\"patient_id\":44,\"doctor_id\":9,\"is_discharge_ready\":false,\"bed_id\":21,\"visited_at\":\"2026-06-19 07:58:16\",\"updated_at\":\"2026-06-19 07:58:16\",\"created_at\":\"2026-06-19 07:58:16\",\"id\":1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'http://127.0.0.1:8000/ward/patients/44/visits', 'POST', 'medium', '2026-06-19 02:58:16', '2026-06-19 02:58:16'),
(445, 7, 'samin Afridi', 'doctor', 'created', 'DoctorOrders', 'Created DoctorOrders #2', 'App\\Models\\PatientDoctorOrder', 2, NULL, '{\"order_type\":\"Medication\",\"title\":\"paracetamol q\",\"details\":\"3 times in a day\",\"special_instructions\":\"there is not special instruction\",\"priority\":\"Urgent\",\"patient_id\":44,\"doctor_id\":9,\"status\":\"Pending\",\"bed_id\":21,\"order_number\":\"ORD-00002\",\"ordered_at\":\"2026-06-19 07:59:29\",\"updated_at\":\"2026-06-19 07:59:29\",\"created_at\":\"2026-06-19 07:59:29\",\"id\":2}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'http://127.0.0.1:8000/ward/patients/44/orders', 'POST', 'medium', '2026-06-19 02:59:29', '2026-06-19 02:59:29'),
(446, 12, 'Asma Javid', 'nurse', 'updated', 'DoctorOrders', 'Updated DoctorOrders #2 — changed: acknowledged_by, status, acknowledged_at, updated_at', 'App\\Models\\PatientDoctorOrder', 2, '{\"acknowledged_by\":null,\"status\":\"Pending\",\"acknowledged_at\":null,\"updated_at\":\"2026-06-19T07:59:29.000000Z\"}', '{\"acknowledged_by\":12,\"status\":\"Acknowledged\",\"acknowledged_at\":\"2026-06-19 08:01:11\",\"updated_at\":\"2026-06-19 08:01:11\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/ward/orders/2/acknowledge', 'POST', 'medium', '2026-06-19 03:01:11', '2026-06-19 03:01:11'),
(447, 11, 'Kamran Akmal', 'super_admin', 'login', 'Auth', 'User \'kamran\' logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'medium', '2026-06-19 04:27:06', '2026-06-19 04:27:06'),
(448, 12, 'Asma Javid', 'nurse', 'updated', 'DoctorOrders', 'Updated DoctorOrders #2 — changed: status, completed_at, updated_at', 'App\\Models\\PatientDoctorOrder', 2, '{\"status\":\"Acknowledged\",\"completed_at\":null,\"updated_at\":\"2026-06-19T08:01:11.000000Z\"}', '{\"status\":\"Completed\",\"completed_at\":\"2026-06-19 09:44:20\",\"updated_at\":\"2026-06-19 09:44:20\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/ward/orders/2/complete', 'POST', 'medium', '2026-06-19 04:44:20', '2026-06-19 04:44:20'),
(449, 11, 'Kamran Akmal', 'super_admin', 'deleted', 'Patient', 'Deleted Patient #64', 'App\\Models\\Patient', 64, '{\"id\":64,\"mrn\":\"MRN-00032\",\"name\":\"salman\",\"father_name\":\"salman gull\",\"date_of_birth\":\"2000-02-09\",\"gender\":\"Male\",\"blood_group\":\"A-\",\"phone\":\"03334504157\",\"emergency_contact\":\"03334504157\",\"emergency_relation\":\"Father\",\"cnic\":\"1231233212343\",\"address\":\"dora school suffaid dheri khatama nabowat chowk peshawar\",\"city\":\"Peshawar\",\"patient_type\":\"IPD\",\"status\":\"Active\",\"doctor_id\":2,\"notes\":\"No History\",\"created_at\":\"2026-06-02 02:24:36\",\"updated_at\":\"2026-06-19 10:04:30\",\"deleted_at\":\"2026-06-19 10:04:30\"}', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/patients/64', 'DELETE', 'high', '2026-06-19 05:04:30', '2026-06-19 05:04:30');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time DEFAULT NULL,
  `duration_minutes` smallint(5) UNSIGNED NOT NULL DEFAULT 15,
  `token_number` smallint(5) UNSIGNED DEFAULT NULL,
  `type` enum('OPD','IPD','Follow-up','Emergency') NOT NULL DEFAULT 'OPD',
  `status` enum('Scheduled','Confirmed','In-Progress','Completed','Cancelled','No-show') NOT NULL DEFAULT 'Scheduled',
  `reason` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `consulted_at` timestamp NULL DEFAULT NULL,
  `follow_up_date` date DEFAULT NULL,
  `cancelled_by` enum('Patient','Doctor','Admin') DEFAULT NULL,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `duration_minutes`, `token_number`, `type`, `status`, `reason`, `notes`, `consulted_at`, `follow_up_date`, `cancelled_by`, `cancellation_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, '2026-05-12', '11:00:00', 15, NULL, 'IPD', 'Completed', 'soure throat', 'The Patients have some sore throat problem which he will describe well', NULL, '2026-05-14', NULL, NULL, '2026-05-10 19:59:50', '2026-05-10 21:19:06', NULL),
(2, 1, 4, '2026-05-11', '17:17:00', 15, 1, 'OPD', 'Completed', 'kidney problem', NULL, NULL, NULL, NULL, NULL, '2026-05-10 23:18:03', '2026-05-10 23:18:10', NULL),
(3, 1, 1, '2026-05-12', '13:53:00', 15, 1, 'OPD', 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-11 21:53:57', '2026-05-11 21:54:02', NULL),
(4, 33, 1, '2026-05-18', '14:59:00', 15, 1, 'OPD', 'Scheduled', 'Joined another company for professional growth', NULL, NULL, NULL, NULL, NULL, '2026-05-17 21:59:18', '2026-05-17 23:09:35', '2026-05-17 23:09:35'),
(5, 33, 1, '2026-05-18', '16:09:00', 15, 1, 'OPD', 'Scheduled', 'Joined another company for professional growth', NULL, NULL, NULL, NULL, NULL, '2026-05-17 23:10:03', '2026-05-17 23:10:03', NULL),
(6, 51, 2, '2026-05-19', '19:32:00', 15, 1, 'OPD', 'Scheduled', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-17 23:32:33', '2026-05-17 23:32:58', NULL),
(7, 34, 1, '2026-05-18', '20:57:00', 15, 2, 'OPD', 'Scheduled', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-18 00:57:58', '2026-05-18 00:57:58', NULL),
(8, 40, 3, '2026-05-19', '17:01:00', 15, NULL, 'Emergency', 'Completed', 'kidney problem', NULL, NULL, NULL, NULL, NULL, '2026-05-18 21:02:10', '2026-05-18 21:16:55', NULL),
(9, 44, 1, '2026-05-22', '13:00:00', 15, NULL, 'IPD', 'Completed', 'Sever Pain in the abdomin  kindney pain', 'The patient says it have pain in the kidney from last two days also pain arise in the abdomin from the last night', '2026-05-22 05:04:34', '2026-05-29', NULL, NULL, '2026-05-22 05:04:05', '2026-05-22 05:04:42', NULL),
(10, 35, 1, '2026-06-02', '20:37:00', 15, NULL, 'IPD', 'Scheduled', 'burning sensation in head', 'the patient have burning sensation in the head area need immeadate action', NULL, NULL, NULL, NULL, '2026-06-01 01:38:46', '2026-06-01 20:35:18', '2026-06-01 20:35:18'),
(11, 63, 2, '2026-06-02', '17:50:00', 30, NULL, 'IPD', 'Scheduled', 'Cought and fiver', 'patient have physco history', NULL, NULL, NULL, NULL, '2026-06-01 20:47:42', '2026-06-01 21:57:39', '2026-06-01 21:57:39'),
(12, 64, 2, '2026-06-02', '21:30:00', 15, 1, 'OPD', 'Completed', 'soure throat', NULL, NULL, '2026-06-03', NULL, NULL, '2026-06-01 21:25:27', '2026-06-04 01:35:53', NULL),
(13, 63, 2, '2026-06-02', '18:00:00', 15, NULL, 'IPD', 'Scheduled', 'kidney problem', NULL, NULL, '2026-06-06', NULL, NULL, '2026-06-01 21:58:15', '2026-06-01 21:58:15', NULL),
(14, 35, 9, '2026-06-04', '15:00:00', 15, 1, 'OPD', 'Scheduled', 'kidney problem', 'The patient have sever pain in the kidney', NULL, '2026-06-25', NULL, NULL, '2026-06-03 20:07:44', '2026-06-03 20:07:44', NULL),
(15, 63, 9, '2026-06-04', '17:40:00', 30, 2, 'OPD', 'Scheduled', 'soure throat', 'NO History', NULL, '2026-06-25', NULL, NULL, '2026-06-03 21:35:29', '2026-06-03 21:35:29', NULL),
(16, 63, 1, '2026-06-08', NULL, 15, NULL, 'IPD', 'Completed', 'I worked there on a contract basis until the completion of my contract.', NULL, NULL, NULL, NULL, NULL, '2026-06-07 23:20:54', '2026-06-12 01:23:45', NULL),
(17, 38, 9, '2026-06-08', NULL, 15, NULL, 'IPD', 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-07 23:22:23', '2026-06-12 01:22:21', NULL),
(18, 38, 9, '2026-06-11', '22:18:00', 15, NULL, 'IPD', 'Completed', 'kidney problem', 'The patient have a servier kidney pain', NULL, NULL, NULL, NULL, '2026-06-11 11:17:56', '2026-06-12 01:22:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `working_minutes` int(11) NOT NULL DEFAULT 0,
  `overtime_minutes` int(11) NOT NULL DEFAULT 0,
  `late_minutes` int(11) NOT NULL DEFAULT 0,
  `status` enum('Present','Absent','Late','Half Day','On Leave','Holiday','Weekend','Work From Home') NOT NULL DEFAULT 'Present',
  `source` enum('Manual','Biometric','System') NOT NULL DEFAULT 'Manual',
  `notes` text DEFAULT NULL,
  `is_regularized` tinyint(1) NOT NULL DEFAULT 0,
  `regularized_by` bigint(20) UNSIGNED DEFAULT NULL,
  `regularization_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `employee_id`, `date`, `check_in`, `check_out`, `working_minutes`, `overtime_minutes`, `late_minutes`, `status`, `source`, `notes`, `is_regularized`, `regularized_by`, `regularization_reason`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-06-09', '08:15:00', NULL, 0, 0, 0, 'Present', 'Manual', 'Urgent', 0, NULL, NULL, '2026-06-09 00:15:53', '2026-06-09 00:15:53'),
(2, 2, '2026-06-10', NULL, NULL, 0, 0, 0, 'Present', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:38:22', '2026-06-09 20:38:22'),
(3, 10, '2026-06-10', NULL, NULL, 0, 0, 0, 'Present', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:38:40', '2026-06-09 20:38:40'),
(4, 4, '2026-06-10', NULL, NULL, 0, 0, 0, 'Present', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:38:46', '2026-06-09 20:38:46'),
(5, 8, '2026-06-10', NULL, NULL, 0, 0, 0, 'Present', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:39:04', '2026-06-09 20:39:04'),
(6, 21, '2026-06-10', NULL, NULL, 0, 0, 0, 'Present', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:39:10', '2026-06-09 20:39:10'),
(7, 16, '2026-06-10', NULL, NULL, 0, 0, 0, 'Present', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:39:16', '2026-06-09 20:39:16'),
(8, 13, '2026-06-10', NULL, NULL, 0, 0, 0, 'Present', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:39:22', '2026-06-09 20:39:22'),
(9, 9, '2026-06-10', NULL, NULL, 0, 0, 0, 'Present', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:39:28', '2026-06-09 20:39:28'),
(10, 7, '2026-06-10', NULL, NULL, 0, 0, 0, 'Present', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:39:35', '2026-06-09 20:39:35'),
(11, 20, '2026-06-10', NULL, NULL, 0, 0, 0, 'Present', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:39:41', '2026-06-09 20:39:41'),
(12, 14, '2026-06-10', NULL, NULL, 0, 0, 0, 'Present', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:39:50', '2026-06-09 20:39:50'),
(13, 17, '2026-06-10', NULL, NULL, 0, 0, 0, 'Present', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:39:57', '2026-06-09 20:39:57'),
(14, 19, '2026-06-10', NULL, NULL, 0, 0, 0, 'Present', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:40:04', '2026-06-09 20:40:04'),
(15, 5, '2026-06-10', NULL, NULL, 0, 0, 0, 'Absent', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:40:30', '2026-06-09 20:40:30'),
(16, 6, '2026-06-10', NULL, NULL, 0, 0, 0, 'On Leave', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:40:40', '2026-06-09 20:40:40'),
(17, 11, '2026-06-10', NULL, NULL, 0, 0, 0, 'Late', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:40:49', '2026-06-09 20:40:49'),
(18, 18, '2026-06-10', NULL, NULL, 0, 0, 0, 'Half Day', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:40:59', '2026-06-09 20:40:59'),
(19, 3, '2026-06-10', NULL, NULL, 0, 0, 0, 'Holiday', 'Manual', NULL, 0, NULL, NULL, '2026-06-09 20:41:11', '2026-06-09 20:41:11'),
(20, 2, '2026-06-11', NULL, NULL, 0, 0, 0, 'Absent', 'Manual', NULL, 0, NULL, NULL, '2026-06-11 06:42:11', '2026-06-11 06:42:11'),
(21, 4, '2026-06-11', NULL, NULL, 0, 0, 0, 'Absent', 'Manual', NULL, 0, NULL, NULL, '2026-06-11 06:42:51', '2026-06-11 06:42:51');

-- --------------------------------------------------------

--
-- Table structure for table `beds`
--

CREATE TABLE `beds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bed_number` varchar(255) NOT NULL,
  `ward_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('Standard','Semi-Private','Private','ICU') NOT NULL DEFAULT 'Standard',
  `status` enum('Available','Occupied','Reserved','Maintenance') NOT NULL DEFAULT 'Available',
  `patient_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admitted_at` date DEFAULT NULL,
  `discharged_at` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `beds`
--

INSERT INTO `beds` (`id`, `bed_number`, `ward_id`, `type`, `status`, `patient_id`, `admitted_at`, `discharged_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'W-001-01', 1, 'Standard', 'Available', NULL, '2026-05-13', '2026-06-17', NULL, '2026-05-10 20:00:43', '2026-06-17 06:38:28'),
(2, 'W-001-02', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(3, 'W-001-03', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(4, 'W-001-04', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(5, 'W-001-05', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(6, 'W-001-06', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(7, 'W-001-07', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(8, 'W-001-08', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(9, 'W-001-09', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(10, 'W-001-10', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(11, 'W-001-11', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(12, 'W-001-12', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(13, 'W-001-13', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(14, 'W-001-14', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(15, 'W-001-15', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(16, 'W-001-16', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(17, 'W-001-17', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(18, 'W-001-18', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(19, 'W-001-19', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(20, 'W-001-20', 1, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(21, 'W-002-01', 2, 'ICU', 'Occupied', 44, '2026-05-22', NULL, NULL, '2026-05-17 23:28:01', '2026-05-22 05:06:39'),
(22, 'W-002-02', 2, 'ICU', 'Available', NULL, '2026-05-19', '2026-06-16', NULL, '2026-05-17 23:28:01', '2026-06-16 06:03:16'),
(23, 'W-002-03', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(24, 'W-002-04', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(25, 'W-002-05', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(26, 'W-002-06', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(27, 'W-002-07', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(28, 'W-002-08', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(29, 'W-002-09', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(30, 'W-002-10', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(31, 'W-002-11', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(32, 'W-002-12', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(33, 'W-002-13', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(34, 'W-002-14', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(35, 'W-002-15', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(36, 'W-002-16', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(37, 'W-002-17', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(38, 'W-002-18', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(39, 'W-002-19', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(40, 'W-002-20', 2, 'ICU', 'Available', NULL, NULL, NULL, NULL, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(394, 'W-003-01', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(395, 'W-003-02', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(396, 'W-003-03', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(397, 'W-003-04', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(398, 'W-003-05', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(399, 'W-003-06', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(400, 'W-003-07', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(401, 'W-003-08', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(402, 'W-003-09', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(403, 'W-003-10', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(404, 'W-003-11', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(405, 'W-003-12', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(406, 'W-003-13', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(407, 'W-003-14', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(408, 'W-003-15', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(409, 'W-003-16', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(410, 'W-003-17', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(411, 'W-003-18', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(412, 'W-003-19', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09'),
(413, 'W-003-20', 4, 'Standard', 'Available', NULL, NULL, NULL, NULL, '2026-06-17 05:17:09', '2026-06-17 05:17:09');

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bill_number` varchar(255) NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `discount_by` bigint(20) UNSIGNED DEFAULT NULL,
  `bill_date` date NOT NULL,
  `bill_type` enum('OPD','IPD','Emergency') NOT NULL DEFAULT 'OPD',
  `status` enum('Draft','Finalized','Cancelled') NOT NULL DEFAULT 'Draft',
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_reason` varchar(255) DEFAULT NULL,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('Unpaid','Partial','Paid') NOT NULL DEFAULT 'Unpaid',
  `notes` text DEFAULT NULL,
  `finalized_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `bill_number`, `patient_id`, `created_by`, `discount_by`, `bill_date`, `bill_type`, `status`, `subtotal`, `discount_amount`, `discount_reason`, `tax_amount`, `net_amount`, `paid_amount`, `due_amount`, `payment_status`, `notes`, `finalized_at`, `cancelled_at`, `cancellation_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'BILL-2026-00001', 1, NULL, NULL, '2026-05-11', 'OPD', 'Finalized', 1718.00, 0.00, NULL, 0.00, 1718.00, 1718.00, 0.00, 'Paid', NULL, '2026-05-10 21:20:18', NULL, NULL, '2026-05-10 21:20:06', '2026-05-10 21:20:24', NULL),
(2, 'BILL-2026-00002', 1, NULL, NULL, '2026-05-11', 'OPD', 'Cancelled', 58900.00, 0.00, NULL, 0.00, 58900.00, 0.00, 58900.00, 'Unpaid', NULL, NULL, '2026-05-10 23:27:08', 'this is just for testing', '2026-05-10 23:25:57', '2026-05-10 23:27:08', NULL),
(3, 'BILL-2026-00003', 1, NULL, NULL, '2026-05-12', 'OPD', 'Finalized', 256.00, 0.00, NULL, 0.00, 256.00, 256.00, 0.00, 'Paid', NULL, '2026-05-17 23:57:01', NULL, NULL, '2026-05-11 20:38:45', '2026-05-17 23:57:29', NULL),
(4, 'BILL-2026-00004', 1, NULL, NULL, '2026-05-12', 'OPD', 'Finalized', 10056.00, 0.00, NULL, 0.00, 10056.00, 10056.00, 0.00, 'Paid', NULL, '2026-05-18 00:03:36', NULL, NULL, '2026-05-11 20:41:42', '2026-05-18 00:03:41', NULL),
(5, 'BILL-2026-00005', 1, NULL, NULL, '2026-05-12', 'OPD', 'Finalized', 258.00, 0.00, NULL, 0.00, 258.00, 258.00, 0.00, 'Paid', NULL, '2026-05-11 20:59:48', NULL, NULL, '2026-05-11 20:58:21', '2026-05-11 20:59:57', NULL),
(6, 'BILL-2026-00006', 1, NULL, NULL, '2026-05-12', 'OPD', 'Finalized', 258.00, 0.00, NULL, 0.00, 258.00, 258.00, 0.00, 'Paid', NULL, '2026-05-17 23:54:17', NULL, NULL, '2026-05-11 21:00:18', '2026-05-17 23:54:22', NULL),
(7, 'BILL-2026-00007', 1, NULL, NULL, '2026-05-12', 'OPD', 'Finalized', 260.00, 500.00, NULL, 0.00, 0.00, 0.00, 0.00, 'Unpaid', NULL, '2026-05-17 23:53:16', NULL, NULL, '2026-05-11 21:15:49', '2026-05-17 23:53:16', NULL),
(8, 'BILL-2026-00008', 1, NULL, NULL, '2026-05-12', 'OPD', 'Finalized', 260.00, 100.00, NULL, 0.00, 160.00, 160.00, 0.00, 'Paid', NULL, '2026-05-17 23:52:39', NULL, NULL, '2026-05-11 21:16:14', '2026-05-17 23:52:44', NULL),
(9, 'BILL-2026-00009', 2, NULL, NULL, '2026-05-12', 'OPD', 'Finalized', 3500.00, 0.00, NULL, 0.00, 3500.00, 3500.00, 0.00, 'Paid', NULL, '2026-05-11 23:38:45', NULL, NULL, '2026-05-11 23:38:24', '2026-05-11 23:38:50', NULL),
(10, 'BILL-2026-00010', 1, NULL, NULL, '2026-05-12', 'OPD', 'Finalized', 1500.00, 0.00, NULL, 0.00, 1500.00, 1500.00, 0.00, 'Paid', NULL, '2026-05-11 23:45:39', NULL, NULL, '2026-05-11 23:45:36', '2026-05-11 23:46:00', NULL),
(11, 'BILL-2026-00011', 2, NULL, NULL, '2026-05-12', 'OPD', 'Finalized', 3500.00, 0.00, NULL, 0.00, 3500.00, 3500.00, 0.00, 'Paid', NULL, '2026-05-12 00:52:03', NULL, NULL, '2026-05-12 00:51:54', '2026-05-12 00:52:06', NULL),
(12, 'BILL-2026-00012', 2, NULL, NULL, '2026-05-12', 'OPD', 'Finalized', 2000.00, 0.00, NULL, 0.00, 2000.00, 2000.00, 0.00, 'Paid', NULL, '2026-05-12 07:05:29', NULL, NULL, '2026-05-12 07:05:23', '2026-05-12 07:05:35', NULL),
(13, 'BILL-2026-00013', 2, NULL, NULL, '2026-05-13', 'OPD', 'Cancelled', 80.00, 0.00, NULL, 0.00, 80.00, 0.00, 80.00, 'Unpaid', NULL, NULL, '2026-05-12 23:34:23', 'it was just a mistake', '2026-05-12 23:34:02', '2026-05-12 23:34:23', NULL),
(14, 'BILL-2026-00014', 56, NULL, NULL, '2026-05-18', 'OPD', 'Cancelled', 700.00, 0.00, NULL, 0.00, 700.00, 0.00, 700.00, 'Unpaid', NULL, NULL, '2026-05-17 20:47:20', 'f', '2026-05-17 20:44:04', '2026-05-17 20:47:20', NULL),
(15, 'BILL-2026-00015', 36, NULL, NULL, '2026-05-18', 'OPD', 'Finalized', 1000.00, 0.00, NULL, 0.00, 1000.00, 1000.00, 0.00, 'Paid', NULL, '2026-05-17 21:43:56', NULL, NULL, '2026-05-17 21:43:33', '2026-05-17 21:44:12', NULL),
(16, 'BILL-2026-00016', 42, NULL, NULL, '2026-05-18', 'OPD', 'Finalized', 2312.00, 0.00, NULL, 0.00, 2312.00, 2312.00, 0.00, 'Paid', NULL, '2026-05-18 00:53:08', NULL, NULL, '2026-05-18 00:53:04', '2026-05-18 00:53:12', NULL),
(17, 'BILL-2026-00017', 34, NULL, NULL, '2026-05-18', 'OPD', 'Finalized', 5790.00, 790.00, NULL, 1500.00, 6500.00, 6500.00, 0.00, 'Paid', NULL, '2026-05-18 01:03:33', NULL, NULL, '2026-05-18 01:03:27', '2026-05-18 01:03:36', NULL),
(18, 'BILL-2026-00018', 1, NULL, NULL, '2026-05-19', 'OPD', 'Draft', 2350.00, 0.00, NULL, 0.00, 2350.00, 0.00, 2350.00, 'Unpaid', NULL, NULL, NULL, NULL, '2026-05-19 00:34:56', '2026-05-19 00:34:56', NULL),
(19, 'BILL-2026-00019', 1, NULL, NULL, '2026-05-19', 'OPD', 'Finalized', 9550.00, 0.00, NULL, 0.00, 9550.00, 9550.00, 0.00, 'Paid', NULL, '2026-05-21 20:00:58', NULL, NULL, '2026-05-19 01:14:10', '2026-05-21 20:01:02', NULL),
(20, 'BILL-2026-00020', 35, NULL, NULL, '2026-05-22', 'OPD', 'Finalized', 20000.00, 0.00, NULL, 0.00, 20000.00, 20000.00, 0.00, 'Paid', NULL, '2026-05-21 20:00:22', NULL, NULL, '2026-05-21 19:59:45', '2026-05-21 20:00:26', NULL),
(21, 'BILL-2026-00021', 37, NULL, NULL, '2026-05-22', 'OPD', 'Finalized', 37000.00, 2000.00, NULL, 0.00, 35000.00, 35000.00, 0.00, 'Paid', NULL, '2026-05-21 20:01:56', NULL, NULL, '2026-05-21 20:01:52', '2026-05-21 20:02:00', NULL),
(22, 'BILL-2026-00022', 35, 3, 3, '2026-06-05', 'OPD', 'Finalized', 16410.00, 410.00, NULL, 0.00, 16000.00, 16000.00, 0.00, 'Paid', NULL, '2026-06-05 00:54:42', NULL, NULL, '2026-06-05 00:53:32', '2026-06-05 00:54:47', NULL),
(23, 'BILL-2026-00023', 63, 3, NULL, '2026-06-05', 'OPD', 'Finalized', 70100.00, 0.00, NULL, 0.00, 70100.00, 70100.00, 0.00, 'Paid', NULL, '2026-06-11 11:38:47', NULL, NULL, '2026-06-05 01:10:16', '2026-06-11 11:38:52', NULL),
(24, 'BILL-2026-00024', 63, 11, NULL, '2026-06-11', 'OPD', 'Finalized', 5100.00, 0.00, NULL, 0.00, 5100.00, 5100.00, 0.00, 'Paid', NULL, '2026-06-11 11:38:14', NULL, NULL, '2026-06-11 11:38:09', '2026-06-11 11:38:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bill_items`
--

CREATE TABLE `bill_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bill_id` bigint(20) UNSIGNED NOT NULL,
  `service_type` enum('Consultation','Lab','Radiology','Pharmacy','Bed Charges','OT Charges','Blood Bank','Death Certificate Fee','Mortuary storage','Service','Other') NOT NULL,
  `description` varchar(255) NOT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(8,2) NOT NULL DEFAULT 1.00,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bill_items`
--

INSERT INTO `bill_items` (`id`, `bill_id`, `service_type`, `description`, `reference_type`, `reference_id`, `quantity`, `unit_price`, `discount`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 'Consultation', 'Consultation Fee: Dr. Ahmed', 'appointments', 1, 1.00, 1500.00, 0.00, 1500.00, '2026-05-10 21:20:06', '2026-05-10 21:20:06'),
(2, 1, 'Bed Charges', 'Bed Charges: Medical A (Bed: W-001-01) - 0.30512215835648 Days', 'beds', 1, 1.00, 200.00, 0.00, 200.00, '2026-05-10 21:20:06', '2026-05-10 21:20:06'),
(3, 1, 'Pharmacy', 'Pharmacy Dispensing #DSP-00001', 'dispensings', 1, 1.00, 18.00, 0.00, 18.00, '2026-05-10 21:20:06', '2026-05-10 21:20:06'),
(4, 2, 'Consultation', 'Consultation Fee: Dr. Zoya', 'appointments', 2, 1.00, 500.00, 0.00, 500.00, '2026-05-10 23:25:57', '2026-05-10 23:25:57'),
(5, 2, 'Bed Charges', 'Bed Charges: Medical A (Bed: W-001-01) - 0.39252242136574 Days', 'beds', 1, 2.00, 200.00, 0.00, 400.00, '2026-05-10 23:25:57', '2026-05-10 23:25:57'),
(6, 2, 'Consultation', 'Emergency Consultation', NULL, NULL, 1.00, 1500.00, 0.00, 1500.00, '2026-05-10 23:25:57', '2026-05-10 23:25:57'),
(7, 2, 'Service', 'ECG', NULL, NULL, 1.00, 1200.00, 0.00, 1200.00, '2026-05-10 23:25:57', '2026-05-10 23:25:57'),
(8, 2, 'Service', 'Nebulization', NULL, NULL, 1.00, 500.00, 0.00, 500.00, '2026-05-10 23:25:57', '2026-05-10 23:25:57'),
(9, 2, 'Service', 'Stitch Removal', NULL, NULL, 1.00, 1000.00, 0.00, 1000.00, '2026-05-10 23:25:57', '2026-05-10 23:25:57'),
(10, 2, 'Service', 'Wound Dressing (Small)', NULL, NULL, 1.00, 800.00, 0.00, 800.00, '2026-05-10 23:25:57', '2026-05-10 23:25:57'),
(11, 2, 'Service', 'CCU Bed Charges', NULL, NULL, 1.00, 12000.00, 0.00, 12000.00, '2026-05-10 23:25:57', '2026-05-10 23:25:57'),
(12, 2, 'Service', 'Private Room (Standard)', NULL, NULL, 1.00, 8000.00, 0.00, 8000.00, '2026-05-10 23:25:57', '2026-05-10 23:25:57'),
(13, 2, 'Service', 'ICU Bed Charges', NULL, NULL, 1.00, 15000.00, 0.00, 15000.00, '2026-05-10 23:25:57', '2026-05-10 23:25:57'),
(14, 2, 'Service', 'OT Charges (Minor)', NULL, NULL, 1.00, 10000.00, 0.00, 10000.00, '2026-05-10 23:25:57', '2026-05-10 23:25:57'),
(15, 2, 'Service', 'Whole Blood Bag', NULL, NULL, 1.00, 3500.00, 0.00, 3500.00, '2026-05-10 23:25:57', '2026-05-10 23:25:57'),
(16, 2, 'Service', 'Ambulance (Within City)', NULL, NULL, 1.00, 3000.00, 0.00, 3000.00, '2026-05-10 23:25:57', '2026-05-10 23:25:57'),
(17, 2, 'Service', 'RMO Service Fee', NULL, NULL, 1.00, 500.00, 0.00, 500.00, '2026-05-10 23:25:57', '2026-05-10 23:25:57'),
(18, 2, 'Service', 'Nursing Care Fee (Daily)', NULL, NULL, 1.00, 1000.00, 0.00, 1000.00, '2026-05-10 23:25:57', '2026-05-10 23:25:57'),
(19, 3, 'Bed Charges', 'Bed Charges: Medical A (Bed: W-001-01) - 1.2768584812384 Days', 'beds', 1, 1.28, 200.00, 0.00, 256.00, '2026-05-11 20:38:45', '2026-05-11 20:38:45'),
(22, 5, 'Bed Charges', 'Bed Charges: Medical A (Bed: W-001-01) - 1.2903848477199 Days', 'beds', 1, 1.29, 200.00, 0.00, 258.00, '2026-05-11 20:58:21', '2026-05-11 20:58:21'),
(23, 6, 'Bed Charges', 'Bed Charges: Medical A (Bed: W-001-01) - 1.2918056121759 Days', 'beds', 1, 1.29, 200.00, 0.00, 258.00, '2026-05-11 21:00:18', '2026-05-11 21:00:18'),
(24, 7, 'Bed Charges', 'Bed Charges: Medical A (Bed: W-001-01) - 1.3025310449306 Days', 'beds', 1, 1.30, 200.00, 0.00, 260.00, '2026-05-11 21:15:49', '2026-05-11 21:15:49'),
(25, 8, 'Bed Charges', 'Bed Charges: Medical A (Bed: W-001-01) - 1.3029277423264 Days', 'beds', 1, 1.30, 200.00, 0.00, 260.00, '2026-05-11 21:16:14', '2026-05-11 21:16:14'),
(26, 9, 'Service', 'Whole Blood Bag', NULL, NULL, 1.00, 3500.00, 0.00, 3500.00, '2026-05-11 23:38:24', '2026-05-11 23:38:24'),
(27, 10, 'Consultation', 'Consultation Fee: Dr. Ahmed', 'appointments', 3, 1.00, 1500.00, 0.00, 1500.00, '2026-05-11 23:45:36', '2026-05-11 23:45:36'),
(28, 11, 'Blood Bank', 'Blood Issue: A+ (Whole Blood)', 'blood_requests', 1, 1.00, 0.00, 0.00, 0.00, '2026-05-12 00:51:54', '2026-05-12 00:51:54'),
(29, 11, 'Consultation', 'General OPD Consultation', NULL, NULL, 1.00, 1000.00, 0.00, 1000.00, '2026-05-12 00:51:54', '2026-05-12 00:51:54'),
(30, 11, 'Consultation', 'Specialist Consultation', NULL, NULL, 1.00, 2500.00, 0.00, 2500.00, '2026-05-12 00:51:54', '2026-05-12 00:51:54'),
(31, 12, 'Blood Bank', 'Blood Issue: A+ (Packed RBC)', 'blood_requests', 4, 1.00, 2000.00, 0.00, 2000.00, '2026-05-12 07:05:23', '2026-05-12 07:05:23'),
(32, 13, 'Bed Charges', 'Bed Charges: Medical A (Bed: W-001-01) - 0.3985478187037 Days', 'beds', 1, 0.40, 200.00, 0.00, 80.00, '2026-05-12 23:34:02', '2026-05-12 23:34:02'),
(33, 14, 'Service', 'Death Certificate Fee', NULL, NULL, 1.00, 500.00, 0.00, 500.00, '2026-05-17 20:44:04', '2026-05-17 20:44:04'),
(34, 14, 'Service', 'Mortuary Storage Charges', NULL, NULL, 1.00, 200.00, 0.00, 200.00, '2026-05-17 20:44:04', '2026-05-17 20:44:04'),
(35, 15, 'Service', 'Death Certificate Fee — DC-2026-00008 (Legal Proceedings)', 'death_certificates', 8, 1.00, 2000.00, 1000.00, 1000.00, '2026-05-17 21:43:33', '2026-05-17 21:43:33'),
(36, 4, 'Bed Charges', 'Bed Charges: Medical A (Bed: W-001-01) - 1.2787646378935 Days', 'beds', 1, 1.28, 200.00, 0.00, 256.00, '2026-05-18 00:03:28', '2026-05-18 00:03:28'),
(37, 4, 'Service', 'Mortuary Storage Charges', NULL, NULL, 1.00, 200.00, 0.00, 200.00, '2026-05-18 00:03:28', '2026-05-18 00:03:28'),
(38, 4, 'Service', 'Death Certificate Fee', NULL, NULL, 1.00, 1000.00, 0.00, 1000.00, '2026-05-18 00:03:28', '2026-05-18 00:03:28'),
(39, 4, 'Service', 'Death Certificate Fee', NULL, NULL, 1.00, 1000.00, 0.00, 1000.00, '2026-05-18 00:03:28', '2026-05-18 00:03:28'),
(40, 4, 'Service', 'Death Certificate Fee', NULL, NULL, 1.00, 1000.00, 0.00, 1000.00, '2026-05-18 00:03:28', '2026-05-18 00:03:28'),
(41, 4, 'Service', 'Death Certificate Fee', NULL, NULL, 1.00, 1000.00, 0.00, 1000.00, '2026-05-18 00:03:28', '2026-05-18 00:03:28'),
(42, 4, 'Service', 'Death Certificate Fee', NULL, NULL, 1.00, 1000.00, 0.00, 1000.00, '2026-05-18 00:03:28', '2026-05-18 00:03:28'),
(43, 4, 'Service', 'Death Certificate Fee', NULL, NULL, 1.00, 1000.00, 0.00, 1000.00, '2026-05-18 00:03:28', '2026-05-18 00:03:28'),
(44, 4, 'Service', 'Death Certificate Fee', NULL, NULL, 1.00, 1000.00, 0.00, 1000.00, '2026-05-18 00:03:28', '2026-05-18 00:03:28'),
(45, 4, 'Service', 'Blood RBC pack', NULL, NULL, 1.00, 2000.00, 0.00, 2000.00, '2026-05-18 00:03:28', '2026-05-18 00:03:28'),
(46, 4, 'Service', 'Mortuary Storage Charges', NULL, NULL, 1.00, 200.00, 0.00, 200.00, '2026-05-18 00:03:28', '2026-05-18 00:03:28'),
(47, 4, 'Service', 'Mortuary Storage Charges', NULL, NULL, 1.00, 200.00, 0.00, 200.00, '2026-05-18 00:03:28', '2026-05-18 00:03:28'),
(48, 4, 'Service', 'Mortuary Storage Charges', NULL, NULL, 1.00, 200.00, 0.00, 200.00, '2026-05-18 00:03:28', '2026-05-18 00:03:28'),
(49, 16, 'Pharmacy', 'Pharmacy Dispensing #DSP-00003', 'dispensings', 3, 1.00, 2112.00, 0.00, 2112.00, '2026-05-18 00:53:04', '2026-05-18 00:53:04'),
(50, 16, 'Service', 'Mortuary Storage — MTY-00005 (1 day(s))', 'mortuary_records', 5, 1.00, 200.00, 0.00, 200.00, '2026-05-18 00:53:04', '2026-05-18 00:53:04'),
(51, 17, 'Pharmacy', 'Pharmacy Dispensing #DSP-00005', 'dispensings', 5, 1.00, 3990.00, 0.00, 3990.00, '2026-05-18 01:03:27', '2026-05-18 01:03:27'),
(52, 17, 'Service', 'Death Certificate Fee — DC-2026-00009 (Burial / Funeral)', 'death_certificates', 9, 1.00, 1800.00, 0.00, 1800.00, '2026-05-18 01:03:27', '2026-05-18 01:03:27'),
(53, 18, 'Lab', 'Lab Order #LAB-00001', 'lab_orders', 1, 1.00, 1500.00, 100.00, 1400.00, '2026-05-19 00:34:56', '2026-05-19 00:34:56'),
(54, 18, 'Pharmacy', 'Pharmacy Dispensing #DSP-00006', 'dispensings', 6, 1.00, 1050.00, 100.00, 950.00, '2026-05-19 00:34:56', '2026-05-19 00:34:56'),
(55, 19, 'Lab', 'Lab Order #LAB-00001', 'lab_orders', 1, 1.00, 1500.00, 0.00, 1500.00, '2026-05-19 01:14:10', '2026-05-19 01:14:10'),
(56, 19, 'Radiology', 'Radiology Order #RAD-00001', 'radiology_orders', 1, 1.00, 7000.00, 0.00, 7000.00, '2026-05-19 01:14:10', '2026-05-19 01:14:10'),
(57, 19, 'Pharmacy', 'Pharmacy Dispensing #DSP-00006', 'dispensings', 6, 1.00, 1050.00, 0.00, 1050.00, '2026-05-19 01:14:10', '2026-05-19 01:14:10'),
(58, 20, 'OT Charges', 'Surgeon Fee: Dr. Sara Ali (SRG-00001)', 'ot_schedules', 1, 1.00, 10000.00, 0.00, 10000.00, '2026-05-21 19:59:45', '2026-05-21 19:59:45'),
(59, 20, 'OT Charges', 'Anesthesia Fee () — SRG-00001', 'ot_schedules', 1, 1.00, 5000.00, 0.00, 5000.00, '2026-05-21 19:59:45', '2026-05-21 19:59:45'),
(60, 20, 'OT Charges', 'OT Room: Main Operation Theater (SRG-00001)', 'ot_schedules', 1, 1.00, 3000.00, 0.00, 3000.00, '2026-05-21 19:59:45', '2026-05-21 19:59:45'),
(61, 20, 'OT Charges', 'OT Consumables — SRG-00001', 'ot_schedules', 1, 1.00, 2000.00, 0.00, 2000.00, '2026-05-21 19:59:45', '2026-05-21 19:59:45'),
(62, 21, 'OT Charges', 'Surgeon Fee: Dr. Sara Ali (SRG-00002)', 'ot_schedules', 2, 1.00, 20000.00, 0.00, 20000.00, '2026-05-21 20:01:52', '2026-05-21 20:01:52'),
(63, 21, 'OT Charges', 'Anesthesia Fee () — SRG-00002', 'ot_schedules', 2, 1.00, 10000.00, 0.00, 10000.00, '2026-05-21 20:01:52', '2026-05-21 20:01:52'),
(64, 21, 'OT Charges', 'OT Room: Main Operation Theater (SRG-00002)', 'ot_schedules', 2, 1.00, 5000.00, 0.00, 5000.00, '2026-05-21 20:01:52', '2026-05-21 20:01:52'),
(65, 21, 'OT Charges', 'OT Consumables — SRG-00002', 'ot_schedules', 2, 1.00, 2000.00, 0.00, 2000.00, '2026-05-21 20:01:52', '2026-05-21 20:01:52'),
(71, 22, 'Lab', 'Lab Order #LAB-00004', 'lab_orders', 4, 1.00, 1500.00, 0.00, 1500.00, '2026-06-05 00:54:31', '2026-06-05 00:54:31'),
(72, 22, 'Lab', 'Lab Order #LAB-00005', 'lab_orders', 5, 1.00, 1500.00, 0.00, 1500.00, '2026-06-05 00:54:31', '2026-06-05 00:54:31'),
(73, 22, 'Radiology', 'Radiology Order #RAD-00004', 'radiology_orders', 4, 1.00, 7000.00, 0.00, 7000.00, '2026-06-05 00:54:31', '2026-06-05 00:54:31'),
(74, 22, 'Pharmacy', 'Pharmacy Dispensing #DSP-00009', 'dispensings', 9, 1.00, 4410.00, 0.00, 4410.00, '2026-06-05 00:54:31', '2026-06-05 00:54:31'),
(75, 22, 'Consultation', 'doctor consulatation fees', NULL, NULL, 1.00, 2000.00, 0.00, 2000.00, '2026-06-05 00:54:31', '2026-06-05 00:54:31'),
(76, 23, 'Lab', 'Lab Order #LAB-00003', 'lab_orders', 3, 1.00, 1500.00, 0.00, 1500.00, '2026-06-05 01:10:16', '2026-06-05 01:10:16'),
(77, 23, 'Lab', 'Lab Order #LAB-00006', 'lab_orders', 6, 1.00, 1500.00, 0.00, 1500.00, '2026-06-05 01:10:16', '2026-06-05 01:10:16'),
(78, 23, 'Pharmacy', 'Pharmacy Dispensing #DSP-00008', 'dispensings', 8, 1.00, 2100.00, 0.00, 2100.00, '2026-06-05 01:10:16', '2026-06-05 01:10:16'),
(79, 23, 'OT Charges', 'Surgeon Fee: Dr. Sara Ali (SRG-00005)', 'ot_schedules', 5, 1.00, 30000.00, 0.00, 30000.00, '2026-06-05 01:10:16', '2026-06-05 01:10:16'),
(80, 23, 'OT Charges', 'Anesthesia Fee (Sedation) — SRG-00005', 'ot_schedules', 5, 1.00, 20000.00, 0.00, 20000.00, '2026-06-05 01:10:16', '2026-06-05 01:10:16'),
(81, 23, 'OT Charges', 'OT Room: Main Operation Theater (SRG-00005)', 'ot_schedules', 5, 1.00, 10000.00, 0.00, 10000.00, '2026-06-05 01:10:16', '2026-06-05 01:10:16'),
(82, 23, 'OT Charges', 'OT Consumables — SRG-00005', 'ot_schedules', 5, 1.00, 5000.00, 0.00, 5000.00, '2026-06-05 01:10:16', '2026-06-05 01:10:16'),
(83, 24, 'Lab', 'Lab Order #LAB-00003', 'lab_orders', 3, 1.00, 1500.00, 0.00, 1500.00, '2026-06-11 11:38:09', '2026-06-11 11:38:09'),
(84, 24, 'Lab', 'Lab Order #LAB-00006', 'lab_orders', 6, 1.00, 1500.00, 0.00, 1500.00, '2026-06-11 11:38:09', '2026-06-11 11:38:09'),
(85, 24, 'Pharmacy', 'Pharmacy Dispensing #DSP-00008', 'dispensings', 8, 1.00, 2100.00, 0.00, 2100.00, '2026-06-11 11:38:09', '2026-06-11 11:38:09');

-- --------------------------------------------------------

--
-- Table structure for table `bill_payments`
--

CREATE TABLE `bill_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_number` varchar(255) NOT NULL,
  `bill_id` bigint(20) UNSIGNED NOT NULL,
  `received_by` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` enum('Cash','Card','Bank Transfer','Cheque','Insurance','Online') NOT NULL DEFAULT 'Cash',
  `payment_date` date NOT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bill_payments`
--

INSERT INTO `bill_payments` (`id`, `payment_number`, `bill_id`, `received_by`, `amount`, `payment_method`, `payment_date`, `reference_number`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'PAY-2026-00001', 1, NULL, 1718.00, 'Cash', '2026-05-11', NULL, NULL, '2026-05-10 21:20:23', '2026-05-10 21:20:23'),
(2, 'PAY-2026-00002', 5, NULL, 258.00, 'Cash', '2026-05-12', NULL, NULL, '2026-05-11 20:59:57', '2026-05-11 20:59:57'),
(3, 'PAY-2026-00003', 9, NULL, 3500.00, 'Cash', '2026-05-12', NULL, NULL, '2026-05-11 23:38:50', '2026-05-11 23:38:50'),
(4, 'PAY-2026-00004', 10, NULL, 500.00, 'Cash', '2026-05-12', NULL, NULL, '2026-05-11 23:45:51', '2026-05-11 23:45:51'),
(5, 'PAY-2026-00005', 10, NULL, 1000.00, 'Cash', '2026-05-12', NULL, NULL, '2026-05-11 23:46:00', '2026-05-11 23:46:00'),
(6, 'PAY-2026-00006', 11, NULL, 3500.00, 'Cash', '2026-05-12', NULL, NULL, '2026-05-12 00:52:06', '2026-05-12 00:52:06'),
(7, 'PAY-2026-00007', 12, NULL, 2000.00, 'Cash', '2026-05-12', NULL, NULL, '2026-05-12 07:05:35', '2026-05-12 07:05:35'),
(8, 'PAY-2026-00008', 15, NULL, 1000.00, 'Cash', '2026-05-18', NULL, NULL, '2026-05-17 21:44:12', '2026-05-17 21:44:12'),
(9, 'PAY-2026-00009', 8, NULL, 160.00, 'Cash', '2026-05-18', NULL, NULL, '2026-05-17 23:52:44', '2026-05-17 23:52:44'),
(10, 'PAY-2026-00010', 6, NULL, 258.00, 'Cash', '2026-05-18', NULL, NULL, '2026-05-17 23:54:22', '2026-05-17 23:54:22'),
(11, 'PAY-2026-00011', 3, NULL, 256.00, 'Cash', '2026-05-18', NULL, NULL, '2026-05-17 23:57:29', '2026-05-17 23:57:29'),
(12, 'PAY-2026-00012', 4, NULL, 10056.00, 'Cash', '2026-05-18', NULL, NULL, '2026-05-18 00:03:41', '2026-05-18 00:03:41'),
(13, 'PAY-2026-00013', 16, NULL, 2312.00, 'Cash', '2026-05-18', NULL, NULL, '2026-05-18 00:53:12', '2026-05-18 00:53:12'),
(14, 'PAY-2026-00014', 17, NULL, 6500.00, 'Cash', '2026-05-18', NULL, NULL, '2026-05-18 01:03:36', '2026-05-18 01:03:36'),
(15, 'PAY-2026-00015', 20, NULL, 20000.00, 'Cash', '2026-05-22', NULL, NULL, '2026-05-21 20:00:26', '2026-05-21 20:00:26'),
(16, 'PAY-2026-00016', 19, NULL, 9550.00, 'Cash', '2026-05-22', NULL, NULL, '2026-05-21 20:01:02', '2026-05-21 20:01:02'),
(17, 'PAY-2026-00017', 21, NULL, 35000.00, 'Cash', '2026-05-22', NULL, NULL, '2026-05-21 20:02:00', '2026-05-21 20:02:00'),
(18, 'PAY-2026-00018', 22, 3, 16000.00, 'Cash', '2026-06-05', NULL, NULL, '2026-06-05 00:54:47', '2026-06-05 00:54:47'),
(19, 'PAY-2026-00019', 24, 11, 5100.00, 'Cash', '2026-06-11', NULL, NULL, '2026-06-11 11:38:20', '2026-06-11 11:38:20'),
(20, 'PAY-2026-00020', 23, 11, 70100.00, 'Cash', '2026-06-11', NULL, NULL, '2026-06-11 11:38:52', '2026-06-11 11:38:52');

-- --------------------------------------------------------

--
-- Table structure for table `bill_service_charges`
--

CREATE TABLE `bill_service_charges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `category` enum('Consultation','Procedure','Bed Charges','OT Charges','Blood Bank','Death Certificate Fee','Mortuary storage','Service','Other') NOT NULL DEFAULT 'Service',
  `blood_component` varchar(255) DEFAULT NULL,
  `default_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bill_service_charges`
--

INSERT INTO `bill_service_charges` (`id`, `name`, `code`, `category`, `blood_component`, `default_price`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(29, 'Blood RBC pack', 'RBC 33', 'Blood Bank', 'Packed RBC', 2000.00, 'THIS IS JUST FOR THE TESTING', 1, '2026-05-12 06:46:21', '2026-05-12 07:04:51'),
(30, 'Death Certificate Fee', 'DC-FEE', 'Service', NULL, 1000.00, 'This is the death certificate charges', 1, '2026-05-17 20:41:01', '2026-05-17 21:36:49'),
(31, 'Mortuary Storage Charges', 'MORT-STORAGE', 'Service', NULL, 200.00, 'For Body Storing', 1, '2026-05-17 20:41:54', '2026-05-17 20:41:54');

-- --------------------------------------------------------

--
-- Table structure for table `blood_crossmatches`
--

CREATE TABLE `blood_crossmatches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `crossmatch_id` varchar(255) NOT NULL,
  `blood_request_id` bigint(20) UNSIGNED NOT NULL,
  `blood_donation_id` bigint(20) UNSIGNED NOT NULL,
  `result` enum('Pending','Compatible','Incompatible') NOT NULL DEFAULT 'Pending',
  `method` enum('Immediate Spin','AHG','Electronic','Saline') NOT NULL DEFAULT 'Immediate Spin',
  `performed_at` timestamp NULL DEFAULT NULL,
  `performed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blood_crossmatches`
--

INSERT INTO `blood_crossmatches` (`id`, `crossmatch_id`, `blood_request_id`, `blood_donation_id`, `result`, `method`, `performed_at`, `performed_by`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'CRM-00001', 1, 1, 'Compatible', 'Electronic', '2026-05-11 23:36:37', 2, NULL, '2026-05-11 23:36:04', '2026-05-11 23:36:37'),
(2, 'CRM-00002', 4, 2, 'Compatible', 'Electronic', '2026-05-12 01:50:03', 2, NULL, '2026-05-12 01:49:57', '2026-05-12 01:50:03'),
(3, 'CRM-00003', 5, 3, 'Compatible', 'Saline', '2026-05-22 07:05:23', 4, 'The blood are successfully issued', '2026-05-22 07:04:47', '2026-05-22 07:05:23');

-- --------------------------------------------------------

--
-- Table structure for table `blood_donations`
--

CREATE TABLE `blood_donations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `donation_id` varchar(255) NOT NULL,
  `donor_id` bigint(20) UNSIGNED NOT NULL,
  `donation_date` date NOT NULL,
  `donation_time` time DEFAULT NULL,
  `blood_group` varchar(255) NOT NULL,
  `volume_ml` decimal(6,1) NOT NULL DEFAULT 450.0,
  `bag_number` varchar(255) DEFAULT NULL,
  `component` enum('Whole Blood','Packed RBC','Platelets','Fresh Frozen Plasma','Cryoprecipitate') NOT NULL DEFAULT 'Whole Blood',
  `screening_status` enum('Pending','Passed','Failed','Discarded') NOT NULL DEFAULT 'Pending',
  `hiv_tested` tinyint(1) NOT NULL DEFAULT 0,
  `hbsag_tested` tinyint(1) NOT NULL DEFAULT 0,
  `hcv_tested` tinyint(1) NOT NULL DEFAULT 0,
  `vdrl_tested` tinyint(1) NOT NULL DEFAULT 0,
  `malaria_tested` tinyint(1) NOT NULL DEFAULT 0,
  `screening_notes` text DEFAULT NULL,
  `status` enum('Available','Reserved','Issued','Expired','Discarded') NOT NULL DEFAULT 'Available',
  `expiry_date` date NOT NULL,
  `collected_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blood_donations`
--

INSERT INTO `blood_donations` (`id`, `donation_id`, `donor_id`, `donation_date`, `donation_time`, `blood_group`, `volume_ml`, `bag_number`, `component`, `screening_status`, `hiv_tested`, `hbsag_tested`, `hcv_tested`, `vdrl_tested`, `malaria_tested`, `screening_notes`, `status`, `expiry_date`, `collected_by`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'DON-00001', 1, '2026-05-12', NULL, 'A+', 600.0, '1', 'Whole Blood', 'Passed', 1, 1, 1, 1, 1, NULL, 'Issued', '2026-06-16', 2, NULL, '2026-05-11 23:22:52', '2026-05-11 23:37:01', NULL),
(2, 'DON-00002', 2, '2026-05-12', NULL, 'A+', 600.0, '100', 'Packed RBC', 'Passed', 1, 1, 1, 1, 1, NULL, 'Issued', '2026-06-16', 1, NULL, '2026-05-12 01:48:35', '2026-05-12 01:50:12', NULL),
(3, 'DON-00003', 3, '2026-05-22', NULL, 'O+', 600.0, 'RBC-1', 'Packed RBC', 'Passed', 1, 1, 1, 1, 1, 'There is not negative value for the donation to not perfrom', 'Issued', '2026-06-26', 13, 'The donation successfully perform', '2026-05-22 07:00:12', '2026-05-22 07:05:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blood_donors`
--

CREATE TABLE `blood_donors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `donor_id` varchar(255) NOT NULL,
  `donor_type` enum('Voluntary','Replacement','Autologous','Directed') NOT NULL DEFAULT 'Voluntary',
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `blood_group` varchar(255) NOT NULL,
  `weight_kg` decimal(5,1) DEFAULT NULL,
  `cnic` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `is_eligible` tinyint(1) NOT NULL DEFAULT 1,
  `ineligibility_reason` text DEFAULT NULL,
  `eligible_from` date DEFAULT NULL,
  `last_donation_date` date DEFAULT NULL,
  `total_donations` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `next_eligible_date` date DEFAULT NULL,
  `patient_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blood_donors`
--

INSERT INTO `blood_donors` (`id`, `donor_id`, `donor_type`, `name`, `father_name`, `date_of_birth`, `gender`, `blood_group`, `weight_kg`, `cnic`, `phone`, `email`, `address`, `city`, `is_eligible`, `ineligibility_reason`, `eligible_from`, `last_donation_date`, `total_donations`, `next_eligible_date`, `patient_id`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'DNR-00001', 'Replacement', 'Sheryar khan', 'Hussain Gul', '1996-02-28', 'Male', 'A+', 77.0, '6110151419917', '03334504157', 'shehreyar882@gmail.com', 'dora school suffaid dheri khatama nabowata chowk peshawar', 'KHYBER', 1, NULL, NULL, '2026-05-12', 1, '2026-08-10', NULL, 'There is not medical history of this donar', '2026-05-11 23:20:30', '2026-05-11 23:22:52', NULL),
(2, 'DNR-00002', 'Voluntary', 'kashan', 'khan', '2002-02-02', 'Male', 'A+', 77.0, '6110298765432', '03335583974', 'shehreyar882@gmail.com', 'dora school suffaid dheri khatama nabowata chowk peshawar', 'Peshawar', 1, NULL, NULL, '2026-05-12', 1, '2026-08-10', 2, NULL, '2026-05-12 01:47:59', '2026-05-12 01:48:35', NULL),
(3, 'DNR-00003', 'Directed', 'Javid azam', 'gulbat khan', '2000-02-01', 'Male', 'O+', 88.0, '6110151419915', '92333450415', 'shehreyar882@gmail.com', 'dora school suffaid dheri khatama nabowata chowk peshawar', 'Peshawar', 1, NULL, NULL, '2026-05-22', 1, '2026-08-20', 44, 'The patient are healthy and strong', '2026-05-22 06:58:20', '2026-05-22 07:00:12', NULL),
(4, 'DNR-00004', 'Replacement', 'Shehreyar Khan Afridi kHAN', NULL, '2000-07-06', 'Male', 'A-', NULL, NULL, '03334504157', 'shehreyar882@gmail.com', 'dora school suffaid dheri khatama nabowata chowk peshawar', 'PESHAWAR', 1, NULL, NULL, NULL, 0, NULL, 2, NULL, '2026-05-24 21:44:09', '2026-05-24 21:44:09', NULL),
(5, 'DNR-00005', 'Replacement', 'Hussain GUl', 'Akai Mubarak', '1940-01-09', 'Male', 'A+', 88.0, '5555555555555', '03335583974', 'shehreyar882@gmail.com', 'Peshawar University Town', 'PESHAWAR', 1, NULL, NULL, NULL, 0, NULL, 35, 'nothing to say to you', '2026-05-24 21:56:51', '2026-05-24 21:56:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blood_inventories`
--

CREATE TABLE `blood_inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blood_group` varchar(255) NOT NULL,
  `component` enum('Whole Blood','Packed RBC','Platelets','Fresh Frozen Plasma','Cryoprecipitate') NOT NULL DEFAULT 'Whole Blood',
  `units_available` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `units_reserved` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `minimum_threshold` smallint(5) UNSIGNED NOT NULL DEFAULT 2,
  `last_updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blood_inventories`
--

INSERT INTO `blood_inventories` (`id`, `blood_group`, `component`, `units_available`, `units_reserved`, `minimum_threshold`, `last_updated_at`) VALUES
(1, 'A+', 'Whole Blood', 1, 0, 2, '2026-05-11 23:22:52'),
(2, 'A+', 'Packed RBC', 1, 0, 2, '2026-05-12 01:48:35'),
(3, 'O+', 'Packed RBC', 1, 0, 2, '2026-05-22 07:00:12');

-- --------------------------------------------------------

--
-- Table structure for table `blood_issues`
--

CREATE TABLE `blood_issues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `issue_id` varchar(255) NOT NULL,
  `blood_request_id` bigint(20) UNSIGNED NOT NULL,
  `blood_donation_id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `blood_group` varchar(255) NOT NULL,
  `bag_number` varchar(255) DEFAULT NULL,
  `volume_ml` decimal(6,1) DEFAULT NULL,
  `component` enum('Whole Blood','Packed RBC','Platelets','Fresh Frozen Plasma','Cryoprecipitate') NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transfusion_started_at` timestamp NULL DEFAULT NULL,
  `transfusion_completed_at` timestamp NULL DEFAULT NULL,
  `reaction_observed` tinyint(1) NOT NULL DEFAULT 0,
  `reaction_type` enum('None','Febrile','Allergic','Haemolytic','TACO','TRALI','Other') NOT NULL DEFAULT 'None',
  `reaction_notes` text DEFAULT NULL,
  `issued_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blood_issues`
--

INSERT INTO `blood_issues` (`id`, `issue_id`, `blood_request_id`, `blood_donation_id`, `patient_id`, `blood_group`, `bag_number`, `volume_ml`, `component`, `issued_at`, `transfusion_started_at`, `transfusion_completed_at`, `reaction_observed`, `reaction_type`, `reaction_notes`, `issued_by`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'BIS-00001', 1, 1, 2, 'A+', '1', 600.0, 'Whole Blood', '2026-05-11 23:37:01', NULL, NULL, 0, 'None', NULL, 3, NULL, '2026-05-11 23:37:01', '2026-05-11 23:37:01'),
(2, 'BIS-00002', 4, 2, 2, 'A+', '100', 600.0, 'Packed RBC', '2026-05-12 01:50:12', NULL, NULL, 0, 'None', NULL, NULL, NULL, '2026-05-12 01:50:12', '2026-05-12 01:50:12'),
(3, 'BIS-00003', 5, 3, 44, 'O+', 'RBC-1', 600.0, 'Packed RBC', '2026-05-22 07:05:51', NULL, NULL, 0, 'None', NULL, 12, 'Blood Issued', '2026-05-22 07:05:51', '2026-05-22 07:05:51');

-- --------------------------------------------------------

--
-- Table structure for table `blood_requests`
--

CREATE TABLE `blood_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_id` varchar(255) NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `blood_group` varchar(255) NOT NULL,
  `component` enum('Whole Blood','Packed RBC','Platelets','Fresh Frozen Plasma','Cryoprecipitate') NOT NULL DEFAULT 'Whole Blood',
  `units_required` smallint(5) UNSIGNED NOT NULL,
  `units_approved` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `urgency` enum('Routine','Urgent','Emergency') NOT NULL DEFAULT 'Routine',
  `indication` varchar(255) NOT NULL,
  `ward` varchar(255) DEFAULT NULL,
  `bed_number` varchar(255) DEFAULT NULL,
  `patient_hemoglobin` decimal(4,1) DEFAULT NULL,
  `status` enum('Pending','Under Review','Crossmatch','Approved','Partially Fulfilled','Fulfilled','Cancelled','Rejected') NOT NULL DEFAULT 'Pending',
  `rejection_reason` varchar(255) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `fulfilled_at` timestamp NULL DEFAULT NULL,
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blood_requests`
--

INSERT INTO `blood_requests` (`id`, `request_id`, `patient_id`, `doctor_id`, `blood_group`, `component`, `units_required`, `units_approved`, `urgency`, `indication`, `ward`, `bed_number`, `patient_hemoglobin`, `status`, `rejection_reason`, `approved_at`, `fulfilled_at`, `processed_by`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'BRQ-00001', 2, 1, 'A+', 'Whole Blood', 1, 1, 'Urgent', 'pre operation needs', 'medical A ward', '14', 7.5, 'Fulfilled', NULL, '2026-05-11 23:35:35', '2026-05-11 23:37:01', 2, NULL, '2026-05-11 23:35:26', '2026-05-11 23:37:01', NULL),
(2, 'BRQ-00002', 2, 1, 'A+', 'Whole Blood', 3, 1, 'Routine', 'pre operation needs', 'medical A ward', '14', 4.0, 'Approved', NULL, '2026-05-11 23:41:56', NULL, 2, NULL, '2026-05-11 23:41:15', '2026-05-11 23:41:56', NULL),
(3, 'BRQ-00003', 2, 1, 'A+', 'Whole Blood', 1, 1, 'Routine', 'pre operation needs', 'medical A ward', '14', 5.0, 'Approved', NULL, '2026-05-11 23:44:16', NULL, 2, NULL, '2026-05-11 23:43:47', '2026-05-11 23:44:16', NULL),
(4, 'BRQ-00004', 2, 1, 'A+', 'Packed RBC', 1, 1, 'Emergency', 'pre operation needs', 'medical A ward', '14', 2.0, 'Fulfilled', NULL, '2026-05-12 01:49:09', '2026-05-12 01:50:12', 2, NULL, '2026-05-12 01:49:01', '2026-05-12 01:50:12', NULL),
(5, 'BRQ-00005', 44, 1, 'O+', 'Packed RBC', 1, 1, 'Emergency', 'pre operation need', 'ward icu', '100', 5.7, 'Fulfilled', NULL, '2026-05-22 07:04:11', '2026-05-22 07:05:51', 6, 'kindly fastly arrange the blood for the patient it is emergency', '2026-05-22 07:03:45', '2026-05-22 07:05:51', NULL),
(6, 'BRQ-00006', 35, 1, 'A+', 'Whole Blood', 1, 1, 'Routine', 'pre operation need', 'medical A ward', '14', 4.4, 'Approved', NULL, '2026-05-24 21:36:40', NULL, 17, NULL, '2026-05-24 21:21:33', '2026-05-24 21:36:40', NULL),
(7, 'BRQ-00007', 33, 1, 'A-', 'Packed RBC', 1, 1, 'Routine', 'pre operation needs', 'medical A ward', '100', 1.6, 'Approved', NULL, '2026-05-31 23:57:11', NULL, 17, NULL, '2026-05-24 21:24:38', '2026-05-31 23:57:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `body_release_records`
--

CREATE TABLE `body_release_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `release_id` varchar(255) NOT NULL,
  `mortuary_record_id` bigint(20) UNSIGNED NOT NULL,
  `released_to_name` varchar(255) NOT NULL,
  `released_to_cnic` varchar(255) NOT NULL,
  `released_to_relation` varchar(255) NOT NULL,
  `released_to_phone` varchar(255) NOT NULL,
  `released_to_address` text DEFAULT NULL,
  `witness_1_name` varchar(255) DEFAULT NULL,
  `witness_1_cnic` varchar(255) DEFAULT NULL,
  `witness_2_name` varchar(255) DEFAULT NULL,
  `witness_2_cnic` varchar(255) DEFAULT NULL,
  `released_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `released_by` bigint(20) UNSIGNED NOT NULL,
  `transport_type` enum('Hospital Ambulance','Private Ambulance','Private Vehicle','On Foot','Other') DEFAULT NULL,
  `vehicle_number` varchar(255) DEFAULT NULL,
  `destination` varchar(255) NOT NULL,
  `death_certificate_provided` tinyint(1) NOT NULL DEFAULT 0,
  `death_certificate_number` varchar(255) DEFAULT NULL,
  `belongings_returned` tinyint(1) NOT NULL DEFAULT 0,
  `belongings_list` text DEFAULT NULL,
  `valuables_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `valuables_returned` tinyint(1) NOT NULL DEFAULT 0,
  `police_clearance_obtained` tinyint(1) NOT NULL DEFAULT 0,
  `police_clearance_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `body_release_records`
--

INSERT INTO `body_release_records` (`id`, `release_id`, `mortuary_record_id`, `released_to_name`, `released_to_cnic`, `released_to_relation`, `released_to_phone`, `released_to_address`, `witness_1_name`, `witness_1_cnic`, `witness_2_name`, `witness_2_cnic`, `released_at`, `released_by`, `transport_type`, `vehicle_number`, `destination`, `death_certificate_provided`, `death_certificate_number`, `belongings_returned`, `belongings_list`, `valuables_amount`, `valuables_returned`, `police_clearance_obtained`, `police_clearance_number`, `notes`, `created_at`, `updated_at`) VALUES
(2, 'BRL-00001', 2, 'Abdul Kalam', '1112345678999', 'brother', '03334504157', NULL, NULL, NULL, NULL, NULL, '2026-05-16 06:37:00', 6, 'Hospital Ambulance', 'STO 2683', 'Bara Khyber Agency', 1, 'DC-2026-00001', 1, NULL, 0.00, 0, 1, '100210', NULL, '2026-05-16 06:39:06', '2026-05-16 06:39:06'),
(3, 'BRL-00003', 3, 'Shehreyar Khan Afridi kHAN', '1112345678999', 'brother', '03334504157', 'dora school suffaid dheri khatama nabowata chowk peshawar', 'gameel khan', '1223456789098', 'sert3wfw', '2323232323232', '2026-05-17 04:22:00', 12, 'Hospital Ambulance', '2ffr44454', 'near haji gul masjid makbara road sango landi bala peshawar', 1, 'DC-2026-00004', 1, 'wallet phone', 1000.00, 1, 1, '100210', 'asdadsasd', '2026-05-17 04:24:38', '2026-05-17 04:24:38'),
(4, 'BRL-00004', 5, 'Rodolfo Moen', '420', '363', '631-974-1194', '427 Green Forge', 'Jaqueline Cormier', '33333333333333', 'Herbert Dickens', '55555555555555', '2026-02-11 23:23:00', 16, 'Private Ambulance', '134', '495 Marc Islands', 1, '185', 1, '242-976-5986', 342.00, 0, 0, NULL, '549', '2026-05-17 21:19:23', '2026-05-17 21:19:23'),
(5, 'BRL-00005', 6, 'Judith Kuvalis', '625', '396', '024-803-4645', '9346 Gail Viaduct', 'Doris Dickinson', '21122212121212', 'Kenya Miller', '34343434343434', '2027-02-08 05:03:00', 20, 'Private Ambulance', '198', '61148 Allen Pines', 1, '424', 1, '903-830-8946', 555555.00, 1, 0, NULL, '473', '2026-05-17 21:52:56', '2026-05-17 21:52:56'),
(6, 'BRL-00006', 7, 'kareen khan', '888888888888', 'wife', '09999999999', NULL, 'gameel khan', '1223456789098', 'sert3wfw', '3434343434343', '2026-05-18 01:00:00', 3, 'Hospital Ambulance', '134', 'dora school suffaid dheri khatama nabowat chowk peshawar', 1, 'DC-2026-00009', 1, NULL, 0.00, 1, 0, NULL, NULL, '2026-05-18 01:01:34', '2026-05-18 01:01:34');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('hospital-management-system-cache-app_settings', 'a:21:{s:13:\"hospital_name\";s:22:\"Leady Reading Hospital\";s:15:\"hospital_slogan\";s:28:\"Caring for a Better Tomorrow\";s:16:\"hospital_address\";s:38:\"Peshawar, Khyber Pakhtunkhwa, Pakistan\";s:14:\"hospital_phone\";s:11:\"03334504157\";s:14:\"hospital_email\";s:22:\"shehreyar882@gmail.com\";s:16:\"hospital_website\";s:0:\"\";s:13:\"hospital_logo\";s:53:\"settings/dofiQeHGiSyCujEu00q6BYsUh8ltZOtA2XyZgzJL.jpg\";s:8:\"timezone\";s:8:\"pakistan\";s:11:\"date_format\";s:9:\"12/6/2026\";s:8:\"currency\";s:3:\"PKR\";s:15:\"currency_symbol\";s:1:\"$\";s:11:\"bill_prefix\";s:5:\"BILL-\";s:14:\"tax_percentage\";s:1:\"0\";s:16:\"bill_footer_note\";s:41:\"Thank for choosing Leady Reading Hospital\";s:10:\"mrn_prefix\";s:4:\"MRN-\";s:17:\"lab_report_footer\";s:41:\"Results verified by licensed pathologist.\";s:8:\"lab_name\";s:19:\"Medicare Laboratory\";s:19:\"low_stock_threshold\";s:2:\"10\";s:17:\"expiry_alert_days\";s:2:\"30\";s:21:\"working_hours_per_day\";s:1:\"8\";s:13:\"payroll_cycle\";s:7:\"monthly\";}', 1781948616),
('hospital-management-system-cache-hospital_dashboard_stats_v2', 'a:56:{s:7:\"patient\";i:34;s:11:\"appointment\";i:0;s:13:\"availableBeds\";i:59;s:12:\"occupiedBeds\";i:1;s:12:\"reservedBeds\";i:0;s:9:\"totalBeds\";i:60;s:10:\"totalWards\";i:3;s:13:\"occupancyRate\";d:2;s:13:\"patientGrowth\";d:-93.8;s:17:\"appointmentChange\";i:0;s:12:\"todayRevenue\";i:0;s:12:\"monthRevenue\";s:8:\"91200.00\";s:12:\"totalRevenue\";s:9:\"188768.00\";s:14:\"labTotalOrders\";i:8;s:14:\"labTodayOrders\";i:0;s:16:\"labPendingOrders\";i:2;s:18:\"labCompletedOrders\";i:6;s:17:\"labCompletionRate\";d:75;s:14:\"radTotalOrders\";i:4;s:14:\"radTodayOrders\";i:0;s:17:\"radReportedOrders\";i:3;s:16:\"radPendingOrders\";i:0;s:10:\"radRevenue\";s:8:\"25550.00\";s:13:\"radReportRate\";d:75;s:16:\"otTotalScheduled\";i:3;s:16:\"otTodayScheduled\";i:0;s:11:\"otCompleted\";i:3;s:10:\"otUpcoming\";i:0;s:16:\"otCompletionRate\";d:100;s:7:\"otRooms\";i:1;s:14:\"totalMedicines\";i:5;s:12:\"lowStockMeds\";i:1;s:16:\"totalDispensings\";i:12;s:16:\"todayDispensings\";i:0;s:15:\"pharmacyRevenue\";s:8:\"24437.20\";s:15:\"stockHealthRate\";d:80;s:11:\"totalDonors\";i:5;s:18:\"totalBloodRequests\";i:7;s:16:\"pendingBloodReqs\";i:0;s:18:\"fulfilledBloodReqs\";i:3;s:15:\"bloodUnitsAvail\";s:1:\"3\";s:16:\"bloodFulfillRate\";d:43;s:13:\"totalMortuary\";i:6;s:17:\"totalCertificates\";i:7;s:14:\"releasedBodies\";i:5;s:11:\"medicoLegal\";i:2;s:15:\"unclaimedBodies\";i:0;s:19:\"mortuaryReleaseRate\";d:83;s:10:\"totalStaff\";i:21;s:12:\"totalDoctors\";i:10;s:11:\"totalNurses\";i:2;s:13:\"clinicalStaff\";i:10;s:13:\"clinicalRatio\";d:48;s:19:\"departmentOccupancy\";a:9:{i:0;a:4:{s:4:\"name\";s:10:\"Cardiology\";s:7:\"percent\";d:0;s:5:\"count\";i:0;s:5:\"color\";s:7:\"#ef4444\";}i:1;a:4:{s:4:\"name\";s:11:\"General OPD\";s:7:\"percent\";d:0;s:5:\"count\";i:0;s:5:\"color\";s:7:\"#6366f1\";}i:2;a:4:{s:4:\"name\";s:3:\"ICU\";s:7:\"percent\";d:0;s:5:\"count\";i:0;s:5:\"color\";s:7:\"#dc2626\";}i:3;a:4:{s:4:\"name\";s:10:\"Pediatrics\";s:7:\"percent\";d:0;s:5:\"count\";i:0;s:5:\"color\";s:7:\"#16a34a\";}i:4;a:4:{s:4:\"name\";s:10:\"Orthopedic\";s:7:\"percent\";d:0;s:5:\"count\";i:0;s:5:\"color\";s:7:\"#6366f1\";}i:5;a:4:{s:4:\"name\";s:9:\"Emergency\";s:7:\"percent\";d:0;s:5:\"count\";i:0;s:5:\"color\";s:7:\"#6366f1\";}i:6;a:4:{s:4:\"name\";s:10:\"Anesthesia\";s:7:\"percent\";d:0;s:5:\"count\";i:0;s:5:\"color\";s:7:\"#6366f1\";}i:7;a:4:{s:4:\"name\";s:7:\"Surgery\";s:7:\"percent\";d:0;s:5:\"count\";i:0;s:5:\"color\";s:7:\"#d97706\";}i:8;a:4:{s:4:\"name\";s:9:\"Radiology\";s:7:\"percent\";d:0;s:5:\"count\";i:0;s:5:\"color\";s:7:\"#6366f1\";}}s:14:\"recentPatients\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;O:18:\"App\\Models\\Patient\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"patients\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:20:{s:2:\"id\";i:64;s:3:\"mrn\";s:9:\"MRN-00032\";s:4:\"name\";s:6:\"salman\";s:11:\"father_name\";s:11:\"salman gull\";s:13:\"date_of_birth\";s:10:\"2000-02-09\";s:6:\"gender\";s:4:\"Male\";s:11:\"blood_group\";s:2:\"A-\";s:5:\"phone\";s:11:\"03334504157\";s:17:\"emergency_contact\";s:11:\"03334504157\";s:18:\"emergency_relation\";s:6:\"Father\";s:4:\"cnic\";s:13:\"1231233212343\";s:7:\"address\";s:56:\"dora school suffaid dheri khatama nabowat chowk peshawar\";s:4:\"city\";s:8:\"Peshawar\";s:12:\"patient_type\";s:3:\"IPD\";s:6:\"status\";s:6:\"Active\";s:9:\"doctor_id\";i:2;s:5:\"notes\";s:10:\"No History\";s:10:\"created_at\";s:19:\"2026-06-02 02:24:36\";s:10:\"updated_at\";s:19:\"2026-06-02 02:24:36\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:20:{s:2:\"id\";i:64;s:3:\"mrn\";s:9:\"MRN-00032\";s:4:\"name\";s:6:\"salman\";s:11:\"father_name\";s:11:\"salman gull\";s:13:\"date_of_birth\";s:10:\"2000-02-09\";s:6:\"gender\";s:4:\"Male\";s:11:\"blood_group\";s:2:\"A-\";s:5:\"phone\";s:11:\"03334504157\";s:17:\"emergency_contact\";s:11:\"03334504157\";s:18:\"emergency_relation\";s:6:\"Father\";s:4:\"cnic\";s:13:\"1231233212343\";s:7:\"address\";s:56:\"dora school suffaid dheri khatama nabowat chowk peshawar\";s:4:\"city\";s:8:\"Peshawar\";s:12:\"patient_type\";s:3:\"IPD\";s:6:\"status\";s:6:\"Active\";s:9:\"doctor_id\";i:2;s:5:\"notes\";s:10:\"No History\";s:10:\"created_at\";s:19:\"2026-06-02 02:24:36\";s:10:\"updated_at\";s:19:\"2026-06-02 02:24:36\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:13:\"date_of_birth\";s:4:\"date\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"doctor\";O:17:\"App\\Models\\Doctor\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:7:\"doctors\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:19:{s:2:\"id\";i:2;s:11:\"employee_id\";i:4;s:9:\"doctor_id\";s:9:\"DOC-00002\";s:14:\"specialization\";s:17:\"Medical Specilist\";s:13:\"qualification\";s:20:\"MBBS, FCPS (Medical)\";s:11:\"pmdc_number\";s:13:\"PMDCWX-33-WXS\";s:14:\"sub_department\";N;s:11:\"doctor_type\";s:15:\"Medical Officer\";s:16:\"consultation_fee\";s:7:\"2000.00\";s:21:\"avg_consultation_mins\";i:15;s:12:\"availability\";s:9:\"Available\";s:14:\"available_days\";s:31:\"[\"Mon\",\"Tue\",\"Wed\",\"Thu\",\"Fri\"]\";s:3:\"bio\";N;s:14:\"clinical_notes\";N;s:9:\"is_active\";i:1;s:20:\"accepts_new_patients\";i:1;s:10:\"created_at\";s:19:\"2026-05-11 00:49:08\";s:10:\"updated_at\";s:19:\"2026-05-11 00:49:08\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:19:{s:2:\"id\";i:2;s:11:\"employee_id\";i:4;s:9:\"doctor_id\";s:9:\"DOC-00002\";s:14:\"specialization\";s:17:\"Medical Specilist\";s:13:\"qualification\";s:20:\"MBBS, FCPS (Medical)\";s:11:\"pmdc_number\";s:13:\"PMDCWX-33-WXS\";s:14:\"sub_department\";N;s:11:\"doctor_type\";s:15:\"Medical Officer\";s:16:\"consultation_fee\";s:7:\"2000.00\";s:21:\"avg_consultation_mins\";i:15;s:12:\"availability\";s:9:\"Available\";s:14:\"available_days\";s:31:\"[\"Mon\",\"Tue\",\"Wed\",\"Thu\",\"Fri\"]\";s:3:\"bio\";N;s:14:\"clinical_notes\";N;s:9:\"is_active\";i:1;s:20:\"accepts_new_patients\";i:1;s:10:\"created_at\";s:19:\"2026-05-11 00:49:08\";s:10:\"updated_at\";s:19:\"2026-05-11 00:49:08\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:9:\"is_active\";s:7:\"boolean\";s:16:\"consultation_fee\";s:9:\"decimal:2\";s:14:\"available_days\";s:5:\"array\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:8:\"employee\";O:19:\"App\\Models\\Employee\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:9:\"employees\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:66:{s:2:\"id\";i:4;s:11:\"employee_id\";s:9:\"EMP-00003\";s:12:\"badge_number\";N;s:10:\"first_name\";s:5:\"Bilal\";s:9:\"last_name\";s:5:\"Malik\";s:11:\"father_name\";s:11:\"Abdul Malik\";s:11:\"mother_name\";N;s:13:\"date_of_birth\";s:10:\"1990-11-05\";s:6:\"gender\";s:4:\"Male\";s:14:\"marital_status\";s:6:\"Single\";s:8:\"religion\";N;s:11:\"nationality\";s:9:\"Pakistani\";s:4:\"cnic\";s:13:\"3520211223344\";s:11:\"cnic_expiry\";N;s:11:\"blood_group\";N;s:5:\"photo\";s:54:\"employees/bOsyADiTtQwZMeIeVMEFGD64YuArwPegGpBZK1Dr.png\";s:14:\"personal_phone\";s:11:\"03334455667\";s:12:\"office_phone\";N;s:14:\"personal_email\";N;s:12:\"office_email\";s:21:\"bilal.mo@hospital.com\";s:22:\"emergency_contact_name\";N;s:23:\"emergency_contact_phone\";N;s:26:\"emergency_contact_relation\";N;s:15:\"present_address\";N;s:17:\"permanent_address\";N;s:4:\"city\";N;s:8:\"province\";N;s:11:\"postal_code\";N;s:10:\"department\";s:24:\"Clinical — General OPD\";s:11:\"designation\";s:15:\"Medical Officer\";s:9:\"job_grade\";N;s:15:\"employment_type\";s:9:\"Permanent\";s:17:\"employment_status\";s:6:\"Active\";s:12:\"joining_date\";s:10:\"2022-06-01\";s:17:\"confirmation_date\";N;s:17:\"contract_end_date\";N;s:16:\"resignation_date\";N;s:16:\"termination_date\";N;s:18:\"termination_reason\";N;s:5:\"shift\";s:7:\"Morning\";s:11:\"shift_start\";N;s:9:\"shift_end\";N;s:12:\"weekly_hours\";i:48;s:12:\"working_days\";s:2:\"[]\";s:21:\"highest_qualification\";N;s:14:\"specialization\";N;s:11:\"institution\";N;s:15:\"graduation_year\";N;s:22:\"total_experience_years\";i:0;s:17:\"previous_employer\";N;s:20:\"previous_designation\";N;s:9:\"bank_name\";N;s:19:\"bank_account_number\";N;s:11:\"bank_branch\";N;s:4:\"iban\";N;s:11:\"salary_type\";s:7:\"Monthly\";s:12:\"basic_salary\";s:9:\"120000.00\";s:10:\"ntn_number\";N;s:11:\"eobi_number\";N;s:12:\"socso_number\";N;s:12:\"is_tax_filer\";i:0;s:17:\"has_system_access\";i:1;s:5:\"notes\";N;s:10:\"created_at\";s:19:\"2026-05-11 05:46:39\";s:10:\"updated_at\";s:19:\"2026-06-02 01:43:01\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:66:{s:2:\"id\";i:4;s:11:\"employee_id\";s:9:\"EMP-00003\";s:12:\"badge_number\";N;s:10:\"first_name\";s:5:\"Bilal\";s:9:\"last_name\";s:5:\"Malik\";s:11:\"father_name\";s:11:\"Abdul Malik\";s:11:\"mother_name\";N;s:13:\"date_of_birth\";s:10:\"1990-11-05\";s:6:\"gender\";s:4:\"Male\";s:14:\"marital_status\";s:6:\"Single\";s:8:\"religion\";N;s:11:\"nationality\";s:9:\"Pakistani\";s:4:\"cnic\";s:13:\"3520211223344\";s:11:\"cnic_expiry\";N;s:11:\"blood_group\";N;s:5:\"photo\";s:54:\"employees/bOsyADiTtQwZMeIeVMEFGD64YuArwPegGpBZK1Dr.png\";s:14:\"personal_phone\";s:11:\"03334455667\";s:12:\"office_phone\";N;s:14:\"personal_email\";N;s:12:\"office_email\";s:21:\"bilal.mo@hospital.com\";s:22:\"emergency_contact_name\";N;s:23:\"emergency_contact_phone\";N;s:26:\"emergency_contact_relation\";N;s:15:\"present_address\";N;s:17:\"permanent_address\";N;s:4:\"city\";N;s:8:\"province\";N;s:11:\"postal_code\";N;s:10:\"department\";s:24:\"Clinical — General OPD\";s:11:\"designation\";s:15:\"Medical Officer\";s:9:\"job_grade\";N;s:15:\"employment_type\";s:9:\"Permanent\";s:17:\"employment_status\";s:6:\"Active\";s:12:\"joining_date\";s:10:\"2022-06-01\";s:17:\"confirmation_date\";N;s:17:\"contract_end_date\";N;s:16:\"resignation_date\";N;s:16:\"termination_date\";N;s:18:\"termination_reason\";N;s:5:\"shift\";s:7:\"Morning\";s:11:\"shift_start\";N;s:9:\"shift_end\";N;s:12:\"weekly_hours\";i:48;s:12:\"working_days\";s:2:\"[]\";s:21:\"highest_qualification\";N;s:14:\"specialization\";N;s:11:\"institution\";N;s:15:\"graduation_year\";N;s:22:\"total_experience_years\";i:0;s:17:\"previous_employer\";N;s:20:\"previous_designation\";N;s:9:\"bank_name\";N;s:19:\"bank_account_number\";N;s:11:\"bank_branch\";N;s:4:\"iban\";N;s:11:\"salary_type\";s:7:\"Monthly\";s:12:\"basic_salary\";s:9:\"120000.00\";s:10:\"ntn_number\";N;s:11:\"eobi_number\";N;s:12:\"socso_number\";N;s:12:\"is_tax_filer\";i:0;s:17:\"has_system_access\";i:1;s:5:\"notes\";N;s:10:\"created_at\";s:19:\"2026-05-11 05:46:39\";s:10:\"updated_at\";s:19:\"2026-06-02 01:43:01\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:12:{s:13:\"date_of_birth\";s:4:\"date\";s:11:\"cnic_expiry\";s:4:\"date\";s:12:\"joining_date\";s:4:\"date\";s:17:\"confirmation_date\";s:4:\"date\";s:17:\"contract_end_date\";s:4:\"date\";s:16:\"resignation_date\";s:4:\"date\";s:16:\"termination_date\";s:4:\"date\";s:12:\"working_days\";s:5:\"array\";s:12:\"basic_salary\";s:9:\"decimal:2\";s:12:\"is_tax_filer\";s:7:\"boolean\";s:17:\"has_system_access\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:64:{i:0;s:11:\"employee_id\";i:1;s:12:\"badge_number\";i:2;s:10:\"first_name\";i:3;s:9:\"last_name\";i:4;s:11:\"father_name\";i:5;s:11:\"mother_name\";i:6;s:13:\"date_of_birth\";i:7;s:6:\"gender\";i:8;s:14:\"marital_status\";i:9;s:8:\"religion\";i:10;s:11:\"nationality\";i:11;s:4:\"cnic\";i:12;s:11:\"cnic_expiry\";i:13;s:11:\"blood_group\";i:14;s:5:\"photo\";i:15;s:14:\"personal_phone\";i:16;s:12:\"office_phone\";i:17;s:14:\"personal_email\";i:18;s:12:\"office_email\";i:19;s:22:\"emergency_contact_name\";i:20;s:23:\"emergency_contact_phone\";i:21;s:26:\"emergency_contact_relation\";i:22;s:15:\"present_address\";i:23;s:17:\"permanent_address\";i:24;s:4:\"city\";i:25;s:8:\"province\";i:26;s:11:\"postal_code\";i:27;s:10:\"department\";i:28;s:11:\"designation\";i:29;s:9:\"job_grade\";i:30;s:15:\"employment_type\";i:31;s:17:\"employment_status\";i:32;s:12:\"joining_date\";i:33;s:17:\"confirmation_date\";i:34;s:17:\"contract_end_date\";i:35;s:16:\"resignation_date\";i:36;s:16:\"termination_date\";i:37;s:18:\"termination_reason\";i:38;s:20:\"reporting_manager_id\";i:39;s:5:\"shift\";i:40;s:11:\"shift_start\";i:41;s:9:\"shift_end\";i:42;s:12:\"weekly_hours\";i:43;s:12:\"working_days\";i:44;s:21:\"highest_qualification\";i:45;s:14:\"specialization\";i:46;s:11:\"institution\";i:47;s:15:\"graduation_year\";i:48;s:22:\"total_experience_years\";i:49;s:17:\"previous_employer\";i:50;s:20:\"previous_designation\";i:51;s:9:\"bank_name\";i:52;s:19:\"bank_account_number\";i:53;s:11:\"bank_branch\";i:54;s:4:\"iban\";i:55;s:11:\"salary_type\";i:56;s:12:\"basic_salary\";i:57;s:10:\"ntn_number\";i:58;s:11:\"eobi_number\";i:59;s:12:\"socso_number\";i:60;s:12:\"is_tax_filer\";i:61;s:7:\"user_id\";i:62;s:17:\"has_system_access\";i:63;s:5:\"notes\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:14:\"\0*\0auditModule\";s:8:\"Employee\";s:16:\"\0*\0forceDeleting\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:15:{i:0;s:11:\"employee_id\";i:1;s:9:\"doctor_id\";i:2;s:14:\"specialization\";i:3;s:13:\"qualification\";i:4;s:11:\"pmdc_number\";i:5;s:16:\"consultation_fee\";i:6;s:12:\"availability\";i:7;s:11:\"doctor_type\";i:8;s:14:\"sub_department\";i:9;s:21:\"avg_consultation_mins\";i:10;s:14:\"available_days\";i:11;s:3:\"bio\";i:12;s:14:\"clinical_notes\";i:13;s:20:\"accepts_new_patients\";i:14;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:14:\"\0*\0auditModule\";s:6:\"Doctor\";s:16:\"\0*\0forceDeleting\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:16:{i:0;s:3:\"mrn\";i:1;s:4:\"name\";i:2;s:11:\"father_name\";i:3;s:13:\"date_of_birth\";i:4;s:6:\"gender\";i:5;s:11:\"blood_group\";i:6;s:5:\"phone\";i:7;s:17:\"emergency_contact\";i:8;s:18:\"emergency_relation\";i:9;s:4:\"cnic\";i:10;s:7:\"address\";i:11;s:4:\"city\";i:12;s:12:\"patient_type\";i:13;s:6:\"status\";i:14;s:9:\"doctor_id\";i:15;s:5:\"notes\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:14:\"\0*\0auditModule\";s:7:\"Patient\";s:16:\"\0*\0forceDeleting\";b:0;}i:1;O:18:\"App\\Models\\Patient\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"patients\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:20:{s:2:\"id\";i:63;s:3:\"mrn\";s:9:\"MRN-00031\";s:4:\"name\";s:5:\"ubaid\";s:11:\"father_name\";s:10:\"hassan jan\";s:13:\"date_of_birth\";s:10:\"2000-02-01\";s:6:\"gender\";s:4:\"Male\";s:11:\"blood_group\";s:2:\"A+\";s:5:\"phone\";s:10:\"0333450415\";s:17:\"emergency_contact\";s:10:\"0317665656\";s:18:\"emergency_relation\";s:6:\"Father\";s:4:\"cnic\";s:13:\"1730122232321\";s:7:\"address\";s:23:\"bara gate peshawa sadar\";s:4:\"city\";s:8:\"peshawar\";s:12:\"patient_type\";s:3:\"IPD\";s:6:\"status\";s:6:\"Active\";s:9:\"doctor_id\";i:2;s:5:\"notes\";s:39:\"The patient have some symptom of physco\";s:10:\"created_at\";s:19:\"2026-06-02 01:38:42\";s:10:\"updated_at\";s:19:\"2026-06-02 01:38:42\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:20:{s:2:\"id\";i:63;s:3:\"mrn\";s:9:\"MRN-00031\";s:4:\"name\";s:5:\"ubaid\";s:11:\"father_name\";s:10:\"hassan jan\";s:13:\"date_of_birth\";s:10:\"2000-02-01\";s:6:\"gender\";s:4:\"Male\";s:11:\"blood_group\";s:2:\"A+\";s:5:\"phone\";s:10:\"0333450415\";s:17:\"emergency_contact\";s:10:\"0317665656\";s:18:\"emergency_relation\";s:6:\"Father\";s:4:\"cnic\";s:13:\"1730122232321\";s:7:\"address\";s:23:\"bara gate peshawa sadar\";s:4:\"city\";s:8:\"peshawar\";s:12:\"patient_type\";s:3:\"IPD\";s:6:\"status\";s:6:\"Active\";s:9:\"doctor_id\";i:2;s:5:\"notes\";s:39:\"The patient have some symptom of physco\";s:10:\"created_at\";s:19:\"2026-06-02 01:38:42\";s:10:\"updated_at\";s:19:\"2026-06-02 01:38:42\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:13:\"date_of_birth\";s:4:\"date\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"doctor\";r:170;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:16:{i:0;s:3:\"mrn\";i:1;s:4:\"name\";i:2;s:11:\"father_name\";i:3;s:13:\"date_of_birth\";i:4;s:6:\"gender\";i:5;s:11:\"blood_group\";i:6;s:5:\"phone\";i:7;s:17:\"emergency_contact\";i:8;s:18:\"emergency_relation\";i:9;s:4:\"cnic\";i:10;s:7:\"address\";i:11;s:4:\"city\";i:12;s:12:\"patient_type\";i:13;s:6:\"status\";i:14;s:9:\"doctor_id\";i:15;s:5:\"notes\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:14:\"\0*\0auditModule\";s:7:\"Patient\";s:16:\"\0*\0forceDeleting\";b:0;}i:2;O:18:\"App\\Models\\Patient\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"patients\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:20:{s:2:\"id\";i:33;s:3:\"mrn\";s:10:\"MRN-000031\";s:4:\"name\";s:9:\"Ahmed Ali\";s:11:\"father_name\";s:12:\"Muhammad Ali\";s:13:\"date_of_birth\";s:10:\"1990-05-15\";s:6:\"gender\";s:4:\"Male\";s:11:\"blood_group\";s:2:\"A+\";s:5:\"phone\";s:11:\"03001234561\";s:17:\"emergency_contact\";s:11:\"03111234561\";s:18:\"emergency_relation\";s:6:\"Father\";s:4:\"cnic\";s:13:\"4210112345671\";s:7:\"address\";s:17:\"Street 1, Gulshan\";s:4:\"city\";s:7:\"Karachi\";s:12:\"patient_type\";s:3:\"IPD\";s:6:\"status\";s:10:\"Discharged\";s:9:\"doctor_id\";N;s:5:\"notes\";s:14:\"Normal checkup\";s:10:\"created_at\";s:19:\"2026-05-13 06:50:55\";s:10:\"updated_at\";s:19:\"2026-06-16 11:03:16\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:20:{s:2:\"id\";i:33;s:3:\"mrn\";s:10:\"MRN-000031\";s:4:\"name\";s:9:\"Ahmed Ali\";s:11:\"father_name\";s:12:\"Muhammad Ali\";s:13:\"date_of_birth\";s:10:\"1990-05-15\";s:6:\"gender\";s:4:\"Male\";s:11:\"blood_group\";s:2:\"A+\";s:5:\"phone\";s:11:\"03001234561\";s:17:\"emergency_contact\";s:11:\"03111234561\";s:18:\"emergency_relation\";s:6:\"Father\";s:4:\"cnic\";s:13:\"4210112345671\";s:7:\"address\";s:17:\"Street 1, Gulshan\";s:4:\"city\";s:7:\"Karachi\";s:12:\"patient_type\";s:3:\"IPD\";s:6:\"status\";s:10:\"Discharged\";s:9:\"doctor_id\";N;s:5:\"notes\";s:14:\"Normal checkup\";s:10:\"created_at\";s:19:\"2026-05-13 06:50:55\";s:10:\"updated_at\";s:19:\"2026-06-16 11:03:16\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:13:\"date_of_birth\";s:4:\"date\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"doctor\";N;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:16:{i:0;s:3:\"mrn\";i:1;s:4:\"name\";i:2;s:11:\"father_name\";i:3;s:13:\"date_of_birth\";i:4;s:6:\"gender\";i:5;s:11:\"blood_group\";i:6;s:5:\"phone\";i:7;s:17:\"emergency_contact\";i:8;s:18:\"emergency_relation\";i:9;s:4:\"cnic\";i:10;s:7:\"address\";i:11;s:4:\"city\";i:12;s:12:\"patient_type\";i:13;s:6:\"status\";i:14;s:9:\"doctor_id\";i:15;s:5:\"notes\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:14:\"\0*\0auditModule\";s:7:\"Patient\";s:16:\"\0*\0forceDeleting\";b:0;}i:3;O:18:\"App\\Models\\Patient\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"patients\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:20:{s:2:\"id\";i:34;s:3:\"mrn\";s:10:\"MRN-000032\";s:4:\"name\";s:9:\"Sara Khan\";s:11:\"father_name\";s:10:\"Abdul Khan\";s:13:\"date_of_birth\";s:10:\"1995-10-22\";s:6:\"gender\";s:6:\"Female\";s:11:\"blood_group\";s:2:\"B-\";s:5:\"phone\";s:11:\"03001234562\";s:17:\"emergency_contact\";s:11:\"03111234562\";s:18:\"emergency_relation\";s:7:\"Husband\";s:4:\"cnic\";s:13:\"4210112345672\";s:7:\"address\";s:16:\"Block 4, Clifton\";s:4:\"city\";s:7:\"Karachi\";s:12:\"patient_type\";s:3:\"IPD\";s:6:\"status\";s:8:\"Deceased\";s:9:\"doctor_id\";N;s:5:\"notes\";s:12:\"Post-surgery\";s:10:\"created_at\";s:19:\"2026-05-13 06:50:55\";s:10:\"updated_at\";s:19:\"2026-05-18 06:00:24\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:20:{s:2:\"id\";i:34;s:3:\"mrn\";s:10:\"MRN-000032\";s:4:\"name\";s:9:\"Sara Khan\";s:11:\"father_name\";s:10:\"Abdul Khan\";s:13:\"date_of_birth\";s:10:\"1995-10-22\";s:6:\"gender\";s:6:\"Female\";s:11:\"blood_group\";s:2:\"B-\";s:5:\"phone\";s:11:\"03001234562\";s:17:\"emergency_contact\";s:11:\"03111234562\";s:18:\"emergency_relation\";s:7:\"Husband\";s:4:\"cnic\";s:13:\"4210112345672\";s:7:\"address\";s:16:\"Block 4, Clifton\";s:4:\"city\";s:7:\"Karachi\";s:12:\"patient_type\";s:3:\"IPD\";s:6:\"status\";s:8:\"Deceased\";s:9:\"doctor_id\";N;s:5:\"notes\";s:12:\"Post-surgery\";s:10:\"created_at\";s:19:\"2026-05-13 06:50:55\";s:10:\"updated_at\";s:19:\"2026-05-18 06:00:24\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:13:\"date_of_birth\";s:4:\"date\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"doctor\";N;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:16:{i:0;s:3:\"mrn\";i:1;s:4:\"name\";i:2;s:11:\"father_name\";i:3;s:13:\"date_of_birth\";i:4;s:6:\"gender\";i:5;s:11:\"blood_group\";i:6;s:5:\"phone\";i:7;s:17:\"emergency_contact\";i:8;s:18:\"emergency_relation\";i:9;s:4:\"cnic\";i:10;s:7:\"address\";i:11;s:4:\"city\";i:12;s:12:\"patient_type\";i:13;s:6:\"status\";i:14;s:9:\"doctor_id\";i:15;s:5:\"notes\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:14:\"\0*\0auditModule\";s:7:\"Patient\";s:16:\"\0*\0forceDeleting\";b:0;}i:4;O:18:\"App\\Models\\Patient\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"patients\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:20:{s:2:\"id\";i:35;s:3:\"mrn\";s:9:\"MRN-00003\";s:4:\"name\";s:13:\"Zubair Sheikh\";s:11:\"father_name\";s:14:\"Ibrahim Sheikh\";s:13:\"date_of_birth\";s:10:\"1985-03-10\";s:6:\"gender\";s:4:\"Male\";s:11:\"blood_group\";s:2:\"O+\";s:5:\"phone\";s:11:\"03001234563\";s:17:\"emergency_contact\";s:11:\"03111234563\";s:18:\"emergency_relation\";s:7:\"Brother\";s:4:\"cnic\";s:13:\"4210112345673\";s:7:\"address\";s:10:\"Model Town\";s:4:\"city\";s:6:\"Lahore\";s:12:\"patient_type\";s:3:\"IPD\";s:6:\"status\";s:6:\"Active\";s:9:\"doctor_id\";N;s:5:\"notes\";s:13:\"Accident case\";s:10:\"created_at\";s:19:\"2026-05-13 06:50:55\";s:10:\"updated_at\";s:19:\"2026-05-19 02:38:09\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:20:{s:2:\"id\";i:35;s:3:\"mrn\";s:9:\"MRN-00003\";s:4:\"name\";s:13:\"Zubair Sheikh\";s:11:\"father_name\";s:14:\"Ibrahim Sheikh\";s:13:\"date_of_birth\";s:10:\"1985-03-10\";s:6:\"gender\";s:4:\"Male\";s:11:\"blood_group\";s:2:\"O+\";s:5:\"phone\";s:11:\"03001234563\";s:17:\"emergency_contact\";s:11:\"03111234563\";s:18:\"emergency_relation\";s:7:\"Brother\";s:4:\"cnic\";s:13:\"4210112345673\";s:7:\"address\";s:10:\"Model Town\";s:4:\"city\";s:6:\"Lahore\";s:12:\"patient_type\";s:3:\"IPD\";s:6:\"status\";s:6:\"Active\";s:9:\"doctor_id\";N;s:5:\"notes\";s:13:\"Accident case\";s:10:\"created_at\";s:19:\"2026-05-13 06:50:55\";s:10:\"updated_at\";s:19:\"2026-05-19 02:38:09\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:13:\"date_of_birth\";s:4:\"date\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"doctor\";N;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:16:{i:0;s:3:\"mrn\";i:1;s:4:\"name\";i:2;s:11:\"father_name\";i:3;s:13:\"date_of_birth\";i:4;s:6:\"gender\";i:5;s:11:\"blood_group\";i:6;s:5:\"phone\";i:7;s:17:\"emergency_contact\";i:8;s:18:\"emergency_relation\";i:9;s:4:\"cnic\";i:10;s:7:\"address\";i:11;s:4:\"city\";i:12;s:12:\"patient_type\";i:13;s:6:\"status\";i:14;s:9:\"doctor_id\";i:15;s:5:\"notes\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:14:\"\0*\0auditModule\";s:7:\"Patient\";s:16:\"\0*\0forceDeleting\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:18:\"recentAppointments\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}', 1781862816);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `death_certificates`
--

CREATE TABLE `death_certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `certificate_number` varchar(255) NOT NULL,
  `mortuary_record_id` bigint(20) UNSIGNED NOT NULL,
  `certificate_type` enum('Hospital Death Certificate','Medico Legal Certificate','Stillbirth Certificate','Duplicate') NOT NULL DEFAULT 'Hospital Death Certificate',
  `purpose` enum('Burial / Funeral','NADRA Registration','Legal Proceedings','Insurance Claim','Embassy / Visa','Other') NOT NULL DEFAULT 'Burial / Funeral',
  `issued_to_name` varchar(255) NOT NULL,
  `issued_to_cnic` varchar(255) DEFAULT NULL,
  `issued_to_relation` varchar(255) NOT NULL,
  `issued_to_phone` varchar(255) DEFAULT NULL,
  `issued_to_address` varchar(255) DEFAULT NULL,
  `signed_by_doctor` bigint(20) UNSIGNED NOT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `issued_by` bigint(20) UNSIGNED DEFAULT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `copy_number` int(11) NOT NULL DEFAULT 1,
  `total_copies` int(11) NOT NULL DEFAULT 1,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verified_at` timestamp NULL DEFAULT NULL,
  `fee_charged` decimal(8,2) NOT NULL DEFAULT 0.00,
  `fee_paid` tinyint(1) NOT NULL DEFAULT 0,
  `bill_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `death_certificates`
--

INSERT INTO `death_certificates` (`id`, `certificate_number`, `mortuary_record_id`, `certificate_type`, `purpose`, `issued_to_name`, `issued_to_cnic`, `issued_to_relation`, `issued_to_phone`, `issued_to_address`, `signed_by_doctor`, `verified_by`, `issued_by`, `issued_at`, `copy_number`, `total_copies`, `is_verified`, `verified_at`, `fee_charged`, `fee_paid`, `bill_id`, `remarks`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'DC-2026-00001', 2, 'Hospital Death Certificate', 'Legal Proceedings', 'Abdul Kalam', '1112345678999', 'brother', '03334504157', NULL, 8, NULL, 11, '2026-05-16 06:33:00', 1, 3, 0, NULL, 5000.00, 0, NULL, NULL, '2026-05-16 06:35:11', '2026-05-16 06:35:11', NULL),
(3, 'DC-2026-00003', 2, 'Hospital Death Certificate', 'Burial / Funeral', 'Abdul Kalam', '1112345678999', 'brother', '03334504157', NULL, 7, NULL, 6, '2026-05-16 11:37:39', 2, 1, 0, NULL, 0.00, 0, NULL, NULL, '2026-05-16 06:37:11', '2026-05-16 06:37:39', '2026-05-16 06:37:39'),
(4, 'DC-2026-00004', 3, 'Hospital Death Certificate', 'Burial / Funeral', 'Shehreyar Khan Afridi kHAN', '1112345678999', 'brother', '03334504157', 'dora school suffaid dheri khatama nabowata chowk peshawar', 8, 12, 11, '2026-05-17 04:16:00', 1, 1, 1, '2026-05-17 04:17:18', 0.00, 0, NULL, 'GHJTHJ', '2026-05-17 04:17:18', '2026-05-17 04:17:18', NULL),
(5, 'DC-2026-00005', 4, 'Hospital Death Certificate', 'NADRA Registration', 'Khan', '1112345678999', 'brother', '03334504157', 'johar town', 8, 18, 1, '2026-05-17 20:50:00', 1, 1, 1, '2026-05-17 20:51:17', 499.99, 0, NULL, NULL, '2026-05-17 20:51:17', '2026-05-17 20:51:17', NULL),
(6, 'DC-2026-00006', 5, 'Hospital Death Certificate', 'Burial / Funeral', 'Tara Adams', '538', '182', '251-061-1581', NULL, 1, NULL, NULL, '2026-05-17 21:10:00', 1, 1, 0, NULL, 0.00, 0, NULL, NULL, '2026-05-17 21:18:27', '2026-05-17 21:18:27', NULL),
(7, 'DC-2026-00007', 4, 'Hospital Death Certificate', 'Burial / Funeral', 'Khan', '1112345678999', 'brother', NULL, NULL, 1, NULL, NULL, '2026-05-17 21:35:00', 2, 1, 0, NULL, 500.00, 0, NULL, NULL, '2026-05-17 21:35:36', '2026-05-17 21:35:36', NULL),
(8, 'DC-2026-00008', 6, 'Stillbirth Certificate', 'Legal Proceedings', 'Saad Khan', '1112345678999', 'Father', '03334434343', 'dora school suffaid dheri khatama nabowat chowk peshawar', 2, 12, 14, '2026-05-17 21:41:00', 1, 1, 1, '2026-05-17 21:42:30', 2000.00, 0, NULL, NULL, '2026-05-17 21:42:30', '2026-05-17 21:42:30', NULL),
(9, 'DC-2026-00009', 7, 'Hospital Death Certificate', 'Burial / Funeral', 'kareen khan', '888888888888', 'wife', '09999999999', 'Peshawar University Town', 9, 18, 12, '2026-05-18 06:03:36', 1, 1, 1, '2026-05-18 01:00:52', 1800.00, 1, 17, NULL, '2026-05-18 01:00:52', '2026-05-18 01:03:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `disciplinary_actions`
--

CREATE TABLE `disciplinary_actions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `action_number` varchar(255) NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `incident_date` date NOT NULL,
  `incident_type` enum('Misconduct','Insubordination','Tardiness','Absenteeism','Negligence','Harassment','Fraud','Violence','Policy Violation','Other') NOT NULL,
  `incident_description` text NOT NULL,
  `action_type` enum('Verbal Warning','Written Warning','Show Cause Notice','Suspension','Demotion','Salary Deduction','Termination','Other') NOT NULL,
  `action_date` date NOT NULL,
  `action_details` text NOT NULL,
  `suspension_from` date DEFAULT NULL,
  `suspension_to` date DEFAULT NULL,
  `suspension_days` int(11) DEFAULT NULL,
  `suspension_paid` tinyint(1) NOT NULL DEFAULT 0,
  `deduction_amount` decimal(10,2) DEFAULT 0.00,
  `deduction_month` varchar(255) DEFAULT NULL,
  `employee_response` text DEFAULT NULL,
  `response_deadline` date DEFAULT NULL,
  `response_received` tinyint(1) NOT NULL DEFAULT 0,
  `response_received_date` date DEFAULT NULL,
  `status` enum('Issued','Acknowledged','Under Review','Resolved','Escalated','Closed') NOT NULL DEFAULT 'Issued',
  `is_appealed` tinyint(1) NOT NULL DEFAULT 0,
  `appeal_details` text DEFAULT NULL,
  `appeal_outcome` enum('Upheld','Overturned','Modified') DEFAULT NULL,
  `issued_by` bigint(20) UNSIGNED NOT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `disciplinary_actions`
--

INSERT INTO `disciplinary_actions` (`id`, `action_number`, `employee_id`, `incident_date`, `incident_type`, `incident_description`, `action_type`, `action_date`, `action_details`, `suspension_from`, `suspension_to`, `suspension_days`, `suspension_paid`, `deduction_amount`, `deduction_month`, `employee_response`, `response_deadline`, `response_received`, `response_received_date`, `status`, `is_appealed`, `appeal_details`, `appeal_outcome`, `issued_by`, `reviewed_by`, `notes`, `document_path`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'DA-00001', 2, '2026-06-11', 'Misconduct', 'it misconduct the information', 'Written Warning', '2026-06-11', 'notgin to sya jsut teminalnte', NULL, NULL, NULL, 0, NULL, NULL, NULL, '2026-06-20', 0, NULL, 'Resolved', 0, NULL, NULL, 11, 11, 'sdf\n\nResolution: SDfsdfsd', NULL, '2026-06-11 04:18:00', '2026-06-11 05:13:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dispensings`
--

CREATE TABLE `dispensings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dispense_number` varchar(255) NOT NULL,
  `prescription_id` bigint(20) UNSIGNED DEFAULT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `dispensed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('Paid','Unpaid','Partial') NOT NULL DEFAULT 'Unpaid',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dispensings`
--

INSERT INTO `dispensings` (`id`, `dispense_number`, `prescription_id`, `patient_id`, `dispensed_at`, `total_amount`, `payment_status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'DSP-00001', 2, 1, '2026-05-11 02:20:24', 18.00, 'Paid', NULL, '2026-05-10 20:44:50', '2026-05-10 21:20:24'),
(2, 'DSP-00002', 3, 33, '2026-05-17 20:53:18', 2100.00, 'Unpaid', NULL, '2026-05-17 20:53:18', '2026-05-17 20:53:18'),
(3, 'DSP-00003', 4, 42, '2026-05-18 05:53:12', 2112.00, 'Paid', NULL, '2026-05-17 21:23:52', '2026-05-18 00:53:12'),
(4, 'DSP-00004', 5, 45, '2026-05-18 00:51:51', 3570.00, 'Unpaid', NULL, '2026-05-18 00:51:51', '2026-05-18 00:51:51'),
(5, 'DSP-00005', 6, 34, '2026-05-18 06:03:36', 3990.00, 'Paid', NULL, '2026-05-18 00:58:50', '2026-05-18 01:03:36'),
(6, 'DSP-00006', 7, 1, '2026-05-22 01:01:02', 1050.00, 'Paid', NULL, '2026-05-18 22:01:50', '2026-05-21 20:01:02'),
(7, 'DSP-00007', 8, 44, '2026-05-22 05:28:40', 2136.00, 'Unpaid', NULL, '2026-05-22 05:28:40', '2026-05-22 05:28:40'),
(8, 'DSP-00008', 9, 63, '2026-06-11 16:38:20', 2100.00, 'Paid', NULL, '2026-06-03 08:22:52', '2026-06-11 11:38:20'),
(9, 'DSP-00009', 10, 35, '2026-06-05 05:54:47', 4410.00, 'Paid', NULL, '2026-06-04 20:45:14', '2026-06-05 00:54:47'),
(10, 'DSP-00010', 1, 1, '2026-06-04 21:40:40', 2100.00, 'Unpaid', NULL, '2026-06-04 21:40:40', '2026-06-04 21:40:40'),
(11, 'DSP-00011', 1, 1, '2026-06-04 21:46:46', 11.20, 'Unpaid', NULL, '2026-06-04 21:46:46', '2026-06-04 21:46:46'),
(12, 'DSP-00012', 13, 35, '2026-06-16 06:03:34', 840.00, 'Unpaid', NULL, '2026-06-16 06:03:34', '2026-06-16 06:03:34');

-- --------------------------------------------------------

--
-- Table structure for table `dispensing_items`
--

CREATE TABLE `dispensing_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dispensing_id` bigint(20) UNSIGNED NOT NULL,
  `prescription_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `medicine_id` bigint(20) UNSIGNED NOT NULL,
  `medicine_batch_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dispensing_items`
--

INSERT INTO `dispensing_items` (`id`, `dispensing_id`, `prescription_item_id`, `medicine_id`, `medicine_batch_id`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 33, 21, 9, 2.00, 18.00, '2026-05-10 20:44:50', '2026-05-10 20:44:50'),
(2, 2, 3, 34, 22, 10, 210.00, 2100.00, '2026-05-17 20:53:18', '2026-05-17 20:53:18'),
(3, 3, 4, 34, 22, 10, 210.00, 2100.00, '2026-05-17 21:23:52', '2026-05-17 21:23:52'),
(4, 3, 5, 35, 23, 10, 1.20, 12.00, '2026-05-17 21:23:52', '2026-05-17 21:23:52'),
(5, 4, 6, 34, 22, 17, 210.00, 3570.00, '2026-05-18 00:51:51', '2026-05-18 00:51:51'),
(6, 5, 7, 34, 22, 19, 210.00, 3990.00, '2026-05-18 00:58:50', '2026-05-18 00:58:50'),
(7, 6, 8, 34, 22, 5, 210.00, 1050.00, '2026-05-18 22:01:50', '2026-05-18 22:01:50'),
(8, 7, 9, 34, 22, 10, 210.00, 2100.00, '2026-05-22 05:28:40', '2026-05-22 05:28:40'),
(9, 7, 10, 36, 24, 9, 2.80, 25.20, '2026-05-22 05:28:40', '2026-05-22 05:28:40'),
(10, 7, 11, 35, 23, 9, 1.20, 10.80, '2026-05-22 05:28:40', '2026-05-22 05:28:40'),
(11, 8, 12, 34, 22, 10, 210.00, 2100.00, '2026-06-03 08:22:52', '2026-06-03 08:22:52'),
(12, 9, 13, 34, 25, 21, 210.00, 4410.00, '2026-06-04 20:45:14', '2026-06-04 20:45:14'),
(13, 10, NULL, 34, 25, 10, 210.00, 2100.00, '2026-06-04 21:40:40', '2026-06-04 21:40:40'),
(14, 11, NULL, 36, 24, 4, 2.80, 11.20, '2026-06-04 21:46:46', '2026-06-04 21:46:46'),
(15, 12, 16, 34, 22, 4, 210.00, 840.00, '2026-06-16 06:03:34', '2026-06-16 06:03:34');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` varchar(255) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `qualification` varchar(255) NOT NULL,
  `pmdc_number` varchar(255) DEFAULT NULL,
  `sub_department` varchar(255) DEFAULT NULL,
  `doctor_type` enum('Consultant','Medical Officer','House Officer','Visiting','Specialist') NOT NULL DEFAULT 'Medical Officer',
  `consultation_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `avg_consultation_mins` int(11) NOT NULL DEFAULT 15,
  `availability` enum('Available','In Consultation','On Leave','Off Duty') NOT NULL DEFAULT 'Available',
  `available_days` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`available_days`)),
  `bio` text DEFAULT NULL,
  `clinical_notes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `accepts_new_patients` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `employee_id`, `doctor_id`, `specialization`, `qualification`, `pmdc_number`, `sub_department`, `doctor_type`, `consultation_fee`, `avg_consultation_mins`, `availability`, `available_days`, `bio`, `clinical_notes`, `is_active`, `accepts_new_patients`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'DOC-00001', 'Cardiologist', 'MBBS, FCPS (cardiology)', NULL, 'Cardiology', 'Medical Officer', 1500.00, 15, 'Available', '[\"Mon\",\"Tue\",\"Wed\",\"Thu\",\"Fri\"]', NULL, NULL, 1, 1, '2026-05-10 19:48:22', '2026-05-10 19:48:22', NULL),
(2, 4, 'DOC-00002', 'Medical Specilist', 'MBBS, FCPS (Medical)', 'PMDCWX-33-WXS', NULL, 'Medical Officer', 2000.00, 15, 'Available', '[\"Mon\",\"Tue\",\"Wed\",\"Thu\",\"Fri\"]', NULL, NULL, 1, 1, '2026-05-10 19:49:08', '2026-05-10 19:49:08', NULL),
(3, 8, 'DOC-00003', 'ICU Care', 'MBBS, FCPS (General)', NULL, 'Operation Theater', 'Medical Officer', 1000.00, 15, 'Available', '[\"Mon\",\"Tue\",\"Wed\",\"Thu\",\"Fri\"]', NULL, NULL, 1, 1, '2026-05-10 19:49:55', '2026-05-10 19:49:55', NULL),
(4, 5, 'DOC-00004', 'Medical Specilist', 'MBBS, FCPS (Pediatrics)', NULL, 'Orthopedic', 'House Officer', 500.00, 15, 'Available', '[\"Mon\",\"Tue\",\"Wed\",\"Thu\",\"Fri\"]', NULL, NULL, 1, 1, '2026-05-10 19:50:51', '2026-05-10 19:50:51', NULL),
(5, 6, 'DOC-00005', 'Medical Specilist', 'MBBS, FCPS (Orthopedic)', 'PMDCWX-33-WXS', 'Orthopedic', 'Medical Officer', 1500.00, 15, 'Available', '[\"Mon\",\"Tue\",\"Wed\",\"Thu\",\"Fri\"]', NULL, NULL, 1, 1, '2026-05-10 19:52:19', '2026-05-10 19:52:19', NULL),
(6, 21, 'DOC-00006', 'Medical Specilist', 'MBBS, MCPS', NULL, NULL, 'House Officer', 1000.00, 15, 'Available', '[\"Mon\",\"Tue\",\"Wed\",\"Thu\",\"Fri\"]', NULL, NULL, 1, 1, '2026-05-10 19:52:55', '2026-05-10 19:52:55', NULL),
(7, 7, 'DOC-00007', 'Medical Specilist', 'MBBS, FCPS (Anesthesia)', NULL, NULL, 'Specialist', 1500.00, 15, 'Available', '[\"Mon\",\"Tue\",\"Wed\",\"Thu\",\"Fri\"]', NULL, NULL, 1, 1, '2026-05-10 19:53:52', '2026-05-10 19:53:52', NULL),
(8, 3, 'DOC-00008', 'Surgen', 'MBBS, FCPS (surgery)', 'PMDCWX-33-WXS', NULL, 'Medical Officer', 3000.00, 15, 'Available', '[\"Mon\",\"Tue\",\"Wed\",\"Thu\",\"Fri\"]', NULL, NULL, 1, 1, '2026-05-10 19:55:30', '2026-05-10 19:55:30', NULL),
(9, 1, 'DOC-00009', 'Surgen', 'MBBS, FCPS (Surgery)', 'PMDCWX-33-WXS', 'Operation Theater', 'Medical Officer', 4000.00, 15, 'Available', '[\"Mon\",\"Tue\",\"Wed\",\"Thu\",\"Fri\"]', NULL, NULL, 1, 1, '2026-05-10 19:57:16', '2026-05-10 19:57:16', NULL),
(10, 17, 'DOC-00010', 'Aestivus vinculum synagoga thema creta odit correptius.', 'Decet adulatio agnitio deleo.', '141', 'Deinde reprehenderit veniam curis creber.', 'Specialist', 100.00, 28, 'On Leave', '[\"Mon\",\"Tue\",\"Wed\",\"Thu\",\"Fri\"]', 'Vix deludo uter cruentus despecto calamitas conitor soluta subnecto amplexus.', '364', 1, 1, '2026-06-12 01:55:04', '2026-06-12 01:55:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `badge_number` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `marital_status` enum('Single','Married','Divorced','Widowed') NOT NULL DEFAULT 'Single',
  `religion` varchar(255) DEFAULT NULL,
  `nationality` varchar(255) NOT NULL DEFAULT 'Pakistani',
  `cnic` varchar(255) DEFAULT NULL,
  `cnic_expiry` date DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `personal_phone` varchar(255) NOT NULL,
  `office_phone` varchar(255) DEFAULT NULL,
  `personal_email` varchar(255) DEFAULT NULL,
  `office_email` varchar(255) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `emergency_contact_relation` varchar(255) DEFAULT NULL,
  `present_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `department` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `job_grade` varchar(255) DEFAULT NULL,
  `employment_type` enum('Permanent','Contractual','Probationary','Part-Time','Intern','Daily-Wage') NOT NULL DEFAULT 'Permanent',
  `employment_status` enum('Active','On Leave','Suspended','Terminated','Resigned','Retired') NOT NULL DEFAULT 'Active',
  `joining_date` date NOT NULL,
  `confirmation_date` date DEFAULT NULL,
  `contract_end_date` date DEFAULT NULL,
  `resignation_date` date DEFAULT NULL,
  `termination_date` date DEFAULT NULL,
  `termination_reason` text DEFAULT NULL,
  `shift` enum('Morning','Evening','Night','Rotating','Custom') NOT NULL DEFAULT 'Morning',
  `shift_start` time DEFAULT NULL,
  `shift_end` time DEFAULT NULL,
  `weekly_hours` int(11) NOT NULL DEFAULT 48,
  `working_days` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`working_days`)),
  `highest_qualification` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `graduation_year` year(4) DEFAULT NULL,
  `total_experience_years` int(11) NOT NULL DEFAULT 0,
  `previous_employer` varchar(255) DEFAULT NULL,
  `previous_designation` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_branch` varchar(255) DEFAULT NULL,
  `iban` varchar(255) DEFAULT NULL,
  `salary_type` enum('Monthly','Daily','Hourly') NOT NULL DEFAULT 'Monthly',
  `basic_salary` decimal(12,2) NOT NULL DEFAULT 0.00,
  `ntn_number` varchar(255) DEFAULT NULL,
  `eobi_number` varchar(255) DEFAULT NULL,
  `socso_number` varchar(255) DEFAULT NULL,
  `is_tax_filer` tinyint(1) NOT NULL DEFAULT 0,
  `has_system_access` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_id`, `badge_number`, `first_name`, `last_name`, `father_name`, `mother_name`, `date_of_birth`, `gender`, `marital_status`, `religion`, `nationality`, `cnic`, `cnic_expiry`, `blood_group`, `photo`, `personal_phone`, `office_phone`, `personal_email`, `office_email`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relation`, `present_address`, `permanent_address`, `city`, `province`, `postal_code`, `department`, `designation`, `job_grade`, `employment_type`, `employment_status`, `joining_date`, `confirmation_date`, `contract_end_date`, `resignation_date`, `termination_date`, `termination_reason`, `shift`, `shift_start`, `shift_end`, `weekly_hours`, `working_days`, `highest_qualification`, `specialization`, `institution`, `graduation_year`, `total_experience_years`, `previous_employer`, `previous_designation`, `bank_name`, `bank_account_number`, `bank_branch`, `iban`, `salary_type`, `basic_salary`, `ntn_number`, `eobi_number`, `socso_number`, `is_tax_filer`, `has_system_access`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'EMP-00001', NULL, 'Samin', 'Afridi', 'Hussain Gul', NULL, '2000-01-01', 'Male', 'Married', 'Islam', 'Pakistani', '17320-0123456', NULL, 'AB+', NULL, '03334504157', '03335583974', 'samin@gmail.com', 'saminhos@gmail.com', 'Hussain Gul', '03335583974', 'Father', 'dora school suffaid dheri khatama nabowat chowk peshawar', 'malak din khyl umar khan khyl nala khur bara', 'KHYBER', 'KPK', '25000', 'Clinical — Surgery', 'Senior Medical Officer', 'BPS-17-GRAD', 'Permanent', 'Active', '2020-02-01', '2021-01-01', NULL, NULL, NULL, NULL, 'Morning', '08:30:00', '16:30:00', 48, '[\"Mon\",\"Tue\",\"Wed\",\"Thu\",\"Fri\",\"Sat\"]', 'MBBS FCPS', 'Medical Specilist', 'Sarhad University Of Science and Information Technology', '2000', 15, 'Shaukat Khanam', NULL, 'The Bank Of Khyber', '3003950227', '0128', 'PK76KHYB0128003003950227', 'Monthly', 200000.00, NULL, NULL, NULL, 0, 0, NULL, '2026-05-10 01:19:42', '2026-06-09 21:19:45', NULL),
(2, 'EMP-000021', NULL, 'Ahmed', 'Khan', 'Bashir Khan', NULL, '1985-05-12', 'Male', 'Married', NULL, 'Pakistani', '4210112345671', NULL, NULL, 'employees/PLeADXzUKX3DQY9ceGgeCHAgGS2uoQPBg0asasFU.jpg', '03001234567', NULL, NULL, 'ahmed.cardio@hospital.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Clinical — Cardiology', 'Consultant', NULL, 'Permanent', 'Active', '2020-01-15', NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, NULL, 48, '[]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 160000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-06-09 21:06:55', NULL),
(3, 'EMP-00002', NULL, 'Sara', 'Ali', 'Zulfiqar Ali', NULL, '1988-08-22', 'Female', 'Married', NULL, 'Pakistani', '4220198765432', NULL, NULL, NULL, '03217654321', NULL, NULL, 'sara.gyn@hospital.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Clinical — Surgery', 'Specialist', NULL, 'Permanent', 'Active', '2021-03-10', NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, NULL, 48, '[]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 220000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-05-10 19:54:44', NULL),
(4, 'EMP-00003', NULL, 'Bilal', 'Malik', 'Abdul Malik', NULL, '1990-11-05', 'Male', 'Single', NULL, 'Pakistani', '3520211223344', NULL, NULL, 'employees/bOsyADiTtQwZMeIeVMEFGD64YuArwPegGpBZK1Dr.png', '03334455667', NULL, NULL, 'bilal.mo@hospital.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Clinical — General OPD', 'Medical Officer', NULL, 'Permanent', 'Active', '2022-06-01', NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, NULL, 48, '[]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 120000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-06-01 20:43:01', NULL),
(5, 'EMP-00004', NULL, 'Zoya', 'Hassan', 'Hassan Mehmood', NULL, '1987-02-14', 'Female', 'Married', NULL, 'Pakistani', '6110155667788', NULL, NULL, NULL, '03451122334', NULL, NULL, 'zoya.peds@hospital.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Clinical — Pediatrics', 'Senior Medical Officer', NULL, 'Permanent', 'Active', '2019-11-20', NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, NULL, 48, '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 180000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-05-11 00:46:39', NULL),
(6, 'EMP-00005', NULL, 'Usman', 'Shah', 'Syed Shah', NULL, '1983-09-30', 'Male', 'Married', NULL, 'Pakistani', '3740599887766', NULL, NULL, NULL, '03120099887', NULL, NULL, 'usman.ortho@hospital.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Clinical — Orthopedic', 'Specialist', NULL, 'Permanent', 'Active', '2018-05-12', NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, NULL, 48, '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 210000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-05-11 00:46:39', NULL),
(7, 'EMP-00006', NULL, 'Maria', 'Qureshi', 'Irfan Qureshi', NULL, '1992-04-18', 'Female', 'Single', NULL, 'Pakistani', '3310244556677', NULL, NULL, NULL, '03012233445', NULL, NULL, 'maria.anes@hospital.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Clinical — Anesthesia', 'Anesthesiologist', NULL, 'Permanent', 'Active', '2023-01-05', NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, NULL, 48, '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 190000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-05-11 00:46:39', NULL),
(8, 'EMP-00007', NULL, 'Faisal', 'Aziz', 'Aziz Ahmed', NULL, '1989-12-25', 'Male', 'Married', NULL, 'Pakistani', '4130311223344', NULL, NULL, NULL, '03009876543', NULL, NULL, 'faisal.icu@hospital.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Clinical — ICU', 'Medical Officer', NULL, 'Permanent', 'Active', '2022-02-28', NULL, NULL, NULL, NULL, NULL, 'Night', NULL, NULL, 48, '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 125000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-05-11 00:46:39', NULL),
(9, 'EMP-00008', NULL, 'Kamran', 'Akmal', 'M. Akmal', NULL, '1980-01-01', 'Male', 'Married', NULL, 'Pakistani', '3520188776655', NULL, NULL, NULL, '03221122334', NULL, NULL, 'kamran.hr@hospital.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Human Resources', 'HR Manager', NULL, 'Permanent', 'Active', '2015-06-15', NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, NULL, 48, '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 95000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-05-11 00:46:39', NULL),
(10, 'EMP-00009', NULL, 'Asma', 'Javed', 'Javed Iqbal', NULL, '1994-03-10', 'Female', 'Single', NULL, 'Pakistani', '3520177665544', NULL, NULL, NULL, '03215544332', NULL, NULL, 'asma.nurse@hospital.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Nursing', 'Head Nurse', NULL, 'Permanent', 'Active', '2018-09-10', NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, NULL, 48, '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 85000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-05-11 00:46:39', NULL),
(11, 'EMP-00010', NULL, 'Tariq', 'Mahmood', 'Sultan Mahmood', NULL, '1975-07-20', 'Male', 'Married', NULL, 'Pakistani', '3410122334455', NULL, NULL, NULL, '03004433221', NULL, NULL, 'tariq.fin@hospital.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Finance & Accounts', 'CFO', NULL, 'Permanent', 'Active', '2010-01-01', NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, NULL, 48, '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 180000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-05-11 00:46:39', NULL),
(12, 'EMP-00011', NULL, 'Saima', 'Raza', 'Raza Ali', NULL, '1995-05-05', 'Female', 'Single', NULL, 'Pakistani', '3110233445566', NULL, NULL, NULL, '03316655443', NULL, NULL, 'saima.pharma@hospital.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pharmacy', 'Pharmacist', NULL, 'Permanent', 'Active', '2023-05-20', NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, NULL, 48, '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 70000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-05-11 00:46:39', NULL),
(13, 'EMP-00012', NULL, 'Irfan', 'Haider', 'Haider Abbas', NULL, '1991-10-12', 'Male', 'Married', NULL, 'Pakistani', '3840344556677', NULL, NULL, NULL, '03441122334', NULL, NULL, 'irfan.lab@hospital.com', NULL, NULL, NULL, NULL, NULL, 'Khyber Agency', NULL, NULL, 'Laboratory', 'Lab Technician', NULL, 'Permanent', 'Active', '2020-08-15', NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, NULL, 48, '[]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 45000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-06-03 23:38:50', NULL),
(14, 'EMP-00013', NULL, 'Noman', 'Ijaz', 'Ijaz Ahmed', NULL, '1996-12-30', 'Male', 'Single', NULL, 'Pakistani', '1210155667788', NULL, NULL, NULL, '03135544332', NULL, NULL, 'noman.it@hospital.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Information Technology', 'Data Entry Operator', NULL, 'Permanent', 'Active', '2024-01-10', NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, NULL, 48, '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 40000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-05-11 00:46:39', NULL),
(15, 'EMP-00014', NULL, 'Sadia', 'Imran', 'Imran Khan', NULL, '1998-06-25', 'Female', 'Single', NULL, 'Pakistani', '1730166778899', NULL, NULL, NULL, '03006677889', NULL, NULL, 'sadia.front@hospital.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Administration', 'Receptionist', NULL, 'Permanent', 'Active', '2023-11-01', NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, NULL, 48, '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 35000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-05-11 00:46:39', NULL),
(16, 'EMP-00015', NULL, 'Gul', 'Khan', 'Sher Khan', NULL, '1985-02-02', 'Male', 'Married', NULL, 'Pakistani', '1560277889900', NULL, NULL, NULL, '03159988776', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Security', 'Security Guard', NULL, 'Permanent', 'Active', '2017-03-20', NULL, NULL, NULL, NULL, NULL, 'Night', NULL, NULL, 48, '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 28000.00, NULL, NULL, NULL, 0, 0, NULL, '2026-05-11 00:46:39', '2026-05-11 00:46:39', NULL),
(17, 'EMP-00016', NULL, 'Pervaiz', 'Masih', 'Sadiq Masih', NULL, '1982-04-04', 'Male', 'Married', NULL, 'Pakistani', '3520288990011', NULL, NULL, NULL, '03331122334', NULL, NULL, 'shehreyar882@gmail.com', NULL, NULL, NULL, NULL, NULL, 'KHYBER', NULL, NULL, 'Clinical — Radiology', 'Radiographer', NULL, 'Permanent', 'Active', '2016-05-15', NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, NULL, 48, '[]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 25000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-06-04 19:47:33', NULL),
(18, 'EMP-00017', NULL, 'Shazia', 'Bibi', 'M. Ramzan', NULL, '1993-09-09', 'Female', 'Married', NULL, 'Pakistani', '3540499001122', NULL, NULL, NULL, '03214455667', NULL, NULL, 'shazia.nurse@hospital.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Nursing', 'Staff Nurse', NULL, 'Permanent', 'Active', '2021-07-22', NULL, NULL, NULL, NULL, NULL, 'Evening', NULL, NULL, 48, '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 55000.00, NULL, NULL, NULL, 0, 1, NULL, '2026-05-11 00:46:39', '2026-05-11 00:46:39', NULL),
(19, 'EMP-00018', NULL, 'Rashid', 'Minhas', 'Minhas Ahmed', NULL, '1980-11-11', 'Male', 'Married', NULL, 'Pakistani', '4220111223344', NULL, NULL, NULL, '03027788990', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ambulance & Transport', 'Driver', NULL, 'Permanent', 'Active', '2014-02-10', NULL, NULL, NULL, NULL, NULL, 'Rotating', NULL, NULL, 48, '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 32000.00, NULL, NULL, NULL, 0, 0, NULL, '2026-05-11 00:46:39', '2026-05-11 00:46:39', NULL),
(20, 'EMP-00019', NULL, 'Mehmood', 'Bhatti', 'Nazir Bhatti', NULL, '1988-03-03', 'Male', 'Married', NULL, 'Pakistani', '3520133445566', NULL, NULL, NULL, '03001122998', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Maintenance', 'Electrician', NULL, 'Permanent', 'Active', '2019-10-05', NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, NULL, 48, '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 35000.00, NULL, NULL, NULL, 0, 0, NULL, '2026-05-11 00:46:39', '2026-05-11 00:46:39', NULL),
(21, 'EMP-00020', NULL, 'Farhan', 'Saeed', 'Saeed Ahmed', NULL, '1997-01-01', 'Male', 'Single', NULL, 'Pakistani', '3310155443322', NULL, NULL, NULL, '03450099112', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Clinical — Emergency', 'Ward Boy', NULL, 'Permanent', 'Active', '2024-02-01', NULL, NULL, NULL, NULL, NULL, 'Rotating', NULL, NULL, 48, '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Monthly', 26000.00, NULL, NULL, NULL, 0, 0, NULL, '2026-05-11 00:46:39', '2026-05-11 00:46:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `date_to` date DEFAULT NULL,
  `total_days` int(11) NOT NULL DEFAULT 1,
  `type` enum('Public Holiday','National Holiday','Religious Holiday','Hospital Holiday','Optional') NOT NULL DEFAULT 'Public Holiday',
  `year` year(4) NOT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `name`, `date`, `date_to`, `total_days`, `type`, `year`, `is_recurring`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(14, 'Kashmir Day', '2025-02-05', NULL, 1, 'National Holiday', '2025', 1, 'Solidarity with people of Kashmir.', 1, '2026-06-11 10:19:05', '2026-06-11 10:19:05'),
(15, 'Pakistan Day', '2025-03-23', NULL, 1, 'National Holiday', '2025', 1, 'Commemorating the Lahore Resolution.', 1, '2026-06-11 10:19:05', '2026-06-11 10:19:05'),
(16, 'Eid-ul-Fitr', '2025-03-31', '2025-04-02', 3, 'Religious Holiday', '2025', 0, 'Islamic festival after Ramadan.', 1, '2026-06-11 10:19:05', '2026-06-11 10:19:05'),
(17, 'World Health Day', '2025-04-07', NULL, 1, 'Hospital Holiday', '2025', 1, 'Special recognition day for healthcare staff.', 1, '2026-06-11 10:19:05', '2026-06-11 10:19:05'),
(18, 'Labor Day', '2025-05-01', NULL, 1, 'Public Holiday', '2025', 1, 'International Workers Day.', 1, '2026-06-11 10:19:05', '2026-06-11 10:19:05'),
(19, 'Eid-ul-Adha', '2025-06-07', '2025-06-09', 3, 'Religious Holiday', '2025', 0, 'Festival of Sacrifice.', 1, '2026-06-11 10:19:05', '2026-06-11 10:19:05'),
(20, 'Ashura', '2025-07-06', '2025-07-07', 2, 'Religious Holiday', '2025', 0, '9th and 10th of Moharram.', 1, '2026-06-11 10:19:05', '2026-06-11 10:19:05'),
(21, 'Independence Day', '2025-08-14', NULL, 1, 'National Holiday', '2025', 1, 'Pakistan Independence Day.', 1, '2026-06-11 10:19:05', '2026-06-11 10:19:05'),
(22, 'Eid Milad-un-Nabi', '2025-09-05', NULL, 1, 'Religious Holiday', '2025', 0, 'Birth of Prophet Muhammad (PBUH).', 1, '2026-06-11 10:19:05', '2026-06-11 10:19:05'),
(23, 'Iqbal Day', '2025-11-09', NULL, 1, 'National Holiday', '2025', 1, 'Birthday of Allama Iqbal.', 1, '2026-06-11 10:19:05', '2026-06-11 10:19:05'),
(24, 'Quaid-e-Azam Day', '2025-12-25', NULL, 1, 'National Holiday', '2025', 1, 'Birthday of Quaid-e-Azam and Christmas.', 1, '2026-06-11 10:19:05', '2026-06-11 10:19:05'),
(25, 'New Year Holiday', '2026-01-01', NULL, 1, 'Optional', '2026', 1, 'Optional holiday for staff.', 1, '2026-06-11 10:19:05', '2026-06-11 10:19:05'),
(26, 'Kashmir Day', '2026-02-05', NULL, 1, 'National Holiday', '2026', 1, 'Solidarity with people of Kashmir.', 1, '2026-06-11 10:47:28', '2026-06-11 10:47:28'),
(27, 'Pakistan Day', '2026-03-23', NULL, 1, 'National Holiday', '2026', 1, 'Commemorating the Lahore Resolution.', 1, '2026-06-11 10:47:28', '2026-06-11 10:47:28'),
(28, 'Eid-ul-Fitr', '2026-03-20', '2026-03-22', 3, 'Religious Holiday', '2026', 0, 'Islamic festival (Tentative).', 1, '2026-06-11 10:47:28', '2026-06-11 10:47:28'),
(29, 'World Health Day', '2026-04-07', NULL, 1, 'Hospital Holiday', '2026', 1, 'Special recognition day for healthcare staff.', 1, '2026-06-11 10:47:28', '2026-06-11 10:47:28'),
(30, 'Labor Day', '2026-05-01', NULL, 1, 'Public Holiday', '2026', 1, 'International Workers Day.', 1, '2026-06-11 10:47:28', '2026-06-11 10:47:28'),
(31, 'Eid-ul-Adha', '2026-05-27', '2026-05-29', 3, 'Religious Holiday', '2026', 0, 'Festival of Sacrifice (Tentative).', 1, '2026-06-11 10:47:28', '2026-06-11 10:47:28'),
(32, 'Ashura', '2026-06-25', '2026-06-26', 2, 'Religious Holiday', '2026', 0, '9th and 10th of Moharram (Tentative).', 1, '2026-06-11 10:47:28', '2026-06-11 10:47:28'),
(33, 'Independence Day', '2026-08-14', NULL, 1, 'National Holiday', '2026', 1, 'Pakistan Independence Day.', 1, '2026-06-11 10:47:28', '2026-06-11 10:47:28'),
(34, 'Eid Milad-un-Nabi', '2026-08-26', NULL, 1, 'Religious Holiday', '2026', 0, 'Birth of Prophet Muhammad (PBUH) - Tentative.', 1, '2026-06-11 10:47:28', '2026-06-11 10:47:28'),
(35, 'Iqbal Day', '2026-11-09', NULL, 1, 'National Holiday', '2026', 1, 'Birthday of Allama Iqbal.', 1, '2026-06-11 10:47:28', '2026-06-11 10:47:28'),
(36, 'Quaid-e-Azam Day', '2026-12-25', NULL, 1, 'National Holiday', '2026', 1, 'Birthday of Quaid-e-Azam and Christmas.', 1, '2026-06-11 10:47:28', '2026-06-11 10:47:28');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_orders`
--

CREATE TABLE `lab_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `appointment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_date` datetime NOT NULL,
  `priority` enum('Routine','Urgent','STAT') NOT NULL DEFAULT 'Routine',
  `status` enum('Pending','Sample Collected','Processing','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('Unpaid','Partial','Paid') NOT NULL DEFAULT 'Unpaid',
  `report_delivered` tinyint(1) NOT NULL DEFAULT 0,
  `report_delivered_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lab_orders`
--

INSERT INTO `lab_orders` (`id`, `order_number`, `patient_id`, `doctor_id`, `appointment_id`, `order_date`, `priority`, `status`, `total_amount`, `discount`, `paid_amount`, `payment_status`, `report_delivered`, `report_delivered_at`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'LAB-00001', 1, 1, NULL, '2026-05-19 10:29:00', 'Routine', 'Completed', 1500.00, 0.00, 1500.00, 'Paid', 1, '2026-05-19 00:33:03', NULL, '2026-05-19 00:30:11', '2026-05-21 20:01:02', NULL),
(2, 'LAB-00002', 44, 1, NULL, '2026-05-22 15:28:00', 'Routine', 'Completed', 1500.00, 0.00, 0.00, 'Unpaid', 1, '2026-06-03 08:26:18', 'Urgent', '2026-05-22 05:30:26', '2026-06-03 08:26:18', NULL),
(3, 'LAB-00003', 63, 2, NULL, '2026-06-03 18:13:00', 'Routine', 'Completed', 1500.00, 0.00, 1500.00, 'Paid', 1, '2026-06-03 08:26:22', NULL, '2026-06-03 08:14:42', '2026-06-11 11:38:20', NULL),
(4, 'LAB-00004', 35, 9, NULL, '2026-06-04 06:21:00', 'Urgent', 'Completed', 1500.00, 0.00, 1500.00, 'Paid', 1, '2026-06-04 00:38:23', 'perfrom the test urgently', '2026-06-03 20:22:10', '2026-06-05 00:54:47', NULL),
(5, 'LAB-00005', 35, 9, NULL, '2026-06-04 06:21:00', 'Urgent', 'Completed', 1500.00, 0.00, 1500.00, 'Paid', 1, '2026-06-04 00:39:24', 'perfrom the test urgently', '2026-06-03 20:23:02', '2026-06-05 00:54:47', NULL),
(6, 'LAB-00006', 63, 9, NULL, '2026-06-04 09:23:00', 'Urgent', 'Completed', 1500.00, 0.00, 1500.00, 'Paid', 1, '2026-06-04 00:19:48', 'perfrom the test urgently', '2026-06-03 23:23:49', '2026-06-11 11:38:20', NULL),
(9, 'LAB-00007', 38, 9, NULL, '2026-06-15 10:10:00', 'Routine', 'Pending', 500.00, 0.00, 0.00, 'Unpaid', 0, NULL, NULL, '2026-06-15 05:18:50', '2026-06-15 05:18:50', NULL),
(10, 'LAB-00010', 1, 9, NULL, '2026-06-15 10:20:00', 'Routine', 'Pending', 500.00, 0.00, 0.00, 'Unpaid', 0, NULL, NULL, '2026-06-15 05:20:37', '2026-06-15 05:20:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lab_order_items`
--

CREATE TABLE `lab_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lab_order_id` bigint(20) UNSIGNED NOT NULL,
  `lab_test_id` bigint(20) UNSIGNED NOT NULL,
  `lab_sample_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `final_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('Pending','Processing','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `technician_name` varchar(255) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lab_order_items`
--

INSERT INTO `lab_order_items` (`id`, `lab_order_id`, `lab_test_id`, `lab_sample_id`, `price`, `discount`, `final_price`, `status`, `technician_name`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 1, 1500.00, 0.00, 1500.00, 'Completed', 'Lab Staff', '2026-05-19 00:32:35', '2026-05-19 00:30:11', '2026-05-19 00:32:35'),
(2, 2, 1, 2, 500.00, 0.00, 500.00, 'Completed', 'Lab Staff', '2026-05-22 05:33:16', '2026-05-22 05:30:26', '2026-05-22 05:33:16'),
(3, 2, 2, 2, 1000.00, 0.00, 1000.00, 'Completed', 'Lab Staff', '2026-05-22 05:33:17', '2026-05-22 05:30:26', '2026-05-22 05:33:17'),
(4, 3, 1, 3, 500.00, 0.00, 500.00, 'Completed', 'Bilal Malik', '2026-06-03 08:15:59', '2026-06-03 08:14:42', '2026-06-03 08:15:59'),
(5, 3, 2, 3, 1000.00, 0.00, 1000.00, 'Completed', 'Bilal Malik', '2026-06-03 08:15:59', '2026-06-03 08:14:42', '2026-06-03 08:15:59'),
(6, 4, 1, 5, 500.00, 0.00, 500.00, 'Completed', 'Irfan Haider', '2026-06-04 00:38:12', '2026-06-03 20:22:10', '2026-06-04 00:38:12'),
(7, 4, 2, 5, 1000.00, 0.00, 1000.00, 'Completed', 'Irfan Haider', '2026-06-04 00:38:12', '2026-06-03 20:22:10', '2026-06-04 00:38:12'),
(8, 5, 1, 6, 500.00, 0.00, 500.00, 'Completed', 'Irfan Haider', '2026-06-04 00:39:19', '2026-06-03 20:23:02', '2026-06-04 00:39:19'),
(9, 5, 2, 6, 1000.00, 0.00, 1000.00, 'Completed', 'Irfan Haider', '2026-06-04 00:39:19', '2026-06-03 20:23:02', '2026-06-04 00:39:19'),
(10, 6, 1, 4, 500.00, 0.00, 500.00, 'Completed', 'Super Admin', '2026-06-04 00:19:32', '2026-06-03 23:23:49', '2026-06-04 00:19:32'),
(11, 6, 2, 4, 1000.00, 0.00, 1000.00, 'Completed', 'Super Admin', '2026-06-04 00:19:32', '2026-06-03 23:23:49', '2026-06-04 00:19:32'),
(14, 9, 1, NULL, 500.00, 0.00, 500.00, 'Pending', NULL, NULL, '2026-06-15 05:18:50', '2026-06-15 05:18:50'),
(15, 10, 1, NULL, 500.00, 0.00, 500.00, 'Pending', NULL, NULL, '2026-06-15 05:20:37', '2026-06-15 05:20:37');

-- --------------------------------------------------------

--
-- Table structure for table `lab_results`
--

CREATE TABLE `lab_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lab_order_item_id` bigint(20) UNSIGNED NOT NULL,
  `result_value` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `normal_range` varchar(255) DEFAULT NULL,
  `flag` enum('Normal','High','Low','Critical High','Critical Low') DEFAULT NULL,
  `is_abnormal` tinyint(1) NOT NULL DEFAULT 0,
  `previous_value` varchar(255) DEFAULT NULL,
  `previous_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `reported_at` datetime DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lab_results`
--

INSERT INTO `lab_results` (`id`, `lab_order_item_id`, `result_value`, `unit`, `normal_range`, `flag`, `is_abnormal`, `previous_value`, `previous_date`, `remarks`, `reported_at`, `verified_by`, `verified_at`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 1, '20%', 'mg/dl', '36% - 48%', NULL, 0, NULL, NULL, 'This test is perform for testing purpose', '2026-05-19 10:32:35', NULL, '2026-05-19 00:32:54', 1, '2026-05-19 00:32:35', '2026-05-19 00:32:54'),
(2, 2, '2.5', 'mg/dl', '4.5 - 11.0 x 10⁹/L (WBC)', NULL, 0, NULL, NULL, 'platilits are less in the amount', '2026-05-22 15:33:16', NULL, '2026-05-22 05:33:22', 1, '2026-05-22 05:33:16', '2026-05-22 05:33:22'),
(3, 3, '4.5', 'mg/dl', '12.0 - 16.0 g/dL', NULL, 0, NULL, NULL, 'Hemoglobin', '2026-05-22 15:33:17', NULL, '2026-05-22 05:33:26', 1, '2026-05-22 05:33:17', '2026-05-22 05:33:26'),
(4, 4, '44', 'mg', '4.5 - 11.0 x 10⁹/L (WBC)', NULL, 0, NULL, NULL, 'issue', '2026-06-03 18:15:59', 3, '2026-06-03 08:16:29', 1, '2026-06-03 08:15:59', '2026-06-03 08:16:29'),
(5, 5, '5.9', 'mg/dl', '12.0 - 16.0 g/dL', NULL, 0, NULL, NULL, NULL, '2026-06-03 18:15:59', 3, '2026-06-03 08:16:32', 1, '2026-06-03 08:15:59', '2026-06-03 08:16:32'),
(6, 10, '5.6 positive', 'mg/dl', '4.5 - 11.0 x 10⁹/L (WBC)', NULL, 0, NULL, NULL, 'This test is perform for testing purpose', '2026-06-04 10:19:32', 3, '2026-06-04 00:19:40', 1, '2026-06-04 00:19:32', '2026-06-04 00:19:40'),
(7, 11, '32', 'mg/dl', '12.0 - 16.0 g/dL', NULL, 0, NULL, NULL, 'critical finding have been seen', '2026-06-04 10:19:32', 3, '2026-06-04 00:19:42', 1, '2026-06-04 00:19:32', '2026-06-04 00:19:42'),
(8, 6, '5.9', 'mg/dl', '4.5 - 11.0 x 10⁹/L (WBC)', NULL, 0, NULL, NULL, 'positive', '2026-06-04 10:38:12', 8, '2026-06-04 00:38:15', 1, '2026-06-04 00:38:12', '2026-06-04 00:38:15'),
(9, 7, '3.9', 'mg/dl', '12.0 - 16.0 g/dL', NULL, 0, NULL, NULL, 'positive', '2026-06-04 10:38:12', 8, '2026-06-04 00:38:17', 1, '2026-06-04 00:38:12', '2026-06-04 00:38:17'),
(10, 8, '5.9', 'mg/dl', '4.5 - 11.0 x 10⁹/L (WBC)', NULL, 0, NULL, NULL, 'positive', '2026-06-04 10:39:19', 8, '2026-06-04 00:39:27', 1, '2026-06-04 00:39:19', '2026-06-04 00:39:27'),
(11, 9, '3.9', 'mg/dl', '12.0 - 16.0 g/dL', NULL, 0, NULL, NULL, 'positive', '2026-06-04 10:39:19', 8, '2026-06-04 00:39:31', 1, '2026-06-04 00:39:19', '2026-06-04 00:39:31');

-- --------------------------------------------------------

--
-- Table structure for table `lab_samples`
--

CREATE TABLE `lab_samples` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sample_number` varchar(255) NOT NULL,
  `lab_order_id` bigint(20) UNSIGNED NOT NULL,
  `sample_type_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('Pending','Collected','Received','In Process','Completed','Rejected') NOT NULL DEFAULT 'Pending',
  `collected_at` datetime DEFAULT NULL,
  `received_at` datetime DEFAULT NULL,
  `processed_at` datetime DEFAULT NULL,
  `collected_by` varchar(255) DEFAULT NULL,
  `rejection_reason` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lab_samples`
--

INSERT INTO `lab_samples` (`id`, `sample_number`, `lab_order_id`, `sample_type_id`, `status`, `collected_at`, `received_at`, `processed_at`, `collected_by`, `rejection_reason`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'SMP-00001', 1, 3, 'Collected', '2026-05-19 10:31:54', NULL, NULL, 'shehreyar khan', NULL, NULL, '2026-05-19 00:31:54', '2026-05-19 00:31:54'),
(2, 'SMP-00002', 2, 1, 'Collected', '2026-05-22 15:30:59', NULL, NULL, 'shehreyar khan', NULL, NULL, '2026-05-22 05:30:59', '2026-05-22 05:30:59'),
(3, 'SMP-00003', 3, 1, 'Collected', '2026-06-03 18:14:57', NULL, NULL, 'shehreyar khan', NULL, NULL, '2026-06-03 08:14:57', '2026-06-03 08:14:57'),
(4, 'SMP-00004', 6, 1, 'Collected', '2026-06-04 10:19:06', NULL, NULL, 'shehreyar khan', NULL, NULL, '2026-06-04 00:19:06', '2026-06-04 00:19:06'),
(5, 'SMP-00005', 4, 1, 'Collected', '2026-06-04 10:37:31', NULL, NULL, 'khan', NULL, NULL, '2026-06-04 00:37:31', '2026-06-04 00:37:31'),
(6, 'SMP-00006', 5, 5, 'Collected', '2026-06-04 10:39:12', NULL, NULL, 'khan', NULL, NULL, '2026-06-04 00:39:12', '2026-06-04 00:39:12');

-- --------------------------------------------------------

--
-- Table structure for table `lab_sample_types`
--

CREATE TABLE `lab_sample_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `container_type` varchar(255) DEFAULT NULL,
  `color_code` varchar(255) DEFAULT NULL,
  `volume_required` int(11) DEFAULT NULL,
  `requires_fasting` tinyint(1) NOT NULL DEFAULT 0,
  `collection_instructions` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lab_sample_types`
--

INSERT INTO `lab_sample_types` (`id`, `name`, `code`, `container_type`, `color_code`, `volume_required`, `requires_fasting`, `collection_instructions`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Blood', 'SMP-BLD', 'Vacutainer', 'Red', 5, 1, 'Collect 5ml blood in red top vacutainer. Allow to clot for 30 mins before centrifugation.', 'Standard serum sample for biochemistry tests', 1, '2026-05-19 04:31:48', '2026-05-18 23:41:13'),
(2, 'Blood EDTA', 'SMP-BLD-EDTA', 'Vacutainer', 'Purple', 3, 0, 'Collect 3ml blood in purple top EDTA tube. Gently invert 8-10 times. Do not freeze.', 'Used for CBC, HbA1c, and other hematology tests', 1, '2026-05-19 04:31:48', '2026-05-19 04:31:48'),
(3, 'Blood Glucose', 'SMP-GLU', 'Vacutainer', 'Grey', 2, 1, 'Patient must fast 8-12 hours. Collect 2ml in fluoride oxalate tube. Mix gently.', 'Fasting blood glucose test sample', 1, '2026-05-19 04:31:48', '2026-05-19 04:31:48'),
(4, 'Urine', 'SMP-URN', 'Sterile Cup', 'Yellow', 30, 0, 'Collect mid-stream urine in sterile container. Label immediately. Refrigerate if not tested within 2 hours.', 'Random urine sample for routine analysis', 1, '2026-05-19 04:31:48', '2026-05-19 04:31:48'),
(5, 'Urine 24hr', 'SMP-URN-24', 'Large Container', 'Yellow', 2000, 0, 'Discard first morning urine. Collect all urine for next 24 hours in provided container. Keep refrigerated.', '24-hour urine collection for protein/creatinine clearance', 1, '2026-05-19 04:31:48', '2026-05-19 04:31:48'),
(6, 'Stool', 'SMP-STL', 'Sterile Container', 'White', 10, 0, 'Collect walnut-sized sample in sterile container. Avoid urine/water contamination. Deliver within 2 hours.', 'Stool sample for ova, parasites, and culture', 1, '2026-05-19 04:31:48', '2026-05-19 04:31:48'),
(7, 'Swab', 'SMP-SWB', 'Transport Tube', 'Blue', NULL, 0, 'Rotate swab firmly on affected area. Place in transport media immediately. Do not refrigerate.', 'For bacterial/viral culture and PCR tests', 1, '2026-05-19 04:31:49', '2026-05-19 04:31:49'),
(8, 'CSF', 'SMP-CSF', 'Sterile Tube', 'Clear', 2, 0, 'Collect via lumbar puncture in sterile numbered tubes. Send to lab immediately on ice.', 'Cerebrospinal fluid for meningitis workup', 1, '2026-05-19 04:31:49', '2026-05-19 04:31:49');

-- --------------------------------------------------------

--
-- Table structure for table `lab_tests`
--

CREATE TABLE `lab_tests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `test_code` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `sample_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `normal_range` varchar(255) DEFAULT NULL,
  `normal_range_male` varchar(255) DEFAULT NULL,
  `normal_range_female` varchar(255) DEFAULT NULL,
  `normal_range_child` varchar(255) DEFAULT NULL,
  `normal_range_elderly` varchar(255) DEFAULT NULL,
  `turnaround_hours` int(11) NOT NULL DEFAULT 24,
  `method` varchar(255) DEFAULT NULL,
  `requires_fasting` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lab_tests`
--

INSERT INTO `lab_tests` (`id`, `name`, `test_code`, `category_id`, `sample_type_id`, `price`, `unit`, `normal_range`, `normal_range_male`, `normal_range_female`, `normal_range_child`, `normal_range_elderly`, `turnaround_hours`, `method`, `requires_fasting`, `is_active`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Complete Blood Count (CBC)', 'T-0001', 13, 1, 500.00, 'mg/dl', '4.5 - 11.0 x 10⁹/L (WBC)', 'RBC: 4.5-5.9 million/µL; Hb: 13.2-16.6 g/dL; Hct: 38.3-48.6%', 'RBC: 3.92-5.13 million/µL; Hb: 11.6-15.0 g/dL; Hct: 35.5-44.9%', 'WBC: 5,000-10,000/µL; RBC varies by age', 'Similar to adults, slight decrease in Hb acceptable', 1, 'ELISA', 0, 1, 'This test will show the complete blood profile', '2026-05-18 23:53:45', '2026-05-18 23:53:45'),
(2, 'Hemoglobin (Hb)', 'T-0002', 13, 2, 1000.00, 'mg/dl', '12.0 - 16.0 g/dL', '13.2 - 16.6 g/dL (13.8-17.2 g/dL)', '11.6 - 15.0 g/dL (12.1-15.1 g/dL)', '11.0 - 14.3 g/dL (varies by age)', 'Male: 12.0-16.0; Female: 11.0-15.0 g/dL', 2, 'PCR', 0, 1, NULL, '2026-05-18 23:55:53', '2026-05-18 23:55:53'),
(3, 'Packed Cell Volume (PCV/Hematocrit)', 'T-0003', 11, 2, 1500.00, 'mg/dl', '36% - 48%', '38.3% - 48.6% (40-52%)', '35.5% - 44.9% (36-48%)', '32% - 42%', 'Male: 35-47%; Female: 33-45%', 1, 'ELISA', 0, 1, NULL, '2026-05-18 23:57:18', '2026-05-18 23:57:18'),
(4, 'Total Leukocyte Count (TLC)', 'T-0004', 5, 3, 300.00, 'mg/dl', '4,000 - 11,000 /µL (4.5-11.0 x 10⁹/L)', '3,400 - 9,600 /µL', '3,400 - 9,600 /µL', '5,000 - 10,000 /µL (higher in infants)', '3,400 - 9,600 /µL', 3, 'PCR', 0, 1, NULL, '2026-05-18 23:58:37', '2026-05-18 23:58:37');

-- --------------------------------------------------------

--
-- Table structure for table `lab_test_categories`
--

CREATE TABLE `lab_test_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lab_test_categories`
--

INSERT INTO `lab_test_categories` (`id`, `name`, `code`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'Biochemistry', 'CAT-002', 'Chemical analysis of body fluids for metabolic, renal, liver, and electrolyte assessment', 1, '2026-05-19 04:45:03', '2026-05-19 04:45:03'),
(4, 'Microbiology', 'CAT-003', 'Culture, sensitivity, and identification of bacteria, fungi, and other microorganisms', 1, '2026-05-19 04:45:03', '2026-05-19 04:45:03'),
(5, 'Immunology', 'CAT-004', 'Tests for antibodies, antigens, autoimmune disorders, and immune system function', 1, '2026-05-19 04:45:03', '2026-05-19 04:45:03'),
(6, 'Endocrinology', 'CAT-005', 'Hormone level testing for thyroid, adrenal, reproductive, and metabolic disorders', 1, '2026-05-19 04:45:03', '2026-05-19 04:45:03'),
(7, 'Urinalysis', 'CAT-006', 'Physical, chemical, and microscopic examination of urine for renal and metabolic evaluation', 1, '2026-05-19 04:45:03', '2026-05-19 04:45:03'),
(8, 'Coagulation', 'CAT-007', 'Tests for blood clotting function including PT, aPTT, INR, and D-Dimer', 1, '2026-05-19 04:45:03', '2026-05-19 04:45:03'),
(9, 'Molecular', 'CAT-008', 'DNA/RNA based testing for genetic disorders, infectious diseases, and oncology markers', 1, '2026-05-19 04:45:03', '2026-05-19 04:45:03'),
(10, 'Toxicology', 'CAT-009', 'Detection and quantification of drugs, alcohol, poisons, and therapeutic drug monitoring', 1, '2026-05-19 04:45:03', '2026-05-19 04:45:03'),
(11, 'Histopathology', 'CAT-010', 'Microscopic examination of tissue biopsies for disease diagnosis and cancer detection', 1, '2026-05-19 04:45:03', '2026-05-19 04:45:03'),
(13, 'Hematology', 'CAT-001', 'Tests related to blood cells, hemoglobin, coagulation, and blood disorders', 1, '2026-05-19 04:45:32', '2026-05-19 04:45:32');

-- --------------------------------------------------------

--
-- Table structure for table `leave_balances`
--

CREATE TABLE `leave_balances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `leave_type_id` bigint(20) UNSIGNED NOT NULL,
  `year` year(4) NOT NULL,
  `entitled_days` decimal(5,1) NOT NULL DEFAULT 0.0,
  `used_days` decimal(5,1) NOT NULL DEFAULT 0.0,
  `pending_days` decimal(5,1) NOT NULL DEFAULT 0.0,
  `remaining_days` decimal(5,1) NOT NULL DEFAULT 0.0,
  `carried_forward` decimal(5,1) NOT NULL DEFAULT 0.0,
  `encashed_days` decimal(5,1) NOT NULL DEFAULT 0.0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_balances`
--

INSERT INTO `leave_balances` (`id`, `employee_id`, `leave_type_id`, `year`, `entitled_days`, `used_days`, `pending_days`, `remaining_days`, `carried_forward`, `encashed_days`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(2, 1, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(3, 1, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(4, 1, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(5, 1, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(6, 1, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(7, 1, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(8, 1, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(9, 1, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(10, 2, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(11, 2, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(12, 2, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(13, 2, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(14, 2, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(15, 2, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(16, 2, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(17, 2, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(18, 2, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(19, 3, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(20, 3, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(21, 3, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(22, 3, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(23, 3, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(24, 3, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(25, 3, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(26, 3, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(27, 3, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(28, 4, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(29, 4, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(30, 4, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(31, 4, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(32, 4, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(33, 4, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(34, 4, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(35, 4, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(36, 4, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(37, 5, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(38, 5, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(39, 5, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(40, 5, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(41, 5, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(42, 5, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(43, 5, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(44, 5, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(45, 5, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(46, 6, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(47, 6, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(48, 6, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(49, 6, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(50, 6, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(51, 6, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(52, 6, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(53, 6, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(54, 6, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(55, 7, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(56, 7, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(57, 7, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(58, 7, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(59, 7, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(60, 7, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(61, 7, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(62, 7, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(63, 7, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(64, 8, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(65, 8, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(66, 8, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(67, 8, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(68, 8, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(69, 8, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(70, 8, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(71, 8, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(72, 8, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(73, 9, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(74, 9, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(75, 9, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(76, 9, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(77, 9, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(78, 9, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(79, 9, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(80, 9, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(81, 9, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(82, 10, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(83, 10, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(84, 10, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(85, 10, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(86, 10, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(87, 10, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(88, 10, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(89, 10, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(90, 10, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(91, 11, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(92, 11, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(93, 11, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(94, 11, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(95, 11, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(96, 11, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(97, 11, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(98, 11, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(99, 11, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(100, 12, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(101, 12, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(102, 12, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(103, 12, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(104, 12, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(105, 12, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(106, 12, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(107, 12, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(108, 12, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(109, 13, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(110, 13, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(111, 13, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(112, 13, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(113, 13, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(114, 13, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(115, 13, 7, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 06:02:23'),
(116, 13, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(117, 13, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(118, 14, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(119, 14, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(120, 14, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(121, 14, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(122, 14, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(123, 14, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(124, 14, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(125, 14, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(126, 14, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(127, 15, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(128, 15, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(129, 15, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(130, 15, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(131, 15, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(132, 15, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(133, 15, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(134, 15, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(135, 15, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(136, 16, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(137, 16, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(138, 16, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(139, 16, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(140, 16, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(141, 16, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(142, 16, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(143, 16, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(144, 16, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(145, 17, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(146, 17, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(147, 17, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(148, 17, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(149, 17, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(150, 17, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(151, 17, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(152, 17, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(153, 17, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(154, 18, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(155, 18, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(156, 18, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(157, 18, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(158, 18, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(159, 18, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(160, 18, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(161, 18, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(162, 18, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(163, 19, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(164, 19, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(165, 19, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(166, 19, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(167, 19, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(168, 19, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(169, 19, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(170, 19, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(171, 19, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(172, 20, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(173, 20, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(174, 20, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(175, 20, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(176, 20, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(177, 20, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(178, 20, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(179, 20, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(180, 20, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(181, 21, 1, '2026', 0.0, 0.0, 0.0, 14.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(182, 21, 2, '2026', 0.0, 0.0, 0.0, 12.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(183, 21, 3, '2026', 0.0, 0.0, 0.0, 10.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(184, 21, 4, '2026', 0.0, 0.0, 0.0, 20.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(185, 21, 5, '2026', 0.0, 0.0, 0.0, 90.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(186, 21, 6, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(187, 21, 7, '2026', 0.0, 0.0, 0.0, 7.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(188, 21, 8, '2026', 0.0, 0.0, 0.0, 3.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24'),
(189, 21, 9, '2026', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '2026-06-11 05:39:24', '2026-06-11 05:39:24');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `leave_number` varchar(255) NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `leave_type_id` bigint(20) UNSIGNED NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `total_days` int(11) NOT NULL,
  `half_day` tinyint(1) NOT NULL DEFAULT 0,
  `half_day_type` enum('Morning','Afternoon') DEFAULT NULL,
  `reason` text NOT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Cancelled','Revoked') NOT NULL DEFAULT 'Pending',
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `cancelled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `leave_number`, `employee_id`, `leave_type_id`, `from_date`, `to_date`, `total_days`, `half_day`, `half_day_type`, `reason`, `document_path`, `status`, `reviewed_by`, `reviewed_at`, `review_notes`, `cancelled_by`, `cancelled_at`, `cancellation_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'LR-00001', 10, 5, '2026-06-11', '2026-10-11', 87, 0, 'Morning', 'the doctor have a child in his belly', NULL, 'Cancelled', 11, '2026-06-11 04:20:05', NULL, 11, '2026-06-11 04:49:46', 'dfsfsdfsdf', '2026-06-11 04:19:59', '2026-06-11 04:49:46', NULL),
(2, 'LR-00002', 15, 2, '2026-06-11', '2026-06-19', 6, 0, 'Morning', 'sadfdfsdf', NULL, 'Rejected', 11, '2026-06-11 04:23:16', 'sdfsdfsd', NULL, NULL, NULL, '2026-06-11 04:23:08', '2026-06-11 04:23:16', NULL),
(3, 'LR-00003', 3, 5, '2026-06-11', '2026-06-26', 11, 0, 'Morning', 'Calamitas deleo teneo atrox atqui terebro censura tempore vulnero.', NULL, 'Approved', 11, '2026-06-11 05:02:06', NULL, NULL, NULL, NULL, '2026-06-11 05:01:47', '2026-06-11 05:02:06', NULL),
(4, 'LR-00004', 13, 7, '2026-06-24', '2026-06-26', 1, 0, 'Morning', 'sfsgsfg', NULL, 'Cancelled', 11, '2026-06-11 05:57:10', NULL, 11, '2026-06-11 06:02:23', 'sds', '2026-06-11 05:56:59', '2026-06-11 06:02:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `days_per_year` int(11) NOT NULL DEFAULT 0,
  `is_paid` tinyint(1) NOT NULL DEFAULT 1,
  `carry_forward` tinyint(1) NOT NULL DEFAULT 0,
  `max_carry_forward` int(11) NOT NULL DEFAULT 0,
  `encashable` tinyint(1) NOT NULL DEFAULT 0,
  `min_service_days` int(11) NOT NULL DEFAULT 0,
  `max_consecutive_days` int(11) DEFAULT NULL,
  `notice_days_required` int(11) NOT NULL DEFAULT 0,
  `requires_document` tinyint(1) NOT NULL DEFAULT 0,
  `document_description` varchar(255) DEFAULT NULL,
  `applicable_male` tinyint(1) NOT NULL DEFAULT 1,
  `applicable_female` tinyint(1) NOT NULL DEFAULT 1,
  `applicable_employment_types` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`applicable_employment_types`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `name`, `code`, `description`, `days_per_year`, `is_paid`, `carry_forward`, `max_carry_forward`, `encashable`, `min_service_days`, `max_consecutive_days`, `notice_days_required`, `requires_document`, `document_description`, `applicable_male`, `applicable_female`, `applicable_employment_types`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Annual Leave', 'AL', 'Paid annual rest leave for permanent employees. Earned after completing 12 months of continuous service, in compliance with Pakistan labor regulations.', 14, 1, 1, 14, 1, 365, 15, 14, 0, NULL, 1, 1, NULL, 1, '2026-06-09 20:53:57', '2026-06-09 20:56:58'),
(2, 'Casual Leave', 'CL', 'Short-term leave for urgent personal matters.', 12, 1, 0, 0, 0, 0, 3, 1, 0, NULL, 1, 1, '[\"Permanent\", \"Probation\", \"Contract\"]', 1, '2026-06-11 09:12:08', '2026-06-11 09:12:08'),
(3, 'Sick Leave', 'SL', 'Leave for medical reasons. Medical certificate required for more than 2 days.', 10, 1, 1, 10, 0, 0, 15, 0, 1, 'Medical Certificate from Registered Practitioner', 1, 1, '[\"Permanent\", \"Probation\", \"Contract\"]', 1, '2026-06-11 09:12:08', '2026-06-11 09:12:08'),
(4, 'Earned Leave', 'EL', 'Annual leave for long vacations. Can be carried forward and encashed.', 20, 1, 1, 45, 1, 180, 15, 15, 0, NULL, 1, 1, '[\"Permanent\"]', 1, '2026-06-11 09:12:08', '2026-06-11 09:12:08'),
(5, 'Maternity Leave', 'ML', 'Paid leave for female employees for childbirth.', 90, 1, 0, 0, 0, 270, 90, 30, 1, 'Birth Certificate or Medical Report', 0, 1, '[\"Permanent\"]', 1, '2026-06-11 09:12:08', '2026-06-11 09:12:08'),
(6, 'Paternity Leave', 'PL', 'Leave for male employees for the birth of a child.', 7, 1, 0, 0, 0, 180, 7, 15, 1, 'Birth Certificate', 1, 0, '[\"Permanent\"]', 1, '2026-06-11 09:12:08', '2026-06-11 09:12:08'),
(7, 'CME Leave', 'CME', 'Leave for attending medical conferences and workshops.', 7, 1, 0, 0, 0, 90, 7, 30, 1, 'Conference Invitation or Registration Receipt', 1, 1, '[\"Permanent\", \"Contract\"]', 1, '2026-06-11 09:12:08', '2026-06-11 09:12:08'),
(8, 'Bereavement Leave', 'BL', 'Leave granted in the event of the death of a family member.', 3, 1, 0, 0, 0, 0, 3, 0, 0, NULL, 1, 1, '[\"Permanent\", \"Probation\", \"Contract\"]', 1, '2026-06-11 09:12:08', '2026-06-11 09:12:08'),
(9, 'Loss of Pay', 'LOP', 'Unpaid leave when all other leave balances are exhausted.', 0, 0, 0, 0, 0, 0, 30, 2, 0, NULL, 1, 1, '[\"Permanent\", \"Probation\", \"Contract\"]', 1, '2026-06-11 09:12:08', '2026-06-11 09:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `medicine_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `generic_name` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `category` enum('Tablet','Capsule','Syrup','Injection','Cream','Drops','Inhaler','Powder','Other') NOT NULL DEFAULT 'Tablet',
  `unit` varchar(255) NOT NULL DEFAULT 'Tablet',
  `purchase_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sale_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `reorder_level` int(11) NOT NULL DEFAULT 10,
  `total_stock` int(11) NOT NULL DEFAULT 0,
  `requires_prescription` tinyint(1) NOT NULL DEFAULT 0,
  `storage_condition` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `medicine_code`, `name`, `generic_name`, `brand`, `category`, `unit`, `purchase_price`, `sale_price`, `reorder_level`, `total_stock`, `requires_prescription`, `storage_condition`, `description`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(33, 'MED-00001', 'Panadol', 'Paracetamol', 'GSK', 'Tablet', 'Tablet', 1.50, 2.00, 500, 2491, 0, 'Room Temperature', 'Common pain reliever and fever reducer', 1, '2026-05-10 20:40:18', '2026-05-10 20:44:50', NULL),
(34, 'MED-00034', 'Augmentin', 'Co-Amoxiclav', 'GSK', 'Tablet', 'Strip', 180.00, 210.00, 50, 84, 0, 'Store below 25°C', 'Broad-spectrum antibiotic', 1, '2026-05-10 20:55:34', '2026-06-16 06:03:34', NULL),
(35, 'MED-00035', 'Flagyl', 'Metronidazole', 'Sanofi', 'Tablet', 'Tablet', 0.80, 1.20, 300, 1981, 1, 'Room Temperature', 'For stomach infections and bacteria', 1, '2026-05-11 01:33:57', '2026-05-22 05:28:40', NULL),
(36, 'MED-00036', 'Brufen', 'Ibuprofen', 'Abbott', 'Tablet', 'Tablet', 2.10, 2.80, 400, 487, 1, 'Room Temperature', 'Anti-inflammatory and pain killer', 1, '2026-05-11 01:39:39', '2026-06-04 21:46:46', NULL),
(37, 'MED-00037', 'Oral rehydration solution', 'Supplement', 'GSK', 'Other', 'Sachet', 20.00, 50.00, 100, 100, 1, 'Room Temperature', 'One Sachet in one litter of water', 1, '2026-06-04 21:44:05', '2026-06-04 21:45:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `medicine_batches`
--

CREATE TABLE `medicine_batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `medicine_id` bigint(20) UNSIGNED NOT NULL,
  `batch_number` varchar(255) NOT NULL,
  `expiry_date` date NOT NULL,
  `manufacture_date` date DEFAULT NULL,
  `quantity_received` int(11) NOT NULL,
  `quantity_in_stock` int(11) NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `supplier_invoice` varchar(255) DEFAULT NULL,
  `status` enum('Active','Expired','Exhausted') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medicine_batches`
--

INSERT INTO `medicine_batches` (`id`, `medicine_id`, `batch_number`, `expiry_date`, `manufacture_date`, `quantity_received`, `quantity_in_stock`, `purchase_price`, `supplier_name`, `supplier_invoice`, `status`, `created_at`, `updated_at`) VALUES
(21, 33, 'BN-100', '2026-11-12', NULL, 2500, 2491, 1.50, 'Ali Medical Store', 'INV-001', 'Active', '2026-05-10 20:43:39', '2026-05-10 20:44:50'),
(22, 34, 'AUG-786', '2028-06-01', '2026-01-11', 100, 15, 180.00, 'HealthCare Dist', 'INV-992', 'Active', '2026-05-10 20:59:26', '2026-06-16 06:03:34'),
(23, 35, 'FLG-552', '2026-11-11', '2022-02-07', 2000, 1981, 0.80, 'Standard Pharma', 'INV-332', 'Active', '2026-05-11 01:35:57', '2026-05-22 05:28:40'),
(24, 36, 'AR-990', '2031-11-12', '2026-05-10', 500, 487, 2.10, 'Ali Medical Store', 'INV-332', 'Active', '2026-05-11 01:41:07', '2026-06-04 21:46:46'),
(25, 34, 'AUG-790', '2028-09-22', '2026-05-31', 100, 69, 180.00, 'Standard Pharma', 'INV-332', 'Active', '2026-06-04 01:34:40', '2026-06-04 21:40:40'),
(26, 37, 'BT-2026', '2026-12-26', '2026-06-02', 100, 100, 20.00, 'KARAN KHAN', 'KAR-002', 'Active', '2026-06-04 21:45:29', '2026-06-04 21:45:29');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0000_01_01_000000_create_employees_table', 1),
(2, '0001_01_01_000000_create_users_table', 1),
(3, '0001_01_01_000001_create_cache_table', 1),
(4, '0001_01_01_000002_create_jobs_table', 1),
(5, '2026_03_30_073915_create_doctors_table', 1),
(6, '2026_03_30_094654_create_patients_table', 1),
(7, '2026_03_30_113429_create_appointments_table', 1),
(8, '2026_04_06_065604_create_wards_table', 1),
(9, '2026_04_06_065849_create_beds_table', 1),
(10, '2026_04_06_094612_create_medicines_table', 1),
(11, '2026_04_06_095000_create_medicine_batches_table', 1),
(12, '2026_04_06_095225_create_prescriptions_table', 1),
(13, '2026_04_06_095815_create_prescription_items_table', 1),
(14, '2026_04_06_095957_create_dispensings_table', 1),
(15, '2026_04_06_100127_create_dispensing_items_table', 1),
(16, '2026_04_08_091526_create_lab_sample_types_table', 1),
(17, '2026_04_08_091828_create_lab_test_categories_table', 1),
(18, '2026_04_08_091927_create_lab_orders_table', 1),
(19, '2026_04_08_092120_create_lab_samples_table', 1),
(20, '2026_04_08_092153_create_lab_tests_table', 1),
(21, '2026_04_08_092325_create_lab_order_items_table', 1),
(22, '2026_04_08_093330_create_lab_results_table', 1),
(23, '2026_04_13_070312_create_radiology_modalities_table', 1),
(24, '2026_04_13_070614_create_radiology_body_parts_table', 1),
(25, '2026_04_13_070745_create_radiology_exams_table', 1),
(26, '2026_04_13_071051_create_radiology_orders_table', 1),
(27, '2026_04_13_071402_create_radiology_order_items_table', 1),
(28, '2026_04_13_071533_create_radiology_reports_table', 1),
(29, '2026_04_13_071923_create_radiology_images_table', 1),
(30, '2026_04_13_072118_create_radiology_consents_table', 1),
(31, '2026_04_22_070246_create_ot_rooms_table', 1),
(32, '2026_04_22_070407_create_ot_schedules_table', 1),
(33, '2026_04_22_070453_create_ot_teams_table', 1),
(34, '2026_04_24_060744_create_blood_donors_table', 1),
(35, '2026_04_24_060859_create_blood_donations_table', 1),
(36, '2026_04_24_061108_create_blood_inventories_table', 1),
(37, '2026_04_24_061552_create_blood_requests_table', 1),
(38, '2026_04_24_061706_create_blood_crossmatches_table', 1),
(39, '2026_04_24_061855_create_blood_issues_table', 1),
(40, '2026_05_04_092123_create_bill_service_charges_table', 1),
(41, '2026_05_04_092240_create_bills_table', 1),
(42, '2026_05_04_092500_create_bill_items_table', 1),
(43, '2026_05_04_092617_create_bill_payments_table', 1),
(44, '2026_05_12_163222_add_blood_component_to_bill_service_charges', 1),
(45, '2026_05_15_055840_create_mortuary_records_table', 1),
(46, '2026_05_15_060928_create_death_certificates_table', 1),
(47, '2026_05_15_065313_create_body_release_records_table', 1),
(48, '2026_06_08_113510_create_leave_types_table', 1),
(49, '2026_06_08_113626_create_leave_requests_table', 1),
(50, '2026_06_08_113817_create_leave_balances_table', 1),
(51, '2026_06_08_113950_create_attendances_table', 1),
(52, '2026_06_08_114122_create_salary_structures_table', 1),
(53, '2026_06_08_114300_create_payroll_runs_table', 1),
(54, '2026_06_08_114557_create_payslips_table', 1),
(55, '2026_06_08_114728_create_disciplinary_actions_table', 1),
(56, '2026_06_08_114823_create_holidays_table', 1),
(57, '2026_06_12_070315_create_settings_table', 2),
(58, '2026_06_15_060611_create_activity_logs_table', 3),
(59, '2026_06_16_092823_create_patient_vitals_table', 4),
(60, '2026_06_16_093019_create_patient_nursing_notes_table', 4),
(61, '2026_06_16_093240_create_patient_doctor_orders_table', 4),
(62, '2026_06_16_093359_create_patient_visit_notes_table', 4),
(63, '2026_06_16_093510_create_patient_discharges_table', 4),
(64, '2026_06_17_094059_create_ward_nurse_assignments_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `mortuary_records`
--

CREATE TABLE `mortuary_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mortuary_id` varchar(255) NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `death_datetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `death_location` enum('Ward','ICU','CCU','Emergency','OT','DOA','Outside Hospital') NOT NULL DEFAULT 'Ward',
  `ward` varchar(255) DEFAULT NULL,
  `bed_number` varchar(255) DEFAULT NULL,
  `immediate_cause` varchar(255) NOT NULL,
  `intermediate_cause` varchar(255) DEFAULT NULL,
  `underlying_cause` varchar(255) DEFAULT NULL,
  `contributing_cause` varchar(255) DEFAULT NULL,
  `manner_of_death` enum('Natural','Accidental','Homicidal','Suicidal','Undetermined') NOT NULL DEFAULT 'Natural',
  `declared_by` bigint(20) UNSIGNED DEFAULT NULL,
  `declared_at` timestamp NULL DEFAULT NULL,
  `locker_number` varchar(255) DEFAULT NULL,
  `body_condition` enum('Normal','Decomposed','Burned','Traumatic','Other') DEFAULT NULL,
  `body_weight_kg` decimal(5,1) DEFAULT NULL,
  `identification_marks` text DEFAULT NULL,
  `status` enum('Admitted','Postmortem Pending','Postmortem Done','Certificate Issued','Released','Transferred','Unclaimed') NOT NULL DEFAULT 'Admitted',
  `postmortem_required` tinyint(1) NOT NULL DEFAULT 0,
  `postmortem_ordered_by` enum('Doctor','Police','Court','Hospital') DEFAULT NULL,
  `postmortem_status` enum('Not Required','Pending','In Progress','Completed') NOT NULL DEFAULT 'Not Required',
  `postmortem_started_at` timestamp NULL DEFAULT NULL,
  `postmortem_completed_at` timestamp NULL DEFAULT NULL,
  `postmortem_by` bigint(20) UNSIGNED DEFAULT NULL,
  `postmortem_findings` text DEFAULT NULL,
  `postmortem_report_number` varchar(255) DEFAULT NULL,
  `is_medico_legal` tinyint(1) NOT NULL DEFAULT 0,
  `mlc_number` varchar(255) DEFAULT NULL,
  `police_station` varchar(255) DEFAULT NULL,
  `investigating_officer` varchar(255) DEFAULT NULL,
  `fir_number` varchar(255) DEFAULT NULL,
  `police_informed_at` timestamp NULL DEFAULT NULL,
  `nok_name` varchar(255) DEFAULT NULL,
  `nok_relation` varchar(255) DEFAULT NULL,
  `nok_cnic` varchar(255) DEFAULT NULL,
  `nok_phone` varchar(255) DEFAULT NULL,
  `nok_informed` tinyint(1) NOT NULL DEFAULT 0,
  `nok_informed_at` timestamp NULL DEFAULT NULL,
  `admitted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mortuary_records`
--

INSERT INTO `mortuary_records` (`id`, `mortuary_id`, `patient_id`, `death_datetime`, `death_location`, `ward`, `bed_number`, `immediate_cause`, `intermediate_cause`, `underlying_cause`, `contributing_cause`, `manner_of_death`, `declared_by`, `declared_at`, `locker_number`, `body_condition`, `body_weight_kg`, `identification_marks`, `status`, `postmortem_required`, `postmortem_ordered_by`, `postmortem_status`, `postmortem_started_at`, `postmortem_completed_at`, `postmortem_by`, `postmortem_findings`, `postmortem_report_number`, `is_medico_legal`, `mlc_number`, `police_station`, `investigating_officer`, `fir_number`, `police_informed_at`, `nok_name`, `nok_relation`, `nok_cnic`, `nok_phone`, `nok_informed`, `nok_informed_at`, `admitted_by`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'MTY-00001', 2, '2026-05-16 11:39:06', 'Ward', 'medical A ward', '14', 'NO', 'NO', 'NO', 'NO', 'Accidental', 1, '2026-05-16 11:16:00', '107', 'Normal', 90.0, 'There is no mark', 'Released', 1, 'Court', 'Completed', '2026-05-15 11:31:00', '2026-05-16 11:31:00', 1, 'In the postmortem finding have significant evidence that the person have been torture too much', '10-33', 1, '444334', 'bara chowk', 'Gold smith', '21122', '2026-05-16 11:18:00', 'Abdul Kalam', 'brother', '1112345678999', '03334504157', 1, '2026-05-16 11:19:00', 5, 'clearly mark', '2026-05-16 06:19:38', '2026-05-16 06:39:06', NULL),
(3, 'MTY-00003', 56, '2026-05-17 09:24:38', 'CCU', 'medical A ward', '14', 'NO', 'NO', 'NO', 'NO', 'Accidental', 8, '2026-05-17 09:12:00', '107', 'Normal', 88.0, 'there is not identification mark are there and we need to focus on that', 'Released', 1, NULL, 'Completed', '2026-05-16 09:15:00', '2026-05-17 09:15:00', 1, 'GGGGGGGGGGGGGGGGGGGGGGGG', '10-33', 1, '444334', 'bara chowk', 'Gold smith', '21122', '2026-05-17 09:13:00', 'Shehreyar Khan Afridi kHAN', 'brother', NULL, '03334504157', 1, '2026-05-17 09:13:00', 3, 'this is just for testing not secure data', '2026-05-17 04:13:50', '2026-05-17 04:24:38', NULL),
(4, 'MTY-00004', 33, '2026-05-18 02:35:36', 'ICU', 'medical A ward', '14', 'NO', 'NO', 'NO', 'NO', 'Undetermined', 1, '2026-05-18 01:49:00', '107', 'Other', 89.8, NULL, 'Certificate Issued', 0, NULL, 'Not Required', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'Khan', 'brother', '1112345678999', NULL, 1, '2026-05-16 01:49:00', 11, NULL, '2026-05-17 20:49:58', '2026-05-17 21:35:36', NULL),
(5, 'MTY-00005', 42, '2026-05-18 02:19:23', 'DOA', 'advenio curriculum candidus', '115', '2026-03-28 04:59:50', '2027-03-17 05:39:43', 'Adrienne13', 'Arthur91', 'Accidental', 1, '2025-11-06 22:46:00', '209', 'Decomposed', 278.0, 'Concido quis velit degusto strues aeger turpis sapiente votum.', 'Released', 0, NULL, 'Not Required', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'Tara Adams', '182', '538', '251-061-1581', 1, '2025-11-21 23:18:00', 8, '58', '2026-05-17 21:09:30', '2026-05-17 21:19:23', NULL),
(6, 'MTY-00006', 36, '2026-05-18 02:52:56', 'Outside Hospital', NULL, NULL, 'Heart Attack', 'Acute', 'Heat patient', 'Ceroses', 'Homicidal', 2, '2026-05-18 02:39:00', NULL, NULL, NULL, NULL, 'Released', 0, NULL, 'Not Required', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'Saad Khan', 'Father', '1112345678999', '03334434343', 1, '2026-05-18 02:40:00', 15, NULL, '2026-05-17 21:41:02', '2026-05-17 21:52:56', NULL),
(7, 'MTY-00007', 34, '2026-05-18 06:01:34', 'Ward', NULL, NULL, 'NO', 'NO', 'NO', 'NO', 'Accidental', 9, '2026-05-18 05:59:00', NULL, NULL, NULL, NULL, 'Released', 0, NULL, 'Not Required', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'kareen khan', 'wife', '888888888888', '09999999999', 1, '2026-05-17 06:00:00', 7, NULL, '2026-05-18 01:00:24', '2026-05-18 01:01:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ot_rooms`
--

CREATE TABLE `ot_rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `room_type` enum('General','Cardiac','Neurology','Orthopedic','Gynecology','ENT','Eye','Trauma','Emergency') NOT NULL DEFAULT 'General',
  `status` enum('Available','Occupied','Cleaning','Maintenance','Out of Service') NOT NULL DEFAULT 'Available',
  `has_anesthesia_machine` tinyint(1) NOT NULL DEFAULT 1,
  `has_ventilator` tinyint(1) NOT NULL DEFAULT 0,
  `has_laparoscopy` tinyint(1) NOT NULL DEFAULT 0,
  `has_c_arm` tinyint(1) NOT NULL DEFAULT 0,
  `is_laminar_flow` tinyint(1) NOT NULL DEFAULT 0,
  `equipment_notes` text DEFAULT NULL,
  `floor` varchar(255) DEFAULT NULL,
  `block` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ot_rooms`
--

INSERT INTO `ot_rooms` (`id`, `room_code`, `name`, `room_type`, `status`, `has_anesthesia_machine`, `has_ventilator`, `has_laparoscopy`, `has_c_arm`, `is_laminar_flow`, `equipment_notes`, `floor`, `block`, `notes`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'OT-001', 'Main Operation Theater', 'Orthopedic', 'Available', 1, 1, 1, 1, 1, NULL, 'First Floor', 'D', NULL, 1, '2026-05-19 01:36:55', '2026-06-05 01:07:26', NULL),
(2, 'OT-01', 'General Operation Theater', 'Gynecology', 'Occupied', 1, 1, 1, 1, 1, NULL, 'First Floor', 'A', NULL, 1, '2026-05-19 01:37:45', '2026-05-22 06:43:13', '2026-05-22 06:43:13'),
(3, 'OT-002', 'Operaton Theate 3', 'Trauma', 'Available', 1, 1, 1, 1, 1, 'The Operation Theater Have full facility', 'First Floor', 'E', 'Main surgery perform there', 1, '2026-05-22 06:45:30', '2026-06-07 23:55:31', '2026-06-07 23:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `ot_schedules`
--

CREATE TABLE `ot_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `surgery_id` varchar(255) NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `ot_room_id` bigint(20) UNSIGNED DEFAULT NULL,
  `surgeon_id` bigint(20) UNSIGNED NOT NULL,
  `anesthesiologist_id` bigint(20) UNSIGNED DEFAULT NULL,
  `scheduled_date` date NOT NULL,
  `scheduled_time` time NOT NULL,
  `estimated_duration_mins` smallint(5) UNSIGNED NOT NULL DEFAULT 60,
  `actual_start_time` timestamp NULL DEFAULT NULL,
  `actual_end_time` timestamp NULL DEFAULT NULL,
  `surgery_type` enum('Elective','Urgent','Emergency','Diagnostic') NOT NULL DEFAULT 'Elective',
  `priority` enum('Routine','Priority','Urgent','Emergency') NOT NULL DEFAULT 'Routine',
  `anesthesia_type` enum('General','Local','Regional','Spinal','Epidural','Sedation','None') DEFAULT NULL,
  `status` enum('Scheduled','Confirmed','Preparing','In-Progress','Completed','Postponed','Cancelled') NOT NULL DEFAULT 'Scheduled',
  `diagnosis` varchar(255) NOT NULL,
  `procedure_name` varchar(255) NOT NULL,
  `procedure_details` text DEFAULT NULL,
  `pre_op_instructions` text DEFAULT NULL,
  `post_op_notes` text DEFAULT NULL,
  `complications` text DEFAULT NULL,
  `post_op_destination` varchar(255) DEFAULT NULL,
  `consent_obtained` tinyint(1) NOT NULL DEFAULT 0,
  `consent_at` timestamp NULL DEFAULT NULL,
  `consent_by` varchar(255) DEFAULT NULL,
  `pre_op_assessment_done` tinyint(1) NOT NULL DEFAULT 0,
  `pre_op_assessment_notes` text DEFAULT NULL,
  `postpone_reason` varchar(255) DEFAULT NULL,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `rescheduled_date` date DEFAULT NULL,
  `billing_status` enum('Unbilled','Billed','Partial','Paid') NOT NULL DEFAULT 'Unbilled',
  `surgeon_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `anesthesia_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ot_room_charges` decimal(10,2) NOT NULL DEFAULT 0.00,
  `consumables_charges` decimal(10,2) NOT NULL DEFAULT 0.00,
  `booked_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ot_schedules`
--

INSERT INTO `ot_schedules` (`id`, `surgery_id`, `patient_id`, `ot_room_id`, `surgeon_id`, `anesthesiologist_id`, `scheduled_date`, `scheduled_time`, `estimated_duration_mins`, `actual_start_time`, `actual_end_time`, `surgery_type`, `priority`, `anesthesia_type`, `status`, `diagnosis`, `procedure_name`, `procedure_details`, `pre_op_instructions`, `post_op_notes`, `complications`, `post_op_destination`, `consent_obtained`, `consent_at`, `consent_by`, `pre_op_assessment_done`, `pre_op_assessment_notes`, `postpone_reason`, `cancellation_reason`, `rescheduled_date`, `billing_status`, `surgeon_fee`, `anesthesia_fee`, `ot_room_charges`, `consumables_charges`, `booked_by`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SRG-00001', 35, 1, 8, 7, '2026-05-21', '23:53:00', 60, NULL, '2026-05-21 06:54:49', 'Elective', 'Routine', NULL, 'Completed', 'flue and fiver', 'Larparoscopic', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 'Paid', 10000.00, 5000.00, 3000.00, 2000.00, NULL, NULL, '2026-05-21 06:53:58', '2026-05-21 20:00:26', NULL),
(2, 'SRG-00002', 37, 1, 8, 7, '2026-05-22', '01:17:00', 60, NULL, '2026-05-21 19:14:04', 'Elective', 'Routine', NULL, 'Completed', 'flue and fiver', 'Larparoscopic', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 'Paid', 20000.00, 10000.00, 5000.00, 2000.00, NULL, NULL, '2026-05-21 19:13:58', '2026-05-21 20:02:00', NULL),
(3, 'SRG-00003', 38, 1, 8, 7, '2026-05-22', '10:17:00', 60, NULL, '2026-05-22 06:42:56', 'Elective', 'Routine', NULL, 'Completed', 'Super vapulus volo cruentus coerceo urbs conicio confugo alius ago.', 'Larparoscopic', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 'Unbilled', 30000.00, 10000.00, 5000.00, 3000.00, NULL, NULL, '2026-05-21 19:15:48', '2026-05-22 06:42:56', NULL),
(4, 'SRG-00004', 44, 3, 8, 7, '2026-05-22', '22:49:00', 60, NULL, '2026-05-22 07:12:04', 'Diagnostic', 'Priority', 'Regional', 'Completed', 'The patient have the kidney stones and we will remove', 'Cholecycropy', 'The procedure will perform by the top doctors we will give anesthesia and also maintain the blood pressure', 'The patient did not eat anything from the 12 AM at night did not drink water etc', NULL, NULL, NULL, 1, NULL, 'Hussain Gul', 1, 'Nurse investigate all the test that the patient are fit for the surgery', NULL, NULL, NULL, 'Unbilled', 35000.00, 15000.00, 1000.00, 5000.00, NULL, 'Patient should be healthy and function', '2026-05-22 06:52:34', '2026-06-07 21:30:30', '2026-06-07 21:30:30'),
(5, 'SRG-00005', 63, 1, 8, 7, '2026-06-05', '16:05:00', 60, NULL, '2026-06-05 01:07:26', 'Diagnostic', 'Emergency', 'Sedation', 'Completed', 'fiver and flue', 'appendocometry', 'sdfsdqwe olajs ejr; oas;lej raslehrhw;e', 'no eat anything', NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 'Unbilled', 30000.00, 20000.00, 10000.00, 5000.00, 7, NULL, '2026-06-05 01:05:52', '2026-06-07 20:51:19', '2026-06-07 20:51:19');

-- --------------------------------------------------------

--
-- Table structure for table `ot_teams`
--

CREATE TABLE `ot_teams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ot_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('Assistant Surgeon','Scrub Nurse','Circulating Nurse','OT Technician','Anesthesia Technician','Perfusionist','Observer','Other') NOT NULL,
  `doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ot_teams`
--

INSERT INTO `ot_teams` (`id`, `ot_schedule_id`, `role`, `doctor_id`, `employee_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 4, 'Assistant Surgeon', 8, 10, NULL, '2026-05-22 06:52:34', '2026-05-22 06:52:34'),
(3, 5, 'Circulating Nurse', 8, 10, NULL, '2026-06-05 01:06:34', '2026-06-05 01:06:34');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mrn` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `emergency_relation` varchar(255) DEFAULT NULL,
  `cnic` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `patient_type` enum('OPD','IPD','Emergency') NOT NULL DEFAULT 'OPD',
  `status` enum('Active','Admitted','Discharged','Deceased') NOT NULL DEFAULT 'Active',
  `doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `mrn`, `name`, `father_name`, `date_of_birth`, `gender`, `blood_group`, `phone`, `emergency_contact`, `emergency_relation`, `cnic`, `address`, `city`, `patient_type`, `status`, `doctor_id`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'MRN-00001', 'Shehreyar Khan', NULL, '2000-04-12', 'Male', 'O+', '92333450415', '091122112221', 'Father', NULL, 'dora school suffaid dheri khatama nabowata chowk peshawar', 'Peshawar', 'IPD', 'Discharged', NULL, NULL, '2026-05-10 19:58:17', '2026-05-11 21:53:32', NULL),
(2, 'MRN-00002', 'Ahmad faraz', 'fara jan', '2022-02-11', 'Male', 'A+', '03334504157', '091122112221', 'Father', '6110298765432', 'dora school suffaid dheri khatama nabowat chowk peshawar', 'Peshawar', 'IPD', 'Deceased', NULL, NULL, '2026-05-11 23:25:48', '2026-05-16 06:19:38', NULL),
(33, 'MRN-000031', 'Ahmed Ali', 'Muhammad Ali', '1990-05-15', 'Male', 'A+', '03001234561', '03111234561', 'Father', '4210112345671', 'Street 1, Gulshan', 'Karachi', 'IPD', 'Discharged', NULL, 'Normal checkup', '2026-05-13 01:50:55', '2026-06-16 06:03:16', NULL),
(34, 'MRN-000032', 'Sara Khan', 'Abdul Khan', '1995-10-22', 'Female', 'B-', '03001234562', '03111234562', 'Husband', '4210112345672', 'Block 4, Clifton', 'Karachi', 'IPD', 'Deceased', NULL, 'Post-surgery', '2026-05-13 01:50:55', '2026-05-18 01:00:24', NULL),
(35, 'MRN-00003', 'Zubair Sheikh', 'Ibrahim Sheikh', '1985-03-10', 'Male', 'O+', '03001234563', '03111234563', 'Brother', '4210112345673', 'Model Town', 'Lahore', 'IPD', 'Active', NULL, 'Accident case', '2026-05-13 01:50:55', '2026-05-18 21:38:09', NULL),
(36, 'MRN-00004', 'Ayesha Malik', 'Zafar Malik', '2000-01-05', 'Female', 'AB+', '03001234564', '03111234564', 'Mother', '4210112345674', 'G-9 Markaz', 'Islamabad', 'OPD', 'Deceased', NULL, NULL, '2026-05-13 01:50:55', '2026-05-17 21:41:02', NULL),
(37, 'MRN-00005', 'Usman Butt', 'Tariq Butt', '1978-08-12', 'Male', 'O-', '03001234565', '03111234565', 'Wife', '4210112345675', 'DHA Phase 5', 'Lahore', 'IPD', 'Active', NULL, 'Recovered', '2026-05-13 01:50:55', '2026-05-18 21:32:37', NULL),
(38, 'MRN-00006', 'Fatima Bi', 'Ghulam Rasool', '1965-12-30', 'Female', 'B+', '03001234566', '03111234566', 'Son', '4210112345676', 'Satellite Town', 'Rawalpindi', 'IPD', 'Active', NULL, 'Sugar patient', '2026-05-13 01:50:55', '2026-05-18 21:21:00', NULL),
(39, 'MRN-00007', 'Hamza Yousuf', 'Yousuf Khan', '1992-04-14', 'Male', 'A-', '03001234567', '03111234567', 'Father', '4210112345677', 'University Road', 'Peshawar', 'IPD', 'Deceased', NULL, 'Heart attack', '2026-05-13 01:50:55', '2026-05-18 21:38:32', NULL),
(40, 'MRN-00008', 'Mariam Jameel', 'Jameel Ahmed', '1988-07-19', 'Female', 'O+', '03001234568', '03111234568', 'Brother', '4210112345678', 'North Nazimabad', 'Karachi', 'OPD', 'Active', NULL, NULL, '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(41, 'MRN-00009', 'Bilal Siddiqui', 'Anwar Siddiqui', '2005-11-11', 'Male', 'B-', '03001234569', '03111234569', 'Father', '4210112345679', 'Johar Town', 'Lahore', 'OPD', 'Active', NULL, 'Flu and Fever', '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(42, 'MRN-00010', 'Zainab Abbas', 'Abbas Raza', '1998-02-28', 'Female', 'A+', '03001234570', '03111234570', 'Mother', '4210112345680', 'F-10 Sector', 'Islamabad', 'IPD', 'Deceased', NULL, 'Observation', '2026-05-13 01:50:55', '2026-05-17 21:09:30', NULL),
(43, 'MRN-00011', 'Kamran Akmal', 'Akmal Shah', '1982-06-25', 'Male', 'AB-', '03001234571', '03111234571', 'Wife', '4210112345681', 'Gulberg 3', 'Lahore', 'Emergency', 'Deceased', NULL, 'Injury', '2026-05-13 01:50:55', '2026-05-16 02:04:21', NULL),
(44, 'MRN-00012', 'Sana Pervez', 'Pervez Alam', '1993-09-17', 'Female', 'O+', '03001234572', '03111234572', 'Husband', '4210112345682', 'Saddar', 'Karachi', 'IPD', 'Admitted', 4, NULL, '2026-05-13 01:50:55', '2026-05-22 05:06:40', NULL),
(45, 'MRN-00013', 'Arsalan Baig', 'Mirza Baig', '1991-03-12', 'Male', 'B+', '03001234573', '03111234573', 'Father', '4210112345683', 'Cantt Area', 'Multan', 'OPD', 'Discharged', NULL, 'Routine Checkup', '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(46, 'MRN-00014', 'Hina Riaz', 'Riaz Ud Din', '1987-12-01', 'Female', 'A+', '03001234574', '03111234574', 'Brother', '4210112345684', 'Civil Lines', 'Faisalabad', 'IPD', 'Admitted', NULL, 'Surgery', '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(47, 'MRN-00015', 'Mustafa Qureshi', 'Haris Qureshi', '1975-05-20', 'Male', 'O-', '03001234575', '03111234575', 'Son', '4210112345685', 'Bahria Town', 'Rawalpindi', 'Emergency', 'Active', NULL, 'Blood pressure', '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(48, 'MRN-00016', 'Nida Hassan', 'Hassan Ali', '1996-08-08', 'Female', 'AB+', '03001234576', '03111234576', 'Mother', '4210112345686', 'Korangi', 'Karachi', 'OPD', 'Active', NULL, NULL, '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(49, 'MRN-00017', 'Waqas Jutt', 'Liaqat Jutt', '1989-11-30', 'Male', 'B+', '03001234577', '03111234577', 'Father', '4210112345687', 'Wapda Town', 'Gujranwala', 'OPD', 'Active', NULL, 'Back pain', '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(50, 'MRN-00018', 'Sadia Imam', 'Imam Baksh', '1980-04-05', 'Female', 'A-', '03001234578', '03111234578', 'Husband', '4210112345688', 'Gulistan-e-Johar', 'Karachi', 'IPD', 'Discharged', NULL, 'Normal delivery', '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(51, 'MRN-00019', 'Fahad Mustafa', 'Mustafa Aziz', '1994-01-25', 'Male', 'O+', '03001234579', '03111234579', 'Brother', '4210112345689', 'Hayatabad', 'Peshawar', 'Emergency', 'Active', NULL, 'Food poisoning', '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(52, 'MRN-00020', 'Kiran Shahzadi', 'Shahzad Gul', '2001-07-14', 'Female', 'B-', '03001234580', '03111234580', 'Father', '4210112345690', 'E-7 Sector', 'Islamabad', 'OPD', 'Active', NULL, 'Skin allergy', '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(53, 'MRN-00021', 'Omer Lodhi', 'Nasir Lodhi', '1984-10-10', 'Male', 'A+', '03001234581', '03111234581', 'Wife', '4210112345691', 'Defence', 'Karachi', 'IPD', 'Admitted', NULL, 'Kidney stone', '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(54, 'MRN-00022', 'Irum Javed', 'Javed Iqbal', '1992-02-12', 'Female', 'AB-', '03001234582', '03111234582', 'Mother', '4210112345692', 'Samanabad', 'Lahore', 'OPD', 'Active', NULL, NULL, '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(55, 'MRN-00023', 'Tahir Mehmood', 'Mehmood Ahmed', '1970-03-22', 'Male', 'O+', '03001234583', '03111234583', 'Son', '4210112345693', 'People’s Colony', 'Faisalabad', 'Emergency', 'Deceased', NULL, 'Cardiac arrest', '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(56, 'MRN-00024', 'Bushra Bibi', 'Ali Murad', '1960-06-18', 'Female', 'B+', '03001234584', '03111234584', 'Son', '4210112345694', 'Liaquatabad', 'Karachi', 'OPD', 'Deceased', NULL, 'Joint pain', '2026-05-13 01:50:55', '2026-05-17 04:13:50', NULL),
(57, 'MRN-00025', 'Saad Ghaffar', 'Abdul Ghaffar', '1997-12-05', 'Male', 'A-', '03001234585', '03111234585', 'Father', '4210112345695', 'Malir Cantt', 'Karachi', 'IPD', 'Admitted', NULL, 'Typhoid', '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(58, 'MRN-00026', 'Mehak Noor', 'Noor Muhammad', '1999-05-30', 'Female', 'O-', '03001234586', '03111234586', 'Brother', '4210112345696', 'G-11 Sector', 'Islamabad', 'OPD', 'Active', NULL, 'Eye checkup', '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(59, 'MRN-00027', 'Farhan Saeed', 'Saeed Anwar', '1986-08-21', 'Male', 'B-', '03001234587', '03111234587', 'Wife', '4210112345697', 'Garden Town', 'Lahore', 'Emergency', 'Active', NULL, 'Head injury', '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(60, 'MRN-00028', 'Saba Qamar', 'Qamar Zaman', '1991-11-14', 'Female', 'A+', '03001234588', '03111234588', 'Husband', '4210112345698', 'Gulshan-e-Iqbal', 'Karachi', 'OPD', 'Active', NULL, NULL, '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(61, 'MRN-00029', 'Junaid Khan', 'Afzal Khan', '1983-04-09', 'Male', 'AB+', '03001234589', '03111234589', 'Father', '4210112345699', 'Saddar', 'Peshawar', 'IPD', 'Discharged', NULL, 'Appendix surgery', '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(62, 'MRN-00030', 'Rida Batool', 'Syed Ali', '1994-07-01', 'Female', 'O+', '03001234590', '03111234590', 'Mother', '4210112345700', 'F-6 Sector', 'Islamabad', 'OPD', 'Active', NULL, 'General weakness', '2026-05-13 01:50:55', '2026-05-13 01:50:55', NULL),
(63, 'MRN-00031', 'ubaid', 'hassan jan', '2000-02-01', 'Male', 'A+', '0333450415', '0317665656', 'Father', '1730122232321', 'bara gate peshawa sadar', 'peshawar', 'IPD', 'Active', 2, 'The patient have some symptom of physco', '2026-06-01 20:38:42', '2026-06-01 20:38:42', NULL),
(64, 'MRN-00032', 'salman', 'salman gull', '2000-02-09', 'Male', 'A-', '03334504157', '03334504157', 'Father', '1231233212343', 'dora school suffaid dheri khatama nabowat chowk peshawar', 'Peshawar', 'IPD', 'Active', 2, 'No History', '2026-06-01 21:24:36', '2026-06-19 05:04:30', '2026-06-19 05:04:30'),
(65, 'MRN-00033', 'Kiel DuBuque', 'Tony97', '2026-01-10', 'Female', 'O+', '384-241-9962', 'Qatar', 'Decretum quibusdam conscendo.', '1222111111111', '43271 E 6 Avenue', 'Pearland', 'OPD', 'Active', 7, '610', '2026-06-15 04:09:43', '2026-06-19 01:08:53', '2026-06-19 01:08:53');

-- --------------------------------------------------------

--
-- Table structure for table `patient_discharges`
--

CREATE TABLE `patient_discharges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `bed_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `processed_by` bigint(20) UNSIGNED NOT NULL,
  `discharge_number` varchar(255) NOT NULL,
  `admitted_date` date NOT NULL,
  `discharge_date` date NOT NULL,
  `total_days` int(11) NOT NULL,
  `discharge_type` enum('Normal','LAMA','Referred','Expired','Absconded') NOT NULL DEFAULT 'Normal',
  `condition_at_discharge` enum('Recovered','Improved','Same','Deteriorated','Expired') NOT NULL DEFAULT 'Improved',
  `admission_diagnosis` text NOT NULL,
  `final_diagnosis` text NOT NULL,
  `treatment_summary` text NOT NULL,
  `procedures_done` text DEFAULT NULL,
  `discharge_instructions` text NOT NULL,
  `medications_on_discharge` text DEFAULT NULL,
  `diet_instructions` text DEFAULT NULL,
  `activity_instructions` text DEFAULT NULL,
  `follow_up_date` date DEFAULT NULL,
  `follow_up_with` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Draft','Final','Printed') NOT NULL DEFAULT 'Draft',
  `finalized_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patient_discharges`
--

INSERT INTO `patient_discharges` (`id`, `patient_id`, `bed_id`, `doctor_id`, `processed_by`, `discharge_number`, `admitted_date`, `discharge_date`, `total_days`, `discharge_type`, `condition_at_discharge`, `admission_diagnosis`, `final_diagnosis`, `treatment_summary`, `procedures_done`, `discharge_instructions`, `medications_on_discharge`, `diet_instructions`, `activity_instructions`, `follow_up_date`, `follow_up_with`, `notes`, `status`, `finalized_at`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 8, 11, 'DC-00001', '2026-05-13', '2026-06-17', 35, 'Normal', 'Improved', 'the dasdfe ;asl erj', 'asldnf werffase', 'ljasdf\'m awernasdf', 'asdfwefsdfe', 'sadfwefsdf', 'sdfaserasdfwaef', 'sdffwae', 'wwercsdf', NULL, 'samin afridi', 'asdfsdfsd', 'Final', '2026-06-17 06:39:36', '2026-06-17 06:38:28', '2026-06-17 06:39:36');

-- --------------------------------------------------------

--
-- Table structure for table `patient_doctor_orders`
--

CREATE TABLE `patient_doctor_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `bed_id` bigint(20) UNSIGNED DEFAULT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `acknowledged_by` bigint(20) UNSIGNED DEFAULT NULL,
  `order_number` varchar(255) NOT NULL,
  `order_type` enum('Medication','Investigation','Diet','Activity','Procedure','Monitoring','Consult','Discharge','Other') NOT NULL,
  `title` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `special_instructions` text DEFAULT NULL,
  `priority` enum('Routine','Urgent','STAT') NOT NULL DEFAULT 'Routine',
  `status` enum('Pending','Acknowledged','In Progress','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `ordered_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `acknowledged_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patient_doctor_orders`
--

INSERT INTO `patient_doctor_orders` (`id`, `patient_id`, `bed_id`, `doctor_id`, `acknowledged_by`, `order_number`, `order_type`, `title`, `details`, `special_instructions`, `priority`, `status`, `ordered_at`, `acknowledged_at`, `completed_at`, `cancellation_reason`, `created_at`, `updated_at`) VALUES
(1, 44, 21, 1, 11, 'ORD-00001', 'Investigation', 'Investor Integration Planner', 'Constans colo conturbo maxime.', 'Abeo accusator combibo arbitro ustilo.', 'Urgent', 'Completed', '2026-06-17 06:42:09', '2026-06-17 01:42:01', '2026-06-17 01:42:09', NULL, '2026-06-17 01:41:51', '2026-06-17 01:42:09'),
(2, 44, 21, 9, 12, 'ORD-00002', 'Medication', 'paracetamol q', '3 times in a day', 'there is not special instruction', 'Urgent', 'Completed', '2026-06-19 09:44:20', '2026-06-19 03:01:11', '2026-06-19 04:44:20', NULL, '2026-06-19 02:59:29', '2026-06-19 04:44:20');

-- --------------------------------------------------------

--
-- Table structure for table `patient_nursing_notes`
--

CREATE TABLE `patient_nursing_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `bed_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nurse_id` bigint(20) UNSIGNED NOT NULL,
  `shift` enum('Morning','Afternoon','Evening','Night') NOT NULL,
  `note_type` enum('General','Medication Given','Procedure Done','Patient Complaint','Family Communication','Incident Report','Handover Note') NOT NULL DEFAULT 'General',
  `note` text NOT NULL,
  `interventions` text DEFAULT NULL,
  `patient_response` text DEFAULT NULL,
  `requires_doctor_attention` tinyint(1) NOT NULL DEFAULT 0,
  `is_urgent` tinyint(1) NOT NULL DEFAULT 0,
  `noted_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patient_nursing_notes`
--

INSERT INTO `patient_nursing_notes` (`id`, `patient_id`, `bed_id`, `nurse_id`, `shift`, `note_type`, `note`, `interventions`, `patient_response`, `requires_doctor_attention`, `is_urgent`, `noted_at`, `created_at`, `updated_at`) VALUES
(1, 44, 21, 12, 'Morning', 'Patient Complaint', 'Quis est in labore r', 'Mollitia pariatur E', 'Consectetur incididu', 0, 0, '2026-06-19 02:37:58', '2026-06-19 02:37:58', '2026-06-19 02:37:58'),
(2, 44, 21, 12, 'Night', 'Medication Given', 'Non et aut quia eaqu', 'Est natus incidunt', 'Provident ut volupt', 1, 1, '2026-06-19 02:50:14', '2026-06-19 02:50:14', '2026-06-19 02:50:14');

-- --------------------------------------------------------

--
-- Table structure for table `patient_visit_notes`
--

CREATE TABLE `patient_visit_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `bed_id` bigint(20) UNSIGNED DEFAULT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `subjective` text DEFAULT NULL,
  `objective` text DEFAULT NULL,
  `assessment` text DEFAULT NULL,
  `plan` text DEFAULT NULL,
  `examination_findings` text DEFAULT NULL,
  `diagnosis_codes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`diagnosis_codes`)),
  `follow_up_instructions` text DEFAULT NULL,
  `is_discharge_ready` tinyint(1) NOT NULL DEFAULT 0,
  `visit_type` enum('Morning Round','Evening Round','Emergency Visit','Consultation','Post-Op Review','Follow-up') NOT NULL DEFAULT 'Morning Round',
  `visited_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patient_visit_notes`
--

INSERT INTO `patient_visit_notes` (`id`, `patient_id`, `bed_id`, `doctor_id`, `subjective`, `objective`, `assessment`, `plan`, `examination_findings`, `diagnosis_codes`, `follow_up_instructions`, `is_discharge_ready`, `visit_type`, `visited_at`, `created_at`, `updated_at`) VALUES
(1, 44, 21, 9, 'nothing he says', 'crtitcal stone in there belly', 'procedure operation will be perform', 'operation and respiror', NULL, NULL, NULL, 0, 'Morning Round', '2026-06-19 02:58:16', '2026-06-19 02:58:16', '2026-06-19 02:58:16');

-- --------------------------------------------------------

--
-- Table structure for table `patient_vitals`
--

CREATE TABLE `patient_vitals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `bed_id` bigint(20) UNSIGNED DEFAULT NULL,
  `recorded_by` bigint(20) UNSIGNED NOT NULL,
  `temperature` decimal(5,2) DEFAULT NULL,
  `temperature_route` varchar(255) DEFAULT NULL,
  `pulse_rate` int(11) DEFAULT NULL,
  `pulse_rhythm` varchar(255) DEFAULT NULL,
  `respiratory_rate` int(11) DEFAULT NULL,
  `systolic_bp` int(11) DEFAULT NULL,
  `diastolic_bp` int(11) DEFAULT NULL,
  `bp_position` varchar(255) DEFAULT NULL,
  `oxygen_saturation` decimal(5,2) DEFAULT NULL,
  `oxygen_delivery` varchar(255) DEFAULT NULL,
  `blood_glucose` decimal(6,2) DEFAULT NULL,
  `blood_glucose_timing` varchar(255) DEFAULT NULL,
  `weight` decimal(6,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `bmi` decimal(5,2) DEFAULT NULL,
  `pain_score` int(11) DEFAULT NULL,
  `pain_location` varchar(255) DEFAULT NULL,
  `gcs_score` int(11) DEFAULT NULL,
  `gcs_eye` int(11) DEFAULT NULL,
  `gcs_verbal` int(11) DEFAULT NULL,
  `gcs_motor` int(11) DEFAULT NULL,
  `central_venous_pressure` int(11) DEFAULT NULL,
  `urine_output` decimal(8,2) DEFAULT NULL,
  `fluid_intake` decimal(8,2) DEFAULT NULL,
  `fluid_output` decimal(8,2) DEFAULT NULL,
  `shift` enum('Morning','Afternoon','Evening','Night') NOT NULL DEFAULT 'Morning',
  `notes` text DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patient_vitals`
--

INSERT INTO `patient_vitals` (`id`, `patient_id`, `bed_id`, `recorded_by`, `temperature`, `temperature_route`, `pulse_rate`, `pulse_rhythm`, `respiratory_rate`, `systolic_bp`, `diastolic_bp`, `bp_position`, `oxygen_saturation`, `oxygen_delivery`, `blood_glucose`, `blood_glucose_timing`, `weight`, `height`, `bmi`, `pain_score`, `pain_location`, `gcs_score`, `gcs_eye`, `gcs_verbal`, `gcs_motor`, `central_venous_pressure`, `urine_output`, `fluid_intake`, `fluid_output`, `shift`, `notes`, `recorded_at`, `created_at`, `updated_at`) VALUES
(1, 44, 21, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Morning', NULL, '2026-06-19 01:59:39', '2026-06-19 01:59:39', '2026-06-19 01:59:39'),
(2, 44, 21, 12, 98.60, NULL, 50, NULL, 5, 120, 80, NULL, 98.00, NULL, 120.00, NULL, 200.00, 180.00, 61.70, 3, NULL, 9, 4, 2, 3, NULL, NULL, NULL, NULL, 'Morning', 'this is just for the testing purpose not a proper data format is this', '2026-06-19 02:39:43', '2026-06-19 02:39:43', '2026-06-19 02:39:43'),
(3, 44, 21, 12, 99.00, NULL, 77, NULL, 14, 120, 70, NULL, 97.00, NULL, 110.00, NULL, 89.00, 170.00, 30.80, 3, NULL, 6, 2, 2, 2, NULL, NULL, NULL, NULL, 'Morning', NULL, '2026-06-19 02:55:08', '2026-06-19 02:55:08', '2026-06-19 02:55:08'),
(4, 44, 21, 12, 99.00, NULL, 77, NULL, 14, 120, 70, NULL, 97.00, NULL, 110.00, NULL, 89.00, 170.00, 30.80, 3, NULL, 6, 2, 2, 2, NULL, NULL, NULL, NULL, 'Morning', NULL, '2026-06-19 02:55:09', '2026-06-19 02:55:09', '2026-06-19 02:55:09');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_runs`
--

CREATE TABLE `payroll_runs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `run_number` varchar(255) NOT NULL,
  `year` year(4) NOT NULL,
  `month` tinyint(4) NOT NULL,
  `month_name` varchar(255) NOT NULL,
  `status` enum('Draft','Processing','Processed','Approved','Paid','Cancelled') NOT NULL DEFAULT 'Draft',
  `total_employees` int(11) NOT NULL DEFAULT 0,
  `total_gross` decimal(14,2) NOT NULL DEFAULT 0.00,
  `total_deductions` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_net` decimal(14,2) NOT NULL DEFAULT 0.00,
  `payment_date` date DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_runs`
--

INSERT INTO `payroll_runs` (`id`, `run_number`, `year`, `month`, `month_name`, `status`, `total_employees`, `total_gross`, `total_deductions`, `total_net`, `payment_date`, `processed_at`, `approved_at`, `created_by`, `approved_by`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 'PR-2026-06', '2026', 6, 'June 2026', 'Paid', 2, 429000.00, 59200.00, 369800.00, '2026-06-11', '2026-06-11 04:16:40', '2026-06-11 04:16:45', 11, 11, NULL, '2026-06-11 04:16:40', '2026-06-11 05:41:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payslips`
--

CREATE TABLE `payslips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_run_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `payslip_number` varchar(255) NOT NULL,
  `salary_structure_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_working_days` int(11) NOT NULL DEFAULT 0,
  `present_days` int(11) NOT NULL DEFAULT 0,
  `absent_days` int(11) NOT NULL DEFAULT 0,
  `late_days` int(11) NOT NULL DEFAULT 0,
  `half_days` int(11) NOT NULL DEFAULT 0,
  `leave_days` int(11) NOT NULL DEFAULT 0,
  `holiday_days` int(11) NOT NULL DEFAULT 0,
  `overtime_hours` decimal(5,2) NOT NULL DEFAULT 0.00,
  `basic_salary` decimal(12,2) NOT NULL DEFAULT 0.00,
  `house_rent_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `medical_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `transport_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `meal_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `special_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `overtime_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bonus` decimal(10,2) NOT NULL DEFAULT 0.00,
  `arrears` decimal(10,2) NOT NULL DEFAULT 0.00,
  `gross_salary` decimal(12,2) NOT NULL DEFAULT 0.00,
  `income_tax_monthly` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_slab` varchar(255) DEFAULT NULL,
  `eobi_employee_share` decimal(10,2) NOT NULL DEFAULT 0.00,
  `provident_fund` decimal(10,2) NOT NULL DEFAULT 0.00,
  `loan_deduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `absent_deduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `late_deduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_deduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_deduction_description` text DEFAULT NULL,
  `total_deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_salary` decimal(12,2) NOT NULL DEFAULT 0.00,
  `per_day_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('Bank Transfer','Cash','Cheque') NOT NULL DEFAULT 'Bank Transfer',
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `paid_on` date DEFAULT NULL,
  `transaction_reference` varchar(255) DEFAULT NULL,
  `status` enum('Draft','Generated','Approved','Paid','Cancelled') NOT NULL DEFAULT 'Draft',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payslips`
--

INSERT INTO `payslips` (`id`, `payroll_run_id`, `employee_id`, `payslip_number`, `salary_structure_id`, `total_working_days`, `present_days`, `absent_days`, `late_days`, `half_days`, `leave_days`, `holiday_days`, `overtime_hours`, `basic_salary`, `house_rent_allowance`, `medical_allowance`, `transport_allowance`, `meal_allowance`, `special_allowance`, `other_allowance`, `overtime_amount`, `bonus`, `arrears`, `gross_salary`, `income_tax_monthly`, `tax_slab`, `eobi_employee_share`, `provident_fund`, `loan_deduction`, `absent_deduction`, `late_deduction`, `other_deduction`, `other_deduction_description`, `total_deductions`, `net_salary`, `per_day_salary`, `payment_method`, `bank_account_number`, `bank_name`, `is_paid`, `paid_on`, `transaction_reference`, `status`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 3, 1, 'PS-2026-06-001', 2, 22, 1, 0, 0, 0, 0, 0, 0.00, 200000.00, 15000.00, 10000.00, 10000.00, 10000.00, 5000.00, 1000.00, 0.00, 0.00, 0.00, 251000.00, 20000.00, '15', 10000.00, 7000.00, 2000.00, 0.00, 0.00, 1000.00, NULL, 40000.00, 211000.00, 11409.09, 'Bank Transfer', '3003950227', 'The Bank Of Khyber', 1, '2026-06-11', NULL, 'Paid', NULL, '2026-06-11 04:16:40', '2026-06-11 05:41:15', NULL),
(4, 3, 2, 'PS-2026-06-002', 1, 22, 1, 0, 0, 0, 0, 0, 0.00, 160000.00, 0.00, 5000.00, 0.00, 10000.00, 1000.00, 2000.00, 0.00, 0.00, 0.00, 178000.00, 10000.00, '10', 3000.00, 5000.00, 500.00, 0.00, 0.00, 700.00, NULL, 19200.00, 158800.00, 8090.91, 'Bank Transfer', NULL, NULL, 1, '2026-06-11', NULL, 'Paid', NULL, '2026-06-11 04:16:40', '2026-06-11 05:41:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prescription_number` varchar(255) NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `appointment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('Pending','Partial','Dispensed','Cancelled') NOT NULL DEFAULT 'Pending',
  `prescribed_date` date NOT NULL,
  `valid_until` date DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `prescription_number`, `patient_id`, `doctor_id`, `appointment_id`, `status`, `prescribed_date`, `valid_until`, `diagnosis`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'RX-00001', 1, 1, NULL, 'Pending', '2026-05-11', '2026-05-13', 'flue and fiver', NULL, '2026-05-10 20:10:35', '2026-05-10 20:10:35', NULL),
(2, 'RX-00002', 1, 1, NULL, 'Dispensed', '2026-05-11', '2026-05-13', 'flue and fiver', NULL, '2026-05-10 20:44:31', '2026-05-10 20:44:50', NULL),
(3, 'RX-00003', 33, 1, NULL, 'Dispensed', '2026-05-18', '2026-05-21', 'flue and fiver', NULL, '2026-05-17 20:53:10', '2026-05-17 20:53:18', NULL),
(4, 'RX-00004', 42, 1, NULL, 'Dispensed', '2026-05-18', '2026-05-28', 'flue and fiver', NULL, '2026-05-17 21:23:42', '2026-05-17 21:23:52', NULL),
(5, 'RX-00005', 45, 2, NULL, 'Dispensed', '2026-05-18', '2026-05-29', NULL, NULL, '2026-05-18 00:51:45', '2026-05-18 00:51:51', NULL),
(6, 'RX-00006', 34, 1, NULL, 'Dispensed', '2026-05-18', '2026-05-21', 'flue and fiver', NULL, '2026-05-18 00:58:45', '2026-05-18 00:58:50', NULL),
(7, 'RX-00007', 1, 1, NULL, 'Dispensed', '2026-05-19', '2026-05-30', 'flue and fiver', NULL, '2026-05-18 22:00:12', '2026-05-18 22:01:50', NULL),
(8, 'RX-00008', 44, 1, NULL, 'Dispensed', '2026-05-22', NULL, 'kidney stone and back disk displace', 'The patient have kidney stone and have the disk displace issue which is some critical issue', '2026-05-22 05:28:27', '2026-05-22 05:28:40', NULL),
(9, 'RX-00009', 63, 2, NULL, 'Dispensed', '2026-06-03', '2026-06-19', 'flue and fiver', NULL, '2026-06-03 08:22:44', '2026-06-03 08:22:52', NULL),
(10, 'RX-00010', 35, 9, NULL, 'Dispensed', '2026-06-04', '2026-06-13', 'fiver and flue', 'There is the optional and the follow', '2026-06-04 00:06:09', '2026-06-04 20:45:14', NULL),
(11, 'RX-00011', 35, 7, NULL, 'Pending', '2026-06-08', '2026-06-11', 'fiver and flue', NULL, '2026-06-07 21:44:59', '2026-06-07 21:44:59', NULL),
(12, 'RX-00012', 35, 9, NULL, 'Pending', '2026-06-08', '2026-06-18', NULL, NULL, '2026-06-07 21:50:40', '2026-06-07 21:50:40', NULL),
(13, 'RX-00013', 35, 9, NULL, 'Dispensed', '2026-06-15', '2026-06-18', NULL, NULL, '2026-06-15 04:12:38', '2026-06-16 06:03:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prescription_items`
--

CREATE TABLE `prescription_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prescription_id` bigint(20) UNSIGNED NOT NULL,
  `medicine_id` bigint(20) UNSIGNED NOT NULL,
  `dosage` varchar(255) NOT NULL,
  `frequency` varchar(255) NOT NULL,
  `duration_days` int(11) NOT NULL DEFAULT 1,
  `quantity` int(11) NOT NULL,
  `dispensed_qty` int(11) NOT NULL DEFAULT 0,
  `instructions` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prescription_items`
--

INSERT INTO `prescription_items` (`id`, `prescription_id`, `medicine_id`, `dosage`, `frequency`, `duration_days`, `quantity`, `dispensed_qty`, `instructions`, `created_at`, `updated_at`) VALUES
(2, 2, 33, '500mg', '1-1-1', 3, 9, 9, 'after meal 3 times in a day', '2026-05-10 20:44:31', '2026-05-10 20:44:50'),
(3, 3, 34, '500mg', '1-1-1', 3, 10, 10, 'after meal 3 times in a day', '2026-05-17 20:53:10', '2026-05-17 20:53:18'),
(4, 4, 34, '500mg', '1-1-1', 3, 10, 10, 'after meal 3 times in a day', '2026-05-17 21:23:42', '2026-05-17 21:23:52'),
(5, 4, 35, '500 mg', '1-1-1', 3, 10, 10, 'after meal', '2026-05-17 21:23:42', '2026-05-17 21:23:52'),
(6, 5, 34, '1000 mg', '1-1-1', 6, 17, 17, NULL, '2026-05-18 00:51:45', '2026-05-18 00:51:51'),
(7, 6, 34, '1000 mg', '1-1-1', 8, 19, 19, NULL, '2026-05-18 00:58:45', '2026-05-18 00:58:50'),
(8, 7, 34, '500mg', '1-0-1', 3, 5, 5, NULL, '2026-05-18 22:00:12', '2026-05-18 22:01:50'),
(9, 8, 34, '500mg', '1-1-1', 3, 10, 10, 'after meal 3 times in a day', '2026-05-22 05:28:27', '2026-05-22 05:28:40'),
(10, 8, 36, '500 mg', '1-1-1', 3, 9, 9, 'after meal', '2026-05-22 05:28:27', '2026-05-22 05:28:40'),
(11, 8, 35, '100', '1-1-1', 3, 9, 9, 'Before The Meal', '2026-05-22 05:28:27', '2026-05-22 05:28:40'),
(12, 9, 34, '500mg', '1-1-1', 3, 10, 10, 'after meal 3 times in a day', '2026-06-03 08:22:44', '2026-06-03 08:22:52'),
(13, 10, 34, '1000 mg', '1-1-1', 7, 21, 21, 'After Meal', '2026-06-04 00:06:09', '2026-06-04 20:45:14'),
(14, 11, 33, '1000 mg', '1-1-1', 3, 1, 0, 'After Meal', '2026-06-07 21:44:59', '2026-06-07 21:44:59'),
(15, 12, 35, '1000 mg', '1-1-1', 3, 4, 0, 'After Meal', '2026-06-07 21:50:40', '2026-06-07 21:50:40'),
(16, 13, 34, '1000 mg', '0-0-1', 3, 4, 4, 'After Meal', '2026-06-15 04:12:38', '2026-06-16 06:03:34');

-- --------------------------------------------------------

--
-- Table structure for table `radiology_body_parts`
--

CREATE TABLE `radiology_body_parts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `region` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `radiology_body_parts`
--

INSERT INTO `radiology_body_parts` (`id`, `name`, `code`, `region`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Brain', 'BP-BRAIN', 'Head & Neck', 1, '2026-05-19 00:37:19', '2026-05-19 00:37:19'),
(2, 'Skull', 'BP-SKULL', 'Upper Extremity', 1, '2026-05-19 00:37:54', '2026-05-19 00:37:54'),
(3, 'Orbit (Eye)', 'BP-ORBIT', 'Head & Neck', 1, '2026-05-19 00:38:18', '2026-05-19 00:38:18'),
(4, 'Paranasal Sinuses', 'BP-SINUS', 'Head & Neck', 1, '2026-05-19 00:38:42', '2026-05-19 00:38:42'),
(5, 'Temporal Bone', 'BP-TEMPORAL', 'Head & Neck', 1, '2026-05-19 00:39:05', '2026-05-19 00:39:05'),
(6, 'Mastoid', 'BP-MASTOID', 'Head & Neck', 1, '2026-05-19 00:39:21', '2026-05-19 00:39:21');

-- --------------------------------------------------------

--
-- Table structure for table `radiology_consents`
--

CREATE TABLE `radiology_consents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `radiology_order_id` bigint(20) UNSIGNED NOT NULL,
  `consent_type` varchar(255) NOT NULL,
  `is_signed` tinyint(1) NOT NULL DEFAULT 0,
  `signed_by` varchar(255) DEFAULT NULL,
  `relationship` varchar(255) DEFAULT NULL,
  `signed_at` timestamp NULL DEFAULT NULL,
  `witness` varchar(255) DEFAULT NULL,
  `signature_path` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `radiology_consents`
--

INSERT INTO `radiology_consents` (`id`, `radiology_order_id`, `consent_type`, `is_signed`, `signed_by`, `relationship`, `signed_at`, `witness`, `signature_path`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'Contrast/Procedure Consent', 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-19 01:08:16', '2026-05-19 01:08:16'),
(2, 3, 'Contrast/Procedure Consent', 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-22 05:36:14', '2026-05-22 05:36:14'),
(3, 4, 'Contrast/Procedure Consent', 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-03 23:42:43', '2026-06-03 23:42:43');

-- --------------------------------------------------------

--
-- Table structure for table `radiology_exams`
--

CREATE TABLE `radiology_exams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `exam_code` varchar(255) NOT NULL,
  `modality_id` bigint(20) UNSIGNED NOT NULL,
  `body_part_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `requires_contrast` tinyint(1) NOT NULL DEFAULT 0,
  `contrast_type` varchar(255) DEFAULT NULL,
  `requires_preparation` tinyint(1) NOT NULL DEFAULT 0,
  `preparation_instructions` text DEFAULT NULL,
  `turnaround_hours` int(11) NOT NULL DEFAULT 24,
  `duration_minutes` int(11) NOT NULL DEFAULT 30,
  `clinical_indications` text DEFAULT NULL,
  `contraindications` text DEFAULT NULL,
  `requires_consent` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `radiology_exams`
--

INSERT INTO `radiology_exams` (`id`, `name`, `exam_code`, `modality_id`, `body_part_id`, `price`, `requires_contrast`, `contrast_type`, `requires_preparation`, `preparation_instructions`, `turnaround_hours`, `duration_minutes`, `clinical_indications`, `contraindications`, `requires_consent`, `is_active`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Chest X-Ray PA View', 'RAD-001', 1, 6, 2000.00, 1, 'Iodine', 1, '5 Hour Fasting', 2, 5, 'Cough, chest pain, TB screening, pneumonia', 'Pregnancy (first trimester)', 1, 1, 'prepare for you chest x ray do not move during exam empty your pockets', '2026-05-19 01:02:52', '2026-05-19 01:02:52'),
(2, 'MRI Brain Plain', 'RAD-006', 3, 1, 5000.00, 0, NULL, 0, NULL, 48, 30, 'Today', 'don\'t have pregnancy', 0, 1, 'Take a grave breath', '2026-05-19 01:05:14', '2026-06-07 20:37:14');

-- --------------------------------------------------------

--
-- Table structure for table `radiology_images`
--

CREATE TABLE `radiology_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `radiology_order_item_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(255) NOT NULL DEFAULT 'image',
  `mime_type` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `dicom_series_uid` varchar(255) DEFAULT NULL,
  `dicom_instance_uid` varchar(255) DEFAULT NULL,
  `view_position` varchar(255) DEFAULT NULL,
  `dicom_metadata` text DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `radiology_images`
--

INSERT INTO `radiology_images` (`id`, `radiology_order_item_id`, `file_path`, `file_name`, `file_type`, `mime_type`, `file_size`, `dicom_series_uid`, `dicom_instance_uid`, `view_position`, `dicom_metadata`, `is_primary`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'radiology/images/RAD-00001/WOF6ELlnOAooH0zLuQy0ZpapK0nItxmnzOmjkVGq.png', '20240506_035854-removebg-preview.JPEG', 'pdf', 'image/png', 242393, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-19 01:10:07', '2026-05-19 01:10:07'),
(2, 2, 'radiology/images/RAD-00001/kH4QavTsauVhFc4g1kAZGAcQszc4XPdhGRDCaOeG.jpg', '484344605_2124705451383987_7847886505100047727_n.jpg', 'pdf', 'image/jpeg', 650983, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-19 01:11:49', '2026-05-19 01:11:49'),
(3, 4, 'radiology/images/RAD-00003/spxyXMvswGhZib8C0dFoer8pAEwyGFgnu934v9JN.jpg', '20250426_bing.jpg', 'pdf', 'image/jpeg', 2315069, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-22 05:37:25', '2026-05-22 05:37:25'),
(4, 5, 'radiology/images/RAD-00003/xnJ5Hmi10IxiozZSlKcCuZaWTqnrFDUpk1LWroA9.jpg', 'copy of fullsizerender-32.jpg', 'pdf', 'image/jpeg', 1583659, NULL, NULL, NULL, NULL, 1, NULL, '2026-05-22 05:37:43', '2026-05-22 05:37:43'),
(5, 6, 'radiology/images/RAD-00004/IpVQ7MXRIOcQCtDcZCMYTq4szLGKx0mJrXf8myDc.png', 'Screenshot 2026-06-01 122838.png', 'pdf', 'image/png', 322582, NULL, NULL, NULL, NULL, 1, NULL, '2026-06-04 19:54:58', '2026-06-04 19:54:58'),
(6, 7, 'radiology/images/RAD-00004/bmpC1eSE7eOCTMrPOn0DPntrmjnQqlyN1eSjxDr3.png', 'Screenshot 2026-06-01 115621.png', 'pdf', 'image/png', 271718, NULL, NULL, NULL, NULL, 1, NULL, '2026-06-04 20:26:44', '2026-06-04 20:26:44');

-- --------------------------------------------------------

--
-- Table structure for table `radiology_modalities`
--

CREATE TABLE `radiology_modalities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `requires_contrast` tinyint(1) NOT NULL DEFAULT 0,
  `requires_preparation` tinyint(1) NOT NULL DEFAULT 0,
  `preparation_instructions` text DEFAULT NULL,
  `average_duration_minutes` int(11) NOT NULL DEFAULT 30,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `radiology_modalities`
--

INSERT INTO `radiology_modalities` (`id`, `name`, `code`, `description`, `requires_contrast`, `requires_preparation`, `preparation_instructions`, `average_duration_minutes`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'X-Ray', 'MOD-XRAY', 'Uses ionizing radiation to produce images of bones and internal organs. Fast and widely available.', 1, 1, 'don\'t eat from the mid night', 30, 1, '2026-05-19 00:44:26', '2026-05-19 00:44:26'),
(2, 'CT Scan', 'MOD-CT', 'Computed Tomography uses X-rays and computer processing to create cross-sectional images.', 1, 0, NULL, 30, 1, '2026-05-19 00:55:07', '2026-05-19 00:55:07'),
(3, 'MRI', 'MOD-MRI', 'Magnetic Resonance Imaging uses strong magnetic fields and radio waves for detailed soft tissue imaging.', 0, 0, NULL, 30, 1, '2026-05-19 00:55:34', '2026-05-19 00:55:34'),
(4, 'Ultrasound', 'MOD-USG', 'Uses high-frequency sound waves to create real-time images. Safe for pregnancy.', 0, 0, NULL, 30, 1, '2026-05-19 00:55:56', '2026-05-19 00:55:56');

-- --------------------------------------------------------

--
-- Table structure for table `radiology_orders`
--

CREATE TABLE `radiology_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `appointment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_date` datetime NOT NULL,
  `scheduled_at` datetime DEFAULT NULL,
  `clinical_history` text DEFAULT NULL,
  `clinical_indication` text DEFAULT NULL,
  `priority` enum('Routine','Urgent','STAT') NOT NULL DEFAULT 'Routine',
  `status` enum('Pending','Scheduled','In Progress','Scan Completed','Reporting','Reported','Verified','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('Unpaid','Partial','Paid') NOT NULL DEFAULT 'Unpaid',
  `report_delivered` tinyint(1) NOT NULL DEFAULT 0,
  `report_delivered_at` timestamp NULL DEFAULT NULL,
  `delivered_to` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `radiology_orders`
--

INSERT INTO `radiology_orders` (`id`, `order_number`, `patient_id`, `doctor_id`, `appointment_id`, `order_date`, `scheduled_at`, `clinical_history`, `clinical_indication`, `priority`, `status`, `total_amount`, `discount`, `net_amount`, `paid_amount`, `payment_status`, `report_delivered`, `report_delivered_at`, `delivered_to`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'RAD-00001', 1, 1, NULL, '2026-05-19 11:07:00', '2026-05-20 16:07:00', NULL, NULL, 'Urgent', 'Delivered', 7000.00, 0.00, 7000.00, 7000.00, 'Paid', 1, '2026-05-19 01:13:25', 'Shehreyar Khan', NULL, '2026-05-19 01:08:16', '2026-05-21 20:01:02', NULL),
(2, 'RAD-00002', 33, 1, NULL, '2026-05-19 11:32:00', '2026-05-20 16:32:00', NULL, NULL, 'Routine', 'Cancelled', 5000.00, 0.00, 5000.00, 0.00, 'Unpaid', 0, NULL, NULL, NULL, '2026-05-19 01:32:34', '2026-05-19 01:32:49', NULL),
(3, 'RAD-00003', 44, 1, NULL, '2026-05-22 15:33:00', '2026-05-23 20:33:00', 'The patient have the kidney stone and abdomin pain disk displace', 'we need to measures it closely to find out the exact issue', 'Routine', 'Delivered', 7000.00, 0.00, 7000.00, 0.00, 'Unpaid', 1, '2026-06-04 20:08:32', 'Sana Pervez', 'we will utilize the report and will proceed with further', '2026-05-22 05:36:14', '2026-06-04 20:08:32', NULL),
(4, 'RAD-00004', 35, 9, NULL, '2026-06-04 09:30:00', '2026-06-04 14:42:00', 'nothing', 'for the brain', 'Urgent', 'Verified', 7000.00, 0.00, 7000.00, 7000.00, 'Paid', 1, '2026-06-04 20:08:40', 'Zubair Sheikh', 'nothing to show thats why', '2026-06-03 23:42:43', '2026-06-05 00:54:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `radiology_order_items`
--

CREATE TABLE `radiology_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `radiology_order_id` bigint(20) UNSIGNED NOT NULL,
  `radiology_exam_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `final_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('Pending','Scheduled','In Progress','Scan Completed','Reported','Cancelled') NOT NULL DEFAULT 'Pending',
  `scanned_at` datetime DEFAULT NULL,
  `technician_name` varchar(255) DEFAULT NULL,
  `equipment_used` varchar(255) DEFAULT NULL,
  `contrast_used` tinyint(1) NOT NULL DEFAULT 0,
  `contrast_agent` varchar(255) DEFAULT NULL,
  `contrast_dose_ml` decimal(8,2) DEFAULT NULL,
  `contrast_reaction` tinyint(1) NOT NULL DEFAULT 0,
  `contrast_reaction_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `radiology_order_items`
--

INSERT INTO `radiology_order_items` (`id`, `radiology_order_id`, `radiology_exam_id`, `price`, `discount`, `final_price`, `status`, `scanned_at`, `technician_name`, `equipment_used`, `contrast_used`, `contrast_agent`, `contrast_dose_ml`, `contrast_reaction`, `contrast_reaction_notes`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 5000.00, 0.00, 5000.00, 'Reported', '2026-05-19 11:10:06', 'Sanam Khan', 'CT SACAN', 0, NULL, NULL, 0, NULL, '2026-05-19 01:08:16', '2026-05-19 01:11:20'),
(2, 1, 1, 2000.00, 0.00, 2000.00, 'Reported', '2026-05-19 11:11:49', 'Sanam Khan', 'CT SACAN', 0, NULL, NULL, 0, NULL, '2026-05-19 01:08:16', '2026-05-19 01:12:37'),
(3, 2, 2, 5000.00, 0.00, 5000.00, 'Cancelled', NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, '2026-05-19 01:32:34', '2026-05-19 01:32:49'),
(4, 3, 2, 5000.00, 0.00, 5000.00, 'Reported', '2026-05-22 15:37:23', 'Shehreyar zafar', 'CT SACAN', 0, NULL, NULL, 0, NULL, '2026-05-22 05:36:14', '2026-05-22 06:37:39'),
(5, 3, 1, 2000.00, 0.00, 2000.00, 'Reported', '2026-05-22 15:37:43', 'Shehreyar zafar', 'CT SACAN', 0, NULL, NULL, 0, NULL, '2026-05-22 05:36:14', '2026-05-22 06:40:45'),
(6, 4, 2, 5000.00, 0.00, 5000.00, 'Reported', '2026-06-05 05:54:56', 'sanam javid', 'Room 14', 0, NULL, NULL, 0, NULL, '2026-06-03 23:42:43', '2026-06-04 20:00:13'),
(7, 4, 1, 2000.00, 0.00, 2000.00, 'Reported', '2026-06-05 06:26:44', 'pervaiz mash', 'xray digital', 1, 'OMNIPAQUE', 30.00, 1, 'Patient cause vomiting', '2026-06-03 23:42:43', '2026-06-04 20:27:20');

-- --------------------------------------------------------

--
-- Table structure for table `radiology_reports`
--

CREATE TABLE `radiology_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `radiology_order_item_id` bigint(20) UNSIGNED NOT NULL,
  `findings` text DEFAULT NULL,
  `impression` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `comparison` varchar(255) DEFAULT NULL,
  `is_critical` tinyint(1) NOT NULL DEFAULT 0,
  `critical_notes` text DEFAULT NULL,
  `critical_notified_at` timestamp NULL DEFAULT NULL,
  `critical_notified_to` varchar(255) DEFAULT NULL,
  `status` enum('Draft','Pending Verification','Verified','Amended') NOT NULL DEFAULT 'Draft',
  `reported_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reported_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `amendment_reason` text DEFAULT NULL,
  `amended_by` bigint(20) UNSIGNED DEFAULT NULL,
  `amended_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `radiology_reports`
--

INSERT INTO `radiology_reports` (`id`, `radiology_order_item_id`, `findings`, `impression`, `recommendations`, `comparison`, `is_critical`, `critical_notes`, `critical_notified_at`, `critical_notified_to`, `status`, `reported_by`, `reported_at`, `verified_by`, `verified_at`, `is_verified`, `amendment_reason`, `amended_by`, `amended_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'NOTHIBNG BEING SERIOUS', 'THER NOT EVIDENCE TO HAVE SOMETING CRATICAL', 'NO', 'FOUND SOME THING', 0, NULL, NULL, NULL, 'Verified', NULL, '2026-05-19 01:11:20', NULL, '2026-05-19 01:11:29', 1, NULL, NULL, NULL, '2026-05-19 01:11:20', '2026-05-19 01:11:29'),
(2, 2, 'HAVE SOME BIG ISSUE', 'WE DID NOT SEE THE ISSUE CLEARLY', 'TRANSFER TO SHAUKIAT KHANAM', 'dfd', 0, NULL, NULL, NULL, 'Verified', NULL, '2026-05-19 01:12:37', NULL, '2026-05-19 01:12:42', 1, NULL, NULL, NULL, '2026-05-19 01:12:37', '2026-05-19 01:12:42'),
(3, 4, 'in the ct scan the we find that the kidney have mal function and blockage of neurons which prevant the function of the abdomin function also have some infection in the kidney', 'we will be perform the surgery and remove the the stones form the kidney', NULL, 'compare CT SCAIN', 1, 'chances of kidney failure', '2026-05-22 06:37:39', 'kamran akmal', 'Verified', NULL, '2026-05-22 06:37:39', NULL, '2026-05-22 06:40:54', 1, NULL, NULL, NULL, '2026-05-22 06:37:39', '2026-05-22 06:40:54'),
(4, 5, 'In the chest there is the mucas which make the blockage from lungs function', 'we will give the antibiotic for the mucus to remove it from the lungs to function it properly', 'there is no need for further procedure', 'Compare from the CT SCAN', 0, NULL, NULL, NULL, 'Verified', NULL, '2026-05-22 06:40:45', NULL, '2026-05-22 06:40:49', 1, NULL, NULL, NULL, '2026-05-22 06:40:45', '2026-05-22 06:40:49'),
(5, 6, 'Hemorrhage: Blood appears very bright white on a CT scan. The report will note the type and location, such as a subdural, epidural, or intracerebral hematoma.Aneurysm or Clots: The report might note blocked blood vessels (infarct) or weakened, bulging areas.Calcification: Calcium deposits or hardening of the blood vessels may be\r\nBrain Tissue (Parenchyma)Stroke or Infarction: Dead or damaged brain tissue.Edema: Swelling in the brain tissue, which appears darker.Tumors or Lesions: Abnormal growths or masses, which might require a CT scan with contrast (special dye) for better visibility.', 'Impression:Acute 6 mm right subdural hematoma along the cerebral hemisphere.Associated mass effect causing a 3 mm leftward midline shift and mild compression of the right lateral ventricle.Non-displaced linear fracture of the right parietal bone\r\nImpression:Early signs of an acute ischemic infarct in the left middle cerebral artery (MCA) territory, evidenced by loss of grey-white matter differentiation.No evidence of acute intracranial hemorrhage.Conclusion / Next Steps: Findings are highly suggestive of an acute stroke. Recommend emergent clinical correlation for potential clot-busting therapy (tPA) or mechanical thrombectomy if within the appropriate time window.', 'Brain bleeds require immediate specialist evaluation and tight blood pressure monitoring.Text: \"Recommend urgent neurosurgical consultation and immediate clinical stabilization. Consider a repeat non-contrast head CT in 6 hours to monitor for hematoma expansion.\"', 'the patient have the brain tumor and no symptom of arthrites', 1, 'need emergency assestance', '2026-06-04 20:00:13', 'samin Afridi', 'Verified', 9, '2026-06-04 20:00:13', 9, '2026-06-04 20:00:27', 1, NULL, NULL, NULL, '2026-06-04 20:00:13', '2026-06-04 20:00:27'),
(6, 7, 'Brain bleeds require immediate specialist evaluation and tight blood pressure monitoring.Text: \"Recommend urgent neurosurgical consultation and immediate clinical stabilization. Consider a repeat non-contrast head CT in 6 hours to monitor for hematoma expansion.\"', 'Impression:Acute 6 mm right subdural hematoma along the cerebral hemisphere.Associated mass effect causing a 3 mm leftward midline shift and mild compression of the right lateral ventricle.Non-displaced linear fracture of the right parietal bone', 'Brain bleeds require immediate specialist evaluation and tight blood pressure monitoring.Text: \"Recommend urgent neurosurgical consultation and immediate clinical stabilization. Consider a repeat non-contrast head CT in 6 hours to monitor for hematoma expansion.\"', 'the patient have the brain tumor and no symptom of arthrites', 0, NULL, NULL, NULL, 'Verified', 9, '2026-06-04 20:27:20', 9, '2026-06-04 20:27:40', 1, NULL, NULL, NULL, '2026-06-04 20:27:20', '2026-06-04 20:27:40');

-- --------------------------------------------------------

--
-- Table structure for table `salary_structures`
--

CREATE TABLE `salary_structures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `basic_salary` decimal(12,2) NOT NULL DEFAULT 0.00,
  `house_rent_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `medical_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `transport_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `meal_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `special_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_allowance_description` text DEFAULT NULL,
  `gross_salary` decimal(12,2) NOT NULL DEFAULT 0.00,
  `eobi_applicable` tinyint(1) NOT NULL DEFAULT 1,
  `eobi_employee_share` decimal(10,2) NOT NULL DEFAULT 0.00,
  `eobi_employer_share` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_tax_exempt` tinyint(1) NOT NULL DEFAULT 0,
  `tax_slab` varchar(255) DEFAULT NULL,
  `income_tax_monthly` decimal(10,2) NOT NULL DEFAULT 0.00,
  `provident_fund` decimal(10,2) NOT NULL DEFAULT 0.00,
  `loan_deduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_deduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_deduction_description` text DEFAULT NULL,
  `total_deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_salary` decimal(12,2) NOT NULL DEFAULT 0.00,
  `effective_from` date NOT NULL,
  `effective_to` date DEFAULT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_structures`
--

INSERT INTO `salary_structures` (`id`, `employee_id`, `basic_salary`, `house_rent_allowance`, `medical_allowance`, `transport_allowance`, `meal_allowance`, `special_allowance`, `other_allowance`, `other_allowance_description`, `gross_salary`, `eobi_applicable`, `eobi_employee_share`, `eobi_employer_share`, `is_tax_exempt`, `tax_slab`, `income_tax_monthly`, `provident_fund`, `loan_deduction`, `other_deduction`, `other_deduction_description`, `total_deductions`, `net_salary`, `effective_from`, `effective_to`, `is_current`, `notes`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 160000.00, 0.00, 5000.00, 0.00, 10000.00, 1000.00, 2000.00, '3000', 178000.00, 1, 3000.00, 0.00, 1, '10', 10000.00, 5000.00, 500.00, 700.00, NULL, 19200.00, 158800.00, '2026-06-10', NULL, 1, NULL, 3, '2026-06-09 21:06:55', '2026-06-09 21:06:55', NULL),
(2, 1, 200000.00, 15000.00, 10000.00, 10000.00, 10000.00, 5000.00, 1000.00, '500', 251000.00, 1, 10000.00, 5000.00, 1, '15', 20000.00, 7000.00, 2000.00, 1000.00, NULL, 40000.00, 211000.00, '2026-06-10', NULL, 1, NULL, 3, '2026-06-09 21:19:45', '2026-06-09 21:19:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3bc85GoyVwwwLxJXePYuUNeMQHlzpSdNsAHEFMTp', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiV3BZaWtreU5aUUZCUEREYlNiVFd4QXJ0VXBsWUJSWXJ0ZjYweXRQdyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcGF0aWVudHMiO3M6NToicm91dGUiO3M6MTQ6InBhdGllbnRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Nzt9', 1781863928),
('HNsDmNQ1uXyyKZt8T9GcUAJEUAwBP91DhnESCr9L', 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTWNLNDNwc0FVNHFmTGNSQUdoTmQxZWhmQWlUOXptd3M1bXBQOUQ5ZyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI0OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvb3QiO3M6NToicm91dGUiO3M6ODoib3QuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMjt9', 1781863987),
('P0gcaCkwiCCjl99CSGzecQFdGGoI8Q3Gozkby0wF', 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibWt4dzBGV045NklBQ0ZEYzBock5BOHdiSE02cGw2QmdIOVFZU0VOQyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM4OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcGF0aWVudHMvNjMvZWRpdCI7czo1OiJyb3V0ZSI7czoxMzoicGF0aWVudHMuZWRpdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjExO30=', 1781863571),
('pcOCWNMb0hFO3i7LumsJr6AeuMjHyfo5nlYj2uAA', 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiaERiSlB1NGtoMHBObWxCcjRUbHJyVFNpZldxSlN0QlFBMGw2RmIwTSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ0OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvd2FyZC9udXJzZS1hc3NpZ25tZW50cyI7czo1OiJyb3V0ZSI7czoyODoid2FyZC5udXJzZS1hc3NpZ25tZW50cy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjExO30=', 1781851811);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `label` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `group`, `key`, `value`, `type`, `label`, `description`, `created_at`, `updated_at`) VALUES
(1, 'general', 'hospital_name', 'Leady Reading Hospital', 'text', 'Hospital Name', NULL, '2026-06-12 02:21:50', '2026-06-12 05:19:05'),
(2, 'general', 'hospital_slogan', 'Caring for a Better Tomorrow', 'text', 'Slogan / Tagline', NULL, '2026-06-12 02:21:50', '2026-06-12 05:19:05'),
(3, 'general', 'hospital_address', 'Peshawar, Khyber Pakhtunkhwa, Pakistan', 'text', 'Address', NULL, '2026-06-12 02:21:50', '2026-06-12 05:19:05'),
(4, 'general', 'hospital_phone', '03334504157', 'text', 'Phone Number', NULL, '2026-06-12 02:21:50', '2026-06-12 05:19:05'),
(5, 'general', 'hospital_email', 'shehreyar882@gmail.com', 'text', 'Email Address', NULL, '2026-06-12 02:21:50', '2026-06-12 05:19:05'),
(6, 'general', 'hospital_website', '', 'text', 'Website URL', NULL, '2026-06-12 02:21:50', '2026-06-12 05:19:05'),
(7, 'general', 'hospital_logo', 'settings/dofiQeHGiSyCujEu00q6BYsUh8ltZOtA2XyZgzJL.jpg', 'image', 'Hospital Logo', NULL, '2026-06-12 02:21:50', '2026-06-12 04:15:08'),
(8, 'general', 'timezone', 'pakistan', 'text', 'Timezone', NULL, '2026-06-12 02:21:50', '2026-06-12 05:19:05'),
(9, 'general', 'date_format', '12/6/2026', 'text', 'Date Format', NULL, '2026-06-12 02:21:50', '2026-06-12 05:19:05'),
(10, 'billing', 'currency', 'PKR', 'text', 'Currency Code', NULL, '2026-06-12 02:21:50', '2026-06-15 05:41:15'),
(11, 'billing', 'currency_symbol', '$', 'text', 'Currency Symbol', NULL, '2026-06-12 02:21:50', '2026-06-15 05:41:15'),
(12, 'billing', 'bill_prefix', 'BILL-', 'text', 'Bill Number Prefix', NULL, '2026-06-12 02:21:50', '2026-06-15 05:41:15'),
(13, 'billing', 'tax_percentage', '0', 'number', 'Tax Percentage (%)', NULL, '2026-06-12 02:21:50', '2026-06-15 05:41:15'),
(14, 'billing', 'bill_footer_note', 'Thank for choosing Leady Reading Hospital', 'text', 'Bill Footer Note', NULL, '2026-06-12 02:21:50', '2026-06-15 05:41:15'),
(15, 'patient', 'mrn_prefix', 'MRN-', 'text', 'MRN Prefix', NULL, '2026-06-12 02:21:50', '2026-06-12 02:21:50'),
(16, 'lab', 'lab_report_footer', 'Results verified by licensed pathologist.', 'text', 'Lab Report Footer', NULL, '2026-06-12 02:21:50', '2026-06-12 02:21:50'),
(17, 'lab', 'lab_name', 'Medicare Laboratory', 'text', 'Laboratory Name', NULL, '2026-06-12 02:21:50', '2026-06-12 02:21:50'),
(18, 'pharmacy', 'low_stock_threshold', '10', 'number', 'Low Stock Alert Threshold', NULL, '2026-06-12 02:21:50', '2026-06-12 02:21:50'),
(19, 'pharmacy', 'expiry_alert_days', '30', 'number', 'Expiry Alert (days before)', NULL, '2026-06-12 02:21:50', '2026-06-12 02:21:50'),
(20, 'hr', 'working_hours_per_day', '8', 'number', 'Working Hours Per Day', NULL, '2026-06-12 02:21:50', '2026-06-12 02:21:50'),
(21, 'hr', 'payroll_cycle', 'monthly', 'text', 'Payroll Cycle', NULL, '2026-06-12 02:21:50', '2026-06-12 02:21:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','receptionist','doctor','nurse','lab_technician','radiologist','pharmacist','hr_manager','accountant') NOT NULL DEFAULT 'receptionist',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `name`, `username`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 2, 'Ahmed Khan', 'ahmad', 'ahmad@gmail.com', NULL, '$2y$12$5epaiayBIvWE2aO59j32RenxKxdBQHf8G2RC6j0dpFFzcL30hy7ju', 'doctor', 1, NULL, '2026-05-25 00:13:21', '2026-05-31 23:21:37'),
(3, NULL, 'Super Admin', 'superadmin', 'admin@gmail.com', NULL, '$2y$12$JJzRkSiVkR9SH7aE23BTWeDEtUEETeEwnk9W9ZoOQYiKrHlst/MwO', 'super_admin', 1, NULL, '2026-05-31 23:17:51', '2026-05-31 23:17:51'),
(4, 13, 'irfan haider', 'irfan', 'irfan@gmail.com', NULL, '$2y$12$arNzKzZ2DI5bEEiF9nKp.O7b9CrPusuY1d3HMCMVYoIUMAaiqvo0S', 'lab_technician', 1, NULL, '2026-05-31 23:38:39', '2026-06-04 19:18:50'),
(5, 15, 'sadia imran', 'sadia', 'sadia@gmail.com', NULL, '$2y$12$t1HNKz1VnAiiSMRM/jlgIuxSBlRGikmON61FZ.Uq1xTaSEW23hMN.', 'receptionist', 1, NULL, '2026-06-01 20:33:33', '2026-06-01 20:33:33'),
(6, 4, 'Bilal Malik', 'bilal', 'bilal@gmail.com', NULL, '$2y$12$dtK3R0Em4jsY6oHSly6S7OKhT70ZcqDQGIGXcSwc2NriPY5SQWVL.', 'doctor', 1, NULL, '2026-06-01 20:45:23', '2026-06-03 08:28:50'),
(7, 1, 'samin Afridi', 'samin', 'samin@gmail.com', NULL, '$2y$12$SQm9ClkZSximbMTKtC3HmO2hxuuLFuZZ4WMsd7UlkW3Z0RM.kwiH2', 'doctor', 1, NULL, '2026-06-03 20:09:39', '2026-06-03 20:09:39'),
(9, 17, 'pervaiz mash', 'pervaiz', 'pervaiz@gmail.com', NULL, '$2y$12$G5tZ2OB4p.WrtTtmvphPZu2sh/5tqyW221akNinI40616ZRrXzg7W', 'radiologist', 1, NULL, '2026-06-04 19:49:53', '2026-06-04 19:51:49'),
(10, 12, 'Saima Raza', 'saima', 'saima@gmail.com', NULL, '$2y$12$kFypPRDcHma7Jv7HUSi38eWj.B1xMZCJxGyPV0luXckVNRFqm4IY2', 'pharmacist', 1, NULL, '2026-06-04 20:43:02', '2026-06-04 20:43:02'),
(11, 9, 'Kamran Akmal', 'kamran', 'kamran@gmail.com', NULL, '$2y$12$9XW7uKFkqA6pCdU0pDz5POopMIXkyUXLjM5Rejo6M4ziJxP1vb2hO', 'super_admin', 1, NULL, '2026-06-09 21:52:13', '2026-06-09 21:52:13'),
(12, 10, 'Asma Javid', 'asma', 'asma@gmail.com', NULL, '$2y$12$t//3FhkNPAK6GGPJbyWKe.PCh.ArvLXO8cLZUBVEkPqGzcY9kwVea', 'nurse', 1, NULL, '2026-06-17 06:03:39', '2026-06-17 06:03:39');

-- --------------------------------------------------------

--
-- Table structure for table `wards`
--

CREATE TABLE `wards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ward_code` varchar(255) NOT NULL,
  `type` enum('General','ICU','CCU','NICU','Surgical','Maternity','Pediatric','Orthopedic','Private','Semi-Private') NOT NULL,
  `total_beds` int(11) NOT NULL DEFAULT 0,
  `floor` varchar(255) DEFAULT NULL,
  `block` varchar(255) DEFAULT NULL,
  `bed_charges` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wards`
--

INSERT INTO `wards` (`id`, `name`, `ward_code`, `type`, `total_beds`, `floor`, `block`, `bed_charges`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Medical A', 'W-001', 'General', 20, 'Ground', 'A', 200.00, NULL, 1, '2026-05-10 20:00:43', '2026-05-10 20:00:43'),
(2, 'Medical ICU Ward B', 'W-002', 'ICU', 20, NULL, 'A', 1500.00, NULL, 1, '2026-05-17 23:28:01', '2026-05-17 23:28:01'),
(4, 'Ortheropedic ward', 'W-003', 'Orthopedic', 20, '4th', 'A', 4000.00, 'the ward have all functions available', 1, '2026-06-17 05:17:09', '2026-06-17 05:17:09');

-- --------------------------------------------------------

--
-- Table structure for table `ward_nurse_assignments`
--

CREATE TABLE `ward_nurse_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ward_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `shift` enum('Morning','Evening','Night') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `assigned_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ward_nurse_assignments`
--

INSERT INTO `ward_nurse_assignments` (`id`, `ward_id`, `user_id`, `shift`, `start_date`, `end_date`, `is_active`, `assigned_by`, `created_at`, `updated_at`) VALUES
(1, 1, 12, 'Morning', '2026-06-17', '2026-06-17', 0, 11, '2026-06-17 06:06:11', '2026-06-17 06:34:10'),
(2, 1, 12, 'Morning', '2026-06-17', '2026-06-19', 0, 11, '2026-06-17 06:34:51', '2026-06-19 01:49:57'),
(3, 2, 12, 'Evening', '2026-06-19', '2026-06-19', 0, 11, '2026-06-19 01:47:14', '2026-06-19 01:49:53'),
(4, 2, 12, 'Morning', '2026-06-19', NULL, 1, 11, '2026-06-19 01:50:10', '2026-06-19 01:50:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_index` (`user_id`),
  ADD KEY `activity_logs_action_index` (`action`),
  ADD KEY `activity_logs_module_index` (`module`),
  ADD KEY `activity_logs_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `activity_logs_severity_index` (`severity`),
  ADD KEY `activity_logs_created_at_index` (`created_at`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_doctor_id_foreign` (`doctor_id`),
  ADD KEY `appointments_appointment_date_doctor_id_index` (`appointment_date`,`doctor_id`),
  ADD KEY `appointments_patient_id_appointment_date_index` (`patient_id`,`appointment_date`),
  ADD KEY `appointments_status_index` (`status`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attendances_employee_id_date_unique` (`employee_id`,`date`),
  ADD KEY `attendances_regularized_by_foreign` (`regularized_by`),
  ADD KEY `attendances_employee_id_date_index` (`employee_id`,`date`),
  ADD KEY `attendances_date_status_index` (`date`,`status`);

--
-- Indexes for table `beds`
--
ALTER TABLE `beds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `beds_bed_number_ward_id_unique` (`bed_number`,`ward_id`),
  ADD KEY `beds_ward_id_foreign` (`ward_id`),
  ADD KEY `beds_patient_id_foreign` (`patient_id`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bills_bill_number_unique` (`bill_number`),
  ADD KEY `bills_created_by_foreign` (`created_by`),
  ADD KEY `bills_discount_by_foreign` (`discount_by`),
  ADD KEY `bills_patient_id_payment_status_index` (`patient_id`,`payment_status`),
  ADD KEY `bills_bill_date_index` (`bill_date`),
  ADD KEY `bills_status_index` (`status`),
  ADD KEY `bills_payment_status_index` (`payment_status`);

--
-- Indexes for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_items_bill_id_index` (`bill_id`),
  ADD KEY `bill_items_reference_type_reference_id_index` (`reference_type`,`reference_id`);

--
-- Indexes for table `bill_payments`
--
ALTER TABLE `bill_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bill_payments_payment_number_unique` (`payment_number`),
  ADD KEY `bill_payments_received_by_foreign` (`received_by`),
  ADD KEY `bill_payments_bill_id_index` (`bill_id`),
  ADD KEY `bill_payments_payment_date_index` (`payment_date`);

--
-- Indexes for table `bill_service_charges`
--
ALTER TABLE `bill_service_charges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bill_service_charges_code_unique` (`code`),
  ADD KEY `bill_service_charges_category_index` (`category`),
  ADD KEY `bill_service_charges_is_active_index` (`is_active`);

--
-- Indexes for table `blood_crossmatches`
--
ALTER TABLE `blood_crossmatches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blood_crossmatches_crossmatch_id_unique` (`crossmatch_id`),
  ADD KEY `blood_crossmatches_blood_donation_id_foreign` (`blood_donation_id`),
  ADD KEY `blood_crossmatches_performed_by_foreign` (`performed_by`),
  ADD KEY `blood_crossmatches_blood_request_id_index` (`blood_request_id`),
  ADD KEY `blood_crossmatches_result_index` (`result`);

--
-- Indexes for table `blood_donations`
--
ALTER TABLE `blood_donations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blood_donations_donation_id_unique` (`donation_id`),
  ADD UNIQUE KEY `blood_donations_bag_number_unique` (`bag_number`),
  ADD KEY `blood_donations_donor_id_foreign` (`donor_id`),
  ADD KEY `blood_donations_collected_by_foreign` (`collected_by`),
  ADD KEY `blood_donations_blood_group_index` (`blood_group`),
  ADD KEY `blood_donations_status_index` (`status`),
  ADD KEY `blood_donations_expiry_date_index` (`expiry_date`),
  ADD KEY `blood_donations_component_index` (`component`);

--
-- Indexes for table `blood_donors`
--
ALTER TABLE `blood_donors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blood_donors_donor_id_unique` (`donor_id`),
  ADD UNIQUE KEY `blood_donors_cnic_unique` (`cnic`),
  ADD KEY `blood_donors_patient_id_foreign` (`patient_id`),
  ADD KEY `blood_donors_blood_group_index` (`blood_group`),
  ADD KEY `blood_donors_is_eligible_index` (`is_eligible`),
  ADD KEY `blood_donors_donor_type_index` (`donor_type`);

--
-- Indexes for table `blood_inventories`
--
ALTER TABLE `blood_inventories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blood_inventories_blood_group_component_unique` (`blood_group`,`component`),
  ADD KEY `blood_inventories_blood_group_index` (`blood_group`);

--
-- Indexes for table `blood_issues`
--
ALTER TABLE `blood_issues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blood_issues_issue_id_unique` (`issue_id`),
  ADD KEY `blood_issues_blood_request_id_foreign` (`blood_request_id`),
  ADD KEY `blood_issues_blood_donation_id_foreign` (`blood_donation_id`),
  ADD KEY `blood_issues_issued_by_foreign` (`issued_by`),
  ADD KEY `blood_issues_patient_id_issued_at_index` (`patient_id`,`issued_at`),
  ADD KEY `blood_issues_blood_group_index` (`blood_group`);

--
-- Indexes for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blood_requests_request_id_unique` (`request_id`),
  ADD KEY `blood_requests_doctor_id_foreign` (`doctor_id`),
  ADD KEY `blood_requests_processed_by_foreign` (`processed_by`),
  ADD KEY `blood_requests_patient_id_status_index` (`patient_id`,`status`),
  ADD KEY `blood_requests_blood_group_index` (`blood_group`),
  ADD KEY `blood_requests_urgency_index` (`urgency`),
  ADD KEY `blood_requests_status_index` (`status`);

--
-- Indexes for table `body_release_records`
--
ALTER TABLE `body_release_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `body_release_records_release_id_unique` (`release_id`),
  ADD KEY `body_release_records_released_by_foreign` (`released_by`),
  ADD KEY `body_release_records_released_at_index` (`released_at`),
  ADD KEY `body_release_records_mortuary_record_id_index` (`mortuary_record_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `death_certificates`
--
ALTER TABLE `death_certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `death_certificates_certificate_number_unique` (`certificate_number`),
  ADD KEY `death_certificates_signed_by_doctor_foreign` (`signed_by_doctor`),
  ADD KEY `death_certificates_verified_by_foreign` (`verified_by`),
  ADD KEY `death_certificates_issued_by_foreign` (`issued_by`),
  ADD KEY `death_certificates_bill_id_foreign` (`bill_id`),
  ADD KEY `death_certificates_certificate_number_index` (`certificate_number`),
  ADD KEY `death_certificates_issued_at_index` (`issued_at`),
  ADD KEY `death_certificates_mortuary_record_id_copy_number_index` (`mortuary_record_id`,`copy_number`);

--
-- Indexes for table `disciplinary_actions`
--
ALTER TABLE `disciplinary_actions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `disciplinary_actions_action_number_unique` (`action_number`),
  ADD KEY `disciplinary_actions_issued_by_foreign` (`issued_by`),
  ADD KEY `disciplinary_actions_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `disciplinary_actions_employee_id_status_index` (`employee_id`,`status`),
  ADD KEY `disciplinary_actions_incident_date_index` (`incident_date`),
  ADD KEY `disciplinary_actions_action_type_index` (`action_type`);

--
-- Indexes for table `dispensings`
--
ALTER TABLE `dispensings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dispensings_dispense_number_unique` (`dispense_number`),
  ADD KEY `dispensings_prescription_id_foreign` (`prescription_id`),
  ADD KEY `dispensings_patient_id_foreign` (`patient_id`),
  ADD KEY `dispensings_dispensed_at_index` (`dispensed_at`),
  ADD KEY `dispensings_payment_status_index` (`payment_status`);

--
-- Indexes for table `dispensing_items`
--
ALTER TABLE `dispensing_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dispensing_items_dispensing_id_foreign` (`dispensing_id`),
  ADD KEY `dispensing_items_prescription_item_id_foreign` (`prescription_item_id`),
  ADD KEY `dispensing_items_medicine_id_foreign` (`medicine_id`),
  ADD KEY `dispensing_items_medicine_batch_id_foreign` (`medicine_batch_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doctors_employee_id_unique` (`employee_id`),
  ADD UNIQUE KEY `doctors_doctor_id_unique` (`doctor_id`),
  ADD KEY `doctors_availability_index` (`availability`),
  ADD KEY `doctors_is_active_index` (`is_active`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_employee_id_unique` (`employee_id`),
  ADD UNIQUE KEY `employees_badge_number_unique` (`badge_number`),
  ADD UNIQUE KEY `employees_cnic_unique` (`cnic`),
  ADD UNIQUE KEY `employees_office_email_unique` (`office_email`),
  ADD KEY `employees_department_index` (`department`),
  ADD KEY `employees_employment_status_index` (`employment_status`),
  ADD KEY `employees_employment_type_index` (`employment_type`),
  ADD KEY `employees_joining_date_index` (`joining_date`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `holidays_year_date_index` (`year`,`date`),
  ADD KEY `holidays_date_index` (`date`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab_orders`
--
ALTER TABLE `lab_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lab_orders_order_number_unique` (`order_number`),
  ADD KEY `lab_orders_doctor_id_foreign` (`doctor_id`),
  ADD KEY `lab_orders_appointment_id_foreign` (`appointment_id`),
  ADD KEY `lab_orders_patient_id_status_index` (`patient_id`,`status`),
  ADD KEY `lab_orders_order_date_index` (`order_date`),
  ADD KEY `lab_orders_priority_index` (`priority`);

--
-- Indexes for table `lab_order_items`
--
ALTER TABLE `lab_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lab_order_items_lab_test_id_foreign` (`lab_test_id`),
  ADD KEY `lab_order_items_lab_sample_id_foreign` (`lab_sample_id`),
  ADD KEY `lab_order_items_lab_order_id_status_index` (`lab_order_id`,`status`);

--
-- Indexes for table `lab_results`
--
ALTER TABLE `lab_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lab_results_lab_order_item_id_foreign` (`lab_order_item_id`),
  ADD KEY `lab_results_is_abnormal_index` (`is_abnormal`),
  ADD KEY `lab_results_is_verified_index` (`is_verified`);

--
-- Indexes for table `lab_samples`
--
ALTER TABLE `lab_samples`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lab_samples_sample_number_unique` (`sample_number`),
  ADD KEY `lab_samples_sample_type_id_foreign` (`sample_type_id`),
  ADD KEY `lab_samples_lab_order_id_status_index` (`lab_order_id`,`status`);

--
-- Indexes for table `lab_sample_types`
--
ALTER TABLE `lab_sample_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lab_sample_types_code_unique` (`code`);

--
-- Indexes for table `lab_tests`
--
ALTER TABLE `lab_tests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lab_tests_test_code_unique` (`test_code`),
  ADD KEY `lab_tests_category_id_foreign` (`category_id`),
  ADD KEY `lab_tests_sample_type_id_foreign` (`sample_type_id`),
  ADD KEY `lab_tests_test_code_index` (`test_code`),
  ADD KEY `lab_tests_is_active_index` (`is_active`);

--
-- Indexes for table `lab_test_categories`
--
ALTER TABLE `lab_test_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lab_test_categories_code_unique` (`code`);

--
-- Indexes for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_balances_employee_id_leave_type_id_year_unique` (`employee_id`,`leave_type_id`,`year`),
  ADD KEY `leave_balances_leave_type_id_foreign` (`leave_type_id`),
  ADD KEY `leave_balances_employee_id_year_index` (`employee_id`,`year`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_requests_leave_number_unique` (`leave_number`),
  ADD KEY `leave_requests_leave_type_id_foreign` (`leave_type_id`),
  ADD KEY `leave_requests_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `leave_requests_cancelled_by_foreign` (`cancelled_by`),
  ADD KEY `leave_requests_employee_id_status_index` (`employee_id`,`status`),
  ADD KEY `leave_requests_from_date_to_date_index` (`from_date`,`to_date`),
  ADD KEY `leave_requests_status_index` (`status`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_types_code_unique` (`code`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `medicines_medicine_code_unique` (`medicine_code`),
  ADD KEY `medicines_name_index` (`name`),
  ADD KEY `medicines_category_index` (`category`),
  ADD KEY `medicines_is_active_index` (`is_active`);

--
-- Indexes for table `medicine_batches`
--
ALTER TABLE `medicine_batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `medicine_batches_medicine_id_batch_number_unique` (`medicine_id`,`batch_number`),
  ADD KEY `medicine_batches_expiry_date_index` (`expiry_date`),
  ADD KEY `medicine_batches_status_index` (`status`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mortuary_records`
--
ALTER TABLE `mortuary_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mortuary_records_mortuary_id_unique` (`mortuary_id`),
  ADD KEY `mortuary_records_declared_by_foreign` (`declared_by`),
  ADD KEY `mortuary_records_postmortem_by_foreign` (`postmortem_by`),
  ADD KEY `mortuary_records_admitted_by_foreign` (`admitted_by`),
  ADD KEY `mortuary_records_status_index` (`status`),
  ADD KEY `mortuary_records_death_datetime_index` (`death_datetime`),
  ADD KEY `mortuary_records_is_medico_legal_index` (`is_medico_legal`),
  ADD KEY `mortuary_records_patient_id_status_index` (`patient_id`,`status`);

--
-- Indexes for table `ot_rooms`
--
ALTER TABLE `ot_rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ot_rooms_room_code_unique` (`room_code`),
  ADD KEY `ot_rooms_status_index` (`status`),
  ADD KEY `ot_rooms_room_type_index` (`room_type`);

--
-- Indexes for table `ot_schedules`
--
ALTER TABLE `ot_schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ot_schedules_surgery_id_unique` (`surgery_id`),
  ADD KEY `ot_schedules_ot_room_id_foreign` (`ot_room_id`),
  ADD KEY `ot_schedules_surgeon_id_foreign` (`surgeon_id`),
  ADD KEY `ot_schedules_anesthesiologist_id_foreign` (`anesthesiologist_id`),
  ADD KEY `ot_schedules_booked_by_foreign` (`booked_by`),
  ADD KEY `ot_schedules_scheduled_date_ot_room_id_index` (`scheduled_date`,`ot_room_id`),
  ADD KEY `ot_schedules_patient_id_scheduled_date_index` (`patient_id`,`scheduled_date`),
  ADD KEY `ot_schedules_status_index` (`status`),
  ADD KEY `ot_schedules_priority_index` (`priority`);

--
-- Indexes for table `ot_teams`
--
ALTER TABLE `ot_teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ot_teams_doctor_id_foreign` (`doctor_id`),
  ADD KEY `ot_teams_employee_id_foreign` (`employee_id`),
  ADD KEY `ot_teams_ot_schedule_id_index` (`ot_schedule_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patients_mrn_unique` (`mrn`),
  ADD UNIQUE KEY `patients_cnic_unique` (`cnic`),
  ADD KEY `patients_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `patient_discharges`
--
ALTER TABLE `patient_discharges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_discharges_discharge_number_unique` (`discharge_number`),
  ADD KEY `patient_discharges_bed_id_foreign` (`bed_id`),
  ADD KEY `patient_discharges_processed_by_foreign` (`processed_by`),
  ADD KEY `patient_discharges_patient_id_index` (`patient_id`),
  ADD KEY `patient_discharges_doctor_id_index` (`doctor_id`),
  ADD KEY `patient_discharges_discharge_date_index` (`discharge_date`);

--
-- Indexes for table `patient_doctor_orders`
--
ALTER TABLE `patient_doctor_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_doctor_orders_order_number_unique` (`order_number`),
  ADD KEY `patient_doctor_orders_bed_id_foreign` (`bed_id`),
  ADD KEY `patient_doctor_orders_acknowledged_by_foreign` (`acknowledged_by`),
  ADD KEY `patient_doctor_orders_patient_id_status_index` (`patient_id`,`status`),
  ADD KEY `patient_doctor_orders_doctor_id_ordered_at_index` (`doctor_id`,`ordered_at`);

--
-- Indexes for table `patient_nursing_notes`
--
ALTER TABLE `patient_nursing_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_nursing_notes_bed_id_foreign` (`bed_id`),
  ADD KEY `patient_nursing_notes_nurse_id_foreign` (`nurse_id`),
  ADD KEY `patient_nursing_notes_patient_id_noted_at_index` (`patient_id`,`noted_at`);

--
-- Indexes for table `patient_visit_notes`
--
ALTER TABLE `patient_visit_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_visit_notes_bed_id_foreign` (`bed_id`),
  ADD KEY `patient_visit_notes_patient_id_visited_at_index` (`patient_id`,`visited_at`),
  ADD KEY `patient_visit_notes_doctor_id_index` (`doctor_id`);

--
-- Indexes for table `patient_vitals`
--
ALTER TABLE `patient_vitals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_vitals_recorded_by_foreign` (`recorded_by`),
  ADD KEY `patient_vitals_patient_id_recorded_at_index` (`patient_id`,`recorded_at`),
  ADD KEY `patient_vitals_bed_id_index` (`bed_id`);

--
-- Indexes for table `payroll_runs`
--
ALTER TABLE `payroll_runs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payroll_runs_year_month_unique` (`year`,`month`),
  ADD UNIQUE KEY `payroll_runs_run_number_unique` (`run_number`),
  ADD KEY `payroll_runs_created_by_foreign` (`created_by`),
  ADD KEY `payroll_runs_approved_by_foreign` (`approved_by`),
  ADD KEY `payroll_runs_year_month_index` (`year`,`month`),
  ADD KEY `payroll_runs_status_index` (`status`);

--
-- Indexes for table `payslips`
--
ALTER TABLE `payslips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payslips_payroll_run_id_employee_id_unique` (`payroll_run_id`,`employee_id`),
  ADD UNIQUE KEY `payslips_payslip_number_unique` (`payslip_number`),
  ADD KEY `payslips_salary_structure_id_foreign` (`salary_structure_id`),
  ADD KEY `payslips_employee_id_status_index` (`employee_id`,`status`),
  ADD KEY `payslips_employee_id_is_paid_index` (`employee_id`,`is_paid`),
  ADD KEY `payslips_payslip_number_index` (`payslip_number`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prescriptions_prescription_number_unique` (`prescription_number`),
  ADD KEY `prescriptions_doctor_id_foreign` (`doctor_id`),
  ADD KEY `prescriptions_appointment_id_foreign` (`appointment_id`),
  ADD KEY `prescriptions_patient_id_status_index` (`patient_id`,`status`),
  ADD KEY `prescriptions_prescribed_date_index` (`prescribed_date`);

--
-- Indexes for table `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescription_items_prescription_id_foreign` (`prescription_id`),
  ADD KEY `prescription_items_medicine_id_foreign` (`medicine_id`);

--
-- Indexes for table `radiology_body_parts`
--
ALTER TABLE `radiology_body_parts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `radiology_body_parts_code_unique` (`code`);

--
-- Indexes for table `radiology_consents`
--
ALTER TABLE `radiology_consents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `radiology_consents_radiology_order_id_foreign` (`radiology_order_id`);

--
-- Indexes for table `radiology_exams`
--
ALTER TABLE `radiology_exams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `radiology_exams_exam_code_unique` (`exam_code`),
  ADD KEY `radiology_exams_body_part_id_foreign` (`body_part_id`),
  ADD KEY `radiology_exams_exam_code_index` (`exam_code`),
  ADD KEY `radiology_exams_modality_id_is_active_index` (`modality_id`,`is_active`);

--
-- Indexes for table `radiology_images`
--
ALTER TABLE `radiology_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `radiology_images_radiology_order_item_id_index` (`radiology_order_item_id`);

--
-- Indexes for table `radiology_modalities`
--
ALTER TABLE `radiology_modalities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `radiology_modalities_code_unique` (`code`);

--
-- Indexes for table `radiology_orders`
--
ALTER TABLE `radiology_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `radiology_orders_order_number_unique` (`order_number`),
  ADD KEY `radiology_orders_doctor_id_foreign` (`doctor_id`),
  ADD KEY `radiology_orders_appointment_id_foreign` (`appointment_id`),
  ADD KEY `radiology_orders_patient_id_status_index` (`patient_id`,`status`),
  ADD KEY `radiology_orders_order_date_index` (`order_date`),
  ADD KEY `radiology_orders_priority_index` (`priority`),
  ADD KEY `radiology_orders_scheduled_at_index` (`scheduled_at`);

--
-- Indexes for table `radiology_order_items`
--
ALTER TABLE `radiology_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `radiology_order_items_radiology_exam_id_foreign` (`radiology_exam_id`),
  ADD KEY `radiology_order_items_radiology_order_id_status_index` (`radiology_order_id`,`status`);

--
-- Indexes for table `radiology_reports`
--
ALTER TABLE `radiology_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `radiology_reports_radiology_order_item_id_foreign` (`radiology_order_item_id`),
  ADD KEY `radiology_reports_reported_by_foreign` (`reported_by`),
  ADD KEY `radiology_reports_verified_by_foreign` (`verified_by`),
  ADD KEY `radiology_reports_amended_by_foreign` (`amended_by`),
  ADD KEY `radiology_reports_is_critical_index` (`is_critical`),
  ADD KEY `radiology_reports_is_verified_index` (`is_verified`),
  ADD KEY `radiology_reports_status_index` (`status`);

--
-- Indexes for table `salary_structures`
--
ALTER TABLE `salary_structures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_structures_created_by_foreign` (`created_by`),
  ADD KEY `salary_structures_employee_id_is_current_index` (`employee_id`,`is_current`),
  ADD KEY `salary_structures_employee_id_effective_from_index` (`employee_id`,`effective_from`),
  ADD KEY `salary_structures_effective_from_index` (`effective_from`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `wards`
--
ALTER TABLE `wards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wards_ward_code_unique` (`ward_code`);

--
-- Indexes for table `ward_nurse_assignments`
--
ALTER TABLE `ward_nurse_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ward_nurse_assignments_assigned_by_foreign` (`assigned_by`),
  ADD KEY `ward_nurse_assignments_user_id_is_active_index` (`user_id`,`is_active`),
  ADD KEY `ward_nurse_assignments_ward_id_is_active_index` (`ward_id`,`is_active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=450;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `beds`
--
ALTER TABLE `beds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=414;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `bill_items`
--
ALTER TABLE `bill_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `bill_payments`
--
ALTER TABLE `bill_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `bill_service_charges`
--
ALTER TABLE `bill_service_charges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `blood_crossmatches`
--
ALTER TABLE `blood_crossmatches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `blood_donations`
--
ALTER TABLE `blood_donations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `blood_donors`
--
ALTER TABLE `blood_donors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blood_inventories`
--
ALTER TABLE `blood_inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `blood_issues`
--
ALTER TABLE `blood_issues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `blood_requests`
--
ALTER TABLE `blood_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `body_release_records`
--
ALTER TABLE `body_release_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `death_certificates`
--
ALTER TABLE `death_certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `disciplinary_actions`
--
ALTER TABLE `disciplinary_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dispensings`
--
ALTER TABLE `dispensings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `dispensing_items`
--
ALTER TABLE `dispensing_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_orders`
--
ALTER TABLE `lab_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `lab_order_items`
--
ALTER TABLE `lab_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `lab_results`
--
ALTER TABLE `lab_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `lab_samples`
--
ALTER TABLE `lab_samples`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lab_sample_types`
--
ALTER TABLE `lab_sample_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lab_tests`
--
ALTER TABLE `lab_tests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lab_test_categories`
--
ALTER TABLE `lab_test_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `leave_balances`
--
ALTER TABLE `leave_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `medicine_batches`
--
ALTER TABLE `medicine_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `mortuary_records`
--
ALTER TABLE `mortuary_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ot_rooms`
--
ALTER TABLE `ot_rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ot_schedules`
--
ALTER TABLE `ot_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ot_teams`
--
ALTER TABLE `ot_teams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `patient_discharges`
--
ALTER TABLE `patient_discharges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `patient_doctor_orders`
--
ALTER TABLE `patient_doctor_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patient_nursing_notes`
--
ALTER TABLE `patient_nursing_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patient_visit_notes`
--
ALTER TABLE `patient_visit_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `patient_vitals`
--
ALTER TABLE `patient_vitals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payroll_runs`
--
ALTER TABLE `payroll_runs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payslips`
--
ALTER TABLE `payslips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `prescription_items`
--
ALTER TABLE `prescription_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `radiology_body_parts`
--
ALTER TABLE `radiology_body_parts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `radiology_consents`
--
ALTER TABLE `radiology_consents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `radiology_exams`
--
ALTER TABLE `radiology_exams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `radiology_images`
--
ALTER TABLE `radiology_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `radiology_modalities`
--
ALTER TABLE `radiology_modalities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `radiology_orders`
--
ALTER TABLE `radiology_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `radiology_order_items`
--
ALTER TABLE `radiology_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `radiology_reports`
--
ALTER TABLE `radiology_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `salary_structures`
--
ALTER TABLE `salary_structures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `wards`
--
ALTER TABLE `wards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ward_nurse_assignments`
--
ALTER TABLE `ward_nurse_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_regularized_by_foreign` FOREIGN KEY (`regularized_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `beds`
--
ALTER TABLE `beds`
  ADD CONSTRAINT `beds_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `beds_ward_id_foreign` FOREIGN KEY (`ward_id`) REFERENCES `wards` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bills_discount_by_foreign` FOREIGN KEY (`discount_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bills_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD CONSTRAINT `bill_items_bill_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bill_payments`
--
ALTER TABLE `bill_payments`
  ADD CONSTRAINT `bill_payments_bill_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bill_payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `blood_crossmatches`
--
ALTER TABLE `blood_crossmatches`
  ADD CONSTRAINT `blood_crossmatches_blood_donation_id_foreign` FOREIGN KEY (`blood_donation_id`) REFERENCES `blood_donations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blood_crossmatches_blood_request_id_foreign` FOREIGN KEY (`blood_request_id`) REFERENCES `blood_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blood_crossmatches_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `blood_donations`
--
ALTER TABLE `blood_donations`
  ADD CONSTRAINT `blood_donations_collected_by_foreign` FOREIGN KEY (`collected_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blood_donations_donor_id_foreign` FOREIGN KEY (`donor_id`) REFERENCES `blood_donors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blood_donors`
--
ALTER TABLE `blood_donors`
  ADD CONSTRAINT `blood_donors_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `blood_issues`
--
ALTER TABLE `blood_issues`
  ADD CONSTRAINT `blood_issues_blood_donation_id_foreign` FOREIGN KEY (`blood_donation_id`) REFERENCES `blood_donations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blood_issues_blood_request_id_foreign` FOREIGN KEY (`blood_request_id`) REFERENCES `blood_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blood_issues_issued_by_foreign` FOREIGN KEY (`issued_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blood_issues_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD CONSTRAINT `blood_requests_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blood_requests_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blood_requests_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `body_release_records`
--
ALTER TABLE `body_release_records`
  ADD CONSTRAINT `body_release_records_mortuary_record_id_foreign` FOREIGN KEY (`mortuary_record_id`) REFERENCES `mortuary_records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `body_release_records_released_by_foreign` FOREIGN KEY (`released_by`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `death_certificates`
--
ALTER TABLE `death_certificates`
  ADD CONSTRAINT `death_certificates_bill_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `death_certificates_issued_by_foreign` FOREIGN KEY (`issued_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `death_certificates_mortuary_record_id_foreign` FOREIGN KEY (`mortuary_record_id`) REFERENCES `mortuary_records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `death_certificates_signed_by_doctor_foreign` FOREIGN KEY (`signed_by_doctor`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `death_certificates_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `disciplinary_actions`
--
ALTER TABLE `disciplinary_actions`
  ADD CONSTRAINT `disciplinary_actions_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `disciplinary_actions_issued_by_foreign` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `disciplinary_actions_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dispensings`
--
ALTER TABLE `dispensings`
  ADD CONSTRAINT `dispensings_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dispensings_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dispensing_items`
--
ALTER TABLE `dispensing_items`
  ADD CONSTRAINT `dispensing_items_dispensing_id_foreign` FOREIGN KEY (`dispensing_id`) REFERENCES `dispensings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dispensing_items_medicine_batch_id_foreign` FOREIGN KEY (`medicine_batch_id`) REFERENCES `medicine_batches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dispensing_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dispensing_items_prescription_item_id_foreign` FOREIGN KEY (`prescription_item_id`) REFERENCES `prescription_items` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lab_orders`
--
ALTER TABLE `lab_orders`
  ADD CONSTRAINT `lab_orders_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lab_orders_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lab_orders_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lab_order_items`
--
ALTER TABLE `lab_order_items`
  ADD CONSTRAINT `lab_order_items_lab_order_id_foreign` FOREIGN KEY (`lab_order_id`) REFERENCES `lab_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lab_order_items_lab_sample_id_foreign` FOREIGN KEY (`lab_sample_id`) REFERENCES `lab_samples` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lab_order_items_lab_test_id_foreign` FOREIGN KEY (`lab_test_id`) REFERENCES `lab_tests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lab_results`
--
ALTER TABLE `lab_results`
  ADD CONSTRAINT `lab_results_lab_order_item_id_foreign` FOREIGN KEY (`lab_order_item_id`) REFERENCES `lab_order_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lab_samples`
--
ALTER TABLE `lab_samples`
  ADD CONSTRAINT `lab_samples_lab_order_id_foreign` FOREIGN KEY (`lab_order_id`) REFERENCES `lab_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lab_samples_sample_type_id_foreign` FOREIGN KEY (`sample_type_id`) REFERENCES `lab_sample_types` (`id`);

--
-- Constraints for table `lab_tests`
--
ALTER TABLE `lab_tests`
  ADD CONSTRAINT `lab_tests_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `lab_test_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lab_tests_sample_type_id_foreign` FOREIGN KEY (`sample_type_id`) REFERENCES `lab_sample_types` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD CONSTRAINT `leave_balances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_balances_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leave_requests_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_requests_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`),
  ADD CONSTRAINT `leave_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `medicine_batches`
--
ALTER TABLE `medicine_batches`
  ADD CONSTRAINT `medicine_batches_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mortuary_records`
--
ALTER TABLE `mortuary_records`
  ADD CONSTRAINT `mortuary_records_admitted_by_foreign` FOREIGN KEY (`admitted_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mortuary_records_declared_by_foreign` FOREIGN KEY (`declared_by`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mortuary_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mortuary_records_postmortem_by_foreign` FOREIGN KEY (`postmortem_by`) REFERENCES `doctors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ot_schedules`
--
ALTER TABLE `ot_schedules`
  ADD CONSTRAINT `ot_schedules_anesthesiologist_id_foreign` FOREIGN KEY (`anesthesiologist_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ot_schedules_booked_by_foreign` FOREIGN KEY (`booked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ot_schedules_ot_room_id_foreign` FOREIGN KEY (`ot_room_id`) REFERENCES `ot_rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ot_schedules_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ot_schedules_surgeon_id_foreign` FOREIGN KEY (`surgeon_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ot_teams`
--
ALTER TABLE `ot_teams`
  ADD CONSTRAINT `ot_teams_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ot_teams_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ot_teams_ot_schedule_id_foreign` FOREIGN KEY (`ot_schedule_id`) REFERENCES `ot_schedules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `patient_discharges`
--
ALTER TABLE `patient_discharges`
  ADD CONSTRAINT `patient_discharges_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_discharges_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_discharges_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_discharges_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_doctor_orders`
--
ALTER TABLE `patient_doctor_orders`
  ADD CONSTRAINT `patient_doctor_orders_acknowledged_by_foreign` FOREIGN KEY (`acknowledged_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `patient_doctor_orders_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `patient_doctor_orders_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_doctor_orders_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_nursing_notes`
--
ALTER TABLE `patient_nursing_notes`
  ADD CONSTRAINT `patient_nursing_notes_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `patient_nursing_notes_nurse_id_foreign` FOREIGN KEY (`nurse_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_nursing_notes_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_visit_notes`
--
ALTER TABLE `patient_visit_notes`
  ADD CONSTRAINT `patient_visit_notes_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `patient_visit_notes_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_visit_notes_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_vitals`
--
ALTER TABLE `patient_vitals`
  ADD CONSTRAINT `patient_vitals_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `patient_vitals_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_vitals_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_runs`
--
ALTER TABLE `payroll_runs`
  ADD CONSTRAINT `payroll_runs_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payroll_runs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payslips`
--
ALTER TABLE `payslips`
  ADD CONSTRAINT `payslips_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payslips_payroll_run_id_foreign` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payslips_salary_structure_id_foreign` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `prescriptions_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `prescriptions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD CONSTRAINT `prescription_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_items_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `radiology_consents`
--
ALTER TABLE `radiology_consents`
  ADD CONSTRAINT `radiology_consents_radiology_order_id_foreign` FOREIGN KEY (`radiology_order_id`) REFERENCES `radiology_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `radiology_exams`
--
ALTER TABLE `radiology_exams`
  ADD CONSTRAINT `radiology_exams_body_part_id_foreign` FOREIGN KEY (`body_part_id`) REFERENCES `radiology_body_parts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `radiology_exams_modality_id_foreign` FOREIGN KEY (`modality_id`) REFERENCES `radiology_modalities` (`id`);

--
-- Constraints for table `radiology_images`
--
ALTER TABLE `radiology_images`
  ADD CONSTRAINT `radiology_images_radiology_order_item_id_foreign` FOREIGN KEY (`radiology_order_item_id`) REFERENCES `radiology_order_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `radiology_orders`
--
ALTER TABLE `radiology_orders`
  ADD CONSTRAINT `radiology_orders_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `radiology_orders_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `radiology_orders_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `radiology_order_items`
--
ALTER TABLE `radiology_order_items`
  ADD CONSTRAINT `radiology_order_items_radiology_exam_id_foreign` FOREIGN KEY (`radiology_exam_id`) REFERENCES `radiology_exams` (`id`),
  ADD CONSTRAINT `radiology_order_items_radiology_order_id_foreign` FOREIGN KEY (`radiology_order_id`) REFERENCES `radiology_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `radiology_reports`
--
ALTER TABLE `radiology_reports`
  ADD CONSTRAINT `radiology_reports_amended_by_foreign` FOREIGN KEY (`amended_by`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `radiology_reports_radiology_order_item_id_foreign` FOREIGN KEY (`radiology_order_item_id`) REFERENCES `radiology_order_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `radiology_reports_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `radiology_reports_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `doctors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `salary_structures`
--
ALTER TABLE `salary_structures`
  ADD CONSTRAINT `salary_structures_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `salary_structures_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ward_nurse_assignments`
--
ALTER TABLE `ward_nurse_assignments`
  ADD CONSTRAINT `ward_nurse_assignments_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ward_nurse_assignments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ward_nurse_assignments_ward_id_foreign` FOREIGN KEY (`ward_id`) REFERENCES `wards` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
