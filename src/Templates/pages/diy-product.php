<?php
/**
 * DIY SEO test page (Edge SEO Course, lessons 1.4 and 1.5).
 *
 * The HTML the server sends is the lessons' starting state: keep it free of
 * canonical tags, JSON-LD, microdata (itemscope/itemprop) and robots meta tags.
 * Name, price, availability, image, SKU and brand stay in the server HTML so
 * Tag Manager variables or a Worker can read them from the page.
 */
$imageUrl = rtrim((string)\Ost\Config::get('app', 'url', 'https://openseotest.org'), '/') . '/images/arden-ax7-headphones.jpg';
?>
<div class="diy-product" data-test="<?= htmlspecialchars($test) ?>" data-hash="<?= htmlspecialchars($debugHash) ?>" data-category="<?= htmlspecialchars($category) ?>">
    <div class="product-layout">
        <div class="product-image">
            <img src="<?= htmlspecialchars($imageUrl) ?>" alt="Arden AX7 wireless headphones in black" width="1200" height="1200">
        </div>

        <div class="product-details">
            <span class="product-brand">Arden Sound</span>
            <h1 class="product-title">Arden AX7 Wireless Headphones</h1>

            <div class="product-price" data-price="249.00" data-currency="USD">$249.00</div>

            <p class="product-availability"><span class="availability-dot"></span>In stock</p>

            <p class="product-description">
                Over-ear wireless headphones with active noise cancellation, 40-hour battery life
                and memory foam ear cushions. Fold flat for travel.
            </p>

            <p class="product-meta">SKU: <span class="product-sku">ARD-AX7-BLK</span></p>

            <button class="add-to-cart" type="button">Add to cart</button>
        </div>
    </div>

    <div class="product-more">
        <h2>Features</h2>
        <ul>
            <li>Active noise cancellation with a transparency mode</li>
            <li>40 hours of playback, 10 minutes of charging for 4 hours</li>
            <li>Bluetooth 5.3 with multipoint pairing</li>
            <li>Memory foam ear cushions and an aluminium headband</li>
            <li>Carrying case and USB-C cable included</li>
        </ul>
    </div>

    <div class="test-info">
        <h4>Test Information</h4>
        <p>
            Test page for the EdgeComet Academy Edge SEO Course, lessons 1.4 and 1.5.
            The HTML the server sends has a generic title, no canonical tag and no structured data.
        </p>
    </div>

    <nav class="product-nav">
        <a href="/">&larr; Back to Home</a>
    </nav>
</div>
