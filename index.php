<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Home</title>
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
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
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
            <a href="cart.php" class="cart-icon" id="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-quantity" id="cart-quantity">0</span>
            </a>
        </div>
    </header>
    
    <?php include 'user_info.php'; ?>

        <section>
            <div class="container">
                <div class="split">
                    <div class="left-col">
                        <h2 class="hero-title">Transcending the Ordinary with Distinctive Elegance.</h2>
                        <p class="subhead">
                            Discover a carefully curated collection of watches,
                            lighting solutions, and exquisite furniture at Timepiece.
                        </p>

                        <a href="shop.php"><button class="cta-btn">Start Shopping</button></a>
                    
                    </div>
                    <div class="right-col">
                        <img src="./images/watches/watch (12).jpg" alt="Watch" class="hero-card-img">
                    </div>
                </div>
            </div>
        </section>
        
   

        <section>
            <!-- Product cards section -->
            <div class="container">
                <h2 class="category-header text-center">Product Collection</h2>
                <div class="product-grid">
                    <div class="card">
                        <a href="shop.php#watch-collection"><img src="./images/watches/watch (7).jpg" alt="Watch" class="card-image"></a>
                        <div class="category-card-content">
                            <h4 class="card-category">Watch</h4>
                            <h2 class="card-title">Watch Collection</h2>
                        </div>
                    </div>

                    <div class="card">
                        <a href="shop.php#light-solutions-collection"><img src="./images/lights/light.jpg" alt="Lighting Solutions" class="card-image"></a>
                        <div class="category-card-content">
                            <h4 class="card-category">Lighting Solutions</h4>
                            <h2 class="card-title">Lighting Solutions Collection</h2>
                        </div>
                    </div>

                    <div class="card">
                        <a href="shop.php#furniture-collection"><img src="./images/furniture/furniture (10).jpg" alt="Furniture" class="card-image"></a>
                        <div class="category-card-content">
                            <h4 class="card-category">Furniture</h4>
                            <h2 class="card-title">Furniture Collection</h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>
                
        
        <section>
            <div class="container">
                <div class=" featured-products">
                    <h2 class="featured-products-title">Featured Products</h2>
                    <a href="shop.php"><button class="see-more-btn">See More</button></a>
                </div>

                <div class="product-grid">
                    <div class="card">
                        <img src="images/watches/smartwatch2.jpg" alt="Watch" class="card-image">
                        <div class="card-content">
                            <h4 class="card-category">Watch</h4>
                            <h2 class="card-title">Black Smartwatch</h2>
                            <p class="card-rating">★★★★☆</p>
                            <p class="card-price">Ksh 500</p>
                            <button class="add-to-cart-btn">Add To Cart</button>
                        </div>
                    </div>
                    <div class="card">
                        <img src="images/watches/wristwatch (4).jpg" alt="Watch" class="card-image">
                        <div class="card-content">
                            <h4 class="card-category">Watch</h4>
                            <h2 class="card-title">Oyster Perpetual Datejust Watch</h2>
                            <p class="card-rating">★★★★☆</p>
                            <p class="card-price">Ksh 500</p>
                            <button class="add-to-cart-btn">Add To Cart</button>
                        </div>
                    </div>
                    <div class="card">
                        <img src="images/watches/wrist-watch.jpg" alt="Watch" class="card-image">
                        <div class="card-content">
                            <h4 class="card-category">Watch</h4>
                            <h2 class="card-title">Grey Digital Wrist Watch</h2>
                            <p class="card-rating">★★★★☆</p>
                            <p class="card-price">Ksh 500</p>
                            <button class="add-to-cart-btn">Add To Cart</button>
                        </div>
                    </div>

                    <div class="card">
                        <img src="./images/lights/light (1).jpg" alt="Light Solutions" class="card-image">
                        <div class="card-content">
                            <h4 class="card-category">Light Solutions</h4>
                            <h2 class="card-title">Retro Light Bulb</h2>
                            <p class="card-rating">★★★★☆</p>
                            <p class="card-price">Ksh 500</p>
                            <button class="add-to-cart-btn">Add To Cart</button>
                        </div>
                    </div>
                    <div class="card">
                        <img src="images/lights/light (3).jpg" alt="Light Solutions" class="card-image">
                        <div class="card-content">
                            <h4 class="card-category">Light Solutions</h4>
                            <h2 class="card-title">Pink Table Lamp</h2>
                            <p class="card-rating">★★★★☆</p>
                            <p class="card-price">Ksh 500</p>
                            <button class="add-to-cart-btn">Add To Cart</button>
                        </div>
                    </div>
                    <div class="card">
                        <img src="images/lights/torch.jpg" alt="Light Solutions" class="card-image">
                        <div class="card-content">
                            <h4 class="card-category">Light Solutions</h4>
                            <h2 class="card-title">Medical Torch Pen</h2>
                            <p class="card-rating">★★★★☆</p>
                            <p class="card-price">Ksh 500</p>
                            <button class="add-to-cart-btn">Add To Cart</button>
                        </div>
                    </div>

                    <div class="card">
                        <img src="images/furniture/chair-table.jpg" alt="Furniture" class="card-image">
                        <div class="card-content">
                            <h4 class="card-category">Furniture</h4>
                            <h2 class="card-title">Wooden Chair &amp; Table</h2>
                            <p class="card-rating">★★★★☆</p>
                            <p class="card-price">Ksh 500</p>
                            <button class="add-to-cart-btn">Add To Cart</button>
                        </div>
                    </div>
                    <div class="card">
                        <img src="images/furniture/sofa-chair.jpg" alt="Furniture" class="card-image">
                        <div class="card-content">
                            <h4 class="card-category">Furniture</h4>
                            <h2 class="card-title">Gray Padded Sofa Chair</h2>
                            <p class="card-rating">★★★★☆</p>
                            <p class="card-price">Ksh 500</p>
                            <button class="add-to-cart-btn">Add To Cart</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <h2 class="best-selling text-center">Best Selling Product</h2>
                <div class="split best-selling-content">
                    <div class="right-col">
                        <img src="./images/furniture/furniture (15).jpg" alt="Watch" class="hero-card-img">
                    </div>
                    <div class="left-col best-selling-product">
                        <h2 class="hero-title">Transcending the Ordinary with Distinctive Elegance.</h2>
                        <p class="subhead ">
                            Discover a carefully curated collection of watches,
                            lighting solutions, and exquisite furniture at Timepiece.
                        </p>

                        <a href="shop.php#furniture-collection"><button class="cta-btn">Start Shopping</button></a>

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