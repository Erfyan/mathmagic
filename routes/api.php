<?php

use App\Controllers\GameController;
use App\Controllers\QuizController;
use App\Middleware\AuthMiddleware;

/** @var \App\Core\Router $router */

// Game score sync
$router->post('/api/game/submit', [GameController::class, 'submitResult'], [AuthMiddleware::class]);

// Quiz submission
$router->post('/api/quiz/submit', [QuizController::class, 'submitQuiz'], [AuthMiddleware::class]);
