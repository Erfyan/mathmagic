<?php
session_start();
require "../config.php";
require "../libs/seed_data_dummy.php";
include "../components/sidebar_siswa.php";

// Pastikan siswa login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/index.php");
    exit;
}

$student_id = $_SESSION['user_id'];

// ================================
// FILTER INPUT
// ================================
$jenjang = $_GET['jenjang'] ?? 'SMP';
$subject_id = $_GET['subject_id'] ?? '';
$kelas = $_GET['kelas'] ?? '';

// ================================
// AMBIL LIST MAPEL
// ================================
$subjects = $conn->query("SELECT id, name FROM subjects ORDER BY name ASC");

// ================================
// QUERY SOAL
// ================================
$q = "SELECT qb.*, s.name AS subject_name 
      FROM question_bank qb
      JOIN subjects s ON s.id = qb.subject_id
      WHERE qb.type = 'mcq'";  // hanya soal pilihan ganda

// Filter jenjang (contoh: kelas = "SMP 7", "SMP 8")
if ($jenjang)      
    $q .= " AND qb.kelas LIKE '%$jenjang%'";

// Filter subject/mapel
if ($subject_id)   
    $q .= " AND qb.subject_id = '$subject_id'";

// Filter kelas nomor (1–12)
if ($kelas)        
    $q .= " AND qb.kelas LIKE '%$kelas%'";

$q .= " ORDER BY qb.created_at DESC";

$questions = $conn->query($q);
?>
<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<title>Bank Soal</title>
<style>
/* =======================================
   RESET & GLOBAL
======================================= */
* {
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 40%, #f093fb 100%);
    min-height: 100vh;
    margin: 0;
    padding: 0;
    color: #333;
}

.container {
    padding: 35px;
    max-width: 1100px;
    margin: auto;
}

/* Title */
h2 {
    text-align: center;
    color: white;
    font-size: 36px;
    margin: 0;
    text-shadow: 0 0 12px rgba(0,0,0,0.35);
}

p {
    text-align: center;
    color: #f8f8f8;
    font-size: 17px;
    margin-bottom: 25px;
}

/* =======================================
   CARD / BOX STYLE
======================================= */
.box {
    background: rgba(255,255,255,0.92);
    padding: 25px;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.25);
    margin-bottom: 25px;
    animation: fadeIn 0.5s ease-out;
}

/* Animasi */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* =======================================
   FILTER FORM
======================================= */
.filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.filter-row div {
    flex: 1 1 180px;
}

.filter-row label {
    font-weight: bold;
    color: #4b5ef7;
}

.filter-row select {
    width: 100%;
    padding: 10px 12px;
    border-radius: 12px;
    border: 1px solid #cfd3ff;
    background: #f7f8ff;
    font-size: 15px;
}

/* Tombol Filter */
.filter-row button {
    padding: 12px;
    background: #4b5ef7;
    border: none;
    border-radius: 12px;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
    box-shadow: 0 3px 12px rgba(0,0,0,0.2);
}

.filter-row button:hover {
    background: #6373ff;
    transform: translateY(-3px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.3);
}

/* =======================================
   DAFTAR SOAL
======================================= */
.soal-item {
    background: #eef1ff;
    padding: 18px;
    border-radius: 16px;
    border-left: 5px solid #4b5ef7;
    margin-bottom: 15px;
    transition: 0.25s ease;
}

.soal-item:hover {
    background: #e2e5ff;
    transform: translateX(5px);
}

/* Judul Mapel & Kelas */
.soal-item strong {
    color: #4b5ef7;
    font-size: 17px;
}

/* Keterangan */
.soal-item small {
    color: #555;
}

/* =======================================
   BUTTON LATIHAN
======================================= */
.soal-item .btn {
    display: inline-block;
    padding: 10px 16px;
    background: #4b5ef7;
    color: white;
    border-radius: 12px;
    font-weight: bold;
    text-decoration: none;
    transition: 0.25s;
    margin-top: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.soal-item .btn:hover {
    background: #6c7bff;
    transform: translateY(-3px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.25);
}

/* Untuk teks soal preview */
.soal-item .preview {
    font-size: 15px;
    color: #444;
}
</style>
</head>

<body>

<div class="container">

<h2>📘 Bank Soal & Latihan</h2>
<p>Silakan filter soal berdasarkan mata pelajaran & kelas.</p>

<!-- FILTER -->
<div class="box">
    <form method="get" class="filter-row">
        
        <div>
            <label>Jenjang</label>
            <select name="jenjang">
                <option value="SD"  <?= $jenjang=="SD"?"selected":"" ?>>SD</option>
                <option value="SMP" <?= $jenjang=="SMP"?"selected":"" ?>>SMP</option>
                <option value="SMA" <?= $jenjang=="SMA"?"selected":"" ?>>SMA</option>
            </select>
        </div>

        <div>
            <label>Mata Pelajaran</label>
            <select name="subject_id">
                <option value="">Semua</option>
                <?php while($s = $subjects->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>" <?= $subject_id==$s['id']?'selected':'' ?>>
                        <?= $s['name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <br>
            <button type="submit" class="btn">Filter</button>
        </div>

    </form>
</div>

<!-- LIST SOAL -->
<div class="box">
    <h3>📚 Daftar Soal</h3>

    <?php if ($questions->num_rows == 0): ?>
        <p><i>Tidak ada soal ditemukan.</i></p>

    <?php else: ?>
        <?php while($q = $questions->fetch_assoc()): ?>
        <div class="soal-item">
            
            <strong><?= $q['subject_name'] ?> - Kelas <?= $q['kelas'] ?></strong><br>
            <small>Kesulitan: <?= $q['difficulty'] ?></small><br><br>

            <div><?= substr($q['question'], 0, 100) ?>...</div>

            <br>

            <!-- Tombol latihan berdasarkan MAPEL -->
<a href="latihan.php?subject=<?= $q['subject_id'] ?>" class="btn">Kerjakan</a>

        </div>
        <?php endwhile; ?>
    <?php endif; ?>

</div>

</div>
</body>
</html>