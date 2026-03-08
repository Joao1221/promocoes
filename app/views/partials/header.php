<header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 py-4">
        <div class="flex items-center justify-between gap-4">
            <a href="<?= e(url('')) ?>" class="shrink-0 text-2xl font-black text-blue-600">Capela Market</a>
            <span class="hidden text-sm text-slate-600 md:block"><?= e($authUser['nome'] ?? '') ?></span>
        </div>

        <div class="mt-4 flex flex-wrap items-center gap-3 md:mt-3 md:flex-nowrap">
            <form action="<?= e(url('buscar')) ?>" method="GET" class="min-w-0 flex-1">
                <div class="relative">
                    <input type="search" name="q" placeholder="Buscar produtos" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-14 focus:border-blue-500 focus:outline-none">
                    <button type="submit" class="absolute right-1.5 top-1.5 flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white" aria-label="Buscar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-3.5-3.5"></path>
                        </svg>
                    </button>
                </div>
            </form>
            <a href="<?= e(url('carrinho')) ?>" class="rounded-2xl bg-slate-100 px-3 py-3 text-sm font-semibold">Carrinho (<?= (int) $cartCount ?>)</a>
            <?php if ($authUser): ?>
                <?php if (($authUser['role'] ?? '') === 'lojista'): ?>
                    <a href="<?= e(url('lojista')) ?>" class="rounded-2xl bg-slate-100 px-3 py-3 text-sm font-semibold">Painel</a>
                <?php endif; ?>
                <?php if (($authUser['role'] ?? '') === 'admin'): ?>
                    <a href="<?= e(url('admin')) ?>" class="rounded-2xl bg-slate-100 px-3 py-3 text-sm font-semibold">Admin</a>
                <?php endif; ?>
                <form action="<?= e(url('logout')) ?>" method="POST">
                    <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
                    <button class="rounded-2xl bg-blue-600 px-3 py-3 text-sm font-semibold text-white">Sair</button>
                </form>
            <?php else: ?>
                <a href="<?= e(url('login')) ?>" class="rounded-2xl bg-slate-100 px-3 py-3 text-sm font-semibold">Login</a>
                <a href="<?= e(url('cadastro')) ?>" class="rounded-2xl bg-blue-600 px-3 py-3 text-sm font-semibold text-white">Cadastro</a>
            <?php endif; ?>
        </div>
    </div>
</header>
