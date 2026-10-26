<!doctype html>
<html lang="<?= e(current_lang()) ?>" dir="<?= is_rtl() ? 'rtl' : 'ltr' ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($title ?? t('app.name')) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="/assets/app.css">
</head>
<body class="plain">
  <?php foreach (take_flashes() as $f): ?>
    <div class="flash flash-<?= e($f['type']) ?> plain-flash" role="status"><?= e($f['message']) ?></div>
  <?php endforeach; ?>
  <?= $content ?>
</body>
</html>
