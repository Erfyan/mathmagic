<?php
session_start();
require_once "../config.php"; // pastikan $conn tersedia (mysqli)

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/index.php");
    exit;
}

$subject_id = 3;

// Ambil satu quiz_list untuk subject_id = 3 (jika ada)
$q = $conn->prepare("SELECT id, title, total_questions FROM quiz_list WHERE subject_id = ? LIMIT 1");
$q->bind_param("i", $subject_id);
$q->execute();
$resQuiz = $q->get_result();
$q->close();

if (!$resQuiz || $resQuiz->num_rows === 0) {
    // Jika tidak ada entri quiz_list, fallback total soal 10 dan judul default
    $quiz = [
        'id' => 0,
        'title' => 'Quiz Matematika (Subject 3)',
        'total_questions' => 10
    ];
} else {
    $quiz = $resQuiz->fetch_assoc();
}

// Ambil soal dari question_bank untuk subject_id = 3
$total_questions = (int)$quiz['total_questions'];
$stmt = $conn->prepare("
    SELECT id, question, option_a, option_b, option_c, option_d
    FROM question_bank
    WHERE subject_id = ?
    ORDER BY RAND()
    LIMIT ?
");
$stmt->bind_param("ii", $subject_id, $total_questions);
$stmt->execute();
$qs = $stmt->get_result();
$stmt->close();

$questions = $qs ? $qs->fetch_all(MYSQLI_ASSOC) : [];
$addExp = 20;        
$stmt = $conn->prepare("
    INSERT INTO student_activity (student_id, activity)
    VALUES (?, ?)
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<title><?= htmlspecialchars($quiz['title']) ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1" />
<style>
/* THEME MATHMAGIC */
:root { --primary:#4b5ef7; --primary-2:#667eea; --bg1:#667eea; --bg2:#f093fb; }
body{
  margin:0;
  font-family: 'Segoe UI', sans-serif;
  background: linear-gradient(135deg,var(--bg1) 0%, #764ba2 45%, var(--bg2) 100%);
  padding:40px 16px;
  color:#222;
}
.container{
  max-width:980px;
  margin:0 auto;
}
.card{
  background: rgba(255,255,255,0.95);
  padding:28px;
  border-radius:16px;
  box-shadow:0 10px 30px rgba(0,0,0,0.15);
}
.header { text-align:center; margin-bottom:18px; }
.header h1{ margin:0; color:var(--primary); font-size:28px; }
.header p{ margin:6px 0 0; color:#555; }

.q-item{
  background:#fbfcff;
  border:1px solid #e6e9fb;
  padding:18px;
  border-radius:12px;
  margin-bottom:18px;
}
.q-title{ font-weight:700; color:#333; margin-bottom:10px; }
.q-option { display:block; padding:10px 12px; border-radius:8px; margin-bottom:8px; background:#fff; border:1px solid #eef2ff; cursor:pointer; }
.q-option input { margin-right:8px; transform:scale(1.1); }

.btn{
  width:100%;
  padding:14px;
  border:none;
  border-radius:12px;
  color:#fff;
  background: linear-gradient(135deg,var(--primary),var(--primary-2));
  font-weight:700;
  font-size:16px;
  cursor:pointer;
  box-shadow:0 8px 20px rgba(75,94,247,0.2);
}
.btn:hover{ transform:translateY(-3px); }

.small-note { font-size:13px; color:#666; margin-top:10px; text-align:center; }
@media(max-width:600px){
  .card{ padding:18px; border-radius:12px; }
}
</style>
</head>
<body>
<div class="container">
  <div class="card">
    <div class="header">
      <h1><?= htmlspecialchars($quiz['title']) ?></h1>
      <p>Total soal: <strong><?= $total_questions ?></strong></p>
    </div>

    <?php if (count($questions) === 0): ?>
      <p style="text-align:center;color:#c33;">Belum ada soal untuk subject_id = 3.</p>
    <?php else: ?>
      <form method="POST" action="quiz_answer.php">
        <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
        <?php $no = 1; foreach ($questions as $q): ?>
          <div class="q-item">
            <div class="q-title"><?= $no++ ?>. <?= htmlspecialchars($q['question']) ?></div>

            <input type="hidden" name="qid[]" value="<?= (int)$q['id'] ?>">

            <label class="q-option">
              <input type="radio" name="answer[<?= (int)$q['id'] ?>]" value="A" required>
              <?= htmlspecialchars($q['option_a']) ?>
            </label>
            <label class="q-option">
              <input type="radio" name="answer[<?= (int)$q['id'] ?>]" value="B">
              <?= htmlspecialchars($q['option_b']) ?>
            </label>
            <label class="q-option">
              <input type="radio" name="answer[<?= (int)$q['id'] ?>]" value="C">
              <?= htmlspecialchars($q['option_c']) ?>
            </label>
            <label class="q-option">
              <input type="radio" name="answer[<?= (int)$q['id'] ?>]" value="D">
              <?= htmlspecialchars($q['option_d']) ?>
            </label>
          </div>
        <?php endforeach; ?>

        <button type="submit" class="btn">Kumpulkan Jawaban</button>
        <div class="small-note">Pastikan semua soal telah dipilih sebelum mengirim.</div>
      </form>
    <?php endif; ?>

  </div>
</div>
</body>
</html>