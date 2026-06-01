<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/_layout.php';

require_admin();

$counts = [
  'products' => (int)db()->query('SELECT COUNT(*) AS c FROM products')->fetch()['c'],
  'orders' => (int)db()->query('SELECT COUNT(*) AS c FROM orders')->fetch()['c'],
  'users' => (int)db()->query('SELECT COUNT(*) AS c FROM users')->fetch()['c'],
  'out_of_stock' => (int)db()->query('SELECT COUNT(*) AS c FROM products WHERE in_stock=0')->fetch()['c'],
];

$recentInquiriesErr = null;
$recentInquiries = [];
try {
  $recentInquiries = db()->query('SELECT company_name, contact_person, email, phone, business_type, created_at FROM b2b_inquiries ORDER BY created_at DESC LIMIT 6')->fetchAll();
} catch (Throwable $e) {
  $recentInquiriesErr = 'b2b_inquiries table not found.';
}

$todaySales = (float)db()->query("SELECT COALESCE(SUM(total),0) AS s FROM orders WHERE DATE(created_at)=CURDATE()")->fetch()['s'];
$todayOrders = (int)db()->query("SELECT COUNT(*) AS c FROM orders WHERE DATE(created_at)=CURDATE()")->fetch()['c'];

$recentOrders = db()->query('SELECT order_number, status, payment_status, payment_method, total, created_at FROM orders ORDER BY created_at DESC LIMIT 8')->fetchAll();

$salesRows = db()->query("SELECT DATE(created_at) AS d, COALESCE(SUM(total),0) AS s FROM orders WHERE created_at >= (CURDATE() - INTERVAL 6 DAY) GROUP BY DATE(created_at) ORDER BY d ASC")->fetchAll();
$salesMap = [];
foreach ($salesRows as $r) {
  $salesMap[(string)$r['d']] = (float)$r['s'];
}
$series = [];
for ($i = 6; $i >= 0; $i--) {
  $d = date('Y-m-d', strtotime('-' . $i . ' day'));
  $series[] = ['d' => $d, 'v' => (float)($salesMap[$d] ?? 0.0)];
}

$flashOk = $_SESSION['admin_flash_success'] ?? null;
$flashErr = $_SESSION['admin_flash_error'] ?? null;
unset($_SESSION['admin_flash_success'], $_SESSION['admin_flash_error']);

admin_layout_start('dashboard', 'Dashboard', 'Overview of sales, orders and inventory');
?>

<?php if ($flashOk): ?>
  <div class="admin-card" style="margin-top:14px;border-color:rgba(34,197,94,.35)">
    <div class="admin-sub" style="color:#86efac"><?= e((string)$flashOk) ?></div>
  </div>
<?php endif; ?>
<?php if ($flashErr): ?>
  <div class="admin-card" style="margin-top:14px;border-color:rgba(239,68,68,.35)">
    <div class="admin-sub" style="color:#fca5a5"><?= e((string)$flashErr) ?></div>
  </div>
<?php endif; ?>

<div class="admin-actions" style="margin-top:14px">
  <a class="admin-btn primary" href="products.php">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 4h10l2 4H5l2-4zm-2 6h14v10H5V10zm4 2v6h2v-6H9zm4 0v6h2v-6h-2z" fill="currentColor"/></svg>
    Products
  </a>
  <a class="admin-btn" href="orders.php">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 4h10v2H7V4zm-2 4h14v12H5V8zm2 2v8h10v-8H7z" fill="currentColor"/></svg>
    Orders
  </a>
</div>

<div class="admin-grid">
  <div class="admin-card" style="grid-column:span 3">
    <div class="admin-kpi">
      <div>
        <div class="label">Sales Today</div>
        <div class="value"><?= money_inr($todaySales) ?></div>
        <div class="admin-badge ok" style="margin-top:10px">Orders: <?= (int)$todayOrders ?></div>
      </div>
    </div>
  </div>

  <div class="admin-card" style="grid-column:span 3">
    <div class="admin-kpi">
      <div>
        <div class="label">Total Orders</div>
        <div class="value"><?= (int)$counts['orders'] ?></div>
        <div class="admin-badge" style="margin-top:10px">All time</div>
      </div>
    </div>
  </div>

  <div class="admin-card" style="grid-column:span 3">
    <div class="admin-kpi">
      <div>
        <div class="label">Products</div>
        <div class="value"><?= (int)$counts['products'] ?></div>
        <?php if ((int)$counts['out_of_stock'] > 0): ?>
          <div class="admin-badge warn" style="margin-top:10px">Stock Alerts: <?= (int)$counts['out_of_stock'] ?></div>
        <?php else: ?>
          <div class="admin-badge ok" style="margin-top:10px">All in stock</div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="admin-card" style="grid-column:span 3">
    <div class="admin-kpi">
      <div>
        <div class="label">Customers</div>
        <div class="value"><?= (int)$counts['users'] ?></div>
        <div class="admin-badge" style="margin-top:10px">Users</div>
      </div>
    </div>
  </div>

  <div class="admin-card" style="grid-column:span 7">
    <canvas class="admin-chart" data-admin-chart data-series='<?= e(json_encode($series)) ?>'></canvas>
  </div>

  <div class="admin-card" style="grid-column:span 5">
    <div class="admin-kpi">
      <div>
        <div class="label">Quick Actions</div>
        <div class="admin-actions" style="margin-top:12px">
          <a class="admin-btn" href="product_form.php">Add Product</a>
          <a class="admin-btn" href="orders.php">View Orders</a>
        </div>
      </div>
    </div>
  </div>

  <div class="admin-card" style="grid-column:span 5">
    <div style="font-weight:700">Send Mail</div>
    <div class="admin-sub" style="margin-top:6px">Sends to nitishx13@gmail.com</div>
    <form method="post" action="send_mail.php" class="admin-grid" style="margin-top:12px">
      <div class="admin-field" style="grid-column:span 12">
        <label>Subject</label>
        <input name="subject" required style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none">
      </div>
      <div class="admin-field" style="grid-column:span 12">
        <label>Message</label>
        <textarea name="message" rows="5" required style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none"></textarea>
      </div>
      <div class="admin-actions" style="grid-column:span 12">
        <button class="admin-btn primary" type="submit">Send</button>
      </div>
    </form>
  </div>

  <div class="admin-card" style="grid-column:span 7; overflow:auto">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px">
      <div>
        <div style="font-weight:700">Recent Inquiries</div>
        <div class="admin-sub" style="margin-top:4px">Latest B2B partnership inquiries</div>
      </div>
      <a class="admin-btn" href="inquiries.php">Open Inquiries</a>
    </div>

    <?php if ($recentInquiriesErr): ?>
      <div class="admin-sub" style="margin-top:12px;color:#fca5a5"><?= e($recentInquiriesErr) ?></div>
    <?php else: ?>
      <div style="margin-top:12px">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Company</th>
              <th>Person</th>
              <th>Phone</th>
              <th>Business</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recentInquiries as $r): ?>
              <tr>
                <td><?= e(date('d M Y', strtotime($r['created_at']))) ?></td>
                <td><b><?= e($r['company_name']) ?></b></td>
                <td><?= e($r['contact_person']) ?></td>
                <td><?= e($r['phone']) ?></td>
                <td><?= e($r['business_type']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <div class="admin-card" style="grid-column:span 12">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px">
      <div>
        <div style="font-weight:700">Recent Orders</div>
        <div class="admin-sub" style="margin-top:4px">Latest 8 orders</div>
      </div>
      <a class="admin-btn" href="orders.php">Open Orders</a>
    </div>
    <div style="margin-top:12px;overflow:auto">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Order</th>
            <th>Date</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recentOrders as $o): ?>
            <tr>
              <td><b><?= e($o['order_number']) ?></b></td>
              <td><?= e(date('d M Y H:i', strtotime($o['created_at']))) ?></td>
              <td><?= e($o['status']) ?></td>
              <td><?= e(strtoupper($o['payment_method'])) ?> / <?= e($o['payment_status']) ?></td>
              <td><b><?= money_inr($o['total']) ?></b></td>
              <td style="width:120px"><a class="admin-btn" href="order_view.php?order=<?= e(urlencode($o['order_number'])) ?>">View</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php admin_layout_end(); ?>
