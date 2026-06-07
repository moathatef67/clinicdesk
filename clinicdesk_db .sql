-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2026 at 03:54 PM
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
-- Database: `clinicdesk_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(10) UNSIGNED NOT NULL,
  `patient_id` int(10) UNSIGNED NOT NULL,
  `doctor_id` int(10) UNSIGNED NOT NULL,
  `appt_date` date NOT NULL,
  `appt_time` time NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `reason` varchar(255) DEFAULT NULL,
  `doctor_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appt_date`, `appt_time`, `status`, `reason`, `doctor_notes`, `created_at`) VALUES
(24, 31, 25, '2026-06-07', '15:00:00', 'completed', NULL, NULL, '2026-06-07 07:08:17'),
(25, 31, 25, '2026-06-07', '11:30:00', 'completed', NULL, NULL, '2026-06-07 07:10:15');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `specialization_id` int(10) UNSIGNED DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `consultation_fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `available_days` varchar(50) NOT NULL DEFAULT 'Sun,Mon,Tue,Wed,Thu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `user_id`, `specialization_id`, `bio`, `consultation_fee`, `available_days`) VALUES
(12, 17, 2, 'طبيب قلب ممتاز', 150.00, 'Sun, Mon, Wed'),
(15, 18, 3, '', 10.00, 'Sun,Mon,Tue,Wed,Thu'),
(16, 19, 10, 'hasan hassan hason', 147.00, 'Sun,Mon,Tue,Wed,Thu'),
(18, 21, 3, '', 14.00, 'Sun,Mon,Tue,Wed,Thu'),
(19, 22, 1, '', 56.00, 'Sun,Mon,Tue,Wed,Thu'),
(20, 23, 6, '', 16.00, 'Sun,Mon,Tue,Wed,Thu'),
(21, 24, 6, '', 54.00, 'Sun,Mon,Tue,Wed,Thu'),
(22, 25, 5, '', 12.00, 'Sun,Mon,Tue,Wed,Thu'),
(23, 26, 6, '', 5120.00, 'Sun,Mon,Tue,Wed,Thu'),
(24, 27, 1, '', 145.00, 'Sun,Mon,Tue,Wed,Thu'),
(25, 28, 4, '', 147.00, 'Sun,Mon,Tue,Wed,Thu');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `medical_info` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `user_id`, `medical_info`, `created_at`) VALUES
(31, 31, '', '2026-06-04 18:08:12');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `appointment_id` int(10) UNSIGNED NOT NULL,
  `doctor_id` int(10) UNSIGNED NOT NULL,
  `patient_id` int(10) UNSIGNED NOT NULL,
  `prescription_text` text NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `appointment_id`, `doctor_id`, `patient_id`, `prescription_text`, `file_path`, `created_at`) VALUES
(24, 24, 28, 31, 'مرحبا بك صورة', 'uploads/prescriptions/1780816145_clean_drawing.png', '2026-06-07 07:09:05'),
(25, 25, 28, 31, 'صورة دار', 'uploads/prescriptions/1780816250_17629598_Scene-89.jpg', '2026-06-07 07:10:50');

-- --------------------------------------------------------

--
-- Table structure for table `specializations`
--

CREATE TABLE `specializations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specializations`
--

INSERT INTO `specializations` (`id`, `name`) VALUES
(2, 'Cardiology'),
(3, 'Dermatology'),
(8, 'ENT'),
(1, 'General Practice'),
(6, 'Neurology'),
(7, 'Ophthalmology'),
(5, 'Orthopedics'),
(4, 'Pediatrics'),
(9, 'Psychiatry'),
(10, 'عام');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(180) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','doctor','patient') NOT NULL DEFAULT 'patient',
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `avatar`, `is_active`, `created_at`) VALUES
(1, 'Admin', 'admin@clinic.local', '$2y$12$JneyphNZEDWr8dabLb.wR.L4ADyNGvCRMVjwlEA1Oyf.dXSaVh3zO', 'admin', NULL, NULL, 1, '2026-05-26 09:58:32'),
(17, 'moath atef', 'moathatef@clinic.local', '$2y$10$Z4TUQY9HPJ7i4uJyRpq.BOF3oVtOxwcXi7guVN4AosbWehAfvXMz2', 'doctor', NULL, NULL, 1, '2026-06-04 13:52:18'),
(18, 'ahmad', 'ahmad@clinic.local', '$2y$10$3fKxCCcF.bsCLhOmOBfXmuzkxXbys.XY.R.VzKl50mDDc606zpng.', 'doctor', NULL, NULL, 1, '2026-06-04 14:02:57'),
(19, 'hasan', 'hasan@clinic.local', '$2y$10$50ZCo.Kn.jcXh1JCAI2JgO7.ozAwOLorNGCot778iiRxtVtSTya/S', 'doctor', NULL, NULL, 1, '2026-06-04 16:43:06'),
(21, 'tariq tariq', 'tariq@clinic.local', '$2y$10$FDAbxUWABowhYBPXDRKdzeuUqXG.gaTjvNbWkxv9DraYwomHFCYFa', 'doctor', NULL, NULL, 1, '2026-06-04 17:12:10'),
(22, 'mahmud mahmud', 'mahmod@clinic.local', '$2y$10$TiKFzHUlOEEPKm8g2DUonOIw/AVJtLxxaMtGjVlT0T2DjgtP1nkWe', 'doctor', NULL, NULL, 1, '2026-06-04 17:12:43'),
(23, 'sara sara', 'sara@clinic.local', '$2y$10$j3rFHVaYk/HvRFDPtAEiCuF.sO5aHb9CZxvjjKQqRJWYY7eYcKocq', 'doctor', NULL, NULL, 1, '2026-06-04 17:13:08'),
(24, 'mohanad', 'mohanad@clinic.local', '$2y$10$C47aiTTogaejlfCh914C5uX45YfI/NjmifELoHkNEQ8kPA0IT8X.q', 'doctor', NULL, NULL, 1, '2026-06-04 17:13:46'),
(25, 'snad', 'snad@clinic.local', '$2y$10$VP4L3wDTDq8nIG2LSFHFjegrAydXlEw.cgVRKudbIvECCX/dmbax2', 'doctor', NULL, NULL, 1, '2026-06-04 17:14:22'),
(26, 'kamel', 'kamel@clinic.local', '$2y$10$3THaRgWejOtrK338pFtqBOINP9ByiUhHugzJn1LkXABBgcy9gf.4y', 'doctor', NULL, NULL, 1, '2026-06-04 17:14:45'),
(27, 'ezz', 'ezz@clinic.local', '$2y$10$n7x8chai5E90LXNveen1BOAN627evOOfQIJiHphpgdcEiQVbYcbmq', 'doctor', NULL, NULL, 1, '2026-06-04 17:15:24'),
(28, 'issa', 'issa@clinic.local', '$2y$10$dmDL6mpdQQ7CdD743Xi1q.cmwVIPlP4YXzfBT596i.IfetMJeq1.u', 'doctor', NULL, NULL, 1, '2026-06-04 17:15:51'),
(31, 'patient', 'patient@clinic.local', '$2y$10$dmDL6mpdQQ7CdD743Xi1q.cmwVIPlP4YXzfBT596i.IfetMJeq1.u', 'patient', '0123456789', NULL, 1, '2026-06-04 18:07:45'),
(32, 'معاذ', 'pant@clinic.local', '$2y$10$fsRt07U01V1U8n/PeF9mOu4l0r4TE3OggoqhFRerJbIIE0weccbqu', 'patient', NULL, NULL, 1, '2026-06-07 11:22:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_double_booking` (`doctor_id`,`appt_date`,`appt_time`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `specialization_id` (`specialization_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `specializations`
--
ALTER TABLE `specializations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `specializations`
--
ALTER TABLE `specializations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctors_ibfk_2` FOREIGN KEY (`specialization_id`) REFERENCES `specializations` (`id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_ibfk_3` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
