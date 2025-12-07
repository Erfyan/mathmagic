<?php
// ==========================
// CONFIG GLOBAL MATHMAGIC
// ==========================

// Error mode dev
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Timezone
date_default_timezone_set("Asia/Jakarta");

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "mathmagic";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Base path
define("BASE_URL", "/mathmagic/public/");

// Folder uploads
define("UPLOAD_PATH", __DIR__ . "/public/uploads/");
?>
