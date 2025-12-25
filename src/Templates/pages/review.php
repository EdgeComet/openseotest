<div class="review-page" data-test="<?= htmlspecialchars($test) ?>" data-hash="<?= htmlspecialchars($debugHash) ?>" data-category="<?= htmlspecialchars($category) ?>">
    <!-- Product Being Reviewed -->
    <div class="reviewed-product">
        <div class="reviewed-product-image">
            <img src="https://picsum.photos/seed/pixelpulse-review/120/120" alt="PixelPulse Pro">
        </div>
        <div class="reviewed-product-info">
            <span class="brand">PixelPulse Audio</span>
            <h1>Pro Wireless Headphones</h1>
            <div class="overall-rating">
                <span class="stars">★★★★½</span>
                <span class="score">4.5</span>
                <span class="count">(2,847 reviews)</span>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section">
        <h2>Customer Reviews</h2>

        <!-- Static Review 1 -->
        <div class="review-card">
            <div class="review-header">
                <div class="reviewer-info">
                    <div class="reviewer-avatar">JD</div>
                    <div>
                        <div class="reviewer-name">John Doe</div>
                        <div class="review-date">December 15, 2024</div>
                    </div>
                </div>
                <div class="review-stars">★★★★★</div>
            </div>
            <div class="review-text">
                Absolutely amazing sound quality! The noise cancellation is top-notch and the
                battery life is incredible. I use these for my daily commute and they've
                completely transformed my listening experience.
            </div>
        </div>

        <!-- Dynamic Review Placeholder 1 -->
        <div id="injected-content-1"></div>

        <!-- Static Review 2 -->
        <div class="review-card">
            <div class="review-header">
                <div class="reviewer-info">
                    <div class="reviewer-avatar">SM</div>
                    <div>
                        <div class="reviewer-name">Sarah Mitchell</div>
                        <div class="review-date">December 10, 2024</div>
                    </div>
                </div>
                <div class="review-stars">★★★★☆</div>
            </div>
            <div class="review-text">
                Great headphones overall. The comfort is unmatched and the sound is crisp.
                Only minor complaint is that the touch controls can be a bit sensitive.
                Would still highly recommend!
            </div>
        </div>

        <!-- Dynamic Review Placeholder 2 -->
        <div id="injected-content-2"></div>
    </div>

    <!-- Test Information -->
    <div class="test-info">
        <h4>Test Information</h4>
        <p><strong>Category:</strong> <?= htmlspecialchars($categoryName) ?></p>
        <p><strong>Test:</strong> <?= htmlspecialchars($testTitle) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($testDescription ?? 'No description available.') ?></p>
        <p><strong>Debug Hash:</strong> <code><?= htmlspecialchars($debugHash) ?></code></p>

        <?php if ($test === 'syntax-before'): ?>
        <p class="mt-2 warning">
            <strong>Expected Behavior:</strong> This page contains a JavaScript syntax error.
            The script will fail to parse, so NO dynamic content should be injected and NO
            beacons should fire. Check your browser console for the syntax error.
        </p>
        <?php elseif ($test === 'runtime-before'): ?>
        <p class="mt-2 warning">
            <strong>Expected Behavior:</strong> This page contains a JavaScript runtime error
            (ReferenceError) before the injection code. NO dynamic content should appear and
            NO beacons should fire. Check your browser console for the error.
        </p>
        <?php elseif ($test === 'error-between'): ?>
        <p class="mt-2 warning">
            <strong>Expected Behavior:</strong> This page has an error BETWEEN two injection
            attempts. The FIRST review should load successfully, but the SECOND should NOT
            appear due to the runtime error. Only the first beacon should fire.
        </p>
        <?php endif; ?>
    </div>

    <!-- Navigation -->
    <nav class="review-nav">
        <a href="/">&larr; Back to Home</a>
        <?php if (!empty($nextTest)): ?>
        <a href="/lab/<?= htmlspecialchars($category) ?>/<?= htmlspecialchars($nextTest) ?>">Next Test &rarr;</a>
        <?php endif; ?>
    </nav>
</div>

<script>
    // Make hash available globally for JS tests
    window.ostHash = '<?= htmlspecialchars($debugHash) ?>';
</script>
