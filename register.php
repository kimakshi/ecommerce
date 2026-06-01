<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/auth.php';

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $next = (string)($_POST['next'] ?? ($_GET['next'] ?? ''));
  $name = (string)($_POST['name'] ?? '');
  $email = (string)($_POST['email'] ?? '');
  $phone = (string)($_POST['phone'] ?? '');
  $password = (string)($_POST['password'] ?? '');
  $res = auth_register($name, $email, $phone, $password);
  if (!$res['ok']) {
    $error = $res['error'] ?? 'Registration failed.';
  } else {
    if ($next !== '' && !preg_match('~^https?://~i', $next) && !str_starts_with($next, '//')) {
      redirect($next);
    }
    redirect('account.php');
  }
}

$title = 'Register - ' . APP_NAME;
$breadcrumb = '<a href="index.php">Home</a> &nbsp;›&nbsp; Register';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding:18px 0 34px 0">
  <div class="page-title" style="text-align:left"><h1 style="font-size:24px">Create Account</h1></div>
  <div class="card"><div class="section" style="max-width:520px">
    <?php if ($error): ?><div class="notice" style="border-color:#fecaca;background:#fff1f2"><?= e($error) ?></div><?php endif; ?>

    <form class="form" method="post">
      <input type="hidden" name="next" value="<?= e((string)($_GET['next'] ?? '')) ?>">
      <div class="field"><label>Full Name</label><input name="name" required></div>
      <div class="field"><label>Email</label><input type="email" name="email" required></div>
      <div class="field"><label>Phone</label><input name="phone"></div>
      <div class="field"><label>Password</label><input type="password" name="password" required></div>
      <button class="btn primary" type="submit">Create Account</button>
    </form>

    <div class="small-muted" style="margin-top:12px">Already have an account? <a href="login.php"><b>Login</b></a>.</div>
  </div></div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
