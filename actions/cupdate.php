<?php
include __DIR__ . '/../config/db.php';
session_start();

// Check if category ID is provided
if (!isset($_GET['cupdateid'])) {
    echo "<script>alert('❌ No category ID provided.'); window.location.href = '../store/category.php';</script>";
    exit();
}

$id = intval($_GET['cupdateid']);

// Fetch existing category
$sql = "SELECT * FROM category WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $category = $result->fetch_assoc(); // Store original values
} else {
    echo "<script>alert('❌ Category not found.'); window.location.href = '../store/category.php';</script>";
    exit();
}
$stmt->close();

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $catename = mysqli_real_escape_string($conn, $_POST['catename']);
    $catedis  = mysqli_real_escape_string($conn, $_POST['catedis']);

    // Prepare and execute update query
    $updateSql = "UPDATE category SET categoryname = ?, description = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssi", $catename, $catedis, $id);

    if ($updateStmt->execute()) {
        // ✅ Log the update if user is logged in
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $updated_data = json_encode([
                'old' => [
                    'name'        => $category['categoryname'],
                    'description' => $category['description']
                ],
                'new' => [
                    'name'        => $catename,
                    'description' => $catedis
                ]
            ]);

            // Insert log entry
            $log_sql = "INSERT INTO action_log (user_id, action_type, added_value)
                        VALUES (?, 'update_category', ?)";
            $stmt_log = $conn->prepare($log_sql);
            $stmt_log->bind_param("is", $user_id, $updated_data); // Bind two variables
            $stmt_log->execute();
            $stmt_log->close();
        }

        echo "<script>alert('✅ Category updated successfully'); window.location.href = '../store/category.php';</script>";
    } else {
        echo "<script>alert('❌ Update failed.'); window.location.href = 'cupdate.php?cupdateid=$id';</script>";
    }
    $updateStmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Category</title>
    
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
            <h3 class="m-0 fw-bold text-dark">Update Category</h3>
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
                            name="catename" 
                            placeholder="Category Name" 
                            value="<?= htmlspecialchars($category['categoryname']); ?>" 
                            required>
                    </div>

                    <!-- Category Description -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="bi bi-text-paragraph me-1"></i> Category Description
                        </label>
                        <input 
                            type="text" 
                            class="form-control" 
                            name="catedis" 
                            placeholder="Category Description" 
                            value="<?= htmlspecialchars($category['description']); ?>" 
                            required>
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                    <button type="submit" 
                            class="btn text-dark fw-semibold px-4"
                            style="background-color: #4cffb0; border-color: #4cffb0;">
                        <i class="bi bi-plus-circle me-1"></i> Update Category
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