<?php if ($currentUser): ?>
<main class="feed-column">
  <div class="feed__list">
    <div class="empty-state">
      <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
      <h1><?= (int) $errorCode ?></h1>
      <p><?= e($errorMessage) ?></p>
      <p><a class="btn" href="<?= e(url('feed')) ?>">Go back home</a></p>
    </div>
  </div>
</main>
<?php else: ?>
<main class="auth-container">
  <section class="auth-box">
    <h1><?= (int) $errorCode ?></h1>
    <p class="subtitle"><?= e($errorMessage) ?></p>
    <p class="auth-footer"><a href="<?= e(url('auth/login')) ?>">Back to log in</a></p>
  </section>
</main>
<?php endif; ?>
