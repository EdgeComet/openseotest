<?php
$pageUrl = 'https://openseotest.org/lab/' . htmlspecialchars($category) . '/' . htmlspecialchars($test);
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => 'ProSound X500 Wireless Headphones',
    'brand' => [
        '@type' => 'Brand',
        'name' => 'AudioTech'
    ],
    'description' => 'Premium wireless headphones with active noise cancellation, 40-hour battery life, and crystal-clear audio quality. Perfect for music lovers and professionals.',
    'sku' => 'PS-X500-BLK',
    'mpn' => 'X500-2024',
    'gtin13' => '0123456789012',
    'itemCondition' => 'https://schema.org/NewCondition',
    'offers' => [
        '@type' => 'Offer',
        'url' => $pageUrl,
        'priceCurrency' => 'USD',
        'price' => '299.99',
        'availability' => 'https://schema.org/InStock',
        'inventoryLevel' => [
            '@type' => 'QuantitativeValue',
            'value' => 5
        ],
        'seller' => [
            '@type' => 'Organization',
            'name' => 'AudioTech Store'
        ]
    ]
];
?>
<script type="application/ld+json">
<?= json_encode($jsonLd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?>
</script>
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
            This page includes Schema.org JSON-LD structured data with product availability,
            inventory level, condition, and identifiers. Check if bots correctly parse these values.
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
