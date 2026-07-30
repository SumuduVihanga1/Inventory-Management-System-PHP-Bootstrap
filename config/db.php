<?php
$conn = mysqli_connect("localhost", "root", "", "stock-management-system");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>