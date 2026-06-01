<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/coupons.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('checkout.php');
}

$cart = cart_get();
if (!$cart) {
  redirect('cart.php');
}

$name = trim((string)($_POST['name'] ?? ''));
$phone = trim((string)($_POST['phone'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$pincode = trim((string)($_POST['pincode'] ?? ''));
$address1 = trim((string)($_POST['address1'] ?? ''));
$address2 = trim((string)($_POST['address2'] ?? ''));
$city = trim((string)($_POST['city'] ?? ''));
$state = trim((string)($_POST['state'] ?? ''));
$notes = trim((string)($_POST['notes'] ?? ''));
$paymentMethod = ($_POST['payment_method'] ?? 'cod') === 'online' ? 'online' : 'cod';

if ($name === '' || $phone === '' || $email === '' || $pincode === '' || $address1 === '' || $city === '' || $state === '') {
  redirect('checkout.php');
}

$subtotal = 0.0;
foreach ($cart as $item) {
  $subtotal += ((float)$item['price']) * (int)$item['qty'];
}
$shipping = ($subtotal >= 499) ? 0.0 : 49.0;

$couponCode = coupon_current_code();
$coupon = $couponCode !== '' ? coupon_find_by_code($couponCode) : null;
$couponOk = $couponCode === '' ? false : (bool)(coupon_validate_for_subtotal($coupon, $subtotal)['ok'] ?? false);
if ($couponCode !== '' && !$couponOk) {
  coupon_clear();
  $couponCode = '';
  $coupon = null;
}

$discount = $coupon ? coupon_calculate_discount($coupon, $subtotal) : 0.0;
$total = max(0.0, ($subtotal - $discount) + $shipping);

$orderNumber = 'KK' . date('ymd') . strtoupper(substr(bin2hex(random_bytes(6)), 0, 6));

$pdo = db();
$pdo->beginTransaction();
try {
  $user = current_user();
  $userId = $user ? (int)$user['id'] : null;

  $stmt = $pdo->prepare('INSERT INTO orders
    (user_id, order_number, status, payment_method, payment_status, subtotal, shipping_fee, discount, total,
     customer_name, customer_phone, customer_email, address_line1, address_line2, city, state, pincode, notes)
    VALUES
    (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');

  $paymentStatus = $paymentMethod === 'cod' ? 'unpaid' : 'unpaid';

  $stmt->execute([
    $userId,
    $orderNumber,
    'pending',
    $paymentMethod,
    $paymentStatus,
    $subtotal,
    $shipping,
    $discount,
    $total,
    $name,
    $phone,
    $email,
    $address1,
    $address2 ?: null,
    $city,
    $state,
    $pincode,
    $notes ?: null,
  ]);

  $orderId = (int)$pdo->lastInsertId();

  $itemStmt = $pdo->prepare('INSERT INTO order_items
    (order_id, product_id, product_name, unit_price, quantity, line_total)
    VALUES (?,?,?,?,?,?)');

  foreach ($cart as $item) {
    $pid = (int)$item['product_id'];
    $qty = (int)$item['qty'];
    $unit = (float)$item['price'];
    $line = $unit * $qty;

    $itemStmt->execute([$orderId, $pid, $item['name'], $unit, $qty, $line]);
  }

  if ($couponCode !== '' && $coupon) {
    try {
      $updCoupon = $pdo->prepare('UPDATE coupons SET used_count = used_count + 1 WHERE code = ?');
      $updCoupon->execute([$couponCode]);
    } catch (Throwable $e) {
    }
  }

  $pdo->commit();
  cart_set([]);
  coupon_clear();

  redirect('order_success.php?order=' . urlencode($orderNumber));
} catch (Throwable $e) {
  $pdo->rollBack();
  http_response_code(500);
  $title = 'Order Failed - ' . APP_NAME;
  $breadcrumb = '<a href="index.php">Home</a> &nbsp;›&nbsp; Order Failed';
  require_once __DIR__ . '/includes/header.php';
  echo '<div class="container" style="padding:24px 0"><div class="notice">Order failed. Please try again.</div></div>';
  require_once __DIR__ . '/includes/footer.php';
}
