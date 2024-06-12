<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">Time<span>Piece</span></a>
        <div class="hamburger" id="hamburger">&#9776;</div>
        <nav id="nav-menu">
            <div class="close" id="close">&times;</div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="about.php" class="active">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['username'])) { ?>
                    <li><a href="logout.php" class="login">Logout</a></li>
                <?php } else { ?>
                    <li><a href="login.php" class="login">Login</a></li>
                <?php } ?>
            </ul>
        </nav>
        <div class="cart-container">
            <a href="cart.php" class="cart-icon" id="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-quantity" id="cart-quantity">0</span>
            </a>
        </div>
    </header>

    <main>
        <section class="about-section">
            <div class="container">
                <h2 class="text-center">About Us</h2>
                <p>Welcome to our online store! We’re passionate about bringing you high-quality products that enhance your lifestyle. Whether you’re looking for elegant timepieces, stylish furniture, or innovative lighting solutions, we’ve got you covered.</p>
                <p>At TimePiece, we believe that every detail matters. From the craftsmanship of our watches to the design of our furniture pieces, we strive for excellence. Our vision is to create a seamless shopping experience where you can discover, explore, and find the perfect products for your home and personal style.</p>
            </div>
        </section>

        <section class="collections-section">
            <div class="container">
                <h2 class="text-center">Our Collections</h2>
                <div class="collection-split split1">
                    <div><a href="shop.php#watch-collection"><img src="./images/watches/watch (7).jpg" alt="Watch"></a></div>
                    <div class="collection-content">
                        <h4 class="text-center collection-title">Watches</h4>
                        <p>Explore our extensive range of premium watches that are perfect for every occasion. From classic to contemporary, our collection features timepieces that blend style and functionality.</p>
                    </div>
                </div>
                <div class="collection-split split2">
                    <div class="collection-content ml-align">
                        <h4 class="text-center collection-title">Light Solutions</h4>
                        <p>Illuminate your space with our innovative light solutions. Our collection includes everything from elegant chandeliers to modern lighting fixtures designed to enhance your home's ambiance.</p>
                    </div>

                    <div><a href="shop.php#light-solutions-collection"><img src="./images/lights/light.jpg" alt="Lighting Solutions"></a></div>
                </div>
                <div class="collection-split split3">
                    <div><a href="shop.php#furniture-collection"><img src="./images/furniture/furniture (10).jpg" alt="Furniture"></a></div>
                    <div class="collection-content">
                        <h4 class="text-center collection-title">Furniture</h4>
                        <p>Discover our stylish and comfortable furniture pieces that are perfect for any home. Our furniture collection combines functionality with contemporary design to offer you the best in comfort and style.</p>
                    </div>
                </div>
            </div>
        </section>

    <footer>
        &copy; 2024 TimePiece. All Rights Reserved. 
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('hamburger').addEventListener('click', function() {
                document.getElementById('nav-menu').classList.add('open');
            });

            document.getElementById('close').addEventListener('click', function() {
                document.getElementById('nav-menu').classList.remove('open');
            });

            const username = '<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ""; ?>';

            function getCartKey(username) {
                return `cart_${username || 'guest'}`;
            }

            let cart = JSON.parse(localStorage.getItem(getCartKey(username))) || [];
            document.getElementById('cart-quantity').textContent = cart.reduce((sum, product) => sum + product.quantity, 0);
        });
    </script>
</body>
</html>