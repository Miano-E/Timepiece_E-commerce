<?php
session_start();

if (isset($_POST['submit'])) {

    date_default_timezone_set('Africa/Nairobi');

    // Access token
    $consumerKey = 'nk16Y74eSbTaGQgc9WF8j6FigApqOMWr'; 
    $consumerSecret = '40fD1vRXCq90XFaU'; 

    // Define the variables
    $BusinessShortCode = '174379';
    $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';  

    $PartyA = $_POST['phone']; 
    $AccountReference = '2255';
    $TransactionDesc = 'Test Payment';
    $Amount = intval($_POST['amount']); 

    // Get the timestamp, format YYYYmmddhms
    $Timestamp = date('YmdHis');    

    // Get the base64 encoded string -> $password
    $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);

    // Header for access token
    $headers = ['Content-Type:application/json; charset=utf8'];

    // M-PESA endpoint urls
    $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

    // Callback url
    $CallBackURL = 'https://361e-41-209-60-94.ngrok-free.app/callback_url.php';  

    // Get access token
    $curl = curl_init($access_token_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
    $result = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $result = json_decode($result);
    $access_token = $result->access_token;  
    curl_close($curl);

    // Header for stk push
    $stkheader = ['Content-Type:application/json','Authorization:Bearer '.$access_token];

    // Initiating the transaction
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $initiate_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader); // Setting custom header

    $curl_post_data = array(
        'BusinessShortCode' => $BusinessShortCode,
        'Password' => $Password,
        'Timestamp' => $Timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $Amount,
        'PartyA' => $PartyA,
        'PartyB' => $BusinessShortCode,
        'PhoneNumber' => $PartyA,
        'CallBackURL' => $CallBackURL,
        'AccountReference' => $AccountReference,
        'TransactionDesc' => $TransactionDesc
    );

    $data_string = json_encode($curl_post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    $curl_response = curl_exec($curl);

    $response = json_decode($curl_response, true);

    if ($response['ResponseCode'] == "0") {
        $user_message = "Success! Your payment request was accepted and is being processed. Please check your phone for the M-PESA prompt.";
        $_SESSION['user_message'] = $user_message;
    } else {
        $user_message = "Error! There was a problem with your payment request. Please try again.";
        $_SESSION['user_message'] = $user_message;
    }

    header("Location: check.php");
    exit();
}

// Check the result in the session for cancellation
if (isset($_SESSION['mpesa_result'])) {
    $mpesa_result = $_SESSION['mpesa_result'];
    if ($mpesa_result['ResultCode'] != 0) {
        $user_message = "Transaction failed or was canceled. " . $mpesa_result['ResultDesc'];
    }
    unset($_SESSION['mpesa_result']);
} elseif (isset($_SESSION['user_message'])) {
    $user_message = $_SESSION['user_message'];
    unset($_SESSION['user_message']);
} else {
    $user_message = "";
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimePiece - Checkout</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/check.css">
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
                <?php if (isset($_SESSION['username'])) { ?>
                    <li><a href="logout.php" class="login">Logout</a></li>
                <?php } else { ?>
                    <li><a href="login.php" class="login">Login</a></li>
                <?php } ?>
            </ul>
        </nav>
    
    </header>
    <?php include 'user_info.php'; ?>

    <!-- Breadcrumb Navigation -->
    <ul class="breadcrumb">
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li> <a href="cart.php">View Cart</a></li>
        <li>Checkout</li>
    </ul>

    <div class="container">
        <h2 class="text-center checkout-title">Checkout</h2>

        <div class="checkout-split">
            <div class="order-summary" id="order-summary">
                <h3 class="order-title">Order Summary</h3>
                <!-- Order summary will be populated by JavaScript -->
            </div>
            <div class="payment-section">
                <h3 class="form-title">Payment</h3>
                <?php if ($user_message): ?>
                    <div class='alert alert-info'><?php echo $user_message; ?></div>
                <?php endif; ?>
                <form action="check.php" method="POST">
                    <label for="amount">Amount</label>
                    <input type="text" id="amount" name="amount" readonly>
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" placeholder="254700000000" required>
                    <button type="submit" class="btn btn-success" name="submit" value="submit">Checkout</button>
                </form>
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

        const username = '<?php echo $username; ?>';

        function getCartKey(username) {
            return `cart_${username || 'guest'}`;
        }

        const cart = JSON.parse(localStorage.getItem(getCartKey(username))) || [];
        const totalAmount = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        document.getElementById('amount').value = Math.floor(totalAmount);

        const orderSummaryContainer = document.getElementById('order-summary');
        cart.forEach((item, index) => {
            const itemElement = document.createElement('div');
            itemElement.classList.add('order-item');
            if (index < cart.length - 1) {
                itemElement.classList.add('border-bottom');
            }
            itemElement.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <div>
                    <p>${item.product_name}</p>
                    <p>Ksh ${item.price} x ${item.quantity}</p>
                </div>
            `;
            orderSummaryContainer.appendChild(itemElement);
        });

        const totalElement = document.createElement('div');
        totalElement.classList.add('total');
        totalElement.textContent = `Total: Ksh ${totalAmount.toFixed(2)}`;
        orderSummaryContainer.appendChild(totalElement);
    });
</script>

</body>
</html>
