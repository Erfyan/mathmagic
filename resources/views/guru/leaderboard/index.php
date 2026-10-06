<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-white">👑 Papan Prestasi Siswa</h2>
            <p class="text-slate-400 text-sm mt-1">Pantau peringkat nilai dan akumulasi EXP semua siswa.</p>
        </div>
    </div>

    <!-- Leaderboard Table -->
    <div class="glass-panel p-6 rounded-3xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800">
                        <th class="py-3 px-4 font-bold text-center w-16">Peringkat</th>
                        <th class="py-3 px-4 font-bold">Nama Lengkap</th>
                        <th class="py-3 px-4 font-bold">Kelas</th>
                        <th class="py-3 px-4 font-bold">Level</th>
                        <th class="py-3 px-4 font-bold">Total Kuis</th>
                        <th class="py-3 px-4 font-bold">Total Game</th>
                        <th class="py-3 px-4 font-bold text-right">Total EXP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    <?php foreach ($leaderboard as $rank => $row): ?>
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="py-3.5 px-4 text-center font-bold text-sm">
                                <?php if ($rank === 0): ?> 🥇
                                <?php elseif ($rank === 1): ?> 🥈
                                <?php elseif ($rank === 2): ?> 🥉
                                <?php else: ?> <span class="text-slate-500">#<?= $rank + 1 ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-white"><?= e($row['fullname']) ?></td>
                            <td class="py-3.5 px-4 text-slate-300"><?= e($row['kelas'] ?? 'Umum') ?></td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 bg-amber-500/10 text-amber-400 rounded-full font-bold">
                                    Lv. <?= e($row['level'] ?? 1) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-300"><?= e($row['total_quizzes'] ?? 0) ?></td>
                            <td class="py-3.5 px-4 text-slate-300"><?= e($row['total_games'] ?? 0) ?></td>
                            <td class="py-3.5 px-4 text-right font-black text-sm text-emerald-400">
                                <?= number_format($row['total_points'] ?? 0) ?> XP
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
