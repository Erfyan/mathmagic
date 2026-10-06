<?php

namespace App\Models;

use App\Core\Model;

class QuestionBank extends Model
{
    protected string $table = 'question_bank';

    public function getByFilter(array $filters = []): array
    {
        $sql = "SELECT q.*, s.name as subject_name, u.fullname as creator_name 
                FROM question_bank q
                LEFT JOIN subjects s ON q.subject_id = s.id
                LEFT JOIN users u ON q.created_by = u.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['kelas'])) {
            $sql .= " AND q.kelas = :kelas";
            $params['kelas'] = $filters['kelas'];
        }

        if (!empty($filters['subject_id'])) {
            $sql .= " AND q.subject_id = :subject_id";
            $params['subject_id'] = $filters['subject_id'];
        }

        if (!empty($filters['difficulty'])) {
            $sql .= " AND q.difficulty = :difficulty";
            $params['difficulty'] = $filters['difficulty'];
        }

        $sql .= " ORDER BY q.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getRandomQuestions(string $kelas = 'SMP', int $limit = 10): array
    {
        $sql = "SELECT * FROM question_bank WHERE kelas = :kelas OR :kelas = 'ALL' ORDER BY RAND() LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':kelas', $kelas);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
