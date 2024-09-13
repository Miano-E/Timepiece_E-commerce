<?php
    include 'includes/header.php';

?>
    <section class="hero">
        <div >
            <div class="container content">
                <h2 class="hero-title">Timeless Pieces for Every Occasion to Elevate Your Space</h2>
                <p class="subhead">
                    Explore our carefully chosen selection of furniture, lighting options, and watches that combine fashion and utility. 
                    Find the ideal match here whether you're remodeling your house or your appearance.
                </p>

                <a href="shop.php"><button class="cta-btn"><span class="btn-text">Start Shopping</span></button></a>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <h2 class="category-header text-center">Our Signature Picks</h2>
            <div class="category-product-grid">
                <div class="card">
                    <a href="shop.php?category=1"><img src="./images/wristwatches-montre-.webp" alt="Watch" class="card-image"></a>
                    <div class="category-card-content">
                        <h2 class="card-title">Watch Collection</h2>
                    </div>
                </div>

                <div class="card">
                    <a href="shop.php?category=2"><img src="./images/pendant_lamp.jpg" alt="Lighting Solutions" class="card-image"></a>
                    <div class="category-card-content">
                        <h2 class="card-title">Lighting Solutions Collection</h2>
                    </div>
                </div>

                <div class="card">
                    <a href="shop.php?category=3"><img src="./images/furniture.jpg" alt="Furniture" class="card-image"></a>
                    <div class="category-card-content">
                        <h2 class="card-title">Furniture Collection</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section>
        <div class="container">
            <div class="featured-products">
                <h2 class="featured-products-title">Featured Products</h2>
                <a href="shop.php"><button class="see-more-btn"><span class="btn-text">See More</span></button></a>
            </div>

            <div class="product-grid">
                <div class="card">
                    <img src="images/smart_watch.jpg" alt="Watch" class="card-image">
                    <div class="card-content">
                        <h4 class="card-category">Watch</h4>
                        <h2 class="card-title">Black Smartwatch</h2>

                        <p class="card-price">Ksh 500</p>
                        <button class="add-to-cart-btn"><span class="btn-text">Add To Cart</span></button>
                    </div>
                </div>
                <div class="card">
                    <img src="images/watches/wristwatch (4).jpg" alt="Watch" class="card-image">
                    <div class="card-content">
                        <h4 class="card-category">Watch</h4>
                        <h2 class="card-title">Oyster Perpetual Datejust Watch</h2>

                        <p class="card-price">Ksh 500</p>
                        <button class="add-to-cart-btn"><span class="btn-text">Add To Cart</span></button>
                    </div>
                </div>
                <div class="card">
                    <img src="images/watches/wrist-watch.jpg" alt="Watch" class="card-image">
                    <div class="card-content">
                        <h4 class="card-category">Watch</h4>
                        <h2 class="card-title">Grey Digital Wrist Watch</h2>

                        <p class="card-price">Ksh 500</p>
                        <button class="add-to-cart-btn"><span class="btn-text">Add To Cart</span></button>
                    </div>
                </div>

                <div class="card">
                    <img src="./images/lights/light (1).jpg" alt="Light Solutions" class="card-image">
                    <div class="card-content">
                        <h4 class="card-category">Light Solutions</h4>
                        <h2 class="card-title">Retro Light Bulb</h2>

                        <p class="card-price">Ksh 500</p>
                        <button class="add-to-cart-btn"><span class="btn-text">Add To Cart</span></button>
                    </div>
                </div>
                <div class="card">
                    <img src="images/lights/light (3).jpg" alt="Light Solutions" class="card-image">
                    <div class="card-content">
                        <h4 class="card-category">Light Solutions</h4>
                        <h2 class="card-title">Pink Table Lamp</h2>

                        <p class="card-price">Ksh 500</p>
                        <button class="add-to-cart-btn"><span class="btn-text">Add To Cart</span></button>
                    </div>
                </div>
                <div class="card">
                    <img src="images/lights/torch.jpg" alt="Light Solutions" class="card-image">
                    <div class="card-content">
                        <h4 class="card-category">Light Solutions</h4>
                        <h2 class="card-title">Medical Torch Pen</h2>

                        <p class="card-price">Ksh 500</p>
                        <button class="add-to-cart-btn"><span class="btn-text">Add To Cart</span></button>
                    </div>
                </div>

                <div class="card">
                    <img src="images/furniture/chair-table.jpg" alt="Furniture" class="card-image">
                    <div class="card-content">
                        <h4 class="card-category">Furniture</h4>
                        <h2 class="card-title">Wooden Chair &amp; Table</h2>

                        <p class="card-price">Ksh 500</p>
                        <button class="add-to-cart-btn"><span class="btn-text">Add To Cart</span></button>
                    </div>
                </div>
                <div class="card">
                    <img src="images/furniture/sofa-chair.jpg" alt="Furniture" class="card-image">
                    <div class="card-content">
                        <h4 class="card-category">Furniture</h4>
                        <h2 class="card-title">Gray Padded Sofa Chair</h2>

                        <p class="card-price">Ksh 500</p>
                        <button class="add-to-cart-btn"><span class="btn-text">Add To Cart</span></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="new-arrivals">
        <div class="container">
            <h2 class="new-arrivals-title text-center">New Arrival</h2>
            <div class="new-arrival-card">
                <img src="./images/study_lamp.jpg" alt="New Arrival" class="new-arrival-image">
                <div class="new-arrival-content">
                    <h3 class="new-arrival-product-name">Study Lamp Saata Raha Saata Ini Dengan</h3>
                    <p class="new-arrival-description">
                        Illuminate your workspace with this stylish and functional study lamp. Designed for optimal focus and comfort, the sleek design and bright light make it a perfect companion for late-night work or study sessions.
                    </p>
                    <button class="new-arrival-cta-btn" onclick="window.location.href='product_details.php?id=25'"><span class="btn-text">Shop Now</span></button>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>


    <!-- <section>
        <div class="container">
            <h2 class="best-selling text-center">Best Selling Product</h2>
            <div class="split best-selling-content">
                <div class="right-col">
                    <img src="./images/furniture/furniture (15).jpg" alt="Watch" class="bt-card-img">
                </div>
                <div class="left-col best-selling-product">
                    <h2 class="hero-title">Transcending the Ordinary with Distinctive Elegance.</h2>
                    <p class="subhead ">
                        Discover a carefully curated collection of watches,
                        lighting solutions, and exquisite furniture at Timepiece.
                    </p>

                    <a href="shop.php#furniture-collection"><button class="cta-btn">Start Shopping</button></a>

                </div>
            </div>
        </div>
    </section> -->

