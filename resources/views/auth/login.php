<div class="glass-card p-8 rounded-3xl shadow-2xl border border-slate-800">
    <!-- Header -->
    <div class="text-center mb-8">
        <a href="<?= base_url('/') ?>" class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-pink-500 font-black text-white text-3xl shadow-lg shadow-brand-500/30 mb-4 hover:scale-105 transition-transform">
            ∑
        </a>
        <h2 class="text-2xl font-black text-white">Selamat Datang!</h2>
        <p class="text-slate-400 text-sm mt-1">Masuk ke akun MathMagic kamu</p>
    </div>

    <!-- Login Form -->
    <form action="<?= base_url('/login') ?>" method="POST" class="space-y-5">
        <?= csrf_field() ?>

        <div>
            <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <input type="email" id="email" name="email" value="<?= e(old('email')) ?>" required 
                       placeholder="nama@email.com"
                       class="w-full pl-10 pr-4 py-3 bg-slate-900/90 border border-slate-700/70 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="text-xs font-bold text-slate-300 uppercase tracking-wider">Kata Sandi</label>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <input type="password" id="password" name="password" required 
                       placeholder="••••••••"
                       class="w-full pl-10 pr-4 py-3 bg-slate-900/90 border border-slate-700/70 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
            </div>
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-brand-600/30 transition-all hover:scale-[1.02]">
            Masuk Sekarang 🚀
        </button>
    </form>

    <!-- Footer -->
    <div class="mt-8 pt-6 border-t border-slate-800 text-center text-xs text-slate-400">
        Belum punya akun? 
        <a href="<?= base_url('/register') ?>" class="font-bold text-brand-400 hover:text-brand-300 ml-1">
            Daftar Sekarang
        </a>
    </div>
</div>
