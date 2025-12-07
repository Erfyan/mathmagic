<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../public/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Quiz Cepat - MathMagic</title>

<style>
body {
    background: #f4f6fb;
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.game-box {
    width: 380px;
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    text-align: center;
}

h2 {
    color: #333;
    margin-bottom: 10px;
}

.timer {
    font-size: 22px;
    color: #ff5252;
    margin-bottom: 10px;
    font-weight: bold;
}

.score {
    font-size: 20px;
    margin-bottom: 15px;
    color: #4CAF50;
}

.question {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 15px;
}

input {
    width: 100%;
    padding: 10px;
    font-size: 18px;
    border-radius: 8px;
    border: 1px solid #ccc;
    text-align: center;
}

button {
    margin-top: 15px;
    width: 100%;
    padding: 12px;
    background: #4C8BF5;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    cursor: pointer;
}

button:hover {
    background: #3d76d8;
}

.result-box {
    margin-top: 15px;
    font-size: 18px;
    color: #333;
}

.back-btn {
    margin-top: 15px;
    background: #555;
}
</style>
</head>

<body>

<div class="game-box">
    <h2>Quiz Cepat ⚡</h2>
    <div class="timer">Waktu: <span id="time">30</span>s</div>
    <div class="score">Skor: <span id="score">0</span></div>

    <div class="question" id="question">Loading...</div>

    <input type="number" id="answer" placeholder="Jawaban kamu..." autocomplete="off">

    <button id="submitBtn">Kirim Jawaban</button>

    <div class="result-box" id="resultText"></div>

    <button class="back-btn" onclick="window.history.back()">Kembali</button>
</div>


<script>
let time = 30;
let score = 0;
let correctAnswer = 0;

const timeEl = document.getElementById("time");
const scoreEl = document.getElementById("score");
const questionEl = document.getElementById("question");
const answerEl = document.getElementById("answer");
const resultText = document.getElementById("resultText");

function newQuestion() {
    let a = Math.floor(Math.random() * 20) + 1;
    let b = Math.floor(Math.random() * 20) + 1;

    let ops = ["+", "-", "×"];
    let op = ops[Math.floor(Math.random() * ops.length)];

    if (op === "+") correctAnswer = a + b;
    else if (op === "-") correctAnswer = a - b;
    else correctAnswer = a * b;

    questionEl.innerHTML = `${a} ${op} ${b} = ?`;
    answerEl.value = "";
}

document.getElementById("submitBtn").onclick = () => {
    let userAnswer = parseInt(answerEl.value);

    if (userAnswer === correctAnswer) {
        score++;
        scoreEl.innerHTML = score;
        resultText.innerHTML = "<span style='color:green'>Benar!</span>";
    } else {
        resultText.innerHTML = "<span style='color:red'>Salah!</span>";
    }

    newQuestion();
};

let timer = setInterval(() => {
    time--;
    timeEl.innerHTML = time;

    if (time <= 0) {
        clearInterval(timer);
        questionEl.innerHTML = "Waktu Habis!";
        answerEl.disabled = true;
        document.getElementById("submitBtn").disabled = true;

        resultText.innerHTML = `<b>Game selesai!</b><br>Skor akhir: ${score}`;
    }
}, 1000);

newQuestion();
</script>

</body>
</html>
