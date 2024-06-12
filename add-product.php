<?php
include("connection.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Insert product details into the products table
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, category_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $name, $description, $price, $category_id);
    $stmt->execute();
    $product_id = $stmt->insert_id;
    $stmt->close();

    // Handle image uploads
    $upload_dir = 'uploaded-imgs/';
    foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
        $image_name = basename($_FILES['image']['name'][$key]);
        $target_file = $upload_dir . $image_name;
        if (move_uploaded_file($tmp_name, $target_file)) {
            // Insert image path into the products_images table
            $stmt = $conn->prepare("INSERT INTO products_images (product_id, imagepath) VALUES (?, ?)");
            $stmt->bind_param("is", $product_id, $target_file);
            $stmt->execute();
        }
    }
    echo '<script> alert ("Product added successfully!");</script>';
    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>

<style>
    form {
        background-color: lightskyblue;
        padding: 1em;
    }
</style>
<body>
    <h2>Add New Product</h2>
    <form action="add-product.php" method="post" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required><br>

        <label for="category">Category:</label>
        <select id="category" name="category_id" required>
            <option value="">Select Category</option>
            <?php
            // Fetch categories from the database
            $conn = new mysqli("localhost", "root", "", "timepiece");
            $result = $conn->query("SELECT id, name FROM categories");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
            $conn->close();
            ?>
        </select><br>

        <label for="image">Product Image:</label>
        <input type="file" id="image" name="image[]" multiple required><br>

        <button type="submit">Add Product</button>
    </form>

    <a href="manage-products.php">Manage Products</a>
    <a href="edit-product.php"> Edit Products</a>
    <a href="index.php">Home</a>
</body>
</html>




