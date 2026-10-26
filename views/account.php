<h1><?= e(t('nav.account')) ?></h1>
<section class="card narrow">
  <form method="post" action="/account" class="stack">
    <?= csrf_field() ?>
    <label><?= e(t('field.name')) ?><input name="name" value="<?= e($user['name']) ?>" required></label>
    <label><?= e(t('field.email')) ?><input value="<?= e($user['email']) ?>" disabled dir="ltr"></label>
    <label><?= e(t('field.phone')) ?><input name="phone" type="tel" value="<?= e($user['phone']) ?>" dir="ltr">
      <?php if ($user['role'] === 'parent'): ?><span class="hint"><?= e(t('account.phone_hint')) ?></span><?php endif; ?>
    </label>
    <h2><?= e(t('account.change_password')) ?></h2>
    <label><?= e(t('account.current_password')) ?><input type="password" name="current_password" autocomplete="current-password" dir="ltr"></label>
    <label><?= e(t('account.new_password')) ?><input type="password" name="new_password" autocomplete="new-password" minlength="8" dir="ltr"></label>
    <button class="btn btn-primary" type="submit"><?= e(t('save')) ?></button>
  </form>
</section>
