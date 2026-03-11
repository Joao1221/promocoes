<?php $price = active_price($product); ?>
<?php $gallery = $product['imagens'] ?? [$product['imagem_principal']]; ?>
<section class="mx-auto max-w-7xl px-4 py-10">
    <div class="grid gap-8 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:grid-cols-2">
        <div x-data="{ activeImage: '<?= e(upload_url('produtos', $gallery[0] ?? $product['imagem_principal'])) ?>' }">
            <div class="flex items-center justify-center rounded-3xl bg-slate-50 p-4">
                <img :src="activeImage" src="<?= e(upload_url('produtos', $gallery[0] ?? $product['imagem_principal'])) ?>" alt="<?= e($product['nome']) ?>" class="h-[260px] w-full max-w-[360px] rounded-3xl object-contain lg:h-[360px]" loading="lazy">
            </div>
            <?php if (count($gallery) > 1): ?>
                <div class="mt-4 grid grid-cols-4 gap-3">
                    <?php foreach ($gallery as $image): ?>
                        <button type="button" class="rounded-2xl border border-slate-200 bg-slate-50 p-2 transition hover:border-blue-400" @click="activeImage = '<?= e(upload_url('produtos', $image)) ?>'">
                            <img src="<?= e(upload_url('produtos', $image)) ?>" alt="<?= e($product['nome']) ?>" class="h-16 w-full rounded-xl object-contain">
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div>
            <p class="text-sm font-semibold text-blue-600"><?= e($product['categoria_nome']) ?></p>
            <h1 class="mt-2 text-2xl font-black leading-tight sm:text-3xl lg:text-[2rem]"><?= e($product['nome']) ?></h1>
            <p class="mt-4 text-slate-600"><?= e($product['descricao']) ?></p>
            <div class="mt-6">
                <?php if (!empty($product['preco_promocional'])): ?>
                    <p class="text-lg text-slate-400 line-through"><?= e(format_price((float) $product['preco_original'])) ?></p>
                <?php endif; ?>
                <p class="text-4xl font-black text-slate-900"><?= e(format_price($price)) ?></p>
            </div>
            <div class="mt-6 grid grid-cols-2 gap-3">
                <a href="<?= e(url('loja/' . $product['loja_slug'])) ?>" class="rounded-2xl bg-slate-100 px-4 py-3 text-center text-sm font-semibold">Ver loja</a>
                <a href="https://wa.me/55<?= e($product['whatsapp']) ?>" target="_blank" rel="noopener" class="rounded-2xl bg-green-500 px-4 py-3 text-center text-sm font-semibold text-white">Falar no WhatsApp</a>
            </div>
            <form action="<?= e(url('carrinho/adicionar')) ?>" method="POST" class="mt-6 flex gap-3">
                <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                <input type="hidden" name="slug" value="<?= e($product['slug']) ?>">
                <input type="number" name="quantidade" value="1" min="1" class="w-24 rounded-2xl border border-slate-200 px-4 py-3 text-sm">
                <button class="rounded-2xl bg-blue-600 px-6 py-3 text-sm font-bold text-white">Adicionar ao carrinho</button>
            </form>
        </div>
    </div>
</section>
