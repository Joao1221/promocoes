<?php $title = $title ?? config('app')['name']; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> | <?= e(config('app')['name']) ?></title>
    <meta name="description" content="<?= e($title) ?> no marketplace local de Capela-SE.">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
    <script defer src="<?= e(asset('js/app.js')) ?>"></script>
</head>
<body class="min-h-screen bg-slate-50">
    <?php require __DIR__ . '/../partials/header.php'; ?>
    <main class="min-h-[70vh]">
        <?php if (!empty($flash)): ?>
            <div class="mx-auto max-w-7xl px-4 pt-4">
                <div class="rounded-2xl px-4 py-3 text-sm <?= $flash['type'] === 'error' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' ?>">
                    <?= e($flash['message']) ?>
                </div>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </main>
    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
