<?php
class VendorController extends Controller
{
    public function dashboard(): void
    {
        AuthMiddleware::handle(['consumidor', 'lojista']);
        $store = (new Store())->byUser((int) Auth::user()['id']);
        $orders = $store ? (new Order())->byStore((int) $store['id']) : [];
        $query = trim((string) ($_GET['q'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $allowedPerPage = [10, 20, 50, 100];
        $perPage = (int) ($_GET['per_page'] ?? 20);
        $perPage = in_array($perPage, $allowedPerPage, true) ? $perPage : 20;

        $products = [];
        $productStats = ['total_produtos' => 0, 'total_views' => 0];
        $filteredProducts = 0;
        $totalPages = 1;

        if ($store) {
            $productModel = new Product();
            $products = $productModel->paginatedByStore((int) $store['id'], $query, $page, $perPage);
            $productStats = $productModel->statsByStore((int) $store['id']);
            $filteredProducts = $productModel->countByStore((int) $store['id'], $query);
            $totalPages = max(1, (int) ceil($filteredProducts / $perPage));
            $page = min($page, $totalPages);

            if ($page !== (int) ($_GET['page'] ?? 1)) {
                $products = $productModel->paginatedByStore((int) $store['id'], $query, $page, $perPage);
            }
        }

        $this->render('vendor/dashboard', [
            'title' => 'Painel de vendas',
            'store' => $store,
            'products' => $products,
            'orders' => $orders,
            'productStats' => $productStats,
            'productFilters' => [
                'q' => $query,
                'page' => $page,
                'per_page' => $perPage,
                'total' => $filteredProducts,
                'total_pages' => $totalPages,
            ],
            'perPageOptions' => $allowedPerPage,
        ], 'layouts/panel');
    }

    public function orderDetail(string $id): void
    {
        AuthMiddleware::handle(['consumidor', 'lojista']);
        $store = (new Store())->byUser((int) Auth::user()['id']);

        if (!$store) {
            Session::flash('error', 'Cadastre sua loja antes de acessar pedidos.');
            $this->redirect('lojista/loja');
        }

        $order = (new Order())->findForStore((int) $id, (int) $store['id']);
        if (!$order) {
            http_response_code(404);
            exit('Pedido nao encontrado.');
        }

        $this->render('vendor/order-detail', [
            'title' => 'Pedido #' . $order['id'],
            'store' => $store,
            'order' => $order,
        ], 'layouts/panel');
    }
}
