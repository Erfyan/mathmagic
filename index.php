<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>MATHMAGIC - Platform Pembelajaran Matematika</title>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
    }

    body {
        background: linear-gradient(135deg, #6a77ff, #c77cff);
        min-height: 100vh;
        color: white;
        overflow-x: hidden;
    }

    .hero {
        text-align: center;
        padding: 60px 20px;
    }

    h1 {
        font-size: 58px;
        font-weight: 700;
        letter-spacing: 2px;
        text-shadow: 0 0 20px rgba(255,255,255,0.6);
        margin-bottom: 10px;
    }

    h2 {
        font-size: 22px;
        font-weight: 400;
        margin-top: 10px;
        margin-bottom: 20px;
    }

    .subtitle {
        font-size: 17px;
        opacity: .9;
        margin-bottom: 6px;
    }

    /* Container Cards */
    .features {
        width: 100%;
        max-width: 1100px;
        margin: 40px auto;
        display: flex;
        justify-content: center;
        gap: 25px;
        flex-wrap: wrap;
    }

    .card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(12px);
        width: 300px;
        padding: 35px 25px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        transition: 0.25s;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.25);
    }

    .card h3 {
        font-size: 24px;
        font-weight: 600;
        margin-top: 10px;
    }

    .card p {
        margin-top: 10px;
        opacity: .85;
        line-height: 1.6;
    }

    /* Tombol di card */
    .btn {
        display: inline-block;
        margin-top: 15px;
        padding: 8px 16px;
        background: rgba(255,255,255,0.2);
        border-radius: 30px;
        color: white;
        font-size: 14px;
        text-decoration: none;
        backdrop-filter: blur(5px);
        transition: 0.25s;
    }

    .btn:hover {
        background: rgba(255,255,255,0.35);
    }
</style>
</head>
<body>

<div class="hero">
    <h1>∑ MATHMAGIC</h1>
    <h2>Platform Pembelajaran Matematika Interaktif</h2>

    <p class="subtitle">✨ Belajar matematika dengan cara yang menyenangkan melalui game, quiz, dan tantangan interaktif!</p>
    <p class="subtitle">🚀 Raih prestasi terbaik dengan sistem gamifikasi yang seru!</p>
</div>

<div class="features">

    <!-- CARD 1 -->
    <div class="card">
        <div style="font-size:45px;">🎮</div>
        <h3>Game Interaktif</h3>
        <p>Quiz, puzzle, dan adventure game matematika yang seru dan menantang</p>

        <button class="btn">∑ Quiz</button>
        <button class="btn">🧩 Puzzle</button>
    </div>

    <!-- CARD 2 -->
    <div class="card">
        <div style="font-size:45px;">🏆</div>
        <h3>Sistem Ranking</h3>
        <p>Kompetisi sehat dengan teman sekelas dan raih prestasi terbaik</p>

        <button class="btn">📊 Leaderboard</button>
        <button class="btn">⭐ Badge</button>
    </div>

    <!-- CARD 3 -->
    <div class="card">
        <div style="font-size:45px;">📚</div>
        <h3>Bank Soal Lengkap</h3>
        <p>Materi lengkap dari SD hingga SMA dengan berbagai tingkat kesulitan</p>

        <button class="btn">📘 SD-SMA</button>
        <button class="btn">⚙️ Adaptif</button>
    </div>

</div>

<!-- Tombol Mulai Tengah Halaman -->
<div style="text-align:center; margin:50px 0;">
    <a href="public/login.php" class="btn" style="font-size:20px; padding:15px 40px;">Mulai</a>
</div>

</body>
</html>