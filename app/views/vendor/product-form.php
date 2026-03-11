<section class="mx-auto max-w-4xl" x-data="{
    files: [],
    previews: [],
    maxFiles: 4,
    storeId: <?= (int) ($storeId ?? 0) ?>,
    storeName: '<?= e($storeName ?? '') ?>',
    productNumber: <?= (int) ($skuPreviewNumber ?? 0) ?>,
    sku: '<?= e($product['sku'] ?? '') ?>',
    normalize(text) {
        return (text || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-zA-Z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .toLowerCase();
    },
    generateSku() {
        const category = this.$refs.categorySelect?.selectedOptions?.[0];
        const categorySlug = category?.dataset?.slug || 'geral';
        const prefix = ((categorySlug.replace(/[^a-zA-Z0-9]/g, '').toUpperCase().slice(0, 3)) || 'GER').padEnd(3, 'X');
        const storeSlug = this.normalize(this.storeName);
        const parts = storeSlug.split('-').filter(Boolean);
        let storeCode = 'LO';
        if (parts.length === 1) {
            storeCode = parts[0].slice(0, 2).toUpperCase().padEnd(2, 'X');
        } else if (parts.length > 1) {
            storeCode = parts.map((part) => part.charAt(0).toUpperCase()).join('').slice(0, 2).padEnd(2, 'X');
        }
        this.sku = `${prefix}-${storeCode}-${String(this.productNumber).padStart(4, '0')}`;
    },
    removeFile(index, input) {
        this.files.splice(index, 1);
        this.previews.forEach((preview) => URL.revokeObjectURL(preview.url));
        this.previews = this.files.map((file) => ({
            name: file.name,
            url: URL.createObjectURL(file),
        }));

        if (typeof DataTransfer !== 'undefined' && input) {
            const transfer = new DataTransfer();
            this.files.forEach((file) => transfer.items.add(file));
            input.files = transfer.files;
        }
    },
    addFiles(event) {
        const incomingFiles = Array.from(event.target.files || []);
        if (incomingFiles.length === 0) {
            return;
        }

        this.files = [...this.files, ...incomingFiles].slice(0, this.maxFiles);
        this.previews.forEach((preview) => URL.revokeObjectURL(preview.url));
        this.previews = this.files.map((file) => ({
            name: file.name,
            url: URL.createObjectURL(file),
        }));

        if (typeof DataTransfer !== 'undefined') {
            const transfer = new DataTransfer();
            this.files.forEach((file) => transfer.items.add(file));
            event.target.files = transfer.files;
        }
    }
}" x-init="generateSku()">
    <h1 class="text-3xl font-black"><?= $product ? 'Editar produto' : 'Cadastrar produto' ?></h1>
    <form action="<?= e(url('lojista/produtos')) ?>" method="POST" enctype="multipart/form-data" class="mt-8 grid gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 md:grid-cols-2">
        <input type="hidden" name="_token" value="<?= e(Csrf::token()) ?>">
        <?php if ($product): ?>
            <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
        <?php endif; ?>
        <input type="text" name="nome" value="<?= e($product['nome'] ?? '') ?>" placeholder="Nome do produto" class="rounded-2xl border border-slate-200 px-4 py-3 md:col-span-2" x-ref="nameInput" @input="generateSku()" required>
        <select name="categoria_id" class="rounded-2xl border border-slate-200 px-4 py-3 md:col-span-2" x-ref="categorySelect" @change="generateSku()" required>
            <option value="">Categoria</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= (int) $category['id'] ?>" data-slug="<?= e($category['slug']) ?>" <?= (int) ($product['categoria_id'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>><?= e($category['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        <textarea name="descricao" placeholder="Descricao" class="rounded-2xl border border-slate-200 px-4 py-3 md:col-span-2" required><?= e($product['descricao'] ?? '') ?></textarea>
        <input type="number" step="0.01" name="preco_original" value="<?= e($product['preco_original'] ?? '') ?>" placeholder="Preco original" class="rounded-2xl border border-slate-200 px-4 py-3" required>
        <input type="number" step="0.01" name="preco_promocional" value="<?= e($product['preco_promocional'] ?? '') ?>" placeholder="Preco promocional" class="rounded-2xl border border-slate-200 px-4 py-3">
        <input type="number" name="estoque" value="<?= e($product['estoque'] ?? '') ?>" placeholder="Estoque" class="rounded-2xl border border-slate-200 px-4 py-3" required>
        <div>
            <input type="text" name="sku" x-model="sku" placeholder="SKU" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-700" readonly>
            <p class="mt-1 text-xs text-slate-500">SKU gerado automaticamente a partir da categoria e nome do produto.</p>
        </div>
        <label class="rounded-2xl border border-dashed border-slate-300 px-4 py-5 text-sm md:col-span-2">Galeria do produto
            <span class="mt-1 block text-xs text-slate-500">Envie de 1 a 4 imagens. A primeira vira a imagem principal.</span>
            <?php if (!empty($product['imagens'])): ?>
                <span class="mt-1 block text-xs text-amber-600">Ao salvar com novas imagens, a galeria atual sera substituida.</span>
            <?php endif; ?>
            <input type="file" name="imagens[]" accept=".jpg,.jpeg,.png,.webp" multiple class="mt-2 block w-full" x-ref="galleryInput" @change="addFiles($event)">
        </label>
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm md:col-span-2">
            <p class="font-semibold text-slate-900">Imagens selecionadas</p>
            <p class="mt-1 text-slate-500" x-show="previews.length === 0">Nenhuma imagem nova selecionada.</p>
            <div class="mt-3 grid grid-cols-2 gap-3 md:grid-cols-4" x-show="previews.length > 0">
                <template x-for="(preview, index) in previews" :key="preview.url">
                    <div class="rounded-2xl border border-slate-200 bg-white p-2">
                        <img :src="preview.url" :alt="preview.name" class="h-28 w-full rounded-xl object-contain">
                        <button type="button" class="mt-2 w-full rounded-xl bg-red-50 px-3 py-2 text-xs font-semibold text-red-600" @click="removeFile(index, $refs.galleryInput)">Remover</button>
                    </div>
                </template>
            </div>
        </div>
        <?php if (!empty($product['imagens'])): ?>
            <div class="md:col-span-2">
                <p class="mb-3 text-sm font-semibold text-slate-900">Imagens atuais</p>
                <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                <?php foreach ($product['imagens'] as $image): ?>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-2">
                        <img src="<?= e(upload_url('produtos', $image)) ?>" alt="<?= e($product['nome'] ?? 'Produto') ?>" class="h-28 w-full rounded-xl object-contain">
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <p class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-600 md:col-span-2">O destaque de produto e controlado pelo painel administrativo.</p>
        <button class="rounded-2xl bg-blue-600 px-5 py-3 font-bold text-white md:col-span-2">Salvar produto</button>
    </form>
</section>
