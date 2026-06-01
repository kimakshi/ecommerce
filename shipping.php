<?php
require_once __DIR__ . '/includes/helpers.php';

$title = 'Shipping Policy - ' . APP_NAME;
$breadcrumb = '<a href="index.php">Home</a> &nbsp;›&nbsp; Shipping Policy';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding:18px 0 34px 0">
  <div class="page-title" style="text-align:left"><h1 style="font-size:24px">Shipping Policy</h1></div>
  <div class="card"><div class="section" style="max-width:900px">
    <div class="small-muted">Orders above ₹499 are eligible for free shipping. For demo, shipping fee is ₹49 below ₹499.</div>
  </div></div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
