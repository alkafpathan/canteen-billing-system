<?php
session_start();
include "db.php";

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$cashier = $_SESSION['user'];
$bill_no = "BILL-" . time(); 
$total = floatval($_POST['total']);

// Insert bill
$stmt = $conn->prepare("INSERT INTO bills (bill_no, total, cashier) VALUES (?, ?, ?)");
$stmt->bind_param("sds", $bill_no, $total, $cashier);
$stmt->execute();

// Decode cart items
$cart = json_decode($_POST['items'], true);

// Insert bill items
$itemStmt = $conn->prepare("INSERT INTO bill_items (bill_no, item_name, price, qty, subtotal) VALUES (?, ?, ?, ?, ?)");

foreach($cart as $item){
    $name = $item['name'];
    $price = floatval($item['price']);
    $qty = intval($item['qty']);
    $sub = floatval($item['subtotal']);

    $itemStmt->bind_param("ssdii", $bill_no, $name, $price, $qty, $sub);
    $itemStmt->execute();
}

header("Location: print_bill.php?bill_no=$bill_no");
exit();
?>
