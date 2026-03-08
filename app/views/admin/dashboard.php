<section>
    <h1 class="text-3xl font-black">Painel administrativo</h1>
    <div class="mt-8 grid gap-5 md:grid-cols-2">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">Pedidos totais</p>
            <p class="mt-3 text-4xl font-black"><?= (int) $metrics['pedidos'] ?></p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">Volume bruto</p>
            <p class="mt-3 text-4xl font-black"><?= e(format_price((float) $metrics['faturamento'])) ?></p>
        </div>
    </div>
    <div class="mt-8 grid gap-8 xl:grid-cols-2">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-xl font-black">Lojas pendentes</h2>
            <div class="mt-4 space-y-4">
                <?php foreach ($pendingStores as $store): ?>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-bold"><?= e($store['nome_loja']) ?></p>
                        <p class="mt-1 text-sm text-slate-500"><?= e($store['bairro']) ?> • <?= e($store['telefone']) ?></p>
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
                <?php foreach ($pendingProducts as $product): ?>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-bold"><?= e($product['nome']) ?></p>
                        <p class="mt-1 text-sm text-slate-500"><?= e($product['nome_loja']) ?> • <?= e(format_price(active_price($product))) ?></p>
                        <form action="<?= e(url('admin/produtos/' . $product['id'] . '/aprovar')) ?>" method="POST" class="mt-3">
                            <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                            <button class="rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Aprovar produto</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
