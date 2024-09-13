<?php
include("connect.php");
include 'includes/header.php';

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize input fields
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    // Check if the subject key exists before accessing it
    $subject = isset($_POST['subject']) ? mysqli_real_escape_string($con, $_POST['subject']) : '';
    $message = mysqli_real_escape_string($con, $_POST['message']);

    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = "All required fields must be filled.";
    } else {
        // Prepare the SQL query to insert data into the database
        $sql = "INSERT INTO contact_submissions (name, email, subject, message) 
                VALUES (?, ?, ?, ?)";

        // Prepare the statement
        if ($stmt = mysqli_prepare($con, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Your message has been sent successfully!";
            } else {
                $error_message = "Error: Could not submit your message.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Error: Could not prepare the SQL statement.";
        }
    }

    // Close the database connection
    mysqli_close($con);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="./css/contact.css">
</head>
<body>
<section>
    <div class="contact container">
        <form action="" method="POST">
            <h2 class="text-center">Contact Us</h2>

            <!-- Display validation or submission messages -->
            <?php if (!empty($error_message)): ?>
                <div class="message error" id="user-message"><?php echo $error_message; ?></div>
            <?php elseif (!empty($success_message)): ?>
                <div class="message success" id="user-message"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <label for="name">Name</label>
            <input type="text" id="name" name="name">

            <label for="email">Email</label>
            <input type="email" id="email" name="email">

            <label for="subject">Subject</label>
            <select id="subject" name="subject">
                <option value="" disabled selected>Select a subject</option>
                <option value="Order Issue">Order Issue</option>
                <option value="Product Inquiry">Product Inquiry</option>
                <option value="Shipping Info">Shipping Info</option>
                <option value="General Inquiry">General Inquiry</option>
            </select>


            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5"></textarea>

            <button type="submit" class="submit-btn">
                <span class="btn-text">Send Message</span>
            </button>
        </form>
    </div>
</section>



    <?php include 'includes/footer.php'; ?>


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

            const userMessage = document.getElementById('user-message');
    
            if (userMessage) {
                // Set a timeout to hide the message after 3 seconds
                setTimeout(() => {
                    userMessage.style.transition = 'opacity 0.5s ease';
                    userMessage.style.opacity = '0';
                    
                    setTimeout(() => {
                        userMessage.style.display = 'none';
                    }, 500); // This matches the CSS transition time
                }, 3000);
            }
        });
    </script>
</body>
</html>