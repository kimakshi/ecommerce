<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/helpers.php';

function admin_layout_start(string $active, string $pageTitle, string $pageSubtitle = ''): void
{
    $u = current_user();
    $base = '../';
    ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e($base) ?>assets/css/admin.css">
</head>
<body>
  <div class="admin-shell" data-admin-shell>
    <aside class="admin-sidebar" data-admin-sidebar>
      <div class="admin-brand">
        <img src="<?= e($base) ?>assets/img/logo.png" alt="<?= e(APP_NAME) ?>">
        <div class="t">Admin</div>
      </div>

      <nav class="admin-nav">
        <a href="index.php" class="<?= $active === 'dashboard' ? 'active' : '' ?>">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 13h7V4H4v9zm0 7h7v-5H4v5zm9 0h7V11h-7v9zm0-16v5h7V4h-7z" fill="currentColor"/></svg>
          Dashboard
        </a>
        <a href="orders.php" class="<?= $active === 'orders' ? 'active' : '' ?>">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 4h10v2H7V4zm-2 4h14v12H5V8zm2 2v8h10v-8H7z" fill="currentColor"/></svg>
          Orders
        </a>
        <a href="products.php" class="<?= $active === 'products' ? 'active' : '' ?>">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 4h10l2 4H5l2-4zm-2 6h14v10H5V10zm4 2v6h2v-6H9zm4 0v6h2v-6h-2z" fill="currentColor"/></svg>
          Products
        </a>
        <a href="customers.php" class="<?= $active === 'customers' ? 'active' : '' ?>">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 11c1.7 0 3-1.3 3-3S17.7 5 16 5s-3 1.3-3 3 1.3 3 3 3zm-8 0c1.7 0 3-1.3 3-3S9.7 5 8 5 5 6.3 5 8s1.3 3 3 3zm0 2c-2.7 0-6 1.3-6 4v2h12v-2c0-2.7-3.3-4-6-4zm8 0c-.3 0-.6 0-.9.1 1.8 1 2.9 2.5 2.9 3.9v2h6v-2c0-2.7-3.3-4-6-4z" fill="currentColor"/></svg>
          Customers
        </a>
        <a href="reports.php" class="<?= $active === 'reports' ? 'active' : '' ?>">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 19h16v2H4v-2zm2-2h3V7H6v10zm5 0h3V3h-3v14zm5 0h3V11h-3v6z" fill="currentColor"/></svg>
          Reports
        </a>
        <a href="inquiries.php" class="<?= $active === 'inquiries' ? 'active' : '' ?>">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16v14H5.2L4 19.2V4zm3 4h10v2H7V8zm0 4h10v2H7v-2z" fill="currentColor"/></svg>
          Inquiries
        </a>
        <a href="coupons.php" class="<?= $active === 'coupons' ? 'active' : '' ?>">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12a2 2 0 01-2 2v3H6v-3a2 2 0 010-4V7h12v3a2 2 0 012 2zm-6-3H8v2h6V9zm0 4H8v2h6v-2z" fill="currentColor"/></svg>
          Coupons
        </a>
        <a href="settings.php" class="<?= $active === 'settings' ? 'active' : '' ?>">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19.4 13a7.7 7.7 0 000-2l2-1.5-2-3.5-2.3.9a7.4 7.4 0 00-1.7-1L15 2h-6l-.4 2.9a7.4 7.4 0 00-1.7 1L4.6 5.9l-2 3.5L4.6 11a7.7 7.7 0 000 2l-2 1.5 2 3.5 2.3-.9a7.4 7.4 0 001.7 1L9 22h6l.4-2.9a7.4 7.4 0 001.7-1l2.3.9 2-3.5-2-1.5zM12 15a3 3 0 110-6 3 3 0 010 6z" fill="currentColor"/></svg>
          Settings
        </a>
        <a href="../account.php" class="<?= $active === 'account' ? 'active' : '' ?>">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4 4 0 100-8 4 4 0 000 8zm0 2c-4.4 0-8 2-8 5v1h16v-1c0-3-3.6-5-8-5z" fill="currentColor"/></svg>
          Account
        </a>
        <a href="../logout.php">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l1.4-1.4L8.8 13H20v-2H8.8l2.6-2.6L10 7l-7 7 7 7z" fill="currentColor"/><path d="M4 4h8V2H4a2 2 0 00-2 2v4h2V4zm0 16v-4H2v4a2 2 0 002 2h8v-2H4z" fill="currentColor"/></svg>
          Logout
        </a>
      </nav>
    </aside>

    <main class="admin-main">
      <div class="admin-topbar">
        <div class="left">
          <button class="admin-menu-btn" type="button" aria-label="Menu" data-admin-menu-btn>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          </button>
          <div class="admin-search">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            <input type="text" placeholder="Search orders, products..." data-admin-search>
          </div>
        </div>
        <div class="admin-user">
          <span class="dot" aria-hidden="true"></span>
          <span><?= e($u['name'] ?? 'Admin') ?></span>
        </div>
      </div>

      <div class="admin-content">
        <h1 class="admin-h1"><?= e($pageTitle) ?></h1>
        <?php if ($pageSubtitle !== ''): ?>
          <div class="admin-sub"><?= e($pageSubtitle) ?></div>
        <?php endif; ?>
<?php
}

function admin_layout_end(): void
{
    $base = '../';
    ?>
      </div>
    </main>
  </div>

  <script src="<?= e($base) ?>assets/js/admin.js"></script>
</body>
</html>
<?php
}
