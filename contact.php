<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/contact.css">
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
                <li><a href="contact.php" class="active">Contact</a></li>
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
        <div class="contactcontainer">
            <h2>Contact Us</h2>
            <form action="process_contact.php" method="POST">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required>
                
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
                
                <button type="submit">Send Message</button>
            </form>
        </div>
    </main>

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
