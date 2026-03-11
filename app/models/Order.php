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
        $base = $this->db->query(
            'SELECT
                COUNT(*) AS pedidos,
                COALESCE(SUM(total), 0) AS faturamento,
                COALESCE(AVG(total), 0) AS ticket_medio,
                SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) AS pedidos_hoje
             FROM pedidos'
        )->fetch() ?: [];

        $statusTotals = [
            'novo' => 0,
            'em_preparo' => 0,
            'enviado' => 0,
            'concluido' => 0,
            'cancelado' => 0,
        ];

        $rows = $this->db->query('SELECT status, COUNT(*) AS total FROM pedidos GROUP BY status')->fetchAll();
        foreach ($rows as $row) {
            $status = (string) ($row['status'] ?? '');
            if (array_key_exists($status, $statusTotals)) {
                $statusTotals[$status] = (int) ($row['total'] ?? 0);
            }
        }

        return [
            'pedidos' => (int) ($base['pedidos'] ?? 0),
            'faturamento' => (float) ($base['faturamento'] ?? 0),
            'ticket_medio' => (float) ($base['ticket_medio'] ?? 0),
            'pedidos_hoje' => (int) ($base['pedidos_hoje'] ?? 0),
            'status' => $statusTotals,
        ];
    }

    public function adminRecent(int $limit = 150): array
    {
        $limit = max(1, min(1000, $limit));
        $stmt = $this->db->prepare(
            'SELECT
                p.id,
                p.loja_id,
                p.usuario_id,
                p.nome_cliente,
                p.telefone_cliente,
                p.endereco_entrega,
                p.forma_pagamento,
                p.total,
                p.status,
                p.created_at,
                u.nome AS comprador_nome,
                u.email AS comprador_email,
                l.nome_loja,
                l.slug AS loja_slug
             FROM pedidos p
             INNER JOIN usuarios u ON u.id = p.usuario_id
             INNER JOIN lojas l ON l.id = p.loja_id
             ORDER BY p.created_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
