<?php
// Include the connection file and start the session
include("connect.php");
session_start();

// Check if the form is submitted using POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $myusername = mysqli_real_escape_string($con, $_POST['username']);
    $mypassword = $_POST['password'];

    // Validate username and password
    if (empty($myusername)) {
        header("Location: login.php?error=Username required");
        exit();
    } else if (empty($mypassword)) {
        header("Location: login.php?error=Password required");
        exit();
    } else {
        // Prepare and execute a SQL query to fetch user details
        $sql = "SELECT * FROM ecommerce_table WHERE username = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $myusername);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        // If password is correct, create a session and redirect to checkout
        if ($row && password_verify($mypassword, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];

            // Sync cart data from local storage to the server
            echo "
                <script>
                    const cartKey = `cart_{$myusername}`;
                    const cart = JSON.parse(localStorage.getItem(cartKey)) || [];
                    fetch('sync_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ cart, username: '{$myusername}' })
                    }).then(response => response.json())
                    .then(data => {
                        console.log('Cart synced with server:', data);
                        window.location.href = 'check.php';
                    }).catch(error => {
                        console.error('Error syncing cart:', error);
                        window.location.href = 'check.php';
                    });
                </script>";
            exit();
        } else {
            header("Location: login.php?error=Incorrect Username or Password");
            exit();
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/login.css">
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
                <li><a href="shop.php">Shop</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php" class="login active">Login</a></li>
            </ul>
        </nav>
    </header>

    <div class="logincontainer">
    <form action="" method="post">
        <h2>Login Here</h2>

        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php }?>

        <label for="username">Username</label>
        <input type="text" name="username" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="myInput" required>

        <div>
            <input type="checkbox" id="showPassword" onclick="togglePassword()">
            <label for="showPassword">Show Password</label>
        </div>

        <button type="submit" name="login" class="submitbtn">Login</button>

        <p class="check">Don't have an account? <a href="signup.php">Register</a></p>

        <p class="demo">Demo Account: <strong>demo</strong> | <strong>Demo@123</strong></p>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const username = '<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>';

        if (username) {
            fetch('fetch_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username })
            }).then(response => response.json())
            .then(data => {
                const cart = data.cart;
                localStorage.setItem(`cart_${username}`, JSON.stringify(cart));
                updateCartSidebar(); // Your existing function to update the cart UI
            }).catch(error => {
                console.error('Error fetching cart from database:', error);
            });
        }
    });

    function togglePassword() {
        var input = document.getElementById("myInput");
        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    }
</script>
</body>
</html>
