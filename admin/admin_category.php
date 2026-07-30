<?php
session_start();
include __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/layout.php';

inv_require_login(['admin']);

$search = '';
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM category WHERE categoryname LIKE '%$search%' OR description LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM category";
}
$result = mysqli_query($conn, $sql);

inv_layout_start('Category', 'admin', 'category', 'bi-tags', $_SESSION['username'], 'Welcome admin');
?>

<form method="GET" class="d-flex justify-content-end gap-2 mb-3">
    <input type="text" name="search" class="form-control" placeholder="Search categories..." value="<?= htmlspecialchars($search) ?>" style="max-width:280px;">
    <button type="submit" class="btn btn-inv-outline"><i class="bi bi-search"></i> Search</button>
</form>

<div class="inv-card">
    <div class="table-responsive">
        <table class="table inv-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category Name</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><i class="bi bi-tag me-1 text-muted"></i><?= htmlspecialchars($row['categoryname']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                </tr>
                <?php endwhile;
                else: ?>
                <tr><td colspan="3" class="text-center text-muted py-4">No categories found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php inv_layout_end(); ?>
