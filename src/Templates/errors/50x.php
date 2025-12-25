<?php
$errorMessages = [
    500 => [
        'title' => 'Internal Server Error',
        'message' => 'Something went wrong on our end. The server encountered an unexpected condition.',
    ],
    502 => [
        'title' => 'Bad Gateway',
        'message' => 'The server received an invalid response from an upstream server.',
    ],
    503 => [
        'title' => 'Service Unavailable',
        'message' => 'The server is temporarily unable to handle the request. Please try again later.',
    ],
    504 => [
        'title' => 'Gateway Timeout',
        'message' => 'The server didn\'t receive a timely response from an upstream server.',
    ],
];

$error = $errorMessages[$errorCode] ?? [
    'title' => 'Server Error',
    'message' => 'An unexpected error occurred.',
];
?>
<div class="error-page error-50x">
    <div class="error-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
            <line x1="12" y1="9" x2="12" y2="13"/>
            <line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
    </div>

    <h1><?= htmlspecialchars($error['title']) ?></h1>

    <p class="error-code">Error <?= $errorCode ?></p>

    <div class="error-message">
        <p><?= htmlspecialchars($error['message']) ?></p>
        <?php if (!empty($errorMessage)): ?>
        <p class="additional-info"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>
    </div>

    <div class="error-actions">
        <p>We apologize for the inconvenience. You can try:</p>

        <div class="action-links">
            <a href="/" class="action-link primary">
                <span class="link-icon">&#8962;</span>
                Return to Homepage
            </a>
            <button onclick="location.reload()" class="action-link secondary">
                <span class="link-icon">&#8635;</span>
                Refresh Page
            </button>
        </div>
    </div>

    <div class="error-debug">
        <p>Debug Hash: <code><?= htmlspecialchars($debugHash) ?></code></p>
        <p class="timestamp">Time: <?= date('Y-m-d H:i:s T') ?></p>
    </div>
</div>

<style>
.error-page.error-50x {
    text-align: center;
    padding: 3rem 1rem;
    max-width: 600px;
    margin: 0 auto;
}

.error-50x .error-icon {
    color: #f59e0b;
    margin-bottom: 1.5rem;
}

.error-50x .error-icon svg {
    opacity: 0.8;
}

.error-50x h1 {
    font-size: 2.5rem;
    color: #e5e7eb;
    margin-bottom: 0.5rem;
}

.error-50x .error-code {
    font-size: 1.25rem;
    color: #f59e0b;
    margin-bottom: 1.5rem;
    font-weight: 600;
}

.error-50x .error-message {
    color: #d1d5db;
    margin-bottom: 2rem;
}

.error-50x .additional-info {
    color: #9ca3af;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

.error-50x .error-actions {
    margin: 2rem 0;
}

.error-50x .error-actions > p {
    color: #9ca3af;
    margin-bottom: 1rem;
}

.error-50x .action-links {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.error-50x .action-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
}

.error-50x .action-link.primary {
    background: #3b82f6;
    color: white;
}

.error-50x .action-link.primary:hover {
    background: #2563eb;
}

.error-50x .action-link.secondary {
    background: rgba(255, 255, 255, 0.1);
    color: #e5e7eb;
}

.error-50x .action-link.secondary:hover {
    background: rgba(255, 255, 255, 0.15);
}

.error-50x .link-icon {
    font-size: 1.25rem;
}

.error-50x .error-debug {
    margin-top: 3rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    font-size: 0.75rem;
    color: #6b7280;
}

.error-50x .error-debug code {
    background: rgba(255, 255, 255, 0.05);
    padding: 0.125rem 0.375rem;
    border-radius: 3px;
}

.error-50x .timestamp {
    margin-top: 0.25rem;
}
</style>
