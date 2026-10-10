<?php /* Success / error banners saved with flash() */ ?>
<?php foreach (($flashes ?? []) as $f): ?>
  <div class="flash flash--<?= $f['type'] === 'error' ? 'error' : 'success' ?>"
       role="<?= $f['type'] === 'error' ? 'alert' : 'status' ?>"><?= e($f['message']) ?></div>
<?php endforeach; ?>
