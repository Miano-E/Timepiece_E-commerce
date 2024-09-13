<footer>
<a href="portfolio.php" class="back-to-portfolio"><span class="btn-text">Back to Portfolio</span></a>

    &copy; 2024 TimePiece. All Rights Reserved. 
</footer>

<script>
   document.addEventListener('DOMContentLoaded', () => {
    
    document.getElementById("hamburger").addEventListener("click", function() {
        document.getElementById("mobileNav").classList.add("open");
    });

    document.getElementById("closeMobileNav").addEventListener("click", function() {
        document.getElementById("mobileNav").classList.remove("open");
    });
        // Retrieve username from PHP session
        const username = '<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ""; ?>';

        function getCartKey(username) {
            return `cart_${username || 'guest'}`;
        }

        let cart = JSON.parse(localStorage.getItem(getCartKey(username))) || [];
        let totalAmount = cart.reduce((sum, product) => sum + product.price * product.quantity, 0);

        document.getElementById('cart-quantity').textContent = cart.reduce((sum, product) => sum + product.quantity, 0);

        function showNotification(message) {
            const notificationContainer = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = 'notification show';
            notification.textContent = message;
            notificationContainer.appendChild(notification);

            setTimeout(() => {
                notification.classList.remove('show');
                notification.classList.add('hide');
                setTimeout(() => notification.remove(), 500);
            }, 2000);
        }

        function addProductToCart(product_name, price, image) {
            const existingProduct = cart.find(product => product.product_name === product_name);
            if (existingProduct) {
                existingProduct.quantity += 1;
            } else {
                cart.push({ product_name, price, image, quantity: 1 });
            }
            totalAmount += price;
            document.getElementById('cart-quantity').textContent = parseInt(document.getElementById('cart-quantity').textContent) + 1;
            localStorage.setItem(getCartKey(username), JSON.stringify(cart));

            if (username) {
                fetch('update_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ cart, username })
                }).then(response => response.text())
                .then(text => {
                    console.log('Response text from update_cart.php:', text);
                    const data = JSON.parse(text);
                    console.log('Cart updated in database:', data);
                }).catch(error => {
                    console.error('Error updating cart in database:', error);
                });
            }

            updateCartSidebar();
        }

        function updateCartSidebar() {
            const sidebarContent = document.getElementById('sidebar-content');
            const cartTotal = document.getElementById('sidebar-cart-total');
            let totalAmount = 0;

            sidebarContent.innerHTML = '';
            cart.forEach(product => {
                const productDiv = document.createElement('div');
                productDiv.className = 'cart-item';
                productDiv.innerHTML = `
                    <img src="${product.image}" alt="${product.product_name}">
                    <div class="item-details">
                        <span class="item-name">${product.product_name}</span>
                        <span class="item-price">Ksh ${product.price.toLocaleString()}</span>
                        <span class="item-quantity">Quantity: ${product.quantity}</span>
                    </div>
                `;
                sidebarContent.appendChild(productDiv);

                totalAmount += product.price * product.quantity;
            });

            cartTotal.textContent = `Ksh ${totalAmount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        }


        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', (event) => {
                const card = event.target.closest('.card');
                const productName = card.querySelector('.card-title').textContent;
                const productPrice = parseFloat(card.querySelector('.card-price').textContent.replace('Ksh ', '').replace(/,/g, ''));
                const productImage = card.querySelector('.card-image').src;

                addProductToCart(productName, productPrice, productImage);
                showNotification(`${productName} added to cart`);
            });
        });

        document.getElementById('cart-icon').addEventListener('click', () => {
            document.getElementById('cart-sidebar').classList.add('open');
        });

        document.getElementById('close-sidebar').addEventListener('click', () => {
            document.getElementById('cart-sidebar').classList.remove('open');
        });

        updateCartSidebar();
    });
   
</script>
