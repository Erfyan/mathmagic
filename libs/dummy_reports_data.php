<?php
// Dummy data untuk laporan lengkap (statistik, ranking, login, quiz)

// Pastikan koneksi tersedia
if (!isset($conn)) {
    require "../config.php";
}

/* =======================================================
   1. student_stats — statistik siswa
   ======================================================= */
$checkStudentStats = $conn->query("SELECT COUNT(*) AS total FROM student_stats")->fetch_assoc()['total'];

if ($checkStudentStats == 0) {

    // Ambil seluruh siswa
    $students = $conn->query("SELECT id, kelas FROM users WHERE role='siswa'");

    while ($stu = $students->fetch_assoc()) {

        $total_points = rand(40, 100);
        $quiz_taken = rand(1, 15);

        $conn->query("
            INSERT INTO student_stats (student_id, kelas, total_points, total_quiz_taken)
            VALUES (
                {$stu['id']},
                '{$stu['kelas']}',
                $total_points,
                $quiz_taken
            )
        ");
    }
}

/* =======================================================
   2. question_bank — statistik bank soal
   ======================================================= */
$checkBank = $conn->query("SELECT COUNT(*) AS total FROM question_bank")->fetch_assoc()['total'];

if ($checkBank == 0) {

    $difficulties = ['easy','medium','hard'];

    for ($i=1; $i<=25; $i++) {

        $diff = $difficulties[array_rand($difficulties)];
        $kelas = ['7A','7B','8A','8B'][array_rand(['7A','7B','8A','8B'])];

        $correct = ['A','B','C','D'][array_rand(['A','B','C','D'])];

        $conn->query("
            INSERT INTO question_bank
            (created_by, subject_id, kelas, type, question, option_a, option_b, option_c, option_d, correct_answer, difficulty)
            VALUES (
                1,
                1,
                '$kelas',
                'mcq',
                'Soal dummy nomor $i: berapa hasil $i + $i?',
                '".($i*2)."',
                '".($i*2+1)."',
                '".($i+3)."',
                '".($i+4)."',
                'A',
                '$diff'
            )
        ");
    }
}

/* =======================================================
   3. quiz_list — daftar quiz
   ======================================================= */
$checkQuizList = $conn->query("SELECT COUNT(*) AS total FROM quiz_list")->fetch_assoc()['total'];

if ($checkQuizList == 0) {

    $conn->query("
        INSERT INTO quiz_list (subject_id, title, difficulty, total_questions)
        VALUES
        (1, 'Quiz Matematika Dasar', 'easy', 10),
        (1, 'Quiz Aljabar', 'medium', 10),
        (1, 'Quiz Geometri', 'hard', 10)
    ");
}

/* =======================================================
   4. quiz_results — performa quiz siswa
   ======================================================= */
$checkQuizResults = $conn->query("SELECT COUNT(*) AS total FROM quiz_results")->fetch_assoc()['total'];

if ($checkQuizResults == 0) {

    // ambil semua siswa
    $students = $conn->query("SELECT id FROM users WHERE role='siswa'");

    while ($stu = $students->fetch_assoc()) {

        // buatkan percobaan kuis
        for ($q=1; $q<=3; $q++) {

            $score = rand(50, 100);

            $conn->query("
                INSERT INTO quiz_results (student_id, quiz_id, score, taken_at)
                VALUES (
                    {$stu['id']},
                    $q,
                    $score,
                    NOW() - INTERVAL ".rand(1,20)." DAY
                )
            ");
        }
    }
}

/* =======================================================
   5. login_logs — riwayat login
   ======================================================= */
$checkLogs = $conn->query("SELECT COUNT(*) AS total FROM login_logs")->fetch_assoc()['total'];

if ($checkLogs == 0) {

    $users = $conn->query("SELECT id FROM users");

    while ($u = $users->fetch_assoc()) {
        for ($i=0; $i<3; $i++) {
            $conn->query("
                INSERT INTO login_logs (user_id, login_time)
                VALUES (
                    {$u['id']},
                    NOW() - INTERVAL ".rand(1,100)." HOUR
                )
            ");
        }
    }
}
?>