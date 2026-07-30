<?php
include __DIR__ . '/../config/db.php';
session_start();

// Get categories
$categories = mysqli_query($conn, "SELECT id, categoryname FROM category");

// Check if 'updateid' is provided
if (isset($_GET['updateid'])) {
    $id = intval($_GET['updateid']);

    // Fetch the existing product details for form and logging
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result); // Store original values
    } else {
        echo "<script>alert('❌ Product not found.'); window.location.href = '../store/product.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('❌ Invalid request. No product ID provided.'); window.location.href = '../store/product.php';</script>";
    exit();
}

// If form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $proname     = trim($_POST['pname']);
    $proprice    = floatval($_POST['pprice']);
    $proqty      = intval($_POST['pqty']);
    $proedate    = $_POST['pedate'];
    $procategory = intval($_POST['pcategory']);

    if (empty($procategory)) {
        echo "<script>alert('❌ Category not selected!'); window.location.href = 'update.php?updateid=$id';</script>";
        exit();
    }

    // ===== Determine status based on quantity =====
    if ($proqty <= 0) {
        $status = 'Out Of Stock';
    } elseif ($proqty <= 5) { // Set low stock threshold (e.g., 5 or less)
        $status = 'Low Stock';
    } else {
        $status = 'In Stock';
    }

    // Prepare and execute update query including status update
    $sql_update = "UPDATE products SET 
                    name = ?,
                    price = ?,
                    quantity = ?,
                    expiredate = ?,
                    category_id = ?,
                    status = ?
                   WHERE id = ?";

    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sdisisi", $proname, $proprice, $proqty, $proedate, $procategory, $status, $id);
    $result_update = $stmt_update->execute();

    if ($result_update) {
        // ✅ Log the update if user is logged in
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $updated_data = json_encode([
                'old' => [
                    'n'   => $product['name'],
                    '$'   => $product['price'],
                    'qty' => $product['quantity'],
                    'status' => $product['status'] ?? ''
                ],
                'new' => [
                    'n'   => $proname,
                    '$'   => $proprice,
                    'qty' => $proqty,
                    'status' => $status
                ]
            ]);

            $log_sql = "INSERT INTO action_log (user_id, action_type, product_id, added_value)
                        VALUES (?, 'update_product', ?, ?)";

            $stmt_log = $conn->prepare($log_sql);
            $stmt_log->bind_param("iis", $user_id, $id, $updated_data);
            $stmt_log->execute();
            $stmt_log->close();
        }

        $stmt_update->close();
        echo "<script>alert('✅ Update Successful'); window.location.href = '../store/product.php';</script>";
    } else {
        $stmt_update->close();
        echo "<script>alert('❌ Update Unsuccessful'); window.location.href = 'update.php?updateid=$id';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    
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
            <h3 class="m-0 fw-bold text-dark">Update Product</h3>
        </div>

        <!-- Form Card Container -->
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <form method="POST">
                <div class="row g-3">
                    
                    <!-- Product Name -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="bi bi-card-text me-1"></i> Product Name
                        </label>
                        <input 
                            type="text" 
                            class="form-control" 
                            name="pname" 
                            value="<?= htmlspecialchars($product['name']); ?>" 
                            required>
                    </div>

                    <!-- Price -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="bi bi-currency-dollar me-1"></i> Product Price
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            class="form-control" 
                            name="pprice" 
                            value="<?= htmlspecialchars($product['price']); ?>" 
                            required>
                    </div>

                    <!-- Quantity -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="bi bi-boxes me-1"></i> Product Quantity
                        </label>
                        <input 
                            type="number" 
                            class="form-control" 
                            name="pqty" 
                            value="<?= htmlspecialchars($product['quantity']); ?>" 
                            required>
                    </div>

                    <!-- Expire Date -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="bi bi-calendar-event me-1"></i> Product Expiry Date
                        </label>
                        <input 
                            type="date" 
                            class="form-control" 
                            name="pedate" 
                            id="pedate" 
                            value="<?= htmlspecialchars($product['expiredate']); ?>" 
                            required>
                        <small 
                            id="error-msg" 
                            class="text-danger mt-2 d-block" 
                            style="display:none !important;">
                            <i class="bi bi-exclamation-circle"></i> You Can't Set An Expired Date.
                        </small>
                    </div>

                    <!-- Category -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="bi bi-tags me-1"></i> Category
                        </label>
                        <select 
                            name="pcategory" 
                            class="form-select" 
                            required>
                            <option value="">-- Select Category --</option>
                            <?php while ($row = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?= $row['id'] ?>" <?= $row['id'] == $product['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['categoryname']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                    <button type="submit" 
                            class="btn text-dark fw-semibold px-4"
                            style="background-color: #4cffb0; border-color: #4cffb0;">
                        <i class="bi bi-plus-circle me-1"></i> Update Product
                    </button>
                    <button type="button" onclick="history.back();" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"></script>

    <script>
        const pedateInput = document.getElementById('pedate');
        const errorMsg = document.getElementById('error-msg');

        pedateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                errorMsg.style.setProperty('display', 'block', 'important');
                this.setCustomValidity("You Can't Set An Expired Date.");
            } else {
                errorMsg.style.setProperty('display', 'none', 'important');
                this.setCustomValidity("");
            }
        });
    </script>
</body>
</html>