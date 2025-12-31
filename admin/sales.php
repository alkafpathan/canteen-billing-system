<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}
include "../db.php";
include "../navbar.php";

// Total sales
$totalSales = $conn->query("SELECT SUM(total) as total FROM bills")->fetch_assoc()['total'];

// Total bills
$totalBills = $conn->query("SELECT COUNT(*) as total FROM bills")->fetch_assoc()['total'];

// Daily sales (last 7 days)
$dailySales = $conn->query("
    SELECT DATE(bill_date) as date, SUM(total) as total 
    FROM bills 
    WHERE bill_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(bill_date)
    ORDER BY DATE(bill_date) ASC
");
$daily = [];
while($row = $dailySales->fetch_assoc()){
    $daily[$row['date']] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sales Summary</title>
<style>
    body { background:#f4f7fc; font-family:Poppins,sans-serif; }
    .container { max-width:900px; margin:auto; padding:20px; }
    h2 { color:#203a43; }
    .cards { display:flex; gap:20px; margin-bottom:25px; }
    .card {
        flex:1; background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 14px rgba(0,0,0,0.1); text-align:center;
    }
    .card h3 { margin:0; font-size:16px; color:#555; }
    .card p { font-size:28px; font-weight:600; color:#203a43; margin-top:10px; }
    table { width:100%; border-collapse:collapse; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 14px rgba(0,0,0,0.1); }
    th, td { padding:10px; text-align:left; border-bottom:1px solid #eee; }
    th { background:#203a43; color:white; }
</style>
</head>
<body>

<div class="container">
    <h2>Sales Summary</h2>

    <div class="cards">
        <div class="card">
            <h3>Total Sales</h3>
            <p>₹<?php echo $totalSales ? $totalSales : 0; ?></p>
        </div>
        <div class="card">
            <h3>Total Bills</h3>
            <p><?php echo $totalBills; ?></p>
        </div>
    </div>

    <h3>Daily Sales (Last 7 Days)</h3>
    <table>
        <tr>
            <th>Date</th>
            <th>Total Sales</th>
        </tr>
        <?php 
        if(!empty($daily)){
            foreach($daily as $date => $total){ ?>
                <tr>
                    <td><?php echo $date; ?></td>
                    <td>₹<?php echo $total; ?></td>
                </tr>
        <?php } } else { ?>
            <tr><td colspan="2">No sales data available.</td></tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
