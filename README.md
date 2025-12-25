# OpenSeoTest

An open-source platform for technical SEO testing. Understand how Googlebot, Bingbot, and AI crawlers (ClaudeBot, GPTBot) behave with different JavaScript content and rendering techniques.

Each test generates a unique debug hash that appears in server logs, HTML markers, and beacon tracking — letting you directly verify what bots download vs. what they execute.

**Why this exists:** The SEO space needs more open, replicable experiments, not just opinions.


## What's Tested

| Category | Tests |
|----------|-------|
| **JS Injection** | Content injected via DOMContentLoaded and setTimeout (100ms-2s) |
| **AJAX Delays** | Product data loaded via fetch with server delays (500ms-5s) |
| **AJAX Chains** | Sequential waterfall requests (3-5 steps) |
| **JS Errors** | Syntax errors, runtime errors, partial execution |
| **Realtime** | Continuously updating timer + async status |
| **HTTP Status** | 301, 302, 404, 500, 503 responses |

## Adding Tests

If you have an idea what to test, feel free to create an issue 
and we'll implement it. 

For those who use Claude Code, you can create a test yourself and push a pull request with the implementation.

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




## License

MIT
