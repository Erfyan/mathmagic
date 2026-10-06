<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl md:text-3xl font-black text-white">🎮 Arena Game Matematika</h2>
            <p class="text-slate-400 text-sm mt-1">Pilih arena game favoritmu, kumpulkan skor setinggi-tingginya, dan buka badge eksklusif!</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 bg-brand-500/10 border border-brand-500/30 text-brand-400 rounded-2xl text-xs font-bold">
                ⚡ Total Skor: <?= number_format($stats['total_points'] ?? 0) ?> XP
            </span>
        </div>
    </div>

    <!-- Games Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- 1. Math Race -->
        <div class="glass-panel p-8 rounded-3xl border border-amber-500/30 relative overflow-hidden group hover:shadow-2xl hover:shadow-amber-500/10 transition-all">
            <div class="flex items-start justify-between mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-500 flex items-center justify-center text-3xl shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform">
                    🏎️
                </div>
                <span class="px-3 py-1 bg-amber-500/20 text-amber-300 text-xs font-bold rounded-full">
                    Kecepatan
                </span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Math Race (Pacuan Hitung)</h3>
            <p class="text-slate-300 text-sm mb-6 leading-relaxed">
                Jawab soal operasi hitung dengan cepat dan akurat untuk memacu mobil balapmu mendahului lawan hingga garis finish!
            </p>
            <a href="<?= base_url('/siswa/games/race') ?>" class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 text-white font-black text-sm rounded-2xl shadow-xl shadow-amber-500/30 transition-all hover:scale-105">
                <span>Mulai Balapan</span>
                <i class="fa-solid fa-flag-checkered"></i>
            </a>
        </div>

        <!-- 2. Math Adventure -->
        <div class="glass-panel p-8 rounded-3xl border border-brand-500/30 relative overflow-hidden group hover:shadow-2xl hover:shadow-brand-500/10 transition-all">
            <div class="flex items-start justify-between mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-600 flex items-center justify-center text-3xl shadow-lg shadow-brand-600/30 group-hover:scale-110 transition-transform">
                    🏃
                </div>
                <span class="px-3 py-1 bg-brand-500/20 text-brand-300 text-xs font-bold rounded-full">
                    Petualangan
                </span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Math Adventure (Runner 2D)</h3>
            <p class="text-slate-300 text-sm mb-6 leading-relaxed">
                Petualangan karakter pahlawan menembus monster aljabar dan mengumpulkan koin pengetahuan matematika.
            </p>
            <a href="<?= base_url('/siswa/games/adventure') ?>" class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-black text-sm rounded-2xl shadow-xl shadow-brand-600/30 transition-all hover:scale-105">
                <span>Mulai Petualangan</span>
                <i class="fa-solid fa-play"></i>
            </a>
        </div>

        <!-- 3. Puzzle Angka -->
        <div class="glass-panel p-8 rounded-3xl border border-pink-500/30 relative overflow-hidden group hover:shadow-2xl hover:shadow-pink-500/10 transition-all">
            <div class="flex items-start justify-between mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-pink-500 to-rose-600 flex items-center justify-center text-3xl shadow-lg shadow-pink-500/30 group-hover:scale-110 transition-transform">
                    🧩
                </div>
                <span class="px-3 py-1 bg-pink-500/20 text-pink-300 text-xs font-bold rounded-full">
                    Logika & Pola
                </span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Puzzle Logika Angka</h3>
            <p class="text-slate-300 text-sm mb-6 leading-relaxed">
                Lengkapi susunan grid angka yang hilang dengan menemukan pola relasi aritmatika tersembunyi.
            </p>
            <a href="<?= base_url('/siswa/games/puzzle') ?>" class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-400 hover:to-rose-500 text-white font-black text-sm rounded-2xl shadow-xl shadow-pink-500/30 transition-all hover:scale-105">
                <span>Buka Puzzle</span>
                <i class="fa-solid fa-puzzle-piece"></i>
            </a>
        </div>

        <!-- 4. Quiz Cepat -->
        <div class="glass-panel p-8 rounded-3xl border border-emerald-500/30 relative overflow-hidden group hover:shadow-2xl hover:shadow-emerald-500/10 transition-all">
            <div class="flex items-start justify-between mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-600 flex items-center justify-center text-3xl shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                    ⚡
                </div>
                <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 text-xs font-bold rounded-full">
                    Tantangan 60 Detik
                </span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Quiz Cepat (Combo Blitz)</h3>
            <p class="text-slate-300 text-sm mb-6 leading-relaxed">
                Selesaikan sebanyak mungkin pertanyaan dalam batas waktu 60 detik. Jawaban berurutan memberikan multiplier combo!
            </p>
            <a href="<?= base_url('/siswa/games/quiz-cepat') ?>" class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-black text-sm rounded-2xl shadow-xl shadow-emerald-500/30 transition-all hover:scale-105">
                <span>Mulai Tantangan</span>
                <i class="fa-solid fa-bolt"></i>
            </a>
        </div>
    </div>
</div>
