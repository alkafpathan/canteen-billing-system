<?php
include "db.php";

$q = isset($_GET['q']) ? $_GET['q'] : '';

// If searching by category or name
if($q != ''){
    $stmt = $conn->prepare("SELECT * FROM menu_items WHERE category LIKE ? OR item_name LIKE ? ORDER BY id DESC");
    $like = "%$q%";
    $stmt->bind_param("ss", $like, $like);
} else {
    $stmt = $conn->prepare("SELECT * FROM menu_items ORDER BY id DESC");
}

$stmt->execute();
$result = $stmt->get_result();

$items = [];
while($row = $result->fetch_assoc()){
    $items[] = [
        "id" => (int)$row['id'],
        "name" => $row['item_name'],
        "price" => (float)$row['price'],
        "image" => $row['image'],
        "category" => $row['category']
    ];
}

// Return JSON
header('Content-Type: application/json');
echo json_encode($items);
