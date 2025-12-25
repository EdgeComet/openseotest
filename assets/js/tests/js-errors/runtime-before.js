// This file has a runtime error before injection
// Test: runtime-before
// Purpose: Verify that bots correctly handle pages where JS throws at runtime

document.addEventListener('DOMContentLoaded', function() {
    // This line throws ReferenceError - stops execution
    undefinedVariable.doSomething();

    // This code will never execute
    document.getElementById('injected-content-1').innerHTML =
        '<p>This should NOT appear</p>' +
        '<span class="seo-marker">OSTS-ERROR-SHOULD-NOT-SEE</span>';
    window.ostBeacon.send(window.ostHash, 'content-injected');
});
