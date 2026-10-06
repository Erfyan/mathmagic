<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'MathMagic - Masuk / Daftar') ?></title>
    
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .auth-bg {
            background-image: 
                radial-gradient(at 0% 0%, rgba(124, 58, 237, 0.25) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(236, 72, 153, 0.2) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(59, 130, 246, 0.15) 0px, transparent 50%);
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="h-full font-sans antialiased auth-bg flex items-center justify-center p-4">
    <!-- Flash Messages -->
    <?php if ($flashSuccess): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed top-5 right-5 z-50 flex items-center p-4 bg-emerald-600/90 backdrop-blur-md text-white rounded-2xl shadow-xl border border-emerald-400/30">
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

    <div class="w-full max-w-md">
        <?= $content ?>
    </div>
</body>
</html>
