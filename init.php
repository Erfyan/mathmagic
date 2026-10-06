<?php

// ==========================================
// MATHMAGIC PSR-4 AUTOLOADER & GLOBAL HELPERS
// ==========================================

spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/app/';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // Check libs folder for legacy compatibility
        $libFile = __DIR__ . '/libs/' . $class . '.php';
        if (file_exists($libFile)) {
            require_once $libFile;
        }
        return;
    }

    // Get relative class name
    $relativeClass = substr($class, $len);

    // Replace namespace separators with directory separators and append .php
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Helper: Get Base URL
function base_url(string $path = ''): string
{
    static $baseUrl = null;
    if ($baseUrl === null) {
        if (!empty($_SERVER['HTTP_HOST'])) {
            $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' && $_SERVER['HTTPS'] !== 'disabled') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            
            $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
            $scriptDir = str_replace('\\', '/', $scriptDir);
            $scriptDir = preg_replace('#/(public)$#i', '', $scriptDir);
            $scriptDir = rtrim($scriptDir, '/');
            if ($scriptDir === '.' || $scriptDir === '/') {
                $scriptDir = '';
            }
            
            $baseUrl = $scheme . '://' . $host . $scriptDir;
        } else {
            $config = require __DIR__ . '/config/app.php';
            $baseUrl = rtrim($config['url'], '/');
        }
    }
    return $path ? $baseUrl . '/' . ltrim($path, '/') : $baseUrl;
}

// Helper: Get Asset URL
function asset(string $path = ''): string
{
    return base_url('public/assets/' . ltrim($path, '/'));
}

// Helper: Get Uploaded file URL
function upload_url(string $path = ''): string
{
    return base_url('public/uploads/' . ltrim($path, '/'));
}

// Helper: Escape HTML string (XSS Prevention)
function e(?string $string): string
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Helper: Get Authenticated User
function auth(): ?array
{
    return \App\Core\Session::get('user');
}

// Helper: Get Old Input value
function old(string $key, mixed $default = ''): mixed
{
    return $_POST[$key] ?? $default;
}

// Helper: CSRF Field
function csrf_field(): string
{
    $token = \App\Core\Session::generateCsrfToken();
    return '<input type="hidden" name="_token" value="' . e($token) . '">';
}
