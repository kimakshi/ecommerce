<?php
require_once __DIR__ . '/includes/helpers.php';

$cart = cart_get();

$subtotal = 0.0;
foreach ($cart as $item) {
  $subtotal += ((float)$item['price']) * (int)$item['qty'];
}

$title = 'Cart - ' . APP_NAME;
$breadcrumb = '<a href="index.php">Home</a> &nbsp;›&nbsp; Cart';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding:18px 0 34px 0">
  <div class="page-title" style="text-align:left"><h1 style="font-size:24px">Cart</h1></div>

  <?php if (!$cart): ?>
    <div class="notice">Your cart is empty. <a href="shop.php"><b>Continue shopping</b></a>.</div>
  <?php else: ?>
    <div class="card">
      <div class="section">
        <form method="post" action="cart_update.php">
          <table class="table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cart as $item): ?>
                <tr>
                  <td>
                    <div style="display:flex;gap:10px;align-items:center">
                      <div style="width:60px;height:60px;border-radius:12px;overflow:hidden;border:1px solid var(--line);background:#f3f4f6">
                        <?php if (!empty($item['image_path']) && file_exists(__DIR__ . '/' . $item['image_path'])): ?>
                          <img src="<?= e($item['image_path']) ?>" alt="" style="width:100%;height:100%;object-fit:cover">
                        <?php endif; ?>
                      </div>
                      <div>
                        <a href="product.php?slug=<?= e($item['slug']) ?>"><b><?= e($item['name']) ?></b></a>
                      </div>
                    </div>
                  </td>
                  <td><?= money_inr($item['price']) ?></td>
                  <td style="width:120px">
                    <input type="number" name="qty[<?= (int)$item['product_id'] ?>]" min="1" value="<?= (int)$item['qty'] ?>">
                  </td>
                  <td><?= money_inr(((float)$item['price']) * (int)$item['qty']) ?></td>
                  <td style="width:80px"><a class="btn" href="cart_remove.php?id=<?= (int)$item['product_id'] ?>">Remove</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

          <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:14px">
            <div class="small-muted">Subtotal: <b style="color:var(--text)"><?= money_inr($subtotal) ?></b></div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
              <button class="btn" type="submit">Update cart</button>
              <a class="btn primary" href="checkout.php">Checkout</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
