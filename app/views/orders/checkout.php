<section class="mx-auto max-w-6xl px-4 py-10">
    <div class="grid gap-8 lg:grid-cols-[1fr_380px]">
        <form action="<?= e(url('checkout')) ?>" method="POST" class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
            <h1 class="text-3xl font-black">Finalizar pedido</h1>
            <div class="mt-8 grid gap-4">
                <p class="rounded-2xl bg-slate-100 px-4 py-3 text-sm text-slate-700">Para comprar, confirme seus dados de entrega. Esses dados ficam salvos para os proximos pedidos.</p>
                <input type="text" name="nome_cliente" value="<?= e($checkoutProfile['nome'] ?? $authUser['nome'] ?? '') ?>" placeholder="Nome" class="rounded-2xl border border-slate-200 px-4 py-3" required>
                <input type="text" name="telefone_cliente" value="<?= e($checkoutProfile['telefone'] ?? '') ?>" placeholder="Telefone" class="rounded-2xl border border-slate-200 px-4 py-3" required>
                <textarea name="endereco_entrega" placeholder="Endereco de entrega" class="rounded-2xl border border-slate-200 px-4 py-3" required><?= e($checkoutProfile['endereco_entrega'] ?? '') ?></textarea>
                <select name="forma_pagamento" class="rounded-2xl border border-slate-200 px-4 py-3" required>
                    <option value="">Forma de pagamento na entrega</option>
                    <option value="Dinheiro">Dinheiro</option>
                    <option value="Pix">Pix</option>
                    <option value="Cartao de debito">Cartao de debito</option>
                    <option value="Cartao de credito">Cartao de credito</option>
                </select>
                <textarea name="observacoes" placeholder="Observacoes do pedido" class="rounded-2xl border border-slate-200 px-4 py-3"></textarea>
                <button class="rounded-2xl bg-blue-600 px-5 py-3 font-bold text-white">Enviar pedido</button>
            </div>
        </form>
        <aside class="rounded-3xl bg-slate-900 p-6 text-white">
            <h2 class="text-xl font-black">Resumo</h2>
            <div class="mt-6 space-y-4">
                <?php foreach ($cart as $item): ?>
                    <div class="flex items-center justify-between text-sm">
                        <span><?= e($item['quantidade']) ?>x <?= e($item['nome']) ?></span>
                        <span><?= e(format_price($item['preco'] * $item['quantidade'])) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-6 border-t border-white/10 pt-4 text-2xl font-black"><?= e(format_price($total)) ?></div>
        </aside>
    </div>
</section>
