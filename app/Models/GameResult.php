<?php

namespace App\Models;

use App\Core\Model;

class GameResult extends Model
{
    protected string $table = 'game_results';

    public function recordGame(int $studentId, string $gameName, int $points, int $timeSpent = 0): array
    {
        $id = $this->insert([
            'student_id' => $studentId,
            'game_name' => $gameName,
            'points' => $points,
            'time_spent' => $timeSpent,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Update Student Stats
        $statModel = new StudentStat();
        $statModel->addGamePoints($studentId, $points);

        // Update Student Level & XP
        $levelModel = new StudentLevel();
        $levelProgress = $levelModel->addExp($studentId, $points);

        // Check & Award Badges
        $badgeModel = new Badge();
        $unlockedBadges = $badgeModel->checkAndAward($studentId, $gameName, $points);

        return [
            'result_id' => $id,
            'points' => $points,
            'level_progress' => $levelProgress,
            'unlocked_badges' => $unlockedBadges
        ];
    }

    public function getStudentHistory(int $studentId, int $limit = 10): array
    {
        return $this->where(['student_id' => $studentId], 'created_at DESC', $limit);
    }
}
