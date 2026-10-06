<?php

namespace App\Models;

use App\Core\Model;

class Quiz extends Model
{
    protected string $table = 'quiz_list';

    public function getQuizzesWithSubject(): array
    {
        $sql = "SELECT q.*, COALESCE(s.name, 'Umum') as subject_name 
                FROM quiz_list q
                LEFT JOIN subjects s ON q.subject_id = s.id
                ORDER BY q.id DESC";
        return $this->query($sql);
    }

    public function saveQuizResult(int $studentId, string $quizTitle, int $score, int $correct = 0, int $wrong = 0): array
    {
        $stmt = $this->db->prepare("INSERT INTO quiz_results (student_id, quiz_title, score, created_at) VALUES (:student_id, :quiz_title, :score, :created_at)");
        $stmt->execute([
            'student_id' => $studentId,
            'quiz_title' => $quizTitle,
            'score' => $score,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $resultId = $this->db->lastInsertId();

        // Update stats and gamification
        $statModel = new StudentStat();
        $statModel->recordQuizResult($studentId, $score, $correct, $wrong);

        $levelModel = new StudentLevel();
        $levelProgress = $levelModel->addExp($studentId, $score);

        $badgeModel = new Badge();
        $newBadges = [];
        if ($score >= 80) {
            $b = $badgeModel->award($studentId, 'speed_demon');
            if ($b) $newBadges[] = $b;
        }

        return [
            'result_id' => $resultId,
            'score' => $score,
            'level_progress' => $levelProgress,
            'unlocked_badges' => $newBadges
        ];
    }

    public function getStudentQuizResults(int $studentId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM quiz_results WHERE student_id = :student_id ORDER BY created_at DESC");
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetchAll();
    }
}
