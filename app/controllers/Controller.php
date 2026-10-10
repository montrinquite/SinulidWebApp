<?php
declare(strict_types=1);

class Controller
{
    private ?array $currentUser = null;

    public function __construct(protected PDO $pdo) {}

    protected function user(): ?array
    {
        if ($this->currentUser === null) {
            $id = auth_id();
            if ($id !== null) {
                $user = (new UserModel($this->pdo))->findById($id);
                if ($user === null) {
                    destroy_session();
                    start_secure_session();
                    return null;
                }
                $this->currentUser = $user;
            }
        }
        return $this->currentUser;
    }

    protected function requireLogin(): array
    {
        $user = $this->user();
        if ($user === null) {
            flash('error', 'Please log in to continue.');
            redirect(url('auth/login'));
        }
        return $user;
    }

    protected function requireCsrf(bool $asJson = false): void
    {
        $token = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        if (!csrf_valid($token)) {
            if ($asJson) {
                $this->json(['ok' => false, 'message' => 'Your session expired. Please refresh the page.'], 419);
            }
            $this->showError(419, 'Your session expired or the form was invalid. Please go back, refresh and try again.');
        }
    }

    protected function render(string $view, array $data = [], string $layout = 'app'): void
    {
        $currentUser = $this->user();
        $flashes     = pull_flashes();
        extract($data, EXTR_SKIP);
        require BASE_PATH . '/app/views/layouts/header.php';
        require BASE_PATH . '/app/views/' . $view . '.php';
        require BASE_PATH . '/app/views/layouts/footer.php';
    }

    protected function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function showError(int $code, string $message): void
    {
        http_response_code($code);
        $this->render('errors/error', [
            'pageTitle'    => 'Error',
            'errorCode'    => $code,
            'errorMessage' => $message,
        ], $this->user() ? 'app' : 'auth');
        exit;
    }

    protected function attachComments(array $posts): array
    {
        $byPost = (new CommentModel($this->pdo))->forPosts(array_column($posts, 'id'));
        foreach ($posts as &$post) {
            $post['comments'] = $byPost[(int) $post['id']] ?? [];
        }
        unset($post);
        return $posts;
    }

    protected function postInt(string $key): int
    {
        return isset($_POST[$key]) ? (int) $_POST[$key] : 0;
    }

    protected function getInt(string $key): int
    {
        return isset($_GET[$key]) ? (int) $_GET[$key] : 0;
    }
}
