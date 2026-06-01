<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/_layout.php';

require_admin();

$salesRows = db()->query("SELECT DATE(created_at) AS d, COALESCE(SUM(total),0) AS s FROM orders WHERE created_at >= (CURDATE() - INTERVAL 6 DAY) GROUP BY DATE(created_at) ORDER BY d ASC")->fetchAll();
$salesMap = [];
foreach ($salesRows as $r) {
  $salesMap[(string)$r['d']] = (float)$r['s'];
}
$series = [];
for ($i = 6; $i >= 0; $i--) {
  $d = date('Y-m-d', strtotime('-' . $i . ' day'));
  $series[] = ['d' => $d, 'v' => (float)($salesMap[$d] ?? 0.0)];
}

$top = db()->query('SELECT product_name, SUM(quantity) AS q, SUM(line_total) AS t FROM order_items GROUP BY product_name ORDER BY t DESC LIMIT 10')->fetchAll();

admin_layout_start('reports', 'Reports', 'Sales overview and top products');
?>

<div class="admin-grid">
  <div class="admin-card" style="grid-column:span 7">
    <canvas class="admin-chart" data-admin-chart data-series='<?= e(json_encode($series)) ?>'></canvas>
  </div>

  <div class="admin-card" style="grid-column:span 5; overflow:auto">
    <div style="font-weight:700">Top Products</div>
    <div class="admin-sub" style="margin-top:6px">By revenue</div>
    <table class="admin-table" style="margin-top:10px">
      <thead>
        <tr>
          <th>Product</th>
          <th>Qty</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($top as $r): ?>
          <tr>
            <td><?= e($r['product_name']) ?></td>
            <td><?= (int)$r['q'] ?></td>
            <td><b><?= money_inr($r['t']) ?></b></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php admin_layout_end(); ?>
