<?php $s = $student ?? []; $action = $student ? '/students/' . $student['id'] : '/students'; ?>
<h1><?= e($title) ?></h1>
<section class="card narrow">
  <form method="post" action="<?= e($action) ?>" class="stack">
    <?= csrf_field() ?>
    <div class="two">
      <label><?= e(t('field.first_name')) ?><input name="first_name" value="<?= e($s['first_name'] ?? '') ?>" required></label>
      <label><?= e(t('field.last_name')) ?><input name="last_name" value="<?= e($s['last_name'] ?? '') ?>" required></label>
    </div>
    <div class="two">
      <label><?= e(t('field.dob')) ?><input type="date" name="date_of_birth" value="<?= e($s['date_of_birth'] ?? '') ?>"></label>
      <label><?= e(t('field.class')) ?>
        <select name="class_id">
          <option value="">—</option>
          <?php foreach ($classes as $c): ?>
            <option value="<?= e($c['id']) ?>" <?= (int)($s['class_id'] ?? 0) === (int)$c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
    </div>
    <label><?= e(t('field.private_notes')) ?>
      <textarea name="notes" rows="3"><?= e($s['notes'] ?? '') ?></textarea>
      <span class="hint"><?= e(t('students.notes_hint')) ?></span>
    </label>
    <div class="row">
      <button class="btn btn-primary" type="submit"><?= e(t('save')) ?></button>
      <a class="btn btn-ghost" href="<?= $student ? '/students/' . e($student['id']) : '/students' ?>"><?= e(t('cancel')) ?></a>
    </div>
  </form>
  <?php if ($student): ?>
  <form method="post" action="/students/<?= e($student['id']) ?>/delete" class="danger-zone" data-confirm="<?= e(t('students.delete_confirm')) ?>">
    <?= csrf_field() ?>
    <button class="btn btn-danger" type="submit"><?= e(t('students.delete')) ?></button>
  </form>
  <?php endif; ?>
</section>
<script src="/assets/app.js"></script>
