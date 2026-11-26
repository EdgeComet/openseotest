<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (!empty($gtmId)): ?>
    <!-- Google Tag Manager -->
    <script async src="https://www.googletagmanager.com/gtm.js?id=<?= htmlspecialchars($gtmId) ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    </script>
    <!-- End Google Tag Manager -->
    <?php endif; ?>
    <title><?= htmlspecialchars($title) ?></title>
    <?php if (!empty($description)): ?>
    <meta name="description" content="<?= htmlspecialchars($description) ?>">
    <?php endif; ?>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="stylesheet" href="<?= $asset->css('site.css') ?>">
    <?php if (!empty($templateCss)): ?>
    <link rel="stylesheet" href="<?= $asset->css('templates/' . $templateCss . '.css') ?>">
    <?php endif; ?>
    <?php if (!empty($headJsonLd)): ?>
    <script type="application/ld+json">
<?= json_encode($headJsonLd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?>
    </script>
    <?php endif; ?>
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="container">
        <?= $content ?>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
    <?php include __DIR__ . '/partials/debug-badge.php'; ?>

    <script src="<?= $asset->js('site.js') ?>" defer></script>
    <script src="<?= $asset->js('beacon.js') ?>" defer></script>
    <script src="<?= $asset->js('log-analyzer-link.js') ?>" defer></script>
    <?php if (!empty($templateJs)): ?>
    <script src="<?= $asset->js('tests/' . $templateJs . '.js') ?>" defer></script>
    <?php endif; ?>
</body>
</html>
