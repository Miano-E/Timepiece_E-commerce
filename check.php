<?php
include 'includes/header.php';


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
    $Password = base64_encode($BusinessShortCode . $Passkey . $Timestamp);

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
    curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
    $result = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $result = json_decode($result);
    $access_token = $result->access_token;
    curl_close($curl);

    // Header for stk push
    $stkheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $access_token];

    // Initiating the transaction
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $initiate_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);

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
    <link rel="stylesheet" href="./css/check.css">
</head>

<body>


    <section>
        <div class="container">
            <h2 class="text-center checkout-title">Checkout</h2>
            <a href="cart.php" class="cta-btn btn-secondary return-cart-btn">
                <span class="btn-text">Return to Cart</span>
            </a>
            <div class="checkout-split">
                <div class="order-summary" id="order-summary">
                    <h3 class="order-title">Order Summary</h3>
                    <!-- Order summary will be populated by JavaScript -->

                </div>
                <div class="payment-section">
                    <h3 class="text-center form-title">Payment</h3>
                    <?php if ($user_message): ?>
                        <div class="alert <?php echo (strpos($user_message, 'Success') !== false) ? 'alert-success' : 'alert-error'; ?>">
                            <?php echo $user_message; ?>
                            <!-- <span class="close-btn">&times;</span> -->
                        </div>
                    <?php endif; ?>

                    <form action="check.php" method="POST">
                        <label for="amount">Amount</label>
                        <input type="text" id="amount" name="amount" readonly>
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" placeholder="254700000000" required>
                        <button type="submit" class="cta-btn btn-success" name="submit" value="submit"> <span class="btn-text">Checkout</span></button>
                    </form>
                </div>
            </div>
        </div>
    </section>


    <?php include 'includes/footer.php'; ?>


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

            const alert = document.querySelector('.alert');
    
            if (alert) {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.style.display = 'none', 500);
                }, 3000); // 3 seconds
            }
        });


    </script>

</body>

</html>