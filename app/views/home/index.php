<section class="bg-gradient-to-r from-blue-600 to-blue-400 text-white">
    <div class="mx-auto grid max-w-7xl gap-6 px-4 py-7 lg:grid-cols-[1.2fr_.8fr] lg:gap-10 lg:py-14">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-blue-100">Marketplace municipal</p>
            <h1 class="mt-2 text-2xl font-black leading-tight sm:text-3xl lg:mt-4 lg:text-6xl">Compre de quem movimenta Capela-SE.</h1>
            <p class="mt-2 max-w-2xl text-sm text-blue-50 sm:text-base lg:mt-4 lg:text-lg">Promoções locais, lojas da cidade e pedidos online em um shopping virtual feito para o comércio capelense.</p>
            <div class="mt-4 flex flex-wrap gap-2 lg:mt-8 lg:gap-3">
                <a href="<?= e(url('buscar')) ?>" class="rounded-[4px] bg-white px-6 py-3 font-bold text-blue-600">Explorar ofertas</a>
                <a href="<?= e(url('cadastro')) ?>" class="rounded-[4px] border border-white/30 px-6 py-3 font-bold text-white">Quero vender</a>
            </div>
        </div>
        <div class="hidden grid-cols-3 gap-2 lg:grid" data-category-rotator data-rotation-ms="15000">
            <?php foreach ($categories as $index => $category): ?>
                <?php $group = intdiv($index, 9); ?>
                <a href="<?= e(url('buscar?categoria=' . $category['slug'])) ?>" class="overflow-hidden rounded-[2px] bg-white/15 backdrop-blur transition hover:bg-white/20 <?= $group > 0 ? 'hidden' : '' ?>" data-category-group="<?= (int) $group ?>">
                    <div class="p-2.5">
                        <div class="inline-flex rounded-[2px] bg-white/20 p-2 text-white">
                            <?= category_icon_svg($category['icone'] ?? null) ?>
                        </div>
                        <p class="mt-1.5 text-xs font-bold leading-tight"><?= e($category['nome']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-black text-slate-900">Promocoes do dia</h2>
        <a href="<?= e(url('buscar')) ?>" class="text-sm font-semibold text-blue-600">Ver tudo</a>
    </div>
    <div class="home-product-grid mt-6 grid grid-cols-2 gap-2 sm:gap-4 xl:grid-cols-4">
        <?php foreach ($promotions as $product): require __DIR__ . '/../partials/product-card.php'; endforeach; ?>
    </div>
</section>

<section class="bg-white py-12">
    <div class="mx-auto max-w-7xl px-4">
        <h2 class="text-2xl font-black text-slate-900">Lojas em destaque</h2>
        <div class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-2 md:gap-5 xl:grid-cols-4">
            <?php foreach ($featuredStores as $store): ?>
                <a href="<?= e(url('loja/' . $store['slug'])) ?>" class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-50">
                    <div class="h-36 w-full bg-slate-100 p-2">
                        <img src="<?= e(upload_url('lojas', $store['banner'])) ?>" alt="<?= e($store['nome_loja']) ?>" class="h-full w-full object-contain" loading="lazy">
                    </div>
                    <div class="p-5">
                        <img src="<?= e(upload_url('lojas', $store['logo'])) ?>" alt="<?= e($store['nome_loja']) ?>" class="-mt-14 h-16 w-16 rounded-2xl border-4 border-white bg-white object-contain p-1">
                        <h3 class="mt-4 text-lg font-black"><?= e($store['nome_loja']) ?></h3>
                        <p class="mt-2 text-sm text-slate-600"><?= e($store['endereco'] ?? (($store['bairro'] ?? '') . ', ' . ($store['cidade'] ?? ''))) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-black text-slate-900">Produtos em alta</h2>
        <a href="<?= e(url('buscar')) ?>" class="text-sm font-semibold text-blue-600">Mais vendidos</a>
    </div>
    <div class="home-product-grid mt-6 grid grid-cols-2 gap-2 sm:gap-4 xl:grid-cols-4">
        <?php foreach ($featuredProducts as $product): require __DIR__ . '/../partials/product-card.php'; endforeach; ?>
    </div>
</section>

<section class="bg-slate-100 py-12">
    <div class="mx-auto max-w-7xl px-4">
        <h2 class="text-2xl font-black text-slate-900">Lojas perto de voce</h2>
        <div class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-2 md:gap-5 xl:grid-cols-4">
            <?php foreach ($nearbyStores as $store): ?>
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm font-semibold text-blue-600"><?= e($store['bairro']) ?></p>
                    <h3 class="mt-2 text-xl font-black"><?= e($store['nome_loja']) ?></h3>
                    <p class="mt-2 text-sm text-slate-600"><?= e($store['endereco']) ?></p>
                    <a href="<?= e(url('loja/' . $store['slug'])) ?>" class="mt-4 inline-block rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white">Ver loja</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
