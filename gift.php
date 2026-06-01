<?php
require_once __DIR__ . '/includes/helpers.php';

$title = 'Gift - ' . APP_NAME;
$breadcrumb = '<a href="index.php">Home</a> &nbsp;›&nbsp; Gift';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding:18px 0 34px 0">
  <div class="page-title" style="text-align:left"><h1 style="font-size:24px">Gift</h1></div>
  <div class="card">
    <div class="section" style="max-width:900px">
      <p class="small-muted">Create custom makhana gift boxes for festivals, weddings, corporate gifting and special occasions. Bulk and private label options available.</p>
      <div style="margin-top:12px;display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn primary" href="shop.php?category=combo-packs">View Combo Packs</a>
        <a class="btn" href="contact.php">Enquire Now</a>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
