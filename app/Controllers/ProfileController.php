<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Badge;
use App\Models\StudentLevel;
use App\Models\StudentStat;
use App\Models\User;

class ProfileController extends Controller
{
    private User $userModel;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->userModel = new User();
    }

    public function index(): void
    {
        $sessionUser = Session::get('user');
        $user = $this->userModel->find($sessionUser['id']);

        $stats = [];
        $levelData = [];
        $badges = [];

        if ($user['role'] === 'siswa') {
            $stats = (new StudentStat())->getByStudentId((int)$user['id']);
            $levelData = (new StudentLevel())->getStudentLevel((int)$user['id']);
            $badges = (new Badge())->getStudentBadges((int)$user['id']);
        }

        $layout = ($user['role'] === 'guru') ? 'guru' : 'siswa';

        $this->render('profile.index', [
            'title' => 'Profil Saya - MathMagic',
            'user' => $user,
            'stats' => $stats,
            'levelData' => $levelData,
            'badges' => $badges
        ], $layout);
    }

    public function update(): void
    {
        $sessionUser = Session::get('user');
        $fullname = trim($this->request->input('fullname', ''));
        $kelas = $this->request->input('kelas', null);
        $mapel = $this->request->input('mapel', null);
        $password = trim($this->request->input('password', ''));

        if (empty($fullname)) {
            Session::setFlash('error', 'Nama lengkap tidak boleh kosong.');
            $this->redirect('/profile');
            return;
        }

        $updateData = [
            'fullname' => $fullname
        ];

        if ($sessionUser['role'] === 'siswa' && !empty($kelas)) {
            $updateData['kelas'] = $kelas;
        }

        if ($sessionUser['role'] === 'guru' && !empty($mapel)) {
            $updateData['mapel'] = $mapel;
        }

        if (!empty($password)) {
            if (strlen($password) < 6) {
                Session::setFlash('error', 'Kata sandi baru minimal 6 karakter.');
                $this->redirect('/profile');
                return;
            }
            $updateData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        // Handle Avatar upload
        $avatarFile = $this->request->file('avatar');
        if ($avatarFile && !empty($avatarFile['name']) && $avatarFile['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($avatarFile['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $newName = time() . '_' . uniqid() . '.' . $ext;
                $uploadDir = __DIR__ . '/../../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                if (move_uploaded_file($avatarFile['tmp_name'], $uploadDir . $newName)) {
                    $updateData['avatar'] = 'uploads/' . $newName;
                }
            }
        }

        $this->userModel->update($sessionUser['id'], $updateData);

        // Refresh session
        $updatedUser = $this->userModel->find($sessionUser['id']);
        unset($updatedUser['password_hash']);
        Session::set('user', $updatedUser);
        Session::set('fullname', $updatedUser['fullname']);

        Session::setFlash('success', 'Profil Anda berhasil diperbarui! ✨');
        $this->redirect('/profile');
    }
}
