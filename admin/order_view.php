<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/_layout.php';

require_admin();

$orderNumber = $_GET['order'] ?? '';
$stmt = db()->prepare('SELECT * FROM orders WHERE order_number=? LIMIT 1');
$stmt->execute([$orderNumber]);
$order = $stmt->fetch();

if (!$order) {
  http_response_code(404);
  admin_layout_start('orders', 'Order Not Found', '');
  echo '<div class="admin-grid" style="margin-top:14px"><div class="admin-card" style="grid-column:span 12"><div class="admin-sub" style="color:#fca5a5">Order not found.</div></div></div>';
  admin_layout_end();
  exit;
}

$itemsStmt = db()->prepare('SELECT * FROM order_items WHERE order_id=?');
$itemsStmt->execute([(int)$order['id']]);
$items = $itemsStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $status = (string)($_POST['status'] ?? $order['status']);
  $paymentStatus = (string)($_POST['payment_status'] ?? $order['payment_status']);

  $upd = db()->prepare('UPDATE orders SET status=?, payment_status=? WHERE id=?');
  $upd->execute([$status, $paymentStatus, (int)$order['id']]);
  redirect('order_view.php?order=' . urlencode($orderNumber));
}

$title = 'View Order - ' . APP_NAME;
admin_layout_start('orders', 'Order ' . (string)$orderNumber, 'View and update this order');
?>

<div class="admin-grid">
  <div class="admin-card" style="grid-column:span 8; overflow:auto">
    <div style="font-weight:700">Items</div>
    <div style="margin-top:10px">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Unit</th>
            <th>Qty</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $it): ?>
            <tr>
              <td><?= e($it['product_name']) ?></td>
              <td><?= money_inr($it['unit_price']) ?></td>
              <td><?= (int)$it['quantity'] ?></td>
              <td><b><?= money_inr($it['line_total']) ?></b></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="admin-card" style="grid-column:span 4">
    <div style="font-weight:700">Update</div>
    <form method="post" style="margin-top:12px" class="admin-grid">
      <div class="admin-field" style="grid-column:span 12">
        <label>Status</label>
        <select name="status">
          <?php foreach (['pending','confirmed','packed','shipped','delivered','cancelled'] as $s): ?>
            <option value="<?= e($s) ?>" <?= $order['status']===$s?'selected':'' ?>><?= e($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="admin-field" style="grid-column:span 12">
        <label>Payment Status</label>
        <select name="payment_status">
          <?php foreach (['unpaid','paid','refunded'] as $ps): ?>
            <option value="<?= e($ps) ?>" <?= $order['payment_status']===$ps?'selected':'' ?>><?= e($ps) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="admin-actions" style="grid-column:span 12">
        <button class="admin-btn primary" type="submit">Save</button>
        <a class="admin-btn" href="orders.php">Back</a>
      </div>
    </form>

    <div style="height:1px;background:var(--a-line);margin:14px 0"></div>
    <div class="admin-kv">
      <div style="display:flex;justify-content:space-between"><span class="label">Subtotal</span><b><?= money_inr($order['subtotal']) ?></b></div>
      <div style="display:flex;justify-content:space-between"><span class="label">Discount</span><b><?= money_inr($order['discount']) ?></b></div>
      <div style="display:flex;justify-content:space-between"><span class="label">Shipping</span><b><?= money_inr($order['shipping_fee']) ?></b></div>
      <div style="display:flex;justify-content:space-between"><span class="label">Total</span><b><?= money_inr($order['total']) ?></b></div>
    </div>
  </div>

  <div class="admin-card" style="grid-column:span 12">
    <div style="font-weight:700">Customer</div>
    <div class="admin-kv" style="margin-top:10px">
      <b><?= e($order['customer_name']) ?></b>
      <div><?= e($order['customer_phone']) ?> · <?= e($order['customer_email']) ?></div>
      <div><?= e($order['address_line1']) ?><?= $order['address_line2'] ? ', ' . e($order['address_line2']) : '' ?>, <?= e($order['city']) ?>, <?= e($order['state']) ?> - <?= e($order['pincode']) ?></div>
      <?php if (!empty($order['notes'])): ?><div><b>Notes:</b> <?= e($order['notes']) ?></div><?php endif; ?>
    </div>
  </div>
</div>

<?php admin_layout_end(); ?>
