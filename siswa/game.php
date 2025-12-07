<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pilihan Game - MathMagic</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6fb;
    display: flex;
}

/* AGAR KONTEN TIDAK TERTUTUP SIDEBAR */
.main-content {
    margin-left: 200px; /* lebar sidebar_siswa */
    padding: 40px;
    width: calc(100% - 260px);
}
.main-content h1 {
    margin: 0;
    font-size: 28px;
    color: #4b5ef7;
    text-shadow: 0 0 10px rgba(0,0,0,0.2);
}

/* GRID GAME */
.games-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin-top: 30px;
}

.game-card {
    background: #fff;
    padding: 25px;
    text-align: center;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: .2s;
}
.game-card:hover {
    transform: translateY(-5px);
}

.game-card h3 {
    color: #4C8BF5;
    margin-bottom: 8px;
}

.game-card p {
    font-size: 14px;
    color: #555;
}

.game-card a {
    display: inline-block;
    margin-top: 12px;
    padding: 10px 20px;
    border-radius: 8px;
    background: #4C8BF5;
    color: white;
    text-decoration: none;
}
</style>
</head>

<body>

<!-- LOAD SIDEBAR -->
<?php include "../components/sidebar_siswa.php"; ?>

<!-- KONTEN UTAMA -->
<div class="main-content">

    <h1 style="font-size: 28px; color: #4b5ef7;">🎮 Pilih Game Edukasi</h1>

    <div class="games-grid">

        <div class="game-card">
            <h3>Quiz Cepat</h3>
            <p>Jawab soal matematika secepat mungkin!</p>
            <a href="../components/game/quiz_cepat.php">Mainkan</a>
        </div>

        <div class="game-card">
            <h3>Puzzle Angka</h3>
            <p>Pecahkan puzzle matematika berbasis logika.</p>
            <a href="../components/game/puzzle_angka.php">Mainkan</a>
        </div>

        <div class="game-card">
            <h3>Adventure Game</h3>
            <p>Jelajahi dunia MathMagic dan selesaikan misinya.</p>
            <a href="../components/game/adventure.php">Mainkan</a>
        </div>

        <div class="game-card">
            <h3>Math Race</h3>
            <p>Berlomba menjawab soal matematika tercepat.</p>
            <a href="../components/game/math_race.php">Mainkan</a>
        </div>

    </div>

</div>

</body>
</html>