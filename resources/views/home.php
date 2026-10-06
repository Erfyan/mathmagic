<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MATHMAGIC - Belajar Matematika Jadi Petualangan Seru!</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
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

    <style>
        .hero-bg {
            background-image: 
                radial-gradient(at 20% 0%, rgba(124, 58, 237, 0.3) 0px, transparent 50%),
                radial-gradient(at 80% 20%, rgba(236, 72, 153, 0.25) 0px, transparent 50%),
                radial-gradient(at 50% 90%, rgba(59, 130, 246, 0.2) 0px, transparent 50%);
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body class="min-h-full font-sans antialiased hero-bg flex flex-col justify-between">
    <!-- Navbar -->
    <header class="max-w-7xl mx-auto w-full px-6 py-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-brand-600 to-pink-500 flex items-center justify-center font-black text-white text-2xl shadow-lg shadow-brand-500/30">
                ∑
            </div>
            <span class="text-xl font-black tracking-wider text-white">MATHMAGIC</span>
        </div>

        <div class="flex items-center gap-4">
            <a href="<?= base_url('/login') ?>" class="px-5 py-2.5 rounded-xl font-bold text-sm text-slate-200 hover:text-white hover:bg-slate-800/60 transition-all">
                Masuk
            </a>
            <a href="<?= base_url('/register') ?>" class="px-6 py-2.5 rounded-xl font-bold text-sm bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white shadow-lg shadow-brand-600/30 transition-all hover:scale-105">
                Daftar Gratis 🚀
            </a>
        </div>
    </header>

    <!-- Hero Content -->
    <section class="max-w-5xl mx-auto px-6 py-16 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand-500/10 border border-brand-500/30 text-brand-400 text-xs font-bold mb-8">
            ✨ Platform Pembelajaran Matematika Modern & Gamifikasi
        </div>

        <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-6 leading-tight">
            Belajar Matematika <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 via-pink-400 to-amber-300">
                Lebih Seru & Menantang!
            </span>
        </h1>

        <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed font-normal">
            Tingkatkan kemampuan berhitung, pecahkan teka-teki logika, menangkan balapan angka, dan raih badge prestasi tertinggi bersama teman sekelasmu.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="<?= base_url('/register') ?>" class="px-8 py-4 rounded-2xl font-black text-base bg-gradient-to-r from-brand-600 to-pink-600 hover:from-brand-500 hover:to-pink-500 text-white shadow-2xl shadow-brand-600/40 transition-all hover:scale-105">
                Mulai Petualangan Sekarang 🎮
            </a>
            <a href="<?= base_url('/login') ?>" class="px-8 py-4 rounded-2xl font-bold text-base glass-card text-white hover:bg-slate-800 transition-all">
                Sudah Punya Akun? Masuk
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="max-w-6xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Feature 1 -->
            <div class="glass-card p-8 rounded-3xl hover:border-brand-500/40 transition-all group">
                <div class="w-14 h-14 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">
                    🎮
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Game Interaktif</h3>
                <p class="text-slate-400 text-sm leading-relaxed mb-4">
                    Math Race, Adventure Runner, Puzzle Logika, dan Kuis Kilat untuk melatih kecepatan serta ketajaman logika berpikirmu.
                </p>
                <div class="flex gap-2">
                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300">⚡ Math Race</span>
                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300">🧩 Puzzle</span>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="glass-card p-8 rounded-3xl hover:border-brand-500/40 transition-all group">
                <div class="w-14 h-14 rounded-2xl bg-brand-500/20 text-brand-400 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">
                    🏆
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Gamifikasi & Peringkat</h3>
                <p class="text-slate-400 text-sm leading-relaxed mb-4">
                    Kumpulkan EXP poin, naikkan Level akunmu, raih koleksi Badge juara, dan jadilah yang terbaik di papan peringkat mingguan!
                </p>
                <div class="flex gap-2">
                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300">👑 Leaderboard</span>
                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300">⭐ Badges</span>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="glass-card p-8 rounded-3xl hover:border-brand-500/40 transition-all group">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">
                    📚
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Bank Soal Terintegrasi</h3>
                <p class="text-slate-400 text-sm leading-relaxed mb-4">
                    Materi kurikulum lengkap SD hingga SMA yang dikurasi oleh para guru ahli dengan tingkat kesulitan berjenjang.
                </p>
                <div class="flex gap-2">
                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300">📘 SD - SMA</span>
                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300">🎯 Analitik</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="max-w-7xl mx-auto w-full px-6 py-8 text-center text-xs text-slate-500 border-t border-slate-900 mt-12">
        <p>&copy; <?= date('Y') ?> MathMagic Learning System. Arsitektur Modern MVC & PHP 8.x.</p>
    </footer>
</body>
</html>
