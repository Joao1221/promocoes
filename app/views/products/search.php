<section class="mx-auto max-w-7xl px-4 py-10">
    <div class="grid gap-8 lg:grid-cols-[280px_1fr]">
        <aside class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h1 class="text-2xl font-black">Busca inteligente</h1>
            <form action="<?= e(url('buscar')) ?>" method="GET" class="mt-6 space-y-4">
                <input type="search" name="q" value="<?= e($filters['q'] ?? '') ?>" placeholder="Produto, loja ou categoria" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                <select name="categoria" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                    <option value="">Todas categorias</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= e($category['slug']) ?>" <?= ($filters['categoria'] ?? '') === $category['slug'] ? 'selected' : '' ?>><?= e($category['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="bairro" value="<?= e($filters['bairro'] ?? '') ?>" placeholder="Bairro" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                <div class="grid grid-cols-2 gap-3">
                    <input type="number" step="0.01" name="preco_min" value="<?= e($filters['preco_min'] ?? '') ?>" placeholder="Min" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                    <input type="number" step="0.01" name="preco_max" value="<?= e($filters['preco_max'] ?? '') ?>" placeholder="Max" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <button class="w-full rounded-2xl bg-blue-600 px-4 py-3 font-bold text-white">Filtrar</button>
            </form>
        </aside>
        <div>
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-black">Resultados</h2>
                <p class="text-sm text-slate-500"><?= count($products) ?> itens</p>
            </div>
            <div class="grid grid-cols-2 gap-4 xl:grid-cols-3">
                <?php foreach ($products as $product): require __DIR__ . '/../partials/product-card.php'; endforeach; ?>
            </div>
        </div>
    </div>
</section>
