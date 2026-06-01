<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/auth.php';

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $next = (string)($_POST['next'] ?? ($_GET['next'] ?? ''));
  $email = (string)($_POST['email'] ?? '');
  $password = (string)($_POST['password'] ?? '');
  if (!auth_login($email, $password)) {
    $error = 'Invalid email or password.';
  } else {
    if ($next !== '' && !preg_match('~^https?://~i', $next) && !str_starts_with($next, '//')) {
      redirect($next);
    }
    redirect('account.php');
  }
}

$title = 'Login - ' . APP_NAME;
$breadcrumb = '<a href="index.php">Home</a> &nbsp;›&nbsp; Login';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding:18px 0 34px 0">
  <div class="page-title" style="text-align:left"><h1 style="font-size:24px">Login</h1></div>
  <div class="card"><div class="section" style="max-width:520px">
    <?php if ($error): ?><div class="notice" style="border-color:#fecaca;background:#fff1f2"><?= e($error) ?></div><?php endif; ?>

    <form class="form" method="post">
      <input type="hidden" name="next" value="<?= e((string)($_GET['next'] ?? '')) ?>">
      <div class="field"><label>Email</label><input type="email" name="email" required></div>
      <div class="field"><label>Password</label><input type="password" name="password" required></div>
      <button class="btn primary" type="submit">Login</button>
    </form>

    <div class="small-muted" style="margin-top:12px">New customer? <a href="register.php"><b>Create an account</b></a>.</div>
    <div class="small-muted" style="margin-top:6px">Admin demo login: <b>admin@makhana.local</b> / <b>admin123</b></div>
  </div></div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
