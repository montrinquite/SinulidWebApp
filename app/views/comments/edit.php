<main class="feed-column">
  <div class="feed__list">
    <section class="form-card" aria-labelledby="edit-comment-title">
      <h1 class="form-card__title" id="edit-comment-title">Edit comment</h1>
      <?php require BASE_PATH . '/app/views/partials/errors.php'; ?>

      <form class="composer__form" action="<?= e(url('comment/update')) ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $comment['id'] ?>">
        <input type="hidden" name="next" value="<?= e($next) ?>">

        <label class="visually-hidden" for="edit-comment">Comment text</label>
        <textarea class="composer__textarea" id="edit-comment" name="content" rows="4" maxlength="500" required><?= e($comment['content']) ?></textarea>

        <div class="form-actions">
          <a class="btn btn--ghost" href="<?= e(safe_next($next, url('feed') . '#post-' . (int) $comment['post_id'])) ?>">Cancel</a>
          <button type="submit" class="composer__submit">Save changes</button>
        </div>
      </form>
    </section>
  </div>
</main>
