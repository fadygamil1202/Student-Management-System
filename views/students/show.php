<?php
$isStaff = in_array($user['role'], ['admin', 'teacher'], true);
$age = age_from_dob($student['date_of_birth']);
$allergies = json_decode($profile['allergies'] ?? '[]', true) ?: [];
$publicLink = public_link($profile['public_token']);
?>
<div class="page-head">
  <div>
    <h1><?= e($student['first_name'] . ' ' . $student['last_name']) ?></h1>
    <p class="muted"><?= e($student['class_name'] ?? t('students.no_class')) ?><?= $age !== null ? ' · ' . e(t('field.age_n', ['n' => $age])) : '' ?></p>
  </div>
  <?php if ($user['role'] === 'admin'): ?>
    <a class="btn" href="/students/<?= e($student['id']) ?>/edit"><?= e(t('students.edit')) ?></a>
  <?php endif; ?>
</div>

<div class="cols">
<div class="col-main">

  <!-- Layer 1 -->
  <section class="card">
    <div class="card-head">
      <h2><?= e(t('layer1.title')) ?></h2>
      <span class="badge <?= $profile['is_enabled'] ? 'badge-on' : 'badge-off' ?>"><?= e($profile['is_enabled'] ? t('layer1.public_on') : t('layer1.public_off')) ?></span>
    </div>
    <div class="facts">
      <div><span class="label"><?= e(t('field.blood_type')) ?></span><strong dir="ltr"><?= e($profile['blood_type'] ?? '—') ?></strong></div>
      <div><span class="label"><?= e(t('field.allergies')) ?></span>
        <?php if (!$allergies): ?>—<?php endif; ?>
        <?php foreach ($allergies as $a): ?><span class="chip chip-<?= e($a['severity']) ?>"><?= e($a['name']) ?> · <?= e(t('severity.' . $a['severity'])) ?></span><?php endforeach; ?>
      </div>
      <div><span class="label"><?= e(t('field.conditions')) ?></span><?= nl2br(e($profile['conditions'] ?? '—')) ?></div>
      <div><span class="label"><?= e(t('field.medications')) ?></span><?= nl2br(e($profile['medications'] ?? '—')) ?></div>
    </div>
    <?php if (!$profile['consent_at']): ?>
      <p class="hint warn"><?= e(t('layer1.no_consent')) ?></p>
    <?php endif; ?>
    <?php if ($canEmergency): ?>
    <div class="row wrap">
      <a class="btn btn-primary" href="/students/<?= e($student['id']) ?>/emergency"><?= e(t('emergency.edit_title')) ?></a>
      <a class="btn" href="/students/<?= e($student['id']) ?>/card" target="_blank" rel="noopener"><?= e(t('card.print')) ?></a>
      <a class="btn" href="<?= e($publicLink) ?>" target="_blank" rel="noopener"><?= e(t('layer1.preview')) ?></a>
      <form method="post" action="/students/<?= e($student['id']) ?>/token" class="inline" data-confirm="<?= e(t('layer1.regen_confirm')) ?>">
        <?= csrf_field() ?><button class="btn btn-ghost" type="submit"><?= e(t('layer1.regen')) ?></button>
      </form>
    </div>
    <?php endif; ?>
  </section>

  <!-- Grades -->
  <section class="card" id="grades">
    <h2><?= e(t('grades.title')) ?></h2>
    <?php if ($averages): ?>
    <div class="bars" role="list">
      <?php foreach ($averages as $a): ?>
        <div class="bar-row" role="listitem">
          <span class="bar-label"><?= e($a['subject']) ?></span>
          <span class="bar"><span class="bar-fill" style="width: <?= e(min(100, (float)$a['pct'])) ?>%"></span></span>
          <span class="bar-val" dir="ltr"><?= e($a['pct']) ?>%</span>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!$grades): ?>
      <p class="muted"><?= e(t('grades.none')) ?></p>
    <?php else: ?>
    <div class="table-wrap"><table>
      <thead><tr>
        <th><?= e(t('field.subject')) ?></th><th><?= e(t('field.term')) ?></th><th><?= e(t('field.assessment')) ?></th>
        <th><?= e(t('field.score')) ?></th><?php if ($isStaff): ?><th><?= e(t('role.teacher')) ?></th><th></th><?php endif; ?>
      </tr></thead>
      <tbody>
      <?php $subjectIds = array_map('intval', array_column($subjects, 'id')); ?>
      <?php foreach ($grades as $g): ?>
        <tr>
          <td><?= e($g['subject']) ?></td><td><?= e($g['term']) ?></td><td><?= e($g['assessment']) ?></td>
          <td dir="ltr"><?= e(rtrim(rtrim($g['score'], '0'), '.')) ?> / <?= e(rtrim(rtrim($g['max_score'], '0'), '.')) ?></td>
          <?php if ($isStaff): ?>
          <td><?= e($g['teacher'] ?? '—') ?></td>
          <td>
            <?php if (in_array((int)$g['subject_id'], $subjectIds, true)): ?>
            <form method="post" action="/grades/<?= e($g['id']) ?>/delete" class="inline" data-confirm="<?= e(t('grades.delete_confirm')) ?>">
              <?= csrf_field() ?><button class="link-btn" type="submit"><?= e(t('delete')) ?></button>
            </form>
            <?php endif; ?>
          </td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table></div>
    <?php endif; ?>

    <?php if ($subjects): ?>
    <details class="add-form">
      <summary class="btn"><?= e(t('grades.add')) ?></summary>
      <form method="post" action="/students/<?= e($student['id']) ?>/grades" class="grid-form">
        <?= csrf_field() ?>
        <label><?= e(t('field.subject')) ?>
          <select name="subject_id" required>
            <?php foreach ($subjects as $sub): ?><option value="<?= e($sub['id']) ?>"><?= e($sub['name']) ?></option><?php endforeach; ?>
          </select>
        </label>
        <label><?= e(t('field.term')) ?><input name="term" required placeholder="<?= e(t('grades.term_ph')) ?>"></label>
        <label><?= e(t('field.assessment')) ?><input name="assessment" required placeholder="<?= e(t('grades.assessment_ph')) ?>"></label>
        <label><?= e(t('field.score')) ?><input name="score" type="number" step="0.01" min="0" required dir="ltr"></label>
        <label><?= e(t('field.max_score')) ?><input name="max_score" type="number" step="0.01" min="1" value="100" required dir="ltr"></label>
        <button class="btn btn-primary" type="submit"><?= e(t('save')) ?></button>
      </form>
    </details>
    <?php endif; ?>
  </section>

  <!-- AI insights -->
  <section class="card" id="insights">
    <div class="card-head">
      <h2><?= e(t('ai.title')) ?></h2>
      <?php if ($canInsights): ?>
      <form method="post" action="/students/<?= e($student['id']) ?>/insights" class="inline">
        <?= csrf_field() ?>
        <button class="btn btn-primary" type="submit" <?= (!ai_enabled() || !$grades) ? 'disabled' : '' ?>><?= e(t('ai.generate')) ?></button>
      </form>
      <?php endif; ?>
    </div>
    <?php if ($canInsights): ?>
      <p class="hint"><?= e(ai_enabled() ? t('ai.privacy_note') : t('ai.disabled')) ?></p>
    <?php endif; ?>
    <?php if (!$insights): ?>
      <p class="muted"><?= e(t('ai.none')) ?></p>
    <?php endif; ?>
    <?php foreach ($insights as $i): ?>
      <article class="insight insight-<?= e($i['status']) ?>">
        <div class="insight-meta">
          <span class="badge badge-<?= e($i['status']) ?>"><?= e(t('ai.status_' . $i['status'])) ?></span>
          <span class="muted small"><?= e(format_dt($i['created_at'])) ?><?= $i['reviewer'] ? ' · ' . e(t('ai.reviewed_by', ['name' => $i['reviewer']])) : '' ?></span>
        </div>
        <?php if ($i['status'] === 'draft' && $canInsights): ?>
          <form method="post" action="/insights/<?= e($i['id']) ?>/review" class="stack">
            <?= csrf_field() ?>
            <label class="sr-only" for="ins<?= e($i['id']) ?>"><?= e(t('ai.edit_before')) ?></label>
            <textarea id="ins<?= e($i['id']) ?>" name="content" rows="8"><?= e($i['content']) ?></textarea>
            <span class="hint"><?= e(t('ai.edit_before')) ?></span>
            <div class="row">
              <button class="btn btn-primary" name="decision" value="approved" type="submit"><?= e(t('ai.approve')) ?></button>
              <button class="btn btn-ghost" name="decision" value="rejected" type="submit"><?= e(t('ai.reject')) ?></button>
            </div>
          </form>
        <?php else: ?>
          <div class="insight-body"><?= nl2br(e($i['content'])) ?></div>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>
  </section>
</div>

<aside class="col-side">
  <section class="card">
    <h2><?= e(t('guardians.title')) ?></h2>
    <?php if (!$guardians): ?><p class="muted"><?= e(t('guardians.none')) ?></p><?php endif; ?>
    <ul class="list">
      <?php foreach ($guardians as $g): ?>
        <li>
          <span><strong><?= e($g['name']) ?></strong><?= $g['relation'] ? ' · ' . e($g['relation']) : '' ?><?= $g['is_primary'] ? ' · ' . e(t('guardians.primary')) : '' ?></span>
          <?php if ($isStaff): ?><span class="small muted" dir="ltr"><?= e($g['phone'] ?? '') ?></span><?php endif; ?>
          <?php if ($user['role'] === 'admin'): ?>
          <form method="post" action="/students/<?= e($student['id']) ?>/guardians/<?= e($g['id']) ?>/delete" class="inline">
            <?= csrf_field() ?><button class="link-btn" type="submit"><?= e(t('remove')) ?></button>
          </form>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php if ($user['role'] === 'admin'): ?>
    <form method="post" action="/students/<?= e($student['id']) ?>/guardians" class="stack">
      <?= csrf_field() ?>
      <label><?= e(t('guardians.link_parent')) ?>
        <select name="user_id" required>
          <option value="">—</option>
          <?php foreach ($parents as $p): ?><option value="<?= e($p['id']) ?>"><?= e($p['name'] . ' (' . $p['email'] . ')') ?></option><?php endforeach; ?>
        </select>
      </label>
      <label><?= e(t('guardians.relation')) ?><input name="relation" placeholder="<?= e(t('guardians.relation_ph')) ?>"></label>
      <label class="check"><input type="checkbox" name="is_primary" value="1"> <?= e(t('guardians.primary')) ?></label>
      <button class="btn" type="submit"><?= e(t('add')) ?></button>
      <?php if (!$parents): ?><span class="hint"><?= e(t('guardians.create_first')) ?></span><?php endif; ?>
    </form>
    <?php endif; ?>
  </section>

  <?php if ($isStaff && $student['notes']): ?>
  <section class="card">
    <h2><?= e(t('field.private_notes')) ?></h2>
    <p><?= nl2br(e($student['notes'])) ?></p>
  </section>
  <?php endif; ?>

  <?php if ($canEmergency): ?>
  <section class="card">
    <h2><?= e(t('scan.title')) ?></h2>
    <?php if (!$scans): ?><p class="muted"><?= e(t('dash.no_scans')) ?></p><?php endif; ?>
    <ul class="list">
      <?php foreach ($scans as $s): ?>
        <li><span><?= e(t('scan.' . $s['action'])) ?></span><span class="small muted"><?= e(format_dt($s['created_at'])) ?></span></li>
      <?php endforeach; ?>
    </ul>
  </section>
  <?php endif; ?>
</aside>
</div>
<script src="/assets/app.js"></script>
