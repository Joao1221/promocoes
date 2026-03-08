<section>
    <div class="h-64 bg-slate-200">
        <img src="<?= e(upload_url('lojas', $store['banner'])) ?>" alt="<?= e($store['nome_loja']) ?>" class="h-full w-full object-cover" loading="lazy">
    </div>
    <div class="mx-auto max-w-7xl px-4 pb-12">
        <div class="mt-6 rounded-3xl bg-white p-6 shadow-xl ring-1 ring-slate-200 md:p-8">
            <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                    <img src="<?= e(upload_url('lojas', $store['logo'])) ?>" alt="<?= e($store['nome_loja']) ?>" class="-mt-16 h-24 w-24 rounded-3xl border-4 border-white object-cover bg-white shadow-lg sm:-mt-20">
                    <div class="pt-1">
                        <p class="text-sm font-semibold text-blue-600"><?= e($store['bairro']) ?>, <?= e($store['cidade']) ?></p>
                        <h1 class="text-3xl font-black"><?= e($store['nome_loja']) ?></h1>
                        <p class="mt-2 text-slate-600"><?= e($store['descricao']) ?></p>
                    </div>
                </div>
                <div class="space-y-3 text-sm">
                    <p><strong>Telefone:</strong> <?= e($store['telefone']) ?></p>
                    <p><strong>Horario:</strong> <?= e($store['horario_funcionamento']) ?></p>
                    <a href="https://wa.me/55<?= e($store['whatsapp']) ?>" class="inline-block rounded-2xl bg-green-500 px-5 py-3 font-semibold text-white" target="_blank" rel="noopener">Falar no WhatsApp</a>
                </div>
            </div>
        </div>
        <div class="mt-10 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <?php foreach ($products as $product): require __DIR__ . '/../partials/product-card.php'; endforeach; ?>
        </div>
    </div>
</section>
