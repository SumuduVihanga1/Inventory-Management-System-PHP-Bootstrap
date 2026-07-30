<?php

function inv_asset($path) {
    return '../assets/' . ltrim($path, '/');
}

function inv_nav_items($panel) {
    if ($panel === 'admin') {
        return [
            'dashboard' => ['file' => 'admin_dashboard.php', 'label' => 'Dashboard', 'icon' => 'bi-speedometer2'],
            'product'   => ['file' => 'admin_product.php',   'label' => 'Products',  'icon' => 'bi-box-seam'],
            'sales'     => ['file' => 'admin_sales.php',     'label' => 'Stock',     'icon' => 'bi-boxes'],
            'category'  => ['file' => 'admin_category.php',  'label' => 'Category',  'icon' => 'bi-tags'],
            'reports'   => ['file' => 'admin_reports.php',   'label' => 'Reports',   'icon' => 'bi-file-earmark-bar-graph'],
            'users'     => ['file' => 'admin_users.php',     'label' => 'Users',     'icon' => 'bi-people'],
        ];
    }
    return [
        'dashboard' => ['file' => 'dashboard.php', 'label' => 'Dashboard', 'icon' => 'bi-speedometer2'],
        'product'   => ['file' => 'product.php',   'label' => 'Products',  'icon' => 'bi-box-seam'],
        'sales'     => ['file' => 'sales.php',     'label' => 'Stock',     'icon' => 'bi-boxes'],
        'category'  => ['file' => 'category.php',  'label' => 'Category',  'icon' => 'bi-tags'],
        'reports'   => ['file' => 'reports.php',   'label' => 'Reports',   'icon' => 'bi-file-earmark-bar-graph'],
        'users'     => ['file' => 'users.php',     'label' => 'Users',     'icon' => 'bi-people'],
    ];
}

function inv_head($title) {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> — Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= inv_asset('css/theme.css') ?>" rel="stylesheet">
</head>
<body>
    <?php
}

function inv_sidebar($panel, $activePage) {
    $items = inv_nav_items($panel);
    $roleLabel = $panel === 'admin' ? 'Admin Panel' : 'Store Keeper';
    ?>
    <aside class="inv-sidebar">
        <div class="inv-sidebar-brand">
            <h5><i class="bi bi-box-seam-fill me-1"></i> Inventory System</h5>
            <small><?= htmlspecialchars($roleLabel) ?></small>
        </div>
        <nav class="inv-nav nav flex-column py-2 flex-grow-1">
            <?php foreach ($items as $key => $item): ?>
                <a class="nav-link<?= $key === $activePage ? ' active' : '' ?>" href="<?= htmlspecialchars($item['file']) ?>">
                    <i class="bi <?= htmlspecialchars($item['icon']) ?>"></i>
                    <?= htmlspecialchars($item['label']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="inv-sidebar-footer">
            <a href="../auth/logout.php" class="btn btn-inv w-100">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </a>
        </div>
    </aside>
    <?php
}

function inv_main_open($title, $icon, $username = null, $welcomeLabel = 'Welcome') {
    ?>
    <main class="inv-main">
        <div class="inv-topbar d-flex justify-content-between align-items-center">
            <h4><i class="bi <?= htmlspecialchars($icon) ?> me-2 text-success"></i><?= htmlspecialchars($title) ?></h4>
            <?php if ($username): ?>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted d-none d-md-inline"><?= htmlspecialchars($welcomeLabel) ?>,</span>
                    <span class="user-badge"><i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($username) ?></span>
                </div>
            <?php endif; ?>
        </div>
        <div class="inv-content">
    <?php
}

function inv_main_close() {
    ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
    <?php
}

function inv_layout_start($title, $panel, $activePage, $icon, $username = null, $welcomeLabel = 'Welcome') {
    inv_head($title);
    inv_sidebar($panel, $activePage);
    inv_main_open($title, $icon, $username, $welcomeLabel);
}

function inv_layout_end() {
    inv_main_close();
}

function inv_auth_head($title) {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> — Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= inv_asset('css/theme.css') ?>" rel="stylesheet">
</head>
<body class="auth-body">
    <?php
}

function inv_auth_foot() {
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
    <?php
}

function inv_form_head($title) {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> — Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= inv_asset('css/theme.css') ?>" rel="stylesheet">
</head>
<body class="form-page-body">
    <?php
}

function inv_form_foot() {
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
    <?php
}

function inv_status_badge($status) {
    $map = [
        'In Stock'      => 'badge-in-stock',
        'Low Stock'     => 'badge-low-stock',
        'Out of Stock'  => 'badge-out-stock',
        'low stock'     => 'badge-low-stock',
        'out of stock'  => 'badge-out-stock',
    ];
    $class = $map[$status] ?? 'bg-secondary';
    return '<span class="badge ' . htmlspecialchars($class) . '">' . htmlspecialchars($status) . '</span>';
}

function inv_expire_class($dateStr) {
    $expire = strtotime($dateStr);
    if (!$expire) return '';
    $today = strtotime('today');
    $diff = ($expire - $today) / 86400;
    if ($diff < 0) return 'badge-expired';
    if ($diff <= 7) return 'badge-expiring';
    return '';
}

function inv_require_login($roles = null) {
    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        header('Location: ../auth/login.php');
        exit();
    }
    if ($roles !== null && (!isset($_SESSION['role']) || !in_array($_SESSION['role'], (array)$roles))) {
        header('Location: ../auth/login.php');
        exit();
    }
}
