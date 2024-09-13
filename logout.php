<?php
session_start();
include 'connect.php';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $userId = $_SESSION['user_id'];

    // Fetch cart data from the server
    $sql = "SELECT product_name, price, quantity, image FROM cart WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $cart = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Store cart data in local storage before logout
    echo "<script>
        localStorage.setItem('cart_{$username}', JSON.stringify(" . json_encode($cart) . "));
        const previousPage = '" . (isset($_SESSION['previous_page']) ? $_SESSION['previous_page'] : 'home.php') . "';
        window.location.href = previousPage;
    </script>";
}

// Clear session-related data
session_unset();
session_destroy();
exit();
?>
