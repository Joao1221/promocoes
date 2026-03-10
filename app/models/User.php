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
        return (int) $this->db->lastInsertId();
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
}
