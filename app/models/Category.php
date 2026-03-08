<?php
class Category extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM categorias ORDER BY nome')->fetchAll();
    }

    public function create(string $nome, string $icone): void
    {
        $stmt = $this->db->prepare('INSERT INTO categorias (nome, slug, icone, created_at, updated_at) VALUES (:nome, :slug, :icone, NOW(), NOW())');
        $stmt->execute([
            'nome' => $nome,
            'slug' => slugify($nome),
            'icone' => $icone,
        ]);
    }
}
