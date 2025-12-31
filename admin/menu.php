<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}
include "../db.php";
include "../navbar.php";

// Handle add item
if(isset($_POST['add'])){
    $name = $_POST['item_name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Image upload
    $image = "default.png";
    if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
        $imgName = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../images/$imgName");
        $image = $imgName;
    }

    $stmt = $conn->prepare("INSERT INTO menu_items (item_name, price, category, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $price, $category, $image);
    $stmt->execute();
}

// Handle delete item
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $imgFile = $conn->query("SELECT image FROM menu_items WHERE id=$id")->fetch_assoc()['image'];
    if($imgFile && $imgFile != "default.png" && file_exists("../images/$imgFile")){
        unlink("../images/$imgFile");
    }
    $conn->query("DELETE FROM menu_items WHERE id=$id");
}

$items = $conn->query("SELECT * FROM menu_items ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Menu Management</title>
<style>
body {
    background:#eef2f6;
    font-family:Poppins, sans-serif;
    margin:0;
    padding:0;
}

.container {
    max-width:1000px;
    margin:auto;
    padding:30px 20px;
}

/* HEADER */
h2 {
    color:#203a43;
    margin-bottom:20px;
}

/* FORM STYLING */
form {
    display:flex;
    flex-wrap:wrap;
    gap:15px;
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
    margin-bottom:25px;
}
form input, form select {
    flex:1 1 200px;
    padding:12px;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:15px;
}
form button {
    background:#203a43;
    color:#fff;
    border:none;
    padding:12px 20px;
    border-radius:6px;
    cursor:pointer;
    font-size:16px;
    transition:0.3s;
}
form button:hover {
    background:#2c5364;
}

/* TABLE STYLING */
.tableWrapper {
    max-height:500px;
    overflow-y:auto;
    background:#fff;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
table {
    width:100%;
    border-collapse:collapse;
    min-width:600px;
}
th, td {
    padding:12px;
    text-align:left;
    border-bottom:1px solid #eee;
    font-size:15px;
}
th {
    background:#203a43;
    color:white;
    position:sticky;
    top:0;
}
td img {
    width:60px;
    height:60px;
    border-radius:8px;
    object-fit:cover;
}
a.delete {
    color:red;
    text-decoration:none;
    font-weight:bold;
}
a.delete:hover {
    text-decoration:underline;
}

/* Responsive */
@media(max-width:768px){
    form { flex-direction:column; }
    table { font-size:14px; }
}
</style>
</head>
<body>

<div class="container">
    <h2>Manage Menu Items</h2>

    <!-- ADD ITEM FORM -->
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="item_name" placeholder="Item Name" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <input type="text" name="category" placeholder="Category" required>
        <input type="file" name="image">
        <button type="submit" name="add">Add Item</button>
    </form>

    <!-- MENU ITEMS TABLE -->
    <div class="tableWrapper">
    <table>
        <thead>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $items->fetch_assoc()){ ?>
        <tr>
            <td><img src="../images/<?php echo $row['image']; ?>" alt=""></td>
            <td><?php echo $row['item_name']; ?></td>
            <td>₹<?php echo $row['price']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td>
                <a href="menu.php?delete=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Delete this item?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    </div>
</div>

</body>
</html>
