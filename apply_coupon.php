<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/coupons.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('checkout.php');
}

require_login();

$code = strtoupper(trim((string)($_POST['code'] ?? '')));
$next = (string)($_POST['next'] ?? 'checkout.php');

$cart = cart_get();
if (!$cart) {
  redirect('cart.php');
}

$subtotal = 0.0;
foreach ($cart as $item) {
  $subtotal += ((float)$item['price']) * (int)$item['qty'];
}

$coupon = coupon_find_by_code($code);
$val = coupon_validate_for_subtotal($coupon, $subtotal);
if (!$val['ok']) {
  $_SESSION['flash_coupon_error'] = (string)($val['error'] ?? 'Invalid coupon.');
  coupon_clear();
} else {
  coupon_set_code($code);
}

if ($next !== '' && !preg_match('~^https?://~i', $next) && !str_starts_with($next, '//')) {
  redirect($next);
}
redirect('checkout.php');
