<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\QuestionBank;

class BankSoalController extends Controller
{
    private QuestionBank $questionModel;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->questionModel = new QuestionBank();
    }

    public function guruIndex(): void
    {
        $user = Session::get('user');
        $kelas = $this->request->input('kelas', '');
        $difficulty = $this->request->input('difficulty', '');

        $filters = [];
        if (!empty($kelas)) $filters['kelas'] = $kelas;
        if ($difficulty !== '') $filters['difficulty'] = $difficulty;

        $questions = $this->questionModel->getByFilter($filters);

        $this->render('guru.banksoal.index', [
            'title' => 'Kelola Bank Soal - MathMagic',
            'user' => $user,
            'questions' => $questions,
            'currentKelas' => $kelas,
            'currentDifficulty' => $difficulty
        ], 'guru');
    }

    public function guruStore(): void
    {
        $user = Session::get('user');
        $question = trim($this->request->input('question', ''));
        $optA = trim($this->request->input('option_a', ''));
        $optB = trim($this->request->input('option_b', ''));
        $optC = trim($this->request->input('option_c', ''));
        $optD = trim($this->request->input('option_d', ''));
        $correct = trim($this->request->input('correct_answer', 'A'));
        $kelas = $this->request->input('kelas', 'SMP');
        $difficulty = (int)$this->request->input('difficulty', 1);

        if (empty($question) || empty($optA) || empty($optB)) {
            Session::setFlash('error', 'Pertanyaan dan minimal 2 opsi jawaban harus diisi.');
            $this->redirect('/guru/banksoal');
            return;
        }

        $this->questionModel->insert([
            'created_by' => $user['id'],
            'subject_id' => 1,
            'kelas' => $kelas,
            'type' => 'mcq',
            'question' => $question,
            'option_a' => $optA,
            'option_b' => $optB,
            'option_c' => $optC,
            'option_d' => $optD,
            'correct_answer' => strtoupper($correct),
            'difficulty' => $difficulty,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        Session::setFlash('success', 'Soal baru berhasil ditambahkan ke Bank Soal! ✨');
        $this->redirect('/guru/banksoal');
    }

    public function guruDelete(string $id): void
    {
        $this->questionModel->delete((int)$id);
        Session::setFlash('success', 'Soal berhasil dihapus.');
        $this->redirect('/guru/banksoal');
    }

    public function siswaIndex(): void
    {
        $user = Session::get('user');
        $kelas = $this->request->input('kelas', $user['kelas'] ?? 'SMP');
        $questions = $this->questionModel->getByFilter(['kelas' => $kelas]);

        $this->render('siswa.banksoal.index', [
            'title' => 'Bank Soal & Latihan Mandiri - MathMagic',
            'user' => $user,
            'questions' => $questions,
            'currentKelas' => $kelas
        ], 'siswa');
    }
}
