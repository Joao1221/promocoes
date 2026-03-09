<section class="mx-auto max-w-7xl">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-3xl font-black">Categorias</h1>
        <div class="flex flex-wrap items-center gap-2">
            <span class="rounded-2xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700"><?= count($categories) ?> cadastradas</span>
            <form action="<?= e(url('admin/categorias/icones/aplicar')) ?>" method="POST">
                <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                <button class="rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Aplicar icones padrao</button>
            </form>
        </div>
    </div>

    <div class="mt-8 grid gap-6 xl:grid-cols-[360px_1fr]">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-xl font-black">Nova categoria</h2>
            <form action="<?= e(url('admin/categorias')) ?>" method="POST" class="mt-5 space-y-4">
                <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nome</label>
                    <input type="text" name="nome" placeholder="Ex.: Pet Shop" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Icone</label>
                    <select name="icone" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                        <option value="">Selecione</option>
                        <?php foreach ($iconOptions as $icon): ?>
                            <option value="<?= e($icon) ?>"><?= e($icon) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button class="w-full rounded-2xl bg-blue-600 px-4 py-3 font-bold text-white">Salvar categoria</button>
            </form>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-xl font-black">Categorias existentes</h2>
            <?php if (empty($categories)): ?>
                <p class="mt-4 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">Nenhuma categoria cadastrada.</p>
            <?php else: ?>
                <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                    <?php foreach ($categories as $category): ?>
                        <article class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                            <div class="inline-flex rounded-xl bg-white p-2 text-slate-700 ring-1 ring-slate-200">
                                <?= category_icon_svg($category['icone'] ?? null) ?>
                            </div>
                            <p class="mt-3 text-lg font-bold text-slate-900"><?= e($category['nome']) ?></p>
                            <p class="mt-1 text-sm text-slate-500">Slug: <?= e($category['slug']) ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
