<div class="max-w-3xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-white">⚙️ Pengaturan Profil</h2>
            <p class="text-slate-400 text-sm mt-1">Perbarui informasi profil dan kata sandi akunmu.</p>
        </div>
    </div>

    <!-- Profile Form Card -->
    <div class="glass-panel p-8 rounded-3xl space-y-6">
        <form action="<?= base_url('/profile') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?= csrf_field() ?>

            <!-- Avatar Preview & Upload -->
            <div class="flex items-center gap-6 pb-6 border-b border-slate-800">
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-brand-600 to-indigo-600 p-1 shadow-lg shadow-brand-500/20 flex-shrink-0">
                    <?php if (!empty($user['avatar']) && file_exists(__DIR__ . '/../../../public/' . ltrim($user['avatar'], '/'))): ?>
                        <img src="<?= upload_url(basename($user['avatar'])) ?>" class="w-full h-full object-cover rounded-2xl" alt="Avatar">
                    <?php else: ?>
                        <div class="w-full h-full rounded-2xl bg-slate-800 flex items-center justify-center font-black text-2xl text-white">
                            <?= strtoupper(substr($user['fullname'] ?? 'U', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-300">Ganti Foto Profil</label>
                    <input type="file" name="avatar" accept="image/*" class="text-xs text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-700 cursor-pointer">
                    <span class="text-[10px] text-slate-500 block">Format: JPG, PNG, WEBP (Maksimal 2MB)</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="fullname" value="<?= e($user['fullname']) ?>" required
                           class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700/80 rounded-xl text-xs text-white focus:outline-none focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Email (Akun)</label>
                    <input type="email" value="<?= e($user['email']) ?>" disabled
                           class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-800 rounded-xl text-xs text-slate-400 cursor-not-allowed">
                </div>
            </div>

            <?php if ($user['role'] === 'siswa'): ?>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Jenjang / Kelas</label>
                    <select name="kelas" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700/80 rounded-xl text-xs text-white">
                        <option value="SD-5" <?= ($user['kelas'] ?? '') === 'SD-5' ? 'selected' : '' ?>>SD Kelas 5</option>
                        <option value="SD-6" <?= ($user['kelas'] ?? '') === 'SD-6' ? 'selected' : '' ?>>SD Kelas 6</option>
                        <option value="SMP-7" <?= ($user['kelas'] ?? '') === 'SMP-7' ? 'selected' : '' ?>>SMP Kelas 7</option>
                        <option value="SMP-8" <?= ($user['kelas'] ?? '') === 'SMP-8' ? 'selected' : '' ?>>SMP Kelas 8</option>
                        <option value="SMP-9" <?= ($user['kelas'] ?? '') === 'SMP-9' ? 'selected' : '' ?>>SMP Kelas 9</option>
                        <option value="SMA-10" <?= ($user['kelas'] ?? '') === 'SMA-10' ? 'selected' : '' ?>>SMA Kelas 10</option>
                        <option value="SMA-11" <?= ($user['kelas'] ?? '') === 'SMA-11' ? 'selected' : '' ?>>SMA Kelas 11</option>
                        <option value="SMA-12" <?= ($user['kelas'] ?? '') === 'SMA-12' ? 'selected' : '' ?>>SMA Kelas 12</option>
                    </select>
                </div>
            <?php else: ?>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Mata Pelajaran</label>
                    <input type="text" name="mapel" value="<?= e($user['mapel'] ?? 'Matematika') ?>"
                           class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700/80 rounded-xl text-xs text-white">
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">Ganti Kata Sandi Baru (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password" placeholder="••••••••"
                       class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700/80 rounded-xl text-xs text-white focus:outline-none focus:border-brand-500">
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-800">
                <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl shadow-lg transition-all">
                    Simpan Perubahan ✨
                </button>
            </div>
        </form>
    </div>
</div>
