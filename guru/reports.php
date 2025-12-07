<?php
session_start();
require '../config.php';
include '../components/sidebar_guru.php';

/* ======================================================
   VALIDASI AKSES GURU
====================================================== */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../public/login.php");
    exit;
}

$teacher_id = $_SESSION['user_id'];

/* ======================================================
   AMBIL DATA PROFIL GURU
====================================================== */
$qTeacher = $conn->prepare("
    SELECT fullname, mapel 
    FROM users 
    WHERE id=?
");
$qTeacher->bind_param("i", $teacher_id);
$qTeacher->execute();
$teacher = $qTeacher->get_result()->fetch_assoc();
$qTeacher->close();

/* ======================================================
   STATISTIK UMUM SISWA
====================================================== */
$stats = $conn->query("
    SELECT 
        COUNT(*) AS total_students,
        AVG(total_points) AS avg_points,
        MAX(total_points) AS max_points,
        MIN(total_points) AS min_points
    FROM student_stats
")->fetch_assoc();

/* ======================================================
   20 SISWA DENGAN NILAI TERTINGGI
====================================================== */
$ranking = $conn->query("
    SELECT 
        u.fullname,
        u.kelas,
        s.total_points,
        s.total_quiz_taken
    FROM student_stats s
    JOIN users u ON u.id = s.student_id
    WHERE u.role='siswa'
    ORDER BY s.total_points DESC
    LIMIT 20
");

/* ======================================================
   RIWAYAT LOGIN SISWA
====================================================== */
$loginLogs = $conn->query("
    SELECT fullname, last_login AS login_time
    FROM users
    WHERE role='siswa'
    ORDER BY last_login DESC
    LIMIT 20
");

/* ======================================================
   PERFORMA QUIZ
====================================================== */
$quizStats = $conn->query("
    SELECT 
        ql.title,
        ql.difficulty,
        COUNT(qr.id) AS total_attempts,
        IFNULL(AVG(qr.score), 0) AS avg_score
    FROM quiz_list ql
    LEFT JOIN quiz_results qr ON qr.quiz_title = ql.title
    GROUP BY ql.title, ql.difficulty
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Guru - Laporan Siswa</title>

<style>
body {
    margin:0;
    background:linear-gradient(135deg,#667eea,#764ba2,#f093fb);
    color:black;
}
.container { padding:35px; }
.page-title { font-size:32px; font-weight:bold; }
.card {
    background:rgba(255,255,255,0.92);
    padding:25px;
    margin-bottom:30px;
    border-radius:18px;
    color:#333;
    box-shadow:0 10px 25px rgba(0,0,0,0.25);
}
.card h2 { margin-top:0; color:#4b5ef7; }
table { width:100%; border-collapse:collapse; margin-top:12px; }
th {
    background:#4b5ef7; 
    color:white; 
    padding:10px; 
}
td {
    padding:9px;
    background:#f9f9f9;
    border-bottom:1px solid #ddd;
}
tr:hover td { background:#eef1ff; }

.download-btn {
    background:#28a745;
    padding:12px 20px;
    color:white;
    border-radius:10px;
    cursor:pointer;
    border:none;
}
.download-btn:hover {
    background:#1c6f30;
}
</style>

</head>
<body>

<?php include '../components/sidebar_guru.php'; ?>

<div class="container">

    <div class="page-title">Dashboard Guru</div>
    <p>Akun Guru: <b><?= $teacher['fullname']; ?></b> • Mapel: <b><?= $teacher['mapel']; ?></b></p>

    <!-- ========================================= -->
    <!-- 1. STATISTIK UMUM SISWA -->
    <!-- ========================================= -->
    <div class="card">
        <h2>Statistik Umum Siswa</h2>
        <table>
            <tr><th>Total Siswa</th><td><?= $stats['total_students']; ?></td></tr>
            <tr><th>Rata-Rata Poin</th><td><?= number_format($stats['avg_points'],2); ?></td></tr>
            <tr><th>Poin Tertinggi</th><td><?= $stats['max_points']; ?></td></tr>
            <tr><th>Poin Terendah</th><td><?= $stats['min_points']; ?></td></tr>
        </table>
    </div>

    <!-- ========================================= -->
    <!-- 2. STATISTIK PER KELAS -->
    <!-- ========================================= -->
    <div class="card">
        <h2>Statistik Per Kelas</h2>
        <table>
            <tr>
                <th>Kelas</th>
                <th>Jumlah Siswa</th>
                <th>Rata-Rata Poin</th>
            </tr>

            <?php
            $kelasStats = $conn->query("
                SELECT 
                    u.kelas,
                    COUNT(*) AS jumlah_siswa,
                    AVG(s.total_points) AS rata_poin
                FROM student_stats s
                JOIN users u ON u.id = s.student_id
                WHERE u.role='siswa'
                GROUP BY u.kelas
            ");

            if ($kelasStats->num_rows > 0):
                while($row = $kelasStats->fetch_assoc()):
            ?>
            <tr>
                <td><?= $row['kelas']; ?></td>
                <td><?= $row['jumlah_siswa']; ?></td>
                <td><?= number_format($row['rata_poin'],2); ?></td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="3">Belum ada data</td></tr>
            <?php endif; ?>
        </table>
    </div>

    <!-- ========================================= -->
    <!-- 3. RANKING TOP 20 -->
    <!-- ========================================= -->
    <div class="card">
        <h2>20 Siswa Dengan Poin Tertinggi</h2>
        <table>
            <tr>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Total Poin</th>
                <th>Total Quiz</th>
            </tr>

            <?php while($row = $ranking->fetch_assoc()): ?>
            <tr>
                <td><?= $row['fullname']; ?></td>
                <td><?= $row['kelas']; ?></td>
                <td><?= $row['total_points']; ?></td>
                <td><?= $row['total_quiz_taken']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- ========================================= -->
    <!-- 4. PERFORMA QUIZ -->
    <!-- ========================================= -->
    <div class="card">
        <h2>Performa Semua Quiz</h2>
        <table>
            <tr>
                <th>Judul Quiz</th>
                <th>Kesulitan</th>
                <th>Dikerjakan</th>
                <th>Rata Skor</th>
            </tr>

            <?php if ($quizStats->num_rows > 0): ?>
                <?php while($row = $quizStats->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['title']; ?></td>
                    <td><?= ucfirst($row['difficulty']); ?></td>
                    <td><?= $row['total_attempts']; ?></td>
                    <td><?= number_format($row['avg_score'],2); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">Belum ada data quiz</td></tr>
            <?php endif; ?>
        </table>
    </div>

    <!-- ========================================= -->
    <!-- 5. LOGIN TERBARU -->
    <!-- ========================================= -->
    <div class="card">
        <h2>Riwayat Login Siswa Terbaru</h2>
        <table>
            <tr>
                <th>Nama</th>
                <th>Waktu Login</th>
            </tr>

            <?php while($row = $loginLogs->fetch_assoc()): ?>
            <tr>
                <td><?= $row['fullname']; ?></td>
                <td><?= $row['login_time']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- DOWNLOAD REPORT -->
    <button class="download-btn" id="downloadReport">⬇ Unduh Laporan Lengkap</button>

</div>

<script>
document.getElementById('downloadReport').addEventListener('click', function() {
    fetch('../api/reports/reports_download.php')
    .then(r => r.blob())
    .then(blob => {
        let url = URL.createObjectURL(blob);
        let a = document.createElement('a');
        a.href = url;
        a.download = "laporan_siswa.csv";
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
    });
});
</script>

</body>
</html>