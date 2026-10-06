<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(Request $request, Response $response): void
    {
        if (!Session::has('user')) {
            if ($request->isJson()) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Sesi login telah berakhir atau tidak valid. Silakan login kembali.'
                ], 401);
            }

            Session::setFlash('error', 'Silakan login terlebih dahulu untuk mengakses halaman tersebut.');
            $response->redirect('/login');
        }
    }
}
