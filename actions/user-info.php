<?php
// Start session if needed (optional here)
session_start();

// Validate and sanitize the infoid parameter
if (!isset($_GET['infoid']) || !is_numeric($_GET['infoid'])) {
    exit("Invalid user ID.");
}

$infoId = (int)$_GET['infoid'];

// Database connection
require __DIR__ . '/../config/db.php';

// Prepare and execute the query
$stmt = $conn->prepare("SELECT name, email, role, last_login FROM users WHERE id = ?");
$stmt->bind_param("i", $infoId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    exit("User not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Info</title>
    <style>
        body { font-family: sans-serif; margin: 50px; background: #024751; color: #fff; }
        h2 { color:rgb(255, 255, 255); }
        .card {  padding: 20px; max-width: 400px; background: #102830; }
        .btn { background: #4CAF50; color: white; padding: 10px 15px; border: none; margin-top: 20px; cursor: pointer; }
        .btn:hover { background: rgb(107, 219, 111); }
    </style>
</head>
<body>

<h2>User: <?= htmlspecialchars($user['name']) ?></h2>

<div class="card">
    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
    
    <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
    

    <button class="btn" onclick="history.back();">Back</button>
</div>

</body>
</html>
