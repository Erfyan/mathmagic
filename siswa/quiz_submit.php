<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/index.php");
    exit;
}

// Ambil data dari session
if (!isset($_SESSION['quiz_subject_id'], $_SESSION['quiz_answers'], $_SESSION['quiz_qids'])) {
    die("Tidak ada data kuis yang tersedia. Mulai kuis dari awal.");
}

$student_id   = (int)$_SESSION['user_id'];
$subject_id   = (int)$_SESSION['quiz_subject_id']; // harus 3
$answers      = $_SESSION['quiz_answers'];          // array ans[qid] => 'A'...
$qids         = $_SESSION['quiz_qids'];            // array of qids (int)

// hapus session kuis supaya tidak double-submit
unset($_SESSION['quiz_subject_id'], $_SESSION['quiz_answers'], $_SESSION['quiz_qids']);

// Validasi ringan
if ($subject_id !== 3) {
    die("Subject tidak valid.");
}
if (!is_array($answers) || count($qids) === 0) {
    die("Data jawaban tidak valid.");
}

// Hitung skor: ambil correct_answer untuk semua qids sekaligus
$ids_for_sql = implode(',', array_map('intval', $qids));
$sql = "SELECT id, correct_answer FROM question_bank WHERE id IN ($ids_for_sql) AND subject_id = 3";
$res = $conn->query($sql);
if (!$res) {
    die("SQL Error: " . $conn->error);
}
$map = [];
while ($r = $res->fetch_assoc()) {
    $map[(int)$r['id']] = strtoupper(trim($r['correct_answer']));
}

// Hitung
$total = 0;
$score = 0;
foreach ($qids as $qid) {
    $qid = (int)$qid;
    if (!isset($map[$qid])) continue; // soal hilang skip (tetap hitung total?)
    $total++;
    $studentAns = isset($answers[$qid]) ? strtoupper(trim($answers[$qid])) : '';
    if ($studentAns !== '' && $studentAns === $map[$qid]) $score++;
}

// Jika total 0 (semua soal hilang), handle
if ($total === 0) {
    die("Tidak ada soal valid untuk dinilai.");
}

$percentage = round(($score / $total) * 100);

// Simpan ke student_score_history
// Pastikan tabel memiliki kolom: id, student_id, score, week_label, created_at
$week_label = "W" . date("W") . "-" . date("Y");

$stmt = $conn->prepare("
    INSERT INTO student_score_history (student_id, score, week_label, created_at)
    VALUES (?, ?, ?, NOW())
");
if ($stmt) {
    $stmt->bind_param("iis", $student_id, $percentage, $week_label);
    $stmt->execute();
    $stmt->close();
} else {
    // fallback: tampilkan error tapi jangan crash
    error_log("Prepare failed: " . $conn->error);
}

$activity = "Menyelesaikan kuis ID $quizId dengan skor $totalScore";

$stmt = $conn->prepare("
    INSERT INTO student_activity (student_id, activity)
    VALUES (?, ?)
");
$stmt->bind_param("is", $studentId, $activity);
$stmt->execute();

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<title>Hasil Kuis</title>
<meta name="viewport" content="width=device-width,initial-scale=1" />
<style>
/* same theme */
body{
  margin:0;
  font-family:'Segoe UI',sans-serif;
  background: linear-gradient(135deg,#667eea 0%,#764ba2 45%,#f093fb 100%);
  padding:50px 16px;
  color:#fff;
}
.wrap{
  max-width:560px;margin:0 auto;
  background:rgba(255,255,255,0.95);
  color:#333;padding:28px;border-radius:16px;
  box-shadow:0 12px 30px rgba(0,0,0,0.2); text-align:center;
}
h1{ margin:0 0 8px; color:#4b5ef7; }
.score{ font-size:48px; font-weight:800; color:#4b5ef7; margin:12px 0; }
.info{ font-size:16px; color:#555; margin-top:8px; }
.btn{ display:inline-block;margin-top:18px;padding:12px 20px;border-radius:12px;
      background:linear-gradient(135deg,#4b5ef7,#667eea); color:#fff; text-decoration:none; font-weight:700;}
</style>
</head>
<body>
  <div class="wrap">
    <h1>🎉 Kuis Selesai</h1>
    <div class="score"><?= $percentage ?>%</div>
    <div class="info">Benar: <?= $score ?> / <?= $total ?></div>
    <a class="btn" href="dashboard.php">Kembali ke Dashboard</a>
  </div>
</body>
</html>
