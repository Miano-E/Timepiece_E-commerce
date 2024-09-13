<?php
include("connection.php");

// Fetch product details if ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];
    $stmt = $conn->prepare("
        SELECT p.name, p.description, p.price, p.category_id, c.name as category_name 
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE p.id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($name, $description, $price, $category_id, $category_name);
    $stmt->fetch();
    $stmt->close();

    // Fetch product images
    $stmt = $conn->prepare("SELECT imagepath FROM products_images WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($imagepath);
    $images = [];
    while ($stmt->fetch()) {
        $images[] = $imagepath;
    }
    $stmt->close();
}

// Update product details if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Update product details
    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ? WHERE id = ?");
    $stmt->bind_param("ssdii", $name, $description, $price, $category_id, $product_id);
    $stmt->execute();
    $stmt->close();

    // Handle image uploads
    $upload_dir = 'uploaded-imgs/';
    if (!empty($_FILES['image']['tmp_name'][0])) {
        // Delete existing images
        $stmt = $conn->prepare("DELETE FROM products_images WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->close();

        // Insert new images
        foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
            $image_name = basename($_FILES['image']['name'][$key]);
            $target_file = $upload_dir . $image_name;
            if (move_uploaded_file($tmp_name, $target_file)) {
                $stmt = $conn->prepare("INSERT INTO products_images (product_id, imagepath) VALUES (?, ?)");
                $stmt->bind_param("is", $product_id, $target_file);
                $stmt->execute();
            }
        }
        $stmt->close();
    }

    echo '<script> alert ("Product updated successfully!");</script>';
    header("Location: manage-products.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <!-- <link rel="stylesheet" href="css/main.css"> -->
    <style>
        img,
        picture {
            max-width: 100%;
            display: block;
        }

        .card-image {
            padding: .5em;
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <h2>Edit Product</h2>
    <form action="edit-product.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">

        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($description); ?></textarea><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($price); ?>" required><br>


        <label for="category">Category:</label>
        <select id="category" name="category_id" required>
            <option value="">Select Category</option>
            <?php
            include("connection.php");
            $result = $conn->query("SELECT id, name FROM categories");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "' " . ($row['id'] == $category_id ? "selected" : "") . ">" . $row['name'] . "</option>";
            }
            $conn->close();
            ?>
        </select><br>

        <label for="current_image">Current Images:</label>
        <div>
            <?php foreach ($images as $image): ?>
                <img src="<?php echo htmlspecialchars($image); ?>" alt="Product Image" class="card-image">
            <?php endforeach; ?>
        </div><br>

        <label for="image">New Product Images:</label>
        <input type="file" id="image" name="image[]" multiple><br>

        <button type="submit">Update Product</button>
    </form>
</body>
</html>
