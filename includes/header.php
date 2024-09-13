<?php 
// Start session if needed for user authentication or cart data
session_start();
include 'includes/user_info.php';


// Determine the current page for active link highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timepiece</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/nav.css">
    <link rel="stylesheet" href="./css/s_product.css">



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
</head>
<body>

<header>
    <!-- Mobile Side Navigation -->
    <div id="mobileNav" class="mobile_side_nav">
        <a href="javascript:void(0)" id="closeMobileNav">
            <span class="close-icon">&times;</span>
        </a>
        <a href="home.php" class="<?php echo $current_page == 'home.php' ? 'active' : ''; ?>">Home</a>
        <a href="shop.php" class="<?php echo $current_page == 'shop.php' ? 'active' : ''; ?>">Shop</a>
        <a href="about.php" class="<?php echo $current_page == 'about.php' ? 'active' : ''; ?>">About</a>
        <a href="contact.php" class="<?php echo $current_page == 'contact.php' ? 'active' : ''; ?>">Contact</a>
        <?php if (isset($_SESSION['username'])) { ?>
        <a href="logout.php" class="<?php echo $current_page == 'logout.php' ? 'active' : ''; ?>">Logout</a>
        <?php } else { ?>
        <a href="login.php" class="<?php echo $current_page == 'login.php' ? 'active' : ''; ?>">Login</a>
        <?php } ?>
    </div>

     <div class="container flex">
        <div class="mobile-nav-controls">
            <span class="menu_icon" id="hamburger">&#9776;</span>
        </div>
        <a href="home.php" class="logo">Time<span>Piece</span></a>

        <nav class="navbar" id="nav-menu">
            <div class="close" id="close">&times;</div>
            <ul class="nav_links nav_links--primary">
                <li class="nav_item"><a href="home.php" class="nav_link <?php echo $current_page == 'home.php' ? 'active' : ''; ?>">Home</a></li>
                <li class="nav_item"><a href="shop.php" class="nav_link <?php echo $current_page == 'shop.php' ? 'active' : ''; ?>">Shop</a></li>
                <li class="nav_item"><a href="about.php" class="nav_link <?php echo $current_page == 'about.php' ? 'active' : ''; ?>">About</a></li>
                <li class="nav_item"><a href="contact.php" class="nav_link <?php echo $current_page == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
                <?php if (isset($_SESSION['username'])) { ?>
                <li class="nav_item">
                    <a href="logout.php" class="nav_link nav__link_logout <?php echo $current_page == 'logout.php' ? 'active' : ''; ?>">Logout</a>
                </li>
                <?php } else { ?>
                <li class="nav_item">
                    <a href="login.php" class="nav_link nav__link_login <?php echo $current_page == 'login.php' ? 'active' : ''; ?>">Login</a>
                </li>
                <?php } ?>
            </ul>
        </nav>
        
        <div class="cart-container">
            <a href="#" class="cart-icon disable-cart-icon" id="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-quantity" id="cart-quantity">0</span>
            </a>
        </div>
    </div>
</header>

<!-- Notification container -->
<div id="notification-container"></div>

<div id="cart-sidebar">
    <div class="sidebar-header">
        <span class="close-sidebar" id="close-sidebar">&times;</span>
        <h3 class="text-center cart-title">Your Cart Items</h3>
    </div>
    <div class="sidebar-content" id="sidebar-content">
    </div>
    <div class="sidebar-footer">
        <p>Total: <span id="sidebar-cart-total">Ksh 0</span></p>
        <div class="btns">
            <a href="cart.php" class="sidebar-cta-btn"><span class="btn-text">View Cart</span></a>
            <a href="check.php" class="sidebar-cta-btn"><span class="btn-text">Checkout</span></a>
        </div>
    </div>
</div> 

