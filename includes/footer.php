<?php
require_once __DIR__ . '/helpers.php';
$base = (isset($_SERVER['SCRIPT_NAME']) && str_contains((string)$_SERVER['SCRIPT_NAME'], '/admin/')) ? '../' : '';
?>

  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div>
          <div class="brand" style="margin-bottom:10px">
            <img class="brand-logo" src="<?= e($base) ?>assets/img/logo.png" alt="<?= e(APP_NAME) ?>">
          </div>
          <p>Chaatku makhana is sourced from the clean ponds of Bihar and roasted in small batches to deliver natural freshness and premium quality.</p>
          <p style="margin-top:10px">+91 8882816805<br>chaatku@gmail.com<br>WhatsApp: +91 8882816805 (Party delivery: order before 6 hours)</p>
          <div style="display:flex;gap:10px;margin-top:10px">
            <a class="icon-btn" href="#" aria-label="Facebook">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 9h3V6h-3c-2.2 0-4 1.8-4 4v3H7v3h3v6h3v-6h3l1-3h-4v-3c0-.6.4-1 1-1z" fill="currentColor"/></svg>
            </a>
            <a class="icon-btn" href="#" aria-label="Instagram">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm10 2H7a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3z" fill="currentColor"/><path d="M12 7a5 5 0 110 10 5 5 0 010-10zm0 2a3 3 0 100 6 3 3 0 000-6z" fill="currentColor"/><path d="M17.5 6.5a1 1 0 110 2 1 1 0 010-2z" fill="currentColor"/></svg>
            </a>
            <a class="icon-btn" href="#" aria-label="YouTube">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.6 7.2a3 3 0 00-2.1-2.1C17.7 4.6 12 4.6 12 4.6s-5.7 0-7.5.5A3 3 0 002.4 7.2 31.3 31.3 0 002.4 12a31.3 31.3 0 00.0 4.8 3 3 0 002.1 2.1c1.8.5 7.5.5 7.5.5s5.7 0 7.5-.5a3 3 0 002.1-2.1A31.3 31.3 0 0021.6 12a31.3 31.3 0 000-4.8z" fill="currentColor"/><path d="M10 15.5v-7l6 3.5-6 3.5z" fill="#fff"/></svg>
            </a>
          </div>
        </div>

        <div>
          <h4>Quick Links</h4>
          <div class="form">
            <a href="b2b.php">B2B</a>
            <a href="returns.php">Return & Refund Policy</a>
            <a href="shipping.php">Shipping policy</a>
            <a href="privacy.php">Privacy Policy</a>
            <a href="terms.php">Terms and Conditions</a>
            <a href="contact.php">Contact Us</a>
          </div>
        </div>

        <div>
          <h4>Sign Up to Newsletter</h4>
          <p>Join Our Snack Family Be the first to know about new launches and special deals</p>
          <form class="newsletter" method="post" action="subscribe.php">
            <input class="input" type="email" name="email" placeholder="Enter your email..." required>
            <button class="btn primary" type="submit">Subscribe</button>
          </form>
          <p style="margin-top:10px">***By entering the e-mail you accept the <a href="terms.php"><b>terms</b></a> and conditions and the <a href="privacy.php"><b>privacy policy</b></a>.</p>
        </div>
      </div>

      <div class="copy">© <?= date('Y') ?> Pinaqyn Tech</div>
    </div>
  </footer>

  <div class="img-modal" data-img-modal aria-hidden="true">
    <div class="img-modal-backdrop" data-img-modal-close></div>
    <div class="img-modal-panel" role="dialog" aria-modal="true">
      <div class="img-modal-toolbar">
        <button class="img-modal-btn" type="button" aria-label="Zoom in" data-img-zoom-in>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
        <button class="img-modal-btn" type="button" aria-label="Zoom out" data-img-zoom-out>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
        <button class="img-modal-btn" type="button" aria-label="Reset" data-img-zoom-reset>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a9 9 0 10-3.3 6.9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M21 12v-6h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
        <button class="img-modal-btn" type="button" aria-label="Close" data-img-modal-close>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
      </div>

      <div class="img-modal-stage" data-img-stage>
        <img class="img-modal-img" data-img-modal-img alt="">
      </div>
    </div>
  </div>

  <script src="<?= e($base) ?>assets/js/app.js"></script>
</body>
</html>
