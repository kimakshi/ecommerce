<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/_layout.php';

require_admin();

$q = trim((string)($_GET['q'] ?? ''));
$status = trim((string)($_GET['status'] ?? ''));
$from = trim((string)($_GET['from'] ?? ''));
$to = trim((string)($_GET['to'] ?? ''));

$where = [];
$bind = [];
if ($q !== '') {
  $where[] = '(order_number LIKE ? OR customer_name LIKE ? OR customer_email LIKE ? OR customer_phone LIKE ?)';
  $like = '%' . $q . '%';
  $bind[] = $like;
  $bind[] = $like;
  $bind[] = $like;
  $bind[] = $like;
}
if ($status !== '') {
  $where[] = 'status = ?';
  $bind[] = $status;
}
if ($from !== '') {
  $where[] = 'DATE(created_at) >= ?';
  $bind[] = $from;
}
if ($to !== '') {
  $where[] = 'DATE(created_at) <= ?';
  $bind[] = $to;
}

$sql = 'SELECT order_number, status, payment_status, payment_method, total, created_at, customer_name, customer_email, customer_phone FROM orders';
if ($where) {
  $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY created_at DESC LIMIT 200';

$stmt = db()->prepare($sql);
$stmt->execute($bind);
$orders = $stmt->fetchAll();

admin_layout_start('orders', 'Orders', 'Filter, review and update orders');
?>

<div class="admin-actions" style="margin-top:14px">
  <form method="get" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;width:100%">
    <div class="admin-search" style="max-width:420px">
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      <input name="q" value="<?= e($q) ?>" placeholder="Search order #, customer, email, phone">
    </div>

    <select name="status" class="admin-btn" style="border-radius:12px">
      <option value="" <?= $status===''?'selected':'' ?>>All status</option>
      <?php foreach (['pending','confirmed','packed','shipped','delivered','cancelled'] as $s): ?>
        <option value="<?= e($s) ?>" <?= $status===$s?'selected':'' ?>><?= e($s) ?></option>
      <?php endforeach; ?>
    </select>

    <div class="admin-btn" style="border-radius:12px">
      <input type="date" name="from" value="<?= e($from) ?>" style="border:0;background:transparent;color:inherit;outline:none">
    </div>
    <div class="admin-btn" style="border-radius:12px">
      <input type="date" name="to" value="<?= e($to) ?>" style="border:0;background:transparent;color:inherit;outline:none">
    </div>

    <button class="admin-btn primary" type="submit">Apply</button>
    <a class="admin-btn" href="orders.php">Reset</a>
  </form>
</div>

<div class="admin-grid">
  <div class="admin-card" style="grid-column:span 12; overflow:auto">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Order</th>
          <th>Customer</th>
          <th>Status</th>
          <th>Payment</th>
          <th>Total</th>
          <th>Date</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
          <tr data-order-row="<?= e($o['order_number']) ?>">
            <td><b><?= e($o['order_number']) ?></b></td>
            <td>
              <div><?= e($o['customer_name']) ?></div>
              <div style="color:rgba(156,163,175,.9);font-size:12px"><?= e($o['customer_email']) ?></div>
            </td>
            <td><span class="admin-status <?= e($o['status']) ?>" data-order-status-badge><?= e($o['status']) ?></span></td>
            <td>
              <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                <span class="admin-status <?= e($o['payment_status']) ?>" data-order-pay-badge><?= e($o['payment_status']) ?></span>
                <span class="admin-badge"><?= e(strtoupper($o['payment_method'])) ?></span>
              </div>
            </td>
            <td><b><?= money_inr($o['total']) ?></b></td>
            <td><?= e(date('d M Y', strtotime($o['created_at']))) ?></td>
            <td style="width:200px">
              <div class="admin-actions">
                <a class="admin-btn" href="#" data-order-open="<?= e($o['order_number']) ?>">Details</a>
                <a class="admin-btn" href="order_view.php?order=<?= e(urlencode($o['order_number'])) ?>">Open</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="admin-drawer" data-order-drawer>
  <div class="admin-drawer-backdrop" data-order-drawer-close></div>
  <div class="admin-drawer-panel">
    <div class="admin-drawer-head">
      <div>
        <div class="admin-drawer-title" data-order-drawer-title>Order</div>
        <div class="admin-sub" style="margin-top:6px" data-order-drawer-meta></div>
      </div>
      <button class="admin-btn" type="button" data-order-drawer-close>Close</button>
    </div>

    <div class="admin-drawer-body">
      <div class="admin-card">
        <div style="font-weight:700">Customer</div>
        <div class="admin-kv" style="margin-top:10px">
          <b data-order-customer-name></b>
          <div data-order-customer-contact></div>
          <div data-order-customer-address></div>
        </div>
      </div>

      <div class="admin-card">
        <div style="font-weight:700">Items</div>
        <div class="admin-sub" style="margin-top:6px">Order items</div>
        <div class="admin-kv" style="margin-top:12px" data-order-drawer-items></div>
        <div style="height:1px;background:var(--a-line);margin:14px 0"></div>
        <div style="display:flex;justify-content:space-between;align-items:center">
          <div class="label">Total</div>
          <div style="font-weight:800">₹<span data-order-drawer-total></span></div>
        </div>
      </div>

      <div class="admin-card">
        <div style="font-weight:700">Update</div>
        <div class="admin-grid" style="margin-top:10px">
          <div class="admin-field" style="grid-column:span 6">
            <label>Status</label>
            <select data-order-status>
              <?php foreach (['pending','confirmed','packed','shipped','delivered','cancelled'] as $s): ?>
                <option value="<?= e($s) ?>"><?= e($s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="admin-field" style="grid-column:span 6">
            <label>Payment</label>
            <select data-order-payment>
              <?php foreach (['unpaid','paid','refunded'] as $ps): ?>
                <option value="<?= e($ps) ?>"><?= e($ps) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="admin-actions" style="margin-top:12px">
          <button class="admin-btn primary" type="button" data-order-save>Save</button>
        </div>
      </div>
    </div>
  </div>
</div>

<?php admin_layout_end(); ?>
