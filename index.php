<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

include "db.php";
include "navbar.php";

$role = $_SESSION['role'];
$username = $_SESSION['user'];

// Fetch some stats for dashboard
$totalOrders = $conn->query("SELECT COUNT(*) as cnt FROM orders")->fetch_assoc()['cnt'];
$totalSales = $conn->query("SELECT SUM(total) as sum FROM bills")->fetch_assoc()['sum'];
$popularItem = $conn->query("SELECT item_name, COUNT(*) as cnt FROM order_items GROUP BY item_name ORDER BY cnt DESC LIMIT 1")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Canteen Dashboard</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body {
    font-family: Poppins, sans-serif;
    background: #f2f6fa;
    margin: 0;
    padding: 0;
}
.container {
    max-width: 1100px;
    margin: auto;
    padding: 30px 20px;
}

/* Header */
h1 {
    color: #203a43;
    margin-bottom: 30px;
}

/* Dashboard cards */
.cards {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}
.card {
    flex: 1 1 250px;
    background: #fff;
    padding: 25px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-decoration: none;
    color: #203a43;
    font-weight: 600;
    transition: 0.3s;
    position: relative;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.15);
}
.card h2 {
    margin-top: 0;
    margin-bottom: 10px;
    font-size: 22px;
}
.card p {
    font-size: 15px;
    color: #555;
}
.card i {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 30px;
    color: #2c5364;
}

/* Stats boxes */
.stats {
    display: flex;
    gap: 20px;
    margin-top: 40px;
    flex-wrap: wrap;
    justify-content: center;
}
.stat-box {
    flex: 1 1 200px;
    background: #fff;
    padding: 20px 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: center;
}
.stat-box h3 {
    margin: 0;
    color: #203a43;
    font-size: 20px;
}
.stat-box p {
    margin-top: 8px;
    font-size: 14px;
    color: #555;
}

/* Responsive */
@media(max-width:768px){
    .cards, .stats {
        flex-direction: column;
        gap: 15px;
    }
}
</style>
</head>
<body>
<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>

    <div class="cards">
        <!-- Cashier / Billing -->
        <a href="billing.php" class="card">
            <i class="fas fa-cash-register"></i>
            <h2>Billing</h2>
            <p>Create bills and manage orders</p>
        </a>

        <!-- Admin Features -->
        <?php if($role == 'admin'){ ?>
        <a href="admin/menu.php" class="card">
            <i class="fas fa-utensils"></i>
            <h2>Menu Management</h2>
            <p>Add, edit, or delete menu items</p>
        </a>

        <a href="admin/bills.php" class="card">
            <i class="fas fa-file-invoice-dollar"></i>
            <h2>Bill History</h2>
            <p>View all bills and sales reports</p>
        </a>
        <?php } ?>
    </div>

    <!-- Stats -->
    <div class="stats">
        <div class="stat-box">
            <h3><?php echo $totalOrders ?: 0; ?></h3>
            <p>Total Orders</p>
        </div>
        <div class="stat-box">
            <h3>₹<?php echo $totalSales ?: 0; ?></h3>
            <p>Total Sales</p>
        </div>
        <div class="stat-box">
            <h3><?php echo $popularItem['item_name'] ?? "N/A"; ?></h3>
            <p>Most Popular Item</p>
        </div>
    </div>
</div>
</body>
</html>
