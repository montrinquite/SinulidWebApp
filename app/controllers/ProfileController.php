<?php
declare(strict_types=1);

class ProfileController extends Controller
{
    public function show(): void
    {
        $me    = $this->requireLogin();
        $users = new UserModel($this->pdo);

        $username = trim((string) ($_GET['u'] ?? ''));
        $profile  = $username === '' ? $me : $users->findByUsername($username);
        if ($profile === null) {
            $this->showError(404, 'That user does not exist.');
        }

        $posts = (new PostModel($this->pdo))->byUser((int) $profile['id'], (int) $me['id']);
        $this->render('profile/show', [
            'pageTitle'   => $profile['full_name'],
            'profileUser' => $profile,
            'isOwner'     => (int) $profile['id'] === (int) $me['id'],
            'posts'       => $this->attachComments($posts),
        ]);
    }

    public function edit(): void
    {
        $me = $this->requireLogin();
        $this->render('profile/edit', [
            'pageTitle' => 'Edit profile',
            'errors'    => [],
            'form'      => ['full_name' => $me['full_name'], 'bio' => (string) $me['bio']],
        ]);
    }

    public function update(): void
    {
        $me = $this->requireLogin();
        $this->requireCsrf();

        $fullName = trim((string) ($_POST['full_name'] ?? ''));
        $bio      = trim((string) ($_POST['bio'] ?? ''));
        $errors   = [];

        $nameLen = mb_strlen($fullName);
        if ($nameLen < 2 || $nameLen > 100) {
            $errors[] = 'Full name must be 2 to 100 characters.';
        }
        if (mb_strlen($bio) > 300) {
            $errors[] = 'Bio can be at most 300 characters.';
        }

        $setImage = false;
        $newImage = $me['profile_image'];
        if ($errors === []) {
            [$uploaded, $uploadError] = handle_image_upload($_FILES['profile_image'] ?? null, 'avatars', MAX_AVATAR_BYTES);
            if ($uploadError !== null) {
                $errors[] = $uploadError;
            } elseif ($uploaded !== null) {
                $setImage = true;
                $newImage = $uploaded;
            } elseif (!empty($_POST['remove_image'])) {
                $setImage = true;
                $newImage = null;
            }
        }

        if ($errors !== []) {
            $this->render('profile/edit', [
                'pageTitle' => 'Edit profile',
                'errors'    => $errors,
                'form'      => ['full_name' => $fullName, 'bio' => $bio],
            ]);
            return;
        }

        (new UserModel($this->pdo))->updateProfile(
            (int) $me['id'],
            $fullName,
            $bio === '' ? null : $bio,
            $setImage,
            $newImage
        );
        if ($setImage) {
            delete_upload($me['profile_image']);
        }
        flash('success', 'Profile updated.');
        redirect(url('profile'));
    }
}
