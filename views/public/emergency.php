<div class="em">
  <header class="em-head">
    <div class="em-top">
      <span class="em-label"><span aria-hidden="true" class="em-cross">+</span> <?= e(t('public.heading')) ?></span>
      <a class="em-lang" href="/lang/<?= is_rtl() ? 'en' : 'ar' ?>"><?= is_rtl() ? 'English' : 'العربية' ?></a>
    </div>
    <div class="em-person">
      <div class="em-avatar" aria-hidden="true"><?= e(mb_substr($p['first_name'], 0, 1)) ?></div>
      <div>
        <div class="em-name"><?= e($p['first_name']) ?></div>
        <?php if ($age !== null): ?><div class="em-sub"><?= e(t('field.age_n', ['n' => $age])) ?></div><?php endif; ?>
      </div>
    </div>
  </header>

  <main class="em-body">
    <?php if ($p['show_blood_type'] && $p['blood_type']): ?>
    <div class="em-card">
      <div class="em-k"><?= e(t('field.blood_type')) ?></div>
      <div class="em-big" dir="ltr"><?= e($p['blood_type']) ?></div>
    </div>
    <?php endif; ?>

    <?php if ($allergies): ?>
    <div class="em-card">
      <div class="em-k"><?= e(t('field.allergies')) ?></div>
      <div class="chips">
        <?php foreach ($allergies as $a): ?>
          <span class="chip chip-<?= e($a['severity']) ?>"><?= e($a['name']) ?> · <?= e(t('severity.' . $a['severity'])) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php foreach (['conditions', 'medications', 'care_notes'] as $f): ?>
      <?php if ($p['show_' . $f] && $p[$f]): ?>
      <div class="em-card">
        <div class="em-k"><?= e(t('field.' . $f)) ?></div>
        <div class="em-v"><?= nl2br(e($p[$f])) ?></div>
      </div>
      <?php endif; ?>
    <?php endforeach; ?>

    <div class="em-actions">
      <?php if ($canCall): ?>
      <form method="post" action="/e/<?= e($token) ?>/call">
        <?= csrf_field() ?>
        <button class="em-btn em-btn-primary" type="submit"><?= e(t('public.call_parent')) ?></button>
      </form>
      <?php endif; ?>
      <a class="em-btn em-btn-outline" href="tel:123"><?= e(t('public.call_ambulance')) ?></a>
    </div>
  </main>

  <footer class="em-foot">
    <p><?= e(t('public.privacy')) ?></p>
    <p><?= e(t('public.logged')) ?></p>
  </footer>
</div>
