<div class="login-wrap">
  <div class="card login">
    <div class="login-head">
      <h1><?= e(t('app.name')) ?></h1>
      <a class="lang" href="/lang/<?= is_rtl() ? 'en' : 'ar' ?>"><?= is_rtl() ? 'English' : 'العربية' ?></a>
    </div>
    <p class="muted"><?= e(t('login.subtitle')) ?></p>
    <form method="post" action="/login" class="stack">
      <?= csrf_field() ?>
      <label><?= e(t('field.email')) ?>
        <input type="email" name="email" required autocomplete="username" dir="ltr">
      </label>
      <label><?= e(t('field.password')) ?>
        <input type="password" name="password" required autocomplete="current-password" dir="ltr">
      </label>
      <button class="btn btn-primary" type="submit"><?= e(t('login.submit')) ?></button>
    </form>
  </div>
</div>
