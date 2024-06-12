<?php
include("connection.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Update product details in the products table
    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ? WHERE id = ?");
    $stmt->bind_param("ssdii", $name, $description, $price, $category_id, $product_id);
    $stmt->execute();
    $stmt->close();

    // Handle image uploads
    $upload_dir = 'uploaded-imgs/';
    foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['image']['error'][$key] == UPLOAD_ERR_OK && move_uploaded_file($tmp_name, $upload_dir . basename($_FILES['image']['name'][$key]))) {
            $imagepath = $upload_dir . basename($_FILES['image']['name'][$key]);

            // Insert image path into the products_images table
            $stmt = $conn->prepare("INSERT INTO products_images (product_id, imagepath) VALUES (?, ?)");
            $stmt->bind_param("is", $product_id, $imagepath);
            $stmt->execute();
            $stmt->close();
        }
    }

    echo '<script>alert("Product updated successfully!"); window.location.href="manage-products.php";</script>';
}

$conn->close();
?>
