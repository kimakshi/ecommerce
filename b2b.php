<?php
require_once __DIR__ . '/includes/helpers.php';

$title = 'B2B - ' . APP_NAME;
$breadcrumb = '<a href="index.php">Home</a> &nbsp;›&nbsp; B2B';
require_once __DIR__ . '/includes/header.php';

$flashOk = $_SESSION['flash_b2b_success'] ?? null;
$flashErr = $_SESSION['flash_b2b_error'] ?? null;
unset($_SESSION['flash_b2b_success'], $_SESSION['flash_b2b_error']);
?>

<div class="b2b-wrap">
  <div class="container" style="padding:18px 0 34px 0">
    <div class="b2b-hero">
      <h1 class="b2b-title">B2B Partnership Inquiry</h1>
      <div class="b2b-sub">Join us to become a premium supplier and get access to premium quality makhana products at wholesale rates. Fill the form below and our team will get back to you soon.</div>
    </div>

    <div class="b2b-card">
      <?php if ($flashOk): ?>
        <div class="notice" style="border-color:#bbf7d0;background:#f0fdf4;margin-bottom:12px"><?= e((string)$flashOk) ?></div>
      <?php endif; ?>
      <?php if ($flashErr): ?>
        <div class="notice" style="border-color:#fecaca;background:#fff1f2;margin-bottom:12px"><?= e((string)$flashErr) ?></div>
      <?php endif; ?>

      <form class="form" method="post" action="b2b_submit.php">
        <div class="b2b-section-title">Company Information</div>
        <div class="b2b-grid">
          <div class="field">
            <label>Company Name *</label>
            <input required name="company_name" placeholder="Your company name">
          </div>
          <div class="field">
            <label>Contact Person *</label>
            <input required name="contact_person" placeholder="Full name">
          </div>
          <div class="field">
            <label>Email Address *</label>
            <input required type="email" name="email" placeholder="name@company.com">
          </div>
          <div class="field">
            <label>Phone Number *</label>
            <input required name="phone" placeholder="+91">
          </div>
          <div class="field">
            <label>Business Type *</label>
            <select required name="business_type">
              <option value="">Select business type</option>
              <option value="distributor">Distributor</option>
              <option value="retailer">Retailer</option>
              <option value="wholesaler">Wholesaler</option>
              <option value="brand">Snack Brand</option>
              <option value="horeca">HoReCa</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="field">
            <label>GST Number</label>
            <input name="gst" placeholder="GSTIN">
          </div>
        </div>

        <div class="b2b-section-title" style="margin-top:14px">Address Information</div>
        <div class="b2b-grid">
          <div class="field" style="grid-column:span 2">
            <label>Address *</label>
            <textarea required name="address" rows="3" placeholder="Street, area"></textarea>
          </div>
          <div class="field">
            <label>City *</label>
            <input required name="city" placeholder="City">
          </div>
          <div class="field">
            <label>State *</label>
            <input required name="state" placeholder="State">
          </div>
          <div class="field" style="grid-column:span 2">
            <label>Pin Code *</label>
            <input required name="pincode" placeholder="Pin code">
          </div>
        </div>

        <div class="b2b-section-title" style="margin-top:14px">Business Requirements</div>
        <div class="b2b-grid">
          <div class="field" style="grid-column:span 1">
            <label>Requirement / Details *</label>
            <textarea required name="requirements" rows="5" placeholder="Tell us your requirement, monthly quantity, delivery city, etc."></textarea>
          </div>
          <div class="field" style="grid-column:span 1">
            <label>Select Products</label>
            <div class="b2b-tags">
              <label class="b2b-tag"><input type="checkbox" name="products[]" value="Organic Plain">Organic Plain</label>
              <label class="b2b-tag"><input type="checkbox" name="products[]" value="Cheese">Cheese</label>
              <label class="b2b-tag"><input type="checkbox" name="products[]" value="Peri Peri">Peri Peri</label>
              <label class="b2b-tag"><input type="checkbox" name="products[]" value="Tomato">Tomato</label>
              <label class="b2b-tag"><input type="checkbox" name="products[]" value="Cream & Onion">Cream & Onion</label>
              <label class="b2b-tag"><input type="checkbox" name="products[]" value="Salt & Pepper">Salt & Pepper</label>
              <label class="b2b-tag"><input type="checkbox" name="products[]" value="Pudina">Pudina</label>
              <label class="b2b-tag"><input type="checkbox" name="products[]" value="Combo Packs">Combo Packs</label>
            </div>
          </div>
        </div>

        <button class="btn b2b-submit" type="submit">Submit Inquiry</button>
      </form>
    </div>

    <div class="b2b-why">
      <h3 class="b2b-why-title">Why Partner With Us?</h3>
      <div class="b2b-why-grid">
        <div class="b2b-mini">
          <div class="b2b-mini-ic">₹</div>
          <div>
            <div class="b2b-mini-t">Competitive Pricing</div>
            <div class="b2b-mini-d">Best wholesale rates with consistent supply and transparent billing.</div>
          </div>
        </div>
        <div class="b2b-mini">
          <div class="b2b-mini-ic">★</div>
          <div>
            <div class="b2b-mini-t">Premium Quality</div>
            <div class="b2b-mini-d">Small-batch roasting and premium sourcing for consistent taste.</div>
          </div>
        </div>
        <div class="b2b-mini">
          <div class="b2b-mini-ic">⚡</div>
          <div>
            <div class="b2b-mini-t">Fast Delivery</div>
            <div class="b2b-mini-d">Pan-India logistics and quick dispatch for regular B2B orders.</div>
          </div>
        </div>
        <div class="b2b-mini">
          <div class="b2b-mini-ic">☎</div>
          <div>
            <div class="b2b-mini-t">Dedicated Support</div>
            <div class="b2b-mini-d">Account manager support for catalogs, pricing and order tracking.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
