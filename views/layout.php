<?php $u = current_user(); ?>
<!doctype html>
<html lang="<?= e(current_lang()) ?>" dir="<?= is_rtl() ? 'rtl' : 'ltr' ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? t('app.name')) ?> · <?= e(t('app.name')) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="/assets/app.css">
</head>
<body>
<header class="topbar">
  <a class="brand" href="/dashboard"><?= e(t('app.name')) ?></a>
  <?php if ($u): ?>
  <nav class="nav">
    <a href="/dashboard"><?= e(t('nav.dashboard')) ?></a>
    <?php if (in_array($u['role'], ['admin', 'teacher'], true)): ?>
      <a href="/students"><?= e(t('nav.students')) ?></a>
    <?php endif; ?>
    <?php if ($u['role'] === 'admin'): ?>
      <a href="/admin/classes"><?= e(t('nav.classes')) ?></a>
      <a href="/admin/users"><?= e(t('nav.users')) ?></a>
    <?php endif; ?>
    <a href="/notifications"><?= e(t('nav.notifications')) ?></a>
    <a href="/account"><?= e(t('nav.account')) ?></a>
  </nav>
  <?php endif; ?>
  <div class="topbar-end">
    <a class="lang" href="/lang/<?= is_rtl() ? 'en' : 'ar' ?>"><?= is_rtl() ? 'English' : 'العربية' ?></a>
    <?php if ($u): ?>
    <form method="post" action="/logout" class="inline"><?= csrf_field() ?>
      <button class="btn btn-ghost" type="submit"><?= e(t('nav.logout')) ?></button>
    </form>
    <?php endif; ?>
  </div>
</header>
<main class="container">
  <?php foreach (take_flashes() as $f): ?>
    <div class="flash flash-<?= e($f['type']) ?>" role="status"><?= e($f['message']) ?></div>
  <?php endforeach; ?>
  <?= $content ?>
</main>
</body>
</html>
