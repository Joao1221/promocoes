<?php
class Product extends Model
{
    private const MAX_IMAGES = 4;

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
        $product = $stmt->fetch() ?: null;
        if (!$product) {
            return null;
        }

        return $this->attachImages($product);
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

    public function paginatedByStore(int $storeId, string $query = '', int $page = 1, int $perPage = 20): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $sql = 'SELECT p.*, c.nome AS categoria_nome, l.nome_loja, l.slug AS loja_slug
                FROM produtos p
                INNER JOIN categorias c ON c.id = p.categoria_id
                INNER JOIN lojas l ON l.id = p.loja_id
                WHERE p.loja_id = :loja_id';
        $params = ['loja_id' => $storeId];

        if ($query !== '') {
            $sql .= ' AND (p.nome LIKE :q_nome OR p.sku LIKE :q_sku OR c.nome LIKE :q_categoria)';
            $term = '%' . $query . '%';
            $params['q_nome'] = $term;
            $params['q_sku'] = $term;
            $params['q_categoria'] = $term;
        }

        $sql .= ' ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countByStore(int $storeId, string $query = ''): int
    {
        $sql = 'SELECT COUNT(*)
                FROM produtos p
                INNER JOIN categorias c ON c.id = p.categoria_id
                WHERE p.loja_id = :loja_id';
        $params = ['loja_id' => $storeId];

        if ($query !== '') {
            $sql .= ' AND (p.nome LIKE :q_nome OR p.sku LIKE :q_sku OR c.nome LIKE :q_categoria)';
            $term = '%' . $query . '%';
            $params['q_nome'] = $term;
            $params['q_sku'] = $term;
            $params['q_categoria'] = $term;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function statsByStore(int $storeId): array
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) AS total_produtos, COALESCE(SUM(views), 0) AS total_views
             FROM produtos
             WHERE loja_id = :loja_id'
        );
        $stmt->execute(['loja_id' => $storeId]);

        $stats = $stmt->fetch() ?: ['total_produtos' => 0, 'total_views' => 0];
        return [
            'total_produtos' => (int) ($stats['total_produtos'] ?? 0),
            'total_views' => (int) ($stats['total_views'] ?? 0),
        ];
    }

    public function nextIdEstimate(): int
    {
        return (int) $this->db->query('SELECT COALESCE(MAX(id), 0) + 1 FROM produtos')->fetchColumn();
    }

    public function findForStore(int $productId, int $storeId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, c.nome AS categoria_nome, l.nome_loja, l.slug AS loja_slug
             FROM produtos p
             INNER JOIN categorias c ON c.id = p.categoria_id
             INNER JOIN lojas l ON l.id = p.loja_id
             WHERE p.id = :id AND p.loja_id = :loja_id
             LIMIT 1'
        );
        $stmt->execute([
            'id' => $productId,
            'loja_id' => $storeId,
        ]);

        $product = $stmt->fetch() ?: null;
        if (!$product) {
            return null;
        }

        return $this->attachImages($product);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO produtos
             (loja_id, categoria_id, nome, slug, descricao, preco_original, preco_promocional, estoque, sku, imagem_principal, status, destaque, views, created_at, updated_at)
             VALUES
             (:loja_id, :categoria_id, :nome, :slug, :descricao, :preco_original, :preco_promocional, :estoque, :sku, :imagem_principal, :status, :destaque, 0, NOW(), NOW())'
        );
        $stmt->execute([
            'loja_id' => $data['loja_id'],
            'categoria_id' => $data['categoria_id'],
            'nome' => $data['nome'],
            'slug' => $data['slug'],
            'descricao' => $data['descricao'],
            'preco_original' => $data['preco_original'],
            'preco_promocional' => $data['preco_promocional'],
            'estoque' => $data['estoque'],
            'sku' => $data['sku'],
            'imagem_principal' => $data['imagem_principal'],
            'status' => $data['status'],
            'destaque' => $data['destaque'],
        ]);
        $id = (int) $this->db->lastInsertId();
        if ($id <= 0) {
            throw new RuntimeException('Falha ao criar produto: a tabela produtos esta sem AUTO_INCREMENT valido.');
        }

        return $id;
    }

    public function update(int $id, array $data): void
    {
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
        $stmt->execute([
            'id' => $id,
            'categoria_id' => $data['categoria_id'],
            'nome' => $data['nome'],
            'slug' => $data['slug'],
            'descricao' => $data['descricao'],
            'preco_original' => $data['preco_original'],
            'preco_promocional' => $data['preco_promocional'],
            'estoque' => $data['estoque'],
            'sku' => $data['sku'],
            'imagem_principal' => $data['imagem_principal'],
            'destaque' => $data['destaque'],
        ]);
    }

    public function incrementViews(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE produtos SET views = views + 1 WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function replaceImages(int $productId, array $filenames): void
    {
        $filenames = array_values(array_unique(array_filter($filenames, static fn ($name) => is_string($name) && $name !== '')));
        if ($filenames === []) {
            return;
        }

        $filenames = array_slice($filenames, 0, self::MAX_IMAGES);

        $this->db->beginTransaction();

        try {
            $updateProduct = $this->db->prepare(
                'UPDATE produtos
                 SET imagem_principal = :imagem_principal,
                     updated_at = NOW()
                 WHERE id = :id'
            );
            $updateProduct->execute([
                'id' => $productId,
                'imagem_principal' => $filenames[0],
            ]);

            $deleteImages = $this->db->prepare('DELETE FROM imagens_produtos WHERE produto_id = :produto_id');
            $deleteImages->execute(['produto_id' => $productId]);

            $insertImage = $this->db->prepare(
                'INSERT INTO imagens_produtos (produto_id, arquivo, ordem, created_at)
                 VALUES (:produto_id, :arquivo, :ordem, NOW())'
            );

            foreach ($filenames as $index => $filename) {
                $insertImage->execute([
                    'produto_id' => $productId,
                    'arquivo' => $filename,
                    'ordem' => $index,
                ]);
            }

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function images(int $productId): array
    {
        $stmt = $this->db->prepare(
            'SELECT arquivo
             FROM imagens_produtos
             WHERE produto_id = :produto_id
             ORDER BY ordem ASC, id ASC'
        );
        $stmt->execute(['produto_id' => $productId]);
        return array_values(array_filter(array_map(static fn ($row) => $row['arquivo'] ?? null, $stmt->fetchAll())));
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

    public function updateSku(int $id, string $sku): void
    {
        $stmt = $this->db->prepare('UPDATE produtos SET sku = :sku, updated_at = NOW() WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'sku' => $sku,
        ]);
    }

    private function attachImages(array $product): array
    {
        $gallery = $this->images((int) $product['id']);
        $primary = $product['imagem_principal'] ?? null;

        if ($primary) {
            array_unshift($gallery, $primary);
        }

        $gallery = array_values(array_unique(array_filter($gallery)));
        $product['imagens'] = array_slice($gallery, 0, self::MAX_IMAGES);
        $product['imagem_principal'] = $product['imagens'][0] ?? $primary;

        return $product;
    }
}
