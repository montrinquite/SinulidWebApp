<?php
$isAuth = ($layout ?? 'app') === 'auth';
?>
<?php if (!$isAuth): ?>
<?php if ($currentUser): ?>
      <aside class="profile-panel" id="profile-panel" aria-labelledby="profile-title" hidden>
        <div class="profile-panel__picture" id="user-avatar"><?= avatar_html($currentUser) ?></div>

        <h2 class="profile-panel__title" id="profile-title">Profile</h2>

        <dl class="profile-panel__details">
          <div class="profile-panel__row">
            <dt>Full Name</dt>
            <dd id="user-full-name"><?= e($currentUser['full_name']) ?></dd>
          </div>
          <div class="profile-panel__row">
            <dt>Username</dt>
            <dd id="user-username">@<?= e($currentUser['username']) ?></dd>
          </div>
          <div class="profile-panel__row">
            <dt>Email Address</dt>
            <dd id="user-email"><?= e($currentUser['email']) ?></dd>
          </div>
          <div class="profile-panel__row">
            <dt>Member Since</dt>
            <dd id="user-since"><?= e(date('F j, Y', strtotime($currentUser['created_at']))) ?></dd>
          </div>
        </dl>

        <a class="profile-panel__link" href="<?= e(url('profile')) ?>">
          <i class="bi bi-person-circle" aria-hidden="true"></i> View my profile
        </a>

        <div class="settings-group">
          <button type="button" class="settings-group__toggle" id="settings-toggle"
                  aria-expanded="false" aria-controls="settings-menu">
            <span>Settings</span>
            <span class="settings-group__chevron" aria-hidden="true"></span>
          </button>
          <ul class="settings-menu" id="settings-menu" hidden>
            <li>
              <a class="settings-menu__item" id="edit-profile-button" href="<?= e(url('profile/edit')) ?>">
                <i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Profile
              </a>
            </li>
            <li>
              <form action="<?= e(url('auth/logout')) ?>" method="post">
                <?= csrf_field() ?>
                <button type="submit" class="settings-menu__item">
                  <i class="bi bi-box-arrow-right" aria-hidden="true"></i> Log out
                </button>
              </form>
            </li>
          </ul>
        </div>

        <div class="profile-panel__settings">
          <h3 class="profile-panel__settings-title">Appearance</h3>
          <button type="button" class="profile-panel__theme-button" id="theme-toggle" aria-pressed="false">
            Dark Mode
          </button>
        </div>
      </aside>
<?php endif; ?>
    </div>
<?php endif; ?>
  </body>
</html>
