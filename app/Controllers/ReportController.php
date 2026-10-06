<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\StudentStat;
use App\Models\User;

class ReportController extends Controller
{
    private StudentStat $statModel;
    private User $userModel;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->statModel = new StudentStat();
        $this->userModel = new User();
    }

    public function index(): void
    {
        $user = Session::get('user');
        $students = $this->userModel->getStudents();
        $leaderboard = $this->statModel->getLeaderboard(100);

        // Calculate class summary stats
        $totalStudents = count($students);
        $totalQuizzes = 0;
        $totalGames = 0;
        $totalPoints = 0;

        foreach ($leaderboard as $row) {
            $totalQuizzes += (int)($row['total_quizzes'] ?? 0);
            $totalGames += (int)($row['total_games'] ?? 0);
            $totalPoints += (int)($row['total_points'] ?? 0);
        }

        $avgScore = $totalStudents > 0 ? round($totalPoints / $totalStudents, 1) : 0;

        $this->render('guru.reports.index', [
            'title' => 'Laporan & Analitik Siswa - MathMagic',
            'user' => $user,
            'students' => $students,
            'leaderboard' => $leaderboard,
            'totalStudents' => $totalStudents,
            'totalQuizzes' => $totalQuizzes,
            'totalGames' => $totalGames,
            'avgScore' => $avgScore
        ], 'guru');
    }
}
