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
            'INSERT INTO usuarios (nome, email, senha, telefone, role, status, created_at, updated_at)
             VALUES (:nome, :email, :senha, :telefone, :role, :status, NOW(), NOW())'
        );
        $stmt->execute([
            'nome' => $data['nome'],
            'email' => $data['email'],
            'senha' => password_hash($data['senha'], PASSWORD_DEFAULT),
            'telefone' => $data['telefone'] ?? null,
            'role' => $data['role'],
            'status' => $data['status'] ?? 'ativo',
        ]);
        return (int) $this->db->lastInsertId();
    }
}
