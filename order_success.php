<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/db.php';

$orderNumber = $_GET['order'] ?? '';
$order = null;
if ($orderNumber) {
  $stmt = db()->prepare('SELECT * FROM orders WHERE order_number = ? LIMIT 1');
  $stmt->execute([$orderNumber]);
  $order = $stmt->fetch();
}

$title = 'Order Success - ' . APP_NAME;
$breadcrumb = '<a href="index.php">Home</a> &nbsp;›&nbsp; Order Success';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding:18px 0 34px 0">
  <div class="card">
    <div class="section">
      <?php if (!$order): ?>
        <div class="notice">Order not found.</div>
      <?php else: ?>
        <h1 style="margin:0 0 8px 0;font-size:24px">Thank you! Your order is placed.</h1>
        <div class="small-muted">Order Number: <b style="color:var(--text)"><?= e($order['order_number']) ?></b></div>
        <div class="small-muted">Payment Method: <b style="color:var(--text)"><?= e(strtoupper($order['payment_method'])) ?></b></div>
        <div class="small-muted">Total: <b style="color:var(--text)"><?= money_inr($order['total']) ?></b></div>

        <?php if ($order['payment_method'] === 'online'): ?>
          <div class="notice" style="margin-top:12px">Online payment gateway is pending. For now, this order is created as unpaid. You can integrate Razorpay/PayU and mark payment as paid after success.</div>
        <?php else: ?>
          <div class="notice" style="margin-top:12px">Your order will be confirmed shortly. You will pay via COD at delivery.</div>
        <?php endif; ?>

        <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap">
          <a class="btn" href="shop.php">Continue Shopping</a>
          <a class="btn primary" href="account.php">My Account</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
