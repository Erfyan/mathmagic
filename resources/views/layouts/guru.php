<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'MathMagic - Panel Guru') ?></title>
    
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
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .guru-bg {
            background-color: #0b1120;
            background-image: 
                radial-gradient(at 0% 0%, rgba(14, 165, 233, 0.15) 0px, transparent 40%),
                radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.12) 0px, transparent 40%);
        }
        .glass-panel {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.07);
        }
        .nav-link.active {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.8), rgba(99, 102, 241, 0.8));
            box-shadow: 0 4px 20px rgba(14, 165, 233, 0.3);
            color: #ffffff;
        }
    </style>
</head>
<body class="h-full font-sans antialiased guru-bg flex overflow-hidden">
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

    <!-- SIDEBAR -->
    <aside class="w-64 glass-panel flex flex-col justify-between p-5 border-r border-slate-800/80 z-20 flex-shrink-0">
        <div>
            <!-- Brand -->
            <a href="<?= base_url('/guru/dashboard') ?>" class="flex items-center gap-3 px-2 py-3 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-600 flex items-center justify-center font-extrabold text-white text-xl shadow-lg shadow-sky-500/30">
                    📐
                </div>
                <div>
                    <h1 class="text-lg font-black tracking-wider text-white">MATHMAGIC</h1>
                    <span class="text-[11px] font-semibold text-sky-400 tracking-wider uppercase">Panel Guru</span>
                </div>
            </a>

            <!-- Navigation Menu -->
            <nav class="space-y-1.5">
                <?php
                $uri = $_SERVER['REQUEST_URI'] ?? '';
                $isNav = fn($path) => str_contains($uri, $path);
                ?>
                <a href="<?= base_url('/guru/dashboard') ?>" class="nav-link flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all <?= $isNav('/guru/dashboard') ? 'active' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?>">
                    <i class="fa-solid fa-chart-pie text-base w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <a href="<?= base_url('/guru/banksoal') ?>" class="nav-link flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all <?= $isNav('/guru/banksoal') ? 'active' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?>">
                    <i class="fa-solid fa-folder-plus text-base w-5 text-center"></i>
                    <span>Bank Soal</span>
                </a>

                <a href="<?= base_url('/guru/leaderboard') ?>" class="nav-link flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all <?= $isNav('/guru/leaderboard') ? 'active' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?>">
                    <i class="fa-solid fa-ranking-star text-base w-5 text-center"></i>
                    <span>Papan Prestasi</span>
                </a>

                <a href="<?= base_url('/guru/reports') ?>" class="nav-link flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all <?= $isNav('/guru/reports') ? 'active' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?>">
                    <i class="fa-solid fa-file-waveform text-base w-5 text-center"></i>
                    <span>Laporan & Analitik</span>
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
                <h2 class="text-xl font-bold text-white"><?= e($title ?? 'Panel Guru') ?></h2>
                <span class="px-3 py-1 bg-sky-500/10 border border-sky-500/30 text-sky-400 text-xs font-semibold rounded-full">
                    <?= e($user['mapel'] ?? 'Pengajar Matematika') ?>
                </span>
            </div>

            <!-- Profile Info -->
            <div class="flex items-center gap-4">
                <a href="<?= base_url('/profile') ?>" class="flex items-center gap-3 group">
                    <div class="text-right">
                        <div class="text-sm font-bold text-white group-hover:text-sky-400 transition-colors"><?= e($user['fullname'] ?? 'Guru') ?></div>
                        <div class="text-[11px] font-medium text-slate-400">Guru / Pengajar</div>
                    </div>
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-600 p-0.5 shadow-md shadow-sky-500/20">
                        <?php if (!empty($user['avatar']) && file_exists(__DIR__ . '/../../../public/' . ltrim($user['avatar'], '/'))): ?>
                            <img src="<?= upload_url(basename($user['avatar'])) ?>" class="w-full h-full object-cover rounded-[14px]" alt="Avatar">
                        <?php else: ?>
                            <div class="w-full h-full rounded-[14px] bg-slate-800 flex items-center justify-center font-bold text-white">
                                <?= strtoupper(substr($user['fullname'] ?? 'G', 0, 1)) ?>
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
</body>
</html>
