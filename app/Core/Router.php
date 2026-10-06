<?php

namespace App\Core;

class Router
{
    private Request $request;
    private Response $response;
    private array $routes = [];
    private string $basePrefix = '';

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;

        // Detect base path automatically from script name
        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        $scriptDir = str_replace('\\', '/', $scriptDir);
        $scriptDir = preg_replace('#/(public)$#i', '', $scriptDir);
        $scriptDir = rtrim($scriptDir, '/');
        if ($scriptDir === '.' || $scriptDir === '/') {
            $scriptDir = '';
        }
        $this->basePrefix = $scriptDir;
    }

    public function get(string $path, array|callable $callback, array $middlewares = []): void
    {
        $this->addRoute('GET', $path, $callback, $middlewares);
    }

    public function post(string $path, array|callable $callback, array $middlewares = []): void
    {
        $this->addRoute('POST', $path, $callback, $middlewares);
    }

    public function put(string $path, array|callable $callback, array $middlewares = []): void
    {
        $this->addRoute('PUT', $path, $callback, $middlewares);
    }

    public function delete(string $path, array|callable $callback, array $middlewares = []): void
    {
        $this->addRoute('DELETE', $path, $callback, $middlewares);
    }

    private function addRoute(string $method, string $path, array|callable $callback, array $middlewares = []): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => '/' . trim($path, '/'),
            'callback' => $callback,
            'middlewares' => $middlewares,
        ];
    }

    public function resolve(): void
    {
        $method = $this->request->getMethod();
        $uri = $this->request->getPath();

        // Strip base prefix if running inside subdirectory (e.g. /mathmagic)
        if (!empty($this->basePrefix) && str_starts_with($uri, $this->basePrefix)) {
            $uri = substr($uri, strlen($this->basePrefix));
        }

        // Also handle if url has /public in it
        if (str_starts_with($uri, '/public')) {
            $uri = substr($uri, 7);
        }

        $uri = '/' . trim($uri, '/');
        if ($uri === '//') {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            // Convert route pattern with {param} to regex
            $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                // Execute Middlewares
                foreach ($route['middlewares'] as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    $middleware->handle($this->request, $this->response);
                }

                // Filter named string keys for params
                $params = array_filter($matches, function ($k) {
                    return !is_numeric($k);
                }, ARRAY_FILTER_USE_KEY);

                $callback = $route['callback'];

                if (is_callable($callback)) {
                    call_user_func_array($callback, [$this->request, $this->response, ...array_values($params)]);
                    return;
                }

                if (is_array($callback)) {
                    [$controllerClass, $action] = $callback;
                    $controller = new $controllerClass($this->request, $this->response);
                    call_user_func_array([$controller, $action], array_values($params));
                    return;
                }
            }
        }

        // 404 Not Found
        if ($this->request->isJson() || str_starts_with($uri, '/api')) {
            $this->response->json([
                'status' => 'error',
                'message' => 'Endpoint tidak ditemukan (404 Not Found)',
                'path' => $uri
            ], 404);
        } else {
            $this->response->setStatusCode(404);
            echo "<div style='font-family:sans-serif; text-align:center; padding:50px;'><h1>404 Not Found</h1><p>Halaman <code>" . htmlspecialchars($uri) . "</code> tidak ditemukan.</p><a href='" . (require __DIR__ . '/../../config/app.php')['url'] . "'>Kembali ke Beranda</a></div>";
        }
    }
}
