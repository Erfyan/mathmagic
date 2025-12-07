<?php
require_once "../init.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request");
}

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND is_active=1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    redirect("index.php?msg=invalid");
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password_hash'])) {
    redirect("index.php?msg=wrongpass");
}

$_SESSION['user_id']  = $user['id'];
$_SESSION['fullname'] = $user['fullname'];
$_SESSION['role']     = $user['role'];

if ($user['role'] === "siswa") redirect("../siswa/dashboard.php");
if ($user['role'] === "guru") redirect("../guru/dashboard.php");

redirect("../index.php");
?>  