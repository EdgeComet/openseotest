/**
 * openseotest.org - Site-wide JavaScript
 *
 * This file contains common functionality used across the site.
 * Test-specific scripts are loaded separately per test page.
 */

// Site namespace
window.OST = window.OST || {};

// Store debug hash for use by other scripts
OST.init = function() {
    const debugBadge = document.querySelector('.debug-badge');
    if (debugBadge) {
        const match = debugBadge.textContent.match(/Debug:\s*([a-f0-9]+)/);
        if (match) {
            OST.hash = match[1];
        }
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', OST.init);
