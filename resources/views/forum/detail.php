<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back button -->
    <a href="<?= base_url('/forum') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-white transition-colors">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Forum
    </a>

    <!-- Thread Main Post -->
    <div class="glass-panel p-8 rounded-3xl space-y-4 border border-brand-500/20">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-brand-600/30 text-brand-400 flex items-center justify-center font-bold text-sm">
                <?= strtoupper(substr($thread['fullname'], 0, 1)) ?>
            </div>
            <div>
                <span class="font-bold text-white text-sm block"><?= e($thread['fullname']) ?></span>
                <span class="text-[11px] text-slate-400"><?= ucfirst(e($thread['role'])) ?> &bull; <?= date('d M Y, H:i', strtotime($thread['created_at'])) ?></span>
            </div>
        </div>

        <h2 class="text-2xl font-black text-white leading-snug"><?= e($thread['title']) ?></h2>

        <div class="text-slate-300 text-sm leading-relaxed whitespace-pre-line pt-2">
            <?= e($thread['content']) ?>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="glass-panel p-8 rounded-3xl space-y-6">
        <h3 class="text-base font-bold text-white flex items-center gap-2">
            <span>💬</span> Tanggapan & Komentar (<?= count($comments) ?>)
        </h3>

        <!-- Comment List -->
        <div class="space-y-4">
            <?php if (empty($comments)): ?>
                <div class="text-center py-6 text-slate-500 text-xs">
                    Belum ada tanggapan. Berikan tanggapan pertamamu di bawah ini!
                </div>
            <?php else: ?>
                <?php foreach ($comments as $c): ?>
                    <div class="p-4 bg-slate-900/70 rounded-2xl border border-slate-800 space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-white"><?= e($c['fullname']) ?></span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold <?= $c['role'] === 'guru' ? 'bg-sky-500/20 text-sky-300' : 'bg-slate-800 text-slate-400' ?>">
                                    <?= ucfirst(e($c['role'])) ?>
                                </span>
                            </div>
                            <span class="text-[10px] text-slate-500"><?= date('d M Y, H:i', strtotime($c['created_at'])) ?></span>
                        </div>
                        <p class="text-slate-300 text-xs leading-relaxed whitespace-pre-line"><?= e($c['comment']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Add Comment Form -->
        <form action="<?= base_url('/forum/' . $thread['id'] . '/comment') ?>" method="POST" class="pt-4 border-t border-slate-800 space-y-3">
            <?= csrf_field() ?>
            <label class="block text-xs font-bold text-slate-300">Tuliskan Tanggapan / Jawabanmu:</label>
            <textarea name="comment" rows="3" required placeholder="Tuliskan komentar atau bantuan penyelesaian soal di sini..."
                      class="w-full px-4 py-3 bg-slate-900 border border-slate-700/80 rounded-2xl text-xs text-white focus:outline-none focus:border-brand-500 transition-all"></textarea>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl shadow-lg transition-all">
                    Kirim Komentar 🚀
                </button>
            </div>
        </form>
    </div>
</div>
