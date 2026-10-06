<div class="max-w-3xl mx-auto space-y-6" x-data="quizSolver(<?= htmlspecialchars(json_encode($questions), ENT_QUOTES) ?>, '<?= e($quiz['title']) ?>')">
    <!-- Top Bar -->
    <div class="flex items-center justify-between">
        <a href="<?= base_url('/siswa/quiz') ?>" class="px-4 py-2 bg-slate-800/80 hover:bg-slate-700 text-slate-300 font-bold text-xs rounded-xl flex items-center gap-2 transition-all">
            <i class="fa-solid fa-arrow-left"></i> Keluar Kuis
        </a>
        <div class="text-xs font-bold text-slate-400">
            Soal <span x-text="currentIndex + 1" class="text-white font-black text-sm"></span> dari <span x-text="questions.length"></span>
        </div>
    </div>

    <!-- Active Question Card -->
    <template x-if="!isFinished">
        <div class="glass-panel p-8 rounded-3xl space-y-6 border border-brand-500/20">
            <!-- Progress Bar -->
            <div class="w-full bg-slate-900 h-2 rounded-full overflow-hidden">
                <div class="bg-gradient-to-r from-brand-500 to-indigo-500 h-full transition-all duration-300" :style="'width: ' + (((currentIndex + 1) / questions.length) * 100) + '%;'"></div>
            </div>

            <!-- Question Text -->
            <div class="space-y-2">
                <span class="text-xs font-bold text-brand-400 uppercase tracking-wider">Pertanyaan Pilihan Ganda</span>
                <h3 class="text-xl md:text-2xl font-bold text-white leading-relaxed" x-text="questions[currentIndex].question"></h3>
            </div>

            <!-- Options Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-2">
                <button type="button" @click="selectOption('A')" 
                        :class="answers[questions[currentIndex].id] === 'A' ? 'bg-brand-600 border-brand-400 text-white ring-2 ring-brand-400' : 'bg-slate-900/80 hover:bg-slate-800/80 text-slate-200 border-slate-700'"
                        class="p-4 rounded-2xl border text-left font-semibold text-sm flex items-center gap-3 transition-all">
                    <span class="w-7 h-7 rounded-xl bg-black/40 flex items-center justify-center font-bold text-xs flex-shrink-0">A</span>
                    <span x-text="questions[currentIndex].option_a"></span>
                </button>

                <button type="button" @click="selectOption('B')" 
                        :class="answers[questions[currentIndex].id] === 'B' ? 'bg-brand-600 border-brand-400 text-white ring-2 ring-brand-400' : 'bg-slate-900/80 hover:bg-slate-800/80 text-slate-200 border-slate-700'"
                        class="p-4 rounded-2xl border text-left font-semibold text-sm flex items-center gap-3 transition-all">
                    <span class="w-7 h-7 rounded-xl bg-black/40 flex items-center justify-center font-bold text-xs flex-shrink-0">B</span>
                    <span x-text="questions[currentIndex].option_b"></span>
                </button>

                <button type="button" @click="selectOption('C')" 
                        :class="answers[questions[currentIndex].id] === 'C' ? 'bg-brand-600 border-brand-400 text-white ring-2 ring-brand-400' : 'bg-slate-900/80 hover:bg-slate-800/80 text-slate-200 border-slate-700'"
                        class="p-4 rounded-2xl border text-left font-semibold text-sm flex items-center gap-3 transition-all">
                    <span class="w-7 h-7 rounded-xl bg-black/40 flex items-center justify-center font-bold text-xs flex-shrink-0">C</span>
                    <span x-text="questions[currentIndex].option_c"></span>
                </button>

                <button type="button" @click="selectOption('D')" 
                        :class="answers[questions[currentIndex].id] === 'D' ? 'bg-brand-600 border-brand-400 text-white ring-2 ring-brand-400' : 'bg-slate-900/80 hover:bg-slate-800/80 text-slate-200 border-slate-700'"
                        class="p-4 rounded-2xl border text-left font-semibold text-sm flex items-center gap-3 transition-all">
                    <span class="w-7 h-7 rounded-xl bg-black/40 flex items-center justify-center font-bold text-xs flex-shrink-0">D</span>
                    <span x-text="questions[currentIndex].option_d"></span>
                </button>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex items-center justify-between pt-4 border-t border-slate-800">
                <button @click="prevQuestion()" :disabled="currentIndex === 0" 
                        class="px-5 py-2.5 bg-slate-800 text-slate-300 font-bold text-xs rounded-xl disabled:opacity-30 transition-all">
                    &larr; Sebelumnya
                </button>

                <button x-show="currentIndex < questions.length - 1" @click="nextQuestion()" 
                        class="px-6 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl transition-all">
                    Selanjutnya &rarr;
                </button>

                <button x-show="currentIndex === questions.length - 1" @click="submitQuiz()" 
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition-all shadow-lg shadow-emerald-600/30">
                    Selesai & Kumpulkan 🎯
                </button>
            </div>
        </div>
    </template>

    <!-- Quiz Result Screen -->
    <template x-if="isFinished">
        <div class="glass-panel p-10 rounded-3xl text-center space-y-6">
            <div class="w-24 h-24 mx-auto rounded-3xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-5xl shadow-xl shadow-amber-500/20 animate-bounce">
                🎉
            </div>
            <h2 class="text-3xl font-black text-white">Kuis Selesai!</h2>
            <p class="text-slate-300 text-sm">Hasil penilaian lembar kuis kamu:</p>

            <div class="bg-slate-900/80 p-6 rounded-3xl border border-slate-800 max-w-sm mx-auto flex justify-around">
                <div>
                    <span class="text-xs text-slate-400 block">Skor Akhir</span>
                    <span class="text-3xl font-black text-amber-400" x-text="resultData.score">0</span>
                </div>
                <div class="border-r border-slate-800"></div>
                <div>
                    <span class="text-xs text-slate-400 block">Benar</span>
                    <span class="text-3xl font-black text-emerald-400" x-text="resultData.correct">0</span>
                </div>
                <div class="border-r border-slate-800"></div>
                <div>
                    <span class="text-xs text-slate-400 block">Salah</span>
                    <span class="text-3xl font-black text-rose-400" x-text="resultData.wrong">0</span>
                </div>
            </div>

            <div class="pt-4">
                <a href="<?= base_url('/siswa/quiz') ?>" class="px-8 py-3.5 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold text-sm rounded-2xl shadow-xl transition-all inline-block">
                    Kembali ke Daftar Kuis
                </a>
            </div>
        </div>
    </template>
</div>

<script>
function quizSolver(questions, quizTitle) {
    return {
        questions: questions || [],
        quizTitle: quizTitle,
        currentIndex: 0,
        answers: {},
        isFinished: false,
        resultData: { score: 0, correct: 0, wrong: 0 },

        selectOption(opt) {
            const q = this.questions[this.currentIndex];
            this.answers[q.id] = opt;
        },

        nextQuestion() {
            if (this.currentIndex < this.questions.length - 1) {
                this.currentIndex++;
            }
        },

        prevQuestion() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
            }
        },

        async submitQuiz() {
            try {
                const res = await fetch('<?= base_url('/api/quiz/submit') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        quiz_title: this.quizTitle,
                        answers: this.answers
                    })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    this.resultData = data.data;
                    this.isFinished = true;
                    if (typeof confetti === 'function') {
                        confetti({ particleCount: 120, spread: 80, origin: { y: 0.6 } });
                    }
                }
            } catch (err) {
                console.error('Submit quiz failed', err);
            }
        }
    }
}
</script>
