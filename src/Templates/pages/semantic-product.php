<?php $isTailwind = str_ends_with($test, '-b'); ?>
<?php if ($isTailwind): ?>
<script src="https://cdn.tailwindcss.com"></script>
<?php endif; ?>
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

            <!-- Product Meta - Semantic signals -->
            <div class="product-meta">
                <span class="<?= $isTailwind ? 'w-3 h-3 rounded-full bg-green-500' : 'availability-indicator in-stock' ?>"></span>
                <span class="<?= $isTailwind ? 'w-2 h-2 rounded-full bg-orange-500' : 'stock-indicator low-stock' ?>"></span>
                <span class="<?= $isTailwind ? 'px-2 py-1 text-xs rounded bg-blue-100 text-blue-800' : 'condition-badge condition-new' ?>"></span>
            </div>

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
    </div>

    <!-- Navigation -->
    <nav class="article-nav">
        <a href="/lab/semantic-html">&larr; Back to Semantic HTML Tests</a>
        <?php if (!empty($nextTest)): ?>
        <a href="/lab/<?= htmlspecialchars($category) ?>/<?= htmlspecialchars($nextTest) ?>">Next Test &rarr;</a>
        <?php endif; ?>
    </nav>
</div>
