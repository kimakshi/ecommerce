<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/mailer.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('index.php');
}

$subject = trim((string)($_POST['subject'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if ($subject === '' || $message === '') {
  $_SESSION['admin_flash_error'] = 'Please enter subject and message.';
  redirect('index.php');
}

$html = '<h2>Admin Dashboard Message</h2>';
$html .= '<p><b>From:</b> ' . e((string)(current_user()['email'] ?? 'admin')) . '</p>';
$html .= '<p><b>Subject:</b> ' . e($subject) . '</p>';
$html .= '<div style="white-space:pre-wrap">' . nl2br(e($message)) . '</div>';

$res = mailer_send('nitishx13@gmail.com', 'Nitish', $subject, $html);
if (!$res['ok']) {
  $_SESSION['admin_flash_error'] = 'Mail failed: ' . (string)($res['error'] ?? 'Unknown error');
  redirect('index.php');
}

$_SESSION['admin_flash_success'] = 'Email sent to nitishx13@gmail.com';
redirect('index.php');
