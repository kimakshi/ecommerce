<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/mailer.php';
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('b2b.php');
}

$companyName = trim((string)($_POST['company_name'] ?? ''));
$contactPerson = trim((string)($_POST['contact_person'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$phone = trim((string)($_POST['phone'] ?? ''));
$businessType = trim((string)($_POST['business_type'] ?? ''));
$gst = trim((string)($_POST['gst'] ?? ''));

$address = trim((string)($_POST['address'] ?? ''));
$city = trim((string)($_POST['city'] ?? ''));
$state = trim((string)($_POST['state'] ?? ''));
$pincode = trim((string)($_POST['pincode'] ?? ''));

$requirements = trim((string)($_POST['requirements'] ?? ''));
$products = $_POST['products'] ?? [];
if (!is_array($products)) {
  $products = [];
}

if ($companyName === '' || $contactPerson === '' || $email === '' || $phone === '' || $businessType === '' || $address === '' || $city === '' || $state === '' || $pincode === '' || $requirements === '') {
  $_SESSION['flash_b2b_error'] = 'Please fill all required fields.';
  redirect('b2b.php');
}

$prodText = $products ? implode(', ', array_map('strval', $products)) : '-';

try {
  $stmt = db()->prepare('INSERT INTO b2b_inquiries
    (company_name, contact_person, email, phone, business_type, gst, address, city, state, pincode, requirements, products)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)');
  $stmt->execute([
    $companyName,
    $contactPerson,
    $email,
    $phone,
    $businessType,
    $gst !== '' ? $gst : null,
    $address,
    $city,
    $state,
    $pincode,
    $requirements,
    $prodText !== '-' ? $prodText : null,
  ]);
} catch (Throwable $e) {
}

$subject = 'B2B Inquiry - ' . $companyName;
$html = '<h2>B2B Partnership Inquiry</h2>';
$html .= '<table cellpadding="8" cellspacing="0" border="1" style="border-collapse:collapse;border:1px solid #e5e7eb">';
$html .= '<tr><td><b>Company</b></td><td>' . e($companyName) . '</td></tr>';
$html .= '<tr><td><b>Contact Person</b></td><td>' . e($contactPerson) . '</td></tr>';
$html .= '<tr><td><b>Email</b></td><td>' . e($email) . '</td></tr>';
$html .= '<tr><td><b>Phone</b></td><td>' . e($phone) . '</td></tr>';
$html .= '<tr><td><b>Business Type</b></td><td>' . e($businessType) . '</td></tr>';
$html .= '<tr><td><b>GST</b></td><td>' . e($gst !== '' ? $gst : '-') . '</td></tr>';
$html .= '<tr><td><b>Address</b></td><td>' . e($address) . ', ' . e($city) . ', ' . e($state) . ' - ' . e($pincode) . '</td></tr>';
$html .= '<tr><td><b>Products</b></td><td>' . e($prodText) . '</td></tr>';
$html .= '<tr><td><b>Requirements</b></td><td>' . nl2br(e($requirements)) . '</td></tr>';
$html .= '</table>';

$res = mailer_send('nitishx13@gmail.com', 'Admin', $subject, $html, $email, $contactPerson);
if (!$res['ok']) {
  $_SESSION['flash_b2b_error'] = 'Mail failed: ' . (string)($res['error'] ?? 'Unknown error');
  redirect('b2b.php');
}

$_SESSION['flash_b2b_success'] = 'Inquiry submitted successfully. We will contact you soon.';
redirect('b2b.php');
