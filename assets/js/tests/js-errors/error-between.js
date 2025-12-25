// This file has an error between two content injections
// Test: error-between
// Purpose: Verify that bots see partial content when JS crashes mid-execution

document.addEventListener('DOMContentLoaded', function() {
    // First injection - should succeed
    document.getElementById('injected-content-1').innerHTML =
        '<div class="review-card dynamic">' +
            '<div class="review-header">' +
                '<div class="reviewer-info">' +
                    '<div class="reviewer-avatar">MK</div>' +
                    '<div>' +
                        '<div class="reviewer-name">Mike K.</div>' +
                        '<div class="review-date">Loaded dynamically</div>' +
                    '</div>' +
                '</div>' +
                '<div class="review-stars">★★★★★</div>' +
            '</div>' +
            '<div class="review-text">' +
                'First review loaded successfully! This content was injected via JavaScript.' +
            '</div>' +
            '<span class="seo-marker">OSTS-' + window.ostHash + '-FIRST</span>' +
        '</div>';
    window.ostBeacon.send(window.ostHash, 'first-injected');

    // Runtime error - crashes execution
    nonExistent.crash();

    // Second injection - should NOT happen due to error above
    document.getElementById('injected-content-2').innerHTML =
        '<div class="review-card dynamic">' +
            '<div class="review-header">' +
                '<div class="reviewer-info">' +
                    '<div class="reviewer-avatar">AJ</div>' +
                    '<div>' +
                        '<div class="reviewer-name">Alex J.</div>' +
                        '<div class="review-date">Should NOT appear</div>' +
                    '</div>' +
                '</div>' +
                '<div class="review-stars">★★★★☆</div>' +
            '</div>' +
            '<div class="review-text">' +
                'Second review - this should NOT appear because the error occurred before this code.' +
            '</div>' +
            '<span class="seo-marker">OSTS-' + window.ostHash + '-SECOND</span>' +
        '</div>';
    window.ostBeacon.send(window.ostHash, 'second-injected');
});
