<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/_layout.php';

require_admin();

$error = null;
$rows = [];
try {
  $rows = db()->query('SELECT * FROM coupons ORDER BY created_at DESC')->fetchAll();
} catch (Throwable $e) {
  $error = 'Coupons table not found. Please run the SQL to create coupons table.';
}

admin_layout_start('coupons', 'Coupons', 'Create and manage coupon codes');
?>

<div class="admin-grid">
  <div class="admin-card" style="grid-column:span 12">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
      <div>
        <div style="font-weight:700">Coupons</div>
        <div class="admin-sub" style="margin-top:6px">Coupons apply on subtotal (before shipping). Discount is saved in orders.</div>
      </div>
      <a class="admin-btn primary" href="coupon_form.php">Add Coupon</a>
    </div>

    <?php if ($error): ?>
      <div class="admin-card" style="margin-top:14px;border-color:rgba(239,68,68,.35)">
        <div class="admin-sub" style="color:#fca5a5"><?= e($error) ?></div>
      </div>
    <?php else: ?>
      <div style="margin-top:12px;overflow:auto">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Code</th>
              <th>Type</th>
              <th>Value</th>
              <th>Min Subtotal</th>
              <th>Max Discount</th>
              <th>Usage</th>
              <th>Dates</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $c): ?>
              <tr>
                <td><b><?= e($c['code']) ?></b></td>
                <td><?= e($c['type']) ?></td>
                <td>
                  <?php if (($c['type'] ?? 'fixed') === 'percent'): ?>
                    <?= e((string)$c['value']) ?>%
                  <?php else: ?>
                    <?= money_inr($c['value']) ?>
                  <?php endif; ?>
                </td>
                <td><?= money_inr($c['min_subtotal']) ?></td>
                <td><?= (float)$c['max_discount'] > 0 ? money_inr($c['max_discount']) : '-' ?></td>
                <td><?= (int)$c['used_count'] ?> / <?= (int)$c['usage_limit'] > 0 ? (int)$c['usage_limit'] : '∞' ?></td>
                <td>
                  <div style="color:rgba(156,163,175,.9);font-size:12px">
                    <?= !empty($c['starts_at']) ? e((string)$c['starts_at']) : '-' ?> → <?= !empty($c['ends_at']) ? e((string)$c['ends_at']) : '-' ?>
                  </div>
                </td>
                <td>
                  <?php if ((int)$c['is_active']): ?>
                    <span class="admin-status delivered">active</span>
                  <?php else: ?>
                    <span class="admin-status cancelled">disabled</span>
                  <?php endif; ?>
                </td>
                <td style="width:220px">
                  <div class="admin-actions">
                    <a class="admin-btn" href="coupon_form.php?id=<?= (int)$c['id'] ?>">Edit</a>
                    <a class="admin-btn danger" href="coupon_delete.php?id=<?= (int)$c['id'] ?>" onclick="return confirm('Delete coupon?')">Delete</a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php admin_layout_end(); ?>
