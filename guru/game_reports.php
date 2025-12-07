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

// Ambil mapel guru
$mapel = $conn->query("SELECT mapel FROM users WHERE id=$guru_id")->fetch_assoc()['mapel'];

// ===============================
// QUERY LAPORAN GAME
// ===============================
$report = $conn->query("
    SELECT 
        u.id,
        u.fullname,
        u.kelas,
        COALESCE(SUM(gr.points),0) AS total_points,
        COUNT(gr.id) AS total_games,
        MAX(gr.created_at) AS last_played,
        (
            SELECT gr2.game_name
            FROM game_results gr2
            WHERE gr2.student_id = u.id
            ORDER BY gr2.created_at DESC
            LIMIT 1
        ) AS last_game
    FROM users u
    LEFT JOIN game_results gr ON gr.student_id = u.id
    WHERE u.role='siswa' AND u.mapel='$mapel'
    GROUP BY u.id, u.fullname, u.kelas
    ORDER BY total_points DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Game Siswa</title>
<style>
    body {
        background: #f7f7ff;  /* Putih sedikit keunguan */
        padding: 25px 35px;
        color: #333;
    }

    h2 {
        font-size: 26px;
        font-weight: 700;
        color: #4a3aff; /* Ungu terang */
        border-left: 6px solid #4a3aff;
        padding-left: 12px;
        margin-bottom: 8px;
    }

    p {
        font-size: 15px;
        margin-bottom: 20px;
        color: #444;
    }

    p b {
        color: #000;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        margin-top: 15px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    th {
        background: #4a3aff; /* Header ungu */
        color: #fff;
        padding: 12px;
        font-size: 15px;
        text-align: center;
        font-weight: 600;
    }

    td {
        padding: 11px;
        font-size: 14px;
        border-bottom: 1px solid #e5e5e5;
        text-align: center;
        color: #333;
    }

    tr:hover td {
        background: #f2f0ff; /* Ungu lembut */
    }

    /* Badge poin */
    .badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        color: #fff;
        display: inline-block;
    }

    .best {
        background: #6c5ce7; /* Ungu vibrant */
    }

    .medium {
        background: #ffca28; /* Kuning */
        color: #000;
    }

    .low {
        background: #e74c3c; /* Merah */
    }

    /* Jika tidak ada data */
    td[colspan] {
        padding: 18px !important;
        font-size: 15px;
        color: #666;
        background: #fafafa;
    }
</style>
</head>
<body>

<h2>📊 Laporan Game — <?= htmlspecialchars($guru_name); ?></h2>
<p>Mapel: <b><?= htmlspecialchars($mapel); ?></b></p>

<table>
<tr>
    <th>Ranking</th>
    <th>Nama Siswa</th>
    <th>Kelas</th>
    <th>Total Poin</th>
    <th>Total Game</th>
    <th>Game Terakhir</th>
    <th>Waktu Bermain Terakhir</th>
</tr>

<?php 
$rank = 1;
if ($report && $report->num_rows > 0):
    while ($r = $report->fetch_assoc()): 

        // Badge warna berdasarkan poin
        $badge = "low";
        if ($r['total_points'] >= 500) $badge = "best";
        else if ($r['total_points'] >= 200) $badge = "medium";
?>
<tr>
    <td><?= $rank++; ?></td>
    <td><?= htmlspecialchars($r['fullname']); ?></td>
    <td><?= htmlspecialchars($r['kelas']); ?></td>
    <td><span class="badge <?= $badge ?>"><?= $r['total_points']; ?></span></td>
    <td><?= $r['total_games']; ?></td>
    <td><?= $r['last_game'] ? htmlspecialchars($r['last_game']) : '-' ?></td>
    <td><?= $r['last_played'] ? htmlspecialchars($r['last_played']) : '-' ?></td>
</tr>
<?php 
    endwhile;
else:
?>
<tr><td colspan="7" style="text-align:center;">Belum ada data game siswa</td></tr>
<?php endif; ?>
</table>

</body>
</html>