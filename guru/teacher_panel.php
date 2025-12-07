<?php
session_start();
require '../config.php';
include '../components/sidebar_guru.php';

// ===============================
// CEK LOGIN GURU
// ===============================
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../login.php");
    exit;
}

$guru_id = $_SESSION['user_id'];
$guru_name = $_SESSION['fullname'];

// ===============================
// AMBIL DATA MAPEL GURU
// ===============================
$mapel_guru = $conn->query("SELECT mapel FROM users WHERE id=$guru_id")->fetch_assoc()['mapel'];

// ===============================
// TOTAL SISWA BERDASARKAN MAPEL GURU
// ===============================
$total_siswa = $conn->query("
    SELECT COUNT(*) AS total 
    FROM users
    WHERE role='siswa'
    AND mapel = '$mapel_guru'
")->fetch_assoc()['total'];

// ===============================
// TOTAL KELAS UNIK
// ===============================
$total_kelas = $conn->query("
    SELECT COUNT(DISTINCT kelas) AS total
    FROM users
    WHERE role='siswa'
    AND mapel='$mapel_guru'
")->fetch_assoc()['total'];

// ===============================
// TOTAL SOAL GURU
// ===============================
$total_soal = $conn->query("
    SELECT COUNT(*) AS total
    FROM question_bank
    WHERE created_by=$guru_id
")->fetch_assoc()['total'];

// ===============================
// RATA-RATA NILAI SISWA
// ===============================
$avg_score = $conn->query("
    SELECT AVG(q.score) AS avg_score
    FROM quiz_results q
    JOIN users u ON u.id = q.student_id
    WHERE u.role='siswa'
    AND u.mapel='$mapel_guru'
")->fetch_assoc()['avg_score'];

$avg_score = $avg_score ? number_format($avg_score, 2) : "0";

// ===============================
// KELAS DENGAN PERFORMA TERBAIK
// ===============================
$kelas_best = $conn->query("
    SELECT u.kelas, AVG(q.score) AS avg_score
    FROM users u
    LEFT JOIN quiz_results q ON q.student_id = u.id
    WHERE u.role = 'siswa'
    AND u.mapel = '$mapel_guru'
    GROUP BY u.kelas
    HAVING u.kelas IS NOT NULL AND u.kelas != ''
    ORDER BY avg_score DESC
    LIMIT 1
")->fetch_assoc();

// ===============================
// AKTIVITAS SISWA TERBARU
// ===============================
$aktivitas = $conn->query("
    SELECT u.fullname, a.activity, a.created_at
    FROM student_activity a
    JOIN users u ON u.id = a.student_id
    WHERE u.role='siswa'
    AND u.mapel='$mapel_guru'
    ORDER BY a.created_at DESC
    LIMIT 10
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Guru</title>
<style>
body {
    padding: 20px; 
    background: #eef2f7;
}

/* Judul */
h2 {
    margin-bottom: 10px;
    color: #2c3e50;
}

/* Container Kartu Ringkasan */
.card-container {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

/* Kartu Ringkasan */
.card {
    background: #ffffff;
    padding: 20px; 
    border-radius: 12px;
    width: 23%; 
    box-shadow: 0 4px 8px rgba(0,0,0,0.12);
    transition: 0.15s;
    border: 1px solid #dce3ea;
}

/* Hover Kartu */
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 14px rgba(0,0,0,0.18);
}

/* Judul dalam Kartu */
.card h3 {
    margin: 0;
    font-size: 17px;
    color: #34495e;
}

/* Angka Utama */
.card p {
    font-size: 30px;
    margin: 12px 0 0;
    font-weight: bold;
    color: #2c3e50;
}

/* Box Tabel */
.table-box {
    background: #ffffff;
    padding: 20px; 
    border-radius: 12px;
    margin-top: 20px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.12);
    border: 1px solid #dce3ea;
}

.table-box h3 {
    color: #2c3e50;
}

/* Tabel */
table {
    width: 100%; 
    border-collapse: collapse; 
    margin-top: 12px;
}

/* Header Tabel */
th {
    padding: 10px;
    border: 1px solid #d0d7df;
    background: #3498db;
    color: white;
    text-align: left;
}

/* Sel Tabel */
td {
    padding: 10px;
    border: 1px solid #d0d7df;
    color: #2c3e50;
}

/* Striping */
tr:nth-child(even) {
    background: #f7f9fc;
}

/* Tombol */
.btn {
    display: inline-block;
    padding: 8px 14px;
    background: #3498db;
    color: white; 
    text-decoration: none;
    border-radius: 6px;
    margin-right: 10px;
    font-weight: 600;
}

.btn-green { background: #27ae60; }
.btn-yellow { background: #f1c40f; color: #333; }
.btn-red { background: #e74c3c; }

</style>
</head>

<body>

<h2>Selamat datang, <?= $guru_name; ?> 👨‍🏫</h2>

<!-- RINGKASAN -->
<div class="card-container">
    <div class="card">
        <h3>Total Siswa</h3>
        <p><?= $total_siswa; ?></p>
    </div>

    <div class="card">
        <h3>Kelas Diampu</h3>
        <p><?= $total_kelas; ?></p>
    </div>

    <div class="card">
        <h3>Bank Soal Guru</h3>
        <p><?= $total_soal; ?></p>
    </div>

    <div class="card">
        <h3>Rata-rata Nilai</h3>
        <p><?= $avg_score; ?></p>
    </div>
</div>

<!-- KELAS TERBAIK -->
<div class="table-box">
    <h3>Kelas Dengan Performa Terbaik</h3>
    <?php if ($kelas_best): ?>
        <p><b><?= $kelas_best['kelas']; ?></b> — Rata-rata nilai: 
        <?= number_format($kelas_best['avg_score'], 2); ?></p>
    <?php else: ?>
        <p>Belum ada data nilai.</p>
    <?php endif; ?>
</div>


</body>
</html>
