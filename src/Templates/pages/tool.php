<div class="tool-page" data-test="<?= htmlspecialchars($test) ?>" data-hash="<?= htmlspecialchars($debugHash) ?>" data-duration="<?= htmlspecialchars((string)($duration ?? 15000)) ?>" data-category="<?= htmlspecialchars($category) ?>">
    <!-- Tool Header -->
    <header class="tool-header">
        <div class="brand">PixelPulse</div>
        <h1>Performance Monitor</h1>
        <p class="subtitle">Real-time System Status Dashboard</p>
    </header>

    <!-- Dashboard Grid -->
    <div class="dashboard-grid">
        <!-- Timer Card (Primary) -->
        <div class="dashboard-card primary">
            <div class="card-label">Elapsed Time</div>
            <div class="timer-display" id="timer-display">
                <span class="seconds">0</span><span class="milliseconds">.0</span>s
            </div>
            <div class="timer-status" id="timer-status">Initializing...</div>
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill" id="timer-progress"></div>
                </div>
                <div class="progress-label">
                    <span>0s</span>
                    <span>15s</span>
                </div>
            </div>
        </div>

        <!-- System Status Card -->
        <div class="dashboard-card">
            <div class="card-label">System Status</div>
            <div class="status-indicator">
                <span class="status-dot loading" id="status-dot"></span>
                <span class="metric-value loading" id="system-status">Checking...</span>
            </div>
        </div>

        <!-- Uptime Card -->
        <div class="dashboard-card">
            <div class="card-label">Uptime</div>
            <div class="metric-value loading" id="system-uptime">--.--%</div>
        </div>

        <!-- Latency Card -->
        <div class="dashboard-card">
            <div class="card-label">Latency</div>
            <div class="metric-value loading" id="system-latency">---ms</div>
        </div>

        <!-- Response Time Card -->
        <div class="dashboard-card">
            <div class="card-label">Avg Response</div>
            <div class="metric-value loading" id="avg-response">---ms</div>
        </div>
    </div>

    <!-- Activity Log -->
    <div class="dashboard-card">
        <div class="card-label">Activity Log</div>
        <div class="activity-log" id="activity-log">
            <div class="log-entry">
                <span class="log-time">--:--:--</span>
                <span class="log-message">Waiting for initialization...</span>
            </div>
        </div>
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
                <strong>Timer Duration:</strong>
                <span>15 seconds (updates every 300ms)</span>
            </div>
            <div class="test-info-item">
                <strong>Status Fetch:</strong>
                <span>2 second server delay</span>
            </div>
            <div class="test-info-item">
                <strong>Debug Hash:</strong>
                <code><?= htmlspecialchars($debugHash) ?></code>
            </div>
            <div class="test-info-item full-width">
                <strong>Note:</strong>
                <p>
                    This page tests continuous JavaScript updates. The timer counts from 0 to 15
                    seconds with updates every 300ms. Simultaneously, an AJAX request fetches system
                    status with a 2-second delay.
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="tool-nav">
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
