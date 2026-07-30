<?php
session_start();
include __DIR__ . '/../config/db.php';

// Ensure user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Allow only admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php"); // Redirect if not admin
    exit();
}

$loggedInUsername = $_SESSION['username'];

// Initialize the search variable
$search = '';

// Check if search parameter exists in the GET request
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    // SQL query with search filter
    $sql = "SELECT * FROM users 
            WHERE name LIKE '%$search%' 
               OR email LIKE '%$search%' 
               OR role LIKE '%$search%'";
} else {
    // Default query without filter
    $sql = "SELECT * FROM users";
}

$result = mysqli_query($conn, $sql);
?>



<!DOCTYPE html>
<html>

<head>
    <title>Users</title>
    <link rel="stylesheet" href="../assets/css/admin/a_dashstyles.css"> 
    <link rel="stylesheet" href="../assets/css/admin/a_users.css"> 
</head>

<body>
    <div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-people fs-2 me-2"></i>
            <h2 class="mb-0">Users</h2>
        </div>

        <div class="d-flex align-items-center">
            <span class="me-3">
                Welcome -
                <strong class="text-success">
                    <?= htmlspecialchars($loggedInUsername) ?>
                </strong>
            </span>

            <i class="bi bi-person-circle fs-3 me-3"></i>
            <i class="bi bi-gear fs-4"></i>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">

        <a href="../actions/addusers.php" class="btn btn-success">
            <i class="bi bi-person-plus-fill me-1"></i>
            Add New User
        </a>

        <form method="GET" class="d-flex gap-2">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search users..."
                style="max-width:280px;">

            <button class="btn btn-inv-outline">
                <i class="bi bi-search me-1"></i>
                Search
            </button>
        </form>

    </div>

    <div class="inv-card">

        <div class="table-responsive">

            <table class="table inv-table mb-0">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th width="250">Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    if ($result) {

                        if (mysqli_num_rows($result) > 0) {

                            while ($row = mysqli_fetch_assoc($result)) {
                    ?>

                    <tr>

                        <td><?= $row['id'] ?></td>

                        <td>
                            <i class="bi bi-person me-1 text-muted"></i>
                            <?= htmlspecialchars($row['name']) ?>
                        </td>

                        <td><?= htmlspecialchars($row['email']) ?></td>

                        <td>
                            <span class="text-muted">••••••••</span>
                        </td>

                        <td>
                            <span class="badge bg-light text-dark">
                                <?= htmlspecialchars($row['role']) ?>
                            </span>
                        </td>

                        <td>

                            <a href="../actions/user-info.php?infoid=<?= $row['id'] ?>"
                               class="btn btn-sm btn-inv-outline me-1">
                                <i class="bi bi-info-circle me-1"></i>
                                Info
                            </a>

                            <a href="../actions/uupdate.php?updateid=<?= $row['id'] ?>"
                               class="btn btn-sm btn-warning me-1">
                                <i class="bi bi-pencil-square me-1"></i>
                                Update
                            </a>

                            <a href="../actions/udelete.php?deleteid=<?= $row['id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Delete this user?')">
                                <i class="bi bi-trash me-1"></i>
                                Delete
                            </a>

                        </td>

                    </tr>

                    <?php
                            }

                        } else {

                            echo "<tr><td colspan='6' class='text-center text-muted py-4'>No users found</td></tr>";

                        }

                    } else {

                        echo "<tr><td colspan='6' class='text-center text-danger py-4'>Error executing query.</td></tr>";

                    }
                    ?>

                </tbody>

            </table>

        </div>

    </div>

</div> 
</body>
</html>