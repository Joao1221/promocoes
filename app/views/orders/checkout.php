<section class="mx-auto max-w-6xl px-4 py-10">
    <div class="grid gap-8 lg:grid-cols-[1fr_380px]">
        <form action="<?= e(url('checkout')) ?>" method="POST" class="contents lg:block lg:rounded-3xl lg:bg-white lg:p-6 lg:shadow-sm lg:ring-1 lg:ring-slate-200">
            <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:rounded-none lg:bg-transparent lg:p-0 lg:shadow-none lg:ring-0">
                <h1 class="text-3xl font-black">Finalizar pedido</h1>
                <div class="mt-8 grid gap-4">
                    <p class="rounded-2xl bg-slate-100 px-4 py-3 text-sm text-slate-700">Para comprar, confirme seus dados de entrega. Esses dados ficam salvos para os proximos pedidos.</p>
                    <p class="rounded-2xl bg-green-50 px-4 py-3 text-sm text-green-800">Ao clicar em fazer pedido, voce sera direcionado para o WhatsApp da loja para negociar o pagamento.</p>
                    <input type="text" name="nome_cliente" value="<?= e($checkoutProfile['nome'] ?? $authUser['nome'] ?? '') ?>" placeholder="Nome" class="rounded-2xl border border-slate-200 px-4 py-3" required>
                    <input type="text" name="telefone_cliente" value="<?= e($checkoutProfile['telefone'] ?? '') ?>" placeholder="Telefone" class="rounded-2xl border border-slate-200 px-4 py-3" required>
                    <textarea name="endereco_entrega" placeholder="Endereco de entrega" class="rounded-2xl border border-slate-200 px-4 py-3" required><?= e($checkoutProfile['endereco_entrega'] ?? '') ?></textarea>
                    <textarea name="observacoes" placeholder="Observacoes do pedido" class="rounded-2xl border border-slate-200 px-4 py-3"></textarea>
                </div>
            </div>
            <aside class="rounded-3xl bg-slate-900 p-6 text-white lg:hidden">
                <h2 class="text-xl font-black">Resumo</h2>
                <div class="mt-6 space-y-4">
                    <?php foreach ($cart as $item): ?>
                        <div class="flex items-center justify-between text-sm">
                            <span><?= e($item['quantidade']) ?>x <?= e($item['nome']) ?></span>
                            <span><?= e(format_price($item['preco'] * $item['quantidade'])) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-6 border-t border-white/10 pt-4">
                    <div class="flex items-center justify-between text-sm text-white/70">
                        <span>Total geral</span>
                        <span class="text-2xl font-black text-white"><?= e(format_price($total)) ?></span>
                    </div>
                </div>
            </aside>
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:mt-6 lg:rounded-none lg:bg-transparent lg:p-0 lg:shadow-none lg:ring-0">
                <button class="w-full rounded-2xl bg-blue-600 px-6 py-4 text-base font-bold text-white lg:w-auto lg:min-w-[220px]">Fazer pedido</button>
            </div>
        </form>
        <aside class="hidden rounded-3xl bg-slate-900 p-6 text-white lg:block">
            <h2 class="text-xl font-black">Resumo</h2>
            <div class="mt-6 space-y-4">
                <?php foreach ($cart as $item): ?>
                    <div class="flex items-center justify-between text-sm">
                        <span><?= e($item['quantidade']) ?>x <?= e($item['nome']) ?></span>
                        <span><?= e(format_price($item['preco'] * $item['quantidade'])) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-6 border-t border-white/10 pt-4">
                <div class="flex items-center justify-between text-sm text-white/70">
                    <span>Total geral</span>
                    <span class="text-2xl font-black text-white"><?= e(format_price($total)) ?></span>
                </div>
            </div>
        </aside>
    </div>
</section>
