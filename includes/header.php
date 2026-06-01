<?php
require_once __DIR__ . '/helpers.php';
$title = $title ?? APP_NAME;
$breadcrumb = $breadcrumb ?? null;
$base = (isset($_SERVER['SCRIPT_NAME']) && str_contains((string)$_SERVER['SCRIPT_NAME'], '/admin/')) ? '../' : '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e($base) ?>assets/css/style.css">
</head>
<body>
  <div class="topbar">Free Shipping on all Orders above ₹499!</div>

  <header class="header">
    <div class="container">
      <div class="header-row">
        <a class="brand" href="<?= e($base) ?>index.php">
          <img class="brand-logo" src="<?= e($base) ?>assets/img/logo.png" alt="<?= e(APP_NAME) ?>">
        </a>

        <nav class="nav">
          <a href="<?= e($base) ?>index.php" class="<?= ($_SERVER['SCRIPT_NAME'] ?? '') === '/index.php' ? 'active' : '' ?>">Home</a>
          <a href="<?= e($base) ?>shop.php" class="<?= ($_SERVER['SCRIPT_NAME'] ?? '') === '/shop.php' ? 'active' : '' ?>">Shop</a>
          <a href="<?= e($base) ?>gift.php" class="<?= ($_SERVER['SCRIPT_NAME'] ?? '') === '/gift.php' ? 'active' : '' ?>">Gift</a>
          <a href="<?= e($base) ?>b2b.php" class="<?= ($_SERVER['SCRIPT_NAME'] ?? '') === '/b2b.php' ? 'active' : '' ?>">B2B</a>
          <a href="<?= e($base) ?>about.php" class="<?= ($_SERVER['SCRIPT_NAME'] ?? '') === '/about.php' ? 'active' : '' ?>">About Us</a>
        </nav>

        <div class="actions">
          <button class="icon-btn menu-btn" type="button" aria-label="Menu" aria-expanded="false" data-mobile-menu-toggle>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          </button>
          <a class="icon-btn" href="<?= e($base) ?>shop.php" aria-label="Search">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          </a>
          <a class="icon-btn" href="<?= e($base) ?><?= current_user() ? 'account.php' : 'login.php' ?>" aria-label="Account">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 21a8 8 0 10-16 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M12 13a4 4 0 100-8 4 4 0 000 8z" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
          </a>
          <a class="icon-btn" href="<?= e($base) ?>cart.php" aria-label="Cart">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6h15l-1.5 9h-12z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M6 6l-1-3H2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M9 21a1 1 0 100-2 1 1 0 000 2zm9 0a1 1 0 100-2 1 1 0 000 2z" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
            <?php if (cart_count() > 0): ?>
              <span class="badge"><?= (int)cart_count() ?></span>
            <?php endif; ?>
          </a>
        </div>
      </div>

      <div class="mobile-nav" data-mobile-menu>
        <a href="<?= e($base) ?>index.php" class="<?= ($_SERVER['SCRIPT_NAME'] ?? '') === '/index.php' ? 'active' : '' ?>">Home</a>
        <a href="<?= e($base) ?>shop.php" class="<?= ($_SERVER['SCRIPT_NAME'] ?? '') === '/shop.php' ? 'active' : '' ?>">Shop</a>
        <a href="<?= e($base) ?>gift.php" class="<?= ($_SERVER['SCRIPT_NAME'] ?? '') === '/gift.php' ? 'active' : '' ?>">Gift</a>
        <a href="<?= e($base) ?>b2b.php" class="<?= ($_SERVER['SCRIPT_NAME'] ?? '') === '/b2b.php' ? 'active' : '' ?>">B2B</a>
        <a href="<?= e($base) ?>about.php" class="<?= ($_SERVER['SCRIPT_NAME'] ?? '') === '/about.php' ? 'active' : '' ?>">About Us</a>
      </div>
    </div>
  </header>

  <?php if ($breadcrumb): ?>
    <div class="breadcrumb">
      <div class="container"><?= $breadcrumb ?></div>
    </div>
  <?php endif; ?>
