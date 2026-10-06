<div class="glass-card p-8 rounded-3xl shadow-2xl border border-slate-800" x-data="{ role: 'siswa' }">
    <!-- Header -->
    <div class="text-center mb-6">
        <a href="<?= base_url('/') ?>" class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-pink-500 font-black text-white text-3xl shadow-lg shadow-brand-500/30 mb-3 hover:scale-105 transition-transform">
            ∑
        </a>
        <h2 class="text-2xl font-black text-white">Buat Akun Baru</h2>
        <p class="text-slate-400 text-xs mt-1">Gabung bersama ribuan petualang matematika!</p>
    </div>

    <!-- Register Form -->
    <form action="<?= base_url('/register') ?>" method="POST" class="space-y-4">
        <?= csrf_field() ?>

        <!-- Role Selector Switch -->
        <div>
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Saya Mendaftar Sebagai:</label>
            <div class="grid grid-cols-2 gap-3 p-1.5 bg-slate-900/90 rounded-2xl border border-slate-800">
                <button type="button" @click="role = 'siswa'" 
                        :class="role === 'siswa' ? 'bg-brand-600 text-white shadow-md' : 'text-slate-400 hover:text-white'"
                        class="py-2.5 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-all">
                    <span>🎒 Siswa</span>
                </button>
                <button type="button" @click="role = 'guru'" 
                        :class="role === 'guru' ? 'bg-sky-600 text-white shadow-md' : 'text-slate-400 hover:text-white'"
                        class="py-2.5 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-all">
                    <span>👨‍🏫 Guru / Pengajar</span>
                </button>
            </div>
            <input type="hidden" name="role" :value="role">
        </div>

        <div>
            <label for="fullname" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
            <input type="text" id="fullname" name="fullname" value="<?= e(old('fullname')) ?>" required 
                   placeholder="Masukkan nama lengkap"
                   class="w-full px-4 py-2.5 bg-slate-900/90 border border-slate-700/70 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Email</label>
            <input type="email" id="email" name="email" value="<?= e(old('email')) ?>" required 
                   placeholder="nama@email.com"
                   class="w-full px-4 py-2.5 bg-slate-900/90 border border-slate-700/70 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all">
        </div>

        <!-- Conditional Class/Subject selection -->
        <div x-show="role === 'siswa'">
            <label for="kelas" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Jenjang / Kelas</label>
            <select id="kelas" name="kelas" class="w-full px-4 py-2.5 bg-slate-900/90 border border-slate-700/70 rounded-xl text-white text-sm focus:outline-none focus:border-brand-500">
                <option value="SD-5">SD Kelas 5</option>
                <option value="SD-6">SD Kelas 6</option>
                <option value="SMP-7">SMP Kelas 7</option>
                <option value="SMP-8">SMP Kelas 8</option>
                <option value="SMP-9">SMP Kelas 9</option>
                <option value="SMA-10">SMA Kelas 10</option>
                <option value="SMA-11">SMA Kelas 11</option>
                <option value="SMA-12">SMA Kelas 12</option>
            </select>
        </div>

        <div x-show="role === 'guru'" style="display:none;">
            <label for="mapel" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Mata Pelajaran</label>
            <input type="text" id="mapel" name="mapel" placeholder="Matematika / IPA" value="Matematika"
                   class="w-full px-4 py-2.5 bg-slate-900/90 border border-slate-700/70 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-sky-500">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Min. 6 karakter"
                       class="w-full px-4 py-2.5 bg-slate-900/90 border border-slate-700/70 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-500">
            </div>
            <div>
                <label for="confirm_password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Konfirmasi</label>
                <input type="password" id="confirm_password" name="confirm_password" required 
                       placeholder="Ulangi sandi"
                       class="w-full px-4 py-2.5 bg-slate-900/90 border border-slate-700/70 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-500">
            </div>
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-brand-600/30 transition-all hover:scale-[1.02] mt-2">
            Daftar Akun 🚀
        </button>
    </form>

    <!-- Footer -->
    <div class="mt-6 pt-5 border-t border-slate-800 text-center text-xs text-slate-400">
        Sudah memiliki akun? 
        <a href="<?= base_url('/login') ?>" class="font-bold text-brand-400 hover:text-brand-300 ml-1">
            Masuk di sini
        </a>
    </div>
</div>
