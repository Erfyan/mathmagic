<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Badge;
use App\Models\StudentStat;

class LeaderboardController extends Controller
{
    private StudentStat $statModel;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->statModel = new StudentStat();
    }

    public function siswaIndex(): void
    {
        $user = Session::get('user');
        $leaderboard = $this->statModel->getLeaderboard(20);

        $badgeModel = new Badge();
        $myBadges = $badgeModel->getStudentBadges((int)$user['id']);

        $this->render('siswa.leaderboard.index', [
            'title' => 'Papan Peringkat Juara - MathMagic',
            'user' => $user,
            'leaderboard' => $leaderboard,
            'myBadges' => $myBadges
        ], 'siswa');
    }

    public function guruIndex(): void
    {
        $user = Session::get('user');
        $leaderboard = $this->statModel->getLeaderboard(50);

        $this->render('guru.leaderboard.index', [
            'title' => 'Papan Prestasi Siswa - MathMagic',
            'user' => $user,
            'leaderboard' => $leaderboard
        ], 'guru');
    }
}
