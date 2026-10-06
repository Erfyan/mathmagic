<?php

/**
 * MATHMAGIC - Database Seed: Default Demo Users
 * 
 * Run this script once to insert demo accounts for testing.
 * php database/seeds/seed_demo_users.php
 */

require_once __DIR__ . '/../../init.php';

use App\Core\Database;

$db = Database::getConnection();

echo "=== MathMagic Demo Data Seeder ===\n\n";

// Check if demo users already exist
$stmt = $db->prepare("SELECT COUNT(*) as c FROM users WHERE email IN (:e1, :e2)");
$stmt->execute(['e1' => 'demo.siswa@mathmagic.id', 'e2' => 'demo.guru@mathmagic.id']);
$row = $stmt->fetch();

if ($row['c'] > 0) {
    echo "[SKIP] Demo users already exist.\n";
} else {
    // Insert demo siswa
    $siswaHash = password_hash('siswa123', PASSWORD_BCRYPT);
    $db->prepare("INSERT INTO users (fullname, email, role, kelas, password_hash, is_active, created_at) VALUES (:name, :email, :role, :kelas, :pass, 1, NOW())")
       ->execute([
           'name' => 'Budi Siswa Demo',
           'email' => 'demo.siswa@mathmagic.id',
           'role' => 'siswa',
           'kelas' => 'SMP-8',
           'pass' => $siswaHash
       ]);
    $siswaId = $db->lastInsertId();
    echo "[OK] Demo Siswa created (ID: {$siswaId}) — email: demo.siswa@mathmagic.id / pass: siswa123\n";

    // Init gamification stats for the demo student
    $db->prepare("INSERT INTO student_stats (student_id, total_points, total_quizzes, total_games, correct_answers, wrong_answers, `level`, progress_percent, avg_score, weekly_progress, total_quiz_taken, weekly_points) VALUES (:sid, 0, 0, 0, 0, 0, 1, 0, 0, '[]', 0, 0)")
       ->execute(['sid' => $siswaId]);

    $db->prepare("INSERT INTO student_levels (student_id, subject_id, `level`, exp, total_score) VALUES (:sid, 1, 1, 0, 0)")
       ->execute(['sid' => $siswaId]);

    // Insert demo guru
    $guruHash = password_hash('guru123', PASSWORD_BCRYPT);
    $db->prepare("INSERT INTO users (fullname, email, role, mapel, password_hash, is_active, created_at) VALUES (:name, :email, :role, :mapel, :pass, 1, NOW())")
       ->execute([
           'name' => 'Ibu Sari Guru Demo',
           'email' => 'demo.guru@mathmagic.id',
           'role' => 'guru',
           'mapel' => 'Matematika',
           'pass' => $guruHash
       ]);
    $guruId = $db->lastInsertId();
    echo "[OK] Demo Guru created (ID: {$guruId}) — email: demo.guru@mathmagic.id / pass: guru123\n";
}

echo "\n=== Seeding Complete ===\n";
