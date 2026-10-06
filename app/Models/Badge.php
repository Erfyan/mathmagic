<?php

namespace App\Models;

use App\Core\Model;

class Badge extends Model
{
    protected string $table = 'badges';

    // List of predefined badges
    public static array $AVAILABLE_BADGES = [
        'first_game' => [
            'title' => 'Langkah Pertama',
            'description' => 'Menyelesaikan permainan pertamamu di MathMagic'
        ],
        'speed_demon' => [
            'title' => 'Kilat Matematika',
            'description' => 'Menjawab 5 pertanyaan benar berturut-turut dalam waktu singkat'
        ],
        'centurion' => [
            'title' => 'Raja Skor',
            'description' => 'Mengumpulkan lebih dari 100 poin dalam satu sesi'
        ],
        'puzzle_master' => [
            'title' => 'Master Teka-Teki',
            'description' => 'Menyelesaikan teka-teki logika angka dengan sempurna'
        ],
        'race_champion' => [
            'title' => 'Juara Pacuan Hitung',
            'description' => 'Memenangkan balapan hitung cepat'
        ],
        'adventure_hero' => [
            'title' => 'Pahlawan Aljabar',
            'description' => 'Menuntaskan petualangan Math Adventure'
        ]
    ];

    public function getStudentBadges(int $studentId): array
    {
        return $this->where(['student_id' => $studentId], 'awarded_at DESC');
    }

    public function hasBadge(int $studentId, string $badgeKey): bool
    {
        $existing = $this->firstWhere(['student_id' => $studentId, 'badge_key' => $badgeKey]);
        return $existing !== null;
    }

    public function award(int $studentId, string $badgeKey): ?array
    {
        if ($this->hasBadge($studentId, $badgeKey)) {
            return null; // Already awarded
        }

        $badgeInfo = self::$AVAILABLE_BADGES[$badgeKey] ?? [
            'title' => ucwords(str_replace('_', ' ', $badgeKey)),
            'description' => 'Pencapaian luar biasa di MathMagic'
        ];

        $this->insert([
            'student_id' => $studentId,
            'badge_key' => $badgeKey,
            'title' => $badgeInfo['title'],
            'description' => $badgeInfo['description'],
            'awarded_at' => date('Y-m-d H:i:s')
        ]);

        return [
            'key' => $badgeKey,
            'title' => $badgeInfo['title'],
            'description' => $badgeInfo['description']
        ];
    }

    public function checkAndAward(int $studentId, string $gameName, int $points): array
    {
        $newBadges = [];

        // Badge: First game
        if (!$this->hasBadge($studentId, 'first_game')) {
            $b = $this->award($studentId, 'first_game');
            if ($b) $newBadges[] = $b;
        }

        // Badge: Centurion
        if ($points >= 100 && !$this->hasBadge($studentId, 'centurion')) {
            $b = $this->award($studentId, 'centurion');
            if ($b) $newBadges[] = $b;
        }

        // Game specific badges
        if (stripos($gameName, 'adventure') !== false && $points >= 50) {
            $b = $this->award($studentId, 'adventure_hero');
            if ($b) $newBadges[] = $b;
        }

        if (stripos($gameName, 'race') !== false && $points >= 50) {
            $b = $this->award($studentId, 'race_champion');
            if ($b) $newBadges[] = $b;
        }

        if (stripos($gameName, 'puzzle') !== false && $points >= 50) {
            $b = $this->award($studentId, 'puzzle_master');
            if ($b) $newBadges[] = $b;
        }

        return $newBadges;
    }
}
