<?php
require_once "../init.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") die("Invalid request");

$fullname = $_POST["fullname"];
$email    = $_POST["email"];
$role     = $_POST["role"];
$kelas    = $_POST["kelas"] ?? NULL;
$mapel    = $_POST["mapel"] ?? NULL;
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    INSERT INTO users(fullname, email, role, kelas, mapel, password_hash)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("ssssss", $fullname, $email, $role, $kelas, $mapel, $password);
$stmt->execute();

redirect("../public/index.php?msg=registered");
?>