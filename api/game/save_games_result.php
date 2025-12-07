<?php
session_start();
require '../../config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='siswa'){
    http_response_code(401);
    echo json_encode(["message"=>"Unauthorized"]);
    exit;
}

$student_id = $_SESSION['user_id'];

// Ambil data JSON dari fetch
$data = json_decode(file_get_contents("php://input"), true);
$game_name = $conn->real_escape_string($data['game_name']);
$points = (int)$data['points'];
$time_spent = 0; // jika ingin pakai timer
$status = $conn->real_escape_string($data['status']);

$stmt = $conn->prepare("INSERT INTO game_results (student_id, game_name, points, time_spent, created_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("isii", $student_id, $game_name, $points, $time_spent);
$stmt->execute();

echo json_encode(["message"=>"Game result saved"]);
?>