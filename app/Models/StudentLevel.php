<?php

namespace App\Models;

use App\Core\Model;

class StudentLevel extends Model
{
    protected string $table = 'student_levels';

    public function initLevel(int $studentId, int $subjectId = 1): void
    {
        $existing = $this->firstWhere(['student_id' => $studentId, 'subject_id' => $subjectId]);
        if (!$existing) {
            $this->insert([
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'level' => 1,
                'exp' => 0,
                'total_score' => 0
            ]);
        }
    }

    public function getStudentLevel(int $studentId, int $subjectId = 1): array
    {
        $res = $this->firstWhere(['student_id' => $studentId, 'subject_id' => $subjectId]);
        if (!$res) {
            $this->initLevel($studentId, $subjectId);
            $res = $this->firstWhere(['student_id' => $studentId, 'subject_id' => $subjectId]);
        }
        return $res ?: ['level' => 1, 'exp' => 0, 'total_score' => 0];
    }

    public function addExp(int $studentId, int $expGain, int $subjectId = 1): array
    {
        $curr = $this->getStudentLevel($studentId, $subjectId);
        $newExp = ($curr['exp'] ?? 0) + $expGain;
        $newScore = ($curr['total_score'] ?? 0) + $expGain;

        $oldLevel = $curr['level'] ?? 1;
        $newLevel = max(1, floor($newExp / 100) + 1);
        $isLevelUp = $newLevel > $oldLevel;

        $this->update($curr['id'], [
            'exp' => $newExp,
            'level' => $newLevel,
            'total_score' => $newScore
        ]);

        return [
            'is_level_up' => $isLevelUp,
            'old_level' => $oldLevel,
            'new_level' => $newLevel,
            'current_exp' => $newExp % 100,
            'total_exp' => $newExp
        ];
    }
}
