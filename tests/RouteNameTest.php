<?php

declare(strict_types=1);

use Touta\Cosan\RouteName;

// Scenario: creating a RouteName wraps a string value
it('wraps a route name string', function (): void {
    $name = new RouteName('users.index');

    expect($name->value)->toBe('users.index');
});

// Scenario: two RouteNames with equal values are structurally equal
it('supports equality by value', function (): void {
    $a = new RouteName('users.show');
    $b = new RouteName('users.show');

    expect($a)->toEqual($b);
});
