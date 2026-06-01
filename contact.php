<?php
require_once __DIR__ . '/includes/helpers.php';

$title = 'Contact Us - ' . APP_NAME;
$breadcrumb = '<a href="index.php">Home</a> &nbsp;›&nbsp; Contact Us';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding:18px 0 34px 0">
  <div class="page-title" style="text-align:left"><h1 style="font-size:24px">Contact Us</h1></div>
  <div class="layout" style="grid-template-columns:1fr 1fr">
    <div class="card">
      <div class="section">
        <div class="small-muted">Phone</div>
        <div style="margin-top:4px"><b>+91 8882816805</b></div>
        <div class="small-muted" style="margin-top:12px">Email</div>
        <div style="margin-top:4px"><b>chaatku@gmail.com</b></div>
        <div class="small-muted" style="margin-top:12px">WhatsApp</div>
        <div style="margin-top:4px"><b>+91 8882816805</b></div>
        <div class="small-muted" style="margin-top:6px">Party delivery: order before 6 hours</div>
        <div class="small-muted" style="margin-top:12px">Address</div>
        <div style="margin-top:4px"><b>India</b></div>
      </div>
    </div>

    <div class="card">
      <div class="section">
        <form class="form" method="post" action="#">
          <div class="field"><label>Name</label><input required></div>
          <div class="field"><label>Email</label><input type="email" required></div>
          <div class="field"><label>Message</label><textarea rows="4" required></textarea></div>
          <button class="btn primary" type="button">Send</button>
        </form>
        <div class="small-muted" style="margin-top:10px">Contact form email sending is not configured on localhost.</div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
