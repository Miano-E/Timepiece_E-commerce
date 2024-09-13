<?php
include 'includes/header.php';
include("connection.php");



// Function to convert images to WebP format
function convertToWebP($source, $destination, $quality = 80)
{
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    } else {
        return false;
    }

    return imagewebp($image, $destination, $quality);
}

// Convert and return WebP image path
function getWebPImagePath($imagepath)
{
    $webpPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $imagepath);
    if (!file_exists($webpPath)) {
        convertToWebP($imagepath, $webpPath);
    }
    return $webpPath;
}

// Check if id is set and is a number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details including category name
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
        $images[] = getWebPImagePath($imagepath);
    }
    $stmt->close();

    // Fetch related products from the same category in random order
    $related_products = [];
    $stmt = $conn->prepare("
        SELECT p.id, p.name, p.price, pi.imagepath 
        FROM products p 
        JOIN (SELECT product_id, MIN(imagepath) AS imagepath FROM products_images GROUP BY product_id) pi ON p.id = pi.product_id
        WHERE p.category_id = ? AND p.id != ?
        ORDER BY RAND()
        LIMIT 4");
    $stmt->bind_param("ii", $category_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['imagepath'] = getWebPImagePath($row['imagepath']);
        $related_products[] = $row;
    }
    $stmt->close();

    // If less than 4 related products, fetch additional products
    if (count($related_products) < 4) {
        $placeholders = str_repeat('?,', count($related_products)) . '?';
        $params = array_column($related_products, 'id');
        $params[] = $product_id; // Exclude the current product
        $param_types = str_repeat('i', count($params));

        $stmt = $conn->prepare("
            SELECT p.id, p.name, p.price, pi.imagepath 
            FROM products p 
            JOIN (SELECT product_id, MIN(imagepath) AS imagepath FROM products_images GROUP BY product_id) pi ON p.id = pi.product_id
            WHERE p.id NOT IN ($placeholders)
            ORDER BY RAND()
            LIMIT ?");
        $params[] = 4 - count($related_products); // Number of additional products needed
        $param_types .= 'i';

        $stmt->bind_param($param_types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $row['imagepath'] = getWebPImagePath($row['imagepath']);
            $related_products[] = $row;
        }
        $stmt->close();
    }
} else {
    die("Product ID is missing or invalid.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
</head>
<body>

    <div class="container continue-shopping">
        <a href="shop.php" class="cta-btn"><span class="btn-text">Continue Shopping</span></a>
    </div>

    <section class="product-details-section">
        <div class="container">
            <div class="product-details">
                <?php if (!empty($images)): ?>
                    <img id="product-image" src="<?php echo htmlspecialchars($images[0]); ?>" alt="Product Image" class="product-img">
                <?php endif; ?>
                <div class="product-summary">
                    <p class="product-category"><?php echo htmlspecialchars($category_name); ?></p>
                    <h2 id="product-title" class="card-title product-name"><?php echo htmlspecialchars($name); ?></h2>
                    <p class="price" id="product-price">Ksh <?php echo htmlspecialchars($price); ?></p>
                    <div class="actions">
                        <div class="quantity-selector">
                            <button class="quantity-btn" id="decrement-btn">-</button>
                            <span id="quantity-display">1</span>
                            <button class="quantity-btn" id="increment-btn">+</button>
                        </div>
                        <button class="add-to-cart-btn" id="add-to-cart-button"><span class="btn-text">Add to Cart</span></button>
                    </div>
                    <div class="description">
                        <h2>Description <i class="fa fa-indent"></i></h2>
                        <p id="product-description"><?php echo htmlspecialchars($description); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products Section -->
    <section>
        <div class="container related-products-container">
            <h2 class="related-products-title text-center">Related Products</h2>
            <div class="related-products-grid">
                <?php foreach ($related_products as $related_product): ?>
                    <div class="card">
                        <a href="product_details.php?id=<?php echo htmlspecialchars($related_product['id']); ?>">
                            <img src="<?php echo htmlspecialchars($related_product['imagepath']); ?>" alt="Related Product Image" class="related-product-img">
                        </a>
                        <div class="card-content">
                            <p class="card-category"><?php echo htmlspecialchars($category_name); ?></p>
                            <h3 class="card-title"><?php echo htmlspecialchars($related_product['name']); ?></h3>
                            <p class="card-price">Ksh <?php echo htmlspecialchars($related_product['price']); ?></p>
                            <button class="add-to-cart-btn"><span class="btn-text">Add to Cart</span></button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>



    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Hamburger menu functionality
            document.getElementById('hamburger').addEventListener('click', function() {
                document.getElementById('nav-menu').classList.add('open');
            });

            document.getElementById('close').addEventListener('click', function() {
                document.getElementById('nav-menu').classList.remove('open');
            });

            // Retrieve username from PHP session
            const username = '<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ""; ?>';

            function getCartKey(username) {
                return `cart_${username || 'guest'}`;
            }

            let cart = JSON.parse(localStorage.getItem(getCartKey(username))) || [];
            let totalAmount = cart.reduce((sum, product) => sum + product.price * product.quantity, 0);

            document.getElementById('cart-quantity').textContent = cart.reduce((sum, product) => sum + product.quantity, 0);

            function showNotification(message) {
                const notificationContainer = document.getElementById('notification-container');
                const notification = document.createElement('div');
                notification.className = 'notification show';
                notification.textContent = message;
                notificationContainer.appendChild(notification);

                setTimeout(() => {
                    notification.classList.remove('show');
                    notification.classList.add('hide');
                    setTimeout(() => notification.remove(), 500);
                }, 2000);
            }

            function addProductToCart(product_name, price, image, quantity = 1) {
                const existingProduct = cart.find(product => product.product_name === product_name);
                if (existingProduct) {
                    existingProduct.quantity += quantity;
                } else {
                    cart.push({
                        product_name,
                        price,
                        image,
                        quantity
                    });
                }
                totalAmount += price * quantity;
                document.getElementById('cart-quantity').textContent = parseInt(document.getElementById('cart-quantity').textContent) + quantity;
                localStorage.setItem(getCartKey(username), JSON.stringify(cart));

                if (username) {
                    fetch('update_cart.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                cart,
                                username
                            })
                        }).then(response => response.text())
                        .then(text => {
                            console.log('Response text from update_cart.php:', text);
                            const data = JSON.parse(text);
                            console.log('Cart updated in database:', data);
                        }).catch(error => {
                            console.error('Error updating cart in database:', error);
                        });
                }

                updateCartSidebar();
            }

            function updateCartSidebar() {
                const sidebarContent = document.getElementById('sidebar-content');
                const cartTotal = document.getElementById('sidebar-cart-total');
                sidebarContent.innerHTML = '';
                cart.forEach(product => {
                    const productDiv = document.createElement('div');
                    productDiv.className = 'cart-item';
                    productDiv.innerHTML = `
                        <img src="${product.image}" alt="${product.product_name}">
                        <div class="item-details">
                            <span class="item-name">${product.product_name}</span>
                            <span class="item-price">Ksh ${product.price}</span>
                            <span class="item-quantity">Quantity: ${product.quantity}</span>
                        </div>
                    `;
                    sidebarContent.appendChild(productDiv);
                });
                cartTotal.textContent = `Ksh ${totalAmount.toFixed(2)}`;
            }

            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', (event) => {
                    const card = event.target.closest('.card');
                    const productName = card.querySelector('.card-title').textContent;
                    const productPrice = parseFloat(card.querySelector('.card-price').textContent.replace('Ksh ', ''));
                    const productImage = card.querySelector('.related-product-img').src;

                    addProductToCart(productName, productPrice, productImage);
                    showNotification(`${productName} added to cart`);
                });
            });

            document.getElementById('cart-icon').addEventListener('click', () => {
                document.getElementById('cart-sidebar').classList.add('open');
            });

            document.getElementById('close-sidebar').addEventListener('click', () => {
                document.getElementById('cart-sidebar').classList.remove('open');
            });

            // Add event listener for the "Add to Cart" button in the product details page
            document.getElementById('add-to-cart-button').addEventListener('click', () => {
                const productName = document.getElementById('product-title').textContent;
                const productPrice = parseFloat(document.getElementById('product-price').textContent.replace('Ksh ', ''));
                const productImage = document.getElementById('product-image').src;
                const quantity = parseInt(document.getElementById('quantity-display').textContent);

                addProductToCart(productName, productPrice, productImage, quantity);
                showNotification(`${productName} added to cart`);
            });

            // Handle quantity increment and decrement
            const quantityDisplay = document.getElementById('quantity-display');
            let quantity = parseInt(quantityDisplay.textContent);

            document.getElementById('increment-btn').addEventListener('click', () => {
                quantity += 1;
                quantityDisplay.textContent = quantity;
            });

            document.getElementById('decrement-btn').addEventListener('click', () => {
                if (quantity > 1) {
                    quantity -= 1;
                    quantityDisplay.textContent = quantity;
                }
            });

            updateCartSidebar();
        });
    </script>

    <?php include 'includes/footer.php'; ?>

</body>
</html>