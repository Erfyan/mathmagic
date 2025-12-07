<?php
require "../config.php";

$id = (int)$_GET['id'];

$q = $conn->query("SELECT * FROM question_bank WHERE id=$id")->fetch_assoc();

header('Content-Type: application/json');
echo json_encode($q);
