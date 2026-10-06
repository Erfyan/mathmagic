<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\ForumThread;

class ForumController extends Controller
{
    private ForumThread $forumModel;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->forumModel = new ForumThread();
    }

    public function index(): void
    {
        $user = Session::get('user');
        $threads = $this->forumModel->getAllWithAuthor();

        $layout = ($user['role'] === 'guru') ? 'guru' : 'siswa';

        $this->render('forum.index', [
            'title' => 'Forum Diskusi Matematika - MathMagic',
            'user' => $user,
            'threads' => $threads
        ], $layout);
    }

    public function detail(string $id): void
    {
        $user = Session::get('user');
        $thread = $this->forumModel->getThreadDetail((int)$id);

        if (!$thread) {
            Session::setFlash('error', 'Topik diskusi tidak ditemukan.');
            $this->redirect('/forum');
            return;
        }

        $comments = $this->forumModel->getComments((int)$id);
        $layout = ($user['role'] === 'guru') ? 'guru' : 'siswa';

        $this->render('forum.detail', [
            'title' => $thread['title'] . ' - Forum MathMagic',
            'user' => $user,
            'thread' => $thread,
            'comments' => $comments
        ], $layout);
    }

    public function createThread(): void
    {
        $user = Session::get('user');
        $title = trim($this->request->input('title', ''));
        $content = trim($this->request->input('content', ''));

        if (empty($title) || empty($content)) {
            Session::setFlash('error', 'Judul dan isi topik tidak boleh kosong.');
            $this->redirect('/forum');
            return;
        }

        $id = $this->forumModel->insert([
            'title' => $title,
            'content' => $content,
            'created_by' => $user['id'],
            'role' => $user['role'],
            'created_at' => date('Y-m-d H:i:s')
        ]);

        Session::setFlash('success', 'Topik diskusi baru berhasil diterbitkan! 🚀');
        $this->redirect('/forum/' . $id);
    }

    public function addComment(string $id): void
    {
        $user = Session::get('user');
        $comment = trim($this->request->input('comment', ''));

        if (empty($comment)) {
            Session::setFlash('error', 'Komentar tidak boleh kosong.');
            $this->redirect('/forum/' . $id);
            return;
        }

        $this->forumModel->addComment((int)$id, (int)$user['id'], $user['role'], $comment);
        Session::setFlash('success', 'Tanggapan Anda telah dikirim.');
        $this->redirect('/forum/' . $id);
    }
}
