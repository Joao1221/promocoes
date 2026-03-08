<section class="mx-auto max-w-4xl">
    <h1 class="text-3xl font-black"><?= $product ? 'Editar produto' : 'Cadastrar produto' ?></h1>
    <form action="<?= e(url('lojista/produtos')) ?>" method="POST" enctype="multipart/form-data" class="mt-8 grid gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 md:grid-cols-2">
        <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
        <?php if ($product): ?>
            <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
        <?php endif; ?>
        <input type="text" name="nome" value="<?= e($product['nome'] ?? '') ?>" placeholder="Nome do produto" class="rounded-2xl border border-slate-200 px-4 py-3 md:col-span-2" required>
        <select name="categoria_id" class="rounded-2xl border border-slate-200 px-4 py-3 md:col-span-2" required>
            <option value="">Categoria</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= (int) $category['id'] ?>" <?= (int) ($product['categoria_id'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>><?= e($category['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        <textarea name="descricao" placeholder="Descricao" class="rounded-2xl border border-slate-200 px-4 py-3 md:col-span-2" required><?= e($product['descricao'] ?? '') ?></textarea>
        <input type="number" step="0.01" name="preco_original" value="<?= e($product['preco_original'] ?? '') ?>" placeholder="Preco original" class="rounded-2xl border border-slate-200 px-4 py-3" required>
        <input type="number" step="0.01" name="preco_promocional" value="<?= e($product['preco_promocional'] ?? '') ?>" placeholder="Preco promocional" class="rounded-2xl border border-slate-200 px-4 py-3">
        <input type="number" name="estoque" value="<?= e($product['estoque'] ?? '') ?>" placeholder="Estoque" class="rounded-2xl border border-slate-200 px-4 py-3" required>
        <input type="text" name="sku" value="<?= e($product['sku'] ?? '') ?>" placeholder="SKU" class="rounded-2xl border border-slate-200 px-4 py-3" required>
        <label class="rounded-2xl border border-dashed border-slate-300 px-4 py-5 text-sm md:col-span-2">Imagem principal
            <input type="file" name="imagem_principal" accept=".jpg,.jpeg,.png,.webp" class="mt-2 block w-full">
        </label>
        <label class="flex items-center gap-3 text-sm md:col-span-2">
            <input type="checkbox" name="destaque" value="1" <?= !empty($product['destaque']) ? 'checked' : '' ?>>
            Produto em destaque
        </label>
        <button class="rounded-2xl bg-blue-600 px-5 py-3 font-bold text-white md:col-span-2">Salvar produto</button>
    </form>
</section>
