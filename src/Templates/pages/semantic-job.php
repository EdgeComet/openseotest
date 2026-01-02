<?php $isTailwind = str_ends_with($test, '-b'); ?>
<?php if ($isTailwind): ?>
<script src="https://cdn.tailwindcss.com"></script>
<?php endif; ?>
<div class="semantic-job <?= $isTailwind ? 'border-2 border-orange-500 rounded-lg p-6' : 'job-card urgent-hiring' ?>" data-test="<?= htmlspecialchars($test) ?>" data-hash="<?= htmlspecialchars($debugHash) ?>" data-category="<?= htmlspecialchars($category) ?>">
    <!-- Job Header -->
    <header class="job-header">
        <span class="job-company">Nexus Software</span>
        <h1 class="job-title">Senior Frontend Developer</h1>
        <div class="job-location-wrapper">
            <span class="job-location">San Francisco, CA</span>
            <span class="<?= $isTailwind ? 'text-gray-600 text-sm' : 'work-type-indicator remote' ?>">&#127968;</span>
        </div>
    </header>

    <!-- Job Meta - Semantic signals -->
    <div class="job-meta">
        <span class="<?= $isTailwind ? 'text-gray-600 text-sm' : 'experience-indicator senior-level' ?>">&#11088;</span>
    </div>

    <!-- Salary -->
    <div class="job-salary">
        <span class="salary-label">Salary:</span>
        <span class="<?= $isTailwind ? 'font-semibold text-gray-900' : 'salary-amount salary-confirmed' ?>">$150,000 - $180,000</span>
    </div>

    <!-- Job Description -->
    <div class="job-description">
        <h2>About This Role</h2>
        <p>
            We're looking for an experienced frontend developer to join our team. You'll work on
            cutting-edge web applications using React, TypeScript, and modern tooling. Strong
            component architecture skills required.
        </p>
    </div>

    <!-- Requirements -->
    <div class="job-requirements">
        <h3>Requirements</h3>
        <ul>
            <li>5+ years of experience with React and TypeScript</li>
            <li>Strong understanding of component architecture and state management</li>
            <li>Experience with modern build tools (Vite, Webpack, esbuild)</li>
            <li>Excellent communication and collaboration skills</li>
        </ul>
    </div>

    <!-- Apply Button -->
    <button class="apply-button">Apply Now</button>

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
            <div class="test-info-item">
                <strong>Debug Hash:</strong>
                <code><?= htmlspecialchars($debugHash) ?></code>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="article-nav">
        <a href="/lab/semantic-html">&larr; Back to Semantic HTML Tests</a>
        <?php if (!empty($nextTest)): ?>
        <a href="/lab/<?= htmlspecialchars($category) ?>/<?= htmlspecialchars($nextTest) ?>">Next Test &rarr;</a>
        <?php endif; ?>
    </nav>
</div>
