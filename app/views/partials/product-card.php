<?php $price = active_price($product); $discount = discount_percent((float) $product['preco_original'], isset($product['preco_promocional']) ? (float) $product['preco_promocional'] : null); ?>
<article class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
    <a href="<?= e(url('produto/' . $product['slug'])) ?>" class="block">
        <img src="<?= e(upload_url('produtos', $product['imagem_principal'])) ?>" alt="<?= e($product['nome']) ?>" class="h-52 w-full object-cover" loading="lazy">
    </a>
    <div class="space-y-3 p-4">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-600"><?= e($product['categoria_nome'] ?? '') ?></p>
                <a href="<?= e(url('produto/' . $product['slug'])) ?>" class="mt-1 block line-clamp-2 text-base font-bold text-slate-900"><?= e($product['nome']) ?></a>
            </div>
            <?php if ($discount > 0): ?>
                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-600">-<?= $discount ?>%</span>
            <?php endif; ?>
        </div>
        <div>
            <?php if (!empty($product['preco_promocional'])): ?>
                <p class="text-sm text-slate-400 line-through"><?= e(format_price((float) $product['preco_original'])) ?></p>
            <?php endif; ?>
            <p class="text-2xl font-black text-slate-900"><?= e(format_price($price)) ?></p>
        </div>
        <div class="flex items-center justify-between gap-3 text-sm">
            <a href="<?= e(url('loja/' . $product['loja_slug'])) ?>" class="text-slate-500"><?= e($product['nome_loja']) ?></a>
            <form action="<?= e(url('carrinho/adicionar')) ?>" method="POST" class="flex items-center gap-2">
                <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                <input type="hidden" name="slug" value="<?= e($product['slug']) ?>">
                <input type="number" name="quantidade" value="1" min="1" max="<?= max(1, (int) ($product['estoque'] ?? 1)) ?>" class="w-16 rounded-xl border border-slate-200 px-2 py-2 text-center text-sm">
                <button class="rounded-2xl bg-blue-600 px-3 py-2 font-semibold text-white">Comprar</button>
            </form>
        </div>
    </div>
</article>
