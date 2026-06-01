<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/_layout.php';

require_admin();

admin_layout_start('settings', 'Settings', 'Store and shipping settings (basic)');
?>

<div class="admin-grid">
  <div class="admin-card" style="grid-column:span 12">
    <div style="font-weight:700">Settings</div>
    <div class="admin-sub" style="margin-top:6px">This project currently stores settings in code. If you want, I can add a settings table and make these editable.</div>

    <div class="admin-grid" style="margin-top:14px">
      <div class="admin-card" style="grid-column:span 6">
        <div class="label">Store Name</div>
        <div class="value" style="font-size:18px;margin-top:6px"><?= e(APP_NAME) ?></div>
      </div>
      <div class="admin-card" style="grid-column:span 6">
        <div class="label">Shipping Rule</div>
        <div class="value" style="font-size:18px;margin-top:6px">Free shipping above ₹499</div>
      </div>
    </div>
  </div>
</div>

<?php admin_layout_end(); ?>
