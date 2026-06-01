<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/_layout.php';

require_admin();

$id = (int)($_GET['id'] ?? 0);
$product = null;
if ($id) {
  $stmt = db()->prepare('SELECT * FROM products WHERE id=?');
  $stmt->execute([$id]);
  $product = $stmt->fetch();
}

$cats = db()->query('SELECT * FROM categories ORDER BY name ASC')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim((string)($_POST['name'] ?? ''));
  $slug = trim((string)($_POST['slug'] ?? ''));
  $categoryId = (int)($_POST['category_id'] ?? 0);
  $mrp = (float)($_POST['price_mrp'] ?? 0);
  $sale = (float)($_POST['price_sale'] ?? 0);
  $inStock = isset($_POST['in_stock']) ? 1 : 0;
  $onSale = isset($_POST['is_on_sale']) ? 1 : 0;
  $badge = trim((string)($_POST['badge_text'] ?? ''));
  $short = trim((string)($_POST['short_desc'] ?? ''));
  $desc = trim((string)($_POST['description'] ?? ''));

  if ($name === '' || $slug === '' || $mrp <= 0 || $sale <= 0) {
    $error = 'Please fill required fields.';
  } else {
    $imagePath = $product['image_path'] ?? null;
    if (!empty($_FILES['image']['name'])) {
      if (!is_dir(PRODUCT_UPLOAD_DIR)) {
        mkdir(PRODUCT_UPLOAD_DIR, 0777, true);
      }
      $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
      $safeExt = in_array($ext, ['jpg','jpeg','png','webp'], true) ? $ext : 'png';
      $fileName = $slug . '-' . time() . '.' . $safeExt;
      $dest = PRODUCT_UPLOAD_DIR . '/' . $fileName;
      if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        $imagePath = 'uploads/products/' . $fileName;
      }
    }

    if ($id) {
      $stmt = db()->prepare('UPDATE products SET category_id=?, name=?, slug=?, short_desc=?, description=?, price_mrp=?, price_sale=?, in_stock=?, is_on_sale=?, image_path=?, badge_text=?, updated_at=NOW() WHERE id=?');
      $stmt->execute([
        $categoryId ?: null,
        $name,
        $slug,
        $short ?: null,
        $desc ?: null,
        $mrp,
        $sale,
        $inStock,
        $onSale,
        $imagePath,
        $badge ?: null,
        $id,
      ]);
    } else {
      $stmt = db()->prepare('INSERT INTO products (category_id, name, slug, short_desc, description, price_mrp, price_sale, in_stock, is_on_sale, image_path, badge_text) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
      $stmt->execute([
        $categoryId ?: null,
        $name,
        $slug,
        $short ?: null,
        $desc ?: null,
        $mrp,
        $sale,
        $inStock,
        $onSale,
        $imagePath,
        $badge ?: null,
      ]);
    }

    redirect('products.php');
  }
}

admin_layout_start('products', $id ? 'Edit Product' : 'Add Product', 'Create or update product details');
?>

<?php if (!empty($error)): ?>
  <div class="admin-card" style="margin-top:14px;border-color:rgba(239,68,68,.35)">
    <div class="admin-sub" style="color:#fca5a5"><?= e($error) ?></div>
  </div>
<?php endif; ?>

<div class="admin-card" style="margin-top:14px;max-width:980px">
  <form method="post" enctype="multipart/form-data" class="admin-grid" style="margin-top:0">
    <div class="admin-field" style="grid-column:span 12">
      <label>Name</label>
      <input name="name" required value="<?= e($product['name'] ?? '') ?>" style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none">
    </div>
    <div class="admin-field" style="grid-column:span 12">
      <label>Slug (unique)</label>
      <input name="slug" required value="<?= e($product['slug'] ?? '') ?>" style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none">
    </div>
    <div class="admin-field" style="grid-column:span 12">
      <label>Category</label>
      <select name="category_id">
        <option value="">-- Select --</option>
        <?php foreach ($cats as $c): ?>
          <option value="<?= (int)$c['id'] ?>" <?= (int)($product['category_id'] ?? 0)===(int)$c['id']?'selected':'' ?>><?= e($c['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="admin-field" style="grid-column:span 6">
      <label>MRP</label>
      <input type="number" step="0.01" name="price_mrp" required value="<?= e((string)($product['price_mrp'] ?? '')) ?>" style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none">
    </div>
    <div class="admin-field" style="grid-column:span 6">
      <label>Sale Price</label>
      <input type="number" step="0.01" name="price_sale" required value="<?= e((string)($product['price_sale'] ?? '')) ?>" style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none">
    </div>

    <div style="grid-column:span 12;display:flex;gap:14px;flex-wrap:wrap;align-items:center">
      <label style="font-size:13px;color:var(--a-text)"><input type="checkbox" name="in_stock" <?= (int)($product['in_stock'] ?? 1) ? 'checked' : '' ?>> In stock</label>
      <label style="font-size:13px;color:var(--a-text)"><input type="checkbox" name="is_on_sale" <?= (int)($product['is_on_sale'] ?? 0) ? 'checked' : '' ?>> On sale</label>
    </div>

    <div class="admin-field" style="grid-column:span 12">
      <label>Badge text (example: -36%)</label>
      <input name="badge_text" value="<?= e($product['badge_text'] ?? '') ?>" style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none">
    </div>
    <div class="admin-field" style="grid-column:span 12">
      <label>Short description</label>
      <input name="short_desc" value="<?= e($product['short_desc'] ?? '') ?>" style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none">
    </div>
    <div class="admin-field" style="grid-column:span 12">
      <label>Description</label>
      <textarea name="description" rows="5" style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none"><?= e($product['description'] ?? '') ?></textarea>
    </div>

    <div class="admin-field" style="grid-column:span 12">
      <label>Image (jpg/png/webp)</label>
      <input type="file" name="image" style="width:100%;border:1px dashed var(--a-line);background:rgba(255,255,255,.02);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none">
      <?php if (!empty($product['image_path'])): ?>
        <div class="admin-sub" style="margin-top:8px">Current: <?= e((string)$product['image_path']) ?></div>
      <?php endif; ?>
    </div>

    <div class="admin-actions" style="grid-column:span 12">
      <button class="admin-btn primary" type="submit">Save</button>
      <a class="admin-btn" href="products.php">Cancel</a>
    </div>
  </form>
</div>

<?php admin_layout_end(); ?>
