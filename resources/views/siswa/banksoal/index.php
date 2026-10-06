<div class="space-y-8" x-data="{ activeKelas: '<?= e($currentKelas) ?>' }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl md:text-3xl font-black text-white">📚 Bank Soal & Latihan Mandiri</h2>
            <p class="text-slate-400 text-sm mt-1">Latih kemampuan analisismu secara mandiri dengan bank soal matematika terlengkap.</p>
        </div>

        <!-- Grade Filter -->
        <div class="flex items-center gap-2">
            <a href="<?= base_url('/siswa/banksoal?kelas=SD') ?>" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all <?= str_starts_with($currentKelas, 'SD') ? 'bg-brand-600 text-white shadow-lg' : 'bg-slate-900 text-slate-400 hover:text-white' ?>">
                Jenjang SD
            </a>
            <a href="<?= base_url('/siswa/banksoal?kelas=SMP') ?>" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all <?= str_starts_with($currentKelas, 'SMP') ? 'bg-brand-600 text-white shadow-lg' : 'bg-slate-900 text-slate-400 hover:text-white' ?>">
                Jenjang SMP
            </a>
            <a href="<?= base_url('/siswa/banksoal?kelas=SMA') ?>" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all <?= str_starts_with($currentKelas, 'SMA') ? 'bg-brand-600 text-white shadow-lg' : 'bg-slate-900 text-slate-400 hover:text-white' ?>">
                Jenjang SMA
            </a>
        </div>
    </div>

    <!-- Questions Accordion / List -->
    <div class="space-y-4">
        <?php if (empty($questions)): ?>
            <div class="glass-panel p-12 text-center rounded-3xl text-slate-500 text-sm">
                Belum ada kumpulan soal untuk jenjang ini.
            </div>
        <?php else: ?>
            <?php foreach ($questions as $idx => $q): ?>
                <div class="glass-panel p-6 rounded-3xl space-y-4" x-data="{ showAnswer: false, selected: null }">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <span class="text-[11px] font-bold text-brand-400 uppercase tracking-wider">Soal #<?= $idx + 1 ?> (<?= e($q['kelas']) ?>)</span>
                            <h4 class="text-base font-bold text-white leading-relaxed"><?= e($q['question']) ?></h4>
                        </div>
                        <span class="px-3 py-1 bg-slate-800 text-slate-300 text-[11px] font-semibold rounded-full flex-shrink-0">
                            Kesulitan: <?= e($q['difficulty'] ?: 1) ?>/5
                        </span>
                    </div>

                    <!-- Options Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-2">
                        <div class="p-3 bg-slate-900/80 rounded-xl border border-slate-800 text-xs text-slate-300 flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-black/40 flex items-center justify-center font-bold">A</span>
                            <span><?= e($q['option_a']) ?></span>
                        </div>
                        <div class="p-3 bg-slate-900/80 rounded-xl border border-slate-800 text-xs text-slate-300 flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-black/40 flex items-center justify-center font-bold">B</span>
                            <span><?= e($q['option_b']) ?></span>
                        </div>
                        <?php if (!empty($q['option_c'])): ?>
                            <div class="p-3 bg-slate-900/80 rounded-xl border border-slate-800 text-xs text-slate-300 flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-lg bg-black/40 flex items-center justify-center font-bold">C</span>
                                <span><?= e($q['option_c']) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($q['option_d'])): ?>
                            <div class="p-3 bg-slate-900/80 rounded-xl border border-slate-800 text-xs text-slate-300 flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-lg bg-black/40 flex items-center justify-center font-bold">D</span>
                                <span><?= e($q['option_d']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Answer Reveal Box -->
                    <div class="pt-2 border-t border-slate-800/80 flex items-center justify-between">
                        <button type="button" @click="showAnswer = !showAnswer" class="text-xs font-bold text-brand-400 hover:text-brand-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-lightbulb"></i>
                            <span x-text="showAnswer ? 'Sembunyikan Kunci' : 'Lihat Kunci Jawaban'"></span>
                        </button>
                        <div x-show="showAnswer" class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl text-xs font-bold">
                            Kunci: Jawaban <?= e($q['correct_answer'] ?? 'A') ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
