<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/products.php';

$title = APP_NAME;
$breadcrumb = null;
require_once __DIR__ . '/includes/header.php';

$products = products_search(['sort' => 'date_desc']);
$featured = array_slice($products, 0, 6);
?>

<div class="container">
  <section class="hero">
    <div class="hero-item">
      <img src="assets/img/hero1.png" alt="<?= e(APP_NAME) ?> hero 1">
    </div>
    <div class="hero-item">
      <img src="assets/img/hero2.jpg" alt="<?= e(APP_NAME) ?> hero 2">
    </div>
  </section>

  <div style="padding:22px 0 10px 0;text-align:center">
    <div class="small-muted">Premium Flavoured Makhana</div>
    <h1 style="margin:8px 0 0 0;font-size:34px">Flavours that crunch</h1>
    <p class="small-muted" style="max-width:720px;margin:10px auto 0 auto">Indian roasted makhana (fox nuts) in exciting flavours. Fresh batches, fast delivery and premium quality.</p>
    <div style="margin-top:14px;display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
      <a class="btn primary" href="shop.php">Shop Now</a>
      <a class="btn" href="about.php">About Us</a>
    </div>
  </div>

  <div style="padding:14px 0 34px 0">
    <div class="grid-top">
      <div class="small-muted">Popular Products</div>
      <a class="small-muted" href="shop.php">View all</a>
    </div>

    <div class="products products-home">
      <?php foreach ($featured as $p): ?>
        <a class="product-card" href="product.php?slug=<?= e($p['slug']) ?>">
          <div class="product-media">
            <?php if (!empty($p['badge_text'])): ?><div class="pill"><?= e($p['badge_text']) ?></div><?php endif; ?>
            <?php if (!empty($p['image_path']) && file_exists(__DIR__ . '/' . $p['image_path'])): ?>
              <img data-zoom-img src="<?= e($p['image_path']) ?>" alt="<?= e($p['name']) ?>">
            <?php else: ?>
              <img data-zoom-img src="assets/img/demo-product.jpg" alt="<?= e($p['name']) ?>">
            <?php endif; ?>
            <div class="flag">veg</div>
          </div>
          <div class="product-body">
            <div class="product-name"><?= e($p['name']) ?></div>
            <div class="price">
              <span class="sale"><?= money_inr($p['price_sale']) ?></span>
              <span class="mrp"><?= money_inr($p['price_mrp']) ?></span>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
