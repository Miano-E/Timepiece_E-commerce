<?php
include("connect.php");
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$cart = $data['cart'];
$username = $data['username'];

// Fetch user ID from the database
$sql = "SELECT id FROM ecommerce_table WHERE username = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

// Clear existing cart items for the user
$sql = "DELETE FROM cart WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Insert new cart items
foreach ($cart as $item) {
    $sql = "INSERT INTO cart (user_id, product_name, price, image, quantity) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("isisi", $user_id, $item['product_name'], $item['price'], $item['image'], $item['quantity']);
    $stmt->execute();
}

echo json_encode(['status' => 'success']);
?>
