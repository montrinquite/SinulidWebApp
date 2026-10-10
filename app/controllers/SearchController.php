<?php
declare(strict_types=1);

class SearchController extends Controller
{
    public function index(): void
    {
        $me = $this->requireLogin();
        $q  = trim((string) ($_GET['q'] ?? ''));
        if (mb_strlen($q) > 100) {
            $q = mb_substr($q, 0, 100);
        }

        $users = [];
        $posts = [];
        if ($q !== '') {
            $like  = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $q) . '%';
            $users = (new UserModel($this->pdo))->search($like);
            $posts = $this->attachComments((new PostModel($this->pdo))->search($like, (int) $me['id']));
        }

        $this->render('search/index', [
            'pageTitle' => $q === '' ? 'Search' : 'Search: ' . $q,
            'q'         => $q,
            'users'     => $users,
            'posts'     => $posts,
        ]);
    }
}
