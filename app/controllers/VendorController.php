<?php
class VendorController extends Controller
{
    public function dashboard(): void
    {
        AuthMiddleware::handle(['consumidor', 'lojista']);
        $store = (new Store())->byUser((int) Auth::user()['id']);
        $products = $store ? (new Product())->byStore((int) $store['id']) : [];
        $orders = $store ? (new Order())->byStore((int) $store['id']) : [];

        $this->render('vendor/dashboard', [
            'title' => 'Painel de vendas',
            'store' => $store,
            'products' => $products,
            'orders' => $orders,
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
