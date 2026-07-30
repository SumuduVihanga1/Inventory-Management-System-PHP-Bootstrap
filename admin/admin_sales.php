<?php
session_start();
include __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/layout.php';

inv_require_login(['admin']);

$filter_category = $_GET['category_id'] ?? '';
$filter_status = $_GET['status'] ?? '';
$sort_order = strtolower($_GET['sort_order'] ?? 'asc');
if (!in_array($sort_order, ['asc', 'desc'])) $sort_order = 'asc';

$sql = "SELECT products.id, products.name, products.quantity, products.last_updated, products.expiredate, category.categoryname,
        CASE WHEN products.quantity = 0 THEN 'Out of Stock' WHEN products.quantity <= 50 THEN 'Low Stock' ELSE 'In Stock' END AS stock_status
        FROM products JOIN category ON products.category_id = category.id WHERE 1";

if (!empty($filter_category)) $sql .= " AND products.category_id = '" . mysqli_real_escape_string($conn, $filter_category) . "'";
if (!empty($filter_status)) {
    $sql .= " AND (CASE WHEN products.quantity = 0 THEN 'Out of Stock' WHEN products.quantity <= 50 THEN 'Low Stock' ELSE 'In Stock' END) = '" . mysqli_real_escape_string($conn, $filter_status) . "'";
}
$sql .= " ORDER BY products.expiredate $sort_order";

$result = mysqli_query($conn, $sql);

inv_layout_start('Stock', 'admin', 'sales', 'bi-boxes', $_SESSION['username'], 'Welcome admin');
?>

<form method="GET" class="filter-bar mb-3">
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
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
        <div class="col-md-3">
            <label class="form-label"><i class="bi bi-sort-down me-1"></i> Sort by Date</label>
            <select name="sort_order" class="form-select">
                <option value="asc" <?= $sort_order === 'asc' ? 'selected' : '' ?>>Ascending</option>
                <option value="desc" <?= $sort_order === 'desc' ? 'selected' : '' ?>>Descending</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label"><i class="bi bi-flag me-1"></i> Status</label>
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <?php foreach (['In Stock', 'Low Stock', 'Out of Stock'] as $s): ?>
                <option value="<?= $s ?>" <?= $filter_status === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-inv w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
        </div>
    </div>
</form>

<div class="inv-card">
    <div class="table-responsive">
        <table class="table inv-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Stock Level</th>
                    <th>Expire Date</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)):
                        $expClass = inv_expire_class($row['expiredate']); ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['categoryname']) ?></td>
                    <td><?= inv_status_badge($row['stock_status']) ?></td>
                    <td><strong><?= htmlspecialchars($row['quantity']) ?></strong></td>
                    <td><?php if ($expClass): ?><span class="badge <?= $expClass ?>"><?= htmlspecialchars($row['expiredate']) ?></span><?php else: ?><?= htmlspecialchars($row['expiredate']) ?><?php endif; ?></td>
                    <td><?= htmlspecialchars($row['last_updated']) ?></td>
                </tr>
                <?php endwhile;
                else: ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No products found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php inv_layout_end(); ?>
