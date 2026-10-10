<?php
$isMine    = (int) $post['user_id'] === (int) $currentUser['id'];
$comments  = $post['comments'] ?? [];
$liked     = (int) $post['liked_by_me'] === 1;
$open      = open_post_id() === $pid;
$commentNext = current_next($pid);
$backQuery = '?' . http_build_query($_GET);
$edited    = (strtotime($post['updated_at']) - strtotime($post['created_at'])) > 5;
$profileUrl = url('profile', ['u' => $post['username']]);
?>
<article class="post" id="post-<?= $pid ?>">
  <header class="post__header">
    <a class="post__avatar" href="<?= e($profileUrl) ?>" aria-label="View <?= e($post['full_name']) ?>'s profile">
      <?= avatar_html($post) ?>
    </a>
    <div class="post__meta">
      <p class="post__author">
        <a class="post__author-link" href="<?= e($profileUrl) ?>"><?= e($post['full_name']) ?></a>
        <span class="post__username">@<?= e($post['username']) ?></span>
      </p>
      <time class="post__time" datetime="<?= e($post['created_at']) ?>" title="<?= e($post['created_at']) ?>"><?= e(time_ago($post['created_at'])) ?></time>
      <?php if ($edited): ?><span class="post__edited">&middot; edited</span><?php endif; ?>
    </div>

    <?php if ($isMine): ?>
      <div class="post__owner-tools">
        <a class="post__tool" href="<?= e(url('post/edit', ['id' => $pid])) ?>">
          <i class="bi bi-pencil" aria-hidden="true"></i> Edit
        </a>
        <form action="<?= e(url('post/delete')) ?>" method="post" data-confirm="Delete this post and all of its comments?">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= $pid ?>">
          <input type="hidden" name="next" value="<?= e($backQuery) ?>">
          <button type="submit" class="post__tool post__tool--danger">
            <i class="bi bi-trash" aria-hidden="true"></i> Delete
          </button>
        </form>
      </div>
    <?php endif; ?>
  </header>

  <div class="post__body"><?= nl2br(e($post['content'])) ?></div>

  <?php if (!empty($post['image'])): ?>
    <a href="<?= e(upload_url($post['image'])) ?>" target="_blank" rel="noopener">
      <img class="post__image" src="<?= e(upload_url($post['image'])) ?>"
           alt="Photo attached to <?= e($post['full_name']) ?>'s post" loading="lazy">
    </a>
  <?php endif; ?>

  <footer class="post__actions">
    <button type="button" class="post__action" data-action="like" data-post-id="<?= $pid ?>"
            data-url="<?= e(url('like/toggle')) ?>"
            aria-pressed="<?= $liked ? 'true' : 'false' ?>">
      <i class="bi <?= $liked ? 'bi-heart-fill' : 'bi-heart' ?>" aria-hidden="true"></i>
      <span class="post__action-label">Like</span>
      <span class="post__count"><?= (int) $post['like_count'] ?></span>
    </button>

    <button type="button" class="post__action" data-action="comment"
            aria-expanded="<?= $open ? 'true' : 'false' ?>" aria-controls="post-<?= $pid ?>-comments">
      <i class="bi bi-chat" aria-hidden="true"></i>
      <span class="post__action-label">Comment</span>
      <span class="post__count"><?= count($comments) ?></span>
    </button>

    <button type="button" class="post__action" data-action="share" aria-haspopup="menu" aria-expanded="false">
      <i class="bi bi-send" aria-hidden="true"></i>
      <span class="post__action-label">Share</span>
    </button>

    <div class="share-menu" role="menu" hidden>
      <button type="button" class="share-menu__item" role="menuitem" data-share="copy">Copy link</button>
    </div>
  </footer>

  <section class="post__comments" id="post-<?= $pid ?>-comments" aria-label="Comments"<?= $open ? '' : ' hidden' ?>>
    <?php if ($comments === []): ?>
      <p class="post__no-comments">No comments yet. Be the first to reply.</p>
    <?php else: ?>
      <ul class="post__comment-list">
        <?php foreach ($comments as $c): ?>
          <?php
            $cMine   = (int) $c['user_id'] === (int) $currentUser['id'];
            $cEdited = (strtotime($c['updated_at']) - strtotime($c['created_at'])) > 5;
          ?>
          <li class="post__comment" id="comment-<?= (int) $c['id'] ?>">
            <a class="post__comment-avatar" href="<?= e(url('profile', ['u' => $c['username']])) ?>"
               aria-label="View <?= e($c['full_name']) ?>'s profile"><?= avatar_html($c) ?></a>
            <div class="post__comment-main">
              <div class="post__comment-bubble">
                <p class="post__comment-name">
                  <?= e($c['full_name']) ?> <span class="post__username">@<?= e($c['username']) ?></span>
                </p>
                <p class="post__comment-text"><?= nl2br(e($c['content'])) ?></p>
              </div>
              <div class="post__comment-meta">
                <time class="post__comment-time" datetime="<?= e($c['created_at']) ?>" title="<?= e($c['created_at']) ?>"><?= e(time_ago($c['created_at'])) ?></time>
                <?php if ($cEdited): ?><span class="post__edited">&middot; edited</span><?php endif; ?>
                <?php if ($cMine): ?>
                  <a class="post__tool post__tool--small"
                     href="<?= e(url('comment/edit', ['id' => (int) $c['id'], 'next' => $commentNext])) ?>">Edit</a>
                  <form action="<?= e(url('comment/delete')) ?>" method="post" data-confirm="Delete this comment?">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
                    <input type="hidden" name="next" value="<?= e($commentNext) ?>">
                    <button type="submit" class="post__tool post__tool--small post__tool--danger">Delete</button>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form class="post__comment-form" action="<?= e(url('comment/store')) ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="post_id" value="<?= $pid ?>">
      <input type="hidden" name="next" value="<?= e($commentNext) ?>">
      <label class="visually-hidden" for="comment-input-<?= $pid ?>">Write a comment</label>
      <input class="post__comment-input" type="text" id="comment-input-<?= $pid ?>" name="content"
             maxlength="500" required placeholder="Write a comment..." autocomplete="off">
      <button type="submit" class="post__comment-submit">Send</button>
    </form>
  </section>
</article>
