<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class AuthController extends Controller
{
    private User $userModel;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->userModel = new User();
    }

    public function loginForm(): void
    {
        // If already logged in, redirect to respective dashboard
        if (Session::has('user')) {
            $user = Session::get('user');
            if ($user['role'] === 'guru') {
                $this->redirect('/guru/dashboard');
            } else {
                $this->redirect('/siswa/dashboard');
            }
        }

        $this->render('auth.login', ['title' => 'Login - MathMagic'], 'auth');
    }

    public function loginProcess(): void
    {
        $email = trim($this->request->input('email', ''));
        $password = trim($this->request->input('password', ''));

        if (empty($email) || empty($password)) {
            Session::setFlash('error', 'Silakan masukkan email dan kata sandi.');
            $this->redirect('/login');
            return;
        }

        $user = $this->userModel->attempt($email, $password);

        if (!$user) {
            Session::setFlash('error', 'Email atau kata sandi tidak cocok.');
            $this->redirect('/login');
            return;
        }

        if (!$user['is_active']) {
            Session::setFlash('error', 'Akun Anda sedang dinonaktifkan.');
            $this->redirect('/login');
            return;
        }

        // Store user in session (omit password hash)
        unset($user['password_hash']);
        Session::set('user', $user);
        Session::set('user_id', $user['id']);
        Session::set('role', $user['role']);
        Session::set('fullname', $user['fullname']);

        Session::setFlash('success', "Selamat datang kembali, {$user['fullname']}! ✨");

        if ($user['role'] === 'guru') {
            $this->redirect('/guru/dashboard');
        } else {
            $this->redirect('/siswa/dashboard');
        }
    }

    public function registerForm(): void
    {
        if (Session::has('user')) {
            $this->redirect('/');
        }

        $this->render('auth.register', ['title' => 'Daftar Akun Baru - MathMagic'], 'auth');
    }

    public function registerProcess(): void
    {
        $fullname = trim($this->request->input('fullname', ''));
        $email = trim($this->request->input('email', ''));
        $password = trim($this->request->input('password', ''));
        $confirmPassword = trim($this->request->input('confirm_password', ''));
        $role = $this->request->input('role', 'siswa');
        $kelas = $this->request->input('kelas', null);
        $mapel = $this->request->input('mapel', null);

        if (empty($fullname) || empty($email) || empty($password)) {
            Session::setFlash('error', 'Semua bidang wajib diisi.');
            $this->redirect('/register');
            return;
        }

        if ($password !== $confirmPassword) {
            Session::setFlash('error', 'Konfirmasi kata sandi tidak sesuai.');
            $this->redirect('/register');
            return;
        }

        if (strlen($password) < 6) {
            Session::setFlash('error', 'Kata sandi minimal 6 karakter.');
            $this->redirect('/register');
            return;
        }

        // Check if email already registered
        $existing = $this->userModel->findByEmail($email);
        if ($existing) {
            Session::setFlash('error', 'Email sudah terdaftar. Silakan gunakan email lain atau login.');
            $this->redirect('/register');
            return;
        }

        // Create new user
        $userId = $this->userModel->register([
            'fullname' => $fullname,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'kelas' => $role === 'siswa' ? $kelas : null,
            'mapel' => $role === 'guru' ? $mapel : null
        ]);

        $user = $this->userModel->find($userId);
        unset($user['password_hash']);

        Session::set('user', $user);
        Session::set('user_id', $user['id']);
        Session::set('role', $user['role']);
        Session::set('fullname', $user['fullname']);

        Session::setFlash('success', "Akun berhasil dibuat! Selamat datang di MathMagic 🎉");

        if ($role === 'guru') {
            $this->redirect('/guru/dashboard');
        } else {
            $this->redirect('/siswa/dashboard');
        }
    }

    public function logout(): void
    {
        Session::destroy();
        $this->redirect('/login');
    }
}
