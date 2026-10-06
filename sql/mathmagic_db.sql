-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 07, 2025 at 01:55 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mathmagic`
--

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `badge_key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `awarded_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `badges`
--

INSERT INTO `badges` (`id`, `student_id`, `badge_key`, `title`, `description`, `awarded_at`) VALUES
(1, 1, 'fast_solver', 'Pemecah Soal Cepat', 'Menyelesaikan soal dengan waktu sangat cepat', '2025-11-23 13:50:03'),
(2, 1, 'weekly_champion', 'Juara Mingguan', 'Menjadi peringkat atas dalam progres mingguan', '2025-11-23 13:50:03'),
(3, 1, 'consistent_learner', 'Belajar Konsisten', 'Menjaga aktivitas belajar setiap hari', '2025-11-23 13:50:03');

-- --------------------------------------------------------

--
-- Table structure for table `forum_comments`
--

CREATE TABLE `forum_comments` (
  `id` int NOT NULL,
  `thread_id` int NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int NOT NULL,
  `role` enum('siswa','guru') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_threads`
--

CREATE TABLE `forum_threads` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int NOT NULL,
  `role` enum('siswa','guru') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `game_results`
--

CREATE TABLE `game_results` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `game_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `points` int NOT NULL DEFAULT '0',
  `time_spent` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_results`
--

INSERT INTO `game_results` (`id`, `student_id`, `game_name`, `points`, `time_spent`, `created_at`) VALUES
(1, 1, 'Adventure', 50, 120, '2025-11-23 18:29:36'),
(4, 1, 'Adventure', 80, 90, '2025-11-23 18:29:36');

-- --------------------------------------------------------

--
-- Table structure for table `question_bank`
--

CREATE TABLE `question_bank` (
  `id` int NOT NULL,
  `created_by` int DEFAULT NULL,
  `subject_id` int NOT NULL,
  `kelas` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('mcq','essay','match','numeric') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'mcq',
  `question` text COLLATE utf8mb4_general_ci NOT NULL,
  `option_a` text COLLATE utf8mb4_general_ci,
  `option_b` text COLLATE utf8mb4_general_ci,
  `option_c` text COLLATE utf8mb4_general_ci,
  `option_d` text COLLATE utf8mb4_general_ci,
  `correct_answer` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `difficulty` tinyint DEFAULT '3',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_bank`
--

INSERT INTO `question_bank` (`id`, `created_by`, `subject_id`, `kelas`, `type`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `difficulty`, `created_at`) VALUES
(1, 4, 1, 'SMP', '', '1 + 1', '2', '3', '4', '5', 'A', 0, '2025-11-24 12:30:46'),
(12, 1, 1, 'SMP', 'mcq', 'Berapakah hasil dari 12 + 8?', '16', '18', '20', '22', 'C', 1, '2025-11-26 15:37:06'),
(13, 1, 1, 'SMP', 'mcq', 'Berapakah nilai dari 15 × 3?', '30', '45', '40', '50', 'B', 1, '2025-11-26 15:37:06'),
(14, 1, 1, 'SMP', 'mcq', 'Berapakah hasil dari 100 ÷ 4?', '20', '25', '30', '40', 'B', 1, '2025-11-26 15:37:06'),
(15, 1, 1, 'SMP', 'mcq', 'Berapakah hasil 9²?', '81', '72', '63', '99', 'A', 2, '2025-11-26 15:37:06'),
(16, 1, 1, 'SMP', 'mcq', 'Berapakah akar kuadrat dari 144?', '10', '11', '12', '13', 'C', 2, '2025-11-26 15:37:06'),
(17, 1, 1, 'SMP', 'mcq', 'Nilai dari 3x jika x = 7 adalah...', '10', '18', '21', '25', 'C', 1, '2025-11-26 15:37:06'),
(18, 1, 1, 'SMP', 'mcq', 'Hasil dari 50% dari 200 adalah...', '50', '75', '100', '150', 'C', 2, '2025-11-26 15:37:06'),
(19, 1, 1, 'SMP', 'mcq', 'Berapa hasil dari 2³?', '6', '8', '12', '16', 'B', 1, '2025-11-26 15:37:06'),
(20, 1, 1, 'SMP', 'mcq', 'Jika a = 5 dan b = 3, maka a² + b² = ...', '25', '34', '30', '28', 'B', 2, '2025-11-26 15:37:06'),
(21, 1, 1, 'SMP', 'mcq', 'Hasil dari 120 - 45 adalah...', '65', '70', '75', '85', 'A', 1, '2025-11-26 15:37:06'),
(22, NULL, 3, 'SMA', 'mcq', 'Nilai dari limit lim_{x→2} (x² − 4) / (x − 2) adalah?', '2', '3', '4', '5', 'C', 0, '2025-11-26 15:59:36'),
(23, NULL, 3, 'SMA', 'mcq', 'Turunan pertama dari f(x) = 3x³ − 5x² + 7 adalah?', '9x² − 10x', '6x − 10', '9x² − 10x + 7', '9x² − 5x', 'A', 0, '2025-11-26 15:59:36'),
(24, NULL, 3, 'SMA', 'mcq', 'Jika matriks A = [[2,3],[1,4]], maka determinan A adalah?', '5', '7', '8', '11', 'B', 0, '2025-11-26 15:59:36'),
(25, NULL, 3, 'SMA', 'mcq', 'Integral dari ∫ (4x³ − 6x) dx adalah?', 'x⁴ − 3x² + C', 'x⁴ − 3x²', '4x² − 6x + C', 'x⁴ − 6x² + C', 'A', 0, '2025-11-26 15:59:36'),
(26, NULL, 3, 'SMA', 'mcq', 'Hasil dari sin(30°) + cos(60°) adalah?', '1/2', '1', '√3/2', '3/4', 'B', 0, '2025-11-26 15:59:36'),
(27, NULL, 3, 'SMA', 'mcq', 'Jika barisan geometri memiliki suku pertama 5 dan rasio 3, maka suku ke-6 adalah?', '405', '1215', '3645', '10935', 'B', 0, '2025-11-26 15:59:36'),
(28, NULL, 3, 'SMA', 'mcq', 'Persamaan trigonometri sin x = 1/2 memiliki solusi umum?', 'x = π/6 + 2kπ', 'x = 5π/6 + 2kπ', 'x = π/6 & 5π/6 + 2kπ', 'x = 3π/2 + 2kπ', 'C', 0, '2025-11-26 15:59:36'),
(29, NULL, 3, 'SMA', 'mcq', 'Jika peluang suatu kejadian A adalah 0,2 dan kejadian B adalah 0,5 dengan A dan B saling lepas, maka P(A ∪ B) adalah?', '0,3', '0,5', '0,7', '0,9', 'C', 0, '2025-11-26 15:59:36'),
(30, NULL, 3, 'SMA', 'mcq', 'Hasil dari log(√1000) dalam basis 10 adalah?', '1', '1,5', '2', '2,5', 'B', 0, '2025-11-26 15:59:36'),
(31, NULL, 3, 'SMA', 'mcq', 'Jika fungsi f(x) = 2x − 3, maka invers fungsi f⁻¹(x) adalah?', '(x + 3)/2', '(x − 3)/2', '2x + 3', '3 − 2x', 'A', 0, '2025-11-26 15:59:36');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_list`
--

CREATE TABLE `quiz_list` (
  `id` int NOT NULL,
  `subject_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `difficulty` enum('easy','medium','hard') COLLATE utf8mb4_general_ci DEFAULT 'easy',
  `total_questions` int DEFAULT '10',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_list`
--

INSERT INTO `quiz_list` (`id`, `subject_id`, `title`, `difficulty`, `total_questions`, `created_at`) VALUES
(3, 3, 'Latihan Matematika SMA - Sulit', '', 10, '2025-11-26 08:03:19'),
(10, 0, 'Quiz ipa', 'easy', 1, '2025-11-26 09:45:35'),
(11, 0, 'IPA', 'easy', 0, '2025-11-26 09:58:55'),
(12, 0, 'IPA', 'easy', 0, '2025-11-26 10:17:38'),
(15, 0, 'Matematika', '', 0, '2025-11-26 10:33:52'),
(16, 0, 'B. Indo', '', 0, '2025-11-26 10:34:45'),
(17, 0, 'B. Indo', '', 0, '2025-11-26 10:38:17'),
(18, 0, 'B. Indo', '', 0, '2025-11-26 10:41:50'),
(19, 0, 'B. Indo', '', 0, '2025-11-26 10:48:56'),
(20, 0, 'B. Indo', '', 0, '2025-11-26 10:52:21');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `quiz_title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `score` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_results`
--

INSERT INTO `quiz_results` (`id`, `student_id`, `quiz_title`, `score`, `created_at`) VALUES
(15, 1, 'Kuis Matematika Dasar', 85, '2025-11-23 16:50:55'),
(18, 1, 'Kuis Aljabar', 70, '2025-11-23 16:50:55'),
(21, 1, 'Kuis Geometri', 90, '2025-11-23 16:50:55');

-- --------------------------------------------------------

--
-- Table structure for table `student_activity`
--

CREATE TABLE `student_activity` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `activity` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_levels`
--

CREATE TABLE `student_levels` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `level` int DEFAULT '1',
  `exp` int DEFAULT '0',
  `total_score` int DEFAULT '0',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_score_history`
--

CREATE TABLE `student_score_history` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `score` int NOT NULL,
  `week_label` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_score_history`
--

INSERT INTO `student_score_history` (`id`, `student_id`, `score`, `week_label`, `created_at`) VALUES
(19, 1, 68, 'M1', '2025-11-26 17:09:46'),
(20, 1, 89, 'M2', '2025-11-26 17:09:46'),
(21, 1, 62, 'M3', '2025-11-26 17:09:46'),
(22, 1, 66, 'M4', '2025-11-26 17:09:46'),
(23, 1, 82, 'M5', '2025-11-26 17:09:46'),
(24, 1, 73, 'M6', '2025-11-26 17:09:46'),
(25, 1, 10, 'W48-2025', '2025-11-26 17:24:01');

-- --------------------------------------------------------

--
-- Table structure for table `student_slow_topics`
--

CREATE TABLE `student_slow_topics` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `avg_time` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_slow_topics`
--

INSERT INTO `student_slow_topics` (`id`, `student_id`, `question`, `avg_time`, `created_at`) VALUES
(1, 1, 'Aritmatika Sosial', 32, '2025-11-23 13:55:28'),
(2, 1, 'Pecahan', 49, '2025-11-23 13:55:28'),
(3, 1, 'Statistika Dasar', 50, '2025-11-23 13:55:28');

-- --------------------------------------------------------

--
-- Table structure for table `student_stats`
--

CREATE TABLE `student_stats` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `subject_id` int DEFAULT NULL,
  `total_points` int DEFAULT '0',
  `total_quizzes` int DEFAULT '0',
  `total_games` int DEFAULT '0',
  `correct_answers` int DEFAULT '0',
  `wrong_answers` int DEFAULT '0',
  `level` int DEFAULT '1',
  `progress_percent` tinyint DEFAULT '0',
  `avg_score` float DEFAULT '0',
  `weekly_progress` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `last_activity` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `total_quiz_taken` int DEFAULT '0',
  `weekly_points` int DEFAULT '0'
) ;

--
-- Dumping data for table `student_stats`
--

INSERT INTO `student_stats` (`id`, `student_id`, `subject_id`, `total_points`, `total_quizzes`, `total_games`, `correct_answers`, `wrong_answers`, `level`, `progress_percent`, `avg_score`, `weekly_progress`, `last_activity`, `updated_at`, `total_quiz_taken`, `weekly_points`) VALUES
(3, 1, NULL, 187, 5, 4, 46, 18, 5, 25, 86, '[]', '2025-11-26 15:57:03', '2025-11-26 15:57:03', 5, 14);

-- --------------------------------------------------------

--
-- Table structure for table `student_weak_topics`
--

CREATE TABLE `student_weak_topics` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `wrong_count` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_weak_topics`
--

INSERT INTO `student_weak_topics` (`id`, `student_id`, `question`, `wrong_count`, `created_at`) VALUES
(1, 1, 'Persamaan Linear', 9, '2025-11-23 13:55:28'),
(2, 1, 'Bangun Datar', 8, '2025-11-23 13:55:28'),
(3, 1, 'Aritmatika Sosial', 9, '2025-11-23 13:55:28');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `code`, `name`, `description`) VALUES
(1, 'MTK', 'Matematika', 'Materi dasar matematika'),
(2, 'ALG', 'Aljabar', 'Persamaan & variabel'),
(3, 'GEO', 'Geometri', 'Bangun ruang & datar');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `fullname` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('siswa','guru','admin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'siswa',
  `kelas` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mapel` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `role`, `kelas`, `mapel`, `password_hash`, `avatar`, `created_at`, `last_login`, `is_active`) VALUES
(1, 'erfyan', 'erfyanjr@gmail.com', 'siswa', 'SMA-11', NULL, '$2y$10$4AxdGKe518wP.jIabrQwie24PbD.GCVsxPiyJ.nyA93emq8g6MHwi', '../public/uploads/1763818168_IMG_9855.JPG', '2025-11-22 08:21:28', '2025-11-26 15:15:40', 1),
(4, 'guru2', 'guru2@gmail.com', 'guru', NULL, 'IPA', '$2y$10$TO1/pSt1zyaRR51EY55BjuvtuzZWgrSay04/m6OAWapafS4W9UIkC', NULL, '2025-11-24 11:51:59', '2025-11-26 18:54:28', 1),
(6, 'Hartati S.Pd', 'guru@gmail.com', 'guru', NULL, 'IPA', '$2y$10$orGZ0wQBXQ6FghlmdqqGXemQ92ff3PpvNTij9UtKlvDlGqzTc7s3G', NULL, '2025-12-07 21:47:36', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_student` (`student_id`);

--
-- Indexes for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thread_id` (`thread_id`);

--
-- Indexes for table `forum_threads`
--
ALTER TABLE `forum_threads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game_results`
--
ALTER TABLE `game_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `question_bank`
--
ALTER TABLE `question_bank`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_subject_kelas` (`subject_id`,`kelas`);

--
-- Indexes for table `quiz_list`
--
ALTER TABLE `quiz_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_activity`
--
ALTER TABLE `student_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_levels`
--
ALTER TABLE `student_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_student_subject` (`student_id`,`subject_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `student_score_history`
--
ALTER TABLE `student_score_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_slow_topics`
--
ALTER TABLE `student_slow_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_stats`
--
ALTER TABLE `student_stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_student` (`student_id`),
  ADD KEY `idx_subject` (`subject_id`);

--
-- Indexes for table `student_weak_topics`
--
ALTER TABLE `student_weak_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `forum_comments`
--
ALTER TABLE `forum_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_threads`
--
ALTER TABLE `forum_threads`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `game_results`
--
ALTER TABLE `game_results`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `question_bank`
--
ALTER TABLE `question_bank`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `quiz_list`
--
ALTER TABLE `quiz_list`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `student_activity`
--
ALTER TABLE `student_activity`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_levels`
--
ALTER TABLE `student_levels`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_score_history`
--
ALTER TABLE `student_score_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `student_slow_topics`
--
ALTER TABLE `student_slow_topics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `student_stats`
--
ALTER TABLE `student_stats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_weak_topics`
--
ALTER TABLE `student_weak_topics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `badges`
--
ALTER TABLE `badges`
  ADD CONSTRAINT `badges_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD CONSTRAINT `forum_comments_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `forum_threads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `game_results`
--
ALTER TABLE `game_results`
  ADD CONSTRAINT `game_results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_bank`
--
ALTER TABLE `question_bank`
  ADD CONSTRAINT `question_bank_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `question_bank_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD CONSTRAINT `quiz_results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_levels`
--
ALTER TABLE `student_levels`
  ADD CONSTRAINT `student_levels_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_levels_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_score_history`
--
ALTER TABLE `student_score_history`
  ADD CONSTRAINT `student_score_history_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_slow_topics`
--
ALTER TABLE `student_slow_topics`
  ADD CONSTRAINT `student_slow_topics_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_stats`
--
ALTER TABLE `student_stats`
  ADD CONSTRAINT `student_stats_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_stats_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `student_weak_topics`
--
ALTER TABLE `student_weak_topics`
  ADD CONSTRAINT `student_weak_topics_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
