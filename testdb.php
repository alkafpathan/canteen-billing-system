<?php
$servername = "localhost";
$username = "root";       // your MySQL username
$password = "";           // your MySQL password (default is empty in XAMPP)
$dbname = "canteen_db";  // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Database connected successfully!";
?>
