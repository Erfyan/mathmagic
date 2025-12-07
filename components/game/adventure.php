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
<title>Adventure Game - MathMagic</title>
<style>
body { margin:0; background:#222; font-family:Arial, sans-serif; overflow:hidden; }
#game {
    width:100vw; height:100vh; position:relative;
    background: url("../../public/assets/game/bg.png") center/cover no-repeat;
}
#hero {
    width:80px; height:80px;
    position:absolute; bottom:20px; left:50px; transition:left 0.3s;
    background: url("../../public/assets/game/hero.png") center/cover no-repeat;
}
#monster {
    width:80px; height:80px;
    background: url("../../public/assets/game/monster.png") center/cover no-repeat;
    position:absolute; right:50px; bottom:20px;
}
#hpBarContainer {
    width:150px; height:20px; background:#555; border-radius:10px; overflow:hidden;
    position:absolute; top:20px; left:20px;
}
#hpBar { width:100%; height:100%; background:#2ecc71; transition:width 0.3s; }
#score { position:absolute; top:20px; right:20px; color:white; font-size:22px; font-weight:bold; }
#questionBox {
    position:absolute; bottom:130px; left:50%; transform:translateX(-50%);
    background: rgba(255,255,255,0.9); border:2px solid #3498db; border-radius:10px;
    padding:15px 20px; display:none; text-align:center;
}
#message { position:absolute; top:40%; width:100%; text-align:center; color:white; font-size:28px; }
input#answer { padding:8px; font-size:16px; width:100px; text-align:center; }
button { padding:8px 12px; font-size:16px; cursor:pointer; }
</style>
</head>
<body>

<div id="game">
    <div id="hero"></div>
    <div id="monster"></div>
    <div id="hpBarContainer"><div id="hpBar"></div></div>
    <div id="score">Poin: 0</div>

    <div id="questionBox">
        <div id="question"></div>
        <input type="number" id="answer" placeholder="Jawaban...">
        <button onclick="checkAnswer()">Kirim</button>
    </div>

    <div id="message"></div>
</div>

<script>
function sendGameResult(finalScore, status){
    fetch("../../api/game/save_game_result.php", {
        method: "POST",
        headers: { "Content-Type":"application/json" },
        body: JSON.stringify({
            game_name: "Adventure",
            points: finalScore,
            time_spent: 0,  // bisa tambahkan timer jika ada
            status: status
        })
    })
    .then(res => res.json())
    .then(data => {
        console.log(data.message);
        showMessage("Hasil tersimpan! Skor: "+finalScore);
    })
    .catch(err => console.error(err));
}

let hero = document.getElementById("hero");
let hp = 3;
let score = 0;
let heroPos = 50;
let canAnswer = false;

// Array sprite untuk animasi
let heroSprites = [
    "../../public/assets/game/hero_sprite.png",
    "../../public/assets/game/hero_sprite1.png",
    "../../public/assets/game/hero_sprite2.png",
    "../../public/assets/game/hero_sprite3.png"
];
let spriteIndex = 0;

function updateHP() { document.getElementById("hpBar").style.width = (hp/3*100)+"%"; }
function addScore(points){ score+=points; document.getElementById("score").innerText="Poin: "+score; }
function showMessage(msg){ document.getElementById("message").innerText=msg; }
function hideMessage(){ document.getElementById("message").innerText=""; }

function animateHero() {
    spriteIndex = (spriteIndex + 1) % heroSprites.length;
    hero.style.backgroundImage = `url('${heroSprites[spriteIndex]}')`;
}

function generateQuestion(){
    let a=Math.floor(Math.random()*10)+1;
    let b=Math.floor(Math.random()*10)+1;
    window.correctAnswer = a+b;
    document.getElementById("question").innerHTML = `${a} + ${b} = ?`;
    document.getElementById("questionBox").style.display="block";
    canAnswer=true;
}

function checkAnswer(){
    if(!canAnswer) return;
    let userAns = Number(document.getElementById("answer").value);
    if(userAns === window.correctAnswer){
        addScore(10); moveHero();
    }else{
        hp--; updateHP(); shakeHero();
        if(hp<=0){ gameOver(); return; }
        showMessage("Salah! Coba lagi!");
        setTimeout(hideMessage,800);
    }
    document.getElementById("answer").value="";
}

function moveHero(){
    heroPos += 80;
    hero.style.left = heroPos+"px";
    animateHero();
    showMessage("Benar! Lanjut!");
    if(heroPos > window.innerWidth - 200){ win(); return; }
    setTimeout(()=>{ hideMessage(); generateQuestion(); }, 800);
}

function shakeHero(){
    let pos=heroPos, i=0;
    let shake=setInterval(()=>{
        hero.style.left = pos + (i%2===0?-10:10) + "px";
        i++;
        if(i>5){ clearInterval(shake); hero.style.left = pos+"px"; }
    },50);
}

function win(){ 
    showMessage("🎉 Kamu Menang! Skor: "+score); 
    document.getElementById("questionBox").style.display="none"; 
    sendGameResult(score, "win"); 
}

function gameOver(){ 
    showMessage("💀 Game Over! Skor: "+score); 
    document.getElementById("questionBox").style.display="none"; 
    sendGameResult(score, "lose"); 
}
generateQuestion();
updateHP();
</script>
</body>
</html>