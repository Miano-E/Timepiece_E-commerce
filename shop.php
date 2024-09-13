<?php
include 'includes/header.php';
include("connection.php");



// Function to convert images to WebP format
function convertToWebP($source, $destination, $quality = 80)
{
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    } else {
        return false;
    }
    return imagewebp($image, $destination, $quality);
}

// Function to fetch products with pagination and category filtering
function fetch_products($conn, $category_id = null, $limit = 10, $offset = 0)
{
    $query = "SELECT p.id, p.name, p.price, pi.imagepath, c.name as category 
              FROM products p
              LEFT JOIN (
                  SELECT product_id, MIN(imagepath) AS imagepath
                  FROM products_images
                  GROUP BY product_id
              ) pi ON p.id = pi.product_id
              JOIN categories c ON p.category_id = c.id";

    // Add category filter if it's not 'all'
    if ($category_id) {
        $query .= " WHERE p.category_id = ?";
    }

    $query .= " LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($query);

    if ($category_id) {
        $stmt->bind_param("iii", $category_id, $limit, $offset);
    } else {
        $stmt->bind_param("ii", $limit, $offset);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to get total product count
function get_total_products($conn, $category_id = null)
{
    $query = "SELECT COUNT(*) as total FROM products";

    if ($category_id) {
        $query .= " WHERE category_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $category_id);
    } else {
        $stmt = $conn->prepare($query);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['total'];
}

// Get current page and category filter from request
$category_id = isset($_GET['category']) && $_GET['category'] !== 'all' ? intval($_GET['category']) : null;
$productsPerPage = 12;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $productsPerPage;

// Fetch products for current page
$products = fetch_products($conn, $category_id, $productsPerPage, $offset);

// Get total number of products for pagination
$totalProducts = get_total_products($conn, $category_id);
$totalPages = ceil($totalProducts / $productsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container">
        <div class="filter-section">
            <label for="category-filter" class="filter-label">Filter</label>
            <select name="category" id="category-filter" onchange="filterByCategory()">
                <option value="all" <?php echo is_null($category_id) ? 'selected' : ''; ?>>All</option>
                <option value="1" <?php echo $category_id == 1 ? 'selected' : ''; ?>>Watches</option>
                <option value="2" <?php echo $category_id == 2 ? 'selected' : ''; ?>>Lighting Solutions</option>
                <option value="3" <?php echo $category_id == 3 ? 'selected' : ''; ?>>Furniture</option>
            </select>
        </div>
    </div>


    <section class="products-section">
        <div class="container">
            <div class="product-grid" id="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="card product-card" data-category="<?php echo htmlspecialchars($product['category']); ?>">
                        <a href="product_details.php?id=<?php echo htmlspecialchars($product['id']); ?>">
                            <img src="<?php echo htmlspecialchars($product['imagepath']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="card-image" loading="lazy">
                        </a>
                        <div class="card-content">
                            <h4 class="card-category"><?php echo htmlspecialchars($product['category']); ?></h4>
                            <h2 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h2>
                            <p class="card-price">Ksh <?php echo number_format($product['price'], 2); ?></p>
                            <button class="add-to-cart-btn" onclick="addProductToCart('<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>, '<?php echo $product['imagepath']; ?>')"><span class="btn-text">Add to Cart</span></button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="shop.php?page=<?php echo $currentPage - 1; ?>&category=<?php echo isset($category_id) ? $category_id : 'all'; ?>" class="pagination-arrow">&laquo; Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="shop.php?page=<?php echo $i; ?>&category=<?php echo isset($category_id) ? $category_id : 'all'; ?>" class="pagination-link <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="shop.php?page=<?php echo $currentPage + 1; ?>&category=<?php echo isset($category_id) ? $category_id : 'all'; ?>" class="pagination-arrow">Next &raquo;</a>
        <?php endif; ?>
    </div>

    <script>
        // JavaScript to handle category change
        document.getElementById('category-filter').addEventListener('change', function() {
            const selectedCategory = this.value;
            window.location.href = `shop.php?category=${selectedCategory}`;
        });
    </script>

    <?php include 'includes/footer.php'; ?>

</body>
</html>