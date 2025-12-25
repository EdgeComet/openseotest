<nav class="main-nav">
    <div class="nav-container">
        <a href="/" class="nav-logo">
            <span class="logo-text">openseotest.org</span>
        </a>
        <div class="nav-links">
            <div class="nav-dropdown">
                <button class="nav-dropdown-btn">Test Categories</button>
                <div class="nav-dropdown-content">
                    <?php
                    $categories = \Ost\TestRegistry::getCategories();
                    foreach ($categories as $slug => $category):
                        $firstTest = array_key_first($category['tests']);
                    ?>
                    <a href="/lab/<?= htmlspecialchars($slug) ?>/<?= htmlspecialchars($firstTest) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <a href="/#about" class="nav-link">About</a>
            <a href="/#how-it-works" class="nav-link">How It Works</a>
        </div>
    </div>
</nav>
