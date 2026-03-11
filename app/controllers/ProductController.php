<?php
class ProductController extends Controller
{
    public function search(): void
    {
        $this->render('products/search', [
            'title' => 'Buscar produtos',
            'products' => (new Product())->search($_GET),
            'categories' => (new Category())->all(),
            'filters' => $_GET,
        ]);
    }

    public function show(string $slug): void
    {
        $productModel = new Product();
        $product = $productModel->findBySlug($slug);
        if (!$product) {
            http_response_code(404);
            exit('Produto nao encontrado.');
        }

        $productModel->incrementViews((int) $product['id']);
        $this->render('products/show', [
            'title' => $product['nome'],
            'product' => $product,
        ]);
    }

    public function vendorForm(?string $id = null): void
    {
        AuthMiddleware::handle(['consumidor', 'lojista']);
        $store = (new Store())->byUser((int) Auth::user()['id']);
        if (!$store) {
            Session::flash('error', 'Cadastre sua loja antes de cadastrar produtos.');
            $this->redirect('lojista/loja');
        }

        $product = $id ? (new Product())->findForStore((int) $id, (int) $store['id']) : null;

        $this->render('vendor/product-form', [
            'title' => $product ? 'Editar produto' : 'Cadastrar produto',
            'product' => $product,
            'categories' => (new Category())->all(),
            'storeId' => (int) $store['id'],
            'storeName' => (string) ($store['nome_loja'] ?? ''),
            'skuPreviewNumber' => $product ? (int) $product['id'] : (new Product())->nextIdEstimate(),
        ], 'layouts/panel');
    }

    public function save(): void
    {
        AuthMiddleware::handle(['consumidor', 'lojista']);
        Csrf::validate($_POST['_token'] ?? null);
        $store = (new Store())->byUser((int) Auth::user()['id']);

        if (!$store) {
            Session::flash('error', 'Cadastre sua loja antes de cadastrar produtos.');
            $this->redirect('lojista/loja');
        }

        $categories = (new Category())->all();
        $data = [
            'loja_id' => (int) $store['id'],
            'categoria_id' => (int) ($_POST['categoria_id'] ?? 0),
            'nome' => trim($_POST['nome'] ?? ''),
            'slug' => slugify($_POST['nome'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'preco_original' => (float) ($_POST['preco_original'] ?? 0),
            'preco_promocional' => ($_POST['preco_promocional'] ?? '') !== '' ? (float) $_POST['preco_promocional'] : null,
            'estoque' => (int) ($_POST['estoque'] ?? 0),
            'sku' => '',
            'imagem_principal' => null,
            'status' => 'aprovado',
            'destaque' => isset($_POST['destaque']) ? 1 : 0,
        ];

        $data['sku'] = 'TMP-' . time();

        if (Validator::required($data, ['categoria_id', 'nome', 'descricao', 'preco_original', 'estoque'])) {
            Session::flash('error', 'Preencha os campos obrigatorios do produto.');
            $this->redirect('lojista/produtos/novo');
        }

        $productModel = new Product();
        $uploadedImages = [];
        $id = $_POST['id'] ?? null;
        $existingProduct = $id ? $productModel->findForStore((int) $id, (int) $store['id']) : null;

        try {
            $uploadedImages = $this->uploadGallery($_FILES['imagens'] ?? [], __DIR__ . '/../../uploads/produtos');
            if ($uploadedImages !== []) {
                $data['imagem_principal'] = $uploadedImages[0];
            }
        } catch (RuntimeException $e) {
            Session::flash('error', $e->getMessage());
            $this->redirect('lojista/produtos/novo');
        }

        if (!$id && $uploadedImages === []) {
            Session::flash('error', 'Envie pelo menos 1 imagem para o produto.');
            $this->redirect('lojista/produtos/novo');
        }

        if ($id) {
            $data['sku'] = $this->generateSku((string) ($store['nome_loja'] ?? ''), $data['categoria_id'], (int) $id, $categories);
            $productModel->update((int) $id, $data);
            if ($uploadedImages !== []) {
                $productModel->replaceImages((int) $id, $uploadedImages);
            }
        } else {
            $newId = $productModel->create($data);
            $productModel->updateSku($newId, $this->generateSku((string) ($store['nome_loja'] ?? ''), $data['categoria_id'], $newId, $categories));
            if ($uploadedImages === [] && $data['imagem_principal']) {
                $uploadedImages = [$data['imagem_principal']];
            }
            if ($uploadedImages !== []) {
                $productModel->replaceImages($newId, $uploadedImages);
            }
        }

        Session::flash('success', 'Produto publicado com sucesso.');
        $this->redirect('lojista');
    }

    private function uploadGallery(array $files, string $targetDir): array
    {
        if ($files === [] || !isset($files['name']) || !is_array($files['name'])) {
            return [];
        }

        $total = count($files['name']);
        if ($total > 4) {
            throw new RuntimeException('Envie no maximo 4 imagens por produto.');
        }

        $uploaded = [];
        for ($i = 0; $i < $total; $i++) {
            $file = [
                'name' => $files['name'][$i] ?? '',
                'type' => $files['type'][$i] ?? '',
                'tmp_name' => $files['tmp_name'][$i] ?? '',
                'error' => $files['error'][$i] ?? UPLOAD_ERR_NO_FILE,
                'size' => $files['size'][$i] ?? 0,
            ];

            $filename = Upload::image($file, $targetDir);
            if ($filename !== null) {
                $uploaded[] = $filename;
            }
        }

        if (count($uploaded) > 4) {
            throw new RuntimeException('Envie no maximo 4 imagens por produto.');
        }

        return $uploaded;
    }

    private function generateSku(string $storeName, int $categoryId, int $productId, array $categories): string
    {
        $categorySlug = 'geral';
        foreach ($categories as $category) {
            if ((int) ($category['id'] ?? 0) === $categoryId) {
                $categorySlug = (string) ($category['slug'] ?? $categorySlug);
                break;
            }
        }

        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $categorySlug) ?: 'GER', 0, 3));
        $prefix = str_pad($prefix, 3, 'X');
        $storeCode = $this->storeCode($storeName);

        return sprintf('%s-%s-%04d', $prefix, $storeCode, $productId);
    }

    private function storeCode(string $storeName): string
    {
        $slug = slugify($storeName);
        $parts = array_values(array_filter(explode('-', $slug)));

        if ($parts === []) {
            return 'LO';
        }

        if (count($parts) === 1) {
            return strtoupper(str_pad(substr($parts[0], 0, 2), 2, 'X'));
        }

        $letters = '';
        foreach ($parts as $part) {
            $letters .= strtoupper(substr($part, 0, 1));
            if (strlen($letters) >= 2) {
                break;
            }
        }

        return str_pad($letters, 2, 'X');
    }
}
