<?php
class Product extends Model
{
    public function homePromotions(int $limit = 12): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, l.nome_loja, l.slug AS loja_slug, c.nome AS categoria_nome
             FROM produtos p
             INNER JOIN lojas l ON l.id = p.loja_id
             INNER JOIN categorias c ON c.id = p.categoria_id
             WHERE p.status = "aprovado" AND l.status = "aprovada" AND p.preco_promocional IS NOT NULL
             ORDER BY ((p.preco_original - p.preco_promocional) / p.preco_original) DESC, p.updated_at DESC'
        );
        $stmt->execute();
        return $this->interleaveByStore($stmt->fetchAll(), $limit);
    }

    private function interleaveByStore(array $products, int $limit): array
    {
        if ($limit <= 0 || $products === []) {
            return [];
        }

        $byStore = [];
        foreach ($products as $product) {
            $storeId = (int) $product['loja_id'];
            $byStore[$storeId][] = $product;
        }

        $storeIds = array_keys($byStore);
        $selected = [];

        while (count($selected) < $limit) {
            $addedInRound = false;

            foreach ($storeIds as $storeId) {
                if (empty($byStore[$storeId])) {
                    continue;
                }

                $selected[] = array_shift($byStore[$storeId]);
                $addedInRound = true;

                if (count($selected) >= $limit) {
                    break;
                }
            }

            if (!$addedInRound) {
                break;
            }
        }

        return $selected;
    }

    public function featured(int $limit = 12): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, l.nome_loja, l.slug AS loja_slug, c.nome AS categoria_nome
             FROM produtos p
             INNER JOIN lojas l ON l.id = p.loja_id
             INNER JOIN categorias c ON c.id = p.categoria_id
             WHERE p.status = "aprovado" AND l.status = "aprovada"
             ORDER BY p.destaque DESC, p.views DESC, p.created_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function search(array $filters): array
    {
        $sql = 'SELECT p.*, l.nome_loja, l.slug AS loja_slug, l.bairro, c.nome AS categoria_nome
                FROM produtos p
                INNER JOIN lojas l ON l.id = p.loja_id
                INNER JOIN categorias c ON c.id = p.categoria_id
                WHERE p.status = "aprovado" AND l.status = "aprovada"';
        $params = [];

        if (!empty($filters['q'])) {
            $sql .= ' AND (p.nome LIKE :q_produto OR p.descricao LIKE :q_descricao OR l.nome_loja LIKE :q_loja OR c.nome LIKE :q_categoria)';
            $term = '%' . $filters['q'] . '%';
            $params['q_produto'] = $term;
            $params['q_descricao'] = $term;
            $params['q_loja'] = $term;
            $params['q_categoria'] = $term;
        }

        if (!empty($filters['categoria'])) {
            $sql .= ' AND c.slug = :categoria';
            $params['categoria'] = $filters['categoria'];
        }

        if (!empty($filters['bairro'])) {
            $sql .= ' AND l.bairro LIKE :bairro';
            $params['bairro'] = '%' . $filters['bairro'] . '%';
        }

        if (!empty($filters['loja'])) {
            $sql .= ' AND l.slug = :loja';
            $params['loja'] = $filters['loja'];
        }

        if (!empty($filters['preco_min'])) {
            $sql .= ' AND COALESCE(p.preco_promocional, p.preco_original) >= :preco_min';
            $params['preco_min'] = $filters['preco_min'];
        }

        if (!empty($filters['preco_max'])) {
            $sql .= ' AND COALESCE(p.preco_promocional, p.preco_original) <= :preco_max';
            $params['preco_max'] = $filters['preco_max'];
        }

        $sql .= ' ORDER BY p.destaque DESC, p.created_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, l.nome_loja, l.slug AS loja_slug, l.whatsapp, c.nome AS categoria_nome
             FROM produtos p
             INNER JOIN lojas l ON l.id = p.loja_id
             INNER JOIN categorias c ON c.id = p.categoria_id
             WHERE p.slug = :slug LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public function byStore(int $storeId): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, c.nome AS categoria_nome, l.nome_loja, l.slug AS loja_slug
             FROM produtos p
             INNER JOIN categorias c ON c.id = p.categoria_id
             INNER JOIN lojas l ON l.id = p.loja_id
             WHERE p.loja_id = :loja_id
             ORDER BY p.created_at DESC'
        );
        $stmt->execute(['loja_id' => $storeId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO produtos
             (loja_id, categoria_id, nome, slug, descricao, preco_original, preco_promocional, estoque, sku, imagem_principal, status, destaque, views, created_at, updated_at)
             VALUES
             (:loja_id, :categoria_id, :nome, :slug, :descricao, :preco_original, :preco_promocional, :estoque, :sku, :imagem_principal, :status, :destaque, 0, NOW(), NOW())'
        );
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $stmt = $this->db->prepare(
            'UPDATE produtos SET
             categoria_id = :categoria_id,
             nome = :nome,
             slug = :slug,
             descricao = :descricao,
             preco_original = :preco_original,
             preco_promocional = :preco_promocional,
             estoque = :estoque,
             sku = :sku,
             imagem_principal = COALESCE(:imagem_principal, imagem_principal),
             destaque = :destaque,
             updated_at = NOW()
             WHERE id = :id'
        );
        $stmt->execute($data);
    }

    public function incrementViews(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE produtos SET views = views + 1 WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function pending(): array
    {
        return $this->db->query(
            'SELECT p.*, l.nome_loja FROM produtos p
             INNER JOIN lojas l ON l.id = p.loja_id
             WHERE p.status = "pendente" ORDER BY p.created_at ASC'
        )->fetchAll();
    }

    public function approve(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE produtos SET status = "aprovado", updated_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
