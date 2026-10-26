<h1><?= e(t('dash.welcome', ['name' => $user['name']])) ?></h1>
<?php if ($pending_insights): ?>
  <div class="flash flash-info"><?= e(t('dash.pending_insights', ['n' => $pending_insights])) ?></div>
<?php endif; ?>
<section class="card">
  <h2><?= e(t('dash.my_classes')) ?></h2>
  <?php if (!$classes): ?>
    <p class="muted"><?= e(t('dash.no_classes')) ?></p>
  <?php else: ?>
  <div class="grid-cards">
    <?php foreach ($classes as $c): ?>
      <a class="class-card" href="/students?class=<?= e($c['id']) ?>">
        <strong><?= e($c['name']) ?></strong>
        <span class="muted"><?= e($c['subjects']) ?></span>
        <span><?= e(t('dash.n_students', ['n' => $c['students']])) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>
