<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class CsrfMiddleware
{
    public function handle(Request $request, Response $response): void
    {
        $method = $request->getMethod();
        if (in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $token = $request->input('_token') ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);

            if (!Session::validateCsrfToken($token)) {
                if ($request->isJson()) {
                    $response->json([
                        'status' => 'error',
                        'message' => 'Token CSRF tidak valid atau telah kedaluwarsa.'
                    ], 419);
                }

                Session::setFlash('error', 'Validasi formulir kedaluwarsa. Silakan muat ulang halaman.');
                $response->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            }
        }
    }
}
