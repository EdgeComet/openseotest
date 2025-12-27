<?php

declare(strict_types=1);

namespace Ost\Controllers;

use Ost\Response;

/**
 * API controller for beacon tracking and other API endpoints.
 */
class ApiController
{
    /**
     * Valid beacon event names.
     */
    private const VALID_EVENTS = [
        'js-executed',
        'content-injected',
        'ajax-complete',
        'error-occurred',
        'timer-complete',
        'first-injected',
        'second-injected',
    ];

    /**
     * Handle beacon tracking request.
     *
     * Beacons are fire-and-forget signals from JavaScript indicating
     * that specific events have occurred (content injected, AJAX complete, etc.)
     *
     * @param string $hash Debug hash for correlation
     * @param string $event Event name
     * @return string Empty response body
     */
    public function beacon(string $hash, string $event): string
    {
        $response = Response::current();

        // Validate hash format (8 hex characters)
        if (!$this->isValidHash($hash)) {
            $response->setStatusCode(400);
            $response->setHeader('Content-Type', 'text/plain');
            return 'Invalid hash';
        }

        // Validate event name
        if (!$this->isValidEvent($event)) {
            $response->setStatusCode(400);
            $response->setHeader('Content-Type', 'text/plain');
            return 'Invalid event';
        }

        // Log the beacon for debugging
        error_log("Beacon: hash={$hash}, event={$event}");

        // Set headers for successful beacon
        $response->setStatusCode(204);
        $response->setHeader('X-Debug-Hash', $hash);

        // No content - 204 response
        return '';
    }

    /**
     * Check if event name is valid.
     *
     * Valid events are:
     * - Static event names from VALID_EVENTS
     * - ajax-step-{n} pattern where n is a positive integer
     */
    private function isValidEvent(string $event): bool
    {
        // Check static event names
        if (in_array($event, self::VALID_EVENTS, true)) {
            return true;
        }

        // Check ajax-step-{n} pattern
        if (preg_match('/^ajax-step-[1-9][0-9]*$/', $event)) {
            return true;
        }

        return false;
    }

    /**
     * Check if hash matches expected format.
     */
    private function isValidHash(string $hash): bool
    {
        return preg_match('/^[a-f0-9]{8}$/', $hash) === 1;
    }
}
