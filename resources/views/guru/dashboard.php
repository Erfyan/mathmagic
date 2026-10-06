<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="rounded-3xl p-8 bg-gradient-to-r from-sky-700 via-sky-600 to-indigo-700 shadow-2xl shadow-sky-700/20 border border-sky-400/20">
        <div class="max-w-2xl">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-sky-200 text-xs font-bold mb-3">
                👨‍🏫 Selamat Datang, <?= e($user['fullname']) ?>!
            </span>
            <h1 class="text-3xl md:text-4xl font-black text-white leading-snug mb-3">
                Panel Pengajar & Analitik Pembelajaran
            </h1>
            <p class="text-sky-100 text-sm leading-relaxed mb-6">
                Kelola bank soal kurikulum, pantau grafik prestasi siswa secara real-time, dan kembangkan kuis interaktif yang memikat.
            </p>
            <div class="flex gap-3">
                <a href="<?= base_url('/guru/banksoal') ?>" class="px-6 py-3 bg-white text-sky-700 hover:bg-sky-50 font-bold text-xs rounded-xl shadow-lg transition-all">
                    + Tambah Soal Baru
                </a>
                <a href="<?= base_url('/guru/reports') ?>" class="px-6 py-3 bg-white/15 hover:bg-white/25 text-white font-bold text-xs rounded-xl backdrop-blur-md transition-all">
                    Lihat Laporan Siswa
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="glass-panel p-6 rounded-3xl flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-2xl flex-shrink-0">
                👥
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Total Siswa Terdaftar</span>
                <span class="text-2xl font-black text-white"><?= number_format($totalStudents) ?></span>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-3xl flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-2xl flex-shrink-0">
                📚
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Total Bank Soal</span>
                <span class="text-2xl font-black text-white"><?= number_format($totalQuestions) ?></span>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-3xl flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl flex-shrink-0">
                📝
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Kuis Aktif</span>
                <span class="text-2xl font-black text-white"><?= number_format($totalQuizzes) ?></span>
            </div>
        </div>
    </div>

    <!-- Leaderboard & Recent Students -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Students -->
        <div class="glass-panel p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <span>👑</span> 5 Siswa Peringkat Teratas
                </h3>
                <a href="<?= base_url('/guru/leaderboard') ?>" class="text-xs font-bold text-sky-400 hover:text-sky-300">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="space-y-3">
                <?php foreach ($leaderboard as $idx => $s): ?>
                    <div class="flex items-center justify-between p-3.5 bg-slate-900/60 rounded-2xl border border-slate-800">
                        <div class="flex items-center gap-3">
                            <span class="w-6 text-center font-bold text-xs text-slate-400">#<?= $idx + 1 ?></span>
                            <div class="w-8 h-8 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center font-bold text-xs">
                                <?= strtoupper(substr($s['fullname'], 0, 1)) ?>
                            </div>
                            <div>
                                <span class="font-bold text-xs text-white block"><?= e($s['fullname']) ?></span>
                                <span class="text-[10px] text-slate-400"><?= e($s['kelas'] ?? 'Siswa') ?></span>
                            </div>
                        </div>
                        <span class="text-xs font-black text-emerald-400">
                            <?= number_format($s['total_points'] ?? 0) ?> XP
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Registered Students Overview -->
        <div class="glass-panel p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <span>🎒</span> Siswa Baru Bergabung
                </h3>
                <a href="<?= base_url('/guru/reports') ?>" class="text-xs font-bold text-sky-400 hover:text-sky-300">
                    Detail Laporan &rarr;
                </a>
            </div>

            <div class="space-y-3">
                <?php foreach ($students as $st): ?>
                    <div class="flex items-center justify-between p-3.5 bg-slate-900/60 rounded-2xl border border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold text-xs">
                                <?= strtoupper(substr($st['fullname'], 0, 1)) ?>
                            </div>
                            <div>
                                <span class="font-bold text-xs text-white block"><?= e($st['fullname']) ?></span>
                                <span class="text-[10px] text-slate-400"><?= e($st['email']) ?></span>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-slate-800 text-[10px] font-semibold text-slate-300">
                            <?= e($st['kelas'] ?? 'Umum') ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
