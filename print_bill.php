<?php
session_start();
include "db.php";

if(!isset($_GET['bill_no'])){
    die("Bill not found!");
}

$bill_no = $_GET['bill_no'];

// Fetch bill details
$bill = $conn->query("SELECT * FROM bills WHERE bill_no='$bill_no'")->fetch_assoc();

// Fetch bill items
$items = $conn->query("SELECT * FROM bill_items WHERE bill_no='$bill_no'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Print Bill</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f2f6fa;
        padding: 20px;
    }

    .billBox {
        max-width: 500px;
        margin: 30px auto;`
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .billHeader {
        text-align: center;
        font-size: 22px;
        font-weight: 700;
        color: #203a43;
        margin-bottom: 10px;
    }

    .logo {
        text-align:center;
        margin-bottom: 15px;
    }
    .logo img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
    }

    .billInfo {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin-bottom: 15px;
        color:#555;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }

    th, td {
        padding: 8px 6px;
        border-bottom: 1px solid #ddd;
        text-align: left;
        font-size: 14px;
    }

    th {
        background: #203a43;
        color: white;
        font-weight: 600;
        text-align: center;
    }

    td img {
        width: 45px;
        height: 45px;
        border-radius: 6px;
        object-fit: cover;
        border:1px solid #ccc;
    }

    .total {
        font-size: 18px;
        font-weight: 700;
        text-align: right;
        color: #203a43;
        margin-bottom: 5px;
    }

    .cashier {
        font-size: 14px;
        text-align: right;
        color: #2c5364;
        margin-bottom: 15px;
    }

    .printBtn {
        width: 100%;
        background: #203a43;
        border: none;
        padding: 12px;
        color: white;
        border-radius: 6px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        transition: 0.3s;
    }
    .printBtn:hover {
        background: #2c5364;
    }

    /* Print style */
    @media print {
        body { background: #fff; }
        .printBtn, .navbar { display: none; }
        .billBox { box-shadow: none; }
    }
</style>
</head>
<body>

<div class="billBox">
    <div class="logo">
        <!-- Add your logo image if you have -->
        <img src="images/logo.png" alt="Canteen Logo">
    </div>

    <div class="billHeader">🧾 Canteen Bill</div>

    <div class="billInfo">
        <span><strong>Bill No:</strong> <?php echo $bill['bill_no']; ?></span>
        <span><strong>Date:</strong> <?php echo $bill['bill_date']; ?></span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $items->fetch_assoc()){ 
            $menuItem = $conn->query("SELECT image FROM menu_items WHERE item_name='{$row['item_name']}'")->fetch_assoc();
            $file = $menuItem['image'] ?? "default.png";
        ?>
            <tr>
                <td><img src="images/<?php echo $file; ?>" alt="<?php echo $row['item_name']; ?>"></td>
                <td><?php echo $row['item_name']; ?></td>
                <td>₹<?php echo $row['price']; ?></td>
                <td><?php echo $row['qty']; ?></td>
                <td>₹<?php echo $row['subtotal']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div class="total">Total: ₹<?php echo $bill['total']; ?></div>
    <div class="cashier">Cashier: <?php echo $bill['cashier']; ?></div>

    <button class="printBtn" onclick="window.print()">🖨 Print Bill</button>
</div>

</body>
</html>
