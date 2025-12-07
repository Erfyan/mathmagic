<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak. Silakan login.");
}

// cek POST
if (!isset($_POST['subject_id']) || !isset($_POST['answer']) || !isset($_POST['qid'])) {
    die("Data tidak lengkap. Pastikan semua jawaban dipilih.");
}

// sanitize
$subject_id = (int)$_POST['subject_id'];
$answers = $_POST['answer'];     // ans[qid] => 'A'/'B'...
$qids = $_POST['qid'];           // qid[]

// minimal checks
if ($subject_id !== 3) {
    die("Akses tidak valid (subject_id harus 3).");
}
if (!is_array($answers) || !is_array($qids) || count($qids) === 0) {
    die("Data soal tidak valid.");
}

// store ke session (sementara)
$_SESSION['quiz_subject_id'] = $subject_id;
$_SESSION['quiz_answers'] = $answers;
$_SESSION['quiz_qids'] = array_map('intval', $qids);

// Redirect ke submit untuk menghitung & menyimpan skor
header("Location: quiz_submit.php");
exit;