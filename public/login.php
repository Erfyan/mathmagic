<?php
require_once "../config.php";
require_once "../init.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, fullname, role, password_hash FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();

    if ($data && password_verify($password, $data['password_hash'])) {

        $_SESSION['user_id'] = $data['id'];
        $_SESSION['fullname'] = $data['fullname'];
        $_SESSION['role'] = $data['role'];

        // redirect by role
        if ($data['role'] === "siswa") {
            header("Location: ../siswa/dashboard.php");
        } else if ($data['role'] === "guru") {
            header("Location: ../guru/dashboard.php");
        } else {
            header("Location: admin.php");
        }
        exit;

    } else {
        $error = "Email atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login - MathMagic</title>
<style>
/* ===================== */
/* GLOBAL STYLE          */
/* ===================== */
body, html {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    color: #333;
    overflow-x: hidden;
}

/* ===================== */
/* SIDEBAR               */
/* ===================== */
.sidebar-siswa, .sidebar-guru {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100vh;
    background: #1a1f36;
    color: white;
    padding-top: 20px;
    box-shadow: 3px 0 10px rgba(0,0,0,0.2);
    z-index: 99;
}

.sidebar-siswa h2, .sidebar-guru h2 {
    text-align: center;
    font-size: 22px;
    margin-bottom: 20px;
    color: #fff;
    letter-spacing: 1px;
}

.sidebar-siswa ul, .sidebar-guru ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-siswa ul li a, .sidebar-guru ul li a {
    display: block;
    padding: 14px 20px;
    color: #e4e4e4;
    font-size: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.sidebar-siswa ul li a:hover, .sidebar-guru ul li a:hover {
    background: rgba(255,255,255,0.1);
    transform: translateX(5px);
}

.sidebar-siswa ul li a.active, .sidebar-guru ul li a.active {
    background: rgba(255,255,255,0.2);
    border-left: 4px solid #fff;
    font-weight: bold;
}

/* ===================== */
/* KONTEN                */
.content-siswa, .content-guru {
    margin-left: 260px;
    padding: 20px;
}

/* ===================== */
/* CARD                  */
.card {
    background: rgba(255,255,255,0.9);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

/* ===================== */
/* BUTTON                */
.button {
    padding: 10px 16px;
    background: #4b5ef7;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.button:hover {
    background: #3a48c1;
    transform: translateY(-2px);
}

/* ===================== */
/* PROFILE CARD          */
.profile-card {
    background: white;
    padding: 20px;
    max-width: 500px;
    margin: auto;
    border-radius: 14px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin: auto;
    display: block;
    border: 4px solid #eee;
}

.profile-table {
    width: 100%;
    margin-top: 15px;
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
}

.update-btn:hover {
    opacity: 0.9;
}

/* ===================== */
/* FLOATING MATH SYMBOLS */
.math-symbols {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
    pointer-events: none;
}

.math-symbol-float {
    position: absolute;
    color: rgba(255,255,255,0.1);
    font-size: 2rem;
    font-weight: bold;
    animation: float 20s infinite linear;
}

@keyframes float {
    0% { transform: translateY(100vh) rotate(0deg); }
    100% { transform: translateY(-100px) rotate(360deg); }
}
/* ===================== */
/* FORM LOGIN - MATHMAGIC */
/* ===================== */
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
    max-width: 380px;
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

.form-box input[type="email"],
.form-box input[type="password"] {
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 15px;
    outline: none;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.form-box input:focus {
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

.error-box {
    background: #ffdddd;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 6px;
    color: #a94442;
    font-size: 14px;
}
</style>
</head>
<body>
<div class="form-box">
    <h2 style="text-align:center">MATHMAGIC</h2>
    <p style="text-align:center">Masuk ke akun Anda</p>

    <?php if ($error): ?>
        <div style="background:#ffdddd;padding:10px;margin-bottom:10px;border-radius:6px;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Masuk</button>
    </form>

    <p style="text-align:center;margin-top:10px;">
        Belum punya akun? <a href="register.php">Daftar disini</a>
    </p>
</div>
</body>
</html>