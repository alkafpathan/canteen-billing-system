<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}
include "../db.php";
include "../navbar.php";

$bills = $conn->query("SELECT * FROM bills ORDER BY bill_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>All Bills</title>
<style>
    body { background:#f4f7fc; font-family:Poppins,sans-serif; }
    .container { max-width:900px; margin:auto; padding:20px; }
    h2 { color:#203a43; }
    table { width:100%; border-collapse:collapse; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 14px rgba(0,0,0,0.1); }
    th, td { padding:10px; text-align:left; border-bottom:1px solid #eee; }
    th { background:#203a43; color:white; }
    a.print { background:#203a43; color:white; padding:6px 12px; border-radius:6px; text-decoration:none; }
    a.print:hover { background:#2c5364; }
</style>
</head>
<body>

<div class="container">
    <h2>All Bills</h2>
    <table>
        <tr>
            <th>Bill No</th>
            <th>Cashier</th>
            <th>Date</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        <?php while($row = $bills->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['bill_no']; ?></td>
            <td><?php echo $row['cashier']; ?></td>
            <td><?php echo $row['bill_date']; ?></td>
            <td>₹<?php echo $row['total']; ?></td>
            <td>
                <a href="../print_bill.php?bill_no=<?php echo $row['bill_no']; ?>" class="print" target="_blank">Print</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
