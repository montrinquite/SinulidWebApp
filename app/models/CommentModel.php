<?php
declare(strict_types=1);

class CommentModel
{
    public function __construct(private PDO $pdo) {}

    public function create(int $postId, int $userId, string $content): int
    {
        $st = $this->pdo->prepare('INSERT INTO comments (post_id, user_id, content) VALUES (:p, :u, :c)');
        $st->execute([':p' => $postId, ':u' => $userId, ':c' => $content]);
        return (int) $this->pdo->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $st = $this->pdo->prepare(
            'SELECT id, post_id, user_id, content, created_at, updated_at FROM comments WHERE id = :id'
        );
        $st->execute([':id' => $id]);
        return $st->fetch() ?: null;
    }

    public function forPosts(array $postIds): array
    {
        $postIds = array_values(array_unique(array_map('intval', $postIds)));
        if ($postIds === []) {
            return [];
        }
        $marks = implode(',', array_fill(0, count($postIds), '?'));
        $st = $this->pdo->prepare(
            "SELECT c.id, c.post_id, c.user_id, c.content, c.created_at, c.updated_at,
                    u.username, u.full_name, u.profile_image
               FROM comments c
               JOIN users u ON u.id = c.user_id
              WHERE c.post_id IN ($marks)
              ORDER BY c.created_at ASC, c.id ASC"
        );
        $st->execute($postIds);

        $grouped = [];
        foreach ($st->fetchAll() as $row) {
            $grouped[(int) $row['post_id']][] = $row;
        }
        return $grouped;
    }

    public function update(int $id, int $userId, string $content): bool
    {
        $st = $this->pdo->prepare('UPDATE comments SET content = :c WHERE id = :id AND user_id = :u');
        $st->execute([':c' => $content, ':id' => $id, ':u' => $userId]);
        return true;
    }

    public function delete(int $id, int $userId): bool
    {
        $st = $this->pdo->prepare('DELETE FROM comments WHERE id = :id AND user_id = :u');
        $st->execute([':id' => $id, ':u' => $userId]);
        return $st->rowCount() > 0;
    }
}
