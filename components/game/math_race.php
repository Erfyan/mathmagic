<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Math Race - MathMagic</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #111;
    color: #fff;
}

/* TRACK */
#track {
    width: 100%;
    height: 220px;
    background: #333;
    margin-top: 60px;
    border-top: 4px solid #999;
    border-bottom: 4px solid #999;
    position: relative;
}

#runner {
    width: 120px;
    height: 120px;
    background: url("../../assets/game/runner.png");
    background-size: contain;
    background-repeat: no-repeat;
    position: absolute;
    left: 0;
    bottom: 10px;
}
/* FINISH LINE */
#finish {
    width: 10px;
    height: 100%;
    background: yellow;
    position: absolute;
    right: 20px;
    top: 0;
}

/* QUESTION BOX */
#questionBox {
    margin-top: 30px;
    text-align: center;
}

#answer {
    padding: 10px;
    font-size: 18px;
    width: 140px;
}

/* PROGRESS BAR */
#progressContainer {
    width: 80%;
    margin: 20px auto;
    background: #444;
    height: 25px;
    border-radius: 20px;
}

#progressBar {
    width: 0%;
    height: 100%;
    background: lime;
    border-radius: 20px;
    transition: width 0.3s;
}

/* MESSAGE */
#msg {
    text-align: center;
    margin-top: 20px;
    font-size: 22px;
}


</style>

</head>
<body>

<h1 style="text-align:center; margin-top:20px;">🏁 Math Race</h1>

<!-- TRACK -->
<div id="track">
    <div id="runner"></div>
    <div id="finish"></div>
</div>

<!-- PROGRESS -->
<div id="progressContainer">
    <div id="progressBar"></div>
</div>

<!-- QUESTION -->
<div id="questionBox">
    <h2 id="questionText"></h2>
    <input type="number" id="answer" placeholder="Jawaban...">
    <button onclick="checkAnswer()">Kirim</button>
</div>

<p id="msg"></p>

<script>
let progress = 0;
let runner = document.getElementById("runner");
let progressBar = document.getElementById("progressBar");
let msg = document.getElementById("msg");

/* ========== GENERATE SOAL ========== */
function generateQuestion() {
    let a = Math.floor(Math.random() * 15) + 1;
    let b = Math.floor(Math.random() * 15) + 1;
    let opList = ['+', '-', '*'];
    let op = opList[Math.floor(Math.random() * opList.length)];

    let q = `${a} ${op} ${b}`;
    window.correctAnswer = eval(q);

    document.getElementById("questionText").innerHTML = "Hitung: " + q;
}

generateQuestion();

/* ========== CEK JAWABAN ========== */
function checkAnswer() {
    let userAns = Number(document.getElementById("answer").value);

    if (userAns === window.correctAnswer) {
        progress += 10; // tambah speed
        msg.innerHTML = "Benar! Kamu makin cepat!";
    } else {
        progress -= 5; // penalti
        if (progress < 0) progress = 0;
        msg.innerHTML = "Salah! Kecepatan menurun!";
    }

    document.getElementById("answer").value = "";

    updateRace();

    if (progress >= 100) {
        winRace();
        return;
    }

    generateQuestion();
}

/* ========== UPDATE POSISI PELARI ========== */
function updateRace() {
    progressBar.style.width = progress + "%";

    // hitung posisi runner
    let maxPosition = window.innerWidth - 180;  
    let moveTo = (progress / 100) * maxPosition;

    runner.style.left = moveTo + "px";
}

/* ========== MENANG ========== */
function winRace() {
    msg.innerHTML = "🎉 Kamu Menang Balapan!";
    document.getElementById("questionBox").style.display = "none";
}
</script>

</body>
</html>
