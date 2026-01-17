-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jan 17, 2026 at 03:29 AM
-- Server version: 8.0.44
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `grmxpertdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Human Resources', 'Employee relations and recruitment', '2026-01-15 06:36:28', '2026-01-15 06:36:28'),
(2, 'Accounting', 'Finance and payroll', '2026-01-15 06:36:28', '2026-01-15 06:36:28'),
(3, 'IT', 'Information Technology', '2026-01-15 06:36:28', '2026-01-15 06:36:28'),
(4, 'Operations', 'Daily operations', '2026-01-15 06:36:28', '2026-01-15 06:36:28'),
(5, 'Administration', 'General administration', '2026-01-15 06:36:28', '2026-01-15 06:36:28');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int NOT NULL,
  `employee_no` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `hire_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_uploads`
--

CREATE TABLE `employee_uploads` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `file_size` bigint DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` enum('submitted','reviewed','approved','rejected') NOT NULL DEFAULT 'submitted',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee_uploads`
--

INSERT INTO `employee_uploads` (`id`, `user_id`, `original_name`, `stored_name`, `file_path`, `mime_type`, `file_size`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'default.png', 'u3_20260115_072527_3b830443980d5fbe.png', 'uploads/employee_files/u3_20260115_072527_3b830443980d5fbe.png', 'image/png', 2538, 'sample', 'submitted', '2026-01-15 07:25:27', '2026-01-15 07:25:27'),
(2, 3, 'default.png', 'u3_20260115_072559_5668a9f19ed2ecfa.png', 'uploads/employee_files/u3_20260115_072559_5668a9f19ed2ecfa.png', 'image/png', 2538, 'sample2', 'submitted', '2026-01-15 07:25:59', '2026-01-15 07:25:59'),
(3, 3, 'IMG_7065.PNG', 'u3_20260115_073119_2ef08e30b514f22e.png', 'uploads/employee_files/u3_20260115_073119_2ef08e30b514f22e.png', 'image/png', 265179, 'UI', 'submitted', '2026-01-15 07:31:19', '2026-01-15 07:31:19'),
(4, 3, 'Screenshot 2026-01-15 at 4.24.00 PM.png', 'u3_20260115_110709_b312433f2f4c37bc.png', 'uploads/employee_files/u3_20260115_110709_b312433f2f4c37bc.png', 'image/png', 220183, 'TOR', 'submitted', '2026-01-15 11:07:09', '2026-01-15 11:07:09');

-- --------------------------------------------------------

--
-- Table structure for table `jobtitles`
--

CREATE TABLE `jobtitles` (
  `id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jobtitles`
--

INSERT INTO `jobtitles` (`id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'HR Manager', 'Oversees human resources operations', '2026-01-15 06:36:52', '2026-01-15 06:36:52'),
(2, 'HR Officer', 'Handles employee relations', '2026-01-15 06:36:52', '2026-01-15 06:36:52'),
(3, 'Accountant', 'Manages financial records', '2026-01-15 06:36:52', '2026-01-15 06:36:52'),
(4, 'IT Support', 'Provides technical assistance', '2026-01-15 06:36:52', '2026-01-15 06:36:52'),
(5, 'Software Developer', 'Develops internal systems', '2026-01-15 06:36:52', '2026-01-15 06:36:52'),
(6, 'Operations Supervisor', 'Supervises daily operations', '2026-01-15 06:36:52', '2026-01-15 06:36:52');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `leave_type` enum('Vacation','Sick','Emergency','Other') NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` int DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `user_id`, `date_from`, `date_to`, `leave_type`, `reason`, `status`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 3, '2026-01-22', '2026-01-24', 'Vacation', 'test', 'approved', 1, '2026-01-15 07:50:56', '2026-01-15 07:32:49', '2026-01-15 07:50:56'),
(2, 3, '2026-01-31', '2026-02-03', 'Emergency', 'N/A', 'approved', 1, '2026-01-15 07:59:57', '2026-01-15 07:59:24', '2026-01-15 07:59:57'),
(3, 3, '2026-01-16', '2026-01-17', 'Vacation', 'Schedule of my wedding.', 'approved', 1, '2026-01-15 11:16:00', '2026-01-15 11:12:14', '2026-01-15 11:16:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('employee','dept_head','admin') NOT NULL DEFAULT 'employee',
  `employee_id` varchar(50) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `contact_no` varchar(30) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `employee_id`, `department`, `contact_no`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'System Administrator', 'admin@grmxpert.com', '$2y$10$s5IT..w5eb12hjzGuqXvYON7I9QRjk.LWdMuLTMejN3IwZQwOMaT.', 'admin', NULL, 'IT', '09171234567', 'default.png', '2026-01-15 06:03:47', '2026-01-15 06:27:16'),
(2, 'Juan Dela Cruz', 'depthead@grmxpert.com', '$2y$10$3bHkPZl2L0wz6KZbQx9Y8uQk9nZKX2ZbCwFZp6Wn2Tn1P5Z9EJX8S', 'dept_head', 'DH-001', 'Human Resources', '09181234567', 'default.png', '2026-01-15 06:03:47', '2026-01-15 06:27:16'),
(3, 'Maria Santos', 'employee@grmxpert.com', '$2y$10$rj.NoqsilFkDs.3/cZh/iuca2UqWw3ZpyGK.4spyHv/acyObP1sCK', 'employee', '100', 'Accounting', '09191234567', 'default.png', '2026-01-15 06:03:47', '2026-01-15 08:54:37'),
(4, 'Gian Carlo S C', 'g@email.com', '$2y$10$MstsuWIWmXKzM51KctcyKeKQuIuAARL56W97a3ko/VQdlbj82YIE2', 'employee', '20', 'Accounting', '12345678', NULL, '2026-01-15 08:18:11', '2026-01-15 09:06:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_department_name` (`name`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_employee_no` (`employee_no`);

--
-- Indexes for table `employee_uploads`
--
ALTER TABLE `employee_uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_employee_uploads_user_id` (`user_id`),
  ADD KEY `idx_employee_uploads_status` (`status`);

--
-- Indexes for table `jobtitles`
--
ALTER TABLE `jobtitles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_jobtitles_title` (`title`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_leave_user` (`user_id`),
  ADD KEY `idx_leave_status` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_uploads`
--
ALTER TABLE `employee_uploads`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobtitles`
--
ALTER TABLE `jobtitles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
