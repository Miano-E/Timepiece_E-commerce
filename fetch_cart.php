<?php
include("connect.php");
session_start();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];

if (empty($username)) {
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

// Fetch cart items for the user
$sql = "SELECT product_name, price, image, quantity FROM cart WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(['cart' => $cart]);
?>
