<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/_layout.php';

require_admin();

$q = trim((string)($_GET['q'] ?? ''));

$error = null;
$rows = [];
try {
  $where = '';
  $bind = [];
  if ($q !== '') {
    $where = 'WHERE company_name LIKE ? OR contact_person LIKE ? OR email LIKE ? OR phone LIKE ?';
    $like = '%' . $q . '%';
    $bind = [$like, $like, $like, $like];
  }

  $stmt = db()->prepare("SELECT * FROM b2b_inquiries $where ORDER BY created_at DESC LIMIT 300");
  $stmt->execute($bind);
  $rows = $stmt->fetchAll();
} catch (Throwable $e) {
  $error = 'b2b_inquiries table not found. Please run the SQL update (database.sql) to create it.';
}

admin_layout_start('inquiries', 'B2B Inquiries', 'All partnership inquiries from B2B page');
?>

<div class="admin-actions" style="margin-top:14px">
  <form method="get" style="flex:1;max-width:520px">
    <div class="admin-search" style="max-width:none">
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      <input name="q" value="<?= e($q) ?>" placeholder="Search company, person, email, phone">
    </div>
  </form>
</div>

<div class="admin-grid">
  <div class="admin-card" style="grid-column:span 12; overflow:auto">
    <?php if ($error): ?>
      <div class="admin-sub" style="color:#fca5a5"><?= e($error) ?></div>
    <?php else: ?>
      <table class="admin-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Company</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Business</th>
            <th>City</th>
            <th>Products</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= e(date('d M Y H:i', strtotime($r['created_at']))) ?></td>
              <td><b><?= e($r['company_name']) ?></b></td>
              <td><?= e($r['contact_person']) ?></td>
              <td><?= e($r['email']) ?></td>
              <td><?= e($r['phone']) ?></td>
              <td><?= e($r['business_type']) ?></td>
              <td><?= e($r['city']) ?></td>
              <td style="max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e((string)($r['products'] ?? '')) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<?php admin_layout_end(); ?>
