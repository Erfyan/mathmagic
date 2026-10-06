<?php

namespace App\Core;

abstract class Controller
{
    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    protected function render(string $view, array $data = [], ?string $layout = 'app'): void
    {
        // Extract data variables to view scope
        extract($data);

        // Flash message helper
        $flashSuccess = Session::getFlash('success');
        $flashError = Session::getFlash('error');
        $flashInfo = Session::getFlash('info');

        // Current authenticated user
        $user = Session::get('user');
        $csrfToken = Session::generateCsrfToken();

        // View file path
        $viewPath = __DIR__ . '/../../resources/views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            die("View [{$view}] not found at {$viewPath}");
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        if ($layout) {
            $layoutPath = __DIR__ . '/../../resources/views/layouts/' . $layout . '.php';
            if (!file_exists($layoutPath)) {
                die("Layout [{$layout}] not found at {$layoutPath}");
            }
            require $layoutPath;
        } else {
            echo $content;
        }
    }

    protected function json(mixed $data, int $statusCode = 200): void
    {
        $this->response->json($data, $statusCode);
    }

    protected function redirect(string $url): void
    {
        $this->response->redirect($url);
    }
}
