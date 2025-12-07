<?php
session_start();
require "../config.php";
include "../components/sidebar_guru.php";

// Cek login & role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../public/index.php");
    exit;
}

$guru_id = $_SESSION['user_id'];
$guru_name = $_SESSION['fullname'];

// ======================================================
// 1. TOTAL SISWA DI KELAS YANG DIAMPU GURU INI
// ======================================================
$q_total_siswa = $conn->query("
    SELECT COUNT(*) AS total
    FROM users 
    WHERE role = 'siswa'
");
$total_siswa = ($q_total_siswa->num_rows > 0) 
    ? $q_total_siswa->fetch_assoc()['total']
    : 0;

// ======================================================
// 2. KELAS YANG DIAMPU
// ======================================================
$q_kelas = $conn->query("
    SELECT DISTINCT kelas 
    FROM users 
    WHERE role = 'siswa' AND kelas IS NOT NULL
");
$kelas_diampu = [];
while ($row = $q_kelas->fetch_assoc()) {
    $kelas_diampu[] = $row['kelas'];
}

// ======================================================
// 3. JUMLAH BANK SOAL YANG DIBUAT GURU
// ======================================================
$q_bank_soal = $conn->query("
    SELECT COUNT(*) AS total
    FROM question_bank
    WHERE created_by = $guru_id
");
$total_bank_soal = $q_bank_soal->fetch_assoc()['total'] ?? 0;

// ======================================================
// 4. RATA-RATA NILAI SEMUA SISWA
// ======================================================
$q_avg_score = $conn->query("
    SELECT AVG(avg_score) AS avg
    FROM student_stats
");
$avg_siswa = number_format($q_avg_score->fetch_assoc()['avg'] ?? 0, 2);

// ======================================================
// 5. AKTIVITAS SISWA HARI INI
// ======================================================
$q_aktivitas = $conn->query("
    SELECT u.fullname, ss.last_activity 
    FROM student_stats ss
    JOIN users u ON u.id = ss.student_id
    WHERE DATE(ss.last_activity) = CURDATE()
    ORDER BY ss.last_activity DESC
");
$aktivitas = [];
while ($row = $q_aktivitas->fetch_assoc()) {
    $aktivitas[] = $row;
}

// ======================================================
// 6. KELAS DENGAN PERFORMA TERBAIK
// ======================================================
$q_best_kelas = $conn->query("
    SELECT u.kelas, AVG(ss.avg_score) AS rata
    FROM student_stats ss
    JOIN users u ON u.id = ss.student_id
    GROUP BY u.kelas
    HAVING u.kelas IS NOT NULL
    ORDER BY rata DESC
    LIMIT 1
");

$kelas_terbaik = "Belum ada data";
if ($q_best_kelas->num_rows > 0) {
    $row = $q_best_kelas->fetch_assoc();
    $kelas_terbaik = "Kelas " . $row['kelas'] . " — Rata-rata: " . number_format($row['rata'], 2);
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Guru - MATHMAGIC</title>
<style>
/* ===== RESET & BODY ===== */
body {
    margin: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    color: #333;
    min-height: 100vh;
    padding: 30px;
}

/* ===== CONTAINER ===== */
.container {
    width: 92%;
    margin: auto;
}

/* ===== HEADER ===== */
h1 {
    color: #fff;
    text-align: center;
    margin-bottom: 10px;
    font-size: 32px;
    text-shadow: 0 0 15px rgba(0,0,0,0.3);
}

.container > p {
    text-align: center;
    color: #f0f0f0;
    margin-bottom: 35px;
    font-size: 16px;
}

/* ===== GRID - CARD LAYOUT ===== */
.grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
}

/* ===== CARD ===== */
.card {
    background: rgba(255,255,255,0.95);
    padding: 25px 20px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transition: transform 0.3s, box-shadow 0.3s;
    text-align: center;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.25);
}

.card h3 {
    margin-top: 0;
    font-size: 18px;
    color: #4b5ef7;
    margin-bottom: 12px;
}

.big {
    font-size: 38px;
    font-weight: bold;
    color: #333;
}

/* ===== BOX (Aktivitas & Performa Terbaik) ===== */
.box {
    background: rgba(255,255,255,0.95);
    padding: 25px 20px;
    border-radius: 16px;
    margin-top: 35px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transition: transform 0.3s, box-shadow 0.3s;
}
.box:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.25);
}

.box h2 {
    margin-top: 0;
    color: #4b5ef7;
    margin-bottom: 15px;
}

/* ===== BADGES (jika dipakai nanti) ===== */
.badge {
    display: inline-block;
    padding: 12px 16px;
    background: linear-gradient(45deg, #ffd700, #ffed4e);
    color: #333;
    border-radius: 12px;
    margin: 5px;
    font-size: 14px;
    text-align: center;
    transition: transform 0.3s;
}
.badge:hover {
    transform: scale(1.05);
}

/* ===== LISTS ===== */
.box ul {
    padding-left: 20px;
}
.box li {
    margin-bottom: 10px;
}

/* ===== ALERT BOX (opsional) ===== */
.alert {
    padding: 12px 15px;
    border-radius: 10px;
    margin-bottom: 15px;
    font-weight: bold;
}
.alert.success { background: #2ecc71; color: white; }
.alert.warning { background: #f1c40f; color: white; }
.alert.error   { background: #e74c3c; color: white; }

/* ===== BUTTON (opsional) ===== */
.btn {
    padding: 12px 20px;
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    text-decoration: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}
.btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}

/* ===== RESPONSIVE ===== */
@media(max-width: 900px) {
    .grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media(max-width: 600px) {
    .grid {
        grid-template-columns: 1fr;
    }
    body { padding: 20px; }
    .card, .box { padding: 20px; }
}
</style>
</head>

<body>
<div class="container">

<h1>Halo, <?= $guru_name ?> 👋</h1>
<p>Selamat datang di Dashboard Guru MATHMAGIC!</p>

<!-- ======================== -->
<!-- STATISTIK UTAMA -->
<!-- ======================== -->
<div class="grid">

    <div class="card">
        <h3>Total Siswa</h3>
        <div class="big"><?= $total_siswa ?></div>
    </div>

    <div class="card">
        <h3>Kelas yang Diampu</h3>
        <div><strong><?= implode(", ", $kelas_diampu) ?></strong></div>
    </div>

    <div class="card">
        <h3>Bank Soal Dibuat</h3>
        <div class="big"><?= $total_bank_soal ?></div>
    </div>

    <div class="card">
        <h3>Rata-rata Nilai Siswa</h3>
        <div class="big"><?= $avg_siswa ?></div>
    </div>

</div>

<!-- ======================== -->
<!-- AKTIVITAS SISWA HARI INI -->
<!-- ======================== -->
<div class="box">
    <h2>📌 Aktivitas Siswa Hari Ini</h2>

    <?php if (count($aktivitas) == 0): ?>
        <i>Tidak ada aktivitas hari ini.</i>
    <?php else: ?>
        <ul>
        <?php foreach ($aktivitas as $a): ?>
            <li>
                <strong><?= $a['fullname'] ?></strong> — 
                <?= date("H:i", strtotime($a['last_activity'])) ?>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<!-- ======================== -->
<!-- KELAS DENGAN PERFORMA TERBAIK -->
<!-- ======================== -->
<div class="box">
    <h2>🏆 Kelas Performa Terbaik</h2>
    <p><?= $kelas_terbaik ?></p>
</div>
</div>
</body>
</html>