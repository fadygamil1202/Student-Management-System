<h1><?= e(t('nav.users')) ?></h1>
<div class="cols">
<div class="col-main">
  <section class="card">
    <div class="table-wrap"><table>
      <thead><tr><th><?= e(t('field.name')) ?></th><th><?= e(t('field.role')) ?></th><th><?= e(t('field.email')) ?></th><th><?= e(t('field.phone')) ?></th><th></th></tr></thead>
      <tbody>
      <?php foreach ($users as $u): ?>
        <tr class="<?= $u['is_active'] ? '' : 'inactive' ?>">
          <td><?= e($u['name']) ?></td>
          <td><?= e(t('role.' . $u['role'])) ?></td>
          <td dir="ltr"><?= e($u['email']) ?></td>
          <td dir="ltr"><?= e($u['phone'] ?? '') ?></td>
          <td>
            <?php if ((int)$u['id'] !== (int)$me['id']): ?>
            <form method="post" action="/admin/users/<?= e($u['id']) ?>/toggle" class="inline"><?= csrf_field() ?>
              <button class="link-btn" type="submit"><?= e($u['is_active'] ? t('users.deactivate') : t('users.activate')) ?></button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table></div>
  </section>
</div>
<aside class="col-side">
  <section class="card">
    <h2><?= e(t('users.new')) ?></h2>
    <form method="post" action="/admin/users" class="stack">
      <?= csrf_field() ?>
      <label><?= e(t('field.role')) ?>
        <select name="role" required>
          <option value="teacher"><?= e(t('role.teacher')) ?></option>
          <option value="parent"><?= e(t('role.parent')) ?></option>
          <option value="admin"><?= e(t('role.admin')) ?></option>
        </select>
      </label>
      <label><?= e(t('field.name')) ?><input name="name" required></label>
      <label><?= e(t('field.email')) ?><input type="email" name="email" required dir="ltr"></label>
      <label><?= e(t('field.phone')) ?><input type="tel" name="phone" dir="ltr"></label>
      <label><?= e(t('users.temp_password')) ?><input type="text" name="password" minlength="8" required dir="ltr" autocomplete="off"></label>
      <span class="hint"><?= e(t('users.temp_hint')) ?></span>
      <button class="btn btn-primary" type="submit"><?= e(t('users.create')) ?></button>
    </form>
  </section>
</aside>
</div>
