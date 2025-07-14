-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2025 at 11:35 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blood_donate`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `country` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `super_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `phone` varchar(20) NOT NULL,
  `is_protected` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `first_name`, `last_name`, `username`, `email`, `gender`, `country`, `password`, `super_admin`, `created_at`, `updated_at`, `phone`, `is_protected`) VALUES
(1, 'Salma', 'Akter', 'salma123', 'salma123@gmail.com', 'Female', 'Bangladesh', '$2y$10$ljjxcBjOeNKRJMYX2pD1h.lUr.w00j5W07X/VqFWVsKu/.MCUfmaO', 0, '2025-06-29 08:25:01', '2025-07-08 04:49:39', '01828392184', 0),
(4, 'Md Rasel', 'Ahmed', 'rasel123', 'rasel123@gmail.com', 'Male', 'Bangladesh', '$2y$10$7zGAIKVu.ADc3CMFn3Oxl.kwO4GMonPtLHCRqaAsiCwlMDggmIrlK', 1, '2025-07-06 07:07:22', '2025-07-06 12:48:06', '01929951023', 1),
(7, 'SM Shariful Islam', 'Hiyan', 'hiyan123', 'hiyan123@gmail.com', 'Male', 'Bangladesh', '$2y$10$Vg.co245.tK7mHoFQgMfZOnAHIiNFvq4K2Y6hJ8I6HUitrv46UGri', 1, '2025-07-07 22:10:53', '2025-07-08 04:46:07', '01993834841', 1);

-- --------------------------------------------------------

--
-- Table structure for table `blood_groups`
--

CREATE TABLE `blood_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_groups`
--

INSERT INTO `blood_groups` (`id`, `name`, `created_at`, `updated_at`) VALUES
(3, 'B+', '2025-07-02 05:52:49', '2025-07-02 05:52:49'),
(4, 'B-', '2025-07-02 05:52:49', '2025-07-02 05:52:49'),
(5, 'AB+', '2025-07-02 05:52:49', '2025-07-02 05:52:49'),
(6, 'AB-', '2025-07-02 05:52:49', '2025-07-02 05:52:49'),
(7, 'O+', '2025-07-02 05:52:49', '2025-07-02 05:52:49'),
(8, 'O-', '2025-07-02 05:52:49', '2025-07-02 05:52:49'),
(18, 'A-', '2025-07-02 07:58:43', '2025-07-02 10:06:54'),
(19, 'A+', '2025-07-02 10:07:03', '2025-07-02 10:07:03');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Dhaka', '2025-07-02 08:04:55', '2025-07-02 09:59:16'),
(2, 'Chattogram', '2025-07-02 08:04:55', '2025-07-02 08:04:55'),
(3, 'Khulna', '2025-07-02 08:04:55', '2025-07-02 08:04:55'),
(4, 'Rajshahi', '2025-07-02 08:04:55', '2025-07-02 08:04:55'),
(5, 'Sylhet', '2025-07-02 08:04:55', '2025-07-02 08:04:55'),
(6, 'Barisal', '2025-07-02 08:04:55', '2025-07-07 16:16:42'),
(7, 'Rangpur', '2025-07-02 08:04:55', '2025-07-02 08:04:55'),
(8, 'Mymensingh', '2025-07-02 08:04:55', '2025-07-02 08:04:55'),
(11, 'Jamalpur', '2025-07-02 19:16:08', '2025-07-02 19:16:08'),
(12, 'Manikganj', '2025-07-07 16:16:17', '2025-07-07 16:16:17'),
(13, 'Jasshore', '2025-07-08 04:09:46', '2025-07-08 04:09:46');

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `pin_code` varchar(10) NOT NULL,
  `city_id` int(11) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `blood_group_id` int(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`id`, `name`, `gender`, `email`, `phone`, `address`, `pin_code`, `city_id`, `country`, `blood_group_id`, `password`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Alice Rahman', 'Female', 'alice@example.com', '01711112222', '123 Gulshan Ave, Dhaka', '1212', 1, 'Bangladesh', 19, '15363ee1cbd863f9bf47922da3105b62badce9b5ff4cd58b0af207eb92490c72', '2025-07-02 17:19:04', '2025-07-02 17:19:04', 'Active'),
(2, 'Rahim Uddin', 'Male', 'rahim@example.com', '01812345678', '45 Agrabad, Chittagong', '4000', 2, 'Bangladesh', 4, '15363ee1cbd863f9bf47922da3105b62badce9b5ff4cd58b0af207eb92490c72', '2025-07-02 17:19:04', '2025-07-02 17:19:04', 'Active'),
(3, 'Sadia Akter', 'Female', 'sadia@example.com', '01999887766', '789 Mirpur Road, Dhaka', '1207', 1, 'Bangladesh', 7, '15363ee1cbd863f9bf47922da3105b62badce9b5ff4cd58b0af207eb92490c72', '2025-07-02 17:19:04', '2025-07-04 15:18:15', 'Inactive'),
(10, 'Jamal Hossain', 'Male', 'jamal@example.com', '01722223333', '56 Banani, Dhaka', '1213', 1, 'Bangladesh', 6, '52bc47f80b6fe698e9a14327e1bb7ddfa39a21740c8c7017329f3ec555a3bcf6', '2025-07-02 17:32:47', '2025-07-02 17:32:47', 'Active'),
(11, 'Fatima Noor', 'Female', 'fatima@example.com', '01833334444', '78 Paltan, Dhaka', '1214', 1, 'Bangladesh', 3, '972172de0ae5edc8abb9addaf197f45fbb4dcc79a736282833b3e0d954cc5a55', '2025-07-02 17:32:47', '2025-07-02 17:32:47', 'Active'),
(12, 'Sami Ahmed', 'Other', 'sami@example.com', '01944445555', '101 Motijheel, Dhaka', '1215', 1, 'Bangladesh', 8, 'bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f', '2025-07-02 17:32:47', '2025-07-02 17:32:47', 'Active'),
(14, 'Sahadat', 'Male', 'sahadat123@gmail.com', '01932894395', 'Dhaka, Bangladesh', '4324', 2, 'Bangladesh', 5, '15363ee1cbd863f9bf47922da3105b62badce9b5ff4cd58b0af207eb92490c72', '2025-07-02 19:08:04', '2025-07-03 16:44:02', 'Active'),
(15, 'Lazina', 'Female', 'lazina123@gmail.com', '01323234534', 'Dhaka, Bangladesh', '5343', 11, 'Bangladesh', 7, '15363ee1cbd863f9bf47922da3105b62badce9b5ff4cd58b0af207eb92490c72', '2025-07-03 18:54:23', '2025-07-03 18:54:23', 'Active'),
(16, 'Aktara Banu', 'Female', 'banu123@gmail.com', '01929951024', 'Dhaka, Bangladesh', '1231', 6, 'Bangladesh', 19, '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2025-07-03 20:42:25', '2025-07-08 04:48:03', 'Inactive'),
(18, 'Mehedi', 'Male', 'mehedi123@gmail.com', '01929951054', 'Dhaka, Bangladesh', '5434', 2, 'Bangladesh', 6, '15363ee1cbd863f9bf47922da3105b62badce9b5ff4cd58b0af207eb92490c72', '2025-07-07 05:39:18', '2025-07-07 05:39:18', 'Active'),
(19, 'Aminul', 'Male', 'aminul123@gmail.com', '01929954576', 'Dhaka, Bangladesh', '5432', 6, 'Bangladesh', 18, '15363ee1cbd863f9bf47922da3105b62badce9b5ff4cd58b0af207eb92490c72', '2025-07-07 05:42:37', '2025-07-07 05:42:37', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `pin_code` varchar(10) NOT NULL,
  `city_id` int(11) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `gender`, `email`, `phone`, `address`, `pin_code`, `city_id`, `country`, `password`, `created_at`, `updated_at`) VALUES
(4, 'Tanvir Islam', 'Male', 'tanvir@example.com', '01755556666', '456 Dhanmondi, Dhaka', '1205', 1, 'Bangladesh', '15363ee1cbd863f9bf47922da3105b62badce9b5ff4cd58b0af207eb92490c72', '2025-07-03 19:27:45', '2025-07-03 19:27:45'),
(5, 'Rima Akhter', 'Female', 'rima123@gmail.com', '01866667777', '123 Uttara, Dhaka', '1230', 1, '0', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2025-07-03 19:27:45', '2025-07-07 22:18:41'),
(6, 'Asif Hossain', 'Male', 'asif@example.com', '01977778888', '789 Rajshahi Road', '6200', 3, 'Bangladesh', '15363ee1cbd863f9bf47922da3105b62badce9b5ff4cd58b0af207eb92490c72', '2025-07-03 19:27:45', '2025-07-03 19:27:45'),
(8, 'Biplob Borua', 'Male', 'biplob123@gmai.com', '01383983284', 'Dhaka, Bangladesh', '2343', 11, 'Bangladesh', '$2y$10$vIs5ttKEqjcCVo33BoJxXuThRVV05B30sz/.QjkqGsoU4nwEdL2iO', '2025-07-06 06:43:00', '2025-07-06 06:46:41'),
(9, 'Bokul', 'Female', 'bokul123@gmail.com', '01723948792', 'Dhaka, Bangladesh', '5434', 7, 'Bangladesh', '15363ee1cbd863f9bf47922da3105b62badce9b5ff4cd58b0af207eb92490c72', '2025-07-07 16:20:48', '2025-07-07 16:20:48'),
(10, 'Priya', 'Female', 'priya123@gmail.com', '01334343973', 'Dhaka, Bangladesh', '4323', 1, '0', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2025-07-08 04:43:55', '2025-07-08 04:48:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `blood_groups`
--
ALTER TABLE `blood_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `blood_group_id` (`blood_group_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `city_id` (`city_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `blood_groups`
--
ALTER TABLE `blood_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donors`
--
ALTER TABLE `donors`
  ADD CONSTRAINT `donors_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `donors_ibfk_2` FOREIGN KEY (`blood_group_id`) REFERENCES `blood_groups` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
