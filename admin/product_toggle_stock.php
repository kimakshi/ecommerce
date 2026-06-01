<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('products.php');
}

$id = (int)($_POST['id'] ?? 0);
if (!$id) {
  redirect('products.php');
}

$stmt = db()->prepare('UPDATE products SET in_stock = CASE WHEN in_stock=1 THEN 0 ELSE 1 END, updated_at=NOW() WHERE id=?');
$stmt->execute([$id]);

$next = (string)($_POST['next'] ?? '');
if ($next !== '' && !preg_match('~^https?://~i', $next) && !str_starts_with($next, '//')) {
  redirect($next);
}

redirect('products.php');
