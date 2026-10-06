<?php

use App\Controllers\AuthController;
use App\Controllers\BankSoalController;
use App\Controllers\DashboardController;
use App\Controllers\ForumController;
use App\Controllers\GameController;
use App\Controllers\LeaderboardController;
use App\Controllers\ProfileController;
use App\Controllers\QuizController;
use App\Controllers\ReportController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\GuruMiddleware;
use App\Middleware\SiswaMiddleware;

/** @var \App\Core\Router $router */

// Public / Landing
$router->get('/', [DashboardController::class, 'index']);

// Authentication Routes
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'loginProcess'], [CsrfMiddleware::class]);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'registerProcess'], [CsrfMiddleware::class]);
$router->get('/logout', [AuthController::class, 'logout']);

// Profile Routes (Any authenticated user)
$router->get('/profile', [ProfileController::class, 'index'], [AuthMiddleware::class]);
$router->post('/profile', [ProfileController::class, 'update'], [AuthMiddleware::class, CsrfMiddleware::class]);

// Forum Routes
$router->get('/forum', [ForumController::class, 'index'], [AuthMiddleware::class]);
$router->get('/forum/{id}', [ForumController::class, 'detail'], [AuthMiddleware::class]);
$router->post('/forum/new', [ForumController::class, 'createThread'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/forum/{id}/comment', [ForumController::class, 'addComment'], [AuthMiddleware::class, CsrfMiddleware::class]);

// ===================================
// SISWA ROUTES
// ===================================
$router->get('/siswa/dashboard', [DashboardController::class, 'siswaDashboard'], [AuthMiddleware::class, SiswaMiddleware::class]);

// Games
$router->get('/siswa/games', [GameController::class, 'index'], [AuthMiddleware::class, SiswaMiddleware::class]);
$router->get('/siswa/games/adventure', [GameController::class, 'adventure'], [AuthMiddleware::class, SiswaMiddleware::class]);
$router->get('/siswa/games/race', [GameController::class, 'mathRace'], [AuthMiddleware::class, SiswaMiddleware::class]);
$router->get('/siswa/games/puzzle', [GameController::class, 'puzzleAngka'], [AuthMiddleware::class, SiswaMiddleware::class]);
$router->get('/siswa/games/quiz-cepat', [GameController::class, 'quizCepat'], [AuthMiddleware::class, SiswaMiddleware::class]);

// Quiz
$router->get('/siswa/quiz', [QuizController::class, 'index'], [AuthMiddleware::class, SiswaMiddleware::class]);
$router->get('/siswa/quiz/{id}', [QuizController::class, 'solve'], [AuthMiddleware::class, SiswaMiddleware::class]);

// Bank Soal Siswa
$router->get('/siswa/banksoal', [BankSoalController::class, 'siswaIndex'], [AuthMiddleware::class, SiswaMiddleware::class]);

// Leaderboard Siswa
$router->get('/siswa/leaderboard', [LeaderboardController::class, 'siswaIndex'], [AuthMiddleware::class, SiswaMiddleware::class]);

// ===================================
// GURU ROUTES
// ===================================
$router->get('/guru/dashboard', [DashboardController::class, 'guruDashboard'], [AuthMiddleware::class, GuruMiddleware::class]);

// Bank Soal Guru
$router->get('/guru/banksoal', [BankSoalController::class, 'guruIndex'], [AuthMiddleware::class, GuruMiddleware::class]);
$router->post('/guru/banksoal/create', [BankSoalController::class, 'guruStore'], [AuthMiddleware::class, GuruMiddleware::class, CsrfMiddleware::class]);
$router->get('/guru/banksoal/delete/{id}', [BankSoalController::class, 'guruDelete'], [AuthMiddleware::class, GuruMiddleware::class]);
$router->post('/guru/banksoal/delete/{id}', [BankSoalController::class, 'guruDelete'], [AuthMiddleware::class, GuruMiddleware::class, CsrfMiddleware::class]);

// Leaderboard & Reports
$router->get('/guru/leaderboard', [LeaderboardController::class, 'guruIndex'], [AuthMiddleware::class, GuruMiddleware::class]);
$router->get('/guru/reports', [ReportController::class, 'index'], [AuthMiddleware::class, GuruMiddleware::class]);

// ===================================
// API ROUTES (JSON Response)
// ===================================
$router->post('/api/quiz/submit', [QuizController::class, 'submitQuiz'], [AuthMiddleware::class]);
$router->post('/api/game/submit', [GameController::class, 'submitResult'], [AuthMiddleware::class]);
