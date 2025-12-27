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
                <strong>Chain Length:</strong>
                <span><?= htmlspecialchars((string)($steps ?? 3)) ?> sequential requests</span>
            </div>
            <div class="test-info-item">
                <strong>Debug Hash:</strong>
                <code><?= htmlspecialchars($debugHash) ?></code>
            </div>
            <div class="test-info-item full-width">
                <strong>Note:</strong>
                <p>
                    Products will load one by one in sequence. Each product requires the previous one
                    to complete before loading.
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="catalog-nav">
        <a href="/">&larr; Back to Home</a>
        <?php if (!empty($nextTest)): ?>
        <a href="/lab/<?= htmlspecialchars($category) ?>/<?= htmlspecialchars($nextTest) ?>">Next Test &rarr;</a>
        <?php endif; ?>
    </nav>
</div>
