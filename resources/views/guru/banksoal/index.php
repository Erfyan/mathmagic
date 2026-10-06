<div class="space-y-8" x-data="{ openModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-white">📚 Kelola Bank Soal</h2>
            <p class="text-slate-400 text-sm mt-1">Buat, filter, dan kelola bank soal latihan serta kuis matematika.</p>
        </div>
        <button @click="openModal = true" class="px-5 py-3 bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-sky-600/30 flex items-center gap-2 transition-all">
            <i class="fa-solid fa-plus"></i> Tambah Soal Baru
        </button>
    </div>

    <!-- Filter Bar -->
    <div class="glass-panel p-4 rounded-2xl flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2 text-xs">
            <span class="text-slate-400 font-bold">Filter Jenjang:</span>
            <a href="<?= base_url('/guru/banksoal') ?>" class="px-3 py-1.5 rounded-lg font-bold <?= empty($currentKelas) ? 'bg-sky-600 text-white' : 'bg-slate-800 text-slate-300' ?>">Semua</a>
            <a href="<?= base_url('/guru/banksoal?kelas=SD') ?>" class="px-3 py-1.5 rounded-lg font-bold <?= $currentKelas === 'SD' ? 'bg-sky-600 text-white' : 'bg-slate-800 text-slate-300' ?>">SD</a>
            <a href="<?= base_url('/guru/banksoal?kelas=SMP') ?>" class="px-3 py-1.5 rounded-lg font-bold <?= $currentKelas === 'SMP' ? 'bg-sky-600 text-white' : 'bg-slate-800 text-slate-300' ?>">SMP</a>
            <a href="<?= base_url('/guru/banksoal?kelas=SMA') ?>" class="px-3 py-1.5 rounded-lg font-bold <?= $currentKelas === 'SMA' ? 'bg-sky-600 text-white' : 'bg-slate-800 text-slate-300' ?>">SMA</a>
        </div>
        <span class="text-xs text-slate-400 font-semibold"><?= count($questions) ?> Soal Tersedia</span>
    </div>

    <!-- Questions Table -->
    <div class="glass-panel p-6 rounded-3xl">
        <?php if (empty($questions)): ?>
            <div class="text-center py-12 text-slate-500 text-sm">
                Belum ada soal pada filter ini.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-800">
                            <th class="py-3 px-4 font-bold">#</th>
                            <th class="py-3 px-4 font-bold">Pertanyaan</th>
                            <th class="py-3 px-4 font-bold">Jenjang</th>
                            <th class="py-3 px-4 font-bold">Kunci</th>
                            <th class="py-3 px-4 font-bold">Tingkat</th>
                            <th class="py-3 px-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        <?php foreach ($questions as $i => $q): ?>
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="py-3.5 px-4 text-slate-400"><?= $i + 1 ?></td>
                                <td class="py-3.5 px-4 font-semibold text-white max-w-md">
                                    <div class="line-clamp-2"><?= e($q['question']) ?></div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-300"><?= e($q['kelas']) ?></td>
                                <td class="py-3.5 px-4 font-black text-emerald-400"><?= e($q['correct_answer']) ?></td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded bg-slate-800 text-[10px] text-slate-300">
                                        Lv. <?= e($q['difficulty'] ?: 1) ?>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <a href="<?= base_url('/guru/banksoal/delete/' . $q['id']) ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus soal ini?')"
                                       class="px-2.5 py-1 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 font-bold text-[11px] rounded-lg transition-all">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal Form Create Soal -->
    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm" style="display:none;">
        <div @click.away="openModal = false" class="glass-panel w-full max-w-xl p-6 rounded-3xl border border-slate-700 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="text-lg font-bold text-white">Tambah Soal Baru</h3>
                <button @click="openModal = false" class="text-slate-400 hover:text-white">&times;</button>
            </div>

            <form action="<?= base_url('/guru/banksoal/create') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Jenjang Kelas</label>
                        <select name="kelas" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white">
                            <option value="SD">SD</option>
                            <option value="SMP" selected>SMP</option>
                            <option value="SMA">SMA</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Tingkat Kesulitan (1 - 5)</label>
                        <select name="difficulty" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white">
                            <option value="1">1 (Sangat Mudah)</option>
                            <option value="2">2 (Mudah)</option>
                            <option value="3" selected>3 (Sedang)</option>
                            <option value="4">4 (Sulit)</option>
                            <option value="5">5 (Olimpiade)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Pertanyaan / Soal</label>
                    <textarea name="question" rows="3" required placeholder="Tuliskan teks pertanyaan matematika..."
                              class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Pilihan A</label>
                        <input type="text" name="option_a" required placeholder="Opsi A" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Pilihan B</label>
                        <input type="text" name="option_b" required placeholder="Opsi B" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Pilihan C</label>
                        <input type="text" name="option_c" placeholder="Opsi C" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Pilihan D</label>
                        <input type="text" name="option_d" placeholder="Opsi D" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Kunci Jawaban Benar</label>
                    <select name="correct_answer" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white">
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-800">
                    <button type="button" @click="openModal = false" class="px-4 py-2 bg-slate-800 text-slate-300 text-xs font-bold rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold rounded-xl shadow-md">Simpan Soal</button>
                </div>
            </form>
        </div>
    </div>
</div>
