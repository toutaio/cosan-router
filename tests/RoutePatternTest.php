<?php

declare(strict_types=1);

use Touta\Cosan\RoutePattern;

// Scenario: creating a RoutePattern wraps a URI pattern string
it('wraps a route pattern string', function (): void {
    $pattern = new RoutePattern('/users/{id}');

    expect($pattern->value)->toBe('/users/{id}');
});

// Scenario: two RoutePatterns with equal values are structurally equal
it('supports equality by value', function (): void {
    $a = new RoutePattern('/users');
    $b = new RoutePattern('/users');

    expect($a)->toEqual($b);
});
