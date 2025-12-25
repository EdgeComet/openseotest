/**
 * openseotest.org - Beacon Tracking
 *
 * Provides fire-and-forget beacon functionality for tracking
 * JavaScript events (content injection, AJAX completion, etc.)
 */

window.ostBeacon = (function() {
    'use strict';

    /**
     * Send a beacon to the server.
     *
     * Uses navigator.sendBeacon for fire-and-forget delivery,
     * with fetch() POST as fallback.
     *
     * @param {string} hash - The debug hash for this page view
     * @param {string} event - The event name (e.g., 'content-injected')
     * @returns {boolean} - True if beacon was queued successfully
     */
    function send(hash, event) {
        var url = '/api/beacon/' + encodeURIComponent(hash) + '/' + encodeURIComponent(event);

        // Try navigator.sendBeacon first (preferred for unload events)
        if (navigator.sendBeacon) {
            try {
                return navigator.sendBeacon(url, '');
            } catch (e) {
                // Fall through to fetch
            }
        }

        // Fallback to fetch
        try {
            fetch(url, {
                method: 'POST',
                keepalive: true,
                headers: {
                    'Content-Type': 'text/plain'
                },
                body: ''
            }).catch(function() {
                // Ignore errors - beacons are fire-and-forget
            });
            return true;
        } catch (e) {
            return false;
        }
    }

    // Public API
    return {
        send: send
    };
})();
