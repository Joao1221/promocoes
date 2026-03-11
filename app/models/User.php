<?php
class User extends Model
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO usuarios (nome, email, senha, telefone, endereco_entrega, role, status, created_at, updated_at)
             VALUES (:nome, :email, :senha, :telefone, :endereco_entrega, :role, :status, NOW(), NOW())'
        );
        $stmt->execute([
            'nome' => $data['nome'],
            'email' => $data['email'],
            'senha' => password_hash($data['senha'], PASSWORD_DEFAULT),
            'telefone' => $data['telefone'] ?? null,
            'endereco_entrega' => $data['endereco_entrega'] ?? null,
            'role' => $data['role'],
            'status' => $data['status'] ?? 'ativo',
        ]);
        $id = (int) $this->db->lastInsertId();
        if ($id <= 0) {
            throw new RuntimeException('Falha ao criar usuario: a tabela usuarios esta sem AUTO_INCREMENT valido.');
        }

        return $id;
    }

    public function updateCheckoutProfile(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE usuarios
             SET nome = :nome,
                 telefone = :telefone,
                 endereco_entrega = :endereco_entrega,
                 updated_at = NOW()
             WHERE id = :id
             LIMIT 1'
        );

        $stmt->execute([
            'id' => $id,
            'nome' => $data['nome'],
            'telefone' => $data['telefone'],
            'endereco_entrega' => $data['endereco_entrega'],
        ]);
    }

    public function adminMetrics(): array
    {
        $base = $this->db->query(
            'SELECT
                COUNT(*) AS total_usuarios,
                SUM(CASE WHEN role = "consumidor" THEN 1 ELSE 0 END) AS total_consumidores,
                SUM(CASE WHEN role = "lojista" THEN 1 ELSE 0 END) AS total_lojistas_role,
                SUM(CASE WHEN role = "admin" THEN 1 ELSE 0 END) AS total_admins
             FROM usuarios'
        )->fetch() ?: [];

        $totalVendedores = (int) $this->db->query('SELECT COUNT(DISTINCT usuario_id) FROM lojas')->fetchColumn();
        $totalCompradores = (int) $this->db->query('SELECT COUNT(DISTINCT usuario_id) FROM pedidos')->fetchColumn();
        $totalCompraEVenda = (int) $this->db->query(
            'SELECT COUNT(*) FROM (
                SELECT DISTINCT l.usuario_id
                FROM lojas l
                INNER JOIN pedidos p ON p.usuario_id = l.usuario_id
             ) cv'
        )->fetchColumn();

        return [
            'total_usuarios' => (int) ($base['total_usuarios'] ?? 0),
            'total_consumidores' => (int) ($base['total_consumidores'] ?? 0),
            'total_lojistas_role' => (int) ($base['total_lojistas_role'] ?? 0),
            'total_admins' => (int) ($base['total_admins'] ?? 0),
            'total_vendedores' => $totalVendedores,
            'total_compradores' => $totalCompradores,
            'total_compra_e_venda' => $totalCompraEVenda,
        ];
    }

    public function adminProfiles(int $limit = 200): array
    {
        $limit = max(1, min(1000, $limit));
        $stmt = $this->db->prepare(
            'SELECT
                u.id,
                u.nome,
                u.email,
                u.telefone,
                u.role,
                u.status,
                u.created_at,
                COALESCE(sl.total_lojas, 0) AS total_lojas,
                COALESCE(pc.total_pedidos, 0) AS total_pedidos_compra,
                COALESCE(pc.total_gasto, 0) AS total_gasto,
                pc.ultima_compra_em
             FROM usuarios u
             LEFT JOIN (
                SELECT usuario_id, COUNT(*) AS total_lojas
                FROM lojas
                GROUP BY usuario_id
             ) sl ON sl.usuario_id = u.id
             LEFT JOIN (
                SELECT usuario_id, COUNT(*) AS total_pedidos, COALESCE(SUM(total), 0) AS total_gasto, MAX(created_at) AS ultima_compra_em
                FROM pedidos
                GROUP BY usuario_id
             ) pc ON pc.usuario_id = u.id
             ORDER BY u.created_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
