<section class="mx-auto max-w-4xl">
    <h1 class="text-3xl font-black"><?= $store ? 'Editar loja' : 'Cadastrar loja' ?></h1>
    <form action="<?= e(url('lojista/loja')) ?>" method="POST" enctype="multipart/form-data" class="mt-8 grid gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 md:grid-cols-2">
        <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
        <input type="text" name="nome_loja" value="<?= e($store['nome_loja'] ?? '') ?>" placeholder="Nome da loja" class="rounded-2xl border border-slate-200 px-4 py-3 md:col-span-2" required>
        <textarea name="descricao" placeholder="Descricao" class="rounded-2xl border border-slate-200 px-4 py-3 md:col-span-2"><?= e($store['descricao'] ?? '') ?></textarea>
        <select name="documento_tipo" class="rounded-2xl border border-slate-200 px-4 py-3" required>
            <option value="">Documento do vendedor</option>
            <option value="CPF" <?= (($store['documento_tipo'] ?? '') === 'CPF') ? 'selected' : '' ?>>CPF</option>
            <option value="CNPJ" <?= (($store['documento_tipo'] ?? '') === 'CNPJ') ? 'selected' : '' ?>>CNPJ</option>
        </select>
        <input type="text" name="documento_numero" value="<?= e($store['documento_numero'] ?? '') ?>" placeholder="Numero do documento" class="rounded-2xl border border-slate-200 px-4 py-3" required>
        <input type="text" name="telefone" value="<?= e($store['telefone'] ?? '') ?>" placeholder="Telefone" class="rounded-2xl border border-slate-200 px-4 py-3" required>
        <input type="text" name="whatsapp" value="<?= e($store['whatsapp'] ?? '') ?>" placeholder="WhatsApp" class="rounded-2xl border border-slate-200 px-4 py-3" required>
        <select name="vende_online" class="rounded-2xl border border-slate-200 px-4 py-3">
            <option value="1" <?= ((int) ($store['vende_online'] ?? 1) === 1) ? 'selected' : '' ?>>Vende online: Sim</option>
            <option value="0" <?= ((int) ($store['vende_online'] ?? 1) === 0) ? 'selected' : '' ?>>Vende online: Nao</option>
        </select>
        <input type="text" name="forma_pagamento" value="<?= e($store['forma_pagamento'] ?? 'PIX') ?>" placeholder="Forma de pagamento (recomendado: PIX)" class="rounded-2xl border border-slate-200 px-4 py-3">
        <select name="tem_delivery" class="rounded-2xl border border-slate-200 px-4 py-3">
            <option value="1" <?= ((int) ($store['tem_delivery'] ?? 0) === 1) ? 'selected' : '' ?>>Tem delivery: Sim</option>
            <option value="0" <?= ((int) ($store['tem_delivery'] ?? 0) === 0) ? 'selected' : '' ?>>Tem delivery: Nao</option>
        </select>
        <input type="text" name="instagram" value="<?= e($store['instagram'] ?? '') ?>" placeholder="@instagram" class="rounded-2xl border border-slate-200 px-4 py-3">
        <input type="text" name="cidade" value="<?= e($store['cidade'] ?? 'Capela') ?>" placeholder="Cidade" class="rounded-2xl border border-slate-200 px-4 py-3">
        <input type="text" name="bairro" value="<?= e($store['bairro'] ?? '') ?>" placeholder="Bairro" class="rounded-2xl border border-slate-200 px-4 py-3" required>
        <input type="text" name="endereco" value="<?= e($store['endereco'] ?? '') ?>" placeholder="Endereco" class="rounded-2xl border border-slate-200 px-4 py-3" required>
        <input type="text" name="horario_funcionamento" value="<?= e($store['horario_funcionamento'] ?? '') ?>" placeholder="Horario de funcionamento" class="rounded-2xl border border-slate-200 px-4 py-3 md:col-span-2">
        <label class="rounded-2xl border border-dashed border-slate-300 px-4 py-5 text-sm">Logo
            <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp" class="mt-2 block w-full">
        </label>
        <label class="rounded-2xl border border-dashed border-slate-300 px-4 py-5 text-sm">Banner
            <input type="file" name="banner" accept=".jpg,.jpeg,.png,.webp" class="mt-2 block w-full">
        </label>
        <label class="rounded-2xl border border-dashed border-slate-300 px-4 py-5 text-sm">Banner mobile
            <input type="file" name="banner_mobile" accept=".jpg,.jpeg,.png,.webp" class="mt-2 block w-full">
        </label>
        <button class="rounded-2xl bg-blue-600 px-5 py-3 font-bold text-white md:col-span-2">Salvar loja</button>
    </form>
</section>
