<?php
require_once "../config.php";
require_once "../init.php";

$err = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    $kelas = ($_POST['role'] === "siswa") ? $_POST['kelas'] : NULL;
    $mapel = ($_POST['role'] === "guru") ? $_POST['mapel'] : NULL;

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // cek email sudah ada
    $check = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $err = "Email sudah terdaftar!";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO users (fullname, email, role, kelas, mapel, password_hash, created_at)
            VALUES (?,?,?,?,?,?,NOW())
        ");
        $stmt->bind_param("ssssss",
            $fullname,
            $email,
            $role,
            $kelas,
            $mapel,
            $password
        );

        if ($stmt->execute()) {
            $success = "Akun berhasil dibuat!";
        } else {
            $err = "Terjadi kesalahan saat membuat akun.";
        }
    }
}
// Setelah siswa berhasil ditambahkan
$studentId = $conn->insert_id;

// Ambil semua mapel untuk di-set level awal
$subjects = $conn->query("SELECT id FROM subjects");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Akun - MathMagic</title>
<link rel="stylesheet" href="assets/css/main.css">

<style>
/* ======================= */
/* FORM REGISTER - MATHMAGIC */
/* ======================= */
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.form-box {
    background: rgba(255,255,255,0.95);
    padding: 40px 30px;
    border-radius: 16px;
    max-width: 400px;
    width: 100%;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    transition: transform 0.3s, box-shadow 0.3s;
    text-align: center;
}

.form-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
}

.form-box h2 {
    margin-bottom: 5px;
    font-size: 28px;
    color: #4b5ef7;
}

.form-box p {
    margin-bottom: 20px;
    color: #333;
}

.form-box label {
    display: block;
    text-align: left;
    margin-top: 15px;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
}

.form-box input[type="text"],
.form-box input[type="email"],
.form-box input[type="password"],
.form-box select {
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 15px;
    outline: none;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.form-box input:focus,
.form-box select:focus {
    border-color: #4b5ef7;
    box-shadow: 0 0 10px rgba(75,94,247,0.3);
}

.form-box button {
    margin-top: 20px;
    width: 100%;
    padding: 12px 0;
    background: #4b5ef7;
    color: white;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.form-box button:hover {
    background: #3a48c1;
    transform: translateY(-2px);
}

.form-box a {
    color: #4b5ef7;
    text-decoration: none;
    font-weight: bold;
}

.form-box a:hover {
    text-decoration: underline;
}

.success-box {
    background: #ddffdd;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 6px;
    color: #2d662d;
    font-size: 14px;
}

.error-box {
    background: #ffdddd;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 6px;
    color: #a94442;
    font-size: 14px;
}
</style>

<script>
function toggleRole() {
    const role = document.getElementById('role').value;
    document.getElementById('kelas_box').style.display = role === 'siswa' ? 'block' : 'none';
    document.getElementById('mapel_box').style.display = role === 'guru' ? 'block' : 'none';
}
</script>
</head>

<body>

<div class="form-box">

    <h2 style="text-align:center">Daftar Akun</h2>

    <?php if ($err): ?>
        <div style="background:#ffdddd;padding:10px;margin-bottom:10px;border-radius:6px;">
            <?= $err ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="background:#ddffdd;padding:10px;margin-bottom:10px;border-radius:6px;">
            <?= $success ?>
        </div>
    <?php endif; ?>


    <form method="POST">

        <label>Nama Lengkap</label>
        <input type="text" name="fullname" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Pilih Role</label>
        <select name="role" id="role" onchange="toggleRole()" required>
            <option value="">-- pilih --</option>
            <option value="siswa">Siswa</option>
            <option value="guru">Guru</option>
        </select>

        <!-- jika siswa -->
        <div id="kelas_box" style="display:none;">
            <label>Pilih Kelas</label>
            <select name="kelas">
                <option value="">-- pilih kelas --</option>
                <option value="SD-1">SD 1</option>
                <option value="SD-2">SD 2</option>
                <option value="SD-3">SD 3</option>
                <option value="SD-4">SD 4</option>
                <option value="SD-5">SD 5</option>
                <option value="SD-6">SD 6</option>
                <option value="SMP-7">SMP 7</option>
                <option value="SMP-8">SMP 8</option>
                <option value="SMP-9">SMP 9</option>
                <option value="SMA-10">SMA 10</option>
                <option value="SMA-11">SMA 11</option>
                <option value="SMA-12">SMA 12</option>
            </select>
        </div>

        <!-- jika guru -->
        <div id="mapel_box" style="display:none;">
            <label>Mata Pelajaran</label>
            <input type="text" name="mapel" placeholder="Matematika, IPA, dll">
        </div>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Buat Akun</button>

        <p style="text-align:center;margin-top:10px;">
            Sudah punya akun? <a href="login.php">Login disini</a>
        </p>

    </form>
</div>

</body>
</html>