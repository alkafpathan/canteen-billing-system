<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: login.php");
include "db.php";
include "navbar.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Canteen Billing</title>
<style>
body { margin:0; font-family:Poppins,sans-serif; background:#f2f6fa; }
.main { display:flex; padding:20px; gap:20px; }
.cartSection { width:32%; background:#fff; padding:20px; box-shadow:2px 0 10px rgba(0,0,0,0.1); border-radius:10px; display:flex; flex-direction:column; max-height:80vh; overflow-y:auto; }
.menuSection { width:68%; padding:20px; background:#fff; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1); max-height:80vh; overflow-y:auto; }
.searchBox input { width:100%; padding:12px; border:1px solid #aaa; border-radius:8px; font-size:15px; margin-bottom:15px; }
.menuItem { background:#f9f9f9; padding:14px; margin:10px 0; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.08); display:flex; justify-content:space-between; align-items:center; cursor:pointer; font-size:16px; }
.menuItem img { width:50px; height:50px; object-fit:cover; border-radius:6px; margin-right:10px; }
.menuItem:hover { background:#e6f0fa; }
table { width:100%; border-collapse:collapse; margin-top:10px; }
th, td { padding:10px; border-bottom:1px solid #ddd; text-align:center; }
th { background:#203a43; color:white; }
.total { font-size:18px; font-weight:600; text-align:right; margin-top:10px; color:#203a43; }
.btn { background:#203a43; color:#fff; border:none; padding:12px 18px; border-radius:6px; cursor:pointer; font-size:15px; margin-top:10px; }
.btn:hover { background:#2c5364; }
.cart-controls button { padding:4px 8px; margin:0 2px; font-size:14px; border:none; border-radius:4px; cursor:pointer; }
.cart-controls button:hover { background:#2c5364; color:white; }
</style>
</head>
<body onload="loadItems()">

<div class="main">
    <!-- CART (LEFT) -->
    <div class="cartSection">
        <h2>Cart</h2>
        <table>
            <thead>
                <tr><th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th><th>Actions</th></tr>
            </thead>
            <tbody id="cartBody"></tbody>
        </table>
        <div class="total">Total: <span id="total">₹0.00</span></div>
        <form method="POST" action="generate_bill.php" onsubmit="return validateCart();">
            <input type="hidden" name="total" id="billTotal">
            <input type="hidden" name="items" id="itemsInput">
            <button class="btn" type="submit">Generate Bill</button>
        </form>
    </div>

    <!-- MENU ITEMS (RIGHT) -->
    <div class="menuSection">
        <div class="searchBox">
            <input type="text" id="search" placeholder="Search menu items..." onkeyup="loadItems()">
        </div>
        <h2>Menu Items</h2>
        <div id="menuItems"></div>
    </div>
</div>

<script>
let cart = [];

// Add item from menu
function addToCart(id, name, price){
    let item = cart.find(i => i.id === id);
    if(item){
        item.qty++;
        item.subtotal = item.qty * price;
    } else {
        cart.push({id, name, price, qty:1, subtotal:price});
    }
    renderCart();
}

// Cart quantity controls
function changeQty(id, change){
    let item = cart.find(i => i.id === id);
    if(!item) return;
    item.qty += change;
    if(item.qty <= 0){
        cart = cart.filter(i => i.id !== id);
    } else {
        item.subtotal = item.qty * item.price;
    }
    renderCart();
}

function removeItem(id){
    cart = cart.filter(i => i.id !== id);
    renderCart();
}

// Render cart table
function renderCart(){
    let rows = "";
    let total = 0;
    cart.forEach(i => {
        total += i.subtotal;
        rows += `<tr>
            <td>${i.name}</td>
            <td>₹${i.price}</td>
            <td>${i.qty}</td>
            <td>₹${i.subtotal}</td>
            <td class="cart-controls">
                <button type="button" onclick="changeQty(${i.id},1)">+</button>
                <button type="button" onclick="changeQty(${i.id},-1)">-</button>
                <button type="button" onclick="removeItem(${i.id})">🗑</button>
            </td>
        </tr>`;
    });
    document.getElementById("cartBody").innerHTML = rows;
    document.getElementById("total").innerText = "₹" + total.toFixed(2);
    document.getElementById("billTotal").value = total;
    document.getElementById("itemsInput").value = JSON.stringify(cart);
}

// Validate before generating bill
function validateCart(){
    if(cart.length === 0){
        alert("Cart is empty! Add at least one item to generate bill.");
        return false;
    }
    return true;
}

// Load menu items dynamically
function loadItems(){
    let q = document.getElementById("search").value;
    fetch("load_items.php?q=" + encodeURIComponent(q))
    .then(r => r.json())
    .then(data => {
        let html = "";
        data.forEach(item => {
            html += `<div class="menuItem" onclick="addToCart(${item.id},'${item.name}',${item.price})">
                        <div style="display:flex;align-items:center;">
                            <img src="images/${item.image}" alt="${item.name}">
                            <span>${item.name} (${item.category})</span>
                        </div>
                        <div>₹${item.price}</div>
                     </div>`;
        });
        document.getElementById("menuItems").innerHTML = html;
    });
}
</script>

</body>
</html>
