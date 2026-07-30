<?php

include __DIR__ . '/../config/db.php';
session_start();

if (isset($_GET['deleteid'])) {
    $deleteid = intval($_GET['deleteid']); // Sanitize input

    // Fetch category name before deleting (optional — can be removed if not needed)
    $query = "SELECT categoryname FROM category WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $deleteid);
    $stmt->execute();
    $stmt->bind_result($categoryname);
    $stmt->fetch();
    $stmt->close();

    if ($categoryname) {
        // Proceed to delete using prepared statement
        $deleteStmt = $conn->prepare("DELETE FROM category WHERE id = ?");
        $deleteStmt->bind_param("i", $deleteid);
        $deleteSuccess = $deleteStmt->execute();
        $deleteStmt->close();

        if ($deleteSuccess) {
            header("Location: ../store/category.php");
            exit();
        } else {
            echo "❌ Error deleting category: " . mysqli_error($conn);
        }
    } else {
        echo "❌ Category not found.";
    }
} else {
    echo "❌ No category ID provided to delete.";
}
?>


