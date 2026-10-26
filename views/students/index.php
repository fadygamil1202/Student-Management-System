<div class="page-head">
  <h1><?= e(t('nav.students')) ?></h1>
  <?php if ($user['role'] === 'admin'): ?>
    <a class="btn btn-primary" href="/students/new"><?= e(t('students.new')) ?></a>
  <?php endif; ?>
</div>
<form method="get" action="/students" class="filters">
  <input type="search" name="q" value="<?= e($search) ?>" placeholder="<?= e(t('students.search')) ?>" aria-label="<?= e(t('students.search')) ?>">
  <select name="class" aria-label="<?= e(t('field.class')) ?>">
    <option value=""><?= e(t('students.all_classes')) ?></option>
    <?php foreach ($classes as $c): ?>
      <option value="<?= e($c['id']) ?>" <?= $classId === (int)$c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
    <?php endforeach; ?>
  </select>
  <button class="btn" type="submit"><?= e(t('filter')) ?></button>
</form>
<section class="card">
  <?php if (!$students): ?>
    <p class="muted"><?= e(t('students.none')) ?></p>
  <?php else: ?>
  <div class="table-wrap"><table>
    <thead><tr><th><?= e(t('field.name')) ?></th><th><?= e(t('field.class')) ?></th><th><?= e(t('field.age')) ?></th></tr></thead>
    <tbody>
    <?php foreach ($students as $s): ?>
      <tr>
        <td><a href="/students/<?= e($s['id']) ?>"><?= e($s['first_name'] . ' ' . $s['last_name']) ?></a></td>
        <td><?= e($s['class_name'] ?? '—') ?></td>
        <td><?= e(age_from_dob($s['date_of_birth']) ?? '—') ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
  <?php endif; ?>
</section>
