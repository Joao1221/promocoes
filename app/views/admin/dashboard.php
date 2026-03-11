<?php
$featuredStoreCount = (int) ($featuredCounts['stores'] ?? 0);
$featuredProductCount = (int) ($featuredCounts['products'] ?? 0);
$featuredStoreLimit = (int) ($featuredLimits['stores'] ?? 0);
$featuredProductLimit = (int) ($featuredLimits['products'] ?? 0);

$summaryCards = [
    [
        'title' => 'Usuarios cadastrados',
        'value' => (int) ($userMetrics['total_usuarios'] ?? 0),
        'hint' => 'Total de contas no sistema',
        'target' => '#detalhes-cadastros',
    ],
    [
        'title' => 'Consumidores',
        'value' => (int) ($userMetrics['total_consumidores'] ?? 0),
        'hint' => 'Contas com perfil consumidor',
        'target' => '#detalhes-cadastros',
    ],
    [
        'title' => 'Lojas cadastradas',
        'value' => (int) ($storeMetrics['total_lojas'] ?? 0),
        'hint' => 'Total de lojas no marketplace',
        'target' => '#detalhes-lojas',
    ],
    [
        'title' => 'Compradores',
        'value' => (int) ($userMetrics['total_compradores'] ?? 0),
        'hint' => 'Usuarios com ao menos 1 pedido',
        'target' => '#detalhes-vendas',
    ],
    [
        'title' => 'Vendedores',
        'value' => (int) ($userMetrics['total_vendedores'] ?? 0),
        'hint' => 'Usuarios com loja criada',
        'target' => '#detalhes-lojas',
    ],
    [
        'title' => 'Compra e venda',
        'value' => (int) ($userMetrics['total_compra_e_venda'] ?? 0),
        'hint' => 'Usuarios que compram e vendem',
        'target' => '#detalhes-cadastros',
    ],
    [
        'title' => 'Pedidos totais',
        'value' => (int) ($orderMetrics['pedidos'] ?? 0),
        'hint' => 'Pedidos desde o inicio',
        'target' => '#detalhes-vendas',
    ],
    [
        'title' => 'Faturamento',
        'value' => format_price((float) ($orderMetrics['faturamento'] ?? 0)),
        'hint' => 'Volume bruto em vendas',
        'target' => '#detalhes-vendas',
    ],
    [
        'title' => 'Lojas em destaque',
        'value' => $featuredStoreCount,
        'hint' => 'Ativas no carrossel de destaque',
        'target' => url('admin/destaques'),
    ],
    [
        'title' => 'Produtos em destaque',
        'value' => $featuredProductCount,
        'hint' => 'Com prioridade de exibicao',
        'target' => url('admin/destaques'),
    ],
];
?>

<section>
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black">Painel administrativo</h1>
            <p class="mt-2 text-sm text-slate-600">Resumo geral e detalhes de lojas, vendas e cadastros do marketplace.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="<?= e(url('admin/destaques')) ?>" class="rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white">Menu de destaques</a>
            <a href="<?= e(url('admin/categorias')) ?>" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Gerenciar categorias</a>
        </div>
    </div>

    <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <?php foreach ($summaryCards as $card): ?>
            <a href="<?= e($card['target']) ?>" class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-sm text-slate-500"><?= e($card['title']) ?></p>
                <p class="mt-2 text-3xl font-black text-slate-900"><?= is_numeric($card['value']) ? (int) $card['value'] : e((string) $card['value']) ?></p>
                <p class="mt-2 text-xs font-medium uppercase tracking-wide text-blue-600">Ver detalhes</p>
                <p class="mt-1 text-xs text-slate-500"><?= e($card['hint']) ?></p>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="mt-8 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-xl font-black">Saude operacional</h2>
        <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Ticket medio</p>
                <p class="mt-2 text-2xl font-black"><?= e(format_price((float) ($orderMetrics['ticket_medio'] ?? 0))) ?></p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Pedidos hoje</p>
                <p class="mt-2 text-2xl font-black"><?= (int) ($orderMetrics['pedidos_hoje'] ?? 0) ?></p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Lojas pendentes</p>
                <p class="mt-2 text-2xl font-black"><?= (int) ($storeMetrics['lojas_pendentes'] ?? 0) ?></p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Produtos pendentes</p>
                <p class="mt-2 text-2xl font-black"><?= count($pendingProducts) ?></p>
            </div>
        </div>
    </div>

    <div id="gestao-destaque" class="mt-8 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-xl font-black">Regras de visibilidade (destaques)</h2>
        <p class="mt-2 text-sm text-slate-600">Somente lojas e produtos aprovados podem receber destaque. Os destaques influenciam diretamente a ordem de exibicao na Home e nas buscas.</p>
        <div class="mt-4 grid gap-3 md:grid-cols-2">
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Limite de lojas em destaque</p>
                <p class="mt-2 text-2xl font-black"><?= $featuredStoreCount ?> / <?= $featuredStoreLimit ?></p>
                <p class="mt-1 text-xs text-slate-500">Quando atingir o limite, remova destaque de outra loja para incluir nova.</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Limite de produtos em destaque</p>
                <p class="mt-2 text-2xl font-black"><?= $featuredProductCount ?> / <?= $featuredProductLimit ?></p>
                <p class="mt-1 text-xs text-slate-500">Produtos em destaque aparecem antes dos demais e com prioridade por vendas/views.</p>
            </div>
        </div>
    </div>

    <div id="pendencias" class="mt-8 grid gap-8 xl:grid-cols-2">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-xl font-black">Lojas pendentes</h2>
            <div class="mt-4 space-y-4">
                <?php if ($pendingStores === []): ?>
                    <p class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">Nenhuma loja pendente no momento.</p>
                <?php endif; ?>

                <?php foreach ($pendingStores as $store): ?>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-bold"><?= e($store['nome_loja']) ?></p>
                        <p class="mt-1 text-sm text-slate-500"><?= e($store['bairro'] ?? '-') ?> | <?= e($store['telefone'] ?? '-') ?></p>
                        <form action="<?= e(url('admin/lojas/' . $store['id'] . '/aprovar')) ?>" method="POST" class="mt-3">
                            <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                            <button class="rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Aprovar loja</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-xl font-black">Produtos pendentes</h2>
            <div class="mt-4 space-y-4">
                <?php if ($pendingProducts === []): ?>
                    <p class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">Nenhum produto pendente no momento.</p>
                <?php endif; ?>

                <?php foreach ($pendingProducts as $product): ?>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-bold"><?= e($product['nome']) ?></p>
                        <p class="mt-1 text-sm text-slate-500"><?= e($product['nome_loja']) ?> | <?= e(format_price(active_price($product))) ?></p>
                        <form action="<?= e(url('admin/produtos/' . $product['id'] . '/aprovar')) ?>" method="POST" class="mt-3">
                            <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                            <button class="rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Aprovar produto</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div id="detalhes-lojas" class="mt-8 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-xl font-black">Detalhes das lojas</h2>
            <p class="text-sm text-slate-500">Clique no card de resumo para voltar para esta secao.</p>
        </div>
        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="px-3 py-3 font-semibold">Loja</th>
                        <th class="px-3 py-3 font-semibold">Responsavel</th>
                        <th class="px-3 py-3 font-semibold">Status</th>
                        <th class="px-3 py-3 font-semibold">Destaque</th>
                        <th class="px-3 py-3 font-semibold">Produtos</th>
                        <th class="px-3 py-3 font-semibold">Pedidos</th>
                        <th class="px-3 py-3 font-semibold">Faturamento</th>
                        <th class="px-3 py-3 font-semibold">Ultima venda</th>
                        <th class="px-3 py-3 font-semibold">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($storeOverview === []): ?>
                        <tr>
                            <td colspan="9" class="px-3 py-6 text-center text-slate-500">Nenhuma loja cadastrada.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($storeOverview as $store): ?>
                        <?php
                            $ultimaVenda = !empty($store['ultima_venda_em']) ? date('d/m/Y H:i', strtotime((string) $store['ultima_venda_em'])) : '-';
                            $storeIsFeatured = (int) ($store['destaque'] ?? 0) === 1;
                            $storeIsApproved = ($store['status'] ?? '') === 'aprovada';
                            $storeCanFeature = $storeIsFeatured || ($storeIsApproved && $featuredStoreCount < $featuredStoreLimit);
                        ?>
                        <tr class="border-b border-slate-100 align-top">
                            <td class="px-3 py-4">
                                <p class="font-semibold text-slate-900"><?= e($store['nome_loja']) ?></p>
                                <p class="mt-1 text-xs text-slate-500"><?= e($store['bairro'] ?? '-') ?> | <?= e($store['cidade'] ?? '-') ?></p>
                            </td>
                            <td class="px-3 py-4">
                                <p class="font-medium text-slate-900"><?= e($store['dono_nome'] ?? '-') ?></p>
                                <p class="mt-1 text-xs text-slate-500"><?= e($store['dono_email'] ?? '-') ?></p>
                                <p class="mt-1 text-xs text-slate-500"><?= e($store['dono_telefone'] ?? '-') ?></p>
                            </td>
                            <td class="px-3 py-4">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700"><?= e($store['status']) ?></span>
                            </td>
                            <td class="px-3 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide <?= $storeIsFeatured ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' ?>">
                                    <?= $storeIsFeatured ? 'Ativo' : 'Inativo' ?>
                                </span>
                            </td>
                            <td class="px-3 py-4 font-semibold"><?= (int) ($store['total_produtos'] ?? 0) ?></td>
                            <td class="px-3 py-4 font-semibold"><?= (int) ($store['total_pedidos'] ?? 0) ?></td>
                            <td class="px-3 py-4 font-semibold"><?= e(format_price((float) ($store['faturamento'] ?? 0))) ?></td>
                            <td class="px-3 py-4 text-slate-600"><?= e($ultimaVenda) ?></td>
                            <td class="px-3 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="<?= e(url('loja/' . $store['slug'])) ?>" class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white">Ver loja</a>
                                    <?php if (($store['status'] ?? '') === 'pendente'): ?>
                                        <form action="<?= e(url('admin/lojas/' . $store['id'] . '/aprovar')) ?>" method="POST">
                                            <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                                            <button class="rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white">Aprovar</button>
                                        </form>
                                    <?php endif; ?>
                                    <form action="<?= e(url('admin/lojas/' . $store['id'] . '/destaque')) ?>" method="POST">
                                        <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                                        <input type="hidden" name="featured" value="<?= $storeIsFeatured ? '0' : '1' ?>">
                                        <button
                                            class="rounded-xl px-3 py-2 text-xs font-semibold text-white <?= $storeCanFeature ? ($storeIsFeatured ? 'bg-amber-600' : 'bg-emerald-600') : 'cursor-not-allowed bg-slate-300' ?>"
                                            <?= $storeCanFeature ? '' : 'disabled' ?>
                                        >
                                            <?= $storeIsFeatured ? 'Remover destaque' : 'Destacar loja' ?>
                                        </button>
                                    </form>
                                </div>
                                <?php if (!$storeIsApproved): ?>
                                    <p class="mt-2 text-xs text-slate-500">Apenas lojas aprovadas podem receber destaque.</p>
                                <?php elseif (!$storeCanFeature): ?>
                                    <p class="mt-2 text-xs text-slate-500">Limite de lojas em destaque atingido.</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="detalhes-produtos" class="mt-8 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-xl font-black">Detalhes dos produtos e prioridade</h2>
            <p class="text-sm text-slate-500">Defina quais produtos recebem maior visibilidade.</p>
        </div>
        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="px-3 py-3 font-semibold">Produto</th>
                        <th class="px-3 py-3 font-semibold">Loja</th>
                        <th class="px-3 py-3 font-semibold">Status</th>
                        <th class="px-3 py-3 font-semibold">Vendas</th>
                        <th class="px-3 py-3 font-semibold">Views</th>
                        <th class="px-3 py-3 font-semibold">Preco ativo</th>
                        <th class="px-3 py-3 font-semibold">Destaque</th>
                        <th class="px-3 py-3 font-semibold">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($productOverview === []): ?>
                        <tr>
                            <td colspan="8" class="px-3 py-6 text-center text-slate-500">Nenhum produto cadastrado.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($productOverview as $product): ?>
                        <?php
                            $productIsFeatured = (int) ($product['destaque'] ?? 0) === 1;
                            $productEligible = (($product['status'] ?? '') === 'aprovado') && (($product['loja_status'] ?? '') === 'aprovada');
                            $productCanFeature = $productIsFeatured || ($productEligible && $featuredProductCount < $featuredProductLimit);
                        ?>
                        <tr class="border-b border-slate-100 align-top">
                            <td class="px-3 py-4">
                                <p class="font-semibold text-slate-900"><?= e($product['nome'] ?? '-') ?></p>
                                <p class="mt-1 text-xs text-slate-500"><?= e($product['categoria_nome'] ?? '-') ?></p>
                            </td>
                            <td class="px-3 py-4">
                                <p class="font-medium text-slate-900"><?= e($product['nome_loja'] ?? '-') ?></p>
                                <?php if (!empty($product['loja_slug'])): ?>
                                    <a href="<?= e(url('loja/' . $product['loja_slug'])) ?>" class="mt-1 inline-block text-xs font-semibold text-blue-600">Abrir loja</a>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-4">
                                <div class="space-y-1">
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700"><?= e($product['status'] ?? '-') ?></span>
                                    <p class="text-xs text-slate-500">Loja: <?= e($product['loja_status'] ?? '-') ?></p>
                                </div>
                            </td>
                            <td class="px-3 py-4 font-semibold"><?= (int) ($product['total_vendas'] ?? 0) ?></td>
                            <td class="px-3 py-4 font-semibold"><?= (int) ($product['views'] ?? 0) ?></td>
                            <td class="px-3 py-4 font-semibold"><?= e(format_price(active_price($product))) ?></td>
                            <td class="px-3 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide <?= $productIsFeatured ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' ?>">
                                    <?= $productIsFeatured ? 'Ativo' : 'Inativo' ?>
                                </span>
                            </td>
                            <td class="px-3 py-4">
                                <form action="<?= e(url('admin/produtos/' . $product['id'] . '/destaque')) ?>" method="POST" class="flex flex-wrap gap-2">
                                    <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                                    <input type="hidden" name="featured" value="<?= $productIsFeatured ? '0' : '1' ?>">
                                    <button
                                        class="rounded-xl px-3 py-2 text-xs font-semibold text-white <?= $productCanFeature ? ($productIsFeatured ? 'bg-amber-600' : 'bg-emerald-600') : 'cursor-not-allowed bg-slate-300' ?>"
                                        <?= $productCanFeature ? '' : 'disabled' ?>
                                    >
                                        <?= $productIsFeatured ? 'Remover destaque' : 'Dar destaque' ?>
                                    </button>
                                    <?php if (($product['status'] ?? '') === 'pendente'): ?>
                                        <button formaction="<?= e(url('admin/produtos/' . $product['id'] . '/aprovar')) ?>" class="rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white">Aprovar</button>
                                    <?php endif; ?>
                                </form>
                                <?php if (!$productEligible): ?>
                                    <p class="mt-2 text-xs text-slate-500">Exige produto e loja aprovados para destacar.</p>
                                <?php elseif (!$productCanFeature): ?>
                                    <p class="mt-2 text-xs text-slate-500">Limite de produtos em destaque atingido.</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="detalhes-vendas" class="mt-8 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-xl font-black">Detalhes das vendas</h2>
            <p class="text-sm text-slate-500">Pedidos mais recentes e status da operacao.</p>
        </div>

        <div class="mt-5 grid gap-3 md:grid-cols-2 xl:grid-cols-5">
            <?php $statusLabels = ['novo' => 'Novos', 'em_preparo' => 'Em preparo', 'enviado' => 'Enviados', 'concluido' => 'Concluidos', 'cancelado' => 'Cancelados']; ?>
            <?php foreach ($statusLabels as $statusKey => $statusLabel): ?>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500"><?= e($statusLabel) ?></p>
                    <p class="mt-2 text-2xl font-black"><?= (int) (($orderMetrics['status'][$statusKey] ?? 0)) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="px-3 py-3 font-semibold">Pedido</th>
                        <th class="px-3 py-3 font-semibold">Data</th>
                        <th class="px-3 py-3 font-semibold">Comprador</th>
                        <th class="px-3 py-3 font-semibold">Loja</th>
                        <th class="px-3 py-3 font-semibold">Pagamento</th>
                        <th class="px-3 py-3 font-semibold">Status</th>
                        <th class="px-3 py-3 font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($recentOrders === []): ?>
                        <tr>
                            <td colspan="7" class="px-3 py-6 text-center text-slate-500">Nenhuma venda registrada ate o momento.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($recentOrders as $order): ?>
                        <?php $createdAt = !empty($order['created_at']) ? date('d/m/Y H:i', strtotime((string) $order['created_at'])) : '-'; ?>
                        <tr class="border-b border-slate-100 align-top">
                            <td class="px-3 py-4 font-semibold">#<?= (int) $order['id'] ?></td>
                            <td class="px-3 py-4 text-slate-600"><?= e($createdAt) ?></td>
                            <td class="px-3 py-4">
                                <p class="font-medium text-slate-900"><?= e($order['comprador_nome'] ?? $order['nome_cliente'] ?? '-') ?></p>
                                <p class="mt-1 text-xs text-slate-500"><?= e($order['comprador_email'] ?? '-') ?></p>
                            </td>
                            <td class="px-3 py-4">
                                <p class="font-medium text-slate-900"><?= e($order['nome_loja'] ?? '-') ?></p>
                                <?php if (!empty($order['loja_slug'])): ?>
                                    <a href="<?= e(url('loja/' . $order['loja_slug'])) ?>" class="mt-1 inline-block text-xs font-semibold text-blue-600">Abrir loja</a>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-4 text-slate-600"><?= e($order['forma_pagamento'] ?? '-') ?></td>
                            <td class="px-3 py-4">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700"><?= e($order['status']) ?></span>
                            </td>
                            <td class="px-3 py-4 font-semibold"><?= e(format_price((float) ($order['total'] ?? 0))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="detalhes-cadastros" class="mt-8 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-xl font-black">Detalhes de cadastros e perfis</h2>
            <p class="text-sm text-slate-500">Quem compra, quem vende e quem faz os dois.</p>
        </div>

        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="px-3 py-3 font-semibold">Usuario</th>
                        <th class="px-3 py-3 font-semibold">Tipo</th>
                        <th class="px-3 py-3 font-semibold">Status</th>
                        <th class="px-3 py-3 font-semibold">Compra</th>
                        <th class="px-3 py-3 font-semibold">Venda</th>
                        <th class="px-3 py-3 font-semibold">Uso da conta</th>
                        <th class="px-3 py-3 font-semibold">Total gasto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($userProfiles === []): ?>
                        <tr>
                            <td colspan="7" class="px-3 py-6 text-center text-slate-500">Nenhum cadastro encontrado.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($userProfiles as $profile): ?>
                        <?php
                            $totalCompras = (int) ($profile['total_pedidos_compra'] ?? 0);
                            $totalLojas = (int) ($profile['total_lojas'] ?? 0);
                            $isBuyer = $totalCompras > 0;
                            $isSeller = $totalLojas > 0;
                            $usage = $isBuyer && $isSeller ? 'Compra e vende' : ($isSeller ? 'Somente vende' : ($isBuyer ? 'Somente compra' : 'Sem atividade'));
                        ?>
                        <tr class="border-b border-slate-100 align-top">
                            <td class="px-3 py-4">
                                <p class="font-medium text-slate-900"><?= e($profile['nome'] ?? '-') ?></p>
                                <p class="mt-1 text-xs text-slate-500"><?= e($profile['email'] ?? '-') ?></p>
                            </td>
                            <td class="px-3 py-4 text-slate-600"><?= e($profile['role'] ?? '-') ?></td>
                            <td class="px-3 py-4 text-slate-600"><?= e($profile['status'] ?? '-') ?></td>
                            <td class="px-3 py-4 font-semibold"><?= $totalCompras ?></td>
                            <td class="px-3 py-4 font-semibold"><?= $totalLojas ?></td>
                            <td class="px-3 py-4">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700"><?= e($usage) ?></span>
                            </td>
                            <td class="px-3 py-4 font-semibold"><?= e(format_price((float) ($profile['total_gasto'] ?? 0))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
