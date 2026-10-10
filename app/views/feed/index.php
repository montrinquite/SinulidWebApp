<aside class="composer-column" aria-label="Write a post">
  <?php require BASE_PATH . '/app/views/partials/composer.php'; ?>
</aside>

<main class="feed-column">
  <section class="feed" aria-label="Feed">
    <div class="feed__list" id="feed-list">
      <?php if ($posts === []): ?>
        <div class="empty-state">
          <i class="bi bi-chat-square-text" aria-hidden="true"></i>
          <h2>Nothing here yet</h2>
          <p>Be the first to share something. Your post will show up here.</p>
        </div>
      <?php else: ?>
        <?php foreach ($posts as $post): ?>
          <?php require BASE_PATH . '/app/views/partials/post.php'; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</main>
