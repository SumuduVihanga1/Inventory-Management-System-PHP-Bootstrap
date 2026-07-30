<?php
// Include the database connection
include __DIR__ . '/../config/db.php';

// Check if the 'updateid' is passed in the URL
if (isset($_GET['updateid'])) {
    $id = intval($_GET['updateid']);

    // Fetch the existing user details from the database
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result); // Fetch the user details
    } else {
        echo "<script>alert('❌ User not found.'); window.location.href = '../store/users.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('❌ Invalid request. No user ID provided.'); window.location.href = '../store/users.php';</script>";
    exit();
}

// Update user in the database if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated user data from the form
    $username     = $_POST['uname'];
    $useremail    = $_POST['uemail'];
    $userpassword = $_POST['upassword'];
    $userrole     = $_POST['userrole'];

    // Escape special characters for safety
    $username     = mysqli_real_escape_string($conn, $username);
    $useremail    = mysqli_real_escape_string($conn, $useremail);
    $userpassword = mysqli_real_escape_string($conn, $userpassword);
    $userrole     = mysqli_real_escape_string($conn, $userrole);

    // Update query
    $sql = "UPDATE users SET 
            name = '$username',
            email = '$useremail',
            password = '$userpassword',
            role = '$userrole'
            WHERE id = $id";

    $result = mysqli_query($conn, $sql);

    // Check if update was successful
    if ($result) {
        echo "<script>alert('✅ Update Successful'); window.location.href = '../admin/admin_users.php';</script>";
    } else {
        echo "<script>alert('❌ Update Unsuccessful'); window.location.href = 'uupdate.php?updateid=$id';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="bg-light p-4">

    <div class="container-fluid max-width-lg">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-pencil-square fs-3 text-success"></i>
            <h3 class="m-0 fw-bold text-dark">Update User</h3>
        </div>

        <!-- Form Card Container -->
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <form method="POST">
                <div class="row g-3">
                    
                    <!-- User Name -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="bi bi-person me-1"></i> User Name
                        </label>
                        <input 
                            type="text" 
                            class="form-control" 
                            placeholder="User Name" 
                            name="uname" 
                            value="<?= htmlspecialchars($user['name']); ?>" 
                            required>
                    </div>

                    <!-- User Email -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="bi bi-envelope me-1"></i> User Email
                        </label>
                        <input 
                            type="email" 
                            class="form-control" 
                            placeholder="User Email" 
                            name="uemail" 
                            value="<?= htmlspecialchars($user['email']); ?>" 
                            required>
                    </div>

                    <!-- User Password -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="bi bi-key me-1"></i> User Password
                        </label>
                        <input 
                            type="password" 
                            class="form-control" 
                            placeholder="User Password" 
                            name="upassword" 
                            value="<?= htmlspecialchars($user['password']); ?>" 
                            required>
                    </div>

                    <!-- User Role -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="bi bi-person-badge me-1"></i> User Role
                        </label>
                        <select name="userrole" class="form-select" required>
                            <option value="">-- Choose a Role --</option>
                            <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="store_keeper" <?= ($user['role'] == 'store_keeper') ? 'selected' : '' ?>>Store Keeper</option>
                        </select>
                    </div>

                </div>

                <!-- Form Action Buttons -->
                <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                    <button type="submit" 
                            class="btn text-dark fw-semibold px-4"
                            style="background-color: #4cffb0; border-color: #4cffb0;">
                        <i class="bi bi-plus-circle me-1"></i> Update User
                    </button>
                    <button type="button" onclick="history.back();" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>