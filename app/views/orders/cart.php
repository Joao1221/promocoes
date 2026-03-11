<section class="mx-auto max-w-5xl px-4 py-10">
    <h1 class="text-3xl font-black">Carrinho</h1>
    <?php if (!$cart): ?>
        <div class="mt-8 rounded-3xl bg-white p-8 text-slate-600 shadow-sm ring-1 ring-slate-200">Seu carrinho esta vazio.</div>
    <?php else: ?>
        <div class="mt-8 space-y-4">
            <?php foreach ($cart as $item): ?>
                <div class="flex flex-col gap-4 rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-4">
                        <img src="<?= e(upload_url('produtos', $item['imagem'])) ?>" alt="<?= e($item['nome']) ?>" class="h-20 w-20 rounded-2xl object-cover">
                        <div>
                            <p class="text-lg font-bold"><?= e($item['nome']) ?></p>
                            <p class="text-sm text-slate-500"><?= e($item['loja_nome']) ?></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-sm text-slate-500"><?= e(format_price((float) $item['preco'])) ?> cada</p>
                            <p class="font-semibold"><?= (int) $item['quantidade'] ?>x</p>
                            <p class="text-lg font-black"><?= e(format_price($item['preco'] * $item['quantidade'])) ?></p>
                        </div>
                        <form action="<?= e(url('carrinho/remover/' . $item['id'])) ?>" method="POST">
                            <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                            <button class="rounded-2xl bg-red-50 px-4 py-3 text-sm font-semibold text-red-600">Remover</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mt-8 rounded-3xl bg-slate-900 p-6 text-white">
            <div class="flex items-center justify-between">
                <span class="text-lg">Total</span>
                <span class="text-3xl font-black"><?= e(format_price($total)) ?></span>
            </div>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <a href="<?= e(url('')) ?>" class="block w-full rounded-2xl bg-white/10 px-5 py-3 text-center font-bold text-white ring-1 ring-white/20">Continuar comprando</a>
                <a href="<?= e(url('checkout')) ?>" class="block w-full rounded-2xl bg-blue-600 px-5 py-3 text-center font-bold text-white">Finalizar pedido</a>
            </div>
        </div>
    <?php endif; ?>
</section>
