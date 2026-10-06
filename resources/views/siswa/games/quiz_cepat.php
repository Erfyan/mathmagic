<div class="max-w-4xl mx-auto space-y-6" x-data="quizCepatGame()">
    <!-- Top Bar -->
    <div class="flex items-center justify-between">
        <a href="<?= base_url('/siswa/games') ?>" class="px-4 py-2 bg-slate-800/80 hover:bg-slate-700 text-slate-300 font-bold text-xs rounded-xl flex items-center gap-2 transition-all">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Arena
        </a>
        <div class="flex items-center gap-4">
            <span class="text-sm font-bold text-amber-400 flex items-center gap-1.5">
                <i class="fa-solid fa-fire"></i> Combo: <span x-text="combo + 'x'" class="text-amber-300 font-black text-lg">1x</span>
            </span>
            <span class="text-sm font-bold text-emerald-400 flex items-center gap-1.5">
                <i class="fa-solid fa-trophy"></i> Skor: <span x-text="score" class="text-white font-black text-lg">0</span>
            </span>
            <span class="text-sm font-bold text-sky-400 flex items-center gap-1.5">
                <i class="fa-solid fa-clock"></i> <span x-text="timeLeft" class="text-white font-black text-lg">60</span>s
            </span>
        </div>
    </div>

    <!-- Quiz Blitz Stage -->
    <div class="glass-panel p-8 rounded-3xl text-center border border-emerald-500/30">
        <template x-if="gameState === 'ready'">
            <div class="py-10 space-y-4">
                <div class="w-20 h-20 mx-auto rounded-3xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-4xl shadow-inner">
                    ⚡
                </div>
                <h2 class="text-2xl font-black text-white">Quiz Cepat: Mode Kilat 60 Detik</h2>
                <p class="text-slate-300 text-sm max-w-md mx-auto leading-relaxed">
                    Uji kecepatan otakmu! Setiap jawaban benar beruntun akan melipatgandakan poin dengan pengali Combo Streak.
                </p>
                <button @click="startGame()" class="px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-black text-base rounded-2xl shadow-xl shadow-emerald-500/30 transition-all hover:scale-105">
                    Mulai Sekarang (60 Detik) ⚡
                </button>
            </div>
        </template>

        <template x-if="gameState === 'playing'">
            <div class="space-y-6 max-w-lg mx-auto">
                <!-- Combo Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-300 text-xs font-black animate-pulse" x-show="combo > 1">
                    🔥 COMBO STREAK x<span x-text="combo"></span> (+<span x-text="combo * 5"></span> Bonus)
                </div>

                <div class="text-5xl font-black text-white py-6" x-text="currentQuestion.text">
                    8 × 7 = ?
                </div>

                <!-- 4 Option Buttons -->
                <div class="grid grid-cols-2 gap-4">
                    <template x-for="(opt, idx) in currentQuestion.options" :key="idx">
                        <button @click="answer(opt)" 
                                class="py-4 px-6 bg-slate-800/80 hover:bg-emerald-600 border border-slate-700 hover:border-emerald-400 text-white font-bold text-2xl rounded-2xl shadow-lg transition-all hover:scale-105 active:scale-95">
                            <span x-text="opt"></span>
                        </button>
                    </template>
                </div>
            </div>
        </template>

        <template x-if="gameState === 'finished'">
            <div class="py-8 space-y-4">
                <div class="text-5xl">⚡</div>
                <h3 class="text-2xl font-black text-white">Waktu Habis!</h3>
                <p class="text-slate-300 text-sm">
                    Kamu menjawab <span class="font-bold text-emerald-400" x-text="correctCount"></span> soal benar dengan total skor <span class="font-bold text-amber-400" x-text="score + ' XP'"></span>!
                </p>
                <div class="flex justify-center gap-4 pt-2">
                    <button @click="startGame()" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition-all">
                        Tantang Lagi 🔄
                    </button>
                    <a href="<?= base_url('/siswa/games') ?>" class="px-6 py-2.5 bg-slate-800 text-slate-300 font-bold text-xs rounded-xl transition-all">
                        Kembali ke Arena
                    </a>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function quizCepatGame() {
    return {
        gameState: 'ready',
        score: 0,
        timeLeft: 60,
        timer: null,
        combo: 1,
        correctCount: 0,
        currentQuestion: { text: '', options: [], correct: 0 },

        startGame() {
            this.gameState = 'playing';
            this.score = 0;
            this.timeLeft = 60;
            this.combo = 1;
            this.correctCount = 0;
            this.generateQuestion();

            clearInterval(this.timer);
            this.timer = setInterval(() => {
                this.timeLeft--;
                if (this.timeLeft <= 0) {
                    this.endGame();
                }
            }, 1000);
        },

        generateQuestion() {
            const ops = ['+', '-', '×'];
            const op = ops[Math.floor(Math.random() * ops.length)];
            let a, b, ans;

            if (op === '+') {
                a = Math.floor(Math.random() * 50) + 10;
                b = Math.floor(Math.random() * 50) + 10;
                ans = a + b;
            } else if (op === '-') {
                a = Math.floor(Math.random() * 80) + 20;
                b = Math.floor(Math.random() * a) + 1;
                ans = a - b;
            } else {
                a = Math.floor(Math.random() * 12) + 2;
                b = Math.floor(Math.random() * 12) + 2;
                ans = a * b;
            }

            const options = new Set([ans]);
            while (options.size < 4) {
                const fake = ans + (Math.floor(Math.random() * 13) - 6);
                if (fake > 0 && fake !== ans) options.add(fake);
            }

            this.currentQuestion = {
                text: `${a} ${op} ${b} = ?`,
                options: Array.from(options).sort(() => Math.random() - 0.5),
                correct: ans
            };
        },

        answer(val) {
            if (val === this.currentQuestion.correct) {
                this.score += (10 * this.combo);
                this.combo = Math.min(5, this.combo + 1);
                this.correctCount++;
            } else {
                this.combo = 1;
                this.score = Math.max(0, this.score - 5);
            }
            this.generateQuestion();
        },

        endGame() {
            clearInterval(this.timer);
            this.gameState = 'finished';
            if (typeof submitGameScore === 'function') {
                submitGameScore('Quiz Cepat', this.score, 60);
            }
        }
    }
}
</script>
