<?php
include("connection.php");


// Fetch all products with category and image information
$sql = "
    SELECT p.id, p.name, p.price, c.name as category_name, pi.imagepath 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    LEFT JOIN (
        SELECT product_id, MIN(imagepath) as imagepath 
        FROM products_images 
        GROUP BY product_id
    ) pi ON p.id = pi.product_id
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table, th, td {
            border: 1px solid #ddd;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
        }
        
        th {
            background-color: #f4f4f4;
            color: #333;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tr:hover {
            background-color: #f1f1f1;
        }
        
        .card-image {
            max-width: 100px;
            height: auto;
        }
        
        a {
            color: #007bff;
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Manage Products</h1>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($row['imagepath']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="card-image"></td>
                        <td><a href="edit-product.php?id=<?php echo htmlspecialchars($row['id']); ?>">Edit</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <a href="edit-product.php"> Edit Products</a>
    <a href="add-product.php">Add a product</a>
</body>
</html>

<?php
$conn->close();
?>


