<main class="feed-column">
  <section class="feed" aria-label="Search results">
    <div class="feed__list">
      <?php if ($q === ''): ?>
        <div class="empty-state">
          <i class="bi bi-search" aria-hidden="true"></i>
          <h2>Search Sinulid</h2>
          <p>Type a name, a username or a keyword in the search bar above.</p>
        </div>
      <?php else: ?>
        <h1 class="section-title">Results for &ldquo;<?= e($q) ?>&rdquo;</h1>

        <h2 class="section-title section-title--small">People</h2>
        <?php if ($users === []): ?>
          <p class="muted-note">No people matched.</p>
        <?php else: ?>
          <ul class="user-list">
            <?php foreach ($users as $u): ?>
              <li>
                <a class="user-card" href="<?= e(url('profile', ['u' => $u['username']])) ?>">
                  <span class="user-card__avatar"><?= avatar_html($u) ?></span>
                  <span class="user-card__text">
                    <strong><?= e($u['full_name']) ?></strong>
                    <span class="post__username">@<?= e($u['username']) ?></span>
                    <?php if (!empty($u['bio'])): ?><span class="user-card__bio"><?= e($u['bio']) ?></span><?php endif; ?>
                  </span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

        <h2 class="section-title section-title--small">Posts</h2>
        <?php if ($posts === []): ?>
          <p class="muted-note">No posts matched.</p>
        <?php else: ?>
          <div id="feed-list" class="profile-posts">
            <?php foreach ($posts as $post): ?>
              <?php require BASE_PATH . '/app/views/partials/post.php'; ?>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </section>
</main>
