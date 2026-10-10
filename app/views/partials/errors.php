<?php /* List of validation errors in $errors */ ?>
<?php if (!empty($errors)): ?>
  <ul class="form-errors" role="alert">
    <?php foreach ($errors as $err): ?>
      <li><?= e($err) ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
