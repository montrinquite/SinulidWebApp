<?php
declare(strict_types=1);

class PostModel
{
    private const SELECT = "
        SELECT p.id, p.user_id, p.content, p.image, p.created_at, p.updated_at,
               u.username, u.full_name, u.profile_image,
               (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id)    AS like_count,
               (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) AS comment_count,
               EXISTS(SELECT 1 FROM likes l2
                       WHERE l2.post_id = p.id AND l2.user_id = :viewer) AS liked_by_me
          FROM posts p
          JOIN users u ON u.id = p.user_id";

    private const ORDER = ' ORDER BY p.created_at DESC, p.id DESC LIMIT :lim';

    public function __construct(private PDO $pdo) {}

    public function create(int $userId, string $content, ?string $image): int
    {
        $st = $this->pdo->prepare('INSERT INTO posts (user_id, content, image) VALUES (:u, :c, :i)');
        $st->execute([':u' => $userId, ':c' => $content, ':i' => $image]);
        return (int) $this->pdo->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $st = $this->pdo->prepare('SELECT id, user_id, content, image, created_at, updated_at FROM posts WHERE id = :id');
        $st->execute([':id' => $id]);
        return $st->fetch() ?: null;
    }

    public function exists(int $id): bool
    {
        $st = $this->pdo->prepare('SELECT 1 FROM posts WHERE id = :id');
        $st->execute([':id' => $id]);
        return (bool) $st->fetchColumn();
    }

    public function feed(int $viewerId, int $limit = 100): array
    {
        $st = $this->pdo->prepare(self::SELECT . self::ORDER);
        $st->bindValue(':viewer', $viewerId, PDO::PARAM_INT);
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll();
    }

    public function byUser(int $authorId, int $viewerId, int $limit = 100): array
    {
        $st = $this->pdo->prepare(self::SELECT . ' WHERE p.user_id = :author' . self::ORDER);
        $st->bindValue(':viewer', $viewerId, PDO::PARAM_INT);
        $st->bindValue(':author', $authorId, PDO::PARAM_INT);
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll();
    }

    public function search(string $like, int $viewerId, int $limit = 50): array
    {
        $st = $this->pdo->prepare(self::SELECT . " WHERE p.content LIKE :kw ESCAPE '!'" . self::ORDER);
        $st->bindValue(':viewer', $viewerId, PDO::PARAM_INT);
        $st->bindValue(':kw', $like);
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll();
    }

    public function update(int $id, int $userId, string $content, bool $setImage, ?string $image): bool
    {
        if ($setImage) {
            $st = $this->pdo->prepare(
                'UPDATE posts SET content = :c, image = :i WHERE id = :id AND user_id = :u'
            );
            $st->execute([':c' => $content, ':i' => $image, ':id' => $id, ':u' => $userId]);
        } else {
            $st = $this->pdo->prepare('UPDATE posts SET content = :c WHERE id = :id AND user_id = :u');
            $st->execute([':c' => $content, ':id' => $id, ':u' => $userId]);
        }
        return true;
    }

    public function delete(int $id, int $userId): bool
    {
        $st = $this->pdo->prepare('DELETE FROM posts WHERE id = :id AND user_id = :u');
        $st->execute([':id' => $id, ':u' => $userId]);
        return $st->rowCount() > 0;
    }
}
