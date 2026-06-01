<?php
require_once __DIR__ . '/includes/helpers.php';

require_login();

$id = (int)($_GET['id'] ?? 0);
$cart = cart_get();
unset($cart[(string)$id]);
cart_set($cart);
redirect('cart.php');
