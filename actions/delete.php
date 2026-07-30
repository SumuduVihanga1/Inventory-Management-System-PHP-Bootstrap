<?php
include __DIR__ . '/../config/db.php';
session_start();

if (isset($_GET['deleteid'])) {
    $deleteid = intval($_GET['deleteid']); // Sanitize input

    // Fetch product details before deletion for logging
    $productQuery = $conn->prepare("SELECT * FROM products WHERE id = ?");
    if (!$productQuery) {
        die("Prepare failed: " . $conn->error);
    }
    $productQuery->bind_param("i", $deleteid);
    if (!$productQuery->execute()) {
        die("Execute failed: " . $productQuery->error);
    }
    $productResult = $productQuery->get_result();

    if ($productResult && $productResult->num_rows > 0) {
        $product = $productResult->fetch_assoc();
        $productQuery->close();

        // Log deletion in action_log FIRST
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            // Prepare JSON string for added_value safely with null coalesce
            $deleted_value = json_encode([
                'name'       => $product['name'] ?? '',
                'price'      => $product['price'] ?? 0,
                'quantity'   => $product['quantity'] ?? 0,
                'category'   => $product['category_id'] ?? null,
                'expiredate' => $product['expiredate'] ?? ''
            ]);

            $sql_log = "INSERT INTO action_log (user_id, action_type, product_id, added_value) 
                        VALUES (?, 'delete_product', ?, ?)";
            $stmt_log = $conn->prepare($sql_log);
            if (!$stmt_log) {
                die("Prepare failed for log insert: " . $conn->error);
            }
            // Bind: user_id (int), product_id (int), added_value (string)
            $stmt_log->bind_param("iis", $user_id, $deleteid, $deleted_value);
            if (!$stmt_log->execute()) {
                die("Failed to log delete action: " . $stmt_log->error);
            }
            $stmt_log->close();
        } else {
            die("No user_id in session to log action.");
        }

        // Now safely delete the product itself
        $sql_delete_product = "DELETE FROM products WHERE id = ?";
        $stmt_product = $conn->prepare($sql_delete_product);
        if (!$stmt_product) {
            die("Prepare failed for product deletion: " . $conn->error);
        }
        $stmt_product->bind_param("i", $deleteid);

        if ($stmt_product->execute()) {
            $stmt_product->close();
            header("Location: ../store/product.php");
            exit();
        } else {
            echo "❌ Error deleting product: " . $stmt_product->error;
            $stmt_product->close();
        }
    } else {
        echo "❌ Product not found.";
    }
} else {
    echo "❌ No product ID provided to delete.";
}
?>
