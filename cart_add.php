<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/products.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('shop.php');
}

require_login();

$productId = (int)($_POST['product_id'] ?? 0);
$qty = (int)($_POST['qty'] ?? 1);
$qty = max(1, $qty);

$stmt = db()->prepare('SELECT id, name, slug, price_sale, price_mrp, image_path FROM products WHERE id = ? LIMIT 1');
$stmt->execute([$productId]);
$p = $stmt->fetch();
if (!$p) {
  redirect('shop.php');
}

$cart = cart_get();
$key = (string)$productId;
if (!isset($cart[$key])) {
  $cart[$key] = [
    'product_id' => (int)$p['id'],
    'name' => $p['name'],
    'slug' => $p['slug'],
    'price' => (float)$p['price_sale'],
    'image_path' => $p['image_path'],
    'qty' => 0,
  ];
}
$cart[$key]['qty'] = (int)$cart[$key]['qty'] + $qty;
cart_set($cart);

redirect('cart.php');
