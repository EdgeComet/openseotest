/**
 * openseotest.org - JavaScript Injection Tests
 *
 * Handles content injection for js-injection test category.
 * - domcontentinit: Injects content on DOMContentLoaded
 * - timeout-*: Injects content after specified delay
 */

(function() {
    'use strict';

    // Get test configuration from page data attributes
    var pageElement = document.querySelector('[data-test]');
    if (!pageElement) {
        console.error('OST: No element with data-test attribute found');
        return;
    }

    var testName = pageElement.getAttribute('data-test');
    var hash = pageElement.getAttribute('data-hash');
    var delay = parseInt(pageElement.getAttribute('data-delay'), 10) || 0;
    var category = pageElement.getAttribute('data-category');

    // Only run for js-injection category
    if (category !== 'js-injection') {
        return;
    }

    // Make hash available globally for other scripts
    window.ostHash = hash;

    /**
     * Inject content into the page.
     *
     * @param {string} testType - The type of test (domcontentinit or timeout)
     * @param {number} delayMs - The delay in milliseconds (0 for immediate)
     */
    function injectContent(testType, delayMs) {
        var container = document.getElementById('injected-content');
        if (!container) {
            console.error('OST: #injected-content element not found');
            return;
        }

        var timestamp = new Date().toISOString();
        var content;

        if (testType === 'domcontentinit') {
            // The injected fact is deliberately unguessable. The serial number, the
            // location and the numbers below appear nowhere in the server-rendered
            // HTML, so a bot that does not execute JavaScript cannot reproduce them.
            // It either admits it cannot find the value or invents one that is
            // visibly wrong, which makes the result easy to score.
            content = '<div class="injected-content">' +
                '<p>This content was dynamically injected via JavaScript on DOMContentLoaded.</p>' +
                '<p>Cold chamber log, PixelPulse acoustics lab, Tromso, 14 March 2024. ' +
                'Twenty-two prototype units ran for nineteen hours at minus twenty-seven ' +
                'degrees Celsius. One unit drifted out of tolerance and was pulled from the ' +
                'batch: serial number <strong>PPX-4417-KOBALT</strong>. The remaining ' +
                'twenty-one units shipped to the Bergen listening panel.</p>' +
                '<span class="seo-marker">OSTS-' + hash + '-LOADED</span>' +
                '</div>';
        } else {
            content = '<div class="injected-content">' +
                '<p>This content was injected after ' + delayMs + 'ms.</p>' +
                '<p>Timestamp: ' + timestamp + '</p>' +
                '<p>Dynamic content loading via setTimeout demonstrates how search engine bots handle ' +
                'delayed JavaScript execution. The PixelPulse content system supports various timing ' +
                'configurations for optimal user experience.</p>' +
                '<span class="seo-marker">OSTS-' + hash + '-TIMEOUT-' + delayMs + '</span>' +
                '</div>';
        }

        // Add loaded class for styling
        container.classList.add('loaded');
        container.innerHTML = content;

        // Send beacon to track injection
        if (window.ostBeacon && typeof window.ostBeacon.send === 'function') {
            window.ostBeacon.send(hash, 'content-injected');
        }

        console.log('OST: Content injected for test "' + testName + '" with hash "' + hash + '"');
    }

    /**
     * Initialize the test on DOMContentLoaded.
     */
    function init() {
        console.log('OST: Initializing js-injection test "' + testName + '"');

        // Send js-executed beacon
        if (window.ostBeacon && typeof window.ostBeacon.send === 'function') {
            window.ostBeacon.send(hash, 'js-executed');
        }

        if (testName === 'domcontentinit') {
            // Inject immediately on DOMContentLoaded
            injectContent('domcontentinit', 0);
        } else if (testName.startsWith('timeout-')) {
            // Show loading state
            var container = document.getElementById('injected-content');
            if (container) {
                container.innerHTML = '<p class="loading">Loading content...</p>';
            }

            // Inject after specified delay
            setTimeout(function() {
                injectContent('timeout', delay);
            }, delay);
        }
    }

    // Run on DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        // DOM is already ready
        init();
    }
})();
