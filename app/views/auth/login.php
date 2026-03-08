<section class="mx-auto max-w-md px-4 py-16">
    <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
        <h1 class="text-3xl font-black">Entrar</h1>
        <form action="<?= e(url('login')) ?>" method="POST" class="mt-8 space-y-4">
            <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
            <input type="email" name="email" placeholder="Seu e-mail" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
            <input type="password" name="senha" placeholder="Sua senha" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
            <button class="w-full rounded-2xl bg-blue-600 px-4 py-3 font-bold text-white">Acessar</button>
        </form>
        <p class="mt-6 text-center text-sm text-slate-500">
            Ainda nao tem conta?
            <a href="<?= e(url('cadastro')) ?>" class="font-semibold text-blue-600">Cadastre-se</a>
        </p>
    </div>
</section>
