<?php
class Order extends Model
{
    public function create(int $userId, int $storeId, array $customer, array $items, float $total): int
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                'INSERT INTO pedidos
                 (usuario_id, loja_id, nome_cliente, telefone_cliente, endereco_entrega, forma_pagamento, observacoes, total, status, created_at, updated_at)
                 VALUES
                 (:usuario_id, :loja_id, :nome_cliente, :telefone_cliente, :endereco_entrega, :forma_pagamento, :observacoes, :total, "novo", NOW(), NOW())'
            );
            $stmt->execute([
                'usuario_id' => $userId,
                'loja_id' => $storeId,
                'nome_cliente' => $customer['nome_cliente'],
                'telefone_cliente' => $customer['telefone_cliente'],
                'endereco_entrega' => $customer['endereco_entrega'],
                'forma_pagamento' => $customer['forma_pagamento'],
                'observacoes' => $customer['observacoes'],
                'total' => $total,
            ]);

            $orderId = (int) $this->db->lastInsertId();
            if ($orderId <= 0) {
                throw new RuntimeException('Falha ao criar pedido: a tabela pedidos esta sem AUTO_INCREMENT valido.');
            }

            $itemStmt = $this->db->prepare(
                'INSERT INTO pedido_itens
                 (pedido_id, produto_id, quantidade, preco_unitario, subtotal, created_at)
                 VALUES
                 (:pedido_id, :produto_id, :quantidade, :preco_unitario, :subtotal, NOW())'
            );
            $stockStmt = $this->db->prepare('UPDATE produtos SET estoque = GREATEST(estoque - :quantidade, 0) WHERE id = :produto_id');

            foreach ($items as $item) {
                $itemStmt->execute([
                    'pedido_id' => $orderId,
                    'produto_id' => $item['id'],
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['preco'],
                    'subtotal' => $item['subtotal'],
                ]);
                $stockStmt->execute([
                    'quantidade' => $item['quantidade'],
                    'produto_id' => $item['id'],
                ]);
            }

            $this->db->commit();
            return $orderId;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function byStore(int $storeId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM pedidos WHERE loja_id = :loja_id ORDER BY created_at DESC');
        $stmt->execute(['loja_id' => $storeId]);
        return $stmt->fetchAll();
    }

    public function findForStore(int $orderId, int $storeId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT *
             FROM pedidos
             WHERE id = :id AND loja_id = :loja_id
             LIMIT 1'
        );
        $stmt->execute([
            'id' => $orderId,
            'loja_id' => $storeId,
        ]);

        $order = $stmt->fetch();
        if (!$order) {
            return null;
        }

        $itemsStmt = $this->db->prepare(
            'SELECT pi.*, p.nome AS produto_nome, p.slug AS produto_slug, p.imagem_principal
             FROM pedido_itens pi
             INNER JOIN produtos p ON p.id = pi.produto_id
             WHERE pi.pedido_id = :pedido_id
             ORDER BY pi.id ASC'
        );
        $itemsStmt->execute(['pedido_id' => $orderId]);
        $order['itens'] = $itemsStmt->fetchAll();

        return $order;
    }

    public function metrics(): array
    {
        return [
            'pedidos' => (int) $this->db->query('SELECT COUNT(*) FROM pedidos')->fetchColumn(),
            'faturamento' => (float) $this->db->query('SELECT COALESCE(SUM(total), 0) FROM pedidos')->fetchColumn(),
        ];
    }
}
