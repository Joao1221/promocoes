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
            'checkoutProfile' => Auth::user(),
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
            'forma_pagamento' => 'Negociar no WhatsApp',
            'observacoes' => trim($_POST['observacoes'] ?? ''),
        ];

        if (Validator::required($customer, ['nome_cliente', 'telefone_cliente', 'endereco_entrega'])) {
            Session::flash('error', 'Preencha os dados de entrega.');
            $this->redirect('checkout');
        }

        (new User())->updateCheckoutProfile((int) Auth::user()['id'], [
            'nome' => $customer['nome_cliente'],
            'telefone' => $customer['telefone_cliente'],
            'endereco_entrega' => $customer['endereco_entrega'],
        ]);

        Session::set('user', array_merge(Auth::user() ?? [], [
            'nome' => $customer['nome_cliente'],
            'telefone' => $customer['telefone_cliente'],
            'endereco_entrega' => $customer['endereco_entrega'],
        ]));

        $items = array_map(function ($item) {
            $item['subtotal'] = $item['preco'] * $item['quantidade'];
            return $item;
        }, array_values($cart));
        $total = array_sum(array_column($items, 'subtotal'));

        $store = (new Store())->find((int) $storeId);
        if (!$store || trim((string) ($store['whatsapp'] ?? '')) === '') {
            Session::flash('error', 'A loja nao possui WhatsApp cadastrado para concluir o pedido.');
            $this->redirect('checkout');
        }

        (new Order())->create((int) Auth::user()['id'], (int) $storeId, $customer, $items, $total);
        Session::forget('cart');

        $messageLines = [
            '*Pedido Capela Market:*',
            '*Cliente:* ' . $customer['nome_cliente'],
            '*Telefone:* ' . $customer['telefone_cliente'],
            '*Entrega:* ' . $customer['endereco_entrega'],
        ];

        if ($customer['observacoes'] !== '') {
            $messageLines[] = '*Observacoes:* ' . $customer['observacoes'];
        }

        $messageLines[] = '';
        $messageLines[] = '*Resumo:*';
        $messageLines[] = '';

        foreach ($items as $item) {
            $messageLines[] = sprintf('%dx %s', (int) $item['quantidade'], $item['nome']);
            $messageLines[] = format_price((float) $item['subtotal']);
            $messageLines[] = '';
        }

        $messageLines[] = '*Total geral*';
        $messageLines[] = '*' . format_price((float) $total) . '*';

        $whatsAppUrl = 'https://wa.me/55' . preg_replace('/\D+/', '', (string) $store['whatsapp']) . '?text=' . rawurlencode(implode("\n", $messageLines));
        header('Location: ' . $whatsAppUrl);
        exit;
    }
}
