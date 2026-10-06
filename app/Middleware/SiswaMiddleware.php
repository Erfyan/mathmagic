<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class SiswaMiddleware
{
    public function handle(Request $request, Response $response): void
    {
        $user = Session::get('user');

        if (!$user) {
            Session::setFlash('error', 'Silakan login terlebih dahulu.');
            $response->redirect('/login');
            return;
        }

        if (($user['role'] ?? '') !== 'siswa') {
            if ($request->isJson()) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Akses ditolak: Halaman ini khusus untuk Siswa.'
                ], 403);
            }

            Session::setFlash('error', 'Anda diarahkan ke panel Guru.');
            $response->redirect('/guru/dashboard');
        }
    }
}
