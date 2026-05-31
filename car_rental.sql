-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2026 at 03:44 AM
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
-- Database: `car_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','receptionist','accountant') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$/79UknXSuuuo0Z04RKMPQu10jvuWF5bWk58CyFoqr.TBug6LzV.xO', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `model_name` varchar(100) NOT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `price_per_day` decimal(10,2) DEFAULT NULL,
  `selling_price` decimal(10,2) DEFAULT 0.00,
  `status` enum('available','rented','sold') DEFAULT 'available',
  `car_image` varchar(255) DEFAULT NULL,
  `rent_price` decimal(10,2) NOT NULL,
  `last_maintenance_date` date DEFAULT NULL,
  `next_maintenance_km` int(11) DEFAULT 10000,
  `current_km` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `model_name`, `purchase_price`, `price_per_day`, `selling_price`, `status`, `car_image`, `rent_price`, `last_maintenance_date`, `next_maintenance_km`, `current_km`) VALUES
(1, 'BMW M4 2026', 2500000.00, 3000.00, 0.00, 'rented', NULL, 0.00, NULL, 10000, 0),
(2, 'Porsche 911 Turbo', 9000000.00, 8000.00, 0.00, 'rented', NULL, 0.00, NULL, 10000, 0),
(3, 'Fiat Tipo 2025', 900000.00, 850.00, 0.00, 'rented', NULL, 0.00, NULL, 10000, 0),
(4, 'Range Rover Velar', 5500000.00, 4500.00, 0.00, 'available', NULL, 0.00, NULL, 10000, 0),
(5, 'Mg 6 2024', 1150000.00, 1100.00, 0.00, 'available', NULL, 0.00, NULL, 10000, 0),
(6, 'Skoda Octavia A8', 1850000.00, 1700.00, 0.00, 'available', NULL, 0.00, NULL, 10000, 0),
(7, 'Mercedes C200', 3200000.00, 3500.00, 0.00, 'available', NULL, 0.00, NULL, 10000, 0),
(8, 'Hyundai Elantra CN7', 1400000.00, 1300.00, 0.00, 'available', NULL, 0.00, NULL, 10000, 0),
(9, 'Kia Sportage', 1900000.00, 1800.00, 0.00, 'available', NULL, 0.00, NULL, 10000, 0),
(10, 'Toyota Corolla', 1600000.00, 1500.00, 0.00, 'available', NULL, 0.00, NULL, 10000, 0),
(11, 'Jeep Renegade', 1700000.00, 1600.00, 0.00, 'available', NULL, 0.00, NULL, 10000, 0),
(12, 'Audi A4', 2800000.00, 2800.00, 0.00, 'available', NULL, 0.00, NULL, 10000, 0),
(13, 'Peugeot 5008', 2100000.00, 2000.00, 0.00, 'available', NULL, 0.00, NULL, 10000, 0),
(14, 'Nissan Sunny', 800000.00, 700.00, 0.00, 'available', NULL, 0.00, NULL, 10000, 0),
(15, 'Chevrolet Captiva', 1300000.00, 1200.00, 0.00, 'available', NULL, 0.00, NULL, 10000, 0),
(16, 'Opel', 25000.00, 5000.00, 0.00, 'rented', '1780182902_images.jfif', 0.00, NULL, 36, 25);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `full_name`, `phone`, `email`, `points`, `created_at`) VALUES
(1, 'Abdullah farag', '01205156342', 'abdullah@restaurant.com', 2600, '2026-05-30 23:38:35');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `role`, `password`, `created_at`) VALUES
(1, 'Ahmed', 'accountant', '2010', '2026-05-30 04:33:20'),
(3, 'abdullah', 'admin', '2002', '2026-05-30 04:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `notifications_log`
--

CREATE TABLE `notifications_log` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications_log`
--

INSERT INTO `notifications_log` (`id`, `message`, `created_at`) VALUES
(1, 'تم حجز السيارة  للعميل رقم ', '2026-05-31 03:31:49');

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(100) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`id`, `car_id`, `customer_name`, `start_date`, `end_date`, `total_price`, `created_at`, `added_by`, `customer_id`, `status`) VALUES
(2, 1, 'Abdullah farag Elshahat', '0000-00-00', '0000-00-00', 25000.00, '2026-05-30 21:07:31', 'abdullah', NULL, 'active'),
(3, 2, 'Abdullah farag Elshahat', '2026-05-19', '2026-06-01', 104000.00, '2026-05-30 21:50:59', NULL, NULL, 'active'),
(4, 16, 'Abdullah farag Elshahat', '2026-05-31', '2026-06-03', 15000.00, '2026-05-30 23:33:25', NULL, NULL, 'active'),
(5, 3, '', '0000-00-00', '0000-00-00', 260000.00, '2026-05-30 23:39:53', 'abdullah', 1, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(11) NOT NULL,
  `emp_name` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `priority` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'مفتوحة'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `emp_name`, `subject`, `message`, `priority`, `created_at`, `status`) VALUES
(1, 'Ahmed', 'السياره اتاخرت ', 'المفروض استلمها يوم 30/05/2025 ليس مش استلمتها ', 'medium', '2026-05-30 21:34:31', 'تم الحل');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications_log`
--
ALTER TABLE `notifications_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications_log`
--
ALTER TABLE `notifications_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
