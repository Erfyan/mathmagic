<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\QuestionBank;
use App\Models\Quiz;

class QuizController extends Controller
{
    private Quiz $quizModel;
    private QuestionBank $questionBank;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->quizModel = new Quiz();
        $this->questionBank = new QuestionBank();
    }

    public function index(): void
    {
        $user = Session::get('user');
        $quizzes = $this->quizModel->getQuizzesWithSubject();
        $history = $this->quizModel->getStudentQuizResults((int)$user['id']);

        $this->render('siswa.quiz.index', [
            'title' => 'Kuis & Ujian Matematika - MathMagic',
            'user' => $user,
            'quizzes' => $quizzes,
            'history' => $history
        ], 'siswa');
    }

    public function solve(string $id): void
    {
        $user = Session::get('user');
        $quiz = $this->quizModel->find((int)$id);

        if (!$quiz) {
            Session::setFlash('error', 'Kuis tidak ditemukan.');
            $this->redirect('/siswa/quiz');
            return;
        }

        // Get questions for this quiz/class
        $kelas = $user['kelas'] ?? 'SMP';
        $questions = $this->questionBank->getRandomQuestions($kelas, $quiz['total_questions'] ?? 10);

        if (empty($questions)) {
            $questions = $this->questionBank->getRandomQuestions('ALL', 10);
        }

        $this->render('siswa.quiz.solve', [
            'title' => 'Mengerjakan Kuis: ' . $quiz['title'],
            'user' => $user,
            'quiz' => $quiz,
            'questions' => $questions
        ], 'siswa');
    }

    public function submitQuiz(): void
    {
        $user = Session::get('user');
        if (!$user) {
            $this->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
            return;
        }

        $studentId = (int)$user['id'];
        $quizTitle = $this->request->input('quiz_title', 'Kuis Latihan');
        $answers = $this->request->input('answers', []); // map of [question_id => selected_option]

        $score = 0;
        $correct = 0;
        $wrong = 0;
        $details = [];

        if (is_array($answers) && !empty($answers)) {
            $total = count($answers);
            foreach ($answers as $qId => $selected) {
                $q = $this->questionBank->find((int)$qId);
                if ($q) {
                    $isCorrect = strtoupper(trim($selected)) === strtoupper(trim($q['correct_answer']));
                    if ($isCorrect) {
                        $correct++;
                    } else {
                        $wrong++;
                    }
                    $details[] = [
                        'question_id' => $qId,
                        'selected' => $selected,
                        'correct_answer' => $q['correct_answer'],
                        'is_correct' => $isCorrect
                    ];
                }
            }
            $score = $total > 0 ? (int)round(($correct / $total) * 100) : 0;
        } else {
            $score = (int)$this->request->input('score', 0);
            $correct = (int)$this->request->input('correct', 0);
            $wrong = (int)$this->request->input('wrong', 0);
        }

        $res = $this->quizModel->saveQuizResult($studentId, $quizTitle, $score, $correct, $wrong);

        $this->json([
            'status' => 'success',
            'message' => 'Hasil kuis berhasil disimpan!',
            'data' => [
                'score' => $score,
                'correct' => $correct,
                'wrong' => $wrong,
                'details' => $details,
                'gamification' => $res
            ]
        ]);
    }
}
