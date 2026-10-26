<h1><?= e(t('dash.welcome', ['name' => $user['name']])) ?></h1>
<div class="stats">
  <a class="stat" href="/students"><span class="stat-n"><?= e($stats['students']) ?></span><span><?= e(t('nav.students')) ?></span></a>
  <a class="stat" href="/admin/users"><span class="stat-n"><?= e($stats['teachers']) ?></span><span><?= e(t('role.teacher_pl')) ?></span></a>
  <a class="stat" href="/admin/users"><span class="stat-n"><?= e($stats['parents']) ?></span><span><?= e(t('role.parent_pl')) ?></span></a>
  <div class="stat"><span class="stat-n"><?= e($stats['scans']) ?></span><span><?= e(t('dash.scans_week')) ?></span></div>
</div>

<div class="actions">
  <a class="btn btn-primary" href="/students/new"><?= e(t('students.new')) ?></a>
  <a class="btn" href="/admin/classes"><?= e(t('nav.classes')) ?></a>
  <a class="btn" href="/admin/users"><?= e(t('users.new')) ?></a>
</div>

<section class="card">
  <h2><?= e(t('dash.recent_scans')) ?></h2>
  <?php if (!$recent_scans): ?>
    <p class="muted"><?= e(t('dash.no_scans')) ?></p>
  <?php else: ?>
  <div class="table-wrap"><table>
    <thead><tr><th><?= e(t('field.student')) ?></th><th><?= e(t('scan.action')) ?></th><th><?= e(t('scan.time')) ?></th></tr></thead>
    <tbody>
    <?php foreach ($recent_scans as $s): ?>
      <tr>
        <td><a href="/students/<?= e($s['student_id']) ?>"><?= e($s['first_name'] . ' ' . $s['last_name']) ?></a></td>
        <td><?= e(t('scan.' . $s['action'])) ?></td>
        <td><?= e(format_dt($s['created_at'])) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
  <?php endif; ?>
</section>
