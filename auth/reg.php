<?php
require __DIR__ . '/../config/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $confirm  = $_POST['password-re'];
    $adminkeycode = $_POST['adminkey'];

    if ($password != $confirm) {
        $error = 'Passwords do not match.';
    } elseif ($adminkeycode != 'admin123') {
        $error = 'Admin key does not match.';
    } else {
        $checkQuery = "SELECT * FROM users WHERE name = ? OR email = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'Username or email already exists.';
        } else {
            $insertQuery = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sss", $username, $email, $password);
            if ($stmt->execute()) {
                header("Location: success.php");
                exit();
            } else {
                $error = 'Error during registration.';
            }
        }
        $stmt->close();
        $conn->close();
    }
}

require __DIR__ . '/../includes/layout.php';
inv_auth_head('Register');
?>
<div class="auth-card" style="max-width:480px;">
    <div class="auth-icon"><i class="bi bi-person-plus"></i></div>
    <h2>Register</h2>
    <p class="subtitle">Create an admin account</p>

    <?php if ($error): ?>
        <div class="alert alert-danger py-2 mb-3"><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="mb-3">
            <label class="form-label"><i class="bi bi-person me-1"></i> Username</label>
            <input type="text" class="form-control" placeholder="Enter username" name="username" required>
        </div>
        <div class="mb-3">
            <label class="form-label"><i class="bi bi-envelope me-1"></i> Email</label>
            <input type="email" class="form-control" placeholder="Enter email" name="email" required>
        </div>
        <div class="mb-3">
            <label class="form-label"><i class="bi bi-key me-1"></i> Password</label>
            <input type="password" class="form-control" placeholder="Enter password" name="password" required>
        </div>
        <div class="mb-3">
            <label class="form-label"><i class="bi bi-key-fill me-1"></i> Confirm Password</label>
            <input type="password" class="form-control" placeholder="Re-enter password" name="password-re" required>
        </div>
        <div class="mb-4">
            <label class="form-label"><i class="bi bi-shield-check me-1"></i> Admin Keycode</label>
            <input type="password" class="form-control" placeholder="Enter admin keycode" name="adminkey" required>
        </div>
        <button type="submit" class="btn btn-inv w-100 py-2 mb-2"><i class="bi bi-person-check me-1"></i> Register</button>
        <button type="button" class="btn btn-outline-light w-100 py-2 mb-3" onclick="history.back();"><i class="bi bi-arrow-left me-1"></i> Back</button>
        <p class="text-center mb-0" style="color:rgba(255,255,255,0.6);">
            Already have an account? <a href="login.php">Login</a>
        </p>
    </form>
</div>
<?php inv_auth_foot(); ?>
