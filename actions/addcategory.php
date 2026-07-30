<?php
include __DIR__ . '/../config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $catename = $_POST['catename'];
    $catedis  = $_POST['catedis'];

    $catename = mysqli_real_escape_string($conn, $catename);
    $catedis  = mysqli_real_escape_string($conn, $catedis);

    // Insert into category table
    $sql = "INSERT INTO `category` (`categoryname`, `description`) 
            VALUES ('$catename', '$catedis')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $category_id = mysqli_insert_id($conn); // if needed later

        // Log the "add category" action
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $added_value = json_encode([
                'category_added' => $catename,
            ]);

            $sql_log = "INSERT INTO action_log (user_id, action_type, product_id, added_value) 
                        VALUES (?, 'add_category', NULL, ?)";
            $stmt_log = $conn->prepare($sql_log);
            $stmt_log->bind_param("is", $user_id, $added_value);
            $stmt_log->execute();
            $stmt_log->close();
        }

        echo "<script>alert('✅ Category added successfully'); window.location.href = '../store/category.php';</script>";
    } else {
        echo "<script>alert('❌ Failed to add category'); window.location.href = 'addcategory.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="bg-light p-4">

    <div class="container-fluid max-width-lg">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-tag fs-3 text-success"></i>
            <h3 class="m-0 fw-bold text-dark">Add New Category</h3>
        </div>

        <!-- Form Card Container -->
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <form method="POST">
                <div class="row g-3">
                    
                    <!-- Category Name -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="bi bi-tags me-1"></i> Category Name
                        </label>
                        <input 
                            type="text" 
                            class="form-control" 
                            placeholder="ex: Dairy & Eggs" 
                            name="catename" 
                            required>
                    </div>

                    <!-- Description -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="bi bi-text-paragraph me-1"></i> Description
                        </label>
                        <input 
                            type="text" 
                            class="form-control" 
                            placeholder="Add description" 
                            name="catedis" 
                            required>
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                    <button type="submit" 
                            class="btn text-dark fw-semibold px-4"
                            style="background-color: #4cffb0; border-color: #4cffb0;">
                        <i class="bi bi-plus-circle me-1"></i> Add Category
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