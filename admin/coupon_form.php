<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/_layout.php';

require_admin();

$id = (int)($_GET['id'] ?? 0);
$coupon = null;
if ($id) {
  $stmt = db()->prepare('SELECT * FROM coupons WHERE id=?');
  $stmt->execute([$id]);
  $coupon = $stmt->fetch();
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $code = strtoupper(trim((string)($_POST['code'] ?? '')));
  $type = ($_POST['type'] ?? 'fixed') === 'percent' ? 'percent' : 'fixed';
  $value = (float)($_POST['value'] ?? 0);
  $minSubtotal = (float)($_POST['min_subtotal'] ?? 0);
  $maxDiscount = (float)($_POST['max_discount'] ?? 0);
  $usageLimit = (int)($_POST['usage_limit'] ?? 0);
  $startsAt = trim((string)($_POST['starts_at'] ?? ''));
  $endsAt = trim((string)($_POST['ends_at'] ?? ''));
  $isActive = isset($_POST['is_active']) ? 1 : 0;

  if ($code === '' || $value <= 0) {
    $error = 'Please fill required fields.';
  } else {
    $startsAt = $startsAt !== '' ? $startsAt : null;
    $endsAt = $endsAt !== '' ? $endsAt : null;

    if ($id) {
      $upd = db()->prepare('UPDATE coupons SET code=?, type=?, value=?, min_subtotal=?, max_discount=?, usage_limit=?, starts_at=?, ends_at=?, is_active=? WHERE id=?');
      $upd->execute([$code, $type, $value, $minSubtotal, $maxDiscount, $usageLimit, $startsAt, $endsAt, $isActive, $id]);
    } else {
      $ins = db()->prepare('INSERT INTO coupons (code, type, value, min_subtotal, max_discount, usage_limit, starts_at, ends_at, is_active) VALUES (?,?,?,?,?,?,?,?,?)');
      $ins->execute([$code, $type, $value, $minSubtotal, $maxDiscount, $usageLimit, $startsAt, $endsAt, $isActive]);
    }

    redirect('coupons.php');
  }
}

admin_layout_start('coupons', $id ? 'Edit Coupon' : 'Add Coupon', 'Create discount rules');
?>

<?php if ($error): ?>
  <div class="admin-card" style="margin-top:14px;border-color:rgba(239,68,68,.35)">
    <div class="admin-sub" style="color:#fca5a5"><?= e($error) ?></div>
  </div>
<?php endif; ?>

<div class="admin-card" style="margin-top:14px;max-width:920px">
  <form method="post" class="admin-grid" style="margin-top:0">
    <div style="grid-column:span 6" class="admin-field">
      <label>Code</label>
      <input name="code" value="<?= e((string)($coupon['code'] ?? '')) ?>" style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none" required>
    </div>

    <div style="grid-column:span 6" class="admin-field">
      <label>Type</label>
      <select name="type">
        <option value="fixed" <?= (($coupon['type'] ?? 'fixed')==='fixed')?'selected':'' ?>>Fixed (₹)</option>
        <option value="percent" <?= (($coupon['type'] ?? '')==='percent')?'selected':'' ?>>Percent (%)</option>
      </select>
    </div>

    <div style="grid-column:span 4" class="admin-field">
      <label>Value</label>
      <input type="number" step="0.01" name="value" value="<?= e((string)($coupon['value'] ?? '')) ?>" style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none" required>
    </div>

    <div style="grid-column:span 4" class="admin-field">
      <label>Min Subtotal</label>
      <input type="number" step="0.01" name="min_subtotal" value="<?= e((string)($coupon['min_subtotal'] ?? '0')) ?>" style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none">
    </div>

    <div style="grid-column:span 4" class="admin-field">
      <label>Max Discount (optional)</label>
      <input type="number" step="0.01" name="max_discount" value="<?= e((string)($coupon['max_discount'] ?? '0')) ?>" style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none">
    </div>

    <div style="grid-column:span 4" class="admin-field">
      <label>Usage Limit (0 = unlimited)</label>
      <input type="number" name="usage_limit" value="<?= e((string)($coupon['usage_limit'] ?? '0')) ?>" style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none">
    </div>

    <div style="grid-column:span 4" class="admin-field">
      <label>Starts At (YYYY-MM-DD)</label>
      <input name="starts_at" value="<?= e((string)($coupon['starts_at'] ?? '')) ?>" style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none">
    </div>

    <div style="grid-column:span 4" class="admin-field">
      <label>Ends At (YYYY-MM-DD)</label>
      <input name="ends_at" value="<?= e((string)($coupon['ends_at'] ?? '')) ?>" style="width:100%;border:1px solid var(--a-line);background:rgba(255,255,255,.04);color:var(--a-text);border-radius:12px;padding:12px 12px;outline:none">
    </div>

    <div style="grid-column:span 12;display:flex;gap:14px;align-items:center;flex-wrap:wrap;margin-top:4px">
      <label style="font-size:13px;color:var(--a-text)">
        <input type="checkbox" name="is_active" <?= (int)($coupon['is_active'] ?? 1) ? 'checked' : '' ?>> Active
      </label>
    </div>

    <div style="grid-column:span 12" class="admin-actions">
      <button class="admin-btn primary" type="submit">Save</button>
      <a class="admin-btn" href="coupons.php">Cancel</a>
    </div>
  </form>
</div>

<?php admin_layout_end(); ?>
