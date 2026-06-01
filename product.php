<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/products.php';

$slug = $_GET['slug'] ?? '';
$product = $slug ? product_find_by_slug($slug) : null;
if (!$product) {
  http_response_code(404);
  $title = 'Product not found - ' . APP_NAME;
  $breadcrumb = '<a href="index.php">Home</a> &nbsp;›&nbsp; <a href="shop.php">Shop</a> &nbsp;›&nbsp; Not found';
  require_once __DIR__ . '/includes/header.php';
  echo '<div class="container" style="padding:24px 0"><div class="notice">Product not found.</div></div>';
  require_once __DIR__ . '/includes/footer.php';
  exit;
}

$title = $product['name'] . ' - ' . APP_NAME;
$breadcrumb = '<a href="index.php">Home</a> &nbsp;›&nbsp; <a href="shop.php">Shop</a> &nbsp;›&nbsp; ' . e($product['name']);
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding:18px 0 34px 0">
  <div class="card">
    <div class="section" style="display:grid;grid-template-columns:1fr 1.1fr;gap:20px;align-items:start">
      <div class="product-media product-media-detail" style="border-radius:18px;overflow:hidden">
        <?php if (!empty($product['badge_text'])): ?><div class="pill"><?= e($product['badge_text']) ?></div><?php endif; ?>
        <?php if (!empty($product['image_path']) && file_exists(__DIR__ . '/' . $product['image_path'])): ?>
          <img data-zoom-img src="<?= e($product['image_path']) ?>" alt="<?= e($product['name']) ?>">
        <?php else: ?>
          <img data-zoom-img src="assets/img/demo-product.jpg" alt="<?= e($product['name']) ?>">
        <?php endif; ?>
        <div class="flag">veg</div>
      </div>

      <div>
        <div class="small-muted"><?= e($product['category_name'] ?? '') ?></div>
        <h1 style="margin:6px 0 0 0;font-size:26px"><?= e($product['name']) ?></h1>
        <div class="price" style="justify-content:flex-start">
          <span class="sale" style="font-size:20px"><?= money_inr($product['price_sale']) ?></span>
          <span class="mrp"><?= money_inr($product['price_mrp']) ?></span>
        </div>
        <p class="small-muted" style="margin-top:10px"><?= e($product['short_desc'] ?? 'Premium quality roasted makhana sourced and packed in India.') ?></p>

        <form method="post" action="cart_add.php" style="margin-top:14px;display:grid;gap:10px;max-width:340px">
          <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
          <div class="field">
            <label>Quantity</label>
            <input type="number" name="qty" min="1" value="1" required>
          </div>
          <button class="btn primary block" type="submit">Add to cart</button>
          <a class="btn block" href="cart.php">Go to cart</a>
        </form>

        <div style="margin-top:18px">
          <h3 style="margin:0 0 8px 0;font-size:13px;letter-spacing:.06em;text-transform:uppercase">Description</h3>
          <div class="small-muted"><?= nl2br(e($product['description'] ?? '')) ?></div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
