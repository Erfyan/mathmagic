<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl md:text-3xl font-black text-white">📝 Kuis & Ujian Matematika</h2>
            <p class="text-slate-400 text-sm mt-1">Uji pemahaman materi matematikamu dengan berbagai kuis berjenjang.</p>
        </div>
    </div>

    <!-- Quiz List Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($quizzes as $q): ?>
            <div class="glass-panel p-6 rounded-3xl flex flex-col justify-between hover:border-brand-500/40 transition-all group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-brand-500/10 text-brand-300 text-xs font-bold rounded-full">
                            <?= e($q['subject_name'] ?? 'Matematika') ?>
                        </span>
                        <span class="text-xs text-slate-400 font-semibold">
                            <i class="fa-solid fa-list-ol mr-1"></i> <?= e($q['total_questions'] ?? 10) ?> Soal
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-white mb-2 group-hover:text-brand-400 transition-colors">
                        <?= e($q['title']) ?>
                    </h3>
                    <p class="text-slate-400 text-xs line-clamp-2 mb-6">
                        Tingkat Kesulitan: <?= ucfirst(e($q['difficulty'] ?: 'Sedang')) ?>. Kerjakan sekarang untuk meraih EXP tambahan!
                    </p>
                </div>

                <a href="<?= base_url('/siswa/quiz/' . $q['id']) ?>" class="w-full py-3 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg text-center transition-all">
                    Mulai Kerjakan 🚀
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Quiz History -->
    <div class="glass-panel p-6 rounded-3xl">
        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
            <span>📜</span> Riwayat Pengerjaan Kuis
        </h3>

        <?php if (empty($history)): ?>
            <div class="text-center py-8 text-slate-500 text-xs">
                Belum ada riwayat pengerjaan kuis.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-800">
                            <th class="py-3 px-4 font-bold">Judul Kuis</th>
                            <th class="py-3 px-4 font-bold">Skor Nilai</th>
                            <th class="py-3 px-4 font-bold">Tanggal</th>
                            <th class="py-3 px-4 font-bold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        <?php foreach ($history as $h): ?>
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-white"><?= e($h['quiz_title']) ?></td>
                                <td class="py-3.5 px-4 font-black text-sm <?= $h['score'] >= 75 ? 'text-emerald-400' : 'text-amber-400' ?>">
                                    <?= e($h['score']) ?> / 100
                                </td>
                                <td class="py-3.5 px-4 text-slate-400"><?= date('d M Y, H:i', strtotime($h['created_at'])) ?></td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold <?= $h['score'] >= 75 ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400' ?>">
                                        <?= $h['score'] >= 75 ? 'Lulus' : 'Perlu Remedial' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
