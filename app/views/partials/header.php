<?php
$headerCategories = is_array($categories ?? null) ? $categories : [];
$isMarketplaceUser = $authUser && in_array(($authUser['role'] ?? ''), ['consumidor', 'lojista'], true);
?>
<header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 py-4">
        <div class="flex items-center justify-between gap-4">
            <a href="<?= e(url('')) ?>" class="inline-flex shrink-0 items-center gap-2 text-2xl font-black text-blue-600">
                <img src="<?= e(url('public/img/capela-shopping-logo.png')) ?>" alt="Capela Shopping" class="h-[3.75rem] w-auto object-contain">
                <span>Capela Market</span>
            </a>
            <div class="flex items-center gap-2">
                <span class="hidden text-sm text-slate-600 md:block"><?= e($authUser['nome'] ?? '') ?></span>
                <a href="<?= e(url('carrinho')) ?>" class="cart-icon-button relative inline-flex h-11 w-11 items-center justify-center text-slate-700" aria-label="Abrir carrinho">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h2l1.2 7.2A2 2 0 0 0 8.18 13H17a2 2 0 0 0 1.95-1.56L20 6H7"></path>
                        <circle cx="9" cy="19" r="1.5"></circle>
                        <circle cx="17" cy="19" r="1.5"></circle>
                    </svg>
                    <?php if ((int) $cartCount > 0): ?>
                        <span class="absolute -right-1 -top-1 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-blue-600 px-1 text-[11px] font-bold leading-none text-white"><?= (int) $cartCount ?></span>
                    <?php endif; ?>
                </a>
                <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-[4px] border border-slate-200 text-slate-700 md:hidden" data-mobile-menu-toggle aria-label="Abrir menu" aria-expanded="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6">
                        <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="mt-4 flex items-center gap-3 md:mt-3">
            <form action="<?= e(url('buscar')) ?>" method="GET" class="min-w-0 flex-1">
                <div class="relative">
                    <input type="search" name="q" placeholder="Buscar produtos" class="w-full rounded-[4px] border border-slate-200 bg-slate-50 px-4 py-[0.35rem] pr-10 focus:border-blue-500 focus:outline-none" list="home-search-suggestions" autocomplete="off" data-search-autocomplete data-suggestions-url="<?= e(url('buscar/sugestoes')) ?>">
                    <datalist id="home-search-suggestions"></datalist>
                    <button type="submit" class="absolute right-1 top-1/2 flex h-5 w-5 -translate-y-1/2 items-center justify-center rounded-[4px] bg-blue-600 text-white" aria-label="Buscar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-3.5-3.5"></path>
                        </svg>
                    </button>
                </div>
            </form>
            <div class="hidden items-center gap-3 md:flex">
                <?php if ($authUser): ?>
                    <?php if ($isMarketplaceUser): ?>
                        <a href="<?= e(url('lojista')) ?>" class="rounded-[4px] border border-blue-600 bg-white px-3 py-[0.35rem] text-sm font-semibold text-blue-600">Vender</a>
                    <?php endif; ?>
                    <?php if (($authUser['role'] ?? '') === 'admin'): ?>
                        <a href="<?= e(url('admin')) ?>" class="rounded-[4px] bg-slate-100 px-3 py-[0.35rem] text-sm font-semibold">Admin</a>
                    <?php endif; ?>
                    <form action="<?= e(url('logout')) ?>" method="POST">
                        <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                        <button class="rounded-[4px] bg-blue-600 px-3 py-[0.35rem] text-sm font-semibold text-white">Sair</button>
                    </form>
                <?php else: ?>
                    <a href="<?= e(url('login')) ?>" class="rounded-[4px] bg-blue-600 px-3 py-[0.35rem] text-sm font-semibold text-white">Entre</a>
                    <a href="<?= e(url('cadastro')) ?>" class="rounded-[4px] border border-blue-600 bg-white px-3 py-[0.35rem] text-sm font-semibold text-blue-600">Crie sua conta</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="mobile-menu-panel hidden border-t border-blue-100 bg-blue-50 md:hidden" data-mobile-menu-panel>
        <div class="mx-auto max-w-7xl space-y-6 px-4 py-5">
            <?php if ($authUser): ?>
                <div class="space-y-3">
                    <?php if ($isMarketplaceUser): ?>
                        <a href="<?= e(url('lojista')) ?>" class="block w-full rounded-[4px] border border-blue-600 bg-white px-4 py-[0.35rem] text-center text-sm font-semibold text-blue-600">Vender</a>
                    <?php endif; ?>
                    <?php if (($authUser['role'] ?? '') === 'admin'): ?>
                        <a href="<?= e(url('admin')) ?>" class="block rounded-[4px] bg-slate-100 px-4 py-[0.35rem] text-sm font-semibold text-slate-900">Admin</a>
                    <?php endif; ?>
                    <form action="<?= e(url('logout')) ?>" method="POST">
                        <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                        <button class="block w-full rounded-[4px] bg-blue-600 px-4 py-[0.35rem] text-sm font-semibold text-white">Sair</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="space-y-3 rounded-[4px] bg-blue-200 p-4">
                    <div>
                        <p class="text-base font-bold text-slate-900">Bem-vindo</p>
                        <p class="mt-1 text-sm text-slate-600">Entre ou crie a sua conta para comprar</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="<?= e(url('login')) ?>" class="block rounded-[4px] bg-blue-600 px-3 py-[0.35rem] text-center text-sm font-semibold text-white">Entre</a>
                        <a href="<?= e(url('cadastro')) ?>" class="block rounded-[4px] border border-blue-600 bg-white px-3 py-[0.35rem] text-center text-sm font-semibold text-blue-600">Crie sua conta</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($headerCategories !== []): ?>
                <div class="rounded-[4px] bg-blue-100/50 p-4">
                    <h2 class="text-sm font-black uppercase tracking-[0.2em] text-slate-500">Categorias</h2>
                    <div class="mt-4 grid grid-cols-3 gap-2">
                        <?php foreach ($headerCategories as $index => $category): ?>
                            <a href="<?= e(url('buscar?categoria=' . $category['slug'])) ?>" class="category-menu-item <?= $index >= 6 ? 'hidden' : '' ?> rounded-[4px] border border-slate-200 bg-slate-50 p-2 text-slate-900" data-category-menu-item>
                                <div class="inline-flex rounded-[4px] bg-blue-50 p-1.5 text-blue-600">
                                    <?= category_icon_svg($category['icone'] ?? null) ?>
                                </div>
                                <p class="mt-1.5 text-[11px] font-semibold leading-tight"><?= e($category['nome']) ?></p>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($headerCategories) > 6): ?>
                        <button type="button" class="mt-3 rounded-[4px] text-sm font-semibold text-blue-600" data-category-menu-more>mais...</button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>
