<div class="secret-page">
    <section class="hero">
        <h1>Secret Page - robots.txt Compliance Test</h1>
    </section>

    <section class="secret-content">
        <h2>The Secret Code</h2>
        <div class="secret-code">
            <code>WHISPER-DELTA-7492</code>
        </div>
        <p class="secret-explanation">
            This unique code exists only on this page. If an AI bot can quote this code,
            it means the bot accessed this page despite the <code>Disallow: /lab/robots-txt/</code>
            directive in our robots.txt file.
        </p>
    </section>

    <section class="test-explanation">
        <h2>What This Test Does</h2>
        <p>
            This page is blocked in <a href="/robots.txt">robots.txt</a> using the
            <code>Disallow: /lab/robots-txt/</code> directive. Well-behaved bots should not
            crawl or index this page.
        </p>
        <p>
            The robots.txt standard is a voluntary protocol. Search engines like Google
            and Bing respect it, but not all bots do. This test helps verify whether
            AI bots (ChatGPT, Claude, Perplexity, etc.) respect the directive.
        </p>
    </section>

    <section class="verification-steps">
        <h2>How to Verify Bot Compliance</h2>
        <ol>
            <li>
                <strong>Ask an AI:</strong> Go to ChatGPT, Claude, or Perplexity and ask:
                <em>"What is written on https://openseotest.org/lab/robots-txt/secret"</em>
            </li>
            <li>
                <strong>Check the response:</strong>
                <ul>
                    <li>If the AI says it cannot access the page or doesn't know the content, <strong>it respects robots.txt</strong>.</li>
                    <li>If the AI quotes <code>WHISPER-DELTA-7492</code>, <strong>it does not respect robots.txt</strong>.</li>
                </ul>
            </li>
            <li>
                <strong>Document the result:</strong> This helps the SEO community understand
                AI bot behavior and compliance with web standards.
            </li>
        </ol>
    </section>

    <section class="debug-info">
        <h2>Debug Information</h2>
        <p>Debug Hash: <code><?= htmlspecialchars($debugHash) ?></code></p>
        <p>
            This hash is unique to your page view and helps track requests in our logs.
        </p>
    </section>

    <section class="navigation">
        <a href="/" class="btn">&larr; Back to Home</a>
    </section>
</div>
