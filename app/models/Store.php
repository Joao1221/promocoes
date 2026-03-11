<?php
class Store extends Model
{
    public function featured(int $limit = 8): array
    {
        $stmt = $this->db->prepare(
            'SELECT l.*, u.nome AS lojista_nome
             FROM lojas l
             INNER JOIN usuarios u ON u.id = l.usuario_id
             WHERE l.status = "aprovada"
             ORDER BY l.destaque DESC, l.created_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT l.*, u.nome AS lojista_nome, u.email AS lojista_email
             FROM lojas l
             INNER JOIN usuarios u ON u.id = l.usuario_id
             WHERE l.slug = :slug LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public function byUser(int $userId): ?array
    {
        if ($userId <= 0) {
            return null;
        }

        $stmt = $this->db->prepare('SELECT * FROM lojas WHERE usuario_id = :usuario_id AND usuario_id > 0 LIMIT 1');
        $stmt->execute(['usuario_id' => $userId]);
        return $stmt->fetch() ?: null;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM lojas WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO lojas
             (usuario_id, nome_loja, slug, descricao, documento_tipo, documento_numero, telefone, whatsapp, vende_online, forma_pagamento, tem_delivery, instagram, logo, banner, banner_mobile, cidade, bairro, endereco, horario_funcionamento, status, destaque, created_at, updated_at)
             VALUES
             (:usuario_id, :nome_loja, :slug, :descricao, :documento_tipo, :documento_numero, :telefone, :whatsapp, :vende_online, :forma_pagamento, :tem_delivery, :instagram, :logo, :banner, :banner_mobile, :cidade, :bairro, :endereco, :horario_funcionamento, :status, :destaque, NOW(), NOW())'
        );
        $stmt->execute([
            ...$data,
            'banner_mobile' => $data['banner_mobile'] ?? null,
        ]);
        $id = (int) $this->db->lastInsertId();
        if ($id <= 0) {
            throw new RuntimeException('Falha ao criar loja: a tabela lojas esta sem AUTO_INCREMENT valido.');
        }

        return $id;
    }

    public function update(int $id, array $data): void
    {
        $payload = [
            'id' => $id,
            'nome_loja' => $data['nome_loja'],
            'slug' => $data['slug'],
            'descricao' => $data['descricao'],
            'documento_tipo' => $data['documento_tipo'],
            'documento_numero' => $data['documento_numero'],
            'telefone' => $data['telefone'],
            'whatsapp' => $data['whatsapp'],
            'vende_online' => $data['vende_online'],
            'forma_pagamento' => $data['forma_pagamento'],
            'tem_delivery' => $data['tem_delivery'],
            'instagram' => $data['instagram'],
            'logo' => $data['logo'],
            'banner' => $data['banner'],
            'banner_mobile' => $data['banner_mobile'] ?? null,
            'cidade' => $data['cidade'],
            'bairro' => $data['bairro'],
            'endereco' => $data['endereco'],
            'horario_funcionamento' => $data['horario_funcionamento'],
        ];
        $stmt = $this->db->prepare(
            'UPDATE lojas SET
             nome_loja = :nome_loja,
             slug = :slug,
             descricao = :descricao,
             documento_tipo = :documento_tipo,
             documento_numero = :documento_numero,
             telefone = :telefone,
             whatsapp = :whatsapp,
             vende_online = :vende_online,
             forma_pagamento = :forma_pagamento,
             tem_delivery = :tem_delivery,
             instagram = :instagram,
             logo = COALESCE(:logo, logo),
             banner = COALESCE(:banner, banner),
             banner_mobile = COALESCE(:banner_mobile, banner_mobile),
             cidade = :cidade,
             bairro = :bairro,
             endereco = :endereco,
             horario_funcionamento = :horario_funcionamento,
             updated_at = NOW()
             WHERE id = :id'
        );
        $stmt->execute($payload);
    }

    public function pending(): array
    {
        return $this->db->query('SELECT * FROM lojas WHERE status = "pendente" ORDER BY created_at ASC')->fetchAll();
    }

    public function approve(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE lojas SET status = "aprovada", updated_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function featuredCount(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM lojas WHERE destaque = 1 AND status = "aprovada"')->fetchColumn();
    }

    public function setFeatured(int $id, bool $featured): bool
    {
        $existsStmt = $this->db->prepare('SELECT status FROM lojas WHERE id = :id LIMIT 1');
        $existsStmt->execute(['id' => $id]);
        $store = $existsStmt->fetch();
        if (!$store) {
            return false;
        }

        if ($featured) {
            if (($store['status'] ?? '') !== 'aprovada') {
                return false;
            }
        }

        $stmt = $this->db->prepare('UPDATE lojas SET destaque = :destaque, updated_at = NOW() WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'destaque' => $featured ? 1 : 0,
        ]);
    }

    public function adminMetrics(): array
    {
        $row = $this->db->query(
            'SELECT
                COUNT(*) AS total_lojas,
                SUM(CASE WHEN status = "aprovada" THEN 1 ELSE 0 END) AS lojas_aprovadas,
                SUM(CASE WHEN status = "pendente" THEN 1 ELSE 0 END) AS lojas_pendentes,
                SUM(CASE WHEN status = "rejeitada" THEN 1 ELSE 0 END) AS lojas_rejeitadas,
                SUM(CASE WHEN status = "suspensa" THEN 1 ELSE 0 END) AS lojas_suspensas
             FROM lojas'
        )->fetch() ?: [];

        return [
            'total_lojas' => (int) ($row['total_lojas'] ?? 0),
            'lojas_aprovadas' => (int) ($row['lojas_aprovadas'] ?? 0),
            'lojas_pendentes' => (int) ($row['lojas_pendentes'] ?? 0),
            'lojas_rejeitadas' => (int) ($row['lojas_rejeitadas'] ?? 0),
            'lojas_suspensas' => (int) ($row['lojas_suspensas'] ?? 0),
        ];
    }

    public function adminOverview(): array
    {
        return $this->db->query(
            'SELECT
                l.*,
                u.nome AS dono_nome,
                u.email AS dono_email,
                u.telefone AS dono_telefone,
                COALESCE(ps.total_produtos, 0) AS total_produtos,
                COALESCE(os.total_pedidos, 0) AS total_pedidos,
                COALESCE(os.faturamento, 0) AS faturamento,
                os.ultima_venda_em
             FROM lojas l
             INNER JOIN usuarios u ON u.id = l.usuario_id
             LEFT JOIN (
                SELECT loja_id, COUNT(*) AS total_produtos
                FROM produtos
                GROUP BY loja_id
             ) ps ON ps.loja_id = l.id
             LEFT JOIN (
                SELECT loja_id, COUNT(*) AS total_pedidos, COALESCE(SUM(total), 0) AS faturamento, MAX(created_at) AS ultima_venda_em
                FROM pedidos
                GROUP BY loja_id
             ) os ON os.loja_id = l.id
             ORDER BY l.created_at DESC'
        )->fetchAll();
    }
}
