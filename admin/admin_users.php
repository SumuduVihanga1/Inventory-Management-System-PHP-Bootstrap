<?php
session_start();
include __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/layout.php';

inv_require_login(['admin']);

$search = '';
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM users WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR role LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM users";
}
$result = mysqli_query($conn, $sql);

inv_layout_start('Users', 'admin', 'users', 'bi-people', $_SESSION['username']);
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <a href="../actions/addusers.php" class="btn btn-inv"><i class="bi bi-plus-circle me-1"></i> Add New User</a>
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
                    <th>Username</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><i class="bi bi-person me-1 text-muted"></i><?= htmlspecialchars($row['name']) ?></td>
                    <td><span class="badge bg-light text-dark"><?= htmlspecialchars($row['role']) ?></span></td>
                    <td>
                        <a href="../actions/uupdate.php?updateid=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <a href="../actions/udelete.php?deleteid=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this User?');"><i class="bi bi-trash"></i></a>
                    </td>
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
