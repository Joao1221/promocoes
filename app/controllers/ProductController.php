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
        AuthMiddleware::handle('lojista');
        $store = (new Store())->byUser((int) Auth::user()['id']);
        if (!$store) {
            Session::flash('error', 'Cadastre sua loja antes de cadastrar produtos.');
            $this->redirect('lojista/loja');
        }

        $products = (new Product())->byStore((int) $store['id']);
        $product = null;
        foreach ($products as $candidate) {
            if ((string) $candidate['id'] === (string) $id) {
                $product = $candidate;
                break;
            }
        }

        $this->render('vendor/product-form', [
            'title' => $product ? 'Editar produto' : 'Cadastrar produto',
            'product' => $product,
            'categories' => (new Category())->all(),
        ], 'layouts/panel');
    }

    public function save(): void
    {
        AuthMiddleware::handle('lojista');
        Csrf::validate($_POST['_token'] ?? null);
        $store = (new Store())->byUser((int) Auth::user()['id']);

        if (!$store) {
            Session::flash('error', 'Cadastre sua loja antes de cadastrar produtos.');
            $this->redirect('lojista/loja');
        }

        $data = [
            'loja_id' => (int) $store['id'],
            'categoria_id' => (int) ($_POST['categoria_id'] ?? 0),
            'nome' => trim($_POST['nome'] ?? ''),
            'slug' => slugify($_POST['nome'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'preco_original' => (float) ($_POST['preco_original'] ?? 0),
            'preco_promocional' => ($_POST['preco_promocional'] ?? '') !== '' ? (float) $_POST['preco_promocional'] : null,
            'estoque' => (int) ($_POST['estoque'] ?? 0),
            'sku' => trim($_POST['sku'] ?? ''),
            'imagem_principal' => null,
            'status' => 'aprovado',
            'destaque' => isset($_POST['destaque']) ? 1 : 0,
        ];

        if (Validator::required($data, ['categoria_id', 'nome', 'descricao', 'preco_original', 'estoque', 'sku'])) {
            Session::flash('error', 'Preencha os campos obrigatorios do produto.');
            $this->redirect('lojista/produtos/novo');
        }

        try {
            $data['imagem_principal'] = Upload::image($_FILES['imagem_principal'] ?? [], __DIR__ . '/../../uploads/produtos');
        } catch (RuntimeException $e) {
            Session::flash('error', $e->getMessage());
            $this->redirect('lojista/produtos/novo');
        }

        $id = $_POST['id'] ?? null;
        if ($id) {
            (new Product())->update((int) $id, $data);
        } else {
            (new Product())->create($data);
        }

        Session::flash('success', 'Produto publicado com sucesso.');
        $this->redirect('lojista');
    }
}
