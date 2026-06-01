<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/db.php';

require_login();

$user = current_user();
$stmt = db()->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([(int)$user['id']]);
$orders = $stmt->fetchAll();

$title = 'My Account - ' . APP_NAME;
$breadcrumb = '<a href="index.php">Home</a> &nbsp;›&nbsp; My Account';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding:18px 0 34px 0">
  <div class="page-title" style="text-align:left"><h1 style="font-size:24px">My Account</h1></div>

  <div class="layout" style="grid-template-columns:280px 1fr">
    <aside class="sidebar" style="border-right:none">
      <div class="card"><div class="section">
        <div class="small-muted">Signed in as</div>
        <div style="margin-top:6px"><b><?= e($user['name']) ?></b></div>
        <div class="small-muted"><?= e($user['email']) ?></div>
        <div style="margin-top:12px;display:grid;gap:10px">
          <?php if (is_admin()): ?>
            <a class="btn primary" href="admin/index.php">Admin Panel</a>
          <?php endif; ?>
          <a class="btn" href="logout.php">Logout</a>
        </div>
      </div></div>
    </aside>

    <main>
      <div class="card"><div class="section">
        <h3>Orders</h3>

        <?php if (!$orders): ?>
          <div class="notice">No orders yet.</div>
        <?php else: ?>
          <table class="table">
            <thead>
              <tr>
                <th>Order #</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $o): ?>
                <tr>
                  <td><?= e($o['order_number']) ?></td>
                  <td><?= e(date('d M Y', strtotime($o['created_at']))) ?></td>
                  <td><?= e($o['status']) ?></td>
                  <td><b><?= money_inr($o['total']) ?></b></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div></div>
    </main>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
