<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        return $this->firstWhere(['email' => $email]);
    }

    public function attempt(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);
        if (!$user) {
            return null;
        }

        // Verify Bcrypt password hash
        if (password_verify($password, $user['password_hash'])) {
            // Update last_login
            $this->update($user['id'], [
                'last_login' => date('Y-m-d H:i:s')
            ]);
            return $user;
        }

        return null;
    }

    public function register(array $data): int|string
    {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        unset($data['password'], $data['confirm_password'], $data['_token']);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['is_active'] = 1;

        $userId = $this->insert($data);

        // If registered as student, initialize gamification stats & level
        if (($data['role'] ?? 'siswa') === 'siswa') {
            (new StudentStat())->initStats((int)$userId);
            (new StudentLevel())->initLevel((int)$userId);
        }

        return $userId;
    }

    public function getStudents(): array
    {
        return $this->where(['role' => 'siswa'], 'id DESC');
    }

    public function getTeachers(): array
    {
        return $this->where(['role' => 'guru'], 'id DESC');
    }
}
