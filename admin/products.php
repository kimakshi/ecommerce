<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/_layout.php';

require_admin();

$q = trim((string)($_GET['q'] ?? ''));
$categoryId = (int)($_GET['category_id'] ?? 0);

$cats = db()->query('SELECT * FROM categories ORDER BY name ASC')->fetchAll();

$where = [];
$bind = [];
if ($q !== '') {
  $where[] = '(p.name LIKE ? OR p.slug LIKE ?)';
  $like = '%' . $q . '%';
  $bind[] = $like;
  $bind[] = $like;
}
if ($categoryId > 0) {
  $where[] = 'p.category_id = ?';
  $bind[] = $categoryId;
}

$sql = 'SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id';
if ($where) {
  $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY p.created_at DESC LIMIT 300';

$stmt = db()->prepare($sql);
$stmt->execute($bind);
$rows = $stmt->fetchAll();

admin_layout_start('products', 'Products', 'Manage products, stock and pricing');
?>

<div class="admin-actions" style="margin-top:14px">
  <a class="admin-btn primary" href="product_form.php">Add Product</a>

  <form method="get" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;flex:1">
    <div class="admin-search" style="max-width:420px">
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      <input name="q" value="<?= e($q) ?>" placeholder="Search name or slug">
    </div>

    <select name="category_id" class="admin-btn" style="border-radius:12px">
      <option value="0">All categories</option>
      <?php foreach ($cats as $c): ?>
        <option value="<?= (int)$c['id'] ?>" <?= $categoryId===(int)$c['id']?'selected':'' ?>><?= e($c['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <button class="admin-btn" type="submit">Apply</button>
    <a class="admin-btn" href="products.php">Reset</a>
  </form>
</div>

<div class="admin-grid">
  <div class="admin-card" style="grid-column:span 12; overflow:auto">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Category</th>
          <th>MRP</th>
          <th>Sale</th>
          <th>Stock</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td>
              <div style="display:flex;gap:10px;align-items:center">
                <div class="admin-thumb">
                  <?php if (!empty($r['image_path']) && file_exists(__DIR__ . '/../' . $r['image_path'])): ?>
                    <img src="<?= e('../' . $r['image_path']) ?>" alt="">
                  <?php endif; ?>
                </div>
                <div>
                  <div><b><?= e($r['name']) ?></b></div>
                  <div style="color:rgba(156,163,175,.9);font-size:12px"><?= e($r['slug']) ?></div>
                </div>
              </div>
            </td>
            <td><?= e($r['category_name'] ?? '-') ?></td>
            <td><?= money_inr($r['price_mrp']) ?></td>
            <td><b><?= money_inr($r['price_sale']) ?></b></td>
            <td>
              <?php if ((int)$r['in_stock']): ?>
                <span class="admin-status delivered">in_stock</span>
              <?php else: ?>
                <span class="admin-status cancelled">out_of_stock</span>
              <?php endif; ?>
            </td>
            <td style="width:320px">
              <div class="admin-actions">
                <a class="admin-btn" href="product_form.php?id=<?= (int)$r['id'] ?>">Edit</a>

                <form method="post" action="product_toggle_stock.php" style="display:inline">
                  <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                  <input type="hidden" name="next" value="<?= e($_SERVER['REQUEST_URI'] ?? 'products.php') ?>">
                  <button class="admin-btn" type="submit"><?= (int)$r['in_stock'] ? 'Mark Out of Stock' : 'Mark In Stock' ?></button>
                </form>

                <a class="admin-btn danger" href="product_delete.php?id=<?= (int)$r['id'] ?>" onclick="return confirm('Delete product?')">Delete</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php admin_layout_end(); ?>
