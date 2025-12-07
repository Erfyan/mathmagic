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
<title>Puzzle Angka - MathMagic</title>

<style>
body {
    background: #e9eef7;
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    display: flex;
    justify-content: center;
    padding-top: 40px;
}

.container {
    background: white;
    width: 430px;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    margin-bottom: 10px;
}

select, button {
    width: 100%;
    padding: 12px;
    margin-top: 5px;
    border-radius: 8px;
    border: 1px solid #bbb;
    font-size: 16px;
}

#puzzleBoard {
    margin-top: 20px;
    display: grid;
    gap: 8px;
}

.tile {
    background: #4C8BF5;
    color: white;
    font-size: 22px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 10px;
    cursor: pointer;
    user-select: none;
}

.tile.empty {
    background: #d6d6d6;
    cursor: default;
}

.stats {
    margin-top: 15px;
    font-size: 16px;
    display: flex;
    justify-content: space-between;
}
</style>
</head>

<body>
<div class="container">
    <h2>Puzzle Angka 🔢</h2>

    <label>Pilih Level:</label>
    <select id="level">
        <option value="3">Mudah (3x3)</option>
        <option value="4">Sedang (4x4)</option>
        <option value="5">Sulit (5x5)</option>
    </select>

    <button onclick="startGame()">Mulai Game</button>

    <div class="stats">
        <span>⏱ Waktu: <b id="time">0</b>s</span>
        <span>👣 Langkah: <b id="steps">0</b></span>
    </div>

    <div id="puzzleBoard"></div>

</div>

<script>
let size = 3;
let tiles = [];
let steps = 0;
let time = 0;
let timer;

function startGame() {
    size = parseInt(document.getElementById("level").value);
    steps = 0;
    time = 0;

    document.getElementById("steps").innerText = 0;
    document.getElementById("time").innerText = 0;

    generatePuzzle();
    clearInterval(timer);
    timer = setInterval(() => {
        time++;
        document.getElementById("time").innerText = time;
    }, 1000);
}

function generatePuzzle() {
    const board = document.getElementById("puzzleBoard");
    board.style.gridTemplateColumns = `repeat(${size}, 1fr)`;

    tiles = Array.from({ length: size * size - 1 }, (_, i) => i + 1);
    tiles.push("");

    // Acak puzzle
    tiles.sort(() => Math.random() - 0.5);

    board.innerHTML = "";
    tiles.forEach((num, index) => {
        const div = document.createElement("div");
        div.className = "tile" + (num === "" ? " empty" : "");
        div.innerText = num;
        div.onclick = () => moveTile(index);
        board.appendChild(div);
    });
}

function moveTile(index) {
    let emptyIndex = tiles.indexOf("");

    let validMoves = [
        index - 1, index + 1,
        index - size, index + size
    ];

    if (!validMoves.includes(emptyIndex)) return;

    // Tukar elemen
    [tiles[index], tiles[emptyIndex]] = [tiles[emptyIndex], tiles[index]];
    steps++;
    document.getElementById("steps").innerText = steps;

    updateBoard();
    checkWin();
}

function updateBoard() {
    const board = document.getElementById("puzzleBoard");
    const divs = board.children;

    tiles.forEach((val, i) => {
        divs[i].innerText = val;
        divs[i].className = "tile" + (val === "" ? " empty" : "");
    });
}

function checkWin() {
    for (let i = 0; i < tiles.length - 1; i++) {
        if (tiles[i] !== i + 1) return;
    }

    clearInterval(timer);
    alert(`Selamat! Puzzle selesai!\nWaktu: ${time}s\nLangkah: ${steps}`);
}
</script>

</body>
</html>
