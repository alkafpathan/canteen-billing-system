<?php
$conn = new mysqli("localhost", "root", "", "canteen_db");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// Generate bcrypt hash
$admin_password = password_hash("admin123", PASSWORD_BCRYPT);
$cashier_password = password_hash("cashier123", PASSWORD_BCRYPT);

echo "Admin Hash: ".$admin_password."<br>";
echo "Cashier Hash: ".$cashier_password."<br>";

// Insert users
$conn->query("INSERT INTO users (name, username, password, role) VALUES 
('Admin','admin1','$admin_password','admin')");

$conn->query("INSERT INTO users (name, username, password, role) VALUES 
('Cashier','cashier1','$cashier_password','cashier')");

echo "Users created successfully!";
?>
