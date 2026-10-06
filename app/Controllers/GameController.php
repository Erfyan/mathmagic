<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Badge;
use App\Models\GameResult;
use App\Models\QuestionBank;
use App\Models\StudentLevel;
use App\Models\StudentStat;

class GameController extends Controller
{
    private GameResult $gameModel;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->gameModel = new GameResult();
    }

    public function index(): void
    {
        $user = Session::get('user');
        $studentId = (int)$user['id'];

        $statModel = new StudentStat();
        $stats = $statModel->getByStudentId($studentId);

        $badgeModel = new Badge();
        $badges = $badgeModel->getStudentBadges($studentId);

        $this->render('siswa.games.index', [
            'title' => 'Arena Game Matematika - MathMagic',
            'user' => $user,
            'stats' => $stats,
            'badges' => $badges
        ], 'siswa');
    }

    public function adventure(): void
    {
        $user = Session::get('user');
        $this->render('siswa.games.adventure', [
            'title' => 'Math Adventure - MathMagic',
            'user' => $user
        ], 'siswa');
    }

    public function mathRace(): void
    {
        $user = Session::get('user');
        $this->render('siswa.games.math_race', [
            'title' => 'Math Race - Balap Hitung Cepat',
            'user' => $user
        ], 'siswa');
    }

    public function puzzleAngka(): void
    {
        $user = Session::get('user');
        $this->render('siswa.games.puzzle_angka', [
            'title' => 'Puzzle Logika Angka - MathMagic',
            'user' => $user
        ], 'siswa');
    }

    public function quizCepat(): void
    {
        $user = Session::get('user');
        $this->render('siswa.games.quiz_cepat', [
            'title' => 'Quiz Cepat - Mode Kilat',
            'user' => $user
        ], 'siswa');
    }

    public function submitResult(): void
    {
        $user = Session::get('user');
        if (!$user) {
            $this->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
            return;
        }

        $studentId = (int)$user['id'];
        $gameName = trim($this->request->input('game_name', 'Math Challenge'));
        $points = max(0, (int)$this->request->input('points', 0));
        $timeSpent = max(0, (int)$this->request->input('time_spent', 0));

        $result = $this->gameModel->recordGame($studentId, $gameName, $points, $timeSpent);

        $this->json([
            'status' => 'success',
            'message' => 'Skor game berhasil dicatat!',
            'data' => $result
        ]);
    }
}
