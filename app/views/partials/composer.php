<?php /* "Write a post" card. Optional vars: $errors, $content */ ?>
<section class="composer" aria-labelledby="composer-title">
  <h2 class="composer__title" id="composer-title">Write a post</h2>

  <?php require BASE_PATH . '/app/views/partials/errors.php'; ?>

  <form class="composer__form" action="<?= e(url('post/store')) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="composer__upload">
      <label class="composer__upload-label" for="composer-files">
        <span><i class="bi bi-image" aria-hidden="true"></i> <span id="composer-file-name">Insert photo (optional)</span></span>
      </label>
      <input
        class="composer__upload-input"
        type="file"
        id="composer-files"
        name="image"
        accept="image/jpeg,image/png,image/gif,image/webp"
      />
    </div>

    <label class="visually-hidden" for="composer-input">Write your post</label>
    <textarea
      class="composer__textarea"
      id="composer-input"
      name="content"
      rows="5"
      maxlength="2000"
      required
      placeholder="What's on your mind?"
    ><?= e($content ?? '') ?></textarea>

    <button type="submit" class="composer__submit">Post</button>
  </form>
</section>
