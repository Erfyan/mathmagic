<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'MathMagic - Belajar Matematika Seru & Interaktif') ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
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
    
    <!-- FontAwesome & Alpine.js & Confetti -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    
    <style>
        .glass {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glow-purple {
            box-shadow: 0 0 30px -5px rgba(139, 92, 246, 0.3);
        }
    </style>
</head>
<body class="h-full font-sans antialiased selection:bg-brand-500 selection:text-white">
    <!-- Flash Messages -->
    <?php if ($flashSuccess): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed top-5 right-5 z-50 flex items-center p-4 bg-emerald-600/90 backdrop-blur-md text-white rounded-2xl shadow-xl border border-emerald-400/30 animate-bounce">
            <i class="fa-solid fa-circle-check text-xl mr-3"></i>
            <span class="font-medium"><?= e($flashSuccess) ?></span>
        </div>
    <?php endif; ?>

    <?php if ($flashError): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed top-5 right-5 z-50 flex items-center p-4 bg-rose-600/90 backdrop-blur-md text-white rounded-2xl shadow-xl border border-rose-400/30">
            <i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
            <span class="font-medium"><?= e($flashError) ?></span>
        </div>
    <?php endif; ?>

    <main>
        <?= $content ?>
    </main>
</body>
</html>
