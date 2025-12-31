<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}
include "../db.php";
include "../navbar.php";

// Fetch counts
$totalItems = $conn->query("SELECT COUNT(*) as count FROM menu_items")->fetch_assoc()['count'];
$totalBills = $conn->query("SELECT COUNT(*) as count FROM bills")->fetch_assoc()['count'];
$totalSales = $conn->query("SELECT SUM(total) as sum FROM bills")->fetch_assoc()['sum'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<style>
    body { background:#f4f7fc; font-family:Poppins,sans-serif; }
    .container { max-width:900px; margin:auto; padding:20px; }
    .cards { display:flex; justify-content:space-between; flex-wrap:wrap; margin-top:20px; }
    .card {
        flex:1;
        background:#fff;
        margin:10px;
        padding:20px;
        border-radius:12px;
        box-shadow:0 4px 14px rgba(0,0,0,0.1);
        text-align:center;
        transition:0.3s;
    }
    .card:hover { box-shadow:0 6px 20px rgba(0,0,0,0.18); }
    .card h3 { margin:0; font-size:16px; color:#555; }
    .card p { font-size:28px; font-weight:600; color:#203a43; margin-top:10px; }
</style>
</head>
<body>

<div class="container">
    <h2>Welcome, <?php echo $_SESSION['user']; ?>!</h2>

    <div class="cards">
        <div class="card">
            <h3>Total Menu Items</h3>
            <p><?php echo $totalItems; ?></p>
        </div>
        <div class="card">
            <h3>Total Bills</h3>
            <p><?php echo $totalBills; ?></p>
        </div>
        <div class="card">
            <h3>Total Sales</h3>
            <p>₹<?php echo $totalSales ? $totalSales : 0; ?></p>
        </div>
    </div>
</div>

</body>
</html>
