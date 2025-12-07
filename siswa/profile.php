<?php
session_start();
require "../config.php";
require "../init.php";

// =======================
// CEK LOGIN & ROLE
// =======================
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../public/index.php");
    exit;
}

$student_id = (int) $_SESSION['user_id'];

// ===== AMBIL DATA USER =====
$stmt = $conn->prepare("SELECT fullname, email, kelas, role, avatar FROM users WHERE id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// ===== UPDATE AVATAR =====
if (isset($_POST['update_profile']) && !empty($_FILES['avatar']['name'])) {
    $fileName = time() . "_" . basename($_FILES['avatar']['name']);
    $dest = "../public/uploads/" . $fileName;
    move_uploaded_file($_FILES['avatar']['tmp_name'], $dest);

    $q = $conn->prepare("UPDATE users SET avatar=? WHERE id=?");
    $q->bind_param("si", $fileName, $student_id);
    $q->execute();

    header("Location: profile.php?updated=1");
    exit;
}

// ===== UPDATE PASSWORD =====
if (isset($_POST['update_pass'])) {
    $newPass = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);

    $q = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    $q->bind_param("si", $newPass, $student_id);
    $q->execute();

    header("Location: profile.php?pass_changed=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil Siswa - MathMagic</title>
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg,#667eea,#764ba2,#f093fb);
    color: #333;
    min-height: 100vh;
}

/* CONTENT WRAPPER */
.content-siswa {
    margin-left: 260px;
    padding: 40px 30px;
}

/* PAGE TITLE */
.page-title {
    text-align: center;
    color: white;
    text-shadow: 0 4px 15px rgba(0,0,0,0.3);
    margin-bottom: 30px;
}
.page-title h1 { font-size: 36px; margin: 0; }
.page-title p { font-size: 16px; margin-top: 5px; }

/* PROFILE CARD */
.profile-card {
    background: rgba(255,255,255,0.95);
    max-width: 500px;
    margin: auto;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    text-align: center;
}
.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #eee;
    margin-bottom: 15px;
}
.profile-table {
    width: 100%;
    margin-top: 15px;
    text-align: left;
}
.profile-table td {
    padding: 8px 5px;
}
.update-btn {
    padding: 10px 15px;
    background: #4b5ef7;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}
.update-btn:hover {
    opacity: 0.9;
}

/* ALERT */
.alert {
    width: 500px;
    margin: 10px auto;
    padding: 12px;
    border-radius: 10px;
    text-align: center;
    font-weight: bold;
}
.alert.success { background: #2ecc71; color: white; }
.alert.error { background: #e74c3c; color: white; }
</style>
</head>
<body>

<?php include "../components/sidebar_siswa.php"; ?>

<div class="content-siswa">
    <div class="page-title">
        <h1>Profil Saya</h1>
        <p>Kelola informasi dan keamanan akun Anda</p>
    </div>

    <!-- ALERT -->
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert success">Foto profil berhasil diperbarui!</div>
    <?php endif; ?>
    <?php if (isset($_GET['pass_changed'])): ?>
        <div class="alert success">Password berhasil diganti!</div>
    <?php endif; ?>

    <!-- PROFILE CARD -->
    <div class="profile-card">
        <img src="<?= $user['avatar'] ?: '../public/assets/images/default-avatar.png' ?>" class="profile-avatar">
        <h2><?= htmlspecialchars($user['fullname']) ?></h2>
        <table class="profile-table">
            <tr><td><strong>Email</strong></td><td>: <?= htmlspecialchars($user['email']) ?></td></tr>
            <tr><td><strong>Kelas</strong></td><td>: <?= htmlspecialchars($user['kelas']) ?></td></tr>
            <tr><td><strong>Role</strong></td><td>: <?= htmlspecialchars($user['role']) ?></td></tr>
        </table>

        <br>
        <form method="POST" enctype="multipart/form-data">
            <label>Ubah Foto Profil:</label><br>
            <input type="file" name="avatar">
            <br><br>
            <button name="update_profile" class="update-btn">Perbarui Profil</button>
        </form>

        <hr><br>

        <form method="POST">
            <label>Password Baru:</label><br>
            <input type="password" name="new_pass" required minlength="6">
            <br><br>
            <button name="update_pass" class="update-btn">Ganti Password</button>
        </form>
    </div>
</div>

</body>
</html>