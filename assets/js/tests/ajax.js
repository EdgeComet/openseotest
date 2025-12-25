/**
 * openseotest.org - AJAX Delay Tests
 *
 * Handles fetching product data via AJAX with server-side delay.
 * Tests how search engine bots handle dynamically loaded content.
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        var productPage = document.querySelector('.product-page');
        if (!productPage) return;

        var delay = productPage.getAttribute('data-delay');
        var hash = productPage.getAttribute('data-hash');
        var category = productPage.getAttribute('data-category');

        // Only run for ajax category
        if (category !== 'ajax') return;

        // Elements to update
        var priceEl = document.getElementById('product-price');
        var availabilityEl = document.getElementById('product-availability');
        var shippingEl = document.getElementById('product-shipping');
        var addToCartBtn = document.querySelector('.add-to-cart');

        // Store hash globally for beacon
        window.ostHash = hash;

        // Build fetch URL
        var fetchUrl = '/lab/ajax/delay-' + delay + '/' + hash + '/fetch';

        // Fetch product data
        fetch(fetchUrl)
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function(data) {
                // Update price
                if (priceEl) {
                    priceEl.textContent = data.price;
                    priceEl.classList.remove('loading');
                }

                // Update availability
                if (availabilityEl) {
                    availabilityEl.innerHTML =
                        '<span class="availability-indicator in-stock"></span>' +
                        '<span>' + data.availability + '</span>';
                    availabilityEl.classList.remove('loading');
                }

                // Update shipping
                if (shippingEl) {
                    shippingEl.textContent = data.shipping;
                    shippingEl.classList.remove('loading');
                }

                // Enable add to cart button
                if (addToCartBtn) {
                    addToCartBtn.disabled = false;
                }

                // Add SEO marker
                var marker = document.createElement('span');
                marker.className = 'seo-marker';
                marker.textContent = 'OSTS-' + hash + '-AJAX-' + delay;
                productPage.appendChild(marker);

                // Send beacon
                if (window.ostBeacon) {
                    window.ostBeacon.send(hash, 'ajax-complete');
                }
            })
            .catch(function(error) {
                console.error('AJAX fetch error:', error);

                // Show error state
                if (priceEl) {
                    priceEl.textContent = 'Price unavailable';
                    priceEl.classList.remove('loading');
                    priceEl.classList.add('error');
                }

                if (availabilityEl) {
                    availabilityEl.innerHTML =
                        '<span class="availability-indicator error"></span>' +
                        '<span>Status unknown</span>';
                    availabilityEl.classList.remove('loading');
                }

                if (shippingEl) {
                    shippingEl.textContent = 'Shipping unavailable';
                    shippingEl.classList.remove('loading');
                    shippingEl.classList.add('error');
                }

                // Send error beacon
                if (window.ostBeacon) {
                    window.ostBeacon.send(hash, 'error-occurred');
                }
            });
    });
})();
