<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';

require_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id) {
  $stmt = db()->prepare('DELETE FROM products WHERE id=?');
  $stmt->execute([$id]);
}

redirect('products.php');
