<?php
include __DIR__ . '/../config/db.php';
session_start();

$categories = mysqli_query($conn, "SELECT id, categoryname FROM category");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $proname     = $_POST['pname'];
    $proprice    = $_POST['pprice'];
    $proqty      = $_POST['pqty'];
    $proedate    = $_POST['pedate'];
    $procategory = isset($_POST['pcategory']) ? $_POST['pcategory'] : null;

    if (empty($procategory)) {
        echo "<script>alert('❌ Category not selected!'); window.location.href = 'addproduct.php';</script>";
        exit();
    }

    // Escape input
    $proname     = mysqli_real_escape_string($conn, $proname);
    $proprice    = mysqli_real_escape_string($conn, $proprice);
    $proqty      = mysqli_real_escape_string($conn, $proqty);
    $proedate    = mysqli_real_escape_string($conn, $proedate);
    $procategory = mysqli_real_escape_string($conn, $procategory);

    // Insert product
    $sql = "INSERT INTO `products` (`name`, `price`, `quantity`, `expiredate`, `category_id`) 
            VALUES ('$proname', '$proprice', '$proqty', '$proedate', '$procategory')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $product_id = mysqli_insert_id($conn);

        // Log the "add product" action
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $added_value = json_encode([
                'quantity_added' => $proqty,
            ]);

            $sql_log = "INSERT INTO action_log (user_id, action_type, product_id, added_value) 
                        VALUES (?, 'add_product', ?, ?)";

            $stmt_log = $conn->prepare($sql_log);
            $stmt_log->bind_param("iis", $user_id, $product_id, $added_value);
            $stmt_log->execute();
            $stmt_log->close();
        }

        echo "<script>alert('✅ Product added successfully'); window.location.href = '../store/product.php';</script>";
    } else {
        echo "<script>alert('❌ Failed to add product'); window.location.href = 'addproduct.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
</head>

<body class="bg-light p-4">

    <div class="container-fluid max-width-lg">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-box-seam fs-3 text-success"></i>
            <h3 class="m-0 fw-bold text-dark">Add New Product</h3>
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
                            placeholder="ex: Onions" 
                            name="pname" 
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
                            placeholder="ex: 125.00" 
                            name="pprice" 
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
                            placeholder="ex: 50" 
                            name="pqty" 
                            required>
                    </div>

                    <!-- Expire Date -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="bi bi-calendar-event me-1"></i> Product Expire Date
                        </label>
                        <input 
                            type="date" 
                            class="form-control" 
                            name="pedate" 
                            id="pedate" 
                            required>
                        <small 
                            id="error-msg" 
                            class="text-danger mt-2 d-block" 
                            style="display:none !important;">
                            <i class="bi bi-exclamation-circle"></i> You Can't Add Expired Item.
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
                                <option value="<?= $row['id'] ?>">
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
                        <i class="bi bi-plus-circle me-1"></i> Add Product
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

    <script>
        const pedateInput = document.getElementById('pedate');
        const errorMsg = document.getElementById('error-msg');

        pedateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                errorMsg.style.setProperty('display', 'block', 'important');
                this.setCustomValidity("You Can't Add Expired Item.");
            } else {
                errorMsg.style.setProperty('display', 'none', 'important');
                this.setCustomValidity("");
            }
        });
    </script>
</body>
</html>