<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';

require_admin();

header('Content-Type: application/json; charset=utf-8');

$orderNumber = (string)($_GET['order'] ?? '');
if ($orderNumber === '') {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Missing order number']);
  exit;
}

$stmt = db()->prepare('SELECT * FROM orders WHERE order_number=? LIMIT 1');
$stmt->execute([$orderNumber]);
$order = $stmt->fetch();
if (!$order) {
  http_response_code(404);
  echo json_encode(['ok' => false, 'error' => 'Order not found']);
  exit;
}

$itemsStmt = db()->prepare('SELECT * FROM order_items WHERE order_id=?');
$itemsStmt->execute([(int)$order['id']]);
$items = $itemsStmt->fetchAll();

echo json_encode([
  'ok' => true,
  'order' => $order,
  'items' => $items,
]);
