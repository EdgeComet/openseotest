<div class="home-page">
    <section class="hero">
        <h1>openseotest.org</h1>
        <p class="tagline">SEO &amp; AI Bot Behavior Testing Platform</p>
    </section>

    <section class="intro" id="about">
        <h2>About This Project</h2>
        <p>
            Welcome to openseotest.org, a testing platform designed to analyze how search engine
            crawlers and AI bots handle JavaScript-rendered content, AJAX requests, and various
            HTTP behaviors.
        </p>
        <p>
            This project is built for use with <a href="https://edgecomet.com" target="_blank" rel="noopener">Edge Comet</a>,
            a dynamic rendering service for SEO optimization. Use these tests to verify how your
            content appears to search engines and AI crawlers.
        </p>
        <div class="features">
            <div class="feature">
                <strong>For SEO Researchers</strong>
                <span>Understand how bots render JavaScript content</span>
            </div>
            <div class="feature">
                <strong>For Developers</strong>
                <span>Test your dynamic rendering implementation</span>
            </div>
            <div class="feature">
                <strong>Privacy First</strong>
                <span>No user tracking - only bot behavior analysis</span>
            </div>
        </div>
    </section>

    <section class="how-it-works" id="how-it-works">
        <h2>How It Works</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h4>Debug Hash System</h4>
                    <p>Each page view generates a unique 8-character hash that appears in URLs, page content, and HTTP headers.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h4>Beacon Tracking</h4>
                    <p>JavaScript beacons fire when content is injected, allowing us to correlate bot visits with JS execution.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h4>Log Analysis</h4>
                    <p>Compare nginx logs with beacon logs to see if bots executed JavaScript and when.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="test-index">
        <h2>Test Categories</h2>
        <p class="section-description">Select a category to begin testing. Each test includes specific behaviors to verify bot rendering capabilities.</p>

        <?php foreach ($categories as $slug => $category): ?>
        <div class="category-section" data-category="<?= htmlspecialchars($slug) ?>">
            <div class="category-header">
                <h3><?= htmlspecialchars($category['name']) ?></h3>
                <p class="category-description"><?= htmlspecialchars($category['description']) ?></p>
            </div>
            <div class="test-list">
                <?php foreach ($category['tests'] as $testSlug => $test): ?>
                <a href="/lab/<?= htmlspecialchars($slug) ?>/<?= htmlspecialchars($testSlug) ?>" class="test-card">
                    <span class="test-name"><?= htmlspecialchars($test['title']) ?></span>
                    <?php if (isset($test['delay'])): ?>
                    <span class="test-param"><?= htmlspecialchars($test['delay']) ?>ms</span>
                    <?php elseif (isset($test['steps'])): ?>
                    <span class="test-param"><?= htmlspecialchars($test['steps']) ?> steps</span>
                    <?php elseif (isset($test['code'])): ?>
                    <span class="test-param">HTTP <?= htmlspecialchars($test['code']) ?></span>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </section>

    <section class="quick-start">
        <h2>Quick Start</h2>
        <p>Not sure where to begin? Try these popular tests:</p>
        <div class="quick-links">
            <a href="/lab/js-injection/timeout-500" class="quick-link">
                <strong>JS Injection (500ms)</strong>
                <span>Content appears after half-second delay</span>
            </a>
            <a href="/lab/ajax/delay-2000" class="quick-link">
                <strong>AJAX Load (2s)</strong>
                <span>Product data fetched via AJAX</span>
            </a>
            <a href="/lab/ajax-chain/3-steps" class="quick-link">
                <strong>Chained AJAX</strong>
                <span>Sequential loading of multiple items</span>
            </a>
        </div>
    </section>
</div>
