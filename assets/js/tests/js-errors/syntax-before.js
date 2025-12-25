// This file has a syntax error - entire script won't parse
// Test: syntax-before
// Purpose: Verify that bots correctly handle pages where JS fails to parse

function broken( {  // Missing closing paren - syntax error
    console.log('broken');
}

// This code will never execute due to the syntax error above
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('injected-content-1').innerHTML =
        '<p>This should NOT appear</p>' +
        '<span class="seo-marker">OSTS-ERROR-SHOULD-NOT-SEE</span>';
    window.ostBeacon.send(window.ostHash, 'content-injected');
});
