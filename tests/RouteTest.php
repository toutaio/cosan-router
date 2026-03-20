<?php

declare(strict_types=1);

use Touta\Cosan\Route;
use Touta\Cosan\RouteName;
use Touta\Cosan\RoutePattern;

// Scenario: creating a Route with method, pattern, and handler
it('creates a route with method, pattern, and handler', function (): void {
    $handler = fn(): string => 'ok';
    $route = new Route('GET', new RoutePattern('/users'), $handler);

    expect($route->method)->toBe('GET')
        ->and($route->pattern)->toEqual(new RoutePattern('/users'))
        ->and($route->handler)->toBe($handler);
});

// Scenario: creating a Route with a branded RouteName
it('creates a route with a name', function (): void {
    $route = new Route('POST', new RoutePattern('/users'), fn(): string => 'ok', new RouteName('users.create'));

    expect($route->name)->toEqual(new RouteName('users.create'));
});

// Scenario: Route name defaults to null when not provided
it('has null name by default', function (): void {
    $route = new Route('GET', new RoutePattern('/'), fn(): string => 'ok');

    expect($route->name)->toBeNull();
});
