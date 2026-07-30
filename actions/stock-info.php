<?php
// Database connection
require __DIR__ . '/../config/db.php';

// Check if 'name' is passed in the URL
if (!isset($_GET['name']) || empty(trim($_GET['name']))) {
    exit("Product name not provided.");
}

// Sanitize the input
$productName = trim($_GET['name']);

// Query the stock table by name
$stmt = $conn->prepare("SELECT name, price, quantity, expiredate, category_name, status, last_updated FROM stock WHERE name = ?");
$stmt->bind_param("s", $productName);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    exit("Product not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stock Info</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 50px;
            background: #ffffff;
            color: #000000;
        }
        h2 {
            color: #102830;
        }
        .card {
            border: 1px solid #ccc;
            padding: 20px;
            max-width: 400px;
            background: #102830;
            color: white;
        }
        .btn {
            background: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            text-decoration: none;
            margin-top: 20px;
            cursor: pointer;
        }
        .btn:hover {
            background: rgb(107, 219, 111);
        }
    </style>
</head>
<body>

<h2>Stock Details: <?= htmlspecialchars($product['name']) ?></h2>

<div class="card">
    <p><strong>Product Name:</strong> <?= htmlspecialchars($product['name']) ?></p>
    <p><strong>Product Price:</strong> <?= htmlspecialchars($product['price']) ?></p>
    <p><strong>Product Quantity:</strong> <?= htmlspecialchars($product['quantity']) ?></p>
    <p><strong>Expire Date:</strong> <?= htmlspecialchars($product['expiredate']) ?></p>
    <p><strong>Category:</strong> <?= htmlspecialchars($product['category_name']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($product['status']) ?></p>
    <p><strong>Last Updated:</strong> <?= htmlspecialchars($product['last_updated']) ?></p>

    <button class="btn" onclick="history.back();">Back</button>
</div>

</body>
</html>
