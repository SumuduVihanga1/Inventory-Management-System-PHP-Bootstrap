<?php
include __DIR__ . '/../config/db.php';
session_start();

if (isset($_POST['submit'])) {
    $username = trim($_POST['name']);
    $password = trim($_POST['pass']);

    $sql = "SELECT * FROM users WHERE name = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $userType = $user['role'];

        $update = "UPDATE users SET last_login = NOW() WHERE id = ?";
        $update_stmt = $conn->prepare($update);
        $update_stmt->bind_param("i", $userId);
        $update_stmt->execute();
        $update_stmt->close();

        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $user['name'];
        $_SESSION['role'] = $userType;

        switch ($userType) {
            case 'admin':
                header("Location: ../admin/admin_dashboard.php");
                break;
            default:
                header("Location: ../store/dashboard.php");
                break;
        }
        exit();
    } else {
        $error = 'Invalid username or password.';
    }
    $stmt->close();
    $conn->close();
}

require __DIR__ . '/../includes/layout.php';
inv_auth_head('Login');
?>
<div class="auth-card">
    <div class="auth-icon"><i class="bi bi-shield-lock"></i></div>
    <h2>Welcome Back</h2>
    <p class="subtitle">Sign in to your inventory account</p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2 mb-3"><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="mb-3">
            <label class="form-label"><i class="bi bi-person me-1"></i> Username</label>
            <input type="text" class="form-control" placeholder="Enter your name" name="name" required>
        </div>
        <div class="mb-4">
            <label class="form-label"><i class="bi bi-key me-1"></i> Password</label>
            <input type="password" class="form-control" placeholder="Enter your password" name="pass" required>
        </div>
        <button type="submit" class="btn btn-inv w-100 py-2 mb-3" name="submit">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login
        </button>
        <p class="text-center mb-0" style="color:rgba(255,255,255,0.6);">
            Don't have an account? (ADMIN ONLY) <a href="reg.php">Register</a>
        </p>
    </form>
</div>
<?php inv_auth_foot(); ?>
