<?php
// =====================================================
//  SEED DATA DUMMY MATHMAGIC (FINAL & TANPA ERROR)
// =====================================================

if (!isset($conn)) {
    die("File ini harus dipanggil dari sistem utama (dashboard / index)");
}

// =====================================================
//  HELPER - CEK APAKAH TABEL ADA
// =====================================================
function table_exists($conn, $table) {
    $res = $conn->query("SHOW TABLES LIKE '$table'");
    return ($res && $res->num_rows > 0);
}

// =====================================================
//  HELPER - CEK APAKAH TABEL KOSONG
// ===================================================== 
function table_is_empty($conn, $table) {
    if (!table_exists($conn, $table)) return true;
    $q = $conn->query("SELECT COUNT(*) AS c FROM $table");
    if (!$q) return true;
    return ($q->fetch_assoc()['c'] == 0);
}

// =====================================================
// 1. SEED SUBJECTS (JIKA KOSONG)
// =====================================================
if (table_exists($conn, "subjects") && table_is_empty($conn, "subjects")) {

    $conn->query("
        INSERT INTO subjects (code, name, description) VALUES
        ('MTK', 'Matematika', 'Materi dasar matematika'),
        ('ALG', 'Aljabar', 'Persamaan & variabel'),
        ('GEO', 'Geometri', 'Bangun ruang & datar')
    ");
}

// =====================================================
// 2. SEED BADGES (TABEL: badges)
// struktur: id, student_id, badge_key, title, description, awarded_at
// =====================================================
if (table_exists($conn, "badges") && table_is_empty($conn, "badges")) {

    $qUsers = $conn->query("SELECT id FROM users WHERE role='siswa'");
    if ($qUsers && $qUsers->num_rows > 0) {
        while ($u = $qUsers->fetch_assoc()) {
            $sid = $u['id'];

            $conn->query("
                INSERT INTO badges (student_id, badge_key, title, description, awarded_at)
                VALUES
                ($sid, 'fast_solver', 'Pemecah Soal Cepat', 'Menyelesaikan soal dengan cepat', NOW()),
                ($sid, 'weekly_champ', 'Juara Mingguan', 'Mendapat poin mingguan tertinggi', NOW()),
                ($sid, 'consistent', 'Belajar Konsisten', 'Aktif belajar setiap minggu', NOW())
            ");
        }
    }
}

// =====================================================
// 3. SEED STUDENT_LEVEL
// struktur: id, student_id, subject_id, level, exp, total_score, updated_at
// =====================================================
if (table_exists($conn, "student_level") && table_is_empty($conn, "student_level")) {

    $subjects = $conn->query("SELECT id FROM subjects");
    $users = $conn->query("SELECT id FROM users WHERE role='siswa'");

    if ($subjects && $users) {
        while ($stu = $users->fetch_assoc()) {
            $sid = $stu['id'];

            $subjects->data_seek(0);
            while ($sub = $subjects->fetch_assoc()) {

                $subject_id = intval($sub['id']);
                
                $conn->query("
                    INSERT INTO student_level (student_id, subject_id, level, exp, total_score)
                    VALUES ($sid, $subject_id, FLOOR(RAND()*5)+1, FLOOR(RAND()*150), FLOOR(RAND()*200))
                ");
            }
        }
    }
}

// =====================================================
// 4. SEED STUDENT_STATS
// =====================================================
if (table_exists($conn, "student_stats") && table_is_empty($conn, "student_stats")) {

    $users = $conn->query("SELECT id FROM users WHERE role='siswa'");

    if ($users) {
        while ($u = $users->fetch_assoc()) {
            $sid = $u['id'];

            $conn->query("
                INSERT INTO student_stats (
                    student_id, total_points, total_quizzes, total_games,
                    correct_answers, wrong_answers, level, progress_percent,
                    avg_score, weekly_progress, last_activity, total_quiz_taken, weekly_points
                )
                VALUES (
                    $sid,
                    FLOOR(RAND()*200),
                    FLOOR(RAND()*10),
                    FLOOR(RAND()*5),
                    FLOOR(RAND()*50),
                    FLOOR(RAND()*20),
                    FLOOR(RAND()*5)+1,
                    FLOOR(RAND()*100),
                    FLOOR(RAND()*100),
                    '[]',
                    NOW(),
                    FLOOR(RAND()*10),
                    FLOOR(RAND()*50)
                )
            ");
        }
    }
}

// =====================================================
// 5. SEED STUDENT_WEAK_TOPICS
// =====================================================
if (table_exists($conn, "student_weak_topics") && table_is_empty($conn, "student_weak_topics")) {

    $users = $conn->query("SELECT id FROM users WHERE role='siswa'");

    if ($users) {
        while ($u = $users->fetch_assoc()) {
            $sid = $u['id'];

            $conn->query("
                INSERT INTO student_weak_topics (student_id, question, wrong_count)
                VALUES
                ($sid, 'Persamaan Linear', FLOOR(RAND()*7)+3),
                ($sid, 'Bangun Datar', FLOOR(RAND()*7)+3),
                ($sid, 'Aritmatika Sosial', FLOOR(RAND()*7)+3)
            ");
        }
    }
}

// =====================================================
// 6. SEED STUDENT_SLOW_TOPICS
// =====================================================
if (table_exists($conn, "student_slow_topics") && table_is_empty($conn, "student_slow_topics")) {

    $users = $conn->query("SELECT id FROM users WHERE role='siswa'");

    if ($users) {
        while ($u = $users->fetch_assoc()) {
            $sid = $u['id'];

            $conn->query("
                INSERT INTO student_slow_topics (student_id, question, avg_time)
                VALUES
                ($sid, 'Aritmatika Sosial', FLOOR(RAND()*40)+20),
                ($sid, 'Pecahan', FLOOR(RAND()*40)+20),
                ($sid, 'Statistika Dasar', FLOOR(RAND()*40)+20)
            ");
        }
    }
}

// =====================================================
// 7. SEED STUDENT_SCORE_HISTORY
// =====================================================
if (table_exists($conn, "student_score_history") && table_is_empty($conn, "student_score_history")) {

    $users = $conn->query("SELECT id FROM users WHERE role='siswa'");

    if ($users) {
        while ($u = $users->fetch_assoc()) {
            $sid = $u['id'];

            for ($i=1; $i<=6; $i++) {
                $conn->query("
                    INSERT INTO student_score_history (student_id, score, week_label)
                    VALUES ($sid, FLOOR(RAND()*40)+60, 'M$i')
                ");
            }
        }
    }
}

?>
