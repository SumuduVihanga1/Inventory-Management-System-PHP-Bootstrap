<?php
session_start();
include __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/layout.php';

inv_require_login(['store_keeper']);

$loggedInUsername = $_SESSION['username'];

$stats = [];
foreach ([
    'total_products'   => "SELECT COUNT(id) AS v FROM products",
    'total_stock'      => "SELECT COALESCE(SUM(quantity),0) AS v FROM products",
    'total_categories' => "SELECT COUNT(id) AS v FROM category",
    'total_lowstock'   => "SELECT COUNT(*) AS v FROM products WHERE quantity > 0 AND quantity <= 50",
    'total_outofstock' => "SELECT COUNT(*) AS v FROM products WHERE quantity = 0",
    'total_users'      => "SELECT COUNT(id) AS v FROM users",
] as $key => $sql) {
    $r = $conn->query($sql);
    $stats[$key] = ($r && $row = $r->fetch_assoc()) ? $row['v'] : 0;
}

$loginActivity = $conn->query("SELECT name, last_login FROM users ORDER BY last_login DESC LIMIT 5");
$recentActivity = $conn->query("SELECT u.name, a.action_type, a.added_value, a.action_time, p.name AS product_name
    FROM action_log a JOIN users u ON a.user_id = u.id LEFT JOIN products p ON a.product_id = p.id
    ORDER BY a.action_time DESC LIMIT 5");

/* ---- Extra data for the charts ---- */
$inStockResult = $conn->query("SELECT COUNT(*) AS v FROM products WHERE status = 'in stock'");
$stats['total_instock'] = $stats['total_products'] 
                          - $stats['total_lowstock'] 
                          - $stats['total_outofstock'];

$categoryBreakdown = $conn->query("SELECT c.categoryname AS category_name, COUNT(p.id) AS product_count
    FROM category c LEFT JOIN products p ON p.category_id = c.id
    GROUP BY c.id, c.categoryname
    ORDER BY product_count DESC
    LIMIT 8");

$categoryLabels = [];
$categoryCounts = [];
if ($categoryBreakdown) {
    while ($row = $categoryBreakdown->fetch_assoc()) {
        $categoryLabels[] = $row['category_name'];
        $categoryCounts[] = (int) $row['product_count'];
    }
}

inv_layout_start('Dashboard', 'store', 'dashboard', 'bi-speedometer2', $loggedInUsername, 'Welcome storekeeper');
?>

<!-- Stat Cards Row -->
<div class="row g-3 mb-4">
    <?php
    $cards = [
        ['label' => 'Total Products', 'value' => $stats['total_products'], 'icon' => 'bi-box-seam', 'cls' => 'stat-icon-primary'],
        ['label' => 'Total Stock', 'value' => $stats['total_stock'], 'icon' => 'bi-boxes', 'cls' => 'stat-icon-info'],
        ['label' => 'Categories', 'value' => $stats['total_categories'], 'icon' => 'bi-tags', 'cls' => 'stat-icon-primary'],
        ['label' => 'Low Stock', 'value' => $stats['total_lowstock'], 'icon' => 'bi-exclamation-triangle', 'cls' => 'stat-icon-warning'],
        ['label' => 'Out of Stock', 'value' => $stats['total_outofstock'], 'icon' => 'bi-x-circle', 'cls' => 'stat-icon-danger'],
        ['label' => 'Total Users', 'value' => $stats['total_users'], 'icon' => 'bi-people', 'cls' => 'stat-icon-info'],
    ];
    foreach ($cards as $c): ?>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon <?= $c['cls'] ?>"><i class="bi <?= $c['icon'] ?>"></i></div>
                <div>
                    <div class="stat-value"><?= htmlspecialchars($c['value']) ?></div>
                    <div class="stat-label"><?= htmlspecialchars($c['label']) ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ===== Charts Row ===== -->
<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="inv-card">
            <div class="inv-card-header"><i class="bi bi-bar-chart-line"></i> Products by Category</div>
            <div class="p-3" style="position:relative; height:320px;">
                <canvas id="categoryBarChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="inv-card">
            <div class="inv-card-header"><i class="bi bi-pie-chart"></i> Stock Status Breakdown</div>
            <div class="p-3" style="position:relative; height:320px;">
                <canvas id="stockPieChart"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- ===== End Charts Row ===== -->

<!-- Activity Row -->
<div class="row g-3">
    <div class="col-lg-3">
        <div class="inv-card">
            <div class="inv-card-header"><i class="bi bi-clock-history"></i> Login Activity</div>
            <div class="p-3">
                <?php if ($loginActivity && $loginActivity->num_rows > 0):
                    while ($row = $loginActivity->fetch_assoc()): ?>
                    <div class="activity-item">
                        <span class="actor"><?= htmlspecialchars($row['name']) ?></span> logged in
                        <span class="time d-block"><i class="bi bi-calendar3 me-1"></i><?= date('M j, H:i', strtotime($row['last_login'])) ?></span>
                    </div>
                    <?php endwhile;
                else: ?>
                    <p class="text-muted mb-0">No recent logins.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="inv-card">
            <div class="inv-card-header"><i class="bi bi-activity"></i> Recent Activity</div>
            <div class="p-3">
                <?php if ($recentActivity && $recentActivity->num_rows > 0):
                    while ($row = $recentActivity->fetch_assoc()):
                        $addedValue = json_decode($row['added_value'], true);
                        $details = '';
                        if (!empty($addedValue)) {
                            foreach ($addedValue as $section => $changes) {
                                if (is_array($changes)) {
                                    foreach ($changes as $k => $v) $details .= "$k: $v, ";
                                } else {
                                    $details .= "$section: $changes ";
                                }
                            }
                        } ?>
                    <div class="activity-item">
                        <span class="actor"><?= htmlspecialchars($row['name']) ?></span>
                        performed <strong><?= htmlspecialchars($row['action_type']) ?></strong>
                        <?php if (!empty($row['product_name'])): ?>
                            on <strong><?= htmlspecialchars($row['product_name']) ?></strong>
                        <?php endif; ?>
                        <?php if ($details): ?>---<?= htmlspecialchars(trim($details, ', ')) ?><?php endif; ?>
                        <span class="time d-block"><i class="bi bi-calendar3 me-1"></i><?= date('M j, H:i', strtotime($row['action_time'])) ?></span>
                    </div>
                    <?php endwhile;
                else: ?>
                    <p class="text-muted mb-0">No recent actions logged.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
inv_layout_end();
?>

<!-- ===== Chart.js library + init script ===== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const categoryLabels = <?= json_encode($categoryLabels) ?>;
    const categoryCounts  = <?= json_encode($categoryCounts) ?>;
    const stockData = [
        <?= (int) $stats['total_instock'] ?>,
        <?= (int) $stats['total_lowstock'] ?>,
        <?= (int) $stats['total_outofstock'] ?>
    ];

    console.log('Chart data check:', { categoryLabels, categoryCounts, stockData });

    function initCharts() {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js did not load - charts cannot render.');
            return;
        }

        const barCtx = document.getElementById('categoryBarChart');
        if (barCtx) {
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: categoryLabels.length ? categoryLabels : ['No categories found'],
                    datasets: [{
                        label: 'Products',
                        data: categoryCounts.length ? categoryCounts : [0],
                        backgroundColor: '#4e73df',
                        borderRadius: 6,
                        maxBarThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });
        }

        const pieCtx = document.getElementById('stockPieChart');
        if (pieCtx) {
            new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: ['In Stock', 'Low Stock', 'Out of Stock'],
                    datasets: [{
                        data: stockData,
                        backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCharts);
    } else {
        initCharts();
    }
})();
</script>