<?php
// pastikan session sudah ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// halaman aktif
$current = basename($_SERVER['PHP_SELF']);
?>

<style>
/* === SIDEBAR FIXED MODERN GURU === */
.sidebar-guru {
    position: fixed;
    left: 0;
    top: 0;
    width: 250px;
    height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding-top: 20px;
    box-shadow: 3px 0 15px rgba(0,0,0,0.3);
    z-index: 99;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* logo / title */
.sidebar-guru h2 {
    text-align: center;
    font-size: 26px;
    margin-bottom: 30px;
    letter-spacing: 2px;
    font-weight: bold;
    background: linear-gradient(90deg, #ffffff, #f0f8ff, #e6f3ff, #ffffff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 0 0 10px rgba(255,255,255,0.4);
}

/* menu list */
.sidebar-guru ul {
    list-style: none;
    padding: 0;
    margin: 0;
    width: 100%;
}

.sidebar-guru ul li a {
    display: flex;
    align-items: center;
    padding: 14px 20px;
    color: rgba(255,255,255,0.9);
    font-size: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.sidebar-guru ul li a:hover {
    background: rgba(255,255,255,0.1);
    transform: translateX(5px);
    color: #fff;
}

.sidebar-guru ul li a.active {
    background: rgba(255,255,255,0.2);
    font-weight: bold;
    border-left: 4px solid #ffed4e;
}

/* ruang konten */
.content-guru {
    margin-left: 260px;
    padding: 25px;
    background: #f4f4f4;
    min-height: 100vh;
    transition: margin-left 0.3s ease;
}

/* ikon di menu */
.sidebar-guru ul li a span {
    margin-left: 10px;
}

/* responsive minor */
@media screen and (max-width: 768px) {
    .sidebar-guru {
        width: 200px;
    }
    .content-guru {
        margin-left: 210px;
    }
}
</style>

<div class="sidebar-guru">

    <h2>MATHMAGIC</h2>

    <ul>
        <li><a href="dashboard.php" class="<?= $current == 'dashboard.php' ? 'active' : '' ?>">📊 Dashboard</a></li>

        <li><a href="profile.php" class="<?= $current == 'profile.php' ? 'active' : '' ?>">👤 Profil</a></li>

        <li><a href="quiz_builder.php" class="<?= $current == 'quiz_builder.php' ? 'active' : '' ?>">📝 Quiz Builder</a></li>

        <li><a href="banksoal_manage.php" class="<?= $current == 'banksoal_manage.php' ? 'active' : '' ?>">📚 Bank Soal</a></li>

        <li><a href="forum.php" class="<?= ($current =='forum.php')?'active':'' ?>">💬 Forum</a></li>

        <li><a href="reports.php" class="<?= $current == 'reports.php' ? 'active' : '' ?>">📈 Laporan Siswa</a></li>

        <li><a href="game_reports.php" class="<?= $current == 'game_reports.php' ? 'active' : '' ?>">🎮 Game Reports</a></li>

        <li><a href="leaderboard.php" class="<?= $current == 'leaderboard.php' ? 'active' : '' ?>">🏆 Leaderboard</a></li>

        <li><a href="teacher_panel.php" class="<?= $current == 'teacher_panel.php' ? 'active' : '' ?>">🧩 Panel Guru </a></li>

        <li><a href="../public/logout.php">🚪 Logout</a></li>
    </ul>

</div>

<!-- ruang konten -->
<div class="content-guru">
