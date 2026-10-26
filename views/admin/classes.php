<h1><?= e(t('nav.classes')) ?></h1>
<div class="cols">
<div class="col-main">
  <section class="card">
    <h2><?= e(t('classes.assignments')) ?></h2>
    <p class="hint"><?= e(t('classes.assign_hint')) ?></p>
    <form method="post" action="/admin/assignments" class="grid-form">
      <?= csrf_field() ?>
      <label><?= e(t('role.teacher')) ?>
        <select name="teacher_id" required><option value="">—</option>
          <?php foreach ($teachers as $x): ?><option value="<?= e($x['id']) ?>"><?= e($x['name']) ?></option><?php endforeach; ?>
        </select></label>
      <label><?= e(t('field.class')) ?>
        <select name="class_id" required><option value="">—</option>
          <?php foreach ($classes as $x): ?><option value="<?= e($x['id']) ?>"><?= e($x['name']) ?></option><?php endforeach; ?>
        </select></label>
      <label><?= e(t('field.subject')) ?>
        <select name="subject_id" required><option value="">—</option>
          <?php foreach ($subjects as $x): ?><option value="<?= e($x['id']) ?>"><?= e($x['name']) ?></option><?php endforeach; ?>
        </select></label>
      <button class="btn btn-primary" type="submit"><?= e(t('classes.assign')) ?></button>
    </form>
    <?php if ($assignments): ?>
    <div class="table-wrap"><table>
      <thead><tr><th><?= e(t('field.class')) ?></th><th><?= e(t('field.subject')) ?></th><th><?= e(t('role.teacher')) ?></th><th></th></tr></thead>
      <tbody>
      <?php foreach ($assignments as $a): ?>
        <tr><td><?= e($a['class']) ?></td><td><?= e($a['subject']) ?></td><td><?= e($a['teacher']) ?></td>
          <td><form method="post" action="/admin/assignments/<?= e($a['id']) ?>/delete" class="inline"><?= csrf_field() ?>
            <button class="link-btn" type="submit"><?= e(t('remove')) ?></button></form></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table></div>
    <?php endif; ?>
  </section>
</div>
<aside class="col-side">
  <section class="card">
    <h2><?= e(t('classes.classes')) ?></h2>
    <ul class="list">
      <?php foreach ($classes as $c): ?>
        <li><a href="/students?class=<?= e($c['id']) ?>"><?= e($c['name']) ?></a><span class="muted small"><?= e(t('dash.n_students', ['n' => $c['n']])) ?></span></li>
      <?php endforeach; ?>
    </ul>
    <form method="post" action="/admin/classes" class="row">
      <?= csrf_field() ?>
      <input name="name" required placeholder="<?= e(t('classes.class_ph')) ?>" aria-label="<?= e(t('classes.new_class')) ?>">
      <button class="btn" type="submit"><?= e(t('add')) ?></button>
    </form>
  </section>
  <section class="card">
    <h2><?= e(t('classes.subjects')) ?></h2>
    <ul class="list">
      <?php foreach ($subjects as $s): ?><li><?= e($s['name']) ?></li><?php endforeach; ?>
    </ul>
    <form method="post" action="/admin/subjects" class="row">
      <?= csrf_field() ?>
      <input name="name" required placeholder="<?= e(t('classes.subject_ph')) ?>" aria-label="<?= e(t('classes.new_subject')) ?>">
      <button class="btn" type="submit"><?= e(t('add')) ?></button>
    </form>
  </section>
</aside>
</div>
