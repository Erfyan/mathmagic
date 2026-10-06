<div class="max-w-4xl mx-auto space-y-6" x-data="mathRaceGame()">
    <!-- Top Bar -->
    <div class="flex items-center justify-between">
        <a href="<?= base_url('/siswa/games') ?>" class="px-4 py-2 bg-slate-800/80 hover:bg-slate-700 text-slate-300 font-bold text-xs rounded-xl flex items-center gap-2 transition-all">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Arena
        </a>
        <div class="flex items-center gap-4">
            <span class="text-sm font-bold text-amber-400 flex items-center gap-1.5">
                <i class="fa-solid fa-trophy"></i> Skor: <span x-text="score" class="text-white font-black text-lg">0</span>
            </span>
            <span class="text-sm font-bold text-sky-400 flex items-center gap-1.5">
                <i class="fa-solid fa-clock"></i> Sisa Waktu: <span x-text="timeLeft" class="text-white font-black text-lg">45</span>s
            </span>
        </div>
    </div>

    <!-- Race Track Canvas Simulation -->
    <div class="glass-panel p-6 rounded-3xl relative overflow-hidden border border-amber-500/20">
        <h3 class="text-xs font-bold text-amber-400 uppercase tracking-wider mb-4 flex items-center gap-2">
            <span>🏁</span> Lintasan Pacuan
        </h3>

        <!-- Player Lane -->
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-xs font-bold text-slate-300 mb-1">
                    <span class="text-brand-400">Kamu (<?= e($user['fullname']) ?>)</span>
                    <span x-text="playerProgress + '%'">0%</span>
                </div>
                <div class="w-full bg-slate-900 h-8 rounded-2xl p-1 border border-slate-700 relative overflow-hidden flex items-center">
                    <div class="bg-gradient-to-r from-brand-500 to-indigo-500 h-full rounded-xl transition-all duration-300 flex items-center justify-end pr-2" :style="'width: ' + Math.max(8, playerProgress) + '%;'">
                        <span class="text-base">🏎️</span>
                    </div>
                </div>
            </div>

            <!-- Bot Rival Lane -->
            <div>
                <div class="flex justify-between text-xs font-bold text-slate-400 mb-1">
                    <span class="text-rose-400">Rival Robot (Bot AI)</span>
                    <span x-text="botProgress + '%'">0%</span>
                </div>
                <div class="w-full bg-slate-900 h-8 rounded-2xl p-1 border border-slate-700 relative overflow-hidden flex items-center">
                    <div class="bg-gradient-to-r from-rose-500 to-orange-500 h-full rounded-xl transition-all duration-500 flex items-center justify-end pr-2" :style="'width: ' + Math.max(8, botProgress) + '%;'">
                        <span class="text-base">🚗</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Question Board / Start Screen -->
    <div class="glass-panel p-8 rounded-3xl text-center">
        <template x-if="gameState === 'ready'">
            <div class="py-10 space-y-4">
                <div class="w-20 h-20 mx-auto rounded-3xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-4xl shadow-inner">
                    🏎️
                </div>
                <h2 class="text-2xl font-black text-white">Math Race: Siap Bertanding?</h2>
                <p class="text-slate-300 text-sm max-w-md mx-auto leading-relaxed">
                    Jawab pertanyaan operasi matematika secepat mungkin untuk memacu mobilmu ke garis finish sebelum waktu habis!
                </p>
                <button @click="startGame()" class="px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 text-white font-black text-base rounded-2xl shadow-xl shadow-amber-500/30 transition-all hover:scale-105">
                    Mulai Balapan Sekarang 🚀
                </button>
            </div>
        </template>

        <template x-if="gameState === 'playing'">
            <div class="space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-slate-800 text-slate-400 text-xs font-bold">
                    Soal No. <span x-text="questionCount" class="text-white">1</span>
                </div>

                <div class="text-4xl md:text-5xl font-black text-white tracking-wider py-4" x-text="currentQuestion.question">
                    12 + 8 = ?
                </div>

                <div class="grid grid-cols-2 gap-4 max-w-lg mx-auto">
                    <template x-for="(opt, idx) in currentQuestion.options" :key="idx">
                        <button @click="chooseAnswer(opt)" 
                                class="py-4 px-6 bg-slate-800/80 hover:bg-brand-600 border border-slate-700 hover:border-brand-400 text-white font-bold text-xl rounded-2xl shadow-lg transition-all hover:scale-105 active:scale-95">
                            <span x-text="opt"></span>
                        </button>
                    </template>
                </div>
            </div>
        </template>

        <template x-if="gameState === 'finished'">
            <div class="py-8 space-y-5">
                <div class="text-5xl" x-text="playerWon ? '🏆' : '🏁'"></div>
                <h2 class="text-2xl font-black text-white" x-text="playerWon ? 'Kamu Menang Balapan!' : 'Waktu Habis!'"></h2>
                <p class="text-slate-300 text-sm">
                    Kamu berhasil mengumpulkan <span class="font-bold text-amber-400" x-text="score + ' Poin'"></span> dalam pertandingan ini.
                </p>
                <div class="flex justify-center gap-4 pt-2">
                    <button @click="startGame()" class="px-6 py-3 bg-brand-600 hover:bg-brand-500 text-white font-bold text-sm rounded-xl transition-all">
                        Main Lagi 🔄
                    </button>
                    <a href="<?= base_url('/siswa/games') ?>" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-sm rounded-xl transition-all">
                        Kembali ke Arena
                    </a>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function mathRaceGame() {
    return {
        gameState: 'ready', // ready, playing, finished
        score: 0,
        timeLeft: 45,
        timerInterval: null,
        botInterval: null,
        playerProgress: 0,
        botProgress: 0,
        questionCount: 0,
        currentQuestion: { question: '', options: [], correct: 0 },
        playerWon: false,

        startGame() {
            this.gameState = 'playing';
            this.score = 0;
            this.timeLeft = 45;
            this.playerProgress = 0;
            this.botProgress = 0;
            this.questionCount = 0;
            this.playerWon = false;

            this.nextQuestion();

            clearInterval(this.timerInterval);
            this.timerInterval = setInterval(() => {
                this.timeLeft--;
                if (this.timeLeft <= 0) {
                    this.endGame();
                }
            }, 1000);

            // Bot slowly progresses
            clearInterval(this.botInterval);
            this.botInterval = setInterval(() => {
                if (this.gameState === 'playing' && this.botProgress < 100) {
                    this.botProgress += Math.floor(Math.random() * 4) + 2;
                    if (this.botProgress >= 100) {
                        this.botProgress = 100;
                        this.endGame();
                    }
                }
            }, 1000);
        },

        nextQuestion() {
            this.questionCount++;
            const ops = ['+', '-', '×'];
            const op = ops[Math.floor(Math.random() * ops.length)];
            let a, b, ans;

            if (op === '+') {
                a = Math.floor(Math.random() * 40) + 5;
                b = Math.floor(Math.random() * 40) + 5;
                ans = a + b;
            } else if (op === '-') {
                a = Math.floor(Math.random() * 50) + 20;
                b = Math.floor(Math.random() * a) + 1;
                ans = a - b;
            } else {
                a = Math.floor(Math.random() * 12) + 2;
                b = Math.floor(Math.random() * 10) + 2;
                ans = a * b;
            }

            const options = new Set([ans]);
            while (options.size < 4) {
                const fake = ans + (Math.floor(Math.random() * 11) - 5);
                if (fake >= 0 && fake !== ans) options.add(fake);
            }

            this.currentQuestion = {
                question: `${a} ${op} ${b} = ?`,
                options: Array.from(options).sort(() => Math.random() - 0.5),
                correct: ans
            };
        },

        chooseAnswer(opt) {
            if (opt === this.currentQuestion.correct) {
                this.score += 15;
                this.playerProgress += 12;
                if (this.playerProgress >= 100) {
                    this.playerProgress = 100;
                    this.playerWon = true;
                    this.endGame();
                    return;
                }
            } else {
                this.score = Math.max(0, this.score - 5);
            }
            this.nextQuestion();
        },

        endGame() {
            clearInterval(this.timerInterval);
            clearInterval(this.botInterval);
            this.gameState = 'finished';

            if (this.playerWon) {
                this.score += 50; // Victory bonus
            }

            // Sync with backend API
            if (typeof submitGameScore === 'function') {
                submitGameScore('Math Race', this.score, 45 - this.timeLeft);
            }
        }
    }
}
</script>
