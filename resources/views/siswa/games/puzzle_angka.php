<div class="max-w-4xl mx-auto space-y-6" x-data="puzzleAngkaGame()">
    <!-- Top Bar -->
    <div class="flex items-center justify-between">
        <a href="<?= base_url('/siswa/games') ?>" class="px-4 py-2 bg-slate-800/80 hover:bg-slate-700 text-slate-300 font-bold text-xs rounded-xl flex items-center gap-2 transition-all">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Arena
        </a>
        <div class="flex items-center gap-4">
            <span class="text-sm font-bold text-pink-400 flex items-center gap-1.5">
                <i class="fa-solid fa-puzzle-piece"></i> Skor: <span x-text="score" class="text-white font-black text-lg">0</span>
            </span>
            <span class="text-sm font-bold text-amber-400 flex items-center gap-1.5">
                Level <span x-text="level" class="text-white font-black text-lg">1</span>
            </span>
        </div>
    </div>

    <!-- Puzzle Board -->
    <div class="glass-panel p-8 rounded-3xl text-center border border-pink-500/30">
        <template x-if="gameState === 'ready'">
            <div class="py-10 space-y-4">
                <div class="w-20 h-20 mx-auto rounded-3xl bg-pink-500/20 text-pink-400 flex items-center justify-center text-4xl shadow-inner">
                    🧩
                </div>
                <h2 class="text-2xl font-black text-white">Puzzle Logika Angka</h2>
                <p class="text-slate-300 text-sm max-w-md mx-auto leading-relaxed">
                    Temukan pola deret matematika tersembunyi dan tentukan angka yang tepat untuk mengisi tanda tanya (?)
                </p>
                <button @click="startGame()" class="px-8 py-4 bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-400 hover:to-rose-500 text-white font-black text-base rounded-2xl shadow-xl shadow-pink-500/30 transition-all hover:scale-105">
                    Mulai Pecahkan Puzzle 🧩
                </button>
            </div>
        </template>

        <template x-if="gameState === 'playing'">
            <div class="space-y-8 max-w-xl mx-auto">
                <div class="text-xs font-bold text-pink-400 uppercase tracking-wider">
                    Pola Deret Angka Ke-<span x-text="level" class="text-white">1</span>
                </div>

                <!-- Number Sequence Grid -->
                <div class="flex items-center justify-center gap-3">
                    <template x-for="(item, idx) in currentPuzzle.sequence" :key="idx">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-black border"
                             :class="item === '?' ? 'bg-pink-500/20 border-pink-500 text-pink-300 animate-pulse' : 'bg-slate-900/90 border-slate-700 text-white'">
                            <span x-text="item"></span>
                        </div>
                    </template>
                </div>

                <p class="text-slate-400 text-xs">Pilih angka yang tepat untuk mengisi kotak <strong>[ ? ]</strong></p>

                <!-- Options -->
                <div class="grid grid-cols-2 gap-4 max-w-sm mx-auto">
                    <template x-for="(opt, idx) in currentPuzzle.options" :key="idx">
                        <button @click="checkAnswer(opt)" 
                                class="py-4 px-6 bg-slate-800/80 hover:bg-pink-600 border border-slate-700 hover:border-pink-400 text-white font-bold text-xl rounded-2xl shadow-lg transition-all hover:scale-105 active:scale-95">
                            <span x-text="opt"></span>
                        </button>
                    </template>
                </div>
            </div>
        </template>

        <template x-if="gameState === 'finished'">
            <div class="py-8 space-y-4">
                <div class="text-5xl">🏆</div>
                <h3 class="text-2xl font-black text-white">Puzzle Selesai!</h3>
                <p class="text-slate-300 text-sm">
                    Kamu berhasil menyelesaikan semua deret logika dengan total skor <span class="font-bold text-pink-400" x-text="score + ' XP'"></span>!
                </p>
                <div class="flex justify-center gap-4 pt-2">
                    <button @click="startGame()" class="px-6 py-2.5 bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs rounded-xl transition-all">
                        Main Lagi 🔄
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
function puzzleAngkaGame() {
    return {
        gameState: 'ready',
        score: 0,
        level: 1,
        maxLevel: 5,
        currentPuzzle: { sequence: [], options: [], correct: 0 },

        startGame() {
            this.gameState = 'playing';
            this.score = 0;
            this.level = 1;
            this.generatePuzzle();
        },

        generatePuzzle() {
            // Generate arithmetic/geometric progression
            const start = Math.floor(Math.random() * 10) + 1;
            const diff = Math.floor(Math.random() * 5) + 2;
            const seq = [start, start + diff, start + (diff * 2), start + (diff * 3)];
            const correct = start + (diff * 4);
            seq.push('?');

            const options = new Set([correct]);
            while (options.size < 4) {
                const fake = correct + (Math.floor(Math.random() * 7) - 3) * diff;
                if (fake > 0 && fake !== correct) options.add(fake);
            }

            this.currentPuzzle = {
                sequence: seq,
                options: Array.from(options).sort(() => Math.random() - 0.5),
                correct: correct
            };
        },

        checkAnswer(opt) {
            if (opt === this.currentPuzzle.correct) {
                this.score += 20;
                this.level++;
                if (this.level > this.maxLevel) {
                    this.endGame();
                    return;
                }
            } else {
                this.score = Math.max(0, this.score - 5);
            }
            this.generatePuzzle();
        },

        endGame() {
            this.gameState = 'finished';
            if (typeof submitGameScore === 'function') {
                submitGameScore('Puzzle Angka', this.score, 30);
            }
        }
    }
}
</script>
