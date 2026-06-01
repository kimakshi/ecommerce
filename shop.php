<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/products.php';

$availability = $_GET['availability'] ?? '';
$maxPrice = $_GET['max_price'] ?? '296';
$sort = $_GET['sort'] ?? 'date_desc';
$category = $_GET['category'] ?? '';

$products = products_search([
  'availability' => $availability,
  'max_price' => $maxPrice,
  'sort' => $sort,
  'category' => $category,
]);
$cats = categories_all();

$title = 'Shop - ' . APP_NAME;
$breadcrumb = '<a href="index.php">Home</a> &nbsp;›&nbsp; Shop';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <div class="page-title"><h1>Shop</h1></div>

  <div class="layout">
    <aside class="sidebar">
      <div class="section">
        <h3>Availability</h3>
        <div class="form">
          <label style="font-size:12px;color:#374151"><input type="radio" name="availability" value="" <?= $availability===''?'checked':'' ?> onchange="location.href=this.dataset.href" data-href="<?= e('shop.php?' . http_build_query(array_merge($_GET, ['availability'=>'']))) ?>"> All</label>
          <label style="font-size:12px;color:#374151"><input type="radio" name="availability" value="on_sale" <?= $availability==='on_sale'?'checked':'' ?> onchange="location.href=this.dataset.href" data-href="<?= e('shop.php?' . http_build_query(array_merge($_GET, ['availability'=>'on_sale']))) ?>"> On sale</label>
          <label style="font-size:12px;color:#374151"><input type="radio" name="availability" value="in_stock" <?= $availability==='in_stock'?'checked':'' ?> onchange="location.href=this.dataset.href" data-href="<?= e('shop.php?' . http_build_query(array_merge($_GET, ['availability'=>'in_stock']))) ?>"> In stock</label>
        </div>
      </div>

      <div class="section">
        <h3>Category</h3>
        <div class="form">
          <a class="small-muted" href="shop.php?<?= e(http_build_query(array_merge($_GET, ['category'=>'']))) ?>">All</a>
          <?php foreach ($cats as $c): ?>
            <a class="small-muted" href="shop.php?<?= e(http_build_query(array_merge($_GET, ['category'=>$c['slug']]))) ?>"><?= e($c['name']) ?></a>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="section">
        <h3>Price</h3>
        <div class="filter-row">
          <div class="small-muted">₹158</div>
          <div class="small-muted">₹296</div>
        </div>
        <div class="small-muted" data-price-label></div>
        <input class="range" data-price-range type="range" min="158" max="296" value="<?= e((string)$maxPrice) ?>" onmouseup="location.href='shop.php?<?= e(http_build_query(array_merge($_GET, ['max_price'=>'__V__']))) ?>'.replace('__V__', this.value)">
      </div>
    </aside>

    <main>
      <div class="grid-top">
        <div class="small-muted">There are <?= count($products) ?> results in total</div>
        <form method="get">
          <input type="hidden" name="availability" value="<?= e($availability) ?>">
          <input type="hidden" name="max_price" value="<?= e((string)$maxPrice) ?>">
          <input type="hidden" name="category" value="<?= e($category) ?>">
          <select class="select" name="sort" onchange="this.form.submit()">
            <option value="date_desc" <?= $sort==='date_desc'?'selected':'' ?>>Sort by: Date, new to old</option>
            <option value="date_asc" <?= $sort==='date_asc'?'selected':'' ?>>Sort by: Date, old to new</option>
            <option value="price_asc" <?= $sort==='price_asc'?'selected':'' ?>>Sort by: Price, low to high</option>
            <option value="price_desc" <?= $sort==='price_desc'?'selected':'' ?>>Sort by: Price, high to low</option>
            <option value="name_asc" <?= $sort==='name_asc'?'selected':'' ?>>Sort by: Name</option>
          </select>
        </form>
      </div>

      <div class="products">
        <?php foreach ($products as $p): ?>
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

      <div style="text-align:center;margin-top:16px" class="small-muted">You've viewed <?= count($products) ?> of <?= count($products) ?> result</div>
    </main>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
