<div class="catalog-page" data-test="<?= htmlspecialchars($test) ?>" data-hash="<?= htmlspecialchars($debugHash) ?>" data-steps="<?= htmlspecialchars((string)($steps ?? 3)) ?>" data-category="<?= htmlspecialchars($category) ?>">
    <!-- Catalog Header -->
    <header class="catalog-header">
        <h1>PixelPulse Product Catalog</h1>
        <p class="subtitle">Premium Audio Equipment Collection</p>
    </header>

    <!-- Step Counter -->
    <div class="step-counter">
        <span class="current-step" id="current-step">0</span>
        <span class="total-steps">/ <?= htmlspecialchars((string)($steps ?? 3)) ?> products loaded</span>
    </div>

    <!-- Product Grid -->
    <div class="product-grid">
        <?php for ($i = 1; $i <= ($steps ?? 3); $i++): ?>
        <div class="product-slot loading" id="product-slot-<?= $i ?>" data-slot="<?= $i ?>">
            <div class="slot-image loading">
                <div class="loading-indicator">
                    <div class="loading-spinner"></div>
                    <span>Loading...</span>
                </div>
            </div>
            <div class="slot-content">
                <div class="slot-name loading">Loading product...</div>
                <div class="slot-price loading">$---.--</div>
            </div>
        </div>
        <?php endfor; ?>
    </div>

    <!-- Test Information -->
    <div class="test-info">
        <h4>Test Information</h4>
        <p><strong>Category:</strong> <?= htmlspecialchars($categoryName) ?></p>
        <p><strong>Test:</strong> <?= htmlspecialchars($testTitle) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($testDescription ?? 'No description available.') ?></p>
        <p><strong>Chain Length:</strong> <?= htmlspecialchars((string)($steps ?? 3)) ?> sequential requests</p>
        <p><strong>Delay per Step:</strong> 500ms</p>
        <p><strong>Debug Hash:</strong> <code><?= htmlspecialchars($debugHash) ?></code></p>
        <p class="mt-2">
            <em>Products will load one by one in sequence. Each product requires the previous one
            to complete before loading. This tests how search engine bots handle waterfall/chain
            AJAX patterns.</em>
        </p>
    </div>

    <!-- Navigation -->
    <nav class="catalog-nav">
        <a href="/">&larr; Back to Home</a>
        <?php if (!empty($nextTest)): ?>
        <a href="/lab/<?= htmlspecialchars($category) ?>/<?= htmlspecialchars($nextTest) ?>">Next Test &rarr;</a>
        <?php endif; ?>
    </nav>
</div>
