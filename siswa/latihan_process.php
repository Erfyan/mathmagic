<?php
session_start();
require_once "../config.php";

// Pastikan login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/index.php");
    exit;
}

$student_id = $_SESSION['user_id'];

// Default nilai
$score = 0;
$total = 0;

// Pastikan request datang dari form (method POST) dan ada question_id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['question_id']) && is_array($_POST['question_id'])) {

    // Ambil array question_id dari POST, sanitasi jadi integer
    $question_ids = array_map('intval', $_POST['question_id']);
    $total = count($question_ids);

    if ($total > 0) {
        // Siapkan daftar id untuk query (safe)
        $ids_for_sql = implode(',', $question_ids);

        // Ambil correct_answer untuk semua question_id sekaligus (lebih efisien)
        $sql = "SELECT id, correct_answer FROM question_bank WHERE id IN ($ids_for_sql)";
        $res = $conn->query($sql);

        // Jika query gagal, set total 0 dan beri pesan (jangan gunakan variabel undefined)
        if (!$res) {
            $total = 0;
            $score = 0;
        } else {
            // Buat map id => correct_answer
            $answers_map = [];
            while ($r = $res->fetch_assoc()) {
                $answers_map[(int)$r['id']] = $r['correct_answer'];
            }

            // Hitung skor dengan membandingkan jawaban user
            $score = 0;
            foreach ($question_ids as $qid) {
                // nama field di form: answer_{id}
                $field = "answer_" . $qid;
                $student_answer = isset($_POST[$field]) ? trim($_POST[$field]) : '';

                // jika correct_answer tersedia di map, bandingkan (case-insensitive)
                if (isset($answers_map[$qid]) && $student_answer !== '') {
                    if (strcasecmp($student_answer, $answers_map[$qid]) === 0) {
                        $score++;
                    }
                }
            }
        }
    }
} else {
    // Jika tidak lewat POST atau question_id kosong, redirect atau siapkan pesan.
    // Kita redirect ke halaman latihan agar pengguna tidak melihat error.
    header("Location: latihan.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Hasil Latihan</title>
<style>
    /* Tema MathMagic (samakan dengan style sebelumnya) */
    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #333;
    }
    .wrap {
        width: 90%;
        max-width: 480px;
        background: rgba(255,255,255,0.95);
        padding: 30px;
        border-radius: 18px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.25);
        text-align: center;
        animation: fadeIn 0.6s ease-out;
    }
    h2 { color: #4b5ef7; margin-bottom: 10px; text-shadow: 0 0 5px rgba(0,0,0,0.2); }
    p { font-size: 18px; margin-top: 15px; color: #333; }
    .score { font-size: 26px; font-weight: bold; color: #4b5ef7; }
    .btn-back {
        margin-top: 25px;
        display: inline-block;
        padding: 12px 20px;
        background: #4b5ef7;
        color: white;
        border-radius: 12px;
        font-weight: bold;
        text-decoration: none;
        transition: 0.3s;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    }
    .btn-back:hover { transform: translateY(-3px); box-shadow: 0 8px 18px rgba(0,0,0,0.3); }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>
</head>
<body>

<div class="wrap">
    <h2>🎉 Hasil Latihan</h2>

    <p>Jawaban Benar:</p>
    <div class="score"><?php echo $score . " / " . $total; ?></div>

    <a href="banksoal.php" class="btn-back">⬅ Kembali ke Bank Soal</a>
</div>

</body>
</html>
