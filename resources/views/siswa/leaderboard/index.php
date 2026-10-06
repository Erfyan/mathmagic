<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl md:text-3xl font-black text-white">🏆 Papan Peringkat Juara</h2>
            <p class="text-slate-400 text-sm mt-1">Daftar siswa terbaik dengan akumulasi EXP dan level tertinggi di MathMagic.</p>
        </div>
    </div>

    <!-- Leaderboard Table -->
    <div class="glass-panel p-6 rounded-3xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800">
                        <th class="py-3 px-4 font-bold text-center w-16">Peringkat</th>
                        <th class="py-3 px-4 font-bold">Nama Siswa</th>
                        <th class="py-3 px-4 font-bold">Kelas</th>
                        <th class="py-3 px-4 font-bold">Level</th>
                        <th class="py-3 px-4 font-bold text-right">Total Skor EXP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    <?php foreach ($leaderboard as $rank => $row): ?>
                        <tr class="hover:bg-slate-800/30 transition-colors <?= $row['id'] == $user['id'] ? 'bg-brand-600/10 border-l-4 border-brand-500' : '' ?>">
                            <td class="py-4 px-4 text-center font-black text-sm">
                                <?php if ($rank === 0): ?>
                                    <span class="text-2xl">🥇</span>
                                <?php elseif ($rank === 1): ?>
                                    <span class="text-2xl">🥈</span>
                                <?php elseif ($rank === 2): ?>
                                    <span class="text-2xl">🥉</span>
                                <?php else: ?>
                                    <span class="text-slate-400">#<?= $rank + 1 ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 flex items-center justify-center font-bold text-white text-xs shadow-md">
                                        <?= strtoupper(substr($row['fullname'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <span class="font-bold text-white text-sm block"><?= e($row['fullname']) ?></span>
                                        <?php if ($row['id'] == $user['id']): ?>
                                            <span class="text-[10px] font-bold text-brand-400">(Kamu)</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-slate-300 font-medium"><?= e($row['kelas'] ?? 'Umum') ?></td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-400 font-bold text-[11px]">
                                    Lv. <?= e($row['level'] ?? 1) ?>
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right font-black text-sm text-emerald-400">
                                <?= number_format($row['total_points'] ?? 0) ?> XP
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
