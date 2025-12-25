/**
 * Realtime Timer Test
 *
 * Tests how bots handle pages with continuously updating content.
 * - Timer counts from 0 to 15 seconds, updating every 300ms
 * - AJAX request fetches system status with 2-second server delay
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        var page = document.querySelector('.tool-page');
        if (!page) return;

        var hash = page.dataset.hash || window.ostHash;
        var duration = parseInt(page.dataset.duration, 10) || 15000;

        // DOM elements
        var timerDisplay = document.getElementById('timer-display');
        var timerStatus = document.getElementById('timer-status');
        var timerProgress = document.getElementById('timer-progress');
        var systemStatus = document.getElementById('system-status');
        var systemUptime = document.getElementById('system-uptime');
        var systemLatency = document.getElementById('system-latency');
        var avgResponse = document.getElementById('avg-response');
        var statusDot = document.getElementById('status-dot');
        var activityLog = document.getElementById('activity-log');

        // Timer state
        var startTime = Date.now();
        var timerInterval = null;
        var timerComplete = false;

        /**
         * Add entry to activity log
         */
        function logActivity(message) {
            var now = new Date();
            var timeStr = now.toTimeString().split(' ')[0];
            var entry = document.createElement('div');
            entry.className = 'log-entry';
            entry.innerHTML = '<span class="log-time">' + timeStr + '</span>' +
                '<span class="log-message">' + message + '</span>';

            // Clear initial placeholder if present
            if (activityLog.children.length === 1 &&
                activityLog.firstElementChild.querySelector('.log-message').textContent.includes('Waiting')) {
                activityLog.innerHTML = '';
            }

            activityLog.appendChild(entry);
            activityLog.scrollTop = activityLog.scrollHeight;
        }

        /**
         * Update timer display
         */
        function updateTimer() {
            var elapsed = Date.now() - startTime;
            var seconds = Math.min(elapsed / 1000, duration / 1000);
            var wholeSeconds = Math.floor(seconds);
            var tenths = Math.floor((seconds - wholeSeconds) * 10);

            // Update display
            if (timerDisplay) {
                timerDisplay.innerHTML = '<span class="seconds">' + wholeSeconds + '</span>' +
                    '<span class="milliseconds">.' + tenths + '</span>s';
            }

            // Update progress bar
            if (timerProgress) {
                var progress = (elapsed / duration) * 100;
                timerProgress.style.width = Math.min(progress, 100) + '%';
            }

            // Update status text
            if (timerStatus) {
                if (elapsed < duration) {
                    timerStatus.textContent = 'Running...';
                    timerStatus.className = 'timer-status running';
                } else {
                    timerStatus.textContent = 'Complete';
                    timerStatus.className = 'timer-status complete';
                }
            }

            // Check if timer complete
            if (elapsed >= duration && !timerComplete) {
                timerComplete = true;
                clearInterval(timerInterval);

                // Final update to ensure we show exactly 15.0s
                if (timerDisplay) {
                    timerDisplay.innerHTML = '<span class="seconds">15</span>' +
                        '<span class="milliseconds">.0</span>s';
                }
                if (timerProgress) {
                    timerProgress.style.width = '100%';
                }

                // Add SEO marker
                var marker = document.createElement('span');
                marker.className = 'seo-marker';
                marker.textContent = 'OSTS-' + hash + '-TIMER-15';
                timerDisplay.parentNode.appendChild(marker);

                // Send beacon
                if (window.ostBeacon) {
                    window.ostBeacon.send(hash, 'timer-complete');
                }

                logActivity('Timer completed at 15.0 seconds');
            }
        }

        /**
         * Fetch system status via AJAX
         */
        function fetchStatus() {
            logActivity('Fetching system status...');

            var url = '/lab/realtime/timer/' + hash + '/status';

            fetch(url)
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Status fetch failed');
                    }
                    return response.json();
                })
                .then(function(data) {
                    // Update status elements
                    if (systemStatus) {
                        systemStatus.textContent = data.status;
                        systemStatus.classList.remove('loading');
                    }
                    if (statusDot) {
                        statusDot.classList.remove('loading');
                        statusDot.classList.add(data.status.toLowerCase() === 'online' ? 'online' : 'offline');
                    }
                    if (systemUptime) {
                        systemUptime.textContent = data.uptime;
                        systemUptime.classList.remove('loading');
                    }
                    if (systemLatency) {
                        systemLatency.textContent = data.latency;
                        systemLatency.classList.remove('loading');
                    }
                    if (avgResponse) {
                        avgResponse.textContent = data.avgResponse || '45ms';
                        avgResponse.classList.remove('loading');
                    }

                    // Add SEO marker
                    var marker = document.createElement('span');
                    marker.className = 'seo-marker';
                    marker.textContent = 'OSTS-' + hash + '-STATUS-LOADED';
                    if (systemStatus) {
                        systemStatus.parentNode.appendChild(marker);
                    }

                    // Send beacon
                    if (window.ostBeacon) {
                        window.ostBeacon.send(hash, 'ajax-complete');
                    }

                    logActivity('System status: ' + data.status + ' (Uptime: ' + data.uptime + ')');
                })
                .catch(function(error) {
                    if (systemStatus) {
                        systemStatus.textContent = 'Error';
                        systemStatus.classList.remove('loading');
                        systemStatus.classList.add('error');
                    }
                    if (statusDot) {
                        statusDot.classList.remove('loading');
                        statusDot.classList.add('error');
                    }
                    logActivity('Error fetching status: ' + error.message);
                });
        }

        // Initialize
        logActivity('Performance Monitor initialized');
        logActivity('Timer started (duration: ' + (duration / 1000) + 's)');

        // Start timer - update every 300ms
        timerInterval = setInterval(updateTimer, 300);
        updateTimer(); // Initial update

        // Fetch status (has 2-second server delay)
        fetchStatus();
    });
})();
