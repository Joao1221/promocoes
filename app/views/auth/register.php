<section class="mx-auto max-w-xl px-4 py-16">
    <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
        <h1 class="text-3xl font-black">Criar conta</h1>
        <form action="<?= e(url('cadastro')) ?>" method="POST" class="mt-8 grid gap-4 md:grid-cols-2">
            <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
            <input type="text" name="nome" placeholder="Nome completo" class="rounded-2xl border border-slate-200 px-4 py-3 md:col-span-2" required>
            <input type="email" name="email" placeholder="E-mail" class="rounded-2xl border border-slate-200 px-4 py-3 md:col-span-2" required>
            <input type="text" name="telefone" placeholder="Telefone" class="rounded-2xl border border-slate-200 px-4 py-3 md:col-span-2">
            <input type="password" name="senha" placeholder="Senha" class="rounded-2xl border border-slate-200 px-4 py-3 md:col-span-2" required>
            <select name="role" class="rounded-2xl border border-slate-200 px-4 py-3 md:col-span-2" required>
                <option value="consumidor">Consumidor</option>
                <option value="lojista">Lojista</option>
            </select>
            <button class="rounded-2xl bg-blue-600 px-4 py-3 font-bold text-white md:col-span-2">Criar conta</button>
        </form>
    </div>
</section>
