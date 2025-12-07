<?php
session_start();
require '../config.php';
include '../components/sidebar_guru.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guru') {
    header("Location: ../login.php");
    exit;
}

$guru_id = $_SESSION['user_id'];

// ================= LOAD DATA =================
$subjects = $conn->query("SELECT id, name FROM subjects ORDER BY name ASC");

$edit_mode = false;
$edit_data = [];

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM question_bank WHERE id=$id AND guru_id=$guru_id");
    header("Location: banksoal_manage.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $subject_id = $_POST['subject_id'];
    $kelas      = $_POST['kelas'];
    $type       = 'MCQ'; // paksa type MCQ
    $question   = $_POST['question'];
    $a = $_POST['option_a'];
    $b = $_POST['option_b'];
    $c = $_POST['option_c'];
    $d = $_POST['option_d'];
    $kunci      = $_POST['correct_answer'];
    $difficulty = $_POST['difficulty'];

    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $conn->query("
            UPDATE question_bank SET 
                subject_id='$subject_id',
                kelas='$kelas',
                type='$type',
                question='$question',
                option_a='$a', option_b='$b', option_c='$c', option_d='$d',
                correct_answer='$kunci',
                difficulty='$difficulty'
            WHERE id=$id AND guru_id=$guru_id
        ");
    } else {
        $conn->query("
            INSERT INTO question_bank 
            (subject_id, kelas, type, question, option_a, option_b, option_c, option_d, correct_answer, difficulty)
            VALUES 
            ('$subject_id', '$kelas', '$type', '$question',
             '$a','$b','$c','$d','$kunci','$difficulty')
        ");
    }

    header("Location: banksoal_manage.php");
    exit;
}

$bank = $conn->query("
    SELECT 
        b.*, 
        s.name AS subject_name
    FROM question_bank b
    LEFT JOIN subjects s ON b.subject_id = s.id
    ORDER BY b.id DESC
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Bank Soal - Guru</title>

<style>
/* ===== BACKGROUND ===== */
body {
    margin: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    min-height: 100vh;
    padding: 30px;
}

/* ===== CONTAINER ===== */
.container {
    width: 92%;
    margin: auto;
}

/* ===== TITLE ===== */
h2 {
    text-align: center;
    color: white;
    font-size: 34px;
    margin-bottom: 20px;
    text-shadow: 0 0 10px rgba(0,0,0,0.3);
}

h3 {
    color: #4b5ef7;
    margin-top: 0;
}

/* ===== FORM CARD ===== */
.form-card {
    background: rgba(255,255,255,0.95);
    padding: 20px 25px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    margin-bottom: 30px;
    transition: 0.3s;
}
.form-card:hover {
    transform: translateY(-4px);
}

/* INPUT */
input, select, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #bbb;
    border-radius: 10px;
    margin: 8px 0 15px 0;
    font-size: 14px;
}

/* BUTTON */
.btn {
    background: linear-gradient(135deg, #667eea, #764ba2);
    padding: 10px 18px;
    border-radius: 10px;
    display: inline-block;
    color: white;
    text-decoration: none;
    transition: 0.3s;
}
.btn:hover {
    transform: scale(1.05);
}

.btn-danger {
    background: #e74c3c;
}

/* ===== TABLE CARD ===== */
.table-card {
    background: rgba(255,255,255,0.95);
    padding: 20px 25px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

th {
    background: #4b5ef7;
    color: white;
    padding: 10px;
    border-radius: 5px;
}

td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}
</style>

</head>
<body>

<div class="container">

    <h2>Kelola Bank Soal</h2>

    <!-- FORM TAMBAH / EDIT -->
    <div class="form-card">
        <h3><?= $edit_mode ? "Edit Soal" : "Tambah Soal Baru" ?></h3>

        <form method="post">

            <?php if ($edit_mode): ?>
                <input type="hidden" name="id" value="<?= $edit_data['id']; ?>">
            <?php endif; ?>

            <label>Mata Pelajaran</label>
            <select name="subject_id" required>
                <?php while ($s = $subjects->fetch_assoc()): ?>
                <option value="<?= $s['id']; ?>"
                    <?= ($edit_mode && $edit_data['subject_id'] == $s['id']) ? "selected" : "" ?>>
                    <?= $s['name']; ?>
                </option>
                <?php endwhile; ?>
            </select>

            <label>Kelas</label>
            <select name="kelas" required>
                <option value="SD"  <?= ($edit_mode && $edit_data['kelas']=='SD') ? "selected":"" ?>>SD</option>
                <option value="SMP" <?= ($edit_mode && $edit_data['kelas']=='SMP') ? "selected":"" ?>>SMP</option>
                <option value="SMA" <?= ($edit_mode && $edit_data['kelas']=='SMA') ? "selected":"" ?>>SMA</option>
            </select>

            <label>Jenis Materi</label>
            <input name="type" value="<?= $edit_mode ? $edit_data['type'] : '' ?>" required>

            <label>Pertanyaan</label>
            <textarea name="question" required><?= $edit_mode ? $edit_data['question'] : '' ?></textarea>

            <label>Pilihan A</label>
            <input name="option_a" value="<?= $edit_mode ? $edit_data['option_a'] : '' ?>" required>

            <label>Pilihan B</label>
            <input name="option_b" value="<?= $edit_mode ? $edit_data['option_b'] : '' ?>" required>

            <label>Pilihan C</label>
            <input name="option_c" value="<?= $edit_mode ? $edit_data['option_c'] : '' ?>" required>

            <label>Pilihan D</label>
            <input name="option_d" value="<?= $edit_mode ? $edit_data['option_d'] : '' ?>" required>

            <label>Kunci Jawaban</label>
            <select name="correct_answer" required>
                <option value="A" <?= ($edit_mode && $edit_data['correct_answer']=='A') ? "selected":"" ?>>A</option>
                <option value="B" <?= ($edit_mode && $edit_data['correct_answer']=='B') ? "selected":"" ?>>B</option>
                <option value="C" <?= ($edit_mode && $edit_data['correct_answer']=='C') ? "selected":"" ?>>C</option>
                <option value="D" <?= ($edit_mode && $edit_data['correct_answer']=='D') ? "selected":"" ?>>D</option>
            </select>

            <label>Tingkat Kesulitan</label>
            <select name="difficulty" required>
                <option value="easy"   <?= ($edit_mode && $edit_data['difficulty']=='easy') ? "selected":"" ?>>Mudah</option>
                <option value="medium" <?= ($edit_mode && $edit_data['difficulty']=='medium') ? "selected":"" ?>>Sedang</option>
                <option value="hard"   <?= ($edit_mode && $edit_data['difficulty']=='hard') ? "selected":"" ?>>Sulit</option>
            </select>

            <button class="btn" type="submit">Simpan Soal</button>
        </form>
    </div>

    <!-- TABLE -->
    <div class="table-card">
        <h3>Daftar Soal Buatan Guru</h3>

        <table>
            <tr>
                <th>ID</th>
                <th>Mata Pelajaran</th>
                <th>Kelas</th>
                <th>Pertanyaan</th>
                <th>Kunci</th>
                <th>Aksi</th>
            </tr>

            <?php while ($b = $bank->fetch_assoc()): ?>
            <tr>
                <td><?= $b['id']; ?></td>
                <td><?= $b['subject_name']; ?></td>
                <td><?= $b['kelas']; ?></td>
                <td><?= substr($b['question'], 0, 50); ?>...</td>
                <td><?= $b['correct_answer']; ?></td>
                <td>
                    <a class="btn btn-danger" href="banksoal_manage.php?delete=<?= $b['id']; ?>"
                        onclick="return confirm('Hapus soal ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>

        </table>
    </div>
</div>

</body>
</html>