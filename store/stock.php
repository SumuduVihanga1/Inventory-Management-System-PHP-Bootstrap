<?php
// Include the database connection
include __DIR__ . '/../config/db.php';

// Capture filters
$filter_category = $_GET['category_id'] ?? '';
$filter_date_start = $_GET['filter_date_start'] ?? '';
$filter_date_end = $_GET['filter_date_end'] ?? '';
$filter_status = $_GET['status'] ?? '';

// Build base SQL
$sql = "SELECT 
          products.id,
          products.name,
          products.price,
          products.quantity,
          products.status,
          products.last_updated,
          products.expiredate,
          category.categoryname,
          CASE 
            WHEN products.quantity = 0 THEN 'Out of Stock'
            WHEN products.quantity <= 50 THEN 'Low Stock'
            ELSE 'In Stock'
          END AS status
        FROM 
          products
        JOIN 
          category ON products.category_id = category.id
        WHERE 1";

// Append filters
if (!empty($filter_category)) {
    $sql .= " AND products.category_id = '" . mysqli_real_escape_string($conn, $filter_category) . "'";
}
if (!empty($filter_date_start) && !empty($filter_date_end)) {
    $sql .= " AND products.expiredate BETWEEN '" . mysqli_real_escape_string($conn, $filter_date_start) . "' AND '" . mysqli_real_escape_string($conn, $filter_date_end) . "'";
} elseif (!empty($filter_date_start)) {
    $sql .= " AND products.expiredate >= '" . mysqli_real_escape_string($conn, $filter_date_start) . "'";
} elseif (!empty($filter_date_end)) {
    $sql .= " AND products.expiredate <= '" . mysqli_real_escape_string($conn, $filter_date_end) . "'";
}

if (!empty($filter_status)) {
    $sql .= " AND products.status = '" . mysqli_real_escape_string($conn, $filter_status) . "'";
}

// Execute the query
$result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Stocks</title> 
    <link rel="stylesheet" href="../assets/css/store/stock.css"> 
    
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <h2>Inventory Management System</h2>
        </div>
        <div class="item"> 
            <ul>
                <li class="active"><img src="../assets/img/dash.png" style="height: 1em; vertical-align: middle;">&nbsp;<a href="dashboard.php">Dashboard</a></li>
                <li><img src="../assets/img/products.png" style="height: 1em; vertical-align: middle;">&nbsp;<a href="product.php">Products</a></li>
                <li><img src="../assets/img/stocks.png" style="height: 1em; vertical-align: middle;">&nbsp;<a href="stock.php">Stocks</a></li>
                <li><img src="../assets/img/category.png" style="height: 1em; vertical-align: middle;">&nbsp;<a href="category.php">Category</a></li>
                <li><img src="../assets/img/reports.png" style="height: 1em; vertical-align: middle;">&nbsp;<a href="reports.php">Reports</a></li>
                <li><img src="../assets/img/users.png" style="height: 1em; vertical-align: middle;">&nbsp;<a href="users.php">Users</a></li>
            </ul>
        </div>
        <div class="logout">
            <a href="../auth/logout.php">Logout</a>&nbsp;
            <img src="../assets/img/logout.png" style="height: 1.5em; vertical-align: middle;"> 
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="title"> 
            <img src="../assets/img/stocks.png" style="height: 1.5em; vertical-align: middle;">&nbsp;&nbsp;;&nbsp;;&nbsp;<h1>Stocks</h1>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="">
            <div class="total-box">
                <div class="boxs">
                    <!-- Category Filter -->
                    <div class="box-info">
                        <label for="category">Category</label>
                        <select name="category_id" id="category">
                            <option value="">-- Select Category --</option>
                            <?php
                            $cat_query = "SELECT id, categoryname FROM category";
                            $cat_result = mysqli_query($conn, $cat_query);
                            while ($cat = mysqli_fetch_assoc($cat_result)) {
                                $selected = ($cat['id'] == $filter_category) ? 'selected' : '';
                                echo "<option value='{$cat['id']}' $selected>" . htmlspecialchars($cat['categoryname']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div class="box-info">
                        <label for="filter_date">Start Date</label>
                        <input type="date" name="filter_date_start" id="filter_date" value="<?php echo htmlspecialchars($filter_date_start); ?>">
                    </div>
                    <div class="box-info">
                        <label for="filter_date">End Date</label>
                        <input type="date" name="filter_date_end" id="filter_date" value="<?php echo htmlspecialchars($filter_date_end); ?>">
                    </div>

                    <!-- Status Filter -->
                    <div class="box-info">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="">-- Select Status --</option>
                            <?php
                            $statuses = ['In Stock', 'Low Stock', 'Out of Stock'];
                            foreach ($statuses as $status) {
                                $selected = ($status == $filter_status) ? 'selected' : '';
                                echo "<option value=\"$status\" $selected>$status</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Submit -->
                    <div class="box-info"> 
                        <label>&nbsp;</label>
                        <button type="submit" class="btn-submit">Add Filter</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Products Table -->
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Stock Lvl</th>
                        <th>Stock Status</th>
                        <th>Expire Date</th>
                        <th>Last Updated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                <td>" . htmlspecialchars($row['id']) . "</td>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['categoryname']) . "</td>
                                <td>" . htmlspecialchars($row['quantity']) . "</td>
                                <td>" . htmlspecialchars($row['status']) . "</td>
                                <td>" . htmlspecialchars($row['expiredate']) . "</td>
                                <td>" . htmlspecialchars($row['last_updated']) . "</td>
                                <td>
                                    <form action='../actions/stock-update.php' method='GET' style='display: inline;'>
                                        <input type='hidden' name='supdateid' value='" . $row['id'] . "'>
                                        <button type='submit' class='stock-update-button'>Update Stock</button>
                                    </form>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No products found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
