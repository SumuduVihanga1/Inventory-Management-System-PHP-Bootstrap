<?php
session_start();
include __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/layout.php';

inv_require_login(['admin']);

$search = '';
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT products.id, products.name, products.price, products.quantity, products.expiredate, category.categoryname
            FROM products JOIN category ON products.category_id = category.id
            WHERE products.name LIKE '%$search%' OR category.categoryname LIKE '%$search%'
               OR products.price LIKE '%$search%' OR products.quantity LIKE '%$search%'";
} else {
    $sql = "SELECT products.id, products.name, products.price, products.quantity, products.expiredate, category.categoryname
            FROM products JOIN category ON products.category_id = category.id";
}
$result = mysqli_query($conn, $sql);

inv_layout_start('Products', 'admin', 'product', 'bi-box-seam', $_SESSION['username'], 'Welcome admin');
?>

<form method="GET" class="d-flex justify-content-end gap-2 mb-3">
    <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>" style="max-width:280px;">
    <button type="submit" class="btn btn-inv-outline"><i class="bi bi-search"></i> Search</button>
</form>

<div class="inv-card">
    <div class="table-responsive">
        <table class="table inv-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Expire Date</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)):
                        $expClass = inv_expire_class($row['expiredate']); ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><i class="bi bi-box me-1 text-muted"></i><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['price']) ?></td>
                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                    <td><?php if ($expClass): ?><span class="badge <?= $expClass ?>"><?= htmlspecialchars($row['expiredate']) ?></span><?php else: ?><?= htmlspecialchars($row['expiredate']) ?><?php endif; ?></td>
                    <td><span class="badge bg-light text-dark"><?= htmlspecialchars($row['categoryname']) ?></span></td>
                </tr>
                <?php endwhile;
                else: ?>
                <tr><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-inbox d-block fs-3"></i>No products found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php inv_layout_end(); ?>
