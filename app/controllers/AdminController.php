<?php
class AdminController extends Controller
{
    private const MAX_FEATURED_STORES = 8;
    private const MAX_FEATURED_PRODUCTS = 24;
    private const HOME_SHOWCASE_PRODUCTS = 12;

    public function categories(): void
    {
        AuthMiddleware::handle('admin');
        $this->render('admin/categories', [
            'title' => 'Categorias',
            'categories' => (new Category())->all(),
            'iconOptions' => $this->categoryIconOptions(),
        ], 'layouts/panel');
    }

    public function storeCategory(): void
    {
        AuthMiddleware::handle('admin');
        Csrf::validate($_POST['_token'] ?? null);

        $data = [
            'nome' => trim($_POST['nome'] ?? ''),
            'icone' => trim($_POST['icone'] ?? ''),
        ];

        if (Validator::required($data, ['nome', 'icone'])) {
            Session::flash('error', 'Preencha nome e icone da categoria.');
            $this->redirect('admin/categorias');
        }

        if (!in_array($data['icone'], $this->categoryIconOptions(), true)) {
            Session::flash('error', 'Icone invalido para categoria.');
            $this->redirect('admin/categorias');
        }

        if (slugify($data['nome']) === '') {
            Session::flash('error', 'Nome invalido para gerar slug da categoria.');
            $this->redirect('admin/categorias');
        }

        try {
            (new Category())->create($data['nome'], $data['icone']);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                Session::flash('error', 'Ja existe uma categoria com este nome/slug.');
                $this->redirect('admin/categorias');
            }

            throw $e;
        }

        Session::flash('success', 'Categoria cadastrada com sucesso.');
        $this->redirect('admin/categorias');
    }

    public function applyCategoryIcons(): void
    {
        AuthMiddleware::handle('admin');
        Csrf::validate($_POST['_token'] ?? null);

        $updated = (new Category())->assignIconsBySlug($this->defaultCategoryIconMap());
        Session::flash('success', "Icones aplicados em {$updated} categoria(s).");
        $this->redirect('admin/categorias');
    }

    public function dashboard(): void
    {
        AuthMiddleware::handle('admin');
        $this->render('admin/dashboard', array_merge([
            'title' => 'Painel administrativo',
        ], $this->highlightPayload()), 'layouts/panel');
    }

    public function highlights(): void
    {
        AuthMiddleware::handle('admin');
        $this->render('admin/highlights', array_merge([
            'title' => 'Gestao de destaques',
        ], $this->highlightPayload()), 'layouts/panel');
    }

    public function saveHomeFeaturedProducts(): void
    {
        AuthMiddleware::handle('admin');
        Csrf::validate($_POST['_token'] ?? null);

        $slotsInput = is_array($_POST['slots'] ?? null) ? $_POST['slots'] : [];
        $slots = [];
        for ($position = 1; $position <= self::HOME_SHOWCASE_PRODUCTS; $position++) {
            $slots[$position] = (int) ($slotsInput[$position] ?? 0);
        }

        try {
            (new HomeFeaturedProduct())->saveSlots($slots, self::HOME_SHOWCASE_PRODUCTS);
            $totalSelected = count(array_filter($slots, static fn (int $id) => $id > 0));
            Session::flash('success', "Selecao da Home atualizada com {$totalSelected} produto(s).");
        } catch (RuntimeException $e) {
            Session::flash('error', $e->getMessage());
        }

        $this->redirect('admin/destaques#home-12');
    }

    public function approveStore(string $id): void
    {
        AuthMiddleware::handle('admin');
        Csrf::validate($_POST['_token'] ?? null);
        (new Store())->approve((int) $id);
        Session::flash('success', 'Loja aprovada.');
        $this->redirect('admin');
    }

    public function approveProduct(string $id): void
    {
        AuthMiddleware::handle('admin');
        Csrf::validate($_POST['_token'] ?? null);
        (new Product())->approve((int) $id);
        Session::flash('success', 'Produto aprovado.');
        $this->redirect('admin');
    }

    public function setStoreFeatured(string $id): void
    {
        AuthMiddleware::handle('admin');
        Csrf::validate($_POST['_token'] ?? null);

        $storeId = (int) $id;
        $featured = (($_POST['featured'] ?? '0') === '1');
        $storeModel = new Store();
        $store = $storeModel->find($storeId);

        if (!$store) {
            Session::flash('error', 'Loja nao encontrada.');
            $this->redirect('admin/destaques#lojas-destaque');
        }

        $isCurrentlyFeatured = (int) ($store['destaque'] ?? 0) === 1;
        if ($featured && !$isCurrentlyFeatured && $storeModel->featuredCount() >= self::MAX_FEATURED_STORES) {
            Session::flash('error', 'Limite de lojas em destaque atingido. Remova um destaque antes.');
            $this->redirect('admin/destaques#lojas-destaque');
        }

        if (!$storeModel->setFeatured($storeId, $featured)) {
            Session::flash('error', 'So e possivel destacar lojas aprovadas.');
            $this->redirect('admin/destaques#lojas-destaque');
        }

        Session::flash('success', $featured ? 'Loja marcada como destaque.' : 'Destaque da loja removido.');
        $this->redirect('admin/destaques#lojas-destaque');
    }

    public function setProductFeatured(string $id): void
    {
        AuthMiddleware::handle('admin');
        Csrf::validate($_POST['_token'] ?? null);

        $productId = (int) $id;
        $featured = (($_POST['featured'] ?? '0') === '1');
        $productModel = new Product();
        $product = $productModel->find($productId);

        if (!$product) {
            Session::flash('error', 'Produto nao encontrado.');
            $this->redirect('admin/destaques#produtos-destaque');
        }

        $isCurrentlyFeatured = (int) ($product['destaque'] ?? 0) === 1;
        if ($featured && !$isCurrentlyFeatured && $productModel->featuredCount() >= self::MAX_FEATURED_PRODUCTS) {
            Session::flash('error', 'Limite de produtos em destaque atingido. Remova um destaque antes.');
            $this->redirect('admin/destaques#produtos-destaque');
        }

        if (!$productModel->setFeatured($productId, $featured)) {
            Session::flash('error', 'So e possivel destacar produtos aprovados de lojas aprovadas.');
            $this->redirect('admin/destaques#produtos-destaque');
        }

        Session::flash('success', $featured ? 'Produto marcado como destaque.' : 'Destaque do produto removido.');
        $this->redirect('admin/destaques#produtos-destaque');
    }

    private function highlightPayload(): array
    {
        $orderModel = new Order();
        $storeModel = new Store();
        $userModel = new User();
        $productModel = new Product();
        $homeFeaturedModel = new HomeFeaturedProduct();

        return [
            'pendingStores' => $storeModel->pending(),
            'pendingProducts' => $productModel->pending(),
            'orderMetrics' => $orderModel->metrics(),
            'storeMetrics' => $storeModel->adminMetrics(),
            'userMetrics' => $userModel->adminMetrics(),
            'storeOverview' => $storeModel->adminOverview(),
            'productOverview' => $productModel->adminOverview(),
            'recentOrders' => $orderModel->adminRecent(),
            'userProfiles' => $userModel->adminProfiles(),
            'featuredLimits' => [
                'stores' => self::MAX_FEATURED_STORES,
                'products' => self::MAX_FEATURED_PRODUCTS,
                'home_products' => self::HOME_SHOWCASE_PRODUCTS,
            ],
            'featuredCounts' => [
                'stores' => $storeModel->featuredCount(),
                'products' => $productModel->featuredCount(),
                'home_products' => count($homeFeaturedModel->currentSlots(self::HOME_SHOWCASE_PRODUCTS)),
            ],
            'homeFeaturedSlots' => $homeFeaturedModel->currentSlots(self::HOME_SHOWCASE_PRODUCTS),
            'homeFeaturedOptions' => $homeFeaturedModel->selectableProducts(),
        ];
    }

    private function categoryIconOptions(): array
    {
        return [
            'Carrinho',
            'Saude',
            'Prato',
            'Camisa',
            'Plug',
            'Ferramenta',
            'Agenda',
            'Acougue',
            'Frios',
            'Doces',
            'Frutas',
            'Verduras',
            'Padaria',
            'Bebidas',
            'Limpeza',
            'Higiene',
            'PetShop',
            'Papelaria',
            'Livraria',
            'Brinquedos',
            'Esportes',
            'Automotivo',
            'Ferramentas',
            'Jardim',
            'Moveis',
            'Decoracao',
            'Informatica',
            'Telefonia',
            'Otica',
            'Relojoaria',
            'Festa',
            'CamaMesaBanho',
        ];
    }

    private function defaultCategoryIconMap(): array
    {
        return [
            'acougue' => 'Acougue',
            'frios' => 'Frios',
            'doces' => 'Doces',
            'frutas' => 'Frutas',
            'verduras' => 'Verduras',
            'padaria' => 'Padaria',
            'bebidas' => 'Bebidas',
            'limpeza' => 'Limpeza',
            'higiene' => 'Higiene',
            'pet-shop' => 'PetShop',
            'papelaria' => 'Papelaria',
            'livraria' => 'Livraria',
            'brinquedos' => 'Brinquedos',
            'esportes' => 'Esportes',
            'automotivo' => 'Automotivo',
            'ferramentas' => 'Ferramentas',
            'jardim' => 'Jardim',
            'moveis' => 'Moveis',
            'decoracao' => 'Decoracao',
            'informatica' => 'Informatica',
            'telefonia' => 'Telefonia',
            'otica' => 'Otica',
            'relojoaria' => 'Relojoaria',
            'artigos-de-festa' => 'Festa',
            'cama-mesa-e-banho' => 'CamaMesaBanho',
        ];
    }
}
