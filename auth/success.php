<?php require __DIR__ . '/../includes/layout.php'; inv_auth_head('Success'); ?>
<div class="auth-card text-center">
    <div class="auth-icon"><i class="bi bi-check-circle"></i></div>
    <h2>Registration Successful!</h2>
    <p class="subtitle mb-4">Your admin account has been created.</p>
    <a href="login.php" class="btn btn-inv w-100 py-2">
        <i class="bi bi-box-arrow-in-right me-1"></i> Go to Login
    </a>
</div>
<?php inv_auth_foot(); ?>
