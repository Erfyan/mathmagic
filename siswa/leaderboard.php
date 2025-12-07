<?php 
require_once "../config.php";
require_once "../init.php";
include "../components/sidebar_siswa.php";

// ==========================
// CEK LOGIN & ROLE
// ==========================
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/index.php");
    exit;
}

$student_id = (int) $_SESSION['user_id'];

// ==========================
// AMBIL DATA USER LOGIN
// ==========================
$stmt = $conn->prepare("SELECT fullname, kelas, avatar FROM users WHERE id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// ==========================
// LEADERBOARD (TOP 10)
// SUM SKOR DARI student_score_history
// ==========================
$leaderboardQuery = $conn->query("
    SELECT 
        u.id,
        u.fullname,
        u.kelas,
        COALESCE(SUM(h.score), 0) AS total_points
    FROM users u
    LEFT JOIN student_score_history h ON h.student_id = u.id
    WHERE u.role = 'siswa'
    GROUP BY u.id, u.fullname, u.kelas
    ORDER BY total_points DESC
    LIMIT 10
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Leaderboard Siswa - MathMagic</title>

<style>
body {
    margin: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    color: #333;
    min-height: 100vh;
}

.container {
    padding: 30px;
}

h2 {
    text-align: center;
    color: #4b5ef7;
    margin-bottom: 5px;
    text-shadow: 0 0 5px rgba(0,0,0,0.3);
}

p {
    text-align: center;
    margin-bottom: 20px;
    color: #4b5ef7;
    text-shadow: 0 0 3px rgba(0,0,0,0.3);
}

.dashboard-box {
    background: rgba(255,255,255,0.95);
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    max-width: 900px;
    margin: 0 auto 30px auto;
}

.dashboard-box h3 {
    margin-top: 0;
    color: #4b5ef7;
}

.leaderboard-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.leaderboard-table th,
.leaderboard-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.leaderboard-table th {
    background: #4b5ef7;
    color: white;
    border-radius: 6px;
}

.leaderboard-table tr:hover {
    background: #e0ebff;
    transform: translateX(2px);
    transition: 0.2s;
}

.highlight {
    background: #ffeaa7 !important;
}

@media(max-width:768px) {
    .container { margin-left: 0; padding: 20px; }
    .leaderboard-table th, .leaderboard-table td { padding: 8px; font-size: 14px; }
}
</style>
</head>
<body>

<div class="container">
<h2>Leaderboard</h2>
<p>Tempat melihat rank kamu 👌</p>

<div class="dashboard-box">
    <h3>Top 10 Siswa</h3>

    <table class="leaderboard-table">
        <tr>
            <th>Posisi</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Total Poin</th>
        </tr>

        <?php 
        if ($leaderboardQuery && $leaderboardQuery->num_rows > 0) {
            $pos = 1;
            while ($row = $leaderboardQuery->fetch_assoc()):
                $highlight = ($row['id'] == $student_id) ? 'highlight' : '';
        ?>
        
        <tr class="<?= $highlight ?>">
            <td><?= $pos ?></td>
            <td><?= htmlspecialchars($row['fullname']) ?></td>
            <td><?= htmlspecialchars($row['kelas']) ?></td>
            <td><?= $row['total_points'] ?></td>
        </tr>

        <?php 
            $pos++;
            endwhile;
        } else { 
        ?>
        <tr><td colspan="4">Belum ada data siswa.</td></tr>
        <?php } ?>

    </table>
</div>

</div>

</body>
</html>