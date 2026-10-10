<main class="auth-container">
  <section class="auth-box">
    <div class="brand-header">
      <img class="inline-img" src="<?= e(asset('images/logo.png')) ?>" alt="" />
      <p>Sinulid</p>
    </div>
    <h1>Create Account</h1>
    <p class="subtitle">Join Sinulid and start sharing.</p>

    <?php require BASE_PATH . '/app/views/partials/flash.php'; ?>
    <?php require BASE_PATH . '/app/views/partials/errors.php'; ?>

    <form id="signupForm" action="<?= e(url('auth/signup')) ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="form-group">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" placeholder="Enter your full name"
               minlength="2" maxlength="100" value="<?= e($old['full_name']) ?>" autocomplete="name" required />
      </div>

      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="letters, numbers, underscore"
               pattern="[A-Za-z0-9_]{3,30}" minlength="3" maxlength="30"
               title="3 to 30 characters: letters, numbers and underscores"
               value="<?= e($old['username']) ?>" autocomplete="username" required />
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="you@example.com"
               maxlength="190" value="<?= e($old['email']) ?>" autocomplete="email" required />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="At least 8 characters, letters + numbers"
               minlength="8" maxlength="72" autocomplete="new-password" required />
      </div>

      <div class="form-group">
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" id="confirmPassword" name="confirm_password" placeholder="Enter password again"
               autocomplete="new-password" required />
      </div>

      <div class="form-group">
        <label for="profile_image">Profile picture (optional)</label>
        <input type="file" id="profile_image" name="profile_image" accept="image/jpeg,image/png,image/gif,image/webp" />
      </div>

      <button type="submit" class="primary-btn">Create Account</button>
    </form>

    <p class="auth-footer">
      Already have an account?
      <a href="<?= e(url('auth/login')) ?>">Log in</a>
    </p>
  </section>
</main>
