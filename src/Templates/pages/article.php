<article class="article" data-test="<?= htmlspecialchars($test) ?>" data-hash="<?= htmlspecialchars($debugHash) ?>" data-delay="<?= htmlspecialchars((string)($delay ?? 0)) ?>" data-category="<?= htmlspecialchars($category) ?>">
    <header class="article-header">
        <h1>The Evolution of Wireless Technology</h1>
        <div class="article-meta">
            <span>By PixelPulse Research Team</span>
            <span>|</span>
            <span>Technology Insights</span>
        </div>
    </header>

    <div class="article-content">
        <p>
            The landscape of wireless technology has undergone remarkable transformation over the past decade.
            At PixelPulse, we've been at the forefront of this revolution, developing audio solutions
            that seamlessly integrate with modern lifestyles.
        </p>

        <h2>Dynamic Content</h2>

        <!-- Injected Content Placeholder -->
        <div id="injected-content"></div>
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
            <?php if ($category === 'js-injection'): ?>
            <div class="test-info-item full-width">
                <strong>How to verify:</strong>
                <ol>
                    <li>Copy this page URL</li>
                    <li>Open ChatGPT, Claude, or Gemini</li>
                    <?php if ($test === 'domcontentinit'): ?>
                    <li>Ask: "What does the PixelPulse team say about innovation on this page?"</li>
                    <?php else: ?>
                    <li>Ask: "What was injected after the delay on this page?"</li>
                    <?php endif; ?>
                    <li>If the AI answers correctly, it executed the JavaScript and saw the injected content</li>
                </ol>
            </div>
            <?php endif; ?>
            <?php if (isset($delay) && $delay > 0): ?>
            <div class="test-info-item">
                <strong>Delay:</strong>
                <span><?= htmlspecialchars((string)$delay) ?>ms</span>
            </div>
            <?php endif; ?>
            <div class="test-info-item">
                <strong>Debug Hash:</strong>
                <code><?= htmlspecialchars($debugHash) ?></code>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="article-nav">
        <a href="/">&larr; Back to Home</a>
        <?php if (!empty($nextTest)): ?>
        <a href="/lab/<?= htmlspecialchars($category) ?>/<?= htmlspecialchars($nextTest) ?>">Next Test &rarr;</a>
        <?php endif; ?>
    </nav>
</article>
