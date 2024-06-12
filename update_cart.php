<?php
include("connect.php");
session_start();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$cart = $data['cart'];
$username = $data['username'];

if (empty($cart) || empty($username)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
    exit();
}

// Fetch user ID from the database
$sql = "SELECT id FROM ecommerce_table WHERE username = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit();
}

// Clear existing cart items for the user
$sql = "DELETE FROM cart WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Insert new cart items
foreach ($cart as $item) {
    $product_name = $item['product_name'] ?? null;
    $price = $item['price'] ?? 0;
    $image = $item['image'] ?? '';
    $quantity = $item['quantity'] ?? 1;

    if (!$product_name) {
        echo json_encode(['status' => 'error', 'message' => 'Product name cannot be null']);
        exit();
    }

    $sql = "INSERT INTO cart (user_id, product_name, price, image, quantity) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("isisi", $user_id, $product_name, $price, $image, $quantity);
    $stmt->execute();
}

echo json_encode(['status' => 'success']);
?>
