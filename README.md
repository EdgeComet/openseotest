# openseotest.org

A test suite for analyzing how search engine bots and AI crawlers handle JavaScript-rendered content. Each test page generates a unique debug hash that appears in server logs, asset URLs, and SEO markers - making it easy to correlate what bots actually execute vs. what they index.

## Quick Start

```bash
composer install
php -S localhost:8000 -t public
```

Visit http://localhost:8000

## What's Tested

| Category | Tests |
|----------|-------|
| **JS Injection** | Content injected via DOMContentLoaded and setTimeout (100ms-2s) |
| **AJAX Delays** | Product data loaded via fetch with server delays (500ms-5s) |
| **AJAX Chains** | Sequential waterfall requests (3-5 steps) |
| **JS Errors** | Syntax errors, runtime errors, partial execution |
| **Realtime** | Continuously updating timer + async status |
| **HTTP Status** | 301, 302, 404, 500, 503 responses |

## Adding Tests with Claude Code

### Add a test to an existing category

```
Add a new js-injection test called "timeout-3000" with a 3 second delay
```

Claude Code will update `config/tests.php`:
```php
'timeout-3000' => ['title' => '3000ms Timeout', 'delay' => 3000],
```

### Create a new test category

```
Create a new test category called "lazy-load" that tests lazy-loaded images.
It should have tests for: viewport-trigger, scroll-trigger, and intersection-observer.
Use the article template and create the JavaScript to lazy-load images.
```

Claude Code will:
1. Add the category to `config/tests.php`
2. Create `assets/js/tests/lazy-load.js`
3. Update templates if needed

### The pattern

Tests follow a simple structure:

```
config/tests.php     → Define category + tests
src/Templates/pages/ → HTML structure (5 templates available)
assets/js/tests/     → Client-side behavior
assets/css/templates/→ Styling (optional)
```

Each test automatically gets:
- Unique debug hash in URLs and headers
- SEO markers (`OSTS-{hash}-{test}`) for verification
- Beacon tracking for JS execution events

## Project Structure

```
├── config/
│   ├── tests.php        # Test definitions
│   └── routes.php       # URL routing
├── src/
│   ├── Controllers/     # Request handlers
│   └── Templates/       # PHP templates
├── assets/
│   ├── css/            # Stylesheets
│   └── js/tests/       # Test JavaScript
├── public/             # Web root
└── tests/              # PHPUnit tests
```

## Running Tests

```bash
./vendor/bin/phpunit
```

## Deployment

See [deploy/README.md](deploy/README.md) for Ansible playbook and nginx configuration.

## License

MIT
