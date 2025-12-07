<?php
session_start();
require "../config.php";
include "../components/sidebar_guru.php";
// ==========================
// Cek login dan role guru
// ==========================
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../public/login.php");
    exit;
}

$teacher_id = $_SESSION['user_id'];

// ==========================
// Ambil data guru
// ==========================
$stmt = $conn->prepare("
    SELECT fullname, email, mapel, avatar 
    FROM users 
    WHERE id = ?
");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$teacher = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$teacher) {
    die("Data guru tidak ditemukan.");
}

// ==========================
// Update Profil
// ==========================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $mapel    = trim($_POST['mapel']);

    // avatar lama
    $avatar_name = $teacher['avatar'];

    // Upload avatar baru jika dipilih
    if (!empty($_FILES['avatar']['name'])) {

        $uploadDir = "../uploads/avatars/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES["avatar"]["name"]);
        $targetPath = $uploadDir . $fileName;

        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetPath)) {
                $avatar_name = $fileName;
            }
        }
    }

    // Simpan perubahan
    $stmt = $conn->prepare("
        UPDATE users 
        SET fullname=?, email=?, mapel=?, avatar=? 
        WHERE id=?
    ");
    $stmt->bind_param("ssssi", $fullname, $email, $mapel, $avatar_name, $teacher_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Profil berhasil diperbarui!'); window.location='profile.php';</script>";
    exit;
}

?>

<?php include "../components/sidebar_guru.php"; ?>

<style>
.profile-card {
    max-width: 550px;
    background: white;
    padding: 25px;
    margin: 20px auto;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}
.profile-card h2 {
    text-align: center;
    margin-bottom: 20px;
}
.profile-card img {
    display: block;
    margin: 10px auto;
    border-radius: 100px;
}
.input-group {
    margin-bottom: 15px;
}
.input-group label {
    font-weight: bold;
    display: block;
}
.input-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
}
.save-btn {
    width: 100%;
    padding: 12px;
    background: #4b5ef7;
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 16px;
    cursor: pointer;
}
.save-btn:hover {
    background: #3544d1;
}
</style>

<div class="content-siswa">

    <div class="profile-card">
        <h2>Profil Guru</h2>

        <form action="" method="POST" enctype="multipart/form-data">

            <img src="../uploads/avatars/<?= $teacher['avatar'] ?: 'default.png' ?>"
                width="120" height="120">

            <div class="input-group">
                <label>Nama Lengkap</label>
                <input type="text" name="fullname" value="<?= $teacher['fullname'] ?>" required>
            </div>

            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= $teacher['email'] ?>" required>
            </div>

            <div class="input-group">
                <label>Mata Pelajaran</label>
                <input type="text" name="mapel" value="<?= $teacher['mapel'] ?>">
            </div>

            <div class="input-group">
                <label>Ganti Foto Profil</label>
                <input type="file" name="avatar" accept="image/*">
            </div>

            <button class="save-btn">Simpan Perubahan</button>
        </form>
    </div>

</div>