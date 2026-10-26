<h1><?= e(t('dash.welcome', ['name' => $user['name']])) ?></h1>
<?php if ($unread): ?>
  <a class="flash flash-info block" href="/notifications"><?= e(t('dash.unread', ['n' => $unread])) ?></a>
<?php endif; ?>
<section class="card">
  <h2><?= e(t('dash.my_children')) ?></h2>
  <?php if (!$children): ?>
    <p class="muted"><?= e(t('dash.no_children')) ?></p>
  <?php else: ?>
  <div class="grid-cards">
    <?php foreach ($children as $c): ?>
      <div class="class-card">
        <strong><?= e($c['first_name'] . ' ' . $c['last_name']) ?></strong>
        <span class="muted"><?= e($c['class_name'] ?? '—') ?></span>
        <div class="row">
          <a class="btn btn-primary" href="/students/<?= e($c['id']) ?>"><?= e(t('dash.view_profile')) ?></a>
          <a class="btn" href="/students/<?= e($c['id']) ?>/emergency"><?= e(t('emergency.edit_title')) ?></a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>
