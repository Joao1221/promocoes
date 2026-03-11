<?php
$featuredStoreCount = (int) ($featuredCounts['stores'] ?? 0);
$featuredProductCount = (int) ($featuredCounts['products'] ?? 0);
$homeSelectedCount = (int) ($featuredCounts['home_products'] ?? 0);
$featuredStoreLimit = (int) ($featuredLimits['stores'] ?? 0);
$featuredProductLimit = (int) ($featuredLimits['products'] ?? 0);
$homeProductsLimit = (int) ($featuredLimits['home_products'] ?? 12);

$slotsByPosition = [];
foreach ($homeFeaturedSlots as $slot) {
    $slotsByPosition[(int) ($slot['posicao'] ?? 0)] = $slot;
}

$optionsById = [];
foreach ($homeFeaturedOptions as $option) {
    $optionsById[(int) $option['id']] = $option;
}
?>

<section>
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black">Gestao de destaques</h1>
            <p class="mt-2 text-sm text-slate-600">Configure visibilidade de lojas, produtos e escolha manual dos 12 produtos exibidos na Home.</p>
        </div>
        <a href="<?= e(url('admin')) ?>" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Voltar ao painel</a>
    </div>

    <div class="mt-8 grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">Lojas em destaque</p>
            <p class="mt-2 text-3xl font-black"><?= $featuredStoreCount ?> / <?= $featuredStoreLimit ?></p>
            <p class="mt-2 text-xs text-slate-500">Somente lojas aprovadas.</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">Produtos em destaque</p>
            <p class="mt-2 text-3xl font-black"><?= $featuredProductCount ?> / <?= $featuredProductLimit ?></p>
            <p class="mt-2 text-xs text-slate-500">Aumenta prioridade de exibicao.</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">Produtos escolhidos para Home</p>
            <p class="mt-2 text-3xl font-black"><?= $homeSelectedCount ?> / <?= $homeProductsLimit ?></p>
            <p class="mt-2 text-xs text-slate-500">Mostrados na secao "Produtos em alta".</p>
        </div>
    </div>

    <div id="home-12" class="mt-8 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-xl font-black">Selecionar os 12 produtos da tela inicial</h2>
        <p class="mt-2 text-sm text-slate-600">Defina manualmente os produtos por posicao (1 a <?= $homeProductsLimit ?>). Deixe vazio para remover da lista.</p>

        <form action="<?= e(url('admin/destaques/home-produtos')) ?>" method="POST" class="mt-5 space-y-4">
            <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">

            <div class="grid gap-4 md:grid-cols-2">
                <?php for ($position = 1; $position <= $homeProductsLimit; $position++): ?>
                    <?php
                        $current = $slotsByPosition[$position] ?? null;
                        $currentProductId = (int) ($current['produto_id'] ?? 0);
                    ?>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <label class="text-sm font-semibold text-slate-700">Posicao <?= $position ?></label>
                        <select name="slots[<?= $position ?>]" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">
                            <option value="">Sem produto</option>
                            <?php if ($currentProductId > 0 && !isset($optionsById[$currentProductId])): ?>
                                <option value="<?= $currentProductId ?>" selected>[Indisponivel] <?= e($current['produto_nome'] ?? ('Produto #' . $currentProductId)) ?></option>
                            <?php endif; ?>
                            <?php foreach ($homeFeaturedOptions as $option): ?>
                                <?php
                                    $optionId = (int) $option['id'];
                                    $optionLabel = '#' . $optionId . ' - ' . ($option['nome'] ?? '') . ' | ' . ($option['nome_loja'] ?? '');
                                ?>
                                <option value="<?= $optionId ?>" <?= $currentProductId === $optionId ? 'selected' : '' ?>><?= e($optionLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($current): ?>
                            <p class="mt-2 text-xs text-slate-500">Atual: <?= e($current['produto_nome'] ?? '-') ?> | <?= e($current['nome_loja'] ?? '-') ?></p>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>

            <button class="rounded-2xl bg-blue-600 px-5 py-3 font-semibold text-white">Salvar selecao da Home</button>
        </form>
    </div>

    <div id="lojas-destaque" class="mt-8 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-xl font-black">Destaque de lojas</h2>
        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="px-3 py-3 font-semibold">Loja</th>
                        <th class="px-3 py-3 font-semibold">Status</th>
                        <th class="px-3 py-3 font-semibold">Pedidos</th>
                        <th class="px-3 py-3 font-semibold">Faturamento</th>
                        <th class="px-3 py-3 font-semibold">Destaque</th>
                        <th class="px-3 py-3 font-semibold">Acao</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($storeOverview as $store): ?>
                        <?php
                            $storeIsFeatured = (int) ($store['destaque'] ?? 0) === 1;
                            $storeIsApproved = ($store['status'] ?? '') === 'aprovada';
                            $storeCanFeature = $storeIsFeatured || ($storeIsApproved && $featuredStoreCount < $featuredStoreLimit);
                        ?>
                        <tr class="border-b border-slate-100">
                            <td class="px-3 py-4">
                                <p class="font-semibold text-slate-900"><?= e($store['nome_loja'] ?? '-') ?></p>
                                <p class="mt-1 text-xs text-slate-500"><?= e($store['bairro'] ?? '-') ?> | <?= e($store['cidade'] ?? '-') ?></p>
                            </td>
                            <td class="px-3 py-4">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700"><?= e($store['status'] ?? '-') ?></span>
                            </td>
                            <td class="px-3 py-4 font-semibold"><?= (int) ($store['total_pedidos'] ?? 0) ?></td>
                            <td class="px-3 py-4 font-semibold"><?= e(format_price((float) ($store['faturamento'] ?? 0))) ?></td>
                            <td class="px-3 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide <?= $storeIsFeatured ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' ?>">
                                    <?= $storeIsFeatured ? 'Ativo' : 'Inativo' ?>
                                </span>
                            </td>
                            <td class="px-3 py-4">
                                <form action="<?= e(url('admin/lojas/' . $store['id'] . '/destaque')) ?>" method="POST" class="flex items-center gap-2">
                                    <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                                    <input type="hidden" name="featured" value="<?= $storeIsFeatured ? '0' : '1' ?>">
                                    <button class="rounded-xl px-3 py-2 text-xs font-semibold text-white <?= $storeCanFeature ? ($storeIsFeatured ? 'bg-amber-600' : 'bg-emerald-600') : 'cursor-not-allowed bg-slate-300' ?>" <?= $storeCanFeature ? '' : 'disabled' ?>>
                                        <?= $storeIsFeatured ? 'Remover destaque' : 'Destacar loja' ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="produtos-destaque" class="mt-8 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-xl font-black">Destaque de produtos</h2>
        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="px-3 py-3 font-semibold">Produto</th>
                        <th class="px-3 py-3 font-semibold">Loja</th>
                        <th class="px-3 py-3 font-semibold">Status</th>
                        <th class="px-3 py-3 font-semibold">Vendas</th>
                        <th class="px-3 py-3 font-semibold">Views</th>
                        <th class="px-3 py-3 font-semibold">Destaque</th>
                        <th class="px-3 py-3 font-semibold">Acao</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productOverview as $product): ?>
                        <?php
                            $productIsFeatured = (int) ($product['destaque'] ?? 0) === 1;
                            $productEligible = (($product['status'] ?? '') === 'aprovado') && (($product['loja_status'] ?? '') === 'aprovada');
                            $productCanFeature = $productIsFeatured || ($productEligible && $featuredProductCount < $featuredProductLimit);
                        ?>
                        <tr class="border-b border-slate-100">
                            <td class="px-3 py-4">
                                <p class="font-semibold text-slate-900"><?= e($product['nome'] ?? '-') ?></p>
                                <p class="mt-1 text-xs text-slate-500">#<?= (int) ($product['id'] ?? 0) ?> | <?= e($product['categoria_nome'] ?? '-') ?></p>
                            </td>
                            <td class="px-3 py-4">
                                <p class="font-medium text-slate-900"><?= e($product['nome_loja'] ?? '-') ?></p>
                            </td>
                            <td class="px-3 py-4">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700"><?= e($product['status'] ?? '-') ?></span>
                            </td>
                            <td class="px-3 py-4 font-semibold"><?= (int) ($product['total_vendas'] ?? 0) ?></td>
                            <td class="px-3 py-4 font-semibold"><?= (int) ($product['views'] ?? 0) ?></td>
                            <td class="px-3 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide <?= $productIsFeatured ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' ?>">
                                    <?= $productIsFeatured ? 'Ativo' : 'Inativo' ?>
                                </span>
                            </td>
                            <td class="px-3 py-4">
                                <form action="<?= e(url('admin/produtos/' . $product['id'] . '/destaque')) ?>" method="POST" class="flex items-center gap-2">
                                    <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                                    <input type="hidden" name="featured" value="<?= $productIsFeatured ? '0' : '1' ?>">
                                    <button class="rounded-xl px-3 py-2 text-xs font-semibold text-white <?= $productCanFeature ? ($productIsFeatured ? 'bg-amber-600' : 'bg-emerald-600') : 'cursor-not-allowed bg-slate-300' ?>" <?= $productCanFeature ? '' : 'disabled' ?>>
                                        <?= $productIsFeatured ? 'Remover destaque' : 'Dar destaque' ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
