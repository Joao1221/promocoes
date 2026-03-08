<section class="mx-auto max-w-6xl">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-blue-600">Pedido #<?= (int) $order['id'] ?></p>
            <h1 class="text-3xl font-black">Detalhes do pedido</h1>
        </div>
        <a href="<?= e(url('lojista')) ?>" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Voltar ao painel</a>
    </div>

    <div class="mt-8 grid gap-8 xl:grid-cols-[1.2fr_.8fr]">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-xl font-black">Itens do pedido</h2>
            <div class="mt-6 space-y-4">
                <?php foreach ($order['itens'] as $item): ?>
                    <div class="flex items-center gap-4 rounded-2xl bg-slate-50 p-4">
                        <img src="<?= e(upload_url('produtos', $item['imagem_principal'])) ?>" alt="<?= e($item['produto_nome']) ?>" class="h-20 w-20 rounded-2xl object-cover">
                        <div class="flex-1">
                            <a href="<?= e(url('produto/' . $item['produto_slug'])) ?>" class="font-bold text-slate-900"><?= e($item['produto_nome']) ?></a>
                            <p class="mt-1 text-sm text-slate-500"><?= (int) $item['quantidade'] ?>x de <?= e(format_price((float) $item['preco_unitario'])) ?></p>
                        </div>
                        <p class="text-lg font-black"><?= e(format_price((float) $item['subtotal'])) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-xl font-black">Cliente</h2>
                <div class="mt-4 space-y-3 text-sm text-slate-600">
                    <p><strong>Nome:</strong> <?= e($order['nome_cliente']) ?></p>
                    <p><strong>Telefone:</strong> <?= e($order['telefone_cliente']) ?></p>
                    <p><strong>Endereco:</strong> <?= nl2br(e($order['endereco_entrega'])) ?></p>
                    <p><strong>Pagamento na entrega:</strong> <?= e($order['forma_pagamento'] ?? 'Nao informado') ?></p>
                    <p><strong>Observacoes:</strong> <?= $order['observacoes'] ? nl2br(e($order['observacoes'])) : 'Nenhuma observacao.' ?></p>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-xl font-black">Resumo</h2>
                <div class="mt-4 space-y-3 text-sm text-slate-600">
                    <p><strong>Status:</strong> <?= e($order['status']) ?></p>
                    <p><strong>Recebido em:</strong> <?= e(date('d/m/Y H:i', strtotime((string) $order['created_at']))) ?></p>
                    <p><strong>Forma de pagamento:</strong> <?= e($order['forma_pagamento'] ?? 'Nao informado') ?></p>
                </div>
                <div class="mt-6 rounded-2xl bg-slate-900 p-4 text-white">
                    <p class="text-sm text-slate-300">Total do pedido</p>
                    <p class="mt-2 text-3xl font-black"><?= e(format_price((float) $order['total'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
