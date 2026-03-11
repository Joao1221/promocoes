<?php
class HomeFeaturedProduct extends Model
{
    public function currentSlots(int $limit = 12): array
    {
        $limit = max(1, min(24, $limit));

        try {
            $stmt = $this->db->prepare(
                'SELECT
                    h.posicao,
                    p.id AS produto_id,
                    p.nome AS produto_nome,
                    p.slug AS produto_slug,
                    p.preco_original,
                    p.preco_promocional,
                    l.nome_loja,
                    l.slug AS loja_slug
                 FROM home_produtos_destaque h
                 INNER JOIN produtos p ON p.id = h.produto_id
                 INNER JOIN lojas l ON l.id = p.loja_id
                 WHERE h.posicao BETWEEN 1 AND :limit
                 ORDER BY h.posicao ASC'
            );
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException) {
            return [];
        }
    }

    public function selectableProducts(int $limit = 500): array
    {
        $limit = max(1, min(2000, $limit));

        try {
            $stmt = $this->db->prepare(
                'SELECT
                    p.id,
                    p.nome,
                    p.slug,
                    p.preco_original,
                    p.preco_promocional,
                    p.views,
                    p.destaque,
                    l.nome_loja,
                    l.slug AS loja_slug
                 FROM produtos p
                 INNER JOIN lojas l ON l.id = p.loja_id
                 WHERE p.status = "aprovado" AND l.status = "aprovada"
                 ORDER BY p.destaque DESC, p.views DESC, p.created_at DESC
                 LIMIT :limit'
            );
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException) {
            return [];
        }
    }

    public function saveSlots(array $slots, int $limit = 12): void
    {
        $limit = max(1, min(24, $limit));
        $normalized = [];

        for ($position = 1; $position <= $limit; $position++) {
            $productId = (int) ($slots[$position] ?? 0);
            if ($productId > 0) {
                $normalized[$position] = $productId;
            }
        }

        $productIds = array_values($normalized);
        if (count($productIds) !== count(array_unique($productIds))) {
            throw new RuntimeException('Nao repita o mesmo produto em mais de uma posicao.');
        }

        if ($productIds !== []) {
            $placeholders = implode(', ', array_fill(0, count($productIds), '?'));
            $checkStmt = $this->db->prepare(
                "SELECT COUNT(*)
                 FROM produtos p
                 INNER JOIN lojas l ON l.id = p.loja_id
                 WHERE p.id IN ($placeholders) AND p.status = 'aprovado' AND l.status = 'aprovada'"
            );
            $checkStmt->execute($productIds);
            $validCount = (int) $checkStmt->fetchColumn();

            if ($validCount !== count($productIds)) {
                throw new RuntimeException('Selecione apenas produtos aprovados de lojas aprovadas.');
            }
        }

        try {
            $this->db->beginTransaction();

            $clearStmt = $this->db->prepare('DELETE FROM home_produtos_destaque WHERE posicao BETWEEN 1 AND :limit');
            $clearStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $clearStmt->execute();

            if ($normalized !== []) {
                $insertStmt = $this->db->prepare(
                    'INSERT INTO home_produtos_destaque (posicao, produto_id, created_at, updated_at)
                     VALUES (:posicao, :produto_id, NOW(), NOW())'
                );
                foreach ($normalized as $position => $productId) {
                    $insertStmt->execute([
                        'posicao' => $position,
                        'produto_id' => $productId,
                    ]);
                }
            }

            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            if ($e instanceof PDOException) {
                throw new RuntimeException('Tabela de destaque da Home nao encontrada. Rode o SQL de atualizacao do banco.');
            }

            throw $e;
        }
    }
}
