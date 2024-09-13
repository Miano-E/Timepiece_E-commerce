<?php
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <link rel="stylesheet" href="./css/cart.css">
</head>

<body>



    <section class="cart-section">
        <div class="container">
            <h2 class="text-center cart-items-title">Cart Items</h2>
            <div id="cart-items-container">
                <!-- Cart items will be dynamically added here  -->
            </div>
            <div class="cart-summary">
                <p>Total: <span id="cart-total"></span></p>
                <div class="cart-buttons">
                    <a href="check.php" class="cta-btn"><span class="btn-text">Checkout</span></a>
                </div>
            </div>
        </div>
    </section>




    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // Fetching the username from PHP session
            const username = '<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ""; ?>';

            // Function to get the correct cart key based on username or guest
            function getCartKey(username) {
                return `cart_${username || 'guest'}`;
            }

            // Getting references to cart-related elements
            const cartItemsContainer = document.getElementById('cart-items-container');
            const cartTotal = document.getElementById('cart-total');


            // Retrieve the cart from localStorage
            let cart = JSON.parse(localStorage.getItem(getCartKey(username))) || [];

            // Helper function to format currency (add commas, 2 decimal places)
            function formatCurrency(amount) {
                return amount.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // Function to update and display the total amount
            function updateCartTotal() {
                const totalAmount = cart.reduce((total, item) => {
                    const itemPrice = parseFloat(item.price) || 0;
                    const itemQuantity = parseInt(item.quantity) || 0;
                    return total + (itemPrice * itemQuantity);
                }, 0);

                cartTotal.textContent = `Ksh ${formatCurrency(totalAmount)}`;
            }

            function renderCartItems() {

                // Clear the container before re-rendering
                cartItemsContainer.innerHTML = '';

                // Iterate over each item in the cart and create DOM elements for it
                cart.forEach((item, index) => {
                    const cartItem = document.createElement('div');
                    cartItem.className = 'cart-item';
                    cartItem.innerHTML = `
                <img src="${item.image}" alt="${item.product_name}" class="cart-item-img">
                <div class="item-details">
                    <span class="item-name">${item.product_name}</span>
                    <span class="item-price">Ksh ${formatCurrency(item.price)}</span>
                    <div class="item-quantity">
                        <button class="quantity-btn decrease-quantity" data-index="${index}">-</button>
                        <span class="quantity-display">${item.quantity}</span>
                        <button class="quantity-btn increase-quantity" data-index="${index}">+</button>
                    </div>
                </div>
                <button class="remove-item" data-index="${index}"><span class="btn-text">Delete</span></button>
            `;
                    cartItemsContainer.appendChild(cartItem);
                });

                // After rendering the items, update the total amount
                updateCartTotal();
            }

            // Function to update the cart in localStorage and re-render it
            function updateCart() {
                // Store the updated cart in localStorage
                localStorage.setItem(getCartKey(username), JSON.stringify(cart));

                renderCartItems();
            }

            // Event listener for interacting with cart items (remove, increase, decrease quantity)
            cartItemsContainer.addEventListener('click', (e) => {
                const button = e.target.closest('button');
                if (!button) return;

                const index = button.dataset.index;

                if (button.classList.contains('remove-item')) {
                    if (confirm('Are you sure you want to remove this item from the cart?')) {
                        cart.splice(index, 1);
                        updateCart();
                    }
                } else if (button.classList.contains('increase-quantity')) {
                    cart[index].quantity++;
                    updateCart();
                } else if (button.classList.contains('decrease-quantity')) {
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

    <?php include 'includes/footer.php'; ?>

</body>
</html>