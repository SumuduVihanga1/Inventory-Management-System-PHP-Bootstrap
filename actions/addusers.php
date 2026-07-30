<?php
include __DIR__ . '/../config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $username     = mysqli_real_escape_string($conn, $_POST['uname']);
    $useremail    = mysqli_real_escape_string($conn, $_POST['uemail']);
    $userpassword = mysqli_real_escape_string($conn, $_POST['upassword']);
    $userrole     = mysqli_real_escape_string($conn, $_POST['userrole']);

    // Insert into users table
    $sql = "INSERT INTO `users` (`name`, `email`, `password`, `role`) 
            VALUES ('$username', '$useremail', '$userpassword', '$userrole')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $new_user_id = mysqli_insert_id($conn);

        // Log the "add user" action
        if (isset($_SESSION['user_id'])) {
            $admin_id = $_SESSION['user_id']; // The one who added this user
            $added_value = json_encode([
                'name' => $username,
            ]);

            $sql_log = "INSERT INTO action_log (user_id, action_type, product_id, added_value) 
                        VALUES (?, 'add_user', NULL, ?)";
            $stmt_log = $conn->prepare($sql_log);
            $stmt_log->bind_param("is", $admin_id, $added_value);
            $stmt_log->execute();
            $stmt_log->close();
        }

        echo "<script>alert('✅ User added successfully'); window.location.href = '../admin/admin_users.php';</script>";
    } else {
        echo "<script>alert('❌ Failed to add user'); window.location.href = 'addusers.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="bg-light p-4">

    <div class="container-fluid max-width-lg">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-person-plus fs-3 text-success"></i>
            <h3 class="m-0 fw-bold text-dark">Add New User</h3>
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
                            placeholder="ex: John Doe" 
                            name="uname" 
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
                            placeholder="ex: john@example.com" 
                            name="uemail" 
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
                            placeholder="Enter password" 
                            name="upassword" 
                            required>
                    </div>

                    <!-- User Role -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="bi bi-person-badge me-1"></i> User Role
                        </label>
                        <select name="userrole" class="form-select" required>
                            <option value="">-- Choose a Role --</option>
                            <option value="admin">Admin</option>
                            <option value="store_keeper">Store Keeper</option>
                        </select>
                    </div>

                </div>

                <!-- Form Action Buttons -->
                <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                    <button type="submit" 
                            class="btn text-dark fw-semibold px-4"
                            style="background-color: #4cffb0; border-color: #4cffb0;">
                        <i class="bi bi-plus-circle me-1"></i> Add User
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