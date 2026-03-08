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
        $stmt = $this->db->prepare('SELECT * FROM lojas WHERE usuario_id = :usuario_id LIMIT 1');
        $stmt->execute(['usuario_id' => $userId]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO lojas
             (usuario_id, nome_loja, slug, descricao, telefone, whatsapp, instagram, logo, banner, cidade, bairro, endereco, horario_funcionamento, status, destaque, created_at, updated_at)
             VALUES
             (:usuario_id, :nome_loja, :slug, :descricao, :telefone, :whatsapp, :instagram, :logo, :banner, :cidade, :bairro, :endereco, :horario_funcionamento, :status, :destaque, NOW(), NOW())'
        );
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $payload = [
            'id' => $id,
            'nome_loja' => $data['nome_loja'],
            'slug' => $data['slug'],
            'descricao' => $data['descricao'],
            'telefone' => $data['telefone'],
            'whatsapp' => $data['whatsapp'],
            'instagram' => $data['instagram'],
            'logo' => $data['logo'],
            'banner' => $data['banner'],
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
             telefone = :telefone,
             whatsapp = :whatsapp,
             instagram = :instagram,
             logo = COALESCE(:logo, logo),
             banner = COALESCE(:banner, banner),
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
}
