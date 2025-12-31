<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>
<style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }
    .navbar {
        background: linear-gradient(90deg, #0f2027, #203a43, #2c5364);
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }
    .nav-left a, .nav-right a {
        color: #fff;
        margin-right: 18px;
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        transition: 0.3s;
    }
    .nav-left a:hover, .nav-right a:hover {
        color: #00eaff;
    }
    .brand {
        color: #00eaff;
        font-size: 22px;
        font-weight: 600;
        text-decoration: none;
    }
    .logout {
        background: rgba(255, 77, 77, 0.9);
        padding: 6px 14px;
        border-radius: 6px;
        font-weight: 500;
    }
    .logout:hover {
        background: #ff1a1a;
        color: #fff !important;
    }
</style>

<nav class="navbar">
    <div class="nav-left">
        <a href="/canteen/index.php" class="brand">College Canteen</a>
        <a href="/canteen/billing.php">Billing</a>

        <?php if(isset($_SESSION['role']) && $_SESSION['role']=='admin'){ ?>
            <a href="/canteen/admin/dashboard.php">Dashboard</a>
            <a href="/canteen/admin/menu.php">Menu Items</a>
            <a href="/canteen/admin/bills.php">Bill History</a>
            <a href="/canteen/admin/sales.php">Sales</a>
        <?php } ?>
    </div>

    <div class="nav-right">
        <a href="/canteen/logout.php" class="logout">Logout</a>
    </div>
</nav>
