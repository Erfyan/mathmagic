<div class="space-y-8">
    <!-- Welcome Banner with Dynamic Level & XP -->
    <div class="relative overflow-hidden rounded-3xl p-8 bg-gradient-to-r from-brand-700 via-brand-600 to-indigo-700 shadow-2xl shadow-brand-700/20 border border-brand-400/20">
        <div class="relative z-10 max-w-2xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-brand-200 text-xs font-bold mb-3 border border-white/10">
                ✨ Halo, <?= e($user['fullname']) ?>!
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight leading-snug mb-3">
                Siap Melanjutkan Petualangan Matematikamu Hari Ini?
            </h1>
            <p class="text-brand-100 text-sm md:text-base mb-6 font-normal">
                Selesaikan misi game, kumpulkan EXP untuk naik level, dan pertahankan posisimu di puncak leaderboard!
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="<?= base_url('/siswa/games') ?>" class="px-6 py-3 bg-white text-brand-700 hover:bg-brand-50 font-black text-sm rounded-2xl shadow-xl shadow-black/10 transition-all hover:scale-105 inline-flex items-center gap-2">
                    <i class="fa-solid fa-gamepad"></i>
                    Mainkan Game Sekarang
                </a>
                <a href="<?= base_url('/siswa/quiz') ?>" class="px-6 py-3 bg-white/15 hover:bg-white/25 text-white font-bold text-sm rounded-2xl backdrop-blur-md transition-all border border-white/20 inline-flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-question"></i>
                    Kerjakan Kuis
                </a>
            </div>
        </div>

        <!-- Decorative Floating Badge -->
        <div class="absolute -right-6 -bottom-6 w-64 h-64 bg-pink-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="hidden lg:flex absolute right-12 top-1/2 -translate-y-1/2 flex-col items-center justify-center p-6 bg-slate-900/40 backdrop-blur-md border border-white/10 rounded-3xl text-center">
            <div class="w-16 h-16 rounded-2xl bg-amber-400/20 text-amber-300 flex items-center justify-center text-3xl font-black mb-2 shadow-inner">
                <?= e($stats['level'] ?? 1) ?>
            </div>
            <span class="text-xs font-bold text-slate-300 uppercase tracking-wider">Level Kamu</span>
            <span class="text-xs text-brand-300 mt-1"><?= e($stats['total_points'] ?? 0) ?> Total XP</span>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="glass-panel p-6 rounded-3xl flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-2xl flex-shrink-0">
                ⚡
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Total Skor</span>
                <span class="text-2xl font-black text-white"><?= number_format($stats['total_points'] ?? 0) ?></span>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-3xl flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-2xl flex-shrink-0">
                🎮
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Game Dimainkan</span>
                <span class="text-2xl font-black text-white"><?= e($stats['total_games'] ?? 0) ?></span>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-3xl flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl flex-shrink-0">
                📝
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Kuis Diselesaikan</span>
                <span class="text-2xl font-black text-white"><?= e($stats['total_quizzes'] ?? 0) ?></span>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-3xl flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-pink-500/20 text-pink-400 flex items-center justify-center text-2xl flex-shrink-0">
                ⭐
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Badge Dimiliki</span>
                <span class="text-2xl font-black text-white"><?= count($badges) ?></span>
            </div>
        </div>
    </div>

    <!-- Main 2-Column Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Quick Games Grid (2 Cols) -->
        <div class="lg:col-span-2 space-y-5">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center gap-2.5">
                    <span>🎮</span> Arena Game Interaktif
                </h3>
                <a href="<?= base_url('/siswa/games') ?>" class="text-xs font-bold text-brand-400 hover:text-brand-300">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Game 1: Math Race -->
                <a href="<?= base_url('/siswa/games/race') ?>" class="glass-panel p-6 rounded-3xl group hover:border-brand-500/50 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-500 flex items-center justify-center text-2xl text-white mb-4 shadow-lg shadow-amber-500/20 group-hover:scale-110 transition-transform">
                        🏎️
                    </div>
                    <h4 class="font-bold text-white text-base group-hover:text-brand-400 transition-colors">Math Race</h4>
                    <p class="text-slate-400 text-xs mt-1 mb-4 leading-relaxed">Adu kecepatan berhitungmu untuk menggerakkan kendaraan ke garis finish!</p>
                    <span class="inline-flex items-center text-xs font-bold text-amber-400">
                        Main Sekarang &rarr;
                    </span>
                </a>

                <!-- Game 2: Math Adventure -->
                <a href="<?= base_url('/siswa/games/adventure') ?>" class="glass-panel p-6 rounded-3xl group hover:border-brand-500/50 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-600 flex items-center justify-center text-2xl text-white mb-4 shadow-lg shadow-brand-600/20 group-hover:scale-110 transition-transform">
                        🏃
                    </div>
                    <h4 class="font-bold text-white text-base group-hover:text-brand-400 transition-colors">Math Adventure</h4>
                    <p class="text-slate-400 text-xs mt-1 mb-4 leading-relaxed">Petualangan platformer aljabar, lewati rintangan dengan jawaban tepat!</p>
                    <span class="inline-flex items-center text-xs font-bold text-brand-400">
                        Mulai Petualangan &rarr;
                    </span>
                </a>

                <!-- Game 3: Puzzle Angka -->
                <a href="<?= base_url('/siswa/games/puzzle') ?>" class="glass-panel p-6 rounded-3xl group hover:border-brand-500/50 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-pink-500 to-rose-500 flex items-center justify-center text-2xl text-white mb-4 shadow-lg shadow-pink-500/20 group-hover:scale-110 transition-transform">
                        🧩
                    </div>
                    <h4 class="font-bold text-white text-base group-hover:text-brand-400 transition-colors">Puzzle Angka</h4>
                    <p class="text-slate-400 text-xs mt-1 mb-4 leading-relaxed">Teka-teki susunan angka dan logika operasi matriks matematika.</p>
                    <span class="inline-flex items-center text-xs font-bold text-pink-400">
                        Pecahkan Teka-teki &rarr;
                    </span>
                </a>

                <!-- Game 4: Quiz Cepat -->
                <a href="<?= base_url('/siswa/games/quiz-cepat') ?>" class="glass-panel p-6 rounded-3xl group hover:border-brand-500/50 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-500 flex items-center justify-center text-2xl text-white mb-4 shadow-lg shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                        ⚡
                    </div>
                    <h4 class="font-bold text-white text-base group-hover:text-brand-400 transition-colors">Quiz Cepat</h4>
                    <p class="text-slate-400 text-xs mt-1 mb-4 leading-relaxed">Mode kilat 60 detik! Dapatkan combo multiplier untuk skor maksimal.</p>
                    <span class="inline-flex items-center text-xs font-bold text-emerald-400">
                        Tantang Sekarang &rarr;
                    </span>
                </a>
            </div>
        </div>

        <!-- Right: Badges & Recent Activities -->
        <div class="space-y-6">
            <!-- Badges Box -->
            <div class="glass-panel p-6 rounded-3xl">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-bold text-white text-sm flex items-center gap-2">
                        <span>🎖️</span> Koleksi Badge
                    </h4>
                    <span class="text-xs text-brand-400 font-semibold"><?= count($badges) ?> Diperoleh</span>
                </div>

                <?php if (empty($badges)): ?>
                    <div class="text-center py-6 text-slate-500 text-xs">
                        Belum ada badge. Mainkan game untuk membuka badge pertama!
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-3 gap-3">
                        <?php foreach ($badges as $b): ?>
                            <div class="p-3 bg-slate-900/80 rounded-2xl border border-slate-800 text-center group relative hover:border-amber-500/40 transition-all" title="<?= e($b['description'] ?? '') ?>">
                                <div class="w-10 h-10 mx-auto rounded-xl bg-amber-500/20 text-amber-300 flex items-center justify-center text-xl mb-1.5">
                                    ⭐
                                </div>
                                <span class="text-[11px] font-bold text-slate-200 block truncate"><?= e($b['title'] ?? 'Badge') ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Recent Games Played -->
            <div class="glass-panel p-6 rounded-3xl">
                <h4 class="font-bold text-white text-sm mb-4 flex items-center gap-2">
                    <span>🕒</span> Riwayat Game Terbaru
                </h4>

                <?php if (empty($recentGames)): ?>
                    <div class="text-center py-6 text-slate-500 text-xs">
                        Belum ada riwayat permainan.
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($recentGames as $rg): ?>
                            <div class="flex items-center justify-between p-3 bg-slate-900/60 rounded-2xl border border-slate-800">
                                <div>
                                    <span class="font-bold text-xs text-white block"><?= e($rg['game_name']) ?></span>
                                    <span class="text-[10px] text-slate-500"><?= date('d M Y, H:i', strtotime($rg['created_at'])) ?></span>
                                </div>
                                <span class="px-2.5 py-1 rounded-xl bg-emerald-500/10 text-emerald-400 font-black text-xs">
                                    +<?= e($rg['points']) ?> XP
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
