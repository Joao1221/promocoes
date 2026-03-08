<?php
class CartController extends Controller
{
    public function add(): void
    {
        Csrf::validate($_POST['_token'] ?? null);
        $product = (new Product())->findBySlug($_POST['slug'] ?? '');
        if (!$product) {
            Session::flash('error', 'Produto invalido.');
            $this->redirect('');
        }

        $cart = Session::get('cart', []);
        $id = (int) $product['id'];
        $quantity = max(1, (int) ($_POST['quantidade'] ?? 1));

        if (isset($cart[$id])) {
            $cart[$id]['quantidade'] += $quantity;
        } else {
            $cart[$id] = [
                'id' => $id,
                'slug' => $product['slug'],
                'nome' => $product['nome'],
                'loja_id' => (int) $product['loja_id'],
                'loja_nome' => $product['nome_loja'],
                'preco' => active_price($product),
                'quantidade' => $quantity,
                'imagem' => $product['imagem_principal'],
            ];
        }

        Session::set('cart', $cart);
        Session::flash('success', 'Produto adicionado ao carrinho.');
        $this->redirect('carrinho');
    }

    public function show(): void
    {
        $cart = Session::get('cart', []);
        $total = array_reduce($cart, fn ($sum, $item) => $sum + ($item['preco'] * $item['quantidade']), 0.0);
        $this->render('orders/cart', [
            'title' => 'Carrinho',
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    public function remove(string $id): void
    {
        Csrf::validate($_POST['_token'] ?? null);
        $cart = Session::get('cart', []);
        unset($cart[(int) $id]);
        Session::set('cart', $cart);
        Session::flash('success', 'Item removido do carrinho.');
        $this->redirect('carrinho');
    }
}
