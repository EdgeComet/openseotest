<article class="article" data-test="<?= htmlspecialchars($test) ?>" data-hash="<?= htmlspecialchars($debugHash) ?>" data-delay="<?= htmlspecialchars((string)($delay ?? 0)) ?>" data-category="<?= htmlspecialchars($category) ?>">
    <header class="article-header">
        <h1>The History of the Internet</h1>
        <div class="article-meta">
            <span>OpenSeoTest Reference Article</span>
            <span>|</span>
            <span>Technology History</span>
        </div>
    </header>

    <div class="article-content">
        <p>
            The Internet, a global network connecting billions of devices, has transformed how humanity
            communicates, works, and accesses information. Its development spans several decades of
            innovation and collaboration.
        </p>

        <h2>ARPANET: The Beginning (1969)</h2>
        <p>
            The Internet's origins trace back to ARPANET (Advanced Research Projects Agency Network),
            funded by the U.S. Department of Defense. On October 29, 1969, the first message was sent
            between UCLA and Stanford Research Institute. The intended message was "LOGIN" but the
            system crashed after transmitting just "LO".
        </p>

        <h2>TCP/IP Protocol (1983)</h2>
        <p>
            On January 1, 1983, ARPANET adopted TCP/IP (Transmission Control Protocol/Internet Protocol),
            developed by Vint Cerf and Bob Kahn. This date is often considered the official birthday
            of the Internet. TCP/IP remains the fundamental communication protocol of the Internet today.
        </p>

        <h2>World Wide Web (1991)</h2>
        <p>
            Tim Berners-Lee, a British scientist at CERN, invented the World Wide Web in 1989 and
            released it publicly on August 6, 1991. The first website was info.cern.ch. Berners-Lee
            also created HTML, URLs, and HTTP - the foundational technologies of the web.
        </p>

        <h2>Key Milestones</h2>
        <ul>
            <li><strong>1969:</strong> First ARPANET message sent</li>
            <li><strong>1971:</strong> Ray Tomlinson sends the first email</li>
            <li><strong>1983:</strong> DNS (Domain Name System) introduced</li>
            <li><strong>1991:</strong> World Wide Web goes public</li>
            <li><strong>1993:</strong> Mosaic browser released, making the web accessible</li>
            <li><strong>1998:</strong> Google founded by Larry Page and Sergey Brin</li>
            <li><strong>2004:</strong> Facebook launched from a Harvard dorm room</li>
            <li><strong>2007:</strong> iPhone introduced, accelerating mobile internet</li>
        </ul>

        <h2>Internet Statistics</h2>
        <p>
            As of 2024, over 5.4 billion people use the Internet worldwide, representing approximately
            67% of the global population. The number of websites has exceeded 1.9 billion, though only
            about 400 million are actively maintained.
        </p>
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
            <div class="test-info-item full-width">
                <strong>How to verify:</strong>
                <ol>
                    <li>Copy this page URL</li>
                    <li>Open ChatGPT, Claude, or Perplexity</li>
                    <li>Ask: "According to this page, when was the first ARPANET message sent?"</li>
                    <li>Correct answer: October 29, 1969</li>
                    <li>If the AI times out or cannot access the page, the server delay exceeded its wait threshold</li>
                </ol>
            </div>
            <?php if (isset($delay) && $delay > 0): ?>
            <div class="test-info-item">
                <strong>Server Delay:</strong>
                <span><?= htmlspecialchars((string)($delay / 1000)) ?> second<?= $delay > 1000 ? 's' : '' ?></span>
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
