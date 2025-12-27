<div class="product-page" data-test="<?= htmlspecialchars($test) ?>" data-hash="<?= htmlspecialchars($debugHash) ?>" data-delay="<?= htmlspecialchars((string)($delay ?? 0)) ?>" data-category="<?= htmlspecialchars($category) ?>">
    <div class="product-layout">
        <!-- Product Image -->
        <div class="product-image">
            <img src="https://picsum.photos/seed/pixelpulse/500/500" alt="PixelPulse Pro Wireless Headphones">
        </div>

        <!-- Product Details -->
        <div class="product-details">
            <div>
                <span class="product-brand">PixelPulse Audio</span>
                <h1 class="product-title">Pro Wireless Headphones</h1>
            </div>

            <!-- Rating -->
            <div class="product-rating">
                <span class="stars">★★★★½</span>
                <span class="rating-count">(2,847 reviews)</span>
            </div>

            <!-- Price - Loaded via AJAX -->
            <div id="product-price" class="product-price loading">Loading price...</div>

            <!-- Availability - Loaded via AJAX -->
            <div id="product-availability" class="product-availability loading">
                <span class="availability-indicator"></span>
                <span>Checking availability...</span>
            </div>

            <!-- Shipping - Loaded via AJAX -->
            <div id="product-shipping" class="product-shipping loading">
                Calculating shipping...
            </div>

            <!-- Add to Cart -->
            <button class="add-to-cart" disabled>Add to Cart</button>
        </div>
    </div>

    <!-- Product Description -->
    <div class="product-description">
        <h2>About This Product</h2>
        <p>
            Experience audio like never before with the PixelPulse Pro Wireless Headphones.
            Featuring our proprietary SoundWave technology, these headphones deliver crystal-clear
            highs and deep, resonant bass that brings your music to life. The advanced active noise
            cancellation blocks out distractions, while the 40-hour battery life keeps you immersed
            in your audio world all day long.
        </p>
        <p>
            Crafted with premium materials including memory foam ear cushions and a lightweight
            aluminum frame, the Pro Wireless Headphones are designed for extended listening sessions
            without fatigue. Seamlessly switch between devices with multipoint Bluetooth connectivity,
            and customize your sound profile using the PixelPulse companion app.
        </p>

        <div class="product-features">
            <h3>Key Features</h3>
            <ul>
                <li>40-hour battery life with quick charge (10 min = 3 hours)</li>
                <li>Advanced Active Noise Cancellation with transparency mode</li>
                <li>Hi-Res Audio certified with LDAC support</li>
                <li>Multipoint Bluetooth 5.2 connectivity</li>
                <li>Premium memory foam ear cushions</li>
                <li>Foldable design with carrying case included</li>
            </ul>
        </div>
    </div>

    <!-- Test Information -->
    <div class="test-info">
        <h4>Test Information</h4>
        <div class="test-info-grid">
            <div class="test-info-item">
                <strong>Category:</strong> 
                <span><?= htmlspecialchars($categoryName) ?></span>
            </div>
            <div class="test-info-item">
                <strong>Test Case:</strong>
                <span><?= htmlspecialchars($testTitle) ?></span>
            </div>
            <div class="test-info-item full-width">
                <strong>What is being tested?</strong>
                <p><?= htmlspecialchars($categoryDescription ?? $testDescription ?? 'No description available.') ?></p>
            </div>
            <div class="test-info-item">
                <strong>Server Delay:</strong>
                <span><?= htmlspecialchars((string)$delay) ?>ms</span>
            </div>
            <div class="test-info-item">
                <strong>Debug Hash:</strong>
                <code><?= htmlspecialchars($debugHash) ?></code>
            </div>
            <div class="test-info-item full-width">
                <strong>Note:</strong>
                <p>
                    The price, availability, and shipping information will be loaded via AJAX after a
                    <?= htmlspecialchars((string)$delay) ?>ms server-side delay.
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="product-nav">
        <a href="/">&larr; Back to Home</a>
        <?php if (!empty($nextTest)): ?>
        <a href="/lab/<?= htmlspecialchars($category) ?>/<?= htmlspecialchars($nextTest) ?>">Next Test &rarr;</a>
        <?php endif; ?>
    </nav>
</div>
