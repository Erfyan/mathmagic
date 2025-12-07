<?php
session_start();
require_once "../config.php";

// =====================================
// Pastikan role user adalah guru
// =====================================
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../login.php");
    exit;
}

$teacher_id = $_SESSION['user_id'];

// =====================================
// Ambil mapel & kelas guru
// =====================================
$stmt = $conn->prepare("SELECT fullname, mapel, kelas FROM users WHERE id=?");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$guru = $stmt->get_result()->fetch_assoc();
$stmt->close();

$teacher_name = $guru['fullname'];
$subject_id   = $guru['mapel'];
$kelas_guru   = $guru['kelas'];

$quiz_id = 0;

// =====================================
// CREATE QUIZ
// =====================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_quiz'])) {

    $title = $_POST['title'];
    $difficulty = $_POST['difficulty'];

    $stmt = $conn->prepare("
        INSERT INTO quiz_list (subject_id, title, difficulty, total_questions)
        VALUES (?, ?, ?, 0)
    ");
    $stmt->bind_param("iss", $subject_id, $title, $difficulty);
    $stmt->execute();

    $quiz_id = $stmt->insert_id;
    $stmt->close();

    $success = "Quiz berhasil dibuat! Sekarang tambahkan soal.";
}

// =====================================
// ADD QUESTION
// =====================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_question'])) {

    $quiz_id   = $_POST['quiz_id'];
    $question  = $_POST['question'];
    $optA      = $_POST['option_a'];
    $optB      = $_POST['option_b'];
    $optC      = $_POST['option_c'];
    $optD      = $_POST['option_d'];
    $correct   = $_POST['correct_answer'];
    $difficulty = $_POST['difficulty'];
    $type       = "mcq";

    $stmt = $conn->prepare("
        INSERT INTO question_bank 
        (created_by, subject_id, kelas, type, question, option_a, option_b, option_c, option_d, correct_answer, difficulty)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "iisssssssss",
        $teacher_id, $subject_id, $kelas_guru, $type, 
        $question, $optA, $optB, $optC, $optD, $correct, $difficulty
    );
    $stmt->execute();
    $stmt->close();

    $conn->query("UPDATE quiz_list SET total_questions = total_questions + 1 WHERE id = $quiz_id");

    $success = "Soal berhasil ditambahkan!";
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Quiz Builder - Guru</title>
<style>
/* ===================== */
/* GLOBAL PAGE STYLE     */
/* ===================== */
body {
    margin: 0;
    background: linear-gradient(135deg, #667eea, #764ba2, #f093fb);
    min-height: 100vh;
    color: white;
}

.container {
    margin-left: 180px;
    padding: 35px;
}

/* ===================== */
/* TITLE                 */
/* ===================== */
.page-title {
    font-size: 32px;
    font-weight: bold;
    text-shadow: 0 3px 6px rgba(0,0,0,0.2);
}
.sub-title {
    opacity: .9;
    margin-bottom: 20px;
}

/* ===================== */
/* ALERT                 */
/* ===================== */
.alert-success {
    background: #2ecc71;
    padding: 15px;
    border-radius: 10px;
    color: white;
    font-weight: bold;
    margin-bottom: 20px;
    width: 500px;
}

/* ===================== */
/* FORM CARD             */
/* ===================== */
.form-card {
    background: rgba(255,255,255,0.93);
    padding: 25px;
    max-width: 600px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
    color: #333;
    margin-bottom: 30px;
}

.form-card h3 {
    margin-top: 0;
    color: #4b5ef7;
}

label {
    font-weight: bold;
}

input[type="text"],
textarea,
select {
    width: 100%;
    padding: 10px;
    margin: 6px 0 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
}

textarea {
    height: 90px;
    resize: vertical;
}

/* ===================== */
/* BUTTONS               */
/* ===================== */
button,
.btn-back {
    background: #4b5ef7;
    border: none;
    padding: 12px 18px;
    color: white;
    font-size: 15px;
    border-radius: 10px;
    cursor: pointer;
    transition: .3s;
    display: inline-block;
    margin-top: 10px;
}

button:hover,
.btn-back:hover {
    background: #3d48c9;
    transform: translateY(-3px);
}

.btn-back {
    text-decoration: none;
}

</style>

</head>
<body>

<?php include "../components/sidebar_guru.php"; ?>

<div class="container">

    <div class="page-title">Quiz Builder</div>
    <div class="sub-title">
        Guru: <b><?= $teacher_name ?></b> • Mapel ID: <b><?= $subject_id ?></b> • Kelas: <b><?= $kelas_guru ?></b>
    </div>

    <?php if (isset($success)) { ?>
        <div class="alert-success"><?= $success ?></div>
    <?php } ?>

    <!-- ===================================== -->
    <!-- FORM BUAT QUIZ -->
    <!-- ===================================== -->
    <?php if ($quiz_id == 0) { ?>
    <div class="form-card">
        <h3>Buat Quiz Baru</h3>

        <form method="POST">

            <label>Judul Quiz</label>
            <input type="text" name="title" required>

            <label>Tingkat Kesulitan</label>
            <select name="difficulty" required>
                <option value="mudah">Mudah</option>
                <option value="sedang">Sedang</option>
                <option value="sulit">Sulit</option>
            </select>

            <button type="submit" name="create_quiz">Buat Quiz</button>
        </form>
    </div>

    <?php } else { ?>

    <!-- ===================================== -->
    <!-- FORM TAMBAH SOAL -->
    <!-- ===================================== -->
    <div class="form-card">
        <h3>Tambah Soal ke Quiz</h3>

        <form method="POST">

            <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">

            <label>Pertanyaan</label>
            <textarea name="question" required></textarea>

            <label>Pilihan A</label>
            <input type="text" name="option_a" required>

            <label>Pilihan B</label>
            <input type="text" name="option_b" required>

            <label>Pilihan C</label>
            <input type="text" name="option_c" required>

            <label>Pilihan D</label>
            <input type="text" name="option_d" required>

            <label>Jawaban Benar</label>
            <select name="correct_answer" required>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>

            <label>Kesulitan Soal</label>
            <select name="difficulty" required>
                <option value="mudah">Mudah</option>
                <option value="sedang">Sedang</option>
                <option value="sulit">Sulit</option>
            </select>

            <button type="submit" name="add_question">Tambah Soal</button>
        </form>
    </div>

    <a href="dashboard.php" class="btn-back">⬅ Kembali ke Dashboard</a>

    <?php } ?>

</div>
</body>
</html>