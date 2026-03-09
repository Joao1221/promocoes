<section>
    <div class="bg-slate-100">
        <picture>
            <?php if (!empty($store['banner_mobile'])): ?>
                <source media="(max-width: 768px)" srcset="<?= e(upload_url('lojas', $store['banner_mobile'])) ?>">
            <?php endif; ?>
            <img src="<?= e(upload_url('lojas', $store['banner'])) ?>" alt="<?= e($store['nome_loja']) ?>" class="mx-auto w-full max-w-7xl object-contain" loading="lazy">
        </picture>
    </div>
    <div class="mx-auto max-w-7xl px-4 pb-12">
        <div class="mt-6 rounded-3xl bg-white p-6 shadow-xl ring-1 ring-slate-200 md:p-8">
            <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                    <img src="<?= e(upload_url('lojas', $store['logo'])) ?>" alt="<?= e($store['nome_loja']) ?>" class="-mt-16 h-24 w-24 rounded-3xl border-4 border-white object-cover bg-white shadow-lg sm:-mt-20">
                    <div class="pt-1">
                        <p class="text-sm font-semibold text-blue-600"><?= e($store['endereco'] ?? (($store['bairro'] ?? '') . ', ' . ($store['cidade'] ?? ''))) ?></p>
                        <h1 class="text-3xl font-black"><?= e($store['nome_loja']) ?></h1>
                        <p class="mt-2 text-slate-600"><?= e($store['descricao']) ?></p>
                    </div>
                </div>
                <div class="space-y-3 text-sm">
                    <p><strong>Telefone:</strong> <?= e($store['telefone']) ?></p>
                    <p><strong>Horario:</strong> <?= e($store['horario_funcionamento']) ?></p>
                    <p><strong>Vendas online:</strong> <?= ((int) ($store['vende_online'] ?? 1) === 1) ? 'Sim' : 'Nao' ?></p>
                    <p><strong>Pagamento:</strong> <?= e($store['forma_pagamento'] ?? 'PIX') ?></p>
                    <p><strong>Delivery:</strong> <?= ((int) ($store['tem_delivery'] ?? 0) === 1) ? 'Sim' : 'Nao' ?></p>
                    <a href="https://wa.me/55<?= e($store['whatsapp']) ?>" class="inline-block rounded-2xl bg-green-500 px-5 py-3 font-semibold text-white" target="_blank" rel="noopener">Falar no WhatsApp</a>
                </div>
            </div>
        </div>
        <div class="mt-10 grid grid-cols-2 gap-4 xl:grid-cols-4">
            <?php foreach ($products as $product): require __DIR__ . '/../partials/product-card.php'; endforeach; ?>
        </div>
    </div>
</section>
