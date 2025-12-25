<?php include __DIR__ . '/nav.php'; ?>

<header class="page-header">
    <?php if (!empty($breadcrumbs)): ?>
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <ol>
            <li><a href="/">Home</a></li>
            <?php foreach ($breadcrumbs as $crumb): ?>
            <?php if (isset($crumb['url'])): ?>
            <li><a href="<?= htmlspecialchars($crumb['url']) ?>"><?= htmlspecialchars($crumb['label']) ?></a></li>
            <?php else: ?>
            <li><span aria-current="page"><?= htmlspecialchars($crumb['label']) ?></span></li>
            <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>
    <?php endif; ?>
</header>
