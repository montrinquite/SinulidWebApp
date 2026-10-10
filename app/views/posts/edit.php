<main class="feed-column">
  <div class="feed__list">
    <section class="form-card" aria-labelledby="edit-post-title">
      <h1 class="form-card__title" id="edit-post-title">Edit post</h1>
      <?php require BASE_PATH . '/app/views/partials/errors.php'; ?>

      <form class="composer__form" action="<?= e(url('post/update')) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">

        <label class="visually-hidden" for="edit-content">Post text</label>
        <textarea class="composer__textarea" id="edit-content" name="content" rows="6" maxlength="2000" required><?= e($post['content']) ?></textarea>

        <?php if (!empty($post['image'])): ?>
          <img class="post__image" src="<?= e(upload_url($post['image'])) ?>" alt="Current photo of this post">
          <label class="check-row">
            <input type="checkbox" name="remove_image" value="1"> Remove this photo
          </label>
        <?php endif; ?>

        <div class="form-field">
          <label for="edit-image"><?= empty($post['image']) ? 'Add a photo (optional)' : 'Replace photo (optional)' ?></label>
          <input type="file" id="edit-image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
        </div>

        <div class="form-actions">
          <a class="btn btn--ghost" href="<?= e(url('feed') . '#post-' . (int) $post['id']) ?>">Cancel</a>
          <button type="submit" class="composer__submit">Save changes</button>
        </div>
      </form>
    </section>
  </div>
</main>
