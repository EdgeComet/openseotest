<article class="article semantic-index" data-category="semantic-html" data-hash="<?= htmlspecialchars($debugHash) ?>">
    <header class="article-header">
        <h1><?= htmlspecialchars($categoryName) ?></h1>
    </header>

    <div class="article-content">
        <p>
            This test compares how AI systems interpret semantic HTML (meaningful CSS class names like
            <code>in-stock</code>, <code>urgent-hiring</code>) versus utility CSS classes (Tailwind classes
            like <code>bg-green-500</code>, <code>border-orange-500</code>). Both versions render identically
            to humans, but the class names carry different semantic signals.
        </p>

        <h2>Test Pages</h2>

        <h3>Product Page</h3>
        <ul>
            <li><a href="/lab/semantic-html/product-a">Product Page (Semantic)</a> - Uses semantic class names</li>
            <li><a href="/lab/semantic-html/product-b">Product Page (Tailwind)</a> - Uses utility class names</li>
        </ul>

        <h3>Job Listing</h3>
        <ul>
            <li><a href="/lab/semantic-html/job-a">Job Listing (Semantic)</a> - Uses semantic class names</li>
            <li><a href="/lab/semantic-html/job-b">Job Listing (Tailwind)</a> - Uses utility class names</li>
        </ul>
    </div>

    <!-- Test Information -->
    <div class="test-info">
        <h4>Test Information</h4>
        <div class="test-info-grid">
            <div class="test-info-item">
                <strong>Category:</strong>
                <span><?= htmlspecialchars($categoryName) ?></span>
            </div>
            <div class="test-info-item full-width">
                <strong>Description:</strong>
                <p><?= htmlspecialchars($category['description'] ?? 'Tests AI comprehension of semantic HTML vs utility CSS classes') ?></p>
            </div>
            <div class="test-info-item">
                <strong>Debug Hash:</strong>
                <code><?= htmlspecialchars($debugHash) ?></code>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="article-nav">
        <a href="/">&larr; Back to Home</a>
    </nav>
</article>
