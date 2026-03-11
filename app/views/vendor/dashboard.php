<section>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600">Painel de vendas</p>
            <h1 class="text-3xl font-black">Resumo da operacao</h1>
        </div>
        <a href="<?= e(url('lojista/produtos/novo')) ?>" class="rounded-2xl bg-blue-600 px-5 py-3 font-bold text-white">Novo produto</a>
    </div>
    <?php if (!$store): ?>
        <div class="mt-8 rounded-3xl border border-amber-200 bg-amber-50 p-6 text-amber-900 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-700">Primeiro passo para vender</p>
            <h2 class="mt-2 text-2xl font-black">Sua loja ainda nao foi criada</h2>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-amber-800">
                Para comecar a vender no marketplace, preencha o cadastro da loja com os dados principais do negocio.
                Depois disso, voce podera publicar produtos e receber pedidos por aqui.
            </p>
            <div class="mt-5 grid gap-3 md:grid-cols-3">
                <div class="rounded-2xl bg-white/80 p-4 ring-1 ring-amber-200">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-amber-700">1. Dados da loja</p>
                    <p class="mt-2 text-sm text-amber-900">Informe nome da loja, CPF ou CNPJ, telefone, WhatsApp e endereco.</p>
                </div>
                <div class="rounded-2xl bg-white/80 p-4 ring-1 ring-amber-200">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-amber-700">2. Aparencia</p>
                    <p class="mt-2 text-sm text-amber-900">Adicione logo, banner e uma descricao para apresentar sua loja aos clientes.</p>
                </div>
                <div class="rounded-2xl bg-white/80 p-4 ring-1 ring-amber-200">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-amber-700">3. Publicacao</p>
                    <p class="mt-2 text-sm text-amber-900">Salve o cadastro e, em seguida, cadastre os produtos que deseja vender.</p>
                </div>
            </div>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="<?= e(url('lojista/loja')) ?>" class="rounded-2xl bg-amber-500 px-5 py-3 text-sm font-bold text-white">Criar minha loja</a>
                <p class="self-center text-sm text-amber-800">Leva poucos minutos para completar.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="mt-8 grid gap-5 md:grid-cols-3">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Produtos</p>
                <p class="mt-3 text-4xl font-black"><?= (int) ($productStats['total_produtos'] ?? 0) ?></p>
            </div>
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Pedidos</p>
                <p class="mt-3 text-4xl font-black"><?= count($orders) ?></p>
            </div>
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Visualizacoes</p>
                <p class="mt-3 text-4xl font-black"><?= (int) ($productStats['total_views'] ?? 0) ?></p>
            </div>
        </div>
        <div class="mt-8 grid gap-8 xl:grid-cols-[1.35fr_.85fr]">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div>
                        <h2 class="text-xl font-black">Produtos</h2>
                        <p class="mt-1 text-sm text-slate-500">Busque, navegue e edite sem alongar a tela.</p>
                    </div>
                    <a href="<?= e(url('lojista/produtos/novo')) ?>" class="text-sm font-semibold text-blue-600">Cadastrar</a>
                </div>
                <form action="<?= e(url('lojista')) ?>" method="GET" class="mt-5 grid gap-3 md:grid-cols-[1fr_150px_120px]">
                    <input type="search" name="q" value="<?= e($productFilters['q'] ?? '') ?>" placeholder="Buscar por nome, SKU ou categoria" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm">
                    <select name="per_page" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm">
                        <?php foreach ($perPageOptions as $option): ?>
                            <option value="<?= (int) $option ?>" <?= (int) ($productFilters['per_page'] ?? 20) === (int) $option ? 'selected' : '' ?>><?= (int) $option ?> por pagina</option>
                        <?php endforeach; ?>
                    </select>
                    <button class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Pesquisar</button>
                </form>
                <div class="mt-4 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
                    <p><?= (int) ($productFilters['total'] ?? 0) ?> produto(s) encontrado(s)</p>
                    <p>Pagina <?= (int) ($productFilters['page'] ?? 1) ?> de <?= (int) ($productFilters['total_pages'] ?? 1) ?></p>
                </div>
                <div class="mt-4 hidden overflow-x-auto xl:block">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-[0.16em] text-slate-500">
                                <th class="px-3 py-3 font-semibold">Produto</th>
                                <th class="px-3 py-3 font-semibold">Categoria</th>
                                <th class="px-3 py-3 font-semibold">SKU</th>
                                <th class="px-3 py-3 font-semibold">Preco</th>
                                <th class="px-3 py-3 font-semibold">Estoque</th>
                                <th class="px-3 py-3 font-semibold">Status</th>
                                <th class="px-3 py-3 font-semibold text-right">Acoes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr class="border-b border-slate-100 align-top">
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-3">
                                            <img src="<?= e(upload_url('produtos', $product['imagem_principal'])) ?>" alt="<?= e($product['nome']) ?>" class="h-12 w-12 rounded-xl bg-slate-50 p-1 object-contain">
                                            <div class="min-w-0">
                                                <p class="truncate font-semibold text-slate-900"><?= e($product['nome']) ?></p>
                                                <p class="text-xs text-slate-500"><?= e(format_price(active_price($product))) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-slate-600"><?= e($product['categoria_nome']) ?></td>
                                    <td class="px-3 py-3 font-mono text-xs text-slate-600"><?= e($product['sku']) ?></td>
                                    <td class="px-3 py-3 text-slate-600"><?= e(format_price(active_price($product))) ?></td>
                                    <td class="px-3 py-3 text-slate-600"><?= (int) $product['estoque'] ?></td>
                                    <td class="px-3 py-3 text-slate-600"><?= e($product['status']) ?></td>
                                    <td class="px-3 py-3 text-right">
                                        <a href="<?= e(url('lojista/produtos/' . $product['id'] . '/editar')) ?>" class="inline-block rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white">Editar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 space-y-3 xl:hidden">
                    <?php foreach ($products as $product): ?>
                        <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                            <div class="flex items-start gap-3">
                                <img src="<?= e(upload_url('produtos', $product['imagem_principal'])) ?>" alt="<?= e($product['nome']) ?>" class="h-14 w-14 rounded-xl bg-white p-1 object-contain">
                                <div class="min-w-0 flex-1">
                                    <p class="font-bold"><?= e($product['nome']) ?></p>
                                    <p class="mt-1 text-xs text-slate-500"><?= e($product['categoria_nome']) ?> • <?= e($product['sku']) ?></p>
                                    <p class="mt-1 text-xs text-slate-500"><?= e($product['status']) ?> • Estoque: <?= (int) $product['estoque'] ?></p>
                                    <p class="mt-2 text-sm font-semibold text-slate-900"><?= e(format_price(active_price($product))) ?></p>
                                </div>
                                <a href="<?= e(url('lojista/produtos/' . $product['id'] . '/editar')) ?>" class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white">Editar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (empty($products)): ?>
                    <div class="mt-4 rounded-2xl bg-slate-50 p-5 text-sm text-slate-500">Nenhum produto encontrado com esse filtro.</div>
                <?php endif; ?>
                <?php if (($productFilters['total_pages'] ?? 1) > 1): ?>
                    <?php
                    $queryBase = [
                        'q' => $productFilters['q'] ?? '',
                        'per_page' => $productFilters['per_page'] ?? 20,
                    ];
                    $currentPage = (int) ($productFilters['page'] ?? 1);
                    $totalPages = (int) ($productFilters['total_pages'] ?? 1);
                    ?>
                    <div class="mt-5 flex flex-wrap items-center gap-2">
                        <?php if ($currentPage > 1): ?>
                            <a href="<?= e(url('lojista?' . http_build_query($queryBase + ['page' => $currentPage - 1]))) ?>" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700">Anterior</a>
                        <?php endif; ?>
                        <?php for ($page = max(1, $currentPage - 2); $page <= min($totalPages, $currentPage + 2); $page++): ?>
                            <a href="<?= e(url('lojista?' . http_build_query($queryBase + ['page' => $page]))) ?>" class="rounded-xl px-3 py-2 text-xs font-semibold <?= $page === $currentPage ? 'bg-slate-900 text-white' : 'border border-slate-200 text-slate-700' ?>"><?= (int) $page ?></a>
                        <?php endfor; ?>
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="<?= e(url('lojista?' . http_build_query($queryBase + ['page' => $currentPage + 1]))) ?>" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700">Proxima</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-xl font-black">Pedidos recebidos</h2>
                <div class="mt-4 space-y-4">
                    <?php foreach ($orders as $order): ?>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <div class="flex items-center justify-between">
                                <p class="font-bold"><?= e($order['nome_cliente']) ?></p>
                                <p class="text-sm font-semibold text-blue-600"><?= e($order['status']) ?></p>
                            </div>
                            <p class="mt-2 text-sm text-slate-500"><?= e($order['telefone_cliente']) ?></p>
                            <p class="mt-2 text-sm text-slate-500"><?= e(format_price((float) $order['total'])) ?></p>
                            <a href="<?= e(url('lojista/pedidos/' . $order['id'])) ?>" class="mt-3 inline-block rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Ver detalhes</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>
