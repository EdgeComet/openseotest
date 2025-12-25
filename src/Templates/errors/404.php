<div class="error-page error-404">
    <div class="error-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <path d="M16 16s-1.5-2-4-2-4 2-4 2"/>
            <line x1="9" y1="9" x2="9.01" y2="9"/>
            <line x1="15" y1="9" x2="15.01" y2="9"/>
        </svg>
    </div>

    <h1>Page Not Found</h1>

    <p class="error-code">Error 404</p>

    <div class="error-message">
        <?php if (!empty($requestedPath)): ?>
        <p>The page <code><?= htmlspecialchars($requestedPath) ?></code> could not be found.</p>
        <?php else: ?>
        <p>The page you're looking for doesn't exist or has been moved.</p>
        <?php endif; ?>
    </div>

    <div class="error-suggestions">
        <h2>What would you like to do?</h2>

        <div class="suggestion-links">
            <a href="/" class="suggestion-link primary">
                <span class="link-icon">&#8962;</span>
                Go to Homepage
            </a>
        </div>

        <?php if (!empty($categories)): ?>
        <div class="test-categories-hint">
            <h3>Or explore our test categories:</h3>
            <ul class="category-list">
                <?php foreach ($categories as $slug => $category): ?>
                <li>
                    <a href="/lab/<?= htmlspecialchars($slug) ?>/<?= htmlspecialchars(array_key_first($category['tests'])) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>

    <div class="error-debug">
        <p>Debug Hash: <code><?= htmlspecialchars($debugHash) ?></code></p>
    </div>
</div>

<style>
.error-page {
    text-align: center;
    padding: 3rem 1rem;
    max-width: 600px;
    margin: 0 auto;
}

.error-icon {
    color: #6b7280;
    margin-bottom: 1.5rem;
}

.error-icon svg {
    opacity: 0.6;
}

.error-page h1 {
    font-size: 2.5rem;
    color: #e5e7eb;
    margin-bottom: 0.5rem;
}

.error-code {
    font-size: 1.25rem;
    color: #9ca3af;
    margin-bottom: 1.5rem;
}

.error-message {
    color: #d1d5db;
    margin-bottom: 2rem;
}

.error-message code {
    background: rgba(255, 255, 255, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-family: monospace;
}

.error-suggestions h2 {
    font-size: 1.25rem;
    color: #e5e7eb;
    margin-bottom: 1rem;
}

.suggestion-links {
    margin-bottom: 2rem;
}

.suggestion-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: #3b82f6;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    transition: background 0.2s;
}

.suggestion-link:hover {
    background: #2563eb;
}

.link-icon {
    font-size: 1.25rem;
}

.test-categories-hint {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.test-categories-hint h3 {
    font-size: 1rem;
    color: #9ca3af;
    margin-bottom: 1rem;
    font-weight: normal;
}

.category-list {
    list-style: none;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
}

.category-list li a {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.05);
    color: #93c5fd;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.875rem;
    transition: background 0.2s;
}

.category-list li a:hover {
    background: rgba(255, 255, 255, 0.1);
}

.error-debug {
    margin-top: 3rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    font-size: 0.75rem;
    color: #6b7280;
}

.error-debug code {
    background: rgba(255, 255, 255, 0.05);
    padding: 0.125rem 0.375rem;
    border-radius: 3px;
}
</style>
