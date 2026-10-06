<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-white">📊 Laporan & Analitik Kelas</h2>
            <p class="text-slate-400 text-sm mt-1">Ringkasan aktivitas belajar, performa pengerjaan kuis, dan keterlibatan siswa.</p>
        </div>
    </div>

    <!-- Aggregate Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="glass-panel p-5 rounded-2xl">
            <span class="text-xs text-slate-400 block mb-1">Total Siswa</span>
            <span class="text-2xl font-black text-white"><?= $totalStudents ?></span>
        </div>
        <div class="glass-panel p-5 rounded-2xl">
            <span class="text-xs text-slate-400 block mb-1">Total Kuis Dikerjakan</span>
            <span class="text-2xl font-black text-sky-400"><?= $totalQuizzes ?></span>
        </div>
        <div class="glass-panel p-5 rounded-2xl">
            <span class="text-xs text-slate-400 block mb-1">Total Sesi Game</span>
            <span class="text-2xl font-black text-amber-400"><?= $totalGames ?></span>
        </div>
        <div class="glass-panel p-5 rounded-2xl">
            <span class="text-xs text-slate-400 block mb-1">Rata-rata Skor XP</span>
            <span class="text-2xl font-black text-emerald-400"><?= $avgScore ?></span>
        </div>
    </div>

    <!-- Students Detail List -->
    <div class="glass-panel p-6 rounded-3xl">
        <h3 class="text-base font-bold text-white mb-4">Daftar Akun Siswa & Detail Statistik</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800">
                        <th class="py-3 px-4 font-bold">Nama Lengkap</th>
                        <th class="py-3 px-4 font-bold">Email</th>
                        <th class="py-3 px-4 font-bold">Kelas</th>
                        <th class="py-3 px-4 font-bold">Status Akun</th>
                        <th class="py-3 px-4 font-bold">Terakhir Login</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    <?php foreach ($students as $st): ?>
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-white"><?= e($st['fullname']) ?></td>
                            <td class="py-3.5 px-4 text-slate-300"><?= e($st['email']) ?></td>
                            <td class="py-3.5 px-4 text-slate-300"><?= e($st['kelas'] ?? 'Umum') ?></td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold <?= $st['is_active'] ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' ?>">
                                    <?= $st['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400"><?= $st['last_login'] ? date('d M Y, H:i', strtotime($st['last_login'])) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
