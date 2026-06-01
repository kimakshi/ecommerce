<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/_layout.php';

require_admin();

$q = trim((string)($_GET['q'] ?? ''));

$where = '';
$bind = [];
if ($q !== '') {
  $where = 'WHERE name LIKE ? OR email LIKE ? OR phone LIKE ?';
  $like = '%' . $q . '%';
  $bind = [$like, $like, $like];
}

$stmt = db()->prepare("SELECT id, name, email, phone, role, created_at FROM users $where ORDER BY created_at DESC");
$stmt->execute($bind);
$users = $stmt->fetchAll();

admin_layout_start('customers', 'Customers', 'Users and customers list');
?>

<div class="admin-actions" style="margin-top:14px">
  <form method="get" style="flex:1;max-width:520px">
    <div class="admin-search" style="max-width:none">
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      <input name="q" value="<?= e($q) ?>" placeholder="Search name, email, phone">
    </div>
  </form>
</div>

<div class="admin-grid">
  <div class="admin-card" style="grid-column:span 12; overflow:auto">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Role</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
          <tr>
            <td><b><?= e($u['name']) ?></b></td>
            <td><?= e($u['email']) ?></td>
            <td><?= e((string)($u['phone'] ?? '')) ?></td>
            <td><?= e($u['role']) ?></td>
            <td><?= e(date('d M Y', strtotime($u['created_at']))) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php admin_layout_end(); ?>
