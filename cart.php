<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <link rel="stylesheet" href="./css/cart.css">
    <link rel="stylesheet" href="./css/main.css">
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

    <!-- Breadcrumb Navigation -->
    <ul class="breadcrumb">
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li>View Cart</li>
    </ul>

    <main>
        <section class="cart-section">
            <div class="container">
                <h2 class="text-center">Your Cart</h2>
                <div id="cart-items-container">
                    <!-- Cart items will be dynamically added here -->
                </div>
                <div class="cart-summary">
                    <p>Total: <span id="cart-total">Ksh 0</span></p>
                    <div class="cart-buttons">
                        <a href="check.php" class="btn">Checkout</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOMContentLoaded event fired');
            
            // Hamburger menu functionality
            document.getElementById('hamburger').addEventListener('click', function() {
                console.log('Hamburger clicked');
                document.getElementById('nav-menu').classList.add('open');
            });

            document.getElementById('close').addEventListener('click', function() {
                console.log('Close button clicked');
                document.getElementById('nav-menu').classList.remove('open');
            });

            // Retrieve username from PHP session
            const username = '<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ""; ?>';
            console.log('Username:', username);

            function getCartKey(username) {
                return `cart_${username || 'guest'}`;
            }

            const cartItemsContainer = document.getElementById('cart-items-container');
            const cartTotal = document.getElementById('cart-total');
            const cartQuantity = document.getElementById('cart-quantity');

            let cart = JSON.parse(localStorage.getItem(getCartKey(username))) || [];
            console.log('Cart:', cart);

            function updateCartTotal() {
                const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
                cartTotal.textContent = `Ksh ${total.toFixed(2)}`;
                console.log('Cart total:', total);
            }

            function updateCartQuantity() {
                const quantity = cart.reduce((sum, item) => sum + item.quantity, 0);
                cartQuantity.textContent = quantity;
                console.log('Cart quantity:', quantity);
            }

            function renderCartItems() {
                console.log('Rendering cart items...');
                cartItemsContainer.innerHTML = '';
                cart.forEach((item, index) => {
                    const cartItem = document.createElement('div');
                    cartItem.className = 'cart-item';
                    cartItem.innerHTML = `
                        <img src="${item.image}" alt="${item.product_name}">
                        <div class="item-details">
                            <span class="item-name">${item.product_name}</span>
                            <span class="item-price">Ksh ${item.price}</span>
                            <div class="item-quantity">
                                <button class="decrease-quantity" data-index="${index}">-</button>
                                <input type="number" value="${item.quantity}" readonly>
                                <button class="increase-quantity" data-index="${index}">+</button>
                            </div>
                        </div>
                        <button class="remove-item" data-index="${index}">Remove</button>
                    `;
                    cartItemsContainer.appendChild(cartItem);
                });
                updateCartTotal();
                updateCartQuantity();
            }

            function updateCart() {
                localStorage.setItem(getCartKey(username), JSON.stringify(cart));
                renderCartItems();
            }

            cartItemsContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-item')) {
                    const index = e.target.dataset.index;
                    if (confirm('Are you sure you want to remove this item from the cart?')) {
                        cart.splice(index, 1);
                        updateCart();
                    }
                } else if (e.target.classList.contains('increase-quantity')) {
                    const index = e.target.dataset.index;
                    cart[index].quantity++;
                    updateCart();
                } else if (e.target.classList.contains('decrease-quantity')) {
                    const index = e.target.dataset.index;
                    if (cart[index].quantity > 1) {
                        cart[index].quantity--;
                    } else {
                        if (confirm('Are you sure you want to remove this item from the cart?')) {
                            cart.splice(index, 1);
                        }
                    }
                    updateCart();
                }
            });

            renderCartItems();
        });
    </script>
</body>
</html>
