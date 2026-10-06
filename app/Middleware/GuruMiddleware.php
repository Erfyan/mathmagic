<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class GuruMiddleware
{
    public function handle(Request $request, Response $response): void
    {
        $user = Session::get('user');

        if (!$user) {
            Session::setFlash('error', 'Silakan login terlebih dahulu.');
            $response->redirect('/login');
            return;
        }

        if (($user['role'] ?? '') !== 'guru') {
            if ($request->isJson()) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Akses ditolak: Hanya Guru yang dapat mengakses halaman ini.'
                ], 403);
            }

            Session::setFlash('error', 'Anda tidak memiliki hak akses sebagai Guru.');
            $response->redirect('/siswa/dashboard');
        }
    }
}
