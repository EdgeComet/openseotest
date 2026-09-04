<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: #f6f8fa;
            color: #1f2328;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            -webkit-text-size-adjust: 100%;
        }

        .prompt {
            width: 100%;
            max-width: 34rem;
        }

        .prompt__text {
            margin: 0 0 1rem;
            padding: 1.25rem;
            background: #ffffff;
            border: 1px solid #d0d7de;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1.0625rem;
            line-height: 1.6;
            white-space: pre-wrap;
            overflow-wrap: break-word;
            -webkit-user-select: text;
            user-select: text;
        }

        .prompt__copy {
            display: block;
            width: 100%;
            padding: 0.9rem 1rem;
            background: #ff6b4a;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }

        .prompt__copy:hover {
            background: #e55a3a;
        }

        .prompt__copy:active {
            background: #e55a3a;
        }
    </style>
</head>
<body>
    <main class="prompt">
        <pre class="prompt__text" id="prompt-text"><?= htmlspecialchars($text) ?></pre>
        <button type="button" class="prompt__copy" id="copy-button">Copy</button>
    </main>

    <script>
        (function () {
            var textEl = document.getElementById('prompt-text');
            var button = document.getElementById('copy-button');
            var resetTimer = null;

            function selectText() {
                var selection = window.getSelection();
                if (!selection) {
                    return;
                }
                var range = document.createRange();
                range.selectNodeContents(textEl);
                selection.removeAllRanges();
                selection.addRange(range);
            }

            function showLabel(label) {
                button.textContent = label;
                clearTimeout(resetTimer);
                resetTimer = setTimeout(function () {
                    button.textContent = 'Copy';
                }, 2000);
            }

            function copyBySelection() {
                selectText();
                var copied = false;
                try {
                    copied = document.execCommand('copy');
                } catch (e) {
                    copied = false;
                }
                showLabel(copied ? 'Copied' : 'Press and hold to copy');
            }

            button.addEventListener('click', function () {
                var text = textEl.textContent;

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(text).then(function () {
                        showLabel('Copied');
                    }, copyBySelection);
                    return;
                }

                copyBySelection();
            });

            textEl.addEventListener('click', selectText);
        })();
    </script>
</body>
</html>
