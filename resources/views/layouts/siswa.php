<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'MathMagic - Portal Siswa') ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <style>
        .app-bg {
            background-color: #090d16;
            background-image: 
                radial-gradient(at 10% 20%, rgba(124, 58, 237, 0.15) 0px, transparent 40%),
                radial-gradient(at 90% 80%, rgba(59, 130, 246, 0.12) 0px, transparent 40%);
        }
        .glass-panel {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.07);
        }
        .nav-link.active {
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.8), rgba(99, 102, 241, 0.8));
            box-shadow: 0 4px 20px rgba(124, 58, 237, 0.35);
            color: #ffffff;
        }
    </style>
</head>
<body class="h-full font-sans antialiased app-bg flex overflow-hidden">
    <!-- Flash Messages -->
    <?php if ($flashSuccess): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed top-5 right-5 z-50 flex items-center p-4 bg-emerald-600/95 backdrop-blur-md text-white rounded-2xl shadow-2xl border border-emerald-400/40 animate-bounce">
            <i class="fa-solid fa-circle-check text-xl mr-3"></i>
            <span class="font-semibold text-sm"><?= e($flashSuccess) ?></span>
        </div>
    <?php endif; ?>

    <?php if ($flashError): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed top-5 right-5 z-50 flex items-center p-4 bg-rose-600/95 backdrop-blur-md text-white rounded-2xl shadow-2xl border border-rose-400/40">
            <i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
            <span class="font-semibold text-sm"><?= e($flashError) ?></span>
        </div>
    <?php endif; ?>

    <!-- Gamification Reward Modal Component -->
    <div id="rewardModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md transition-all">
        <div class="glass-panel w-full max-w-sm p-6 rounded-3xl text-center border-2 border-brand-500/50 shadow-2xl shadow-brand-500/30 scale-95 transition-transform" id="rewardModalBox">
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-tr from-amber-400 to-yellow-300 rounded-full flex items-center justify-center text-4xl shadow-lg shadow-amber-400/40 animate-pulse">
                🏆
            </div>
            <h3 class="text-2xl font-black text-white mb-1" id="rewardTitle">Selamat!</h3>
            <p class="text-slate-300 text-sm mb-4" id="rewardDesc">Kamu mendapatkan pencapaian baru.</p>
            
            <div class="bg-slate-800/80 rounded-2xl p-4 mb-5 border border-slate-700/50 flex justify-around">
                <div>
                    <span class="text-xs text-slate-400 block">Poin Tambahan</span>
                    <span class="text-xl font-bold text-emerald-400" id="rewardPoints">+50 XP</span>
                </div>
                <div class="border-r border-slate-700"></div>
                <div>
                    <span class="text-xs text-slate-400 block">Level Sekarang</span>
                    <span class="text-xl font-bold text-amber-400" id="rewardLevel">Lv. 2</span>
                </div>
            </div>

            <button onclick="closeRewardModal()" class="w-full py-3 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg transition-all">
                Lanjut Belajar ✨
            </button>
        </div>
    </div>

    <!-- SIDEBAR -->
    <aside class="w-64 glass-panel flex flex-col justify-between p-5 border-r border-slate-800/80 z-20 flex-shrink-0">
        <div>
            <!-- Brand -->
            <a href="<?= base_url('/siswa/dashboard') ?>" class="flex items-center gap-3 px-2 py-3 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-brand-500 to-pink-500 flex items-center justify-center font-extrabold text-white text-xl shadow-lg shadow-brand-500/30">
                    ∑
                </div>
                <div>
                    <h1 class="text-lg font-black tracking-wider text-white">MATHMAGIC</h1>
                    <span class="text-[11px] font-semibold text-brand-400 tracking-wider uppercase">Portal Siswa</span>
                </div>
            </a>

            <!-- Navigation Menu -->
            <nav class="space-y-1.5">
                <?php
                $uri = $_SERVER['REQUEST_URI'] ?? '';
                $isNav = fn($path) => str_contains($uri, $path);
                ?>
                <a href="<?= base_url('/siswa/dashboard') ?>" class="nav-link flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all <?= $isNav('/siswa/dashboard') ? 'active' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?>">
                    <i class="fa-solid fa-shapes text-base w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <a href="<?= base_url('/siswa/games') ?>" class="nav-link flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all <?= $isNav('/siswa/games') ? 'active' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?>">
                    <i class="fa-solid fa-gamepad text-base w-5 text-center"></i>
                    <span>Arena Game</span>
                </a>

                <a href="<?= base_url('/siswa/quiz') ?>" class="nav-link flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all <?= $isNav('/siswa/quiz') ? 'active' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?>">
                    <i class="fa-solid fa-list-check text-base w-5 text-center"></i>
                    <span>Kuis & Ujian</span>
                </a>

                <a href="<?= base_url('/siswa/banksoal') ?>" class="nav-link flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all <?= $isNav('/siswa/banksoal') ? 'active' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?>">
                    <i class="fa-solid fa-book-open text-base w-5 text-center"></i>
                    <span>Bank Soal</span>
                </a>

                <a href="<?= base_url('/siswa/leaderboard') ?>" class="nav-link flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all <?= $isNav('/siswa/leaderboard') ? 'active' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?>">
                    <i class="fa-solid fa-trophy text-base w-5 text-center"></i>
                    <span>Papan Peringkat</span>
                </a>

                <a href="<?= base_url('/forum') ?>" class="nav-link flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all <?= $isNav('/forum') ? 'active' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?>">
                    <i class="fa-solid fa-comments text-base w-5 text-center"></i>
                    <span>Forum Diskusi</span>
                </a>

                <a href="<?= base_url('/profile') ?>" class="nav-link flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all <?= $isNav('/profile') ? 'active' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?>">
                    <i class="fa-solid fa-user-gear text-base w-5 text-center"></i>
                    <span>Profil Saya</span>
                </a>
            </nav>
        </div>

        <!-- Logout Bottom -->
        <div class="pt-4 border-t border-slate-800/80">
            <a href="<?= base_url('/logout') ?>" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold text-rose-400 hover:bg-rose-500/10 transition-all">
                <i class="fa-solid fa-arrow-right-from-bracket text-base w-5 text-center"></i>
                <span>Keluar</span>
            </a>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Navbar -->
        <header class="h-20 glass-panel border-b border-slate-800/80 flex items-center justify-between px-8 z-10">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-bold text-white"><?= e($title ?? 'Dashboard') ?></h2>
                <span class="hidden md:inline-block px-3 py-1 bg-brand-500/10 border border-brand-500/30 text-brand-400 text-xs font-semibold rounded-full">
                    <?= e($user['kelas'] ?? 'Siswa') ?>
                </span>
            </div>

            <!-- Gamification & Profile Bar -->
            <div class="flex items-center gap-6">
                <!-- XP Indicator -->
                <div class="hidden sm:flex items-center gap-3 bg-slate-900/80 px-4 py-2 rounded-2xl border border-slate-800">
                    <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-sm">
                        ⚡
                    </div>
                    <div>
                        <div class="flex justify-between items-center text-xs font-semibold mb-1">
                            <span class="text-amber-400">XP Progres</span>
                            <span class="text-slate-400" id="topExpText"><?= e($stats['total_points'] ?? 0) ?> XP</span>
                        </div>
                        <div class="w-28 bg-slate-800 h-2 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-amber-400 to-orange-500 h-full rounded-full transition-all duration-500" style="width: <?= min(100, ($stats['progress_percent'] ?? 0)) ?>%;"></div>
                        </div>
                    </div>
                </div>

                <!-- User Badge / Avatar -->
                <a href="<?= base_url('/profile') ?>" class="flex items-center gap-3 group">
                    <div class="text-right hidden md:block">
                        <div class="text-sm font-bold text-white group-hover:text-brand-400 transition-colors"><?= e($user['fullname'] ?? 'Siswa') ?></div>
                        <div class="text-[11px] font-medium text-slate-400">Level <?= e($stats['level'] ?? 1) ?></div>
                    </div>
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-brand-600 to-indigo-600 p-0.5 shadow-md shadow-brand-500/20">
                        <?php if (!empty($user['avatar']) && file_exists(__DIR__ . '/../../../public/' . ltrim($user['avatar'], '/'))): ?>
                            <img src="<?= upload_url(basename($user['avatar'])) ?>" class="w-full h-full object-cover rounded-[14px]" alt="Avatar">
                        <?php else: ?>
                            <div class="w-full h-full rounded-[14px] bg-slate-800 flex items-center justify-center font-bold text-white">
                                <?= strtoupper(substr($user['fullname'] ?? 'S', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
        </header>

        <!-- Main Content Body -->
        <main class="flex-1 overflow-y-auto p-8">
            <?= $content ?>
        </main>
    </div>

    <!-- Gamification Trigger JS Helper -->
    <script>
        function showRewardModal(title, desc, points, level) {
            document.getElementById('rewardTitle').textContent = title;
            document.getElementById('rewardDesc').textContent = desc;
            document.getElementById('rewardPoints').textContent = '+' + points + ' XP';
            document.getElementById('rewardLevel').textContent = 'Lv. ' + level;
            
            const modal = document.getElementById('rewardModal');
            const box = document.getElementById('rewardModalBox');
            modal.classList.remove('hidden');
            setTimeout(() => {
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
            }, 50);

            // Trigger Confetti
            if (typeof confetti === 'function') {
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 }
                });
            }
        }

        function closeRewardModal() {
            const modal = document.getElementById('rewardModal');
            modal.classList.add('hidden');
        }

        // Global Game Result Dispatcher
        async function submitGameScore(gameName, points, timeSpent = 0) {
            try {
                const res = await fetch('<?= base_url('/api/game/submit') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        game_name: gameName,
                        points: points,
                        time_spent: timeSpent
                    })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    const gamification = data.data;
                    const isLevelUp = gamification.level_progress?.is_level_up;
                    const newLevel = gamification.level_progress?.new_level || 1;
                    
                    showRewardModal(
                        isLevelUp ? '🎉 Level Up!' : 'Permainan Selesai!',
                        isLevelUp ? 'Hebat! Kamu naik ke Level ' + newLevel : 'Skor kamu berhasil disimpan ke leaderboard.',
                        points,
                        newLevel
                    );
                }
                return data;
            } catch (err) {
                console.error('Failed to submit game score', err);
            }
        }
    </script>
</body>
</html>
