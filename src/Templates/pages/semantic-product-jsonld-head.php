<div class="semantic-product" data-test="<?= htmlspecialchars($test) ?>" data-hash="<?= htmlspecialchars($debugHash) ?>" data-category="<?= htmlspecialchars($category) ?>">
    <div class="product-layout">
        <!-- Product Image -->
        <div class="product-image">
            <div class="product-image-placeholder">Product Image</div>
        </div>

        <!-- Product Details -->
        <div class="product-details">
            <span class="product-brand">AudioTech</span>
            <h1 class="product-title">ProSound X500 Wireless Headphones</h1>

            <div class="product-price">$299.99</div>

            <p class="product-description">
                Premium wireless headphones with active noise cancellation, 40-hour battery life,
                and crystal-clear audio quality. Perfect for music lovers and professionals.
            </p>

            <!-- Add to Cart -->
            <button class="add-to-cart">Add to Cart</button>
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
            <div class="test-info-item">
                <strong>Debug Hash:</strong>
                <code><?= htmlspecialchars($debugHash) ?></code>
            </div>
        </div>
        <p class="test-info-description">
            This page includes Schema.org JSON-LD structured data placed in the &lt;head&gt; section.
            Check if bots correctly parse JSON-LD from the head vs the body.
        </p>
    </div>

    <!-- Navigation -->
    <nav class="article-nav">
        <a href="/lab/semantic-html">&larr; Back to Semantic HTML Tests</a>
        <?php if (!empty($nextTest)): ?>
        <a href="/lab/<?= htmlspecialchars($category) ?>/<?= htmlspecialchars($nextTest) ?>">Next Test &rarr;</a>
        <?php endif; ?>
    </nav>
</div>
