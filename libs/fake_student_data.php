<?php
// ==========================================
// FAKE / DUMMY DATA UNTUK DASHBOARD SISWA
// ==========================================

$FAKE_STATS = [
    "total_points"      => 0,
    "total_quizzes"     => 0,
    "total_games"       => 0,
    "level"             => 1,
    "progress_percent"  => 0,
    "avg_score"         => 0,
    "weekly_progress"   => json_encode([
        ["day" => "Senin", "value" => 0],
        ["day" => "Selasa", "value" => 0],
        ["day" => "Rabu", "value" => 0],
        ["day" => "Kamis", "value" => 0],
        ["day" => "Jumat", "value" => 0],
        ["day" => "Sabtu", "value" => 0],
        ["day" => "Minggu", "value" => 0],
    ]),
    "last_activity"     => "-"
];

$FAKE_HISTORY = [
    [
        "quiz_title" => "Belum ada data",
        "score"      => 0,
        "created_at" => "-"
    ]
];
