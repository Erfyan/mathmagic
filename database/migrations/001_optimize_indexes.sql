-- ============================================
-- MATHMAGIC DATABASE MIGRATION (Modern MVC)
-- Compatible with existing mathmagic schema
-- ============================================
-- This migration is designed to work alongside
-- the existing sql/mathmagic_db.sql schema.
-- Run only if starting fresh or adding new columns.

-- Ensure users table has all required columns
ALTER TABLE `users`
  MODIFY `password_hash` varchar(255) NOT NULL,
  MODIFY `role` enum('siswa','guru','admin') NOT NULL DEFAULT 'siswa';

-- Ensure student_stats has weekly_points column
ALTER TABLE `student_stats`
  ADD COLUMN IF NOT EXISTS `weekly_points` int DEFAULT 0;

-- Ensure student_stats has total_quiz_taken column  
ALTER TABLE `student_stats`
  ADD COLUMN IF NOT EXISTS `total_quiz_taken` int DEFAULT 0;

-- Index optimization for leaderboard queries
CREATE INDEX IF NOT EXISTS `idx_stats_points` ON `student_stats` (`total_points` DESC);
CREATE INDEX IF NOT EXISTS `idx_stats_student` ON `student_stats` (`student_id`);
CREATE INDEX IF NOT EXISTS `idx_levels_student` ON `student_levels` (`student_id`, `subject_id`);
CREATE INDEX IF NOT EXISTS `idx_badges_student` ON `badges` (`student_id`);
CREATE INDEX IF NOT EXISTS `idx_game_results_student` ON `game_results` (`student_id`);
CREATE INDEX IF NOT EXISTS `idx_users_email` ON `users` (`email`);
CREATE INDEX IF NOT EXISTS `idx_users_role` ON `users` (`role`);
