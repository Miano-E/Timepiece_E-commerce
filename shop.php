<?php
include("connection.php");

session_start();

    // Function to fetch products by category
    function fetch_products_by_category($conn, $category_id, $limit = 8) {
        $stmt = $conn->prepare("
            SELECT p.id, p.name, p.price, pi.imagepath 
            FROM products p 
            LEFT JOIN (
                SELECT product_id, MIN(imagepath) as imagepath 
                FROM products_images 
                GROUP BY product_id
            ) pi ON p.id = pi.product_id
            WHERE p.category_id = ?
            LIMIT ?");
        $stmt->bind_param("ii", $category_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        $stmt->close();
        return $products;
    }

// Fetch products for each category
$watchs = fetch_products_by_category($conn, 1);
$light_solutions= fetch_products_by_category($conn, 2);
$furniture = fetch_products_by_category($conn, 3);

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

    <header>
        <a href="index.php" class="logo">Time<span>Piece</span></a>
        <div class="hamburger" id="hamburger">
            &#9776;
        </div>
        <nav id="nav-menu">
            <div class="close" id="close">
                &times;
            </div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php" class="active">Shop</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['username'])) { ?>
                    <li><a href="logout.php" class="login">Logout</a></li>
                <?php } else { ?>
                    <li><a href="login.php" class="login">Login</a></li>
                <?php } ?>
            </ul>
        </nav>
        <div class="cart-container">
            <a href="#" class="cart-icon" id="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-quantity" id="cart-quantity">0</span>
            </a>
        </div>
    </header>

    <section id="watch-collection">
        <h2 class="text-center">Explore Our Watch Collection.</h2>
        <div class="container">
            <div class="product-grid">
                <?php foreach ($watchs as $product): ?>
                    <div class="card">
                        <a href="product_details.php?id=<?php echo htmlspecialchars($product['id']); ?>">
                            <img src="<?php echo htmlspecialchars($product['imagepath']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="card-image">
                        </a>
                        <div class="card-content">
                            <h2 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h2>
                            <p class="card-price">Ksh <?php echo htmlspecialchars($product['price']); ?></p>
                            <button class="add-to-cart-btn">Add To Cart</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="light-solutions-collection">
        <h2 class="text-center">Explore Our Light Soulutions Collection.</h2>
        <div class="container">
            <div class="product-grid">
                <?php foreach ($light_solutions as $product): ?>
                    <div class="card">
                        <a href="product_details.php?id=<?php echo htmlspecialchars($product['id']); ?>">
                            <img src="<?php echo htmlspecialchars($product['imagepath']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="card-image">
                        </a>
                        <div class="card-content">
                            <h2 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h2>
                            <p class="card-price">Ksh <?php echo htmlspecialchars($product['price']); ?></p>
                            <button class="add-to-cart-btn">Add To Cart</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="furniture-collection">
        <h2 class="text-center">Explore Our Furniture Collection.</h2>
        <div class="container">
            <div class="product-grid">
                <?php foreach ($furniture as $product): ?>
                    <div class="card">
                        <a href="product_details.php?id=<?php echo htmlspecialchars($product['id']); ?>">
                            <img src="<?php echo htmlspecialchars($product['imagepath']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="card-image">
                        </a>
                        <div class="card-content">
                            <h2 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h2>
                            <p class="card-price">Ksh <?php echo htmlspecialchars($product['price']); ?></p>
                            <button class="add-to-cart-btn">Add To Cart</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

     <!-- Notification container -->
     <div id="notification-container"></div>

      <!-- Cart Sidebar -->
      <div id="cart-sidebar">
            <div class="sidebar-header">
                <span class="close-sidebar" id="close-sidebar">&times;</span>
                <h3 class="text-center cart-title">Your Cart</h3>
            </div>
            <div class="sidebar-content" id="sidebar-content">
                <!-- Cart items will be added here -->
            </div>
            <div class="sidebar-footer">
                <p>Total: <span id="cart-total">Ksh 0</span></p>
                <div class="btns">
                    <a href="cart.php" class="btn">View Cart</a>
                    <a href="check.php" class="btn">Checkout</a>
                </div>
            </div>
        </div>

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

        function addProductToCart(product_name, price, image) {
            const existingProduct = cart.find(product => product.product_name === product_name);
            if (existingProduct) {
                existingProduct.quantity += 1;
            } else {
                cart.push({ product_name, price, image, quantity: 1 });
            }
            totalAmount += price;
            document.getElementById('cart-quantity').textContent = parseInt(document.getElementById('cart-quantity').textContent) + 1;
            localStorage.setItem(getCartKey(username), JSON.stringify(cart));

            if (username) {
                fetch('update_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ cart, username })
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
            const cartTotal = document.getElementById('cart-total');
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
                const productImage = card.querySelector('.card-image').src;

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

        updateCartSidebar();
    });
</script>

<footer>
    &copy; 2024 TimePiece. All Rights Reserved. 
</footer>    

</body>
</html>