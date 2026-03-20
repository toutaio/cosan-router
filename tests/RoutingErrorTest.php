<?php

declare(strict_types=1);

use Touta\Cosan\RoutingError;

// Scenario: creating a RoutingError with code and message
it('creates a routing error with code and message', function (): void {
    $error = new RoutingError(RoutingError::NOT_FOUND, 'No route matched');

    expect($error->code)->toBe('ROUTING.NOT_FOUND')
        ->and($error->message)->toBe('No route matched')
        ->and($error->context)->toBe([]);
});

// Scenario: creating a RoutingError with context
it('creates a routing error with context', function (): void {
    $error = new RoutingError(
        RoutingError::METHOD_NOT_ALLOWED,
        'Method not allowed',
        ['method' => 'DELETE'],
    );

    expect($error->code)->toBe('ROUTING.METHOD_NOT_ALLOWED')
        ->and($error->context)->toBe(['method' => 'DELETE']);
});

// Scenario: withMessage returns a new instance with updated message
it('returns new instance with updated message via withMessage', function (): void {
    $error = new RoutingError(RoutingError::NOT_FOUND, 'original');
    $updated = $error->withMessage('updated');

    expect($updated->message)->toBe('updated')
        ->and($updated->code)->toBe(RoutingError::NOT_FOUND)
        ->and($error->message)->toBe('original');
});

// Scenario: withContext merges additional context
it('merges context via withContext', function (): void {
    $error = new RoutingError(RoutingError::NOT_FOUND, 'msg', ['a' => '1']);
    $updated = $error->withContext(['b' => '2']);

    expect($updated->context)->toBe(['a' => '1', 'b' => '2'])
        ->and($error->context)->toBe(['a' => '1']);
});

// Scenario: all error code constants are defined
it('defines all routing error codes', function (): void {
    expect(RoutingError::NOT_FOUND)->toBe('ROUTING.NOT_FOUND')
        ->and(RoutingError::METHOD_NOT_ALLOWED)->toBe('ROUTING.METHOD_NOT_ALLOWED')
        ->and(RoutingError::INVALID_PATTERN)->toBe('ROUTING.INVALID_PATTERN')
        ->and(RoutingError::MIDDLEWARE_FAILED)->toBe('ROUTING.MIDDLEWARE_FAILED');
});
