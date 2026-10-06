<?php

namespace App\Models;

use App\Core\Model;

class ForumThread extends Model
{
    protected string $table = 'forum_threads';

    public function getAllWithAuthor(): array
    {
        $sql = "SELECT t.*, u.fullname, u.role, u.avatar,
                (SELECT COUNT(*) FROM forum_comments c WHERE c.thread_id = t.id) as total_comments
                FROM forum_threads t
                LEFT JOIN users u ON t.created_by = u.id
                ORDER BY t.created_at DESC";
        return $this->query($sql);
    }

    public function getThreadDetail(int $id): ?array
    {
        $sql = "SELECT t.*, u.fullname, u.role, u.avatar
                FROM forum_threads t
                LEFT JOIN users u ON t.created_by = u.id
                WHERE t.id = :id
                LIMIT 1";
        $res = $this->query($sql, ['id' => $id]);
        return !empty($res) ? $res[0] : null;
    }

    public function getComments(int $threadId): array
    {
        $sql = "SELECT c.*, u.fullname, u.role, u.avatar
                FROM forum_comments c
                LEFT JOIN users u ON c.created_by = u.id
                WHERE c.thread_id = :thread_id
                ORDER BY c.created_at ASC";
        return $this->query($sql, ['thread_id' => $threadId]);
    }

    public function addComment(int $threadId, int $userId, string $role, string $comment): int|string
    {
        $stmt = $this->db->prepare("INSERT INTO forum_comments (thread_id, comment, created_by, role, created_at) VALUES (:thread_id, :comment, :created_by, :role, :created_at)");
        $stmt->execute([
            'thread_id' => $threadId,
            'comment' => $comment,
            'created_by' => $userId,
            'role' => $role,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        return $this->db->lastInsertId();
    }
}
