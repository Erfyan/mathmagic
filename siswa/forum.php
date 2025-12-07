<?php
session_start();
require "../config.php";

// =====================
// Cek login siswa
// =====================
if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='siswa') {
    header("Location: ../public/index.php");
    exit;
}

$student_id = (int)$_SESSION['user_id'];

// =====================
// Tambah thread baru
// =====================
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['new_thread'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO forum_threads(title, content, created_by, role) VALUES(?,?,?,?)");
    $role = 'siswa';
    $stmt->bind_param("ssis", $title, $content, $student_id, $role);
    $stmt->execute();
    $stmt->close();
    header("Location: forum.php");
    exit;
}

// =====================
// Ambil semua thread
// =====================
$threads = $conn->query("SELECT f.*, u.fullname FROM forum_threads f JOIN users u ON f.created_by=u.id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Forum Siswa - MATHMAGIC</title>
<style>
body {margin:0; font-family:'Segoe UI',sans-serif; background: linear-gradient(135deg,#667eea,#764ba2,#f093fb); color:#333; min-height:100vh;}
.container {margin-left:260px; padding:30px;}
h1 {text-align:center; color:white; margin-bottom:20px; text-shadow:0 0 10px rgba(0,0,0,0.3);}
.thread {background:rgba(255,255,255,0.95); padding:20px; border-radius:14px; margin-bottom:15px; box-shadow:0 8px 20px rgba(0,0,0,0.2);}
.thread h3 {margin:0 0 10px 0; color:#4b5ef7;}
.thread p {margin:0; color:#333;}
.new-thread {background:rgba(255,255,255,0.95); padding:20px; border-radius:14px; margin-bottom:30px; box-shadow:0 8px 20px rgba(0,0,0,0.2);}
input, textarea {width:100%; padding:10px; margin-bottom:10px; border-radius:10px; border:1px solid #ccc; font-family:'Segoe UI';}
button {background:#4b5ef7; color:white; padding:12px 20px; border:none; border-radius:12px; cursor:pointer; font-size:16px;}
button:hover {background:#667eea;}
</style>
</head>
<body>

<?php include "../components/sidebar_siswa.php"; ?>

<div class="container">
<h1>Forum Siswa</h1>

<div class="new-thread">
<form method="POST">
    <input type="text" name="title" placeholder="Judul Thread" required>
    <textarea name="content" placeholder="Isi thread..." rows="4" required></textarea>
    <button type="submit" name="new_thread">Buat Thread Baru</button>
</form>
</div>

<?php while($row=$threads->fetch_assoc()): ?>
<div class="thread">
    <h3><?= htmlspecialchars($row['title']) ?> <small style="font-size:12px;color:#555;">oleh <?= htmlspecialchars($row['fullname']) ?> (<?= $row['role'] ?>)</small></h3>
    <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
</div>
<?php endwhile; ?>

</div>
</body>
</html>
