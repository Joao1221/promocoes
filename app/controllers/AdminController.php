<?php
class AdminController extends Controller
{
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
        $this->render('admin/dashboard', [
            'title' => 'Painel administrativo',
            'pendingStores' => (new Store())->pending(),
            'pendingProducts' => (new Product())->pending(),
            'metrics' => (new Order())->metrics(),
        ], 'layouts/panel');
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
