<?php
session_start();
include __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/layout.php';

inv_require_login(['store_keeper']);

$search = '';
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM users WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR role LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM users";
}
$result = mysqli_query($conn, $sql);

inv_layout_start('Users', 'store', 'users', 'bi-people', $_SESSION['username']);
?>

<form method="GET" class="d-flex justify-content-end gap-2 mb-3">
    <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?= htmlspecialchars($search) ?>" style="max-width:280px;">
    <button type="submit" class="btn btn-inv-outline"><i class="bi bi-search"></i> Search</button>
</form>

<div class="inv-card">
    <div class="table-responsive">
        <table class="table inv-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><i class="bi bi-person me-1 text-muted"></i><?= htmlspecialchars($row['name']) ?></td>
                    <td><span class="badge bg-light text-dark"><?= htmlspecialchars($row['role']) ?></span></td>
                </tr>
                <?php endwhile;
                else: ?>
                <tr><td colspan="4" class="text-center text-muted py-4">No users found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php inv_layout_end(); ?>
