<section>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600">Painel de vendas</p>
            <h1 class="text-3xl font-black">Resumo da operacao</h1>
        </div>
        <a href="<?= e(url('lojista/produtos/novo')) ?>" class="rounded-2xl bg-blue-600 px-5 py-3 font-bold text-white">Novo produto</a>
    </div>
    <?php if (!$store): ?>
        <div class="mt-8 rounded-3xl bg-amber-100 p-6 text-amber-800">
            Sua conta ainda nao possui loja cadastrada. <a href="<?= e(url('lojista/loja')) ?>" class="font-bold underline">Criar loja agora</a>.
        </div>
    <?php else: ?>
        <div class="mt-8 grid gap-5 md:grid-cols-3">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Produtos</p>
                <p class="mt-3 text-4xl font-black"><?= count($products) ?></p>
            </div>
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Pedidos</p>
                <p class="mt-3 text-4xl font-black"><?= count($orders) ?></p>
            </div>
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Visualizacoes</p>
                <p class="mt-3 text-4xl font-black"><?= array_sum(array_map(fn ($item) => (int) ($item['views'] ?? 0), $products)) ?></p>
            </div>
        </div>
        <div class="mt-8 grid gap-8 xl:grid-cols-2">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-black">Produtos</h2>
                    <a href="<?= e(url('lojista/produtos/novo')) ?>" class="text-sm font-semibold text-blue-600">Cadastrar</a>
                </div>
                <div class="mt-4 space-y-4">
                    <?php foreach ($products as $product): ?>
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 p-4">
                            <div>
                                <p class="font-bold"><?= e($product['nome']) ?></p>
                                <p class="text-sm text-slate-500"><?= e($product['status']) ?> • <?= e(format_price(active_price($product))) ?></p>
                            </div>
                            <a href="<?= e(url('lojista/produtos/' . $product['id'] . '/editar')) ?>" class="rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Editar</a>
                        </div>
                    <?php endforeach; ?>
                </div>
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
