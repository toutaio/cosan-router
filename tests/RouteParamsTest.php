<?php

declare(strict_types=1);

use Touta\Cosan\RouteParams;

// Scenario: creating RouteParams wraps a parameters array
it('wraps a route parameters array', function (): void {
    $params = new RouteParams(['id' => '42', 'slug' => 'hello']);

    expect($params->value)->toBe(['id' => '42', 'slug' => 'hello']);
});

// Scenario: empty parameters are valid
it('defaults to empty array', function (): void {
    $params = new RouteParams();

    expect($params->value)->toBe([]);
});

// Scenario: two RouteParams with equal values are structurally equal
it('supports equality by value', function (): void {
    $a = new RouteParams(['id' => '1']);
    $b = new RouteParams(['id' => '1']);

    expect($a)->toEqual($b);
});
