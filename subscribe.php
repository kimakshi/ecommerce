<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('index.php');
}

$email = trim((string)($_POST['email'] ?? ''));
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  redirect($_SERVER['HTTP_REFERER'] ?? 'index.php');
}

$stmt = db()->prepare('INSERT IGNORE INTO newsletter_subscribers (email) VALUES (?)');
$stmt->execute([$email]);

redirect($_SERVER['HTTP_REFERER'] ?? 'index.php');
