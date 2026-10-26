<?php $p = $profile; ?>
<h1><?= e(t('emergency.edit_title')) ?> — <?= e($student['first_name']) ?></h1>
<section class="card narrow">
  <p class="hint"><?= e(t('emergency.intro')) ?></p>
  <form method="post" action="/students/<?= e($student['id']) ?>/emergency" class="stack">
    <?= csrf_field() ?>

    <label><?= e(t('field.blood_type')) ?>
      <select name="blood_type" dir="ltr">
        <option value="">—</option>
        <?php foreach (BLOOD_TYPES as $b): ?><option <?= $p['blood_type'] === $b ? 'selected' : '' ?>><?= e($b) ?></option><?php endforeach; ?>
      </select>
    </label>

    <label><?= e(t('field.allergies')) ?>
      <textarea name="allergies" rows="3" placeholder="<?= e(t('emergency.allergies_ph')) ?>"><?= e(allergies_to_text($p['allergies'])) ?></textarea>
      <span class="hint"><?= e(t('emergency.allergies_hint')) ?></span>
    </label>

    <label><?= e(t('field.conditions')) ?>
      <textarea name="conditions" rows="2" placeholder="<?= e(t('emergency.conditions_ph')) ?>"><?= e($p['conditions']) ?></textarea>
    </label>
    <label><?= e(t('field.medications')) ?>
      <textarea name="medications" rows="2" placeholder="<?= e(t('emergency.medications_ph')) ?>"><?= e($p['medications']) ?></textarea>
    </label>
    <label><?= e(t('field.care_notes')) ?>
      <textarea name="care_notes" rows="2" placeholder="<?= e(t('emergency.care_ph')) ?>"><?= e($p['care_notes']) ?></textarea>
    </label>

    <fieldset>
      <legend><?= e(t('emergency.visible_title')) ?></legend>
      <p class="hint"><?= e(t('emergency.visible_hint')) ?></p>
      <?php foreach (['show_age' => 'field.age', 'show_blood_type' => 'field.blood_type', 'show_allergies' => 'field.allergies',
                      'show_conditions' => 'field.conditions', 'show_medications' => 'field.medications', 'show_care_notes' => 'field.care_notes'] as $flag => $label): ?>
        <label class="check"><input type="checkbox" name="<?= e($flag) ?>" value="1" <?= $p[$flag] ? 'checked' : '' ?>> <?= e(t($label)) ?></label>
      <?php endforeach; ?>
    </fieldset>

    <label class="check"><input type="checkbox" name="is_enabled" value="1" <?= $p['is_enabled'] ? 'checked' : '' ?>> <?= e(t('emergency.enabled')) ?></label>

    <?php if ($user['role'] === 'parent'): ?>
      <?php if ($p['consent_at']): ?>
        <p class="hint"><?= e(t('emergency.consented_at', ['date' => format_dt($p['consent_at'])])) ?></p>
      <?php else: ?>
        <label class="check consent"><input type="checkbox" name="consent" value="1"> <?= e(t('emergency.consent')) ?></label>
      <?php endif; ?>
    <?php endif; ?>

    <div class="row">
      <button class="btn btn-primary" type="submit"><?= e(t('save')) ?></button>
      <a class="btn btn-ghost" href="/students/<?= e($student['id']) ?>"><?= e(t('cancel')) ?></a>
    </div>
  </form>
</section>
