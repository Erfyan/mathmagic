<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Badge;
use App\Models\GameResult;
use App\Models\QuestionBank;
use App\Models\Quiz;
use App\Models\StudentLevel;
use App\Models\StudentStat;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(): void
    {
        if (Session::has('user')) {
            $user = Session::get('user');
            if ($user['role'] === 'guru') {
                $this->redirect('/guru/dashboard');
            } else {
                $this->redirect('/siswa/dashboard');
            }
        }

        $this->render('home', ['title' => 'MathMagic - Platform Belajar Matematika Interaktif'], null);
    }

    public function siswaDashboard(): void
    {
        $user = Session::get('user');
        $studentId = (int)$user['id'];

        $statModel = new StudentStat();
        $stats = $statModel->getByStudentId($studentId);

        $levelModel = new StudentLevel();
        $levelData = $levelModel->getStudentLevel($studentId);

        $badgeModel = new Badge();
        $badges = $badgeModel->getStudentBadges($studentId);

        $gameResultModel = new GameResult();
        $recentGames = $gameResultModel->getStudentHistory($studentId, 5);

        $quizModel = new Quiz();
        $recentQuizzes = $quizModel->getStudentQuizResults($studentId);

        $this->render('siswa.dashboard', [
            'title' => 'Dashboard Siswa - MathMagic',
            'user' => $user,
            'stats' => $stats,
            'levelData' => $levelData,
            'badges' => $badges,
            'recentGames' => $recentGames,
            'recentQuizzes' => $recentQuizzes
        ], 'siswa');
    }

    public function guruDashboard(): void
    {
        $user = Session::get('user');
        $userModel = new User();
        $students = $userModel->getStudents();

        $qbModel = new QuestionBank();
        $totalQuestions = count($qbModel->all());

        $quizModel = new Quiz();
        $totalQuizzes = count($quizModel->all());

        $statModel = new StudentStat();
        $leaderboard = $statModel->getLeaderboard(5);

        $this->render('guru.dashboard', [
            'title' => 'Panel Guru - MathMagic',
            'user' => $user,
            'totalStudents' => count($students),
            'totalQuestions' => $totalQuestions,
            'totalQuizzes' => $totalQuizzes,
            'leaderboard' => $leaderboard,
            'students' => array_slice($students, 0, 5)
        ], 'guru');
    }
}
