<?php
session_start();
include __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/layout.php';

inv_require_login(['store_keeper']);

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

inv_layout_start('Products', 'store', 'product', 'bi-box-seam');
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <a href="../actions/addproduct.php" class="btn btn-inv"><i class="bi bi-plus-circle me-1"></i> Add New Product</a>
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>" style="min-width:220px;">
        <button type="submit" class="btn btn-inv-outline"><i class="bi bi-search"></i></button>
    </form>
</div>

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
                    <th>Actions</th>
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
                    <td class="text-nowrap">
                        <a href="../actions/update.php?updateid=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <a href="../actions/delete.php?deleteid=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?');"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile;
                else: ?>
                <tr><td colspan="7" class="text-center text-muted py-4"><i class="bi bi-inbox d-block fs-3"></i>No products found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php inv_layout_end(); ?>
