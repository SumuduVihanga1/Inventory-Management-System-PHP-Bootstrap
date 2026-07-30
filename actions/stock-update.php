<?php
include __DIR__ . '/../config/db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in.'); window.location.href = '../auth/login.php';</script>";
    exit();
}
$user_id = $_SESSION['user_id'];

// Check for valid product ID
if (isset($_GET['supdateid'])) {
    $id = intval($_GET['supdateid']);
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<script>alert('❌ Product not found.'); window.location.href = '../store/sales.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('❌ Invalid request. No product ID provided.'); window.location.href = '../store/sales.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate quantity_sold input
    if (!isset($_POST['quantity_sold']) || !is_numeric($_POST['quantity_sold'])) {
        echo "<script>alert('❌ Invalid quantity sold.'); window.location.href = 'stock-update.php?supdateid=$id';</script>";
        exit();
    }

    $quantity_sold = intval($_POST['quantity_sold']);
    $old_quantity = intval($product['quantity']);

    if ($quantity_sold <= 0) {
        echo "<script>alert('❌ Quantity sold must be positive.'); window.location.href = 'stock-update.php?supdateid=$id';</script>";
        exit();
    }

    if ($quantity_sold > $old_quantity) {
        echo "<script>alert('❌ Quantity sold cannot be more than current stock ($old_quantity).'); window.location.href = 'stock-update.php?supdateid=$id';</script>";
        exit();
    }

    $new_quantity = $old_quantity - $quantity_sold;

    // Update product stock
    $sql_update = "UPDATE products SET 
                    quantity = ?, 
                    last_updated = NOW(),
                    status = CASE
                                WHEN quantity = 0 THEN 'Out of Stock'
                                WHEN quantity <= 50 THEN 'Low Stock'
                                ELSE 'In Stock'
                            END
                   WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ii", $new_quantity, $id);

    if ($stmt_update->execute()) {
        // Log the sale in action_log
        $added_value = json_encode(['quantity_sold' => $quantity_sold]);
        $sql_log = "INSERT INTO action_log (user_id, action_type, product_id, added_value) VALUES (?, 'Take-out', ?, ?)";
        $stmt_log = $conn->prepare($sql_log);
        $stmt_log->bind_param("iis", $user_id, $id, $added_value);
        $stmt_log->execute();
        $stmt_log->close();

        echo "<script>alert('✅ Sale recorded and stock updated successfully.'); window.location.href = '../store/sales.php';</script>";
    } else {
        echo "<script>alert('❌ Failed to update stock.'); window.location.href = 'stock-update.php?supdateid=$id';</script>";
    }

    $stmt_update->close();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Sale - Adjust Stock</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="bg-light p-4">

    <div class="container-fluid max-width-lg">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-box-arrow-up-right fs-3 text-success"></i>
            <h3 class="m-0 fw-bold text-dark">Take-out Stock</h3>
        </div>

        <!-- Form Card Container -->
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            
            <!-- Product Header Details -->
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                <div>
                    <span class="text-uppercase text-secondary fs-7 fw-bold">Product</span>
                    <h4 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($product['name']); ?></h4>
                </div>
                <div class="text-end">
                    <span class="text-uppercase text-secondary fs-7 fw-bold d-block">Current Stock</span>
                    <span class="badge bg-secondary-subtle text-dark border px-3 py-2 fs-6 fw-bold rounded-pill">
                        <i class="bi bi-boxes me-1 text-success"></i> <?= intval($product['quantity']); ?> Units
                    </span>
                </div>
            </div>

            <form method="POST" id="saleForm">
                <div class="row g-3">
                    
                    <!-- Quantity Input -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="bi bi-dash-circle me-1"></i> Quantity to Take-out
                        </label>
                        <input 
                            type="number" 
                            class="form-control" 
                            name="quantity_sold" 
                            id="quantity_sold" 
                            min="1" 
                            max="<?= intval($product['quantity']); ?>" 
                            placeholder="ex: 5" 
                            required>
                    </div>

                </div>

                <!-- Form Action Buttons -->
                <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                    <button type="submit" 
                            class="btn text-dark fw-semibold px-4"
                            style="background-color: #4cffb0; border-color: #4cffb0;">
                        <i class="bi bi-plus-circle me-1"></i> Take-out
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
        document.getElementById('saleForm').addEventListener('submit', function(e) {
            const qtyInput = document.getElementById('quantity_sold');
            const qtySold = parseInt(qtyInput.value);
            const maxQty = parseInt(qtyInput.max);

            if (isNaN(qtySold) || qtySold < 1) {
                alert('❌ Please enter a valid positive number.');
                e.preventDefault();
                return;
            }

            if (qtySold > maxQty) {
                alert('❌ Quantity sold cannot be more than current stock (' + maxQty + ').');
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>