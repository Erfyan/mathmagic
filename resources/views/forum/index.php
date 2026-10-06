<div class="space-y-8" x-data="{ openThreadModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-white">💬 Forum Diskusi Matematika</h2>
            <p class="text-slate-400 text-sm mt-1">Tanyakan soal yang sulit, diskusikan rumus, dan berkolaborasi bersama teman dan guru.</p>
        </div>
        <button @click="openThreadModal = true" class="px-5 py-3 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-brand-600/30 flex items-center gap-2 transition-all">
            <i class="fa-solid fa-pen-to-square"></i> Buat Topik Baru
        </button>
    </div>

    <!-- Threads List -->
    <div class="space-y-4">
        <?php if (empty($threads)): ?>
            <div class="glass-panel p-12 text-center rounded-3xl text-slate-500 text-sm">
                Belum ada topik diskusi. Jadilah yang pertama membuat topik!
            </div>
        <?php else: ?>
            <?php foreach ($threads as $t): ?>
                <a href="<?= base_url('/forum/' . $t['id']) ?>" class="glass-panel p-6 rounded-3xl block hover:border-brand-500/40 transition-all group">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-xs">
                                <span class="font-bold text-brand-400"><?= e($t['fullname']) ?></span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= $t['role'] === 'guru' ? 'bg-sky-500/20 text-sky-300' : 'bg-slate-800 text-slate-300' ?>">
                                    <?= ucfirst(e($t['role'])) ?>
                                </span>
                                <span class="text-slate-500">&bull; <?= date('d M Y, H:i', strtotime($t['created_at'])) ?></span>
                            </div>
                            <h3 class="text-lg font-bold text-white group-hover:text-brand-400 transition-colors">
                                <?= e($t['title']) ?>
                            </h3>
                            <p class="text-slate-300 text-xs line-clamp-2 leading-relaxed">
                                <?= e($t['content']) ?>
                            </p>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-900/80 rounded-xl text-slate-400 text-xs font-bold flex-shrink-0">
                            <i class="fa-solid fa-comment-dots"></i>
                            <span><?= e($t['total_comments']) ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Modal Buat Topik Baru -->
    <div x-show="openThreadModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm" style="display:none;">
        <div @click.away="openThreadModal = false" class="glass-panel w-full max-w-lg p-6 rounded-3xl border border-slate-700 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="text-lg font-bold text-white">Buat Topik Diskusi Baru</h3>
                <button @click="openThreadModal = false" class="text-slate-400 hover:text-white">&times;</button>
            </div>

            <form action="<?= base_url('/forum/new') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Judul Topik</label>
                    <input type="text" name="title" required placeholder="Contoh: Cara mudah menyelesaikan rumus Pythagoras"
                           class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Isi Pertanyaan / Penjelasan</label>
                    <textarea name="content" rows="4" required placeholder="Jelaskan pertanyaan atau topik yang ingin didiskusikan..."
                              class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-brand-500"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-800">
                    <button type="button" @click="openThreadModal = false" class="px-4 py-2 bg-slate-800 text-slate-300 text-xs font-bold rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold rounded-xl shadow-md">Terbitkan Topik</button>
                </div>
            </form>
        </div>
    </div>
</div>
