<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';

require_admin();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
  exit;
}

$orderNumber = (string)($_POST['order'] ?? '');
$status = (string)($_POST['status'] ?? '');
$paymentStatus = (string)($_POST['payment_status'] ?? '');

$allowedStatus = ['pending','confirmed','packed','shipped','delivered','cancelled'];
$allowedPay = ['unpaid','paid','refunded'];

if ($orderNumber === '' || !in_array($status, $allowedStatus, true) || !in_array($paymentStatus, $allowedPay, true)) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Invalid input']);
  exit;
}

$upd = db()->prepare('UPDATE orders SET status=?, payment_status=? WHERE order_number=?');
$upd->execute([$status, $paymentStatus, $orderNumber]);

echo json_encode(['ok' => true]);
