<?php

namespace App\Models;

use App\Core\Model;

class StudentStat extends Model
{
    protected string $table = 'student_stats';

    public function initStats(int $studentId): void
    {
        $existing = $this->firstWhere(['student_id' => $studentId]);
        if (!$existing) {
            $this->insert([
                'student_id' => $studentId,
                'total_points' => 0,
                'total_quizzes' => 0,
                'total_games' => 0,
                'correct_answers' => 0,
                'wrong_answers' => 0,
                'level' => 1,
                'progress_percent' => 0,
                'avg_score' => 0,
                'weekly_progress' => '[]',
                'last_activity' => date('Y-m-d H:i:s'),
                'total_quiz_taken' => 0,
                'weekly_points' => 0
            ]);
        }
    }

    public function getByStudentId(int $studentId): array
    {
        $stat = $this->firstWhere(['student_id' => $studentId]);
        if (!$stat) {
            $this->initStats($studentId);
            $stat = $this->firstWhere(['student_id' => $studentId]);
        }
        return $stat ?: [];
    }

    public function addGamePoints(int $studentId, int $points): void
    {
        $stat = $this->getByStudentId($studentId);
        $newPoints = ($stat['total_points'] ?? 0) + $points;
        $totalGames = ($stat['total_games'] ?? 0) + 1;
        $weeklyPoints = ($stat['weekly_points'] ?? 0) + $points;

        // Calculate Level & Progress % (Level up every 100 XP)
        $level = max(1, floor($newPoints / 100) + 1);
        $progress = ($newPoints % 100);

        $this->update($stat['id'], [
            'total_points' => $newPoints,
            'total_games' => $totalGames,
            'weekly_points' => $weeklyPoints,
            'level' => $level,
            'progress_percent' => $progress,
            'last_activity' => date('Y-m-d H:i:s')
        ]);
    }

    public function recordQuizResult(int $studentId, int $score, int $correct, int $wrong): void
    {
        $stat = $this->getByStudentId($studentId);
        $totalQuizzes = ($stat['total_quizzes'] ?? 0) + 1;
        $totalCorrect = ($stat['correct_answers'] ?? 0) + $correct;
        $totalWrong = ($stat['wrong_answers'] ?? 0) + $wrong;
        $newPoints = ($stat['total_points'] ?? 0) + $score;

        // Calculate new average
        $totalQuestions = $totalCorrect + $totalWrong;
        $avgScore = $totalQuestions > 0 ? round(($totalCorrect / $totalQuestions) * 100, 1) : 0;

        $level = max(1, floor($newPoints / 100) + 1);
        $progress = ($newPoints % 100);

        $this->update($stat['id'], [
            'total_points' => $newPoints,
            'total_quizzes' => $totalQuizzes,
            'total_quiz_taken' => $totalQuizzes,
            'correct_answers' => $totalCorrect,
            'wrong_answers' => $totalWrong,
            'avg_score' => $avgScore,
            'level' => $level,
            'progress_percent' => $progress,
            'last_activity' => date('Y-m-d H:i:s')
        ]);
    }

    public function getLeaderboard(int $limit = 10): array
    {
        $sql = "SELECT u.id, u.fullname, u.kelas, u.avatar, s.total_points, s.level, s.total_games, s.total_quizzes, s.avg_score
                FROM users u
                INNER JOIN student_stats s ON u.id = s.student_id
                WHERE u.role = 'siswa' AND u.is_active = 1
                ORDER BY s.total_points DESC, s.level DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
