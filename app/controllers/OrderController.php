<?php
class OrderController extends Controller
{
    public function checkout(): void
    {
        AuthMiddleware::handle(['consumidor', 'lojista', 'admin']);
        $cart = Session::get('cart', []);
        if (!$cart) {
            Session::flash('error', 'Carrinho vazio.');
            $this->redirect('carrinho');
        }

        $total = array_reduce($cart, fn ($sum, $item) => $sum + ($item['preco'] * $item['quantidade']), 0.0);
        $this->render('orders/checkout', [
            'title' => 'Finalizar pedido',
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    public function store(): void
    {
        AuthMiddleware::handle(['consumidor', 'lojista', 'admin']);
        Csrf::validate($_POST['_token'] ?? null);
        $cart = Session::get('cart', []);
        if (!$cart) {
            Session::flash('error', 'Carrinho vazio.');
            $this->redirect('carrinho');
        }

        $storeId = array_values($cart)[0]['loja_id'];
        foreach ($cart as $item) {
            if ($item['loja_id'] !== $storeId) {
                Session::flash('error', 'Finalize pedidos de uma loja por vez.');
                $this->redirect('carrinho');
            }
        }

        $customer = [
            'nome_cliente' => trim($_POST['nome_cliente'] ?? ''),
            'telefone_cliente' => trim($_POST['telefone_cliente'] ?? ''),
            'endereco_entrega' => trim($_POST['endereco_entrega'] ?? ''),
            'forma_pagamento' => trim($_POST['forma_pagamento'] ?? ''),
            'observacoes' => trim($_POST['observacoes'] ?? ''),
        ];

        if (Validator::required($customer, ['nome_cliente', 'telefone_cliente', 'endereco_entrega', 'forma_pagamento'])) {
            Session::flash('error', 'Preencha os dados de entrega.');
            $this->redirect('checkout');
        }

        $items = array_map(function ($item) {
            $item['subtotal'] = $item['preco'] * $item['quantidade'];
            return $item;
        }, array_values($cart));
        $total = array_sum(array_column($items, 'subtotal'));

        (new Order())->create((int) Auth::user()['id'], (int) $storeId, $customer, $items, $total);
        Session::forget('cart');
        Session::flash('success', 'Pedido enviado para a loja.');
        $this->redirect('');
    }
}
