/**
 * openseotest.org - AJAX Chain Tests
 *
 * Handles sequential fetching of products via AJAX.
 * Tests how search engine bots handle waterfall/chain AJAX patterns.
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        var catalogPage = document.querySelector('.catalog-page');
        if (!catalogPage) return;

        var steps = parseInt(catalogPage.getAttribute('data-steps'), 10);
        var hash = catalogPage.getAttribute('data-hash');
        var category = catalogPage.getAttribute('data-category');

        // Only run for ajax-chain category
        if (category !== 'ajax-chain') return;

        // Store hash globally for beacon
        window.ostHash = hash;

        // Current step counter element
        var stepCounter = document.getElementById('current-step');

        /**
         * Fetch a single product step
         */
        function fetchStep(step) {
            var fetchUrl = '/lab/ajax-chain/' + steps + '-steps/' + hash + '/fetch/' + step;

            return fetch(fetchUrl)
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(function(data) {
                    // Update the product slot
                    updateProductSlot(step, data.product);

                    // Update step counter
                    if (stepCounter) {
                        stepCounter.textContent = step;
                    }

                    // Send beacon for this step
                    if (window.ostBeacon) {
                        window.ostBeacon.send(hash, 'ajax-step-' + step);
                    }

                    // Return hasMore flag for chaining
                    return data.hasMore;
                });
        }

        /**
         * Update a product slot with product data
         */
        function updateProductSlot(step, product) {
            var slot = document.getElementById('product-slot-' + step);
            if (!slot) return;

            // Remove loading state
            slot.classList.remove('loading');

            // Update image
            var imageEl = slot.querySelector('.slot-image');
            if (imageEl) {
                imageEl.innerHTML = '<img src="' + product.image + '" alt="' + escapeHtml(product.name) + '">';
                imageEl.classList.remove('loading');
            }

            // Update name
            var nameEl = slot.querySelector('.slot-name');
            if (nameEl) {
                nameEl.textContent = product.name;
                nameEl.classList.remove('loading');
            }

            // Update price
            var priceEl = slot.querySelector('.slot-price');
            if (priceEl) {
                priceEl.textContent = product.price;
                priceEl.classList.remove('loading');
            }

            // Add SEO marker for this product
            var marker = document.createElement('span');
            marker.className = 'seo-marker';
            marker.textContent = 'OSTS-' + hash + '-CHAIN-STEP-' + step;
            slot.appendChild(marker);
        }

        /**
         * Escape HTML to prevent XSS
         */
        function escapeHtml(text) {
            var div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        /**
         * Load all products sequentially
         */
        function loadSequentially(currentStep) {
            if (currentStep > steps) {
                // All done - add completion marker
                var marker = document.createElement('span');
                marker.className = 'seo-marker';
                marker.textContent = 'OSTS-' + hash + '-CHAIN-' + steps + '-COMPLETE';
                catalogPage.appendChild(marker);

                // Send completion beacon
                if (window.ostBeacon) {
                    window.ostBeacon.send(hash, 'ajax-complete');
                }
                return;
            }

            fetchStep(currentStep)
                .then(function(hasMore) {
                    // Load next step
                    loadSequentially(currentStep + 1);
                })
                .catch(function(error) {
                    console.error('Error loading step ' + currentStep + ':', error);

                    // Mark slot as error
                    var slot = document.getElementById('product-slot-' + currentStep);
                    if (slot) {
                        slot.classList.remove('loading');
                        slot.classList.add('error');
                        var nameEl = slot.querySelector('.slot-name');
                        if (nameEl) {
                            nameEl.textContent = 'Error loading product';
                        }
                    }

                    // Send error beacon
                    if (window.ostBeacon) {
                        window.ostBeacon.send(hash, 'error-occurred');
                    }
                });
        }

        // Start loading sequence
        loadSequentially(1);
    });
})();
