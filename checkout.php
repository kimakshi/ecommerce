<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/coupons.php';

require_login();

$cart = cart_get();
if (!$cart) {
  redirect('cart.php');
}

$subtotal = 0.0;
foreach ($cart as $item) {
  $subtotal += ((float)$item['price']) * (int)$item['qty'];
}

$shipping = ($subtotal >= 499) ? 0.0 : 49.0;

$couponError = $_SESSION['flash_coupon_error'] ?? null;
unset($_SESSION['flash_coupon_error']);

$couponCode = coupon_current_code();
$coupon = $couponCode !== '' ? coupon_find_by_code($couponCode) : null;
$couponOk = $couponCode === '' ? false : (bool)(coupon_validate_for_subtotal($coupon, $subtotal)['ok'] ?? false);
if ($couponCode !== '' && !$couponOk) {
  coupon_clear();
  $couponCode = '';
  $coupon = null;
}

$discount = $coupon ? coupon_calculate_discount($coupon, $subtotal) : 0.0;
$total = max(0.0, ($subtotal - $discount) + $shipping);

$title = 'Checkout - ' . APP_NAME;
$breadcrumb = '<a href="index.php">Home</a> &nbsp;›&nbsp; <a href="cart.php">Cart</a> &nbsp;›&nbsp; Checkout';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding:18px 0 34px 0">
  <div class="page-title" style="text-align:left"><h1 style="font-size:24px">Checkout</h1></div>

  <div class="layout" style="grid-template-columns:1.15fr .85fr">
    <main>
      <div class="card">
        <div class="section">
          <?php if ($couponError): ?>
            <div class="notice" style="border-color:#fecaca;background:#fff1f2"><?= e((string)$couponError) ?></div>
          <?php endif; ?>

          <div class="field">
            <label>Coupon</label>
            <?php if ($couponCode !== '' && $coupon): ?>
              <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap">
                <div class="small-muted">Applied: <b style="color:var(--text)"><?= e($couponCode) ?></b></div>
                <form method="post" action="remove_coupon.php">
                  <input type="hidden" name="next" value="checkout.php">
                  <button class="btn" type="submit">Remove</button>
                </form>
              </div>
            <?php else: ?>
              <form method="post" action="apply_coupon.php" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
                <input class="input" name="code" placeholder="Enter coupon code" style="max-width:260px">
                <input type="hidden" name="next" value="checkout.php">
                <button class="btn" type="submit">Apply</button>
              </form>
            <?php endif; ?>
          </div>

          <form class="form" method="post" action="place_order.php">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
              <div class="field">
                <label>Full Name</label>
                <input name="name" required value="<?= e(current_user()['name'] ?? '') ?>">
              </div>
              <div class="field">
                <label>Phone</label>
                <input name="phone" required value="<?= e(current_user()['phone'] ?? '') ?>">
              </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
              <div class="field">
                <label>Email</label>
                <input type="email" name="email" required value="<?= e(current_user()['email'] ?? '') ?>">
              </div>
              <div class="field">
                <label>Pincode</label>
                <input name="pincode" required>
              </div>
            </div>

            <div class="field">
              <label>Address Line 1</label>
              <input name="address1" required>
            </div>
            <div class="field">
              <label>Address Line 2</label>
              <input name="address2">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
              <div class="field">
                <label>City</label>
                <input name="city" required>
              </div>
              <div class="field">
                <label>State</label>
                <input name="state" required>
              </div>
            </div>

            <div class="field">
              <label>Notes</label>
              <textarea name="notes" rows="3"></textarea>
            </div>

            <div class="field">
              <label>Payment Method</label>
              <select name="payment_method" required>
                <option value="cod">Cash on Delivery (COD)</option>
                <option value="online">Online (Payment Pending)</option>
              </select>
            </div>

            <button class="btn primary" type="submit">Place Order</button>
          </form>
        </div>
      </div>
    </main>

    <aside>
      <div class="card">
        <div class="section">
          <h3>Order Summary</h3>
          <div class="form">
            <?php foreach ($cart as $item): ?>
              <div style="display:flex;justify-content:space-between;gap:10px;font-size:12px;color:#374151">
                <div><?= e($item['name']) ?> × <?= (int)$item['qty'] ?></div>
                <div><b><?= money_inr(((float)$item['price']) * (int)$item['qty']) ?></b></div>
              </div>
            <?php endforeach; ?>
          </div>
          <div style="height:1px;background:var(--line);margin:14px 0"></div>
          <div class="form">
            <div style="display:flex;justify-content:space-between"><span class="small-muted">Subtotal</span><b><?= money_inr($subtotal) ?></b></div>
            <div style="display:flex;justify-content:space-between"><span class="small-muted">Discount</span><b><?= money_inr($discount) ?></b></div>
            <div style="display:flex;justify-content:space-between"><span class="small-muted">Shipping</span><b><?= money_inr($shipping) ?></b></div>
            <div style="display:flex;justify-content:space-between"><span class="small-muted">Total</span><b><?= money_inr($total) ?></b></div>
          </div>
          <div class="small-muted" style="margin-top:10px">Free Shipping on all Orders above ₹499!</div>
        </div>
      </div>
    </aside>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
