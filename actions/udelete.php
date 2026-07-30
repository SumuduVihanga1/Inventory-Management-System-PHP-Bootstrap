<?php
include __DIR__ . '/../config/db.php';
session_start(); // Needed for logging the current user

if (isset($_GET['deleteid'])) {
    $deleteid = intval($_GET['deleteid']); // Sanitize input

    // Step 1: Fetch the username before deletion
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $deleteid);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();

    if ($username) {
        // Step 2: Delete the user
        $sql = "DELETE FROM users WHERE id = $deleteid";
        if (mysqli_query($conn, $sql)) {
            
            // Step 3: Log the deletion in action_log
            if (isset($_SESSION['user_id'])) {
                $logged_in_user_id = $_SESSION['user_id'];
                $action_type = 'delete_user';
                $added_value = json_encode(['deleted_username' => $username]);

                $stmt_log = $conn->prepare(
                    "INSERT INTO action_log (user_id, action_type, product_id, added_value) VALUES (?, ?, NULL, ?)"
                );
                $stmt_log->bind_param("iss", $logged_in_user_id, $action_type, $added_value);
                $stmt_log->execute();
                $stmt_log->close();
            }

            header("Location: ../admin/admin_users.php");
            exit();
        } else {
            echo "❌ Error deleting user: " . mysqli_error($conn);
        }
    } else {
        echo "❌ User not found.";
    }
} else {
    echo "❌ No user ID provided to delete.";
}
?>
