<?php
session_start();
require_once "../config.php";

// =====================================
// VALIDASI AKSES GURU
// =====================================
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../login.php");
    exit;
}

$teacher_id = $_SESSION['user_id'];
$quiz_id = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 0;

if ($quiz_id <= 0) die("Quiz ID tidak valid.");

// =====================================
// AMBIL DATA QUIZ
// =====================================
$q = $conn->prepare("SELECT title, subject_id FROM quiz_list WHERE id=?");
$q->bind_param("i", $quiz_id);
$q->execute();
$quiz = $q->get_result()->fetch_assoc();
$q->close();

if (!$quiz) die("Quiz tidak ditemukan.");

$title = $quiz['title'];
$subject_id = $quiz['subject_id'];

// =====================================
// VALIDASI SUBJECT_ID
// =====================================
$check = $conn->prepare("SELECT id FROM subjects WHERE id=?");
$check->bind_param("i", $subject_id);
$check->execute();
$exists = $check->get_result()->num_rows > 0;
$check->close();
if (!$exists) die("Subject terkait quiz tidak ditemukan.");

// =====================================
// AMBIL DATA GURU
// =====================================
$g = $conn->prepare("SELECT fullname FROM users WHERE id=?");
$g->bind_param("i", $teacher_id);
$g->execute();
$guru = $g->get_result()->fetch_assoc();
$g->close();

// =====================================
// TAMBAH SOAL
// =====================================
$success = $error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_question'])) {
    // Ambil data dari form
    $kelas      = $_POST['kelas'] ?? '';
    $difficulty = (int)($_POST['difficulty'] ?? 3);
    $question   = trim($_POST['question'] ?? '');
    $optA       = !empty($_POST['option_a']) ? trim($_POST['option_a']) : null;
    $optB       = !empty($_POST['option_b']) ? trim($_POST['option_b']) : null;
    $optC       = !empty($_POST['option_c']) ? trim($_POST['option_c']) : null;
    $optD       = !empty($_POST['option_d']) ? trim($_POST['option_d']) : null;
    $correct    = !empty($_POST['correct_answer']) ? $_POST['correct_answer'] : null;

    // Validasi wajib
    if (empty($kelas)) {
        $error = "Kategori kelas harus dipilih!";
    } elseif (empty($question)) {
        $error = "Pertanyaan tidak boleh kosong!";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO question_bank
            (created_by, subject_id, kelas, type, question, option_a, option_b, option_c, option_d, correct_answer, difficulty)
            VALUES (?, ?, ?, 'mcq', ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iisssssssi",
            $teacher_id,  // created_by
            $subject_id,  // subject_id
            $kelas,       // kelas
            $question,
            $optA,
            $optB,
            $optC,
            $optD,
            $correct,
            $difficulty
        );

        if ($stmt->execute()) {
            $conn->query("UPDATE quiz_list SET total_questions = total_questions + 1 WHERE id = $quiz_id");
            $success = "Soal berhasil ditambahkan!";
        } else {
            $error = "Terjadi kesalahan: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Soal — <?= htmlspecialchars($title) ?></title>
<style>
body { margin:0; background: linear-gradient(135deg,#667eea,#764ba2,#f093fb); color:white; min-height:100vh; font-family:'Segoe UI',sans-serif; }
.container { margin-left:180px; padding:40px; }
.form-card { background: rgba(255,255,255,0.93); padding:25px; border-radius:16px; max-width:700px; color:#333; box-shadow:0 10px 25px rgba(0,0,0,0.25); }
input,textarea,select { width:100%; padding:10px; border-radius:8px; margin-bottom:15px; }
button { background:#4b5ef7; border:none; padding:12px 18px; color:white; border-radius:10px; cursor:pointer; }
button:hover { background:#3a45d1; }
.alert { padding:12px 15px; border-radius:10px; margin-bottom:15px; font-weight:bold; }
.alert.success { background:#2ecc71; color:white; }
.alert.error { background:#e74c3c; color:white; }
</style>
</head>
<body>

<?php include "../components/sidebar_guru.php"; ?>

<div class="container">
    <h2>Tambah Soal ke Quiz: <b><?= htmlspecialchars($title) ?></b></h2>

    <?php if ($success): ?>
        <div class="alert success"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST">
            <label>Kategori Kelas</label>
            <select name="kelas" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="SD" <?= (isset($kelas) && $kelas=='SD')?'selected':'' ?>>SD</option>
                <option value="SMP" <?= (isset($kelas) && $kelas=='SMP')?'selected':'' ?>>SMP</option>
                <option value="SMA" <?= (isset($kelas) && $kelas=='SMA')?'selected':'' ?>>SMA</option>
            </select>

            <label>Kesulitan Soal</label>
            <select name="difficulty" required>
                <option value="1" <?= ($difficulty==1)?'selected':'' ?>>Easy</option>
                <option value="2" <?= ($difficulty==2)?'selected':'' ?>>Medium</option>
                <option value="3" <?= ($difficulty==3)?'selected':'' ?>>Hard</option>
            </select>

            <label>Pertanyaan</label>
            <textarea name="question" required><?= isset($question)?htmlspecialchars($question):'' ?></textarea>

            <label>Pilihan A</label><input type="text" name="option_a" value="<?= isset($optA)?htmlspecialchars($optA):'' ?>">
            <label>Pilihan B</label><input type="text" name="option_b" value="<?= isset($optB)?htmlspecialchars($optB):'' ?>">
            <label>Pilihan C</label><input type="text" name="option_c" value="<?= isset($optC)?htmlspecialchars($optC):'' ?>">
            <label>Pilihan D</label><input type="text" name="option_d" value="<?= isset($optD)?htmlspecialchars($optD):'' ?>">

            <label>Jawaban Benar</label>
            <select name="correct_answer">
                <option value="">-- Pilih Jawaban --</option>
                <option value="A" <?= (isset($correct) && $correct=='A')?'selected':'' ?>>A</option>
                <option value="B" <?= (isset($correct) && $correct=='B')?'selected':'' ?>>B</option>
                <option value="C" <?= (isset($correct) && $correct=='C')?'selected':'' ?>>C</option>
                <option value="D" <?= (isset($correct) && $correct=='D')?'selected':'' ?>>D</option>
            </select>

            <button type="submit" name="add_question">Tambah Soal</button>
        </form>
    </div>

    <br>
    <a href="quiz_list.php" style="color:white;">⬅ Kembali ke daftar quiz</a>
</div>
</body>
</html>