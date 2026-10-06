<?php

namespace App\Core;

class App
{
    private Router $router;
    private Request $request;
    private Response $response;

    public function __construct()
    {
        // Set Timezone & Error Handling
        $config = require __DIR__ . '/../../config/app.php';
        date_default_timezone_set($config['timezone'] ?? 'Asia/Jakarta');

        if ($config['debug'] ?? true) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(0);
            ini_set('display_errors', '0');
        }

        Session::start();

        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function run(): void
    {
        // Load Web & API routes
        $router = $this->router;
        require __DIR__ . '/../../routes/web.php';
        require __DIR__ . '/../../routes/api.php';

        $this->router->resolve();
    }
}
