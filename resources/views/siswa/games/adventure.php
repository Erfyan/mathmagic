<div class="max-w-4xl mx-auto space-y-6" x-data="adventureGame()">
    <!-- Top Bar -->
    <div class="flex items-center justify-between">
        <a href="<?= base_url('/siswa/games') ?>" class="px-4 py-2 bg-slate-800/80 hover:bg-slate-700 text-slate-300 font-bold text-xs rounded-xl flex items-center gap-2 transition-all">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Arena
        </a>
        <div class="flex items-center gap-4">
            <span class="text-sm font-bold text-amber-400 flex items-center gap-1.5">
                <i class="fa-solid fa-gem"></i> Koin/Skor: <span x-text="score" class="text-white font-black text-lg">0</span>
            </span>
            <span class="text-sm font-bold text-rose-400 flex items-center gap-1.5">
                <i class="fa-solid fa-heart"></i> Nyawa: <span x-text="lives" class="text-white font-black text-lg">3</span>
            </span>
        </div>
    </div>

    <!-- Adventure Game Area -->
    <div class="glass-panel p-6 rounded-3xl relative overflow-hidden border border-brand-500/30">
        <!-- Canvas Stage -->
        <div class="relative w-full h-64 bg-slate-900 rounded-2xl overflow-hidden border border-slate-800 flex items-center justify-center">
            <!-- Background Elements -->
            <div class="absolute inset-0 bg-gradient-to-b from-indigo-950/40 via-purple-950/30 to-slate-900"></div>
            
            <!-- Ground Line -->
            <div class="absolute bottom-0 inset-x-0 h-10 bg-slate-800 border-t-2 border-brand-500/40 flex items-center justify-around text-xs text-slate-500">
                <span>🌲</span><span>⛰️</span><span>🌳</span><span>🌲</span><span>🏰</span>
            </div>

            <!-- Hero Character -->
            <div class="absolute bottom-10 transition-all duration-300 flex flex-col items-center" :style="'left: ' + playerX + '%;'">
                <div class="text-4xl animate-bounce">🧙‍♂️</div>
                <span class="text-[10px] font-bold text-brand-300 bg-black/60 px-1.5 py-0.5 rounded-md mt-0.5">Hero</span>
            </div>

            <!-- Monster Obstacle -->
            <div class="absolute bottom-10 right-16 flex flex-col items-center animate-pulse">
                <div class="text-4xl">👾</div>
                <span class="text-[10px] font-bold text-rose-400 bg-black/60 px-1.5 py-0.5 rounded-md mt-0.5">Monster Aljabar</span>
            </div>
        </div>

        <!-- Question & Action Section -->
        <div class="mt-6 text-center">
            <template x-if="gameState === 'ready'">
                <div class="py-6 space-y-4">
                    <h3 class="text-xl font-black text-white">Petualangan Menembus Monster Angka</h3>
                    <p class="text-slate-300 text-sm max-w-md mx-auto">Kalahkan monster aljabar dengan memilih mantra jawaban yang benar untuk maju ke kastil selanjutnya!</p>
                    <button @click="startGame()" class="px-8 py-3.5 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-black text-sm rounded-2xl shadow-xl shadow-brand-600/30 transition-all hover:scale-105">
                        Mulai Petualangan ⚔️
                    </button>
                </div>
            </template>

            <template x-if="gameState === 'playing'">
                <div class="space-y-5">
                    <div class="text-xs font-bold text-brand-400 uppercase tracking-wider">
                        Rintangan Ke-<span x-text="stage" class="text-white">1</span>
                    </div>

                    <div class="text-3xl md:text-4xl font-black text-white" x-text="currentQuestion.question">
                        2x + 4 = 10, tentukan x?
                    </div>

                    <div class="grid grid-cols-2 gap-4 max-w-md mx-auto pt-2">
                        <template x-for="(opt, idx) in currentQuestion.options" :key="idx">
                            <button @click="submitAnswer(opt)" 
                                    class="py-3.5 px-6 bg-slate-800 hover:bg-brand-600 border border-slate-700 text-white font-bold text-lg rounded-2xl transition-all hover:scale-105">
                                <span x-text="opt"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </template>

            <template x-if="gameState === 'finished'">
                <div class="py-6 space-y-4">
                    <div class="text-5xl" x-text="lives > 0 ? '🏆' : '💀'"></div>
                    <h3 class="text-2xl font-black text-white" x-text="lives > 0 ? 'Selamat! Kamu Menaklukkan Kastil!' : 'Kamu Kehabisan Nyawa!'"></h3>
                    <p class="text-slate-300 text-sm">
                        Total Skor Didapat: <span class="font-bold text-amber-400" x-text="score + ' EXP'"></span>
                    </p>
                    <div class="flex justify-center gap-4 pt-2">
                        <button @click="startGame()" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl transition-all">
                            Coba Lagi 🔄
                        </button>
                        <a href="<?= base_url('/siswa/games') ?>" class="px-6 py-2.5 bg-slate-800 text-slate-300 font-bold text-xs rounded-xl transition-all">
                            Kembali ke Arena
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function adventureGame() {
    return {
        gameState: 'ready',
        score: 0,
        lives: 3,
        stage: 1,
        playerX: 10,
        currentQuestion: { question: '', options: [], correct: 0 },

        startGame() {
            this.gameState = 'playing';
            this.score = 0;
            this.lives = 3;
            this.stage = 1;
            this.playerX = 10;
            this.generateQuestion();
        },

        generateQuestion() {
            // Generate linear algebra question: a * x + b = c
            const x = Math.floor(Math.random() * 8) + 1;
            const a = Math.floor(Math.random() * 4) + 2;
            const b = Math.floor(Math.random() * 10) + 1;
            const c = (a * x) + b;

            const options = new Set([x]);
            while (options.size < 4) {
                const fake = Math.floor(Math.random() * 10) + 1;
                if (fake !== x) options.add(fake);
            }

            this.currentQuestion = {
                question: `${a}x + ${b} = ${c}, nilai x adalah?`,
                options: Array.from(options).sort(() => Math.random() - 0.5),
                correct: x
            };
        },

        submitAnswer(opt) {
            if (opt === this.currentQuestion.correct) {
                this.score += 20;
                this.stage++;
                this.playerX = Math.min(75, this.playerX + 15);

                if (this.stage > 5) {
                    this.endGame();
                    return;
                }
            } else {
                this.lives--;
                if (this.lives <= 0) {
                    this.endGame();
                    return;
                }
            }
            this.generateQuestion();
        },

        endGame() {
            this.gameState = 'finished';
            if (typeof submitGameScore === 'function') {
                submitGameScore('Math Adventure', this.score, 30);
            }
        }
    }
}
</script>
