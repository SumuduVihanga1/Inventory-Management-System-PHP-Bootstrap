<?php
session_start();
include __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/layout.php';

inv_require_login(['store_keeper']);

$filter_category = $_GET['category_id'] ?? '';
$filter_date_start = $_GET['filter_date_start'] ?? '';
$filter_date_end = $_GET['filter_date_end'] ?? '';
$filter_status = $_GET['status'] ?? '';

$sql = "SELECT products.id, products.name, products.quantity, products.last_updated, products.expiredate, category.categoryname,
        CASE WHEN products.quantity = 0 THEN 'Out of Stock' WHEN products.quantity <= 50 THEN 'Low Stock' ELSE 'In Stock' END AS stock_status
        FROM products JOIN category ON products.category_id = category.id WHERE 1";

if (!empty($filter_category)) $sql .= " AND products.category_id = '" . mysqli_real_escape_string($conn, $filter_category) . "'";
if (!empty($filter_date_start) && !empty($filter_date_end)) {
    $sql .= " AND products.expiredate BETWEEN '" . mysqli_real_escape_string($conn, $filter_date_start) . "' AND '" . mysqli_real_escape_string($conn, $filter_date_end) . "'";
} elseif (!empty($filter_date_start)) {
    $sql .= " AND products.expiredate >= '" . mysqli_real_escape_string($conn, $filter_date_start) . "'";
} elseif (!empty($filter_date_end)) {
    $sql .= " AND products.expiredate <= '" . mysqli_real_escape_string($conn, $filter_date_end) . "'";
}
if (!empty($filter_status)) {
    $sql .= " AND (CASE WHEN products.quantity = 0 THEN 'Out of Stock' WHEN products.quantity <= 50 THEN 'Low Stock' ELSE 'In Stock' END) = '" . mysqli_real_escape_string($conn, $filter_status) . "'";
}

$result = mysqli_query($conn, $sql);

inv_layout_start('Reports', 'store', 'reports', 'bi-file-earmark-bar-graph');
?>

<form method="GET" class="filter-bar mb-3">
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label"><i class="bi bi-tags me-1"></i> Category</label>
            <select name="category_id" class="form-select">
                <option value="">All Categories</option>
                <?php
                $cat_result = mysqli_query($conn, "SELECT id, categoryname FROM category");
                while ($cat = mysqli_fetch_assoc($cat_result)) {
                    $sel = ($cat['id'] == $filter_category) ? 'selected' : '';
                    echo "<option value='{$cat['id']}' $sel>" . htmlspecialchars($cat['categoryname']) . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label"><i class="bi bi-calendar me-1"></i> Start Date</label>
            <input type="date" name="filter_date_start" class="form-control" value="<?= htmlspecialchars($filter_date_start) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label"><i class="bi bi-calendar me-1"></i> End Date</label>
            <input type="date" name="filter_date_end" class="form-control" value="<?= htmlspecialchars($filter_date_end) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label"><i class="bi bi-flag me-1"></i> Status</label>
            <select name="status" class="form-select">
                <option value="">All</option>
                <?php foreach (['In Stock', 'Low Stock', 'Out of Stock'] as $s): ?>
                <option value="<?= $s ?>" <?= $filter_status === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-inv flex-grow-1"><i class="bi bi-funnel me-1"></i> Filter</button>
            <button type="button" class="btn btn-inv-outline" onclick="printTable()"><i class="bi bi-printer"></i></button>
        </div>
    </div>
</form>

<div class="inv-card" id="reportTable">
    <div class="table-responsive">
        <table class="table inv-table mb-0">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Quantity</th>
                    <th>Expire Date</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)):
                        $expClass = inv_expire_class($row['expiredate']); ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['categoryname']) ?></td>
                    <td><?= inv_status_badge($row['stock_status']) ?></td>
                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                    <td><?php if ($expClass): ?><span class="badge <?= $expClass ?>"><?= htmlspecialchars($row['expiredate']) ?></span><?php else: ?><?= htmlspecialchars($row['expiredate']) ?><?php endif; ?></td>
                    <td><?= htmlspecialchars($row['last_updated']) ?></td>
                </tr>
                <?php endwhile;
                else: ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No products found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function printTable() {
    const content = document.getElementById('reportTable').innerHTML;
    const w = window.open('', '', 'height=600,width=900');
    w.document.write('<html><head><title>Report</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="p-4">');
    w.document.write('<h4>Inventory Report</h4><p class="text-muted">Printed: ' + new Date().toLocaleString() + '</p>');
    w.document.write(content);
    w.document.write('</body></html>');
    w.document.close();
    w.print();
}
</script>

<?php inv_layout_end(); ?>
