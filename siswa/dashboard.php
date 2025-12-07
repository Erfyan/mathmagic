<?php
session_start();
require_once "../config.php";
require_once "../libs/seed_data_dummy.php";  // ⬅ auto seed jika belum ada data
include "../components/sidebar_siswa.php";
// =======================
// CEK LOGIN
// =======================
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/index.php");
    exit;
}

$student_id = $_SESSION['user_id'];

// =======================
// AMBIL DATA SISWA
// =======================
$stmt = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("User tidak ditemukan.");
}

$user = $res->fetch_assoc();
$fullname = $user['fullname'];

// =======================
// AMBIL BADGES (FINAL FIX)
// =======================
$badge_stmt = $conn->prepare("
    SELECT badge_key, title, description 
    FROM badges 
    WHERE student_id = ?
");
$badge_stmt->bind_param("i", $student_id);
$badge_stmt->execute();
$badges = $badge_stmt->get_result();

// =======================
// LEVEL BERDASARKAN LEADERBOARD
// =======================

$level_q = $conn->prepare("
    SELECT 
        COALESCE(SUM(score), 0) AS total_score
    FROM student_score_history
    WHERE student_id = ?
");
$level_q->bind_param("i", $student_id);
$level_q->execute();
$level_res = $level_q->get_result()->fetch_assoc();

$total_score = $level_res['total_score'];
$level = floor($total_score / 100);   // 100 poin = kenaikan 1 level
if ($level < 1) $level = 1;           // minimal level 1

// =======================
// AMBIL TOPIK LEMAH
// =======================
$weak_topics = $conn->query("
    SELECT question, wrong_count
    FROM student_weak_topics
    WHERE student_id = $student_id
");

?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Dashboard Siswa - MathMagic</title>
<style>
/* ===== RESET & BODY ===== */
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    color: #333;
    min-height: 100vh;
    padding: 30px;
}

/* ===== HEADER ===== */
h1 {
    color: #fff;
    text-align: center;
    margin-bottom: 30px;
    font-size: 32px;
    text-shadow: 0 0 15px rgba(0,0,0,0.3);
}

/* ===== CARDS ===== */
.card {
    background: rgba(255,255,255,0.95);
    padding: 25px 20px;
    border-radius: 16px;
    margin-bottom: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    transition: transform 0.3s, box-shadow 0.3s;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
}

/* ===== BADGES ===== */
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
.card ul {
    padding-left: 20px;
}
.card li {
    margin-bottom: 10px;
}

/* ===== HEADINGS INSIDE CARD ===== */
.card h2 {
    margin-top: 0;
    color: #4b5ef7;
    margin-bottom: 15px;
}

/* ===== ALERT / INFO BOXES ===== */
.alert {
    padding: 12px 15px;
    border-radius: 10px;
    margin-bottom: 15px;
    font-weight: bold;
}
.alert.success { background: #2ecc71; color: white; }
.alert.warning { background: #f1c40f; color: white; }
.alert.error { background: #e74c3c; color: white; }

/* ===== RESPONSIVE ===== */
@media(max-width:768px) {
    body { padding: 20px; }
    .card { padding: 20px; }
}
/* Tambahan untuk tombol */
.quiz-btn {
    display: inline-block;
    padding: 12px 25px;
    margin-top: 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: bold;
    border-radius: 10px;
    text-decoration: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

.quiz-btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}
</style>
</head>
<body>

<h1>Selamat Datang, <?= htmlspecialchars($fullname) ?> 👋</h1>

<div class="card">
    <h2>🎖 Lencana yang Kamu Dapatkan</h2>
    <?php if ($badges->num_rows > 0): ?>
        <?php while ($b = $badges->fetch_assoc()): ?>
            <div class="badge">
                <strong><?= $b['title'] ?></strong><br>
                <small><?= $b['description'] ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Belum ada badge.</p>
    <?php endif; ?>
</div>

<div class="card">
    <h2>📚 Level Kamu</h2>
    <p style="font-size:20px;">
        Total Score: <strong><?= $total_score ?></strong><br>
        Level: <strong style="color:#4b5ef7;">Level <?= $level ?></strong>
    </p>

    <p>
        Level dihitung berdasarkan total skor kamu di leaderboard.
        Semakin sering kamu mendapat nilai tinggi, level kamu akan naik.
    </p>
</div>


<div class="card">
    <h2>⚠ Topik yang Perlu Diperbaiki</h2>
    <?php if ($weak_topics->num_rows > 0): ?>
        <ul>
            <?php while ($wt = $weak_topics->fetch_assoc()): ?>
                <li>
                    <?= $wt['question'] ?> — salah <strong><?= $wt['wrong_count'] ?></strong> kali
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Tidak ada data topik lemah.</p>
    <?php endif; ?>
</div>
<div class="dashboard-box">
    <h3>📚 Quiz Terbaru</h3>
    <p>Uji kemampuanmu dengan quiz terbaru dan tingkatkan levelmu!</p>
    
    <a href="quiz.php" class="quiz-btn">🎯 Mulai Quiz Sekarang</a>
</div>
</body>
</html>
