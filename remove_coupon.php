<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/coupons.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('checkout.php');
}

require_login();

coupon_clear();

$next = (string)($_POST['next'] ?? 'checkout.php');
if ($next !== '' && !preg_match('~^https?://~i', $next) && !str_starts_with($next, '//')) {
  redirect($next);
}
redirect('checkout.php');
