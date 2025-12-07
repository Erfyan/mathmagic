<?php
session_start();
require_once "../config.php";

// ==================================
// CEK LOGIN SISWA
// ==================================
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/index.php");
    exit;
}

$student_id = (int)$_SESSION['user_id'];
$kelas_siswa = "SMP"; // default sementara

// ==================================
// Ambil subject ID
// ==================================
$subject_id = isset($_GET['subject']) ? (int)$_GET['subject'] : 0;
if ($subject_id <= 0) {
    die("<h3 style='color:white; text-align:center'>Subject tidak valid.</h3>");
}

// ==================================
// QUERY AMBIL SOAL
// ==================================
$sql = "
    SELECT id, type, question, option_a, option_b, option_c, option_d, correct_answer
    FROM question_bank 
    WHERE subject_id = $subject_id
      AND kelas = '$kelas_siswa'
      AND type = 'mcq'
    ORDER BY RAND()
    LIMIT 10
";

$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("<h3 style='color:white; text-align:center'>Tidak ada soal latihan untuk mapel ini.</h3>");
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Latihan Soal</title>

<style>
/* ===== Background Tema ===== */
body {
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 45%, #f093fb 100%);
    min-height: 100vh;
    padding-bottom: 50px;
}

/* ===== WRAPPER ===== */
.container {
    width: 90%;
    max-width: 760px;
    margin: auto;
    margin-top: 40px;
}

/* ===== TITLE ===== */
.title-box {
    text-align: center;
    background: rgba(255,255,255,0.9);
    padding: 18px;
    border-radius: 16px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.title-box h2 {
    color: #4b5ef7;
    margin: 0;
    text-shadow: 0 0 5px rgba(0,0,0,0.25);
}

.title-box p {
    color: #444;
    margin-top: 5px;
}

/* ===== CARD SOAL ===== */
.card {
    background: rgba(255,255,255,0.95);
    padding: 18px;
    border-radius: 14px;
    margin-bottom: 18px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.25);
}

.card p {
    font-size: 17px;
    margin-bottom: 12px;
}

/* ===== RADIO BUTTON ===== */
.card label {
    display: block;
    cursor: pointer;
    background: #eef0ff;
    padding: 8px 12px;
    border-radius: 8px;
    margin-bottom: 6px;
    transition: 0.2s;
}

.card label:hover {
    background: #dde0ff;
}

/* ===== BUTTON SUBMIT ===== */
.btn-submit {
    display: block;
    width: 100%;
    padding: 12px;
    margin-top: 18px;
    border: none;
    border-radius: 12px;
    background: #4b5ef7;
    color: white;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.22);
    transition: 0.3s;
}

.btn-submit:hover {
    background: #6272ff;
    transform: translateY(-3px);
}
</style>

</head>
<body>

<div class="container">

    <div class="title-box">
        <h2>Latihan Soal</h2>
        <p>Mata Pelajaran ID: <?= $subject_id ?></p>
    </div>

    <form action="latihan_process.php" method="post">
        <input type="hidden" name="subject_id" value="<?= $subject_id ?>">

        <?php 
        $no = 1;
        while ($row = $result->fetch_assoc()):
        ?>
        <div class="card">
            <p><b><?= $no++ ?>. <?= $row['question'] ?></b></p>

            <input type="hidden" name="question_id[]" value="<?= $row['id'] ?>">

            <label><input type="radio" name="answer_<?= $row['id'] ?>" value="A"> <?= $row['option_a'] ?></label>
            <label><input type="radio" name="answer_<?= $row['id'] ?>" value="B"> <?= $row['option_b'] ?></label>
            <label><input type="radio" name="answer_<?= $row['id'] ?>" value="C"> <?= $row['option_c'] ?></label>
            <label><input type="radio" name="answer_<?= $row['id'] ?>" value="D"> <?= $row['option_d'] ?></label>
        </div>
        <?php endwhile; ?>

        <button type="submit" class="btn-submit">Kirim Jawaban</button>
    </form>

</div>

</body>
</html>