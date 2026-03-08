<?php
class AdminController extends Controller
{
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
}
