<?php $title = $title ?? config('app')['name']; ?>
<?php $isMarketplaceUser = $authUser && in_array(($authUser['role'] ?? ''), ['consumidor', 'lojista'], true); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> | <?= e(config('app')['name']) ?></title>
    <link rel="icon" type="image/png" href="<?= e(url('public/img/favicon.png')) ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
    <script defer src="<?= e(asset('js/app.js')) ?>"></script>
</head>
<body class="bg-slate-100">
    <div class="min-h-screen lg:grid lg:grid-cols-[280px_1fr]">
        <aside class="bg-slate-900 p-6 text-white">
            <a class="text-2xl font-black" href="<?= e(url('')) ?>">Capela Market</a>
            <p class="mt-2 text-sm text-slate-300"><?= e($authUser['nome'] ?? '') ?></p>
            <nav class="mt-8 space-y-3 text-sm">
                <?php if ($isMarketplaceUser): ?>
                    <a class="block rounded-xl bg-slate-800 px-4 py-3" href="<?= e(url('lojista')) ?>">Dashboard</a>
                    <a class="block rounded-xl bg-slate-800 px-4 py-3" href="<?= e(url('lojista/loja')) ?>">Minha loja</a>
                    <a class="block rounded-xl bg-slate-800 px-4 py-3" href="<?= e(url('lojista/produtos/novo')) ?>">Novo produto</a>
                <?php endif; ?>
                <?php if (($authUser['role'] ?? '') === 'admin'): ?>
                    <a class="block rounded-xl bg-slate-800 px-4 py-3" href="<?= e(url('admin')) ?>">Moderacao</a>
                    <a class="block rounded-xl bg-slate-800 px-4 py-3" href="<?= e(url('admin/destaques')) ?>">Destaques</a>
                    <a class="block rounded-xl bg-slate-800 px-4 py-3" href="<?= e(url('admin/categorias')) ?>">Categorias</a>
                <?php endif; ?>
            </nav>
        </aside>
        <main class="p-6 lg:p-10">
            <?php if (!empty($flash)): ?>
                <div class="mb-6 rounded-2xl px-4 py-3 text-sm <?= $flash['type'] === 'error' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' ?>">
                    <?= e($flash['message']) ?>
                </div>
            <?php endif; ?>
            <?= $content ?>
        </main>
    </div>
</body>
</html>
