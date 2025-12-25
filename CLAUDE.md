# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

openseotest.org is an open-source SEO and AI bot behavior testing platform. It verifies how search engine crawlers (Googlebot, Bingbot) and AI crawlers (GPTBot, ClaudeBot, PerplexityBot) handle JavaScript rendering, AJAX requests, and dynamic content injection.

**Key principle**: The platform provides identical responses to bots and humans—no runtime bot detection. All bot classification happens during log analysis.

## Build & Development Commands

```bash
# Install dependencies
composer install

# Development server
php -S localhost:8000 -t public

# Run all tests (179 tests, 635+ assertions)
./vendor/bin/phpunit

# Run specific test file
./vendor/bin/phpunit tests/SitemapTest.php

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage/
```

## Architecture

**Stack**: Pure PHP 8.3 (no framework), PHPUnit 11.0, Nginx, PHP-FPM

**Request flow**:
```
Request → public/index.php → Router → Controller → View → Response
```

**Core components** (`src/`):
- `Router.php` - URL pattern matching with parametric segments (`/lab/{category}/{test}`) and wildcards (`/dist/{hash}/{path:*}`)
- `HashGenerator.php` - Creates 8-char debug hashes for request tracking across assets and beacons
- `TestRegistry.php` - Loads/validates test definitions from `config/tests.php`
- `Controllers/LabController.php` - Handles all test pages and AJAX endpoints

**Debug hash system**: Every page visit generates a unique hash (e.g., `a7f3b2c1`) that:
- Embeds in asset URLs: `/dist/a7f3b2c1/css/site.css`
- Sets `X-Debug-Hash` response header
- Used in beacon tracking: `/api/beacon/a7f3b2c1/js-executed`

## Key Routes

| Pattern | Controller | Purpose |
|---------|------------|---------|
| `/lab/{category}/{test}` | LabController::show | Test pages |
| `/lab/ajax/delay-{delay}/{hash}/fetch` | LabController::ajaxFetch | AJAX content |
| `/api/beacon/{hash}/{event}` | ApiController::beacon | JS execution tracking |
| `/dist/{hash}/{path:*}` | AssetController::serve | Static assets |

## Test Categories

Defined in `config/tests.php`:
- **js-injection**: Text injected at DOMContentLoaded or various delays (100-2000ms)
- **ajax**: Product data loaded via single AJAX call (500-5000ms delays)
- **ajax-chain**: Sequential AJAX calls (3 or 5 steps)
- **js-errors**: Tests crawler behavior with JavaScript errors
- **realtime**: Interactive timer with status polling
- **http-status**: HTTP status code handling (301, 302, 404, 500, 503)

## Directory Structure

```
public/           # Web root (front controller, robots.txt)
src/              # Application code
  Controllers/    # Request handlers
  Templates/      # PHP view templates
    pages/        # Test page templates (article, product, catalog, review, tool)
    partials/     # Reusable components
config/           # app.php, routes.php, tests.php
assets/           # CSS and JS (served via /dist/{hash}/)
tests/            # PHPUnit tests
  Integration/    # Integration tests
deploy/           # Nginx, PHP-FPM, Ansible configs
```

## Configuration

- `config/app.php` - APP_ENV, APP_DEBUG, APP_URL, APP_TIMEZONE
- `config/routes.php` - URL route definitions
- `config/tests.php` - Test category and test definitions
- `.env` - Environment overrides (see `.env.example`)

## Adding New Tests

1. Add test definition to `config/tests.php` under appropriate category
2. Add route if new URL pattern needed in `config/routes.php`
3. Add controller method if new behavior needed
4. Create/update template in `src/Templates/pages/`
5. Add JavaScript if needed in `assets/js/tests/`
