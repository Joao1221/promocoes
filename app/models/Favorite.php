<?php
class Favorite extends Model
{
    public function toggle(int $userId, int $productId): void
    {
        $stmt = $this->db->prepare('SELECT id FROM favoritos WHERE usuario_id = :usuario_id AND produto_id = :produto_id LIMIT 1');
        $stmt->execute([
            'usuario_id' => $userId,
            'produto_id' => $productId,
        ]);
        $found = $stmt->fetchColumn();

        if ($found) {
            $delete = $this->db->prepare('DELETE FROM favoritos WHERE id = :id');
            $delete->execute(['id' => $found]);
            return;
        }

        $insert = $this->db->prepare('INSERT INTO favoritos (usuario_id, produto_id, created_at) VALUES (:usuario_id, :produto_id, NOW())');
        $insert->execute([
            'usuario_id' => $userId,
            'produto_id' => $productId,
        ]);
    }
}
