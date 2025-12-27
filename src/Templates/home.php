<div class="home-page">
    <section class="hero">
        <h1>SEO &amp; AI Bot Behavior Testing Platform</h1>
    </section>

    <section class="intro" id="about">
        <h2>About This Project</h2>
        <p>
            <strong>OpenSeoTest</strong> is an open-source platform for performing technical SEO testing.
            The goal is to provide a transparent laboratory to understand how Googlebot,
            search crawlers, and AI bots behave with different JavaScript content and techniques.
        </p>
        <p>
            We believe that the SEO space needs more open and replicable experiments, not just opinions.
            On this platform, access logs are open for everyone, allowing you to verify your hypotheses
            immediately with real data.
        </p>
        <p>
            We regularly publish our experiment results and deep-dive researches on the
            <a href="https://edgecomet.com/blog/" target="_blank" rel="noopener">EdgeComet Blog</a>.
        </p>
        <p>
            Whether you are testing dynamic rendering implementations or researching bot capabilities, this project enables you to create new tests, deploy them,
            and observe the results.
        </p>
        <div class="features">
            <div class="feature">
                <strong>Open Science</strong>
                <span>Replicable experiments with open access logs</span>
            </div>
            <div class="feature">
                <strong>Bot Behavior</strong>
                <span>Analyze how crawlers render JS & AJAX</span>
            </div>
            <div class="feature">
                <strong>Community Driven</strong>
                <span>Create and deploy your own test cases</span>
            </div>
        </div>
    </section>

    <section class="how-it-works" id="how-it-works">
        <h2>How It Works</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h4>The Debug Hash System</h4>
                    <p>
                        Every request generates a unique 8-character <strong>Debug Hash</strong>. This hash is injected into
                        the URL, HTML content, and X-Debug-Hash headers. It acts as a "fingerprint" that allows us to
                        distinguish between the initial server-side response and subsequent client-side executions.
                    </p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h4>Beacon Correlation</h4>
                    <p>
                        When a bot renders JavaScript, our beacon fires the same unique hash back to the server.
                        This confirms that the JS was executed and allows us to verify exactly what content the bot was able to "see" and interact with.
                    </p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h4>Log Analysis (Coming Soon)</h4>
                    <p>
                        We are building an open dashboard to analyze these hashes in real-time.
                        Soon, you will be able to cross-reference server logs with JS beacon data to see the exact
                        timeline of bot rendering behavior across the entire platform.
                    </p>
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
        <h2>Quick Start: Test AI Capabilities Yourself</h2>
        <p>You don't need to be a developer to see how AI bots handle the web. Try this simple experiment:</p>
        
        <div class="experiment-steps">
            <ol>
                <li>
                    <strong>Pick a test:</strong> Right-click and copy the link for the 
                    <a href="/lab/ajax/delay-2000">AJAX Load (2s)</a> test.
                </li>
                <li>
                    <strong>Open an AI Chat:</strong> Go to ChatGPT, Claude, or Gemini.
                </li>
                <li>
                    <strong>Ask a question:</strong> Paste the link and ask: <em>"What is the price of the product on this page?"</em>
                </li>
                <li>
                    <strong>Verify:</strong> If the AI answers with the correct price (which is generated via JavaScript after a delay), you know it successfully executed the code!
                </li>
            </ol>
        </div>

        <h3>Popular Tests to Try</h3>
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

    <section class="contribute" id="contribute">
        <h2>Open Source & Contributions</h2>
        <p>
            OpenSeoTest is a community-driven project. We encourage SEO researchers and developers to contribute new test cases,
            improve existing ones, or help with the platform's infrastructure.
        </p>
        <div class="cta-container">
            <a href="https://github.com/EdgeComet/openseotest" class="btn btn-github" target="_blank" rel="noopener">
                View on GitHub
            </a>
        </div>
    </section>
</div>
