<?php

// Front Controller Entry Point
require_once __DIR__ . '/../init.php';

use App\Core\App;

$app = new App();
$app->run();
