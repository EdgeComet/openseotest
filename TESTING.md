# Testing Guide for openseotest.org

This document provides instructions for testing the openseotest.org application.

## Running Automated Tests

### PHPUnit Tests

Run all tests:

```bash
./vendor/bin/phpunit
```

Run specific test file:

```bash
./vendor/bin/phpunit tests/SitemapTest.php
```

Run with verbose output:

```bash
./vendor/bin/phpunit --verbose
```

Run with coverage (requires Xdebug):

```bash
./vendor/bin/phpunit --coverage-html coverage/
```

### Expected Results

- All tests should pass (green)
- Current test count: 179 tests, 635+ assertions
- PHPUnit deprecation warnings are expected and can be ignored

---

## Manual Testing Checklist

Use this checklist to verify all functionality works correctly.

### Prerequisites

Start the development server:

```bash
php -S localhost:8000 -t public
```

### Homepage & Navigation

- [ ] Homepage loads at http://localhost:8000/
- [ ] Page title shows "openseotest.org"
- [ ] Debug badge visible in bottom-right corner with 8-character hash
- [ ] All 6 test categories are displayed
- [ ] Each category links to its first test
- [ ] Navigation menu works
- [ ] "About" section is present
- [ ] "How It Works" section is present
- [ ] Footer contains Edge Comet link

### JavaScript Injection Tests

- [ ] `/lab/js-injection/domcontentinit` - Content injects on page load
- [ ] `/lab/js-injection/timeout-100` - Content appears after ~100ms
- [ ] `/lab/js-injection/timeout-500` - Content appears after ~500ms
- [ ] `/lab/js-injection/timeout-1000` - Content appears after ~1 second
- [ ] `/lab/js-injection/timeout-2000` - Content appears after ~2 seconds
- [ ] SEO markers visible in DOM (inspect element)
- [ ] Beacon requests fire (check Network tab)

### AJAX Delay Tests

- [ ] `/lab/ajax/delay-500` - Price loads after ~500ms
- [ ] `/lab/ajax/delay-1000` - Price loads after ~1 second
- [ ] `/lab/ajax/delay-2000` - Price loads after ~2 seconds
- [ ] `/lab/ajax/delay-3000` - Price loads after ~3 seconds
- [ ] `/lab/ajax/delay-4000` - Price loads after ~4 seconds
- [ ] `/lab/ajax/delay-5000` - Price loads after ~5 seconds
- [ ] Loading indicators show before content
- [ ] Availability and shipping info appear with price

### AJAX Chain Tests

- [ ] `/lab/ajax-chain/3-steps` - 3 products load sequentially
- [ ] `/lab/ajax-chain/5-steps` - 5 products load sequentially
- [ ] Products appear one at a time (~500ms apart)
- [ ] Loading spinners visible during fetch
- [ ] All products display name, price, and image

### JavaScript Error Tests

- [ ] `/lab/js-errors/syntax-before` - Console shows syntax error, no content injected
- [ ] `/lab/js-errors/runtime-before` - Console shows ReferenceError, no content injected
- [ ] `/lab/js-errors/error-between` - First content appears, second does not

### Realtime Timer Test

- [ ] `/lab/realtime/timer` - Timer counts from 0 to 15 seconds
- [ ] Timer updates every 300ms (smooth counting)
- [ ] Status loads after ~2 seconds showing "Online"
- [ ] Both timer and status have SEO markers

### HTTP Status Tests

- [ ] `/lab/http-status/301` - Redirects to target page (check Network tab)
- [ ] `/lab/http-status/302` - Redirects to target page
- [ ] `/lab/http-status/404` - Returns 404 with custom error page
- [ ] `/lab/http-status/500` - Returns 500 with custom error page
- [ ] `/lab/http-status/503` - Returns 503 with custom error page

### Error Pages

- [ ] Random URL (e.g., `/nonexistent`) shows styled 404 page
- [ ] 404 page has navigation to homepage
- [ ] 404 page shows test category links
- [ ] 404 page has debug hash

### Assets & Resources

- [ ] CSS loads correctly (dark theme visible)
- [ ] JavaScript files load without errors
- [ ] Beacon.js available
- [ ] `/robots.txt` accessible
- [ ] `/sitemap.xml` returns valid XML

---

## Testing with curl

### Check Response Headers

```bash
# Homepage - should return 200 with X-Debug-Hash
curl -I http://localhost:8000/

# Check for debug hash header
curl -I http://localhost:8000/ 2>/dev/null | grep -i x-debug-hash
```

### Test HTTP Status Codes

```bash
# 301 Redirect
curl -I http://localhost:8000/lab/http-status/301

# 404 Error
curl -I http://localhost:8000/lab/http-status/404

# 500 Error
curl -I http://localhost:8000/lab/http-status/500

# Real 404 (unknown route)
curl -I http://localhost:8000/this-does-not-exist
```

### Test AJAX Endpoints

```bash
# AJAX fetch (should take ~500ms)
time curl -s http://localhost:8000/lab/ajax/delay-500/abc12345/fetch

# AJAX chain step
curl -s http://localhost:8000/lab/ajax-chain/3-steps/abc12345/fetch/1
```

### Test Beacon Endpoint

```bash
# Valid beacon
curl -X POST -I http://localhost:8000/api/beacon/abc12345/js-executed

# Invalid event (should return 400)
curl -X POST -I http://localhost:8000/api/beacon/abc12345/invalid-event
```

### Test Sitemap

```bash
# Get sitemap
curl -s http://localhost:8000/sitemap.xml | head -20

# Validate XML structure
curl -s http://localhost:8000/sitemap.xml | xmllint --noout -
```

---

## Log Verification

### Development Logs

PHP errors are logged to:

```bash
tail -f logs/php_errors.log
```

### Nginx Logs (Production)

Access log with debug hash:

```bash
tail -f /var/log/nginx/openseotest.access.log
```

Look for the `hash=` field in log entries:

```
2024-12-25T10:00:00+00:00 192.168.1.1 "Mozilla/5.0..." "/lab/ajax/delay-500" 200 0.512 hash=abc12345 x_hash=abc12345
```

### Beacon Tracking

In development, beacon events are logged to error_log. Check for:

```
Beacon: hash=abc12345, event=js-executed
Beacon: hash=abc12345, event=content-injected
```

---

## Security Verification

### Path Traversal Protection

```bash
# These should all return 403 or 404
curl -I "http://localhost:8000/dist/abc12345/../../../etc/passwd"
curl -I "http://localhost:8000/dist/abc12345/css/../../../etc/passwd"
```

### Input Validation

```bash
# Invalid hash format (should return 400)
curl -s http://localhost:8000/lab/ajax/delay-500/invalid-hash/fetch

# Invalid delay value (should return 400)
curl -s http://localhost:8000/lab/ajax/delay-999/abc12345/fetch

# Invalid beacon event (should return 400)
curl -X POST http://localhost:8000/api/beacon/abc12345/invalid
```

### Directory Listing

```bash
# These should not list directory contents
curl http://localhost:8000/config/
curl http://localhost:8000/src/
curl http://localhost:8000/tests/
```

---

## Performance Verification

### Response Times

- Homepage should load in < 100ms
- AJAX endpoints should respond in approximately their configured delay + overhead
- Asset files should load quickly with proper caching headers

### Check Asset Caching

```bash
# Check cache headers on assets
curl -I "http://localhost:8000/dist/abc12345/css/site.css"
```

In production (nginx), assets should have:
- `Cache-Control: public, immutable`
- `Expires` header set to 1 year

---

## Browser Testing

For complete testing, verify in multiple browsers:

- [ ] Chrome/Chromium
- [ ] Firefox
- [ ] Safari
- [ ] Edge

Check browser console for:
- No unexpected JavaScript errors (except intentional ones in js-errors tests)
- Network requests completing successfully
- Beacon requests firing

---

## Troubleshooting

### Tests Failing

1. Ensure dependencies are installed: `composer install`
2. Check PHP version: `php -v` (requires 8.3+)
3. Clear any cached data and retry

### Page Not Loading

1. Check PHP server is running
2. Verify port is not in use: `lsof -i :8000`
3. Check PHP error log for issues

### Assets Not Loading

1. Verify assets directory exists with files
2. Check asset URLs in page source match route pattern
3. Test asset route directly with curl

### Beacons Not Firing

1. Check browser console for JavaScript errors
2. Verify beacon.js is loaded
3. Check Network tab for beacon requests
