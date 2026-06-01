<?php
require_once __DIR__ . '/includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('cart.php');
}

require_login();

$cart = cart_get();
$qtys = $_POST['qty'] ?? [];

foreach ($qtys as $pid => $qty) {
  $pid = (int)$pid;
  $key = (string)$pid;
  if (!isset($cart[$key])) {
    continue;
  }
  $q = max(1, (int)$qty);
  $cart[$key]['qty'] = $q;
}

cart_set($cart);
redirect('cart.php');
