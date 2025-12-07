<?php
session_start();
require '../../config.php';  // sesuaikan path
require_once '../../init.php';

// Cek login guru
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    die("Akses ditolak.");
}

$guru_id = $_SESSION['user_id'];

// Ambil mapel guru
$mapel_guru = $conn->query("SELECT mapel FROM users WHERE id=$guru_id")->fetch_assoc()['mapel'];

// Ambil data siswa + performa
$students = $conn->query("
    SELECT u.id, u.fullname, u.kelas, IFNULL(s.total_points,0) AS total_points, 
           IFNULL(s.total_quiz_taken,0) AS total_quiz_taken, u.last_login
    FROM users u
    LEFT JOIN student_stats s ON s.student_id = u.id
    WHERE u.role='siswa' AND u.mapel='$mapel_guru'
    ORDER BY u.kelas, u.fullname
");

// Ambil statistik bank soal
$bankSoal = $conn->query("
    SELECT difficulty, COUNT(*) AS total
    FROM question_bank
    GROUP BY difficulty
");

// Ambil performa quiz
$quizStats = $conn->query("
    SELECT ql.title, ql.difficulty, COUNT(qr.quiz_title) AS total_attempts,
           IFNULL(AVG(qr.score),0) AS avg_score
    FROM quiz_list ql
    LEFT JOIN quiz_results qr ON qr.quiz_title = ql.title
    GROUP BY ql.title, ql.difficulty
    ORDER BY ql.created_at DESC
");

// Header CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=laporan_siswa.csv');
$output = fopen('php://output', 'w');

// Bagian 1 — Statistik Siswa
fputcsv($output, ["ID Siswa","Nama","Kelas","Total Poin","Total Quiz","Login Terakhir"]);
while($row = $students->fetch_assoc()) {
    fputcsv($output, [
        $row['id'], $row['fullname'], $row['kelas'], 
        $row['total_points'], $row['total_quiz_taken'], $row['last_login']
    ]);
}
fputcsv($output, []); // baris kosong

// Bagian 2 — Bank Soal
fputcsv($output, ["Bank Soal","Difficulty","Total Soal"]);
while($row = $bankSoal->fetch_assoc()) {
    fputcsv($output, ["", $row['difficulty'], $row['total']]);
}
fputcsv($output, []);

// Bagian 3 — Performa Quiz
fputcsv($output, ["Performa Quiz","Difficulty","Total Pengerjaan","Rata-Rata Skor"]);
while($row = $quizStats->fetch_assoc()) {
    fputcsv($output, [$row['title'], $row['difficulty'], $row['total_attempts'], number_format($row['avg_score'],2)]);
}

fclose($output);
exit;
